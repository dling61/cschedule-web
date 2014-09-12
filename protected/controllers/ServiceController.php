<?php

class ServiceController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            // 'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'view', 'create', 'update', 'admin', 'delete', 'getSchedules', 'InsertService', 'getNewServiceId', 'update1', 'AddSharedMembers', 'UpdateSharedMembers', 'UnSharedMembers', 'GetSharedMembers', 'Edit', 'Timezone', 'ServicePath'),
                'users' => array('*'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function beforeAction($action)
    {
        // Check only when the user is logged in
        if (isset($_SESSION['ownerid'])) {
            if (Yii::app()->user->getState('userSessionTimeout') < time()) {
                // timeout
                if (in_array(strtolower($action->getId()), array('view'))) {
                    echo "{'data':'ajaxsessionout'}";
                } else if (in_array(strtolower($action->getId()), array('update', 'delete', 'getsharedmembers', 'addsharedmembers', 'insertservice'))) {
                    echo "ajaxsessionout";
                } else {
                    Yii::app()->user->logout();
                    $this->redirect(array('/user/login'));
                }
            } else {
                Yii::app()->user->setState('userSessionTimeout', time() + Yii::app()->params['sessionTimeoutSeconds']);
                return true;
            }
        } else {
            return true;
        }
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView()
    {
        if (isLogin()) {
            $this->redirect(Yii::app()->createUrl('User/login'));
            exit;
        }

        $id = $_POST['activity'];

        $ownerid = $_SESSION['ownerid'];
        $cache_myservices = Yii::app()->cache->get($ownerid . '_myservices');
        if ($cache_myservices === array() || $cache_myservices) {
            $services = $cache_myservices;
        } else {
            $arr = array(
                'ownerid' => $ownerid,
                'lastupdatetime' => '0000-00-00 00:00:00'
            );
            $result = $this->rest()->getResponse('services', 'get', $arr);
            if ($result['code'] == 200) {
                $services = json_decode($result['response'])->services;
                Yii::app()->cache->set($ownerid . '_myservices', $services, CACHETIME);
            } else {
                $services = array();
            }
        }

        // dump($services);exit;

        $str = "";
        if ($services) {
            foreach ($services as $vals) {
                if ($vals->serviceid == $id) {
                    //$timezone = -28800/3600;
                    //$timezone = ($vals->utcoff)/3600;
                    // $str .= "{'name':'".my_nl2br($vals->servicename)."','start':'".strtotime(substr($vals->startdatetime,0,-3))."','end':'".strtotime(substr($vals->enddatetime,0,-3))."','desp':'".my_nl2br($vals->desp)."','repeat':'".$vals->repeat."','alert':'".$vals->alert."','timezone':'"."-28800"."'}";

                    $str .= "{'name':'" . my_nl2br($vals->servicename)
                        //."','start':'".date('Y-m-d H:i:s',(strtotime($vals->startdatetime)+($timezone*3600)))
                        //."','end':'".date('Y-m-d H:i:s',(strtotime($vals->enddatetime)+$timezone*3600))
                        . "','desp':'" . my_nl2br($vals->desp)
                        //."','repeat':'".$vals->repeat
                        //."','alert':'".$vals->alert
                        //."','timezone':'".$timezone
                        . "'}";
                }
            }
        }
        echo $str;

        /*$detailedservice = array();
        if($services){
            foreach($services as $vals){
                if($vals->serviceid == $id){
                    $detailedservice['name'] = my_nl2br($vals->servicename);
                    $detailedservice['start'] = substr($vals->startdatetime,0,-3);
                    $detailedservice['end'] = substr($vals->enddatetime,0,-3);
                    $detailedservice['desp'] = my_nl2br($vals->desp);
                    $detailedservice['repeat'] = $vals->repeat;
                    $detailedservice['alert'] = $vals->alert;
                }
            }
        }
        echo json_encode($detailedservice);*/
    }

    /*
    * create service
    */
    public function actionCreate()
    {
        if (isLogin()) {
            $this->redirect(Yii::app()->createUrl('User/login'));
        } else
            $this->render('createactivity');
    }

    public function actionServicePath()
    {
        if (isset($_POST['name'])) {
            //ownerid
            $ownerid = $_SESSION['ownerid'];
            $serviceid = ($_SESSION['serviceid'] == 0) ? ($_SESSION['ownerid'] . '0001') : ($_SESSION['serviceid'] + 1);

            // $real_start = date('Y-m-d H:i:s',(strtotime($_POST['starttime'])-($_POST['timezone']*3600)));
            // $real_end = date('Y-m-d H:i:s',(strtotime($_POST['endtime'])-($_POST['timezone']*3600)));
            // echo $real_start;exit;

            $arr = array(
                'ownerid' => $ownerid,
                'services' => array(
                    'serviceid' => $serviceid,
                    'servicename' => $_POST['name'],
                    'desp' => $_POST['desp'],
                    //'repeat'=>0,
                    //'startdatetime'=>'00:00:00 00:00:00',
                    //'enddatetime'=>'00:00:00 00:00:00',
                    // 'repeat'=>$_POST['repeat'],
                    // 'startdatetime'=>$real_start,
                    // 'enddatetime'=>$real_end,
                    //'alert'=>$_POST['alerts'],
                    //'utcoff'=>$_POST['timezone']*3600
                )
            );
            // dump($arr);exit;
            $result = $this->rest()->getResponse('services', 'post', $arr);
            //if success to create service,do something
            if ($result['code'] == 200) {
                //update the serviceid session
                $_SESSION['serviceid'] = $serviceid;

                $myservices = Yii::app()->cache->get($ownerid . '_myservices');
                if (!$myservices) {
                    Yii::app()->cache->set($ownerid . '_myservices', array(), CACHETIME);
                }
                $myservices = Yii::app()->cache->get($ownerid . '_myservices');
                $addservice = new stdClass();
                $addservice->serviceid = $serviceid;
                $addservice->servicename = $_POST['name'];
                $addservice->desp = $_POST['desp'];
                // $addservice->repeat = $_POST['repeat'];
                // $addservice->startdatetime = $real_start;
                // $addservice->enddatetime = $real_end;

                $addservice->repeat = 0;
                $addservice->startdatetime = '00:00:00 00:00:00';
                $addservice->enddatetime = '00:00:00 00:00:00';

                //$addservice->alert = $_POST['alerts'];
                //$addservice->utcoff = $_POST['timezone']*3600;
                $addservice->sharedrole = 0;
                array_push($myservices, $addservice);
                Yii::app()->cache->set($ownerid . '_myservices', $myservices, CACHETIME);


                $ownerid = $_SESSION['ownerid'];
                $creatorId = $ownerid.'0000';
                $sharedMembers = array();
                $sharedMemberIds = array();

                //sharemembers cache
                $cache_sharedMembers = Yii::app()->cache->get($serviceid . '_sharedmembers');
                // print_r($cache_sharedMembers);exit;

                if ($cache_sharedMembers === array() || $cache_sharedMembers) {
                    foreach ($cache_sharedMembers as $cache_sharedMembers_vals) {
                        $sharedMembers[$cache_sharedMembers_vals->memberid] = $cache_sharedMembers_vals->sharedrole;
                        // array_push($sharedMemberIds,$cache_sharedMembers_vals->memberid);

                        if ($cache_sharedMembers_vals->sharedrole == 0) {
                            $creator = $cache_sharedMembers_vals->membername;
                            $creatoremail = $cache_sharedMembers_vals->memberemail;
                            $creatorId = $cache_sharedMembers_vals->memberid;
                        }
                    }
                } else {
                    $lastupdatetime = '0000-00-00 00:00:00';
                    $arr = array(
                        'ownerid' => $ownerid,
                        'lastupdatetime' => $lastupdatetime
                    );
                    $result = $this->rest()->getResponse('services/' . $serviceid . '/sharedmembers', 'get', $arr);

                    if ($result['code'] == 200) {
                        $sharedmembers_result = json_decode($result['response'])->sharedmembers;
                        // dump($sharedmembers_result);exit;

                        if ($sharedmembers_result) {
                            foreach ($sharedmembers_result as $key2 => $vals) {
                                $sharedMembers[$vals->memberid] = $vals->sharedrole;
                                // array_push($sharedMemberIds,$vals->memberid);

                                if ($vals->sharedrole == 0) {
                                    $creator = $vals->membername;
                                    // $creatoremail = $vals->memberemail;
                                }
                            }
                        }
                        Yii::app()->cache->set($serviceid . '_sharedmembers', $sharedmembers_result, CACHETIME);
                    }
                }

                // dump($sharedmembers_result);exit;

                $cache_mymembers = Yii::app()->cache->get($ownerid . "_mymembers");
                if ($cache_mymembers === array() || $cache_mymembers) {
                    $members = $cache_mymembers;
                } else {
                    $arr = array(
                        'ownerid' => $ownerid,
                        'lastupdatetime' => '00-00-00 00:00:00'
                    );
                    $result = $this->rest()->getResponse('members', 'get', $arr);
                    if ($result['code'] == 200) {
                        $members = json_decode($result['response'])->members;
                        Yii::app()->cache->set($ownerid . "_mymembers", $members, CACHETIME);
                    } else {
                        //do something.
                        $members = array();
                    }
                }
                // dump($members);exit;
                $sharedmembers_str = "
    <td valign='top'><span class='fontsize1' style='visibility:hidden;'>On Duty</span></td>
    <td>&nbsp;</td>
    <td>
	<div class='cschbg2'><table width='563' border='0' cellspacing='0' cellpadding='0'>
  <tr>
  <td><div class='cschb'>
    <ul>
	
		 <li>
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td>
                <span class='name'>" . $creator . "</span></td>
            <td width='25'><span class='cha'></span></td>
                </tr>
          </table>
            </li>
			
			<span id='editonduty'>";


                // $str = "<li>
                // <table width='117' border='0' cellspacing='0' cellpadding='0'>
                // <tr>
                // <td width='25'></td>
                // <td width='75' height='25' id='".$ownerid."0000_name'>".$creator."</td>
                // </tr>
                // </table>
                // </li>";
                $str = "";

                if ($members) {
                    foreach ($members as $memberkey => $membersvals) {
                        if ($membersvals->memberid == $creatorId)
                            continue;
                        /*if(in_array($membersvals->memberid,$sharedMemberIds)){
                            $str .= "<li>
            <table width='117' border='0' cellspacing='0' cellpadding='0'>
              <tr>
               <td width='25'><input name='contact_check' type='checkbox' id='".$membersvals->memberid."_check' onclick='is_Checked(".$membersvals->memberid.")' checked></td>
                <td width='75' height='25' id='".$membersvals->memberid."_name'>".$membersvals->membername."</td>
                </tr>
              </table>
                </li>";


                            $sharedmembers_str .= "<li id='".$membersvals->memberid."_selected'>
            <table width='117' border='0' cellspacing='0' cellpadding='0'>
              <tr>
                <td width='75' height='25'><span class='name'>".$membersvals->membername."</span></td>
                      <td width='25'><span class='cha' style='cursor:pointer;' onclick='deleteContact(".$membersvals->memberid.")'></span></td>
                    </tr>
              </table>
                </li>";
                        }else{*/
                        $str .= "<li>
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr>
		   <td width='25'><input name='contact_check' type='checkbox' id='" . $membersvals->memberid . "_check' onclick='is_Checked(" . $membersvals->memberid . ")'></td>
            <td id='" . $membersvals->memberid . "_name'>" . $membersvals->membername . "</td>
			</tr>
          </table>
            </li>";
                        /*}*/


                        //存储member id 和对应的email
                        $str .= "<input type='hidden' id='" . $membersvals->memberid . "_all' value='" . $membersvals->memberemail . "'>";
                        $str .= "<input type='hidden' id='" . $membersvals->memberid . "_allmobile' value='" . $membersvals->mobilenumber . "'>";

                    }
                }

                $sharedmembers_str .= "</span><div class='clear'></div>
		
    </ul>
    </div></td>
    </tr>
</table>
</div>
<div style='width:563px; border-left:1px solid #dbe2e7; border-right:1px solid #dbe2e7; border-bottom:1px solid #dbe2e7; '>&nbsp;&nbsp;<span class='color1'>Please select contacts to add particpants into activity.</span></div>
<div class='memberlist'>
  <ul id='addnewcontact'>" . $str . "</ul>
</div>
</td>
    <td>&nbsp;</td>";

                $sharedmembers_str .= "<input id='sharedActivityid' type='hidden' value='" . $serviceid . "'>";

                echo $sharedmembers_str;

            }
        }
    }

    public function actionInsertService()
    {
        if (isset($_POST['name'])) {
            //ownerid
            $ownerid = $_SESSION['ownerid'];
            $serviceid = ($_SESSION['serviceid'] == 0) ? ($_SESSION['ownerid'] . '0001') : ($_SESSION['serviceid'] + 1);

            $real_start = date('Y-m-d H:i:s', (strtotime($_POST['starttime']) - ($_POST['timezone'] * 3600)));
            $real_end = date('Y-m-d H:i:s', (strtotime($_POST['endtime']) - ($_POST['timezone'] * 3600)));
            // echo $real_start;exit;

            $arr = array(
                'ownerid' => $ownerid,
                'services' => array(
                    'serviceid' => $serviceid,
                    'servicename' => $_POST['name'],
                    'desp' => $_POST['desp'],
                    'repeat' => $_POST['repeat'],
                    'startdatetime' => $real_start,
                    'enddatetime' => $real_end,
                    'alert' => $_POST['alerts'],
                    'utcoff' => $_POST['timezone'] * 3600
                )
            );
            // dump($arr);exit;
            $result = $this->rest()->getResponse('services', 'post', $arr);
            //if success to create service,do something
            if ($result['code'] == 200) {
                //update the serviceid session
                $_SESSION['serviceid'] = $serviceid;

                $myservices = Yii::app()->cache->get($ownerid . '_myservices');
                if (!$myservices) {
                    Yii::app()->cache->set($ownerid . '_myservices', array(), CACHETIME);
                }
                $myservices = Yii::app()->cache->get($ownerid . '_myservices');
                $addservice = new stdClass();
                $addservice->serviceid = $serviceid;
                $addservice->servicename = $_POST['name'];
                $addservice->desp = $_POST['desp'];
                $addservice->repeat = $_POST['repeat'];
                $addservice->startdatetime = $real_start;
                $addservice->enddatetime = $real_end;
                $addservice->alert = $_POST['alerts'];
                $addservice->utcoff = $_POST['timezone'] * 3600;
                $addservice->sharedrole = 0;
                array_push($myservices, $addservice);
                Yii::app()->cache->set($ownerid . '_myservices', $myservices, CACHETIME);
                echo 'ok';
            }
        }
    }

    public function actionEdit()
    {
        $this->render('editactivity');
    }

    public function actionUpdate()
    {
        if (isset($_POST)) {
            $ownerid = $_SESSION['ownerid'];
            $id = $_POST['id'];
            // $start = $_POST['start'].":00";
            // $end = $_POST['end'].":00";
            $desp = $_POST['desp'];
            // $repeat = $_POST['repeat'];
            $repeat = 0;
            //$alerts = $_POST['alerts'];
            $name = $_POST['name'];


            // $start = date('Y-m-d H:i:s',(strtotime($_POST['start'])-($_POST['timezone']*3600)));
            // $end = date('Y-m-d H:i:s',(strtotime($_POST['end'])-($_POST['timezone']*3600)));
            $start = $end = "00:00:00 00:00:00";

            $arr = array(
                'ownerid' => $ownerid,
                'services' => array(
                    'servicename' => $name,
                    "desp" => $desp,
                    "repeat" => $repeat,
                    // "startdatetime"=>$start,
                    // "enddatetime"=>$end,
                    // "alert"=>$alerts,
                    // "utcoff"=>"0"
                )
            );

            $result = $this->rest()->getResponse('services/' . $id, 'put', $arr);

            if ($result['code'] == 200) {
                $myservices = Yii::app()->cache->get($ownerid . '_myservices');
                if ($myservices) {
                    foreach ($myservices as $servicevals) {
                        if ($servicevals->serviceid == $id) {
                            $servicevals->servicename = $name;
                            $servicevals->desp = $desp;
                            $servicevals->repeat = $repeat;
                            // $servicevals->startdatetime = $start;
                            // $servicevals->enddatetime = $end;
                            // $servicevals->alert = $alerts;
                        }
                    }
                    Yii::app()->cache->set($ownerid . '_myservices', $myservices, CACHETIME);
                }

                echo "ok";
                //success to update the service
            } else if ($result['code'] == 201) {
                echo 'can not update the service';
                //can not update the service
            } else if ($result['code'] == 202) {
                //service does not exist
                echo 'service does not exist';
            } else {
                echo 'busy network';
            }
        }
    }


    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete()
    {
        $id = $_POST['id'];
        $ownerid = $_SESSION['ownerid'];
        $result = $this->rest()->getResponse('services/' . $id, 'delete',null,$ownerid);

        if ($result['code'] == 200) {
            $services = Yii::app()->cache->get($ownerid . '_myservices');
            if ($services) {
                foreach ($services as $servicekey => $servicevals) {
                    if ($servicevals->serviceid == $id) {
                        array_splice($services, $servicekey, 1);
                    }
                }
                Yii::app()->cache->set($ownerid . '_myservices', $services, CACHETIME);
            }
            // dump($services);exit;

            $cache_myschedules = Yii::app()->cache->get($ownerid . '_myschedules');
            $schedules = array();
            if ($cache_myschedules === array() || $cache_myschedules) {
                foreach ($cache_myschedules as $cache_myschedules_vals) {
                    if ($cache_myschedules_vals->serviceid == $id) {
                        // $schedules = $cache_myschedules_vals;
                        array_push($schedules, $cache_myschedules_vals);
                    }
                }
            } else {
                $arr = array(
                    'ownerid' => $ownerid,
                    'lastupdatetime' => '0000-00-00 00:00:00'
                );

                $service_result = $this->rest()->getResponse('services/' . $id . '/schedules', 'get', $arr);
                if ($service_result['code'] == 200) {
                    $schedules = json_decode($service_result['response'])->schedules;
                }
            }
            // dump($schedules);exit;
            if ($schedules) {

                foreach ($schedules as $schedulesvals) {

                    $schedules_result = $this->rest()->getResponse('schedules/' . $schedulesvals->scheduleid, 'delete');

                    // echo $schedules_result['code'];

                    //api error-- can not delete schedule
                    // if($schedules_result['code'] == 200){
                    $myschedules = Yii::app()->cache->get($ownerid . '_myschedules');
                    // dump($myschedules);exit;
                    if ($myschedules) {
                        foreach ($myschedules as $schedulekey => $scheduleval) {
                            if ($schedulesvals->scheduleid == $scheduleval->scheduleid) {
                                array_splice($myschedules, $schedulekey, 1);
                            }
                        }
                    }

                    Yii::app()->cache->set($ownerid . '_myschedules', $myschedules, CACHETIME);

                    // dump($myschedules);exit;
                    // echo 'ok';
                    // }
                }
            }


            /*$sharedmembers_cache = Yii::app()->cache->get($id.'_sharedmembers');
            if($sharedmembers_cache === array() || $sharedmembers_cache){
                $sharedmembers = $sharedmembers_cache;
            }else{
                $lastupdatetime = '0000-00-00 00:00:00';
                $arr = array(
                    'ownerid'=>$ownerid,
                    'lastupdatetime'=>$lastupdatetime
                );
                $sharedmembers_result = $this->rest()->getResponse('services/'.$id.'/sharedmembers','get',$arr);
                if($sharedmembers_result['code'] == 200){
                    $sharedmembers = json_decode($sharedmembers_result['response'])->sharedmembers;
                }else{
                    $sharedmembers = array();
                }
            }

            if($sharedmembers){
                foreach($sharedmembers as $sharedmembersvals){
                    $this->rest()->getResponse('services/'.$id.'/sharedmembers/'.$sharedmembersvals->memberid,'delete',NULL,$ownerid);
                }
            }



            if($sharedmembers_cache){
                Yii::app()->cache->delete($id.'_sharedmembers');
            }*/
            // success to delete the service
            echo 'ok';
        } else if ($result['code'] == 202) {
            //this service can’t be deleted
            // echo 'This activity can’t be deleted.';
        } else {
            // echo 'Busy network,try it later.';
        }
    }

    /**
     * get all the services created by the owner
     */
    public function actionAdmin()
    {
        if (isLogin()) {
            $this->redirect(Yii::app()->createUrl('User/login'));
            exit;
        }

        $ownerid = $_SESSION['ownerid'];
        $cache_myservices = Yii::app()->cache->get($ownerid . '_myservices');
        if ($cache_myservices === array() || $cache_myservices) {
            $services = $cache_myservices;
        } else {
            $arr = array(
                'ownerid' => $ownerid,
                'lastupdatetime' => '00-00-00 00:00:00'
            );
            $result = $this->rest()->getResponse('services', 'get', $arr);
            if ($result['code'] == 200) {
                $services = json_decode($result['response'])->services;
                Yii::app()->cache->set($ownerid . '_myservices', $services, CACHETIME);
            } else {
                $services = array();
            }
        }

        // dump($services);exit;

        $this->render('myactivity', array(
            'services' => $services,
        ));
    }

    /*
    * get  the shared members of the specify activity
    * GET
    */

    public function actionGetSharedMembers()
    {
        $ownerid = $_SESSION['ownerid'];
        $creatorId = $ownerid.'0000';

        if (isset($_POST['activityid'])) {
            $serviceid = $_POST['activityid'];

            $sharedMembers = array();
            $sharedMemberIds = array();

            //sharemembers cache
            $cache_sharedMembers = Yii::app()->cache->get($serviceid . '_sharedmembers');
            // print_r($cache_sharedMembers);exit;

            if ($cache_sharedMembers === array() || $cache_sharedMembers) {
                foreach ($cache_sharedMembers as $cache_sharedMembers_vals) {
                    $sharedMembers[$cache_sharedMembers_vals->memberid] = $cache_sharedMembers_vals->sharedrole;
                    array_push($sharedMemberIds, $cache_sharedMembers_vals->memberid);

                    if ($cache_sharedMembers_vals->sharedrole == 0) {
                        $creator = $cache_sharedMembers_vals->membername;
                        $creatoremail = $cache_sharedMembers_vals->memberemail;
                    }
                }
            } else {
                $lastupdatetime = '0000-00-00 00:00:00';
                $arr = array(
                    'ownerid' => $ownerid,
                    'lastupdatetime' => $lastupdatetime
                );
                $result = $this->rest()->getResponse('services/' . $serviceid . '/sharedmembers', 'get', $arr);

                if ($result['code'] == 200) {
                    $sharedmembers_result = json_decode($result['response'])->sharedmembers;
                    // dump($sharedmembers_result);exit;

                    if ($sharedmembers_result) {
                        foreach ($sharedmembers_result as $key2 => $vals) {
                            $sharedMembers[$vals->memberid] = $vals->sharedrole;
                            array_push($sharedMemberIds, $vals->memberid);

                            if ($vals->sharedrole == 0) {
                                $creator = $vals->membername;
                                $creatoremail = $vals->memberemail;
                            }
                        }
                    }
                    Yii::app()->cache->set($serviceid . '_sharedmembers', $sharedmembers_result, CACHETIME);
                }
            }


            $cache_mymembers = Yii::app()->cache->get($ownerid . "_mymembers");
            if ($cache_mymembers === array() || $cache_mymembers) {
                $members = $cache_mymembers;
            } else {
                $arr = array(
                    'ownerid' => $ownerid,
                    'lastupdatetime' => '00-00-00 00:00:00'
                );
                $result = $this->rest()->getResponse('members', 'get', $arr);
                if ($result['code'] == 200) {
                    $members = json_decode($result['response'])->members;
                    Yii::app()->cache->set($ownerid . "_mymembers", $members, CACHETIME);
                } else {
                    //do something.
                    $members = array();
                }
            }
            // dump($members);exit;
            $sharedmembers_str = "
                <td valign='top'>
                    <span class='fontsize1' style='visibility:hidden;'>On Duty</span>
                </td>
                <td>&nbsp;</td>
                <td>
	                <div class='cschbg2'>
	                    <table width='563' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                 <td>
                                    <div class='cschb'>
                                        <ul>

                                            <li>
                                                <table border='0' cellspacing='0' cellpadding='0'>
                                                    <tr>
                                                        <td>
                                                            <span class='name'>" . $creator . "</span>
                                                        </td>
                                                        <td width='25'><span class='cha'></span></td>
                                                    </tr>
                                                </table>
                                            </li>

                                            <span id='editonduty'>";

            $str = "";
            if ($members) {
                foreach ($members as $memberkey => $membersvals) {

                    if ($membersvals->memberid != $creatorId){
                        if (in_array($membersvals->memberid, $sharedMemberIds)) {
                            $str .=
                                "<li>
                                    <table border='0' cellspacing='0' cellpadding='0'>
                                        <tr>
                                            <td width='25'><input name='contact_check' type='checkbox' id='" . $membersvals->memberid . "_check' onclick='is_Checked(" . $membersvals->memberid . ")' checked></td>
                                            <td id='" . $membersvals->memberid . "_name'>" . $membersvals->membername . "</td>
                                        </tr>
                                    </table>
                                </li>";
                            $sharedmembers_str .=
                                "<li id='" . $membersvals->memberid . "_selected'>
                                    <table border='0' cellspacing='0' cellpadding='0'>
                                        <tr>
                                            <td ><span class='name'>" . $membersvals->membername . "</span></td>
                                            <td width='25' onclick='deleteContact(" . $membersvals->memberid . ")'><span class='cha' style='cursor:pointer;'></span></td>
                                        </tr>
                                    </table>
                                </li>";
                        }
                        else {
                            $str .=
                                "<li>
                                    <table border='0' cellspacing='0' cellpadding='0'>
                                        <tr>
                                            <td width='25'><input name='contact_check' type='checkbox' id='" . $membersvals->memberid . "_check' onclick='is_Checked(" . $membersvals->memberid . ")'></td>
                                            <td id='" . $membersvals->memberid . "_name'>" . $membersvals->membername . "</td>
                                        </tr>
                                     </table>
                                </li>";
                        }
                    }

                    //存储member id 和对应的email
                    $str .= "<input type='hidden' id='" . $membersvals->memberid . "_all' value='" . $membersvals->memberemail . "'>";
                    $str .= "<input type='hidden' id='" . $membersvals->memberid . "_allmobile' value='" . $membersvals->mobilenumber . "'>";

                }
            }

            $sharedmembers_str .= "</span><div class='clear'></div>
		
    </ul>
    </div></td>
    </tr>
</table>
</div>
<div style='width:563px; border-left:1px solid #dbe2e7; border-right:1px solid #dbe2e7; border-bottom:1px solid #dbe2e7; '>&nbsp;&nbsp;<span class='color1'>Please select contacts to add particpants into activity.</span></div>
<div class='memberlist'>
  <ul id='addnewcontact'>" . $str . "</ul>
</div>
</td>
    <td>&nbsp;</td>";

            echo $sharedmembers_str;
        }

    }



    /*public function actionGetSharedMembers(){
        $ownerid = $_SESSION['ownerid'];

        if(isset($_POST['activityid'])){
            $serviceid = $_POST['activityid'];

            $sharedMembers = array();
            $sharedMemberIds = array();

            //sharemembers cache
            $cache_sharedMembers = Yii::app()->cache->get($serviceid.'_sharedmembers');
            // print_r($cache_sharedMembers);exit;

            if($cache_sharedMembers === array() || $cache_sharedMembers){
                //sharedmembers 所有的信息
                $sharedmems = $cache_sharedMembers;

                foreach($cache_sharedMembers as $cache_sharedMembers_vals){
                    $sharedMembers[$cache_sharedMembers_vals->memberid] = $cache_sharedMembers_vals->sharedrole;
                    array_push($sharedMemberIds,$cache_sharedMembers_vals->memberid);

                    if($cache_sharedMembers_vals->sharedrole == 0){
                        $creator = $cache_sharedMembers_vals->membername;
                        $creatoremail = $cache_sharedMembers_vals->memberemail;
                        $creator_memberid = $cache_sharedMembers_vals->memberid;
                    }
                }
            }else{
                $lastupdatetime = '0000-00-00 00:00:00';
                $arr = array(
                    'ownerid'=>$ownerid,
                    'lastupdatetime'=>$lastupdatetime
                );
                $result = $this->rest()->getResponse('services/'.$serviceid.'/sharedmembers','get',$arr);

                if($result['code'] == 200){
                    $sharedmembers_result = json_decode($result['response'])->sharedmembers;
                    // dump($sharedmembers_result);exit;

                    if($sharedmembers_result){

                        $sharedmems = $sharedmembers_result;
                        foreach($sharedmembers_result as $key2=>$vals){
                            $sharedMembers[$vals->memberid] = $vals->sharedrole;
                            array_push($sharedMemberIds,$vals->memberid);

                            if($vals->sharedrole == 0){
                                $creator = $vals->membername;
                                $creatoremail = $vals->memberemail;
                                $creator_memberid = $vals->memberid;
                            }
                        }
                    }
                    Yii::app()->cache->set($serviceid.'_sharedmembers',$sharedmembers_result,CACHETIME);
                }
            }

            // dump($sharedmems);exit;

            $cache_mymembers = Yii::app()->cache->get($ownerid."_mymembers");
            $memberids = array();
            if($cache_mymembers === array() || $cache_mymembers){
                $members = $cache_mymembers;
            }else{
                $arr = array(
                    'ownerid'=>$ownerid,
                    'lastupdatetime'=>'00-00-00 00:00:00'
                );
                $result = $this->rest()->getResponse('members','get',$arr);
                if($result['code'] == 200){
                    $members = json_decode($result['response'])->members;
                    Yii::app()->cache->set($ownerid."_mymembers",$members,CACHETIME);
                }else{
                    //do something.
                    $members = array();
                }
            }

            if($members){
                foreach($members as $member_v){
                    array_push($memberids,$member_v->memberid);
                }
            }

            $sharedmembers_str = "";
            if($sharedmems){
                if(substr($creator_memberid,0,-4) == $ownerid){
                    foreach($sharedmems as $sharedmems_vals){
                        if($sharedmems_vals->sharedrole == 0){
                            $sharedmembers_str .= "<li class='sharebg6'><table width='527' border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td width='35'>&nbsp;</td>
    <td width='154' height='33'>".$sharedmems_vals->membername."</td>
    <td width='222'>".$sharedmems_vals->memberemail."</td>
    <td width='116'>Creator</td>
  </tr>
</table>
</li>
<li class='sharebg7'></li>";
                        }else{
                            $sharedmembers_str .= "<li class='sharebg6'><table width='527' border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td width='35'>&nbsp;</td>
    <td width='154' height='33' id='name_".$sharedmems_vals->memberid."'>".$sharedmems_vals->membername."</td>
    <td width='222' id='email_".$sharedmems_vals->memberid."'>".$sharedmems_vals->memberemail."</td>
    <td width='116'><select  id=".$sharedmems_vals->memberid." name = 'selectdMembers' onchange=\"changerole('".$sharedmems_vals->memberid."')\"><option value='-1'>No share</option><option value ='2' ".(($sharedmems_vals->sharedrole == 2)?'selected':'').">Participant</option><option value ='1' ".(($sharedmems_vals->sharedrole == 1)?'selected':'').">Organizer</option></select></td>
  </tr>
</table>
</li>
<li class='sharebg7'></li>";

                        $sharedmembers_str .= "<input type='hidden' name='o_share' id='oshare_".$sharedmems_vals->memberid."' value='".$sharedmems_vals->sharedrole."'>";
                        $sharedmembers_str .= "<input type='hidden' name='n_share' id='nshare_".$sharedmems_vals->memberid."' value='".$sharedmems_vals->sharedrole."'>";
                        }
                    }
                }else{
                    foreach($sharedmems as $sharedmems_vals){
                        $sharedmembers_str .= "<li class='sharebg6'><table width='527' border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td width='35'>&nbsp;</td>
    <td width='154' height='33'>".$sharedmems_vals->membername."</td>
    <td width='222'>".$sharedmems_vals->memberemail."</td>";

                        if(in_array($sharedmems_vals->memberid,$memberids)){
                            $sharedmembers_str .="<td width='116'><select  id=".$sharedmems_vals->memberid." name = 'selectdMembers' onchange=\"changerole('".$sharedmems_vals->memberid."')\"><option value='-1'>No share</option><option value ='2' ".(($sharedmems_vals->sharedrole == 2)?'selected':'').">Participant</option><option value ='1' ".(($sharedmems_vals->sharedrole == 1)?'selected':'').">Organizer</option></select></td>";
                            $sharedmembers_str .= "<input type='hidden' name='o_share' id='oshare_".$sharedmems_vals->memberid."' value='".$sharedmems_vals->sharedrole."'>";
                            $sharedmembers_str .= "<input type='hidden' name='n_share' id='nshare_".$sharedmems_vals->memberid."' value='".$sharedmems_vals->sharedrole."'>";
                        }else{
                            if($sharedmems_vals->sharedrole == 0){
                                $sharedmembers_str .= "<td width='116'>Creator</td>";
                            }else if($sharedmems_vals->sharedrole == 1){
                                $sharedmembers_str .= "<td width='116'>Organizer</td>";
                            }else if($sharedmems_vals->sharedrole == 2){
                                $sharedmembers_str .= "<td width='116'>Participant</td>";
                            }
                        }

                        $sharedmembers_str .="</tr></table></li><li class='sharebg7'></li>";
                        }
                    }

                }

        if($members){
            foreach($members as $membersvals){
                if(!in_array($membersvals->memberid,$sharedMemberIds)){
                    $sharedmembers_str .= "<li class='sharebg6'><table width='527' border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td width='35'>&nbsp;</td>
    <td width='154' height='33' id='name_".$membersvals->memberid."'>".$membersvals->membername."</td>
    <td width='222' id='email_".$membersvals->memberid."'>".$membersvals->memberemail."</td>
    <td width='116'><select  id=".$membersvals->memberid." name = 'selectdMembers' onchange=\"changerole('".$membersvals->memberid."')\"><option value='-1'>No share</option><option value ='2'>Participant</option><option value ='1'>Organizer</option></select></td>
  </tr>
</table>
</li>
<li class='sharebg7'></li>";
                    $sharedmembers_str .= "<input type='hidden' name='o_share' id='oshare_".$membersvals->memberid."' value='-1'>";
                        $sharedmembers_str .= "<input type='hidden' name='n_share' id='nshare_".$membersvals->memberid."' value='-1'>";
                }
            }
        }

            echo $sharedmembers_str;
    }

}*/

    /*
    * add one member to the shared member list
    * POST
    */
    public function actionAddSharedMembers()
    {
        if (isLogin()) {
            $this->redirect(Yii::app()->createUrl('User/login'));
            exit;
        }
        $ownerid = $_SESSION['ownerid'];

        if (isset($_POST['activity'])) {
            $serviceid = $_POST['activity'];
            $members = explode("_", $_POST['members']);
            $names = explode(",", $_POST['names']);
            $emails = explode(",", $_POST['emails']);
            $mobiles = explode(",", $_POST['mobiles']);

            $length = count($members);
            $fail = 0;
            $success = 0;

            $shared = array();

            //sharemembers cache
            $cache_sharedMembers = Yii::app()->cache->get($serviceid . '_sharedmembers');

            //if sharemembers cache exists
            if ($cache_sharedMembers === array() || $cache_sharedMembers) {
                foreach ($cache_sharedMembers as $cache_sharedMembers_vals) {
                    array_push($shared, $cache_sharedMembers_vals->memberid);
                }
            } else {
                //if sharemembers cache doesn't exist,get it from rest api
                $lastupdatetime = '2012-01-01 00:00:00';
                $get_arr = array(
                    'ownerid' => $ownerid,
                    'lastupdatetime' => $lastupdatetime
                );
                $get_result = $this->rest()->getResponse('services/' . $serviceid . '/sharedmembers', 'get', $get_arr);

                if ($get_result['code'] == 200) {
                    $sharedMembers = json_decode($get_result['response'])->sharedmembers;
                    foreach ($sharedMembers as $sharedMembersvals) {
                        array_push($shared, $sharedMembersvals->memberid);
                    }
                    //set the service's sharemembers cache
                    Yii::app()->cache->set($serviceid . '_sharedmembers', $sharedMembers, CACHETIME);
                }
            }

            //the service's sharemember cache
            $cache = Yii::app()->cache->get($serviceid . '_sharedmembers');

            //原来sharedmembers 再次share
            $shareagain = array();

            //add the new sharemembers to the server
            for ($i = 0; $i < $length; $i++) {
                $memberid = $members[$i];
                $sharedemail = $emails[$i];
                $sharedname = $names[$i];
                $sharedmobile = $mobiles[$i];

                $arr = array(
                    'ownerid' => $ownerid,
                    'memberid' => $memberid,
                    'sharedrole' => 2
                );
                // if($sharedrole > 0){
                //if the members' role is not 'NoShare' and it has not been shared before,post the data to sharemembers rest api to add a new sharemember.
                if (!in_array($memberid, $shared)) {
                    $result = $this->rest()->getResponse('services/' . $serviceid . '/sharedmembers', 'post', $arr);
                    if ($result['code'] == 200) {
                        $success++;

                        //add the new sharemember to the sharemembers cache
                        if ($cache === array() || $cache) {
                            $addsharedmember = new stdClass();
                            $addsharedmember->memberid = $memberid;
                            $addsharedmember->memberemail = $sharedemail;
                            $addsharedmember->membername = $sharedname;

                            $addsharedmember->mobilenumber = $sharedmobile;

                            $addsharedmember->sharedrole = 2;
                            array_push($cache, $addsharedmember);
                            Yii::app()->cache->set($serviceid . '_sharedmembers', $cache, CACHETIME);
                        }

                    } else if ($result['code'] == 201) {
                        // echo "Can't share activity with this email.";
                        $fail++;
                    } else {
                        //echo 'Busy network, try it later.';
                        $fail++;
                    }
                } else if (in_array($memberid, $shared)) {
                    array_push($shareagain, $memberid);
                }

                /*else{
                    // if the members' role is not 'Noshare' and it has been shared before, use PUT rest api to modify the sharemembers' data.
                    $put_arr = array(
                        'ownerid'=>$ownerid,
                        'sharedrole'=>$sharedrole
                    );
                    $put_result = $this->rest()->getResponse('services/'.$serviceid.'/sharedmembers/'.$memberid,'put',$put_arr);
                    if($put_result['code'] == 200){
                        $success++;

                        //update sharedmembers cache;
                        if($cache === array() || $cache){
                            foreach($cache as $cachevals){
                                if($memberid == $cachevals->memberid){
                                    $cachevals->sharedrole = $sharedrole;
                                }
                            }
                        }

                        Yii::app()->cache->set($serviceid.'_sharedmembers',$cache,CACHETIME);

                    }else if($put_result['code'] == 201){
                        // echo "Can’t update sharing role with this member on the activity.";
                        $fail++;
                    }else{
                        // echo 'Busy network, try it later.';
                        $fail++;
                    }

                }
                //if the members' sharerole is 'NoShare' and it has been shared before, do the unshare operation
            }else if($sharedrole < 0 && in_array($memberid,$shared)){
                if($this->UnSharedMembers($serviceid,$memberid))
                    // $success++;
                    echo "success to unshare the member.";
            }*/
            }

            //unsharedmembers
            $unsharedmemberids = array_diff($shared, $shareagain);
            if ($unsharedmemberids) {
                foreach ($unsharedmemberids as $unsharedmemberidsvals) {
                    if ($unsharedmemberidsvals != $ownerid . '0000') {
                        $this->UnSharedMembers($serviceid, $unsharedmemberidsvals);
                    }
                }
            }

            echo "success to share the activity to " . $success . " members";
        }
    }

    /*
    * update  sharedMembers
    * PUT
    */
    public function actionUpdateSharedMembers()
    {
        if (isLogin()) {
            $this->redirect(Yii::app()->createUrl('User/login'));
            exit;
        }

        $ownerid = $_SESSION['ownerid'];
        $serviceid = 850060;
        $memberid = 85004046;
        $sharedrole = 2;
        $arr = array(
            'ownerid' => $ownerid,
            'sharedrole' => $sharedrole
        );
        $result = $this->rest()->getResponse('services/' . $serviceid . '/sharedmembers/' . $memberid, 'put', $arr);
        if ($result['code'] == 200) {
            dump($result['response']);
        } else if ($result['code'] == 201) {
            echo "Can’t update sharing role with this member on the activity.";
        } else {
            echo 'Busy network, try it later.';
        }
    }

    /*
    * delete  sharedMembers
    * delete
    */
    public function UnSharedMembers($serviceid, $memberid)
    {
        $ownerid = $_SESSION['ownerid'];

        $result = $this->rest()->getResponse('services/' . $serviceid . '/sharedmembers/' . $memberid, 'delete', NULL, $ownerid);
        if ($result['code'] == 200) {
            //update the sharedmembers cache --  delete

            $shared_cache = Yii::app()->cache->get($serviceid . '_sharedmembers');
            if ($shared_cache) {
                foreach ($shared_cache as $shared_id => $sharedvals) {
                    if ($sharedvals->memberid == $memberid) {
                        array_splice($shared_cache, $shared_id, 1);
                    }
                }
                Yii::app()->cache->set($serviceid . '_sharedmembers', $shared_cache, CACHETIME);
            }

            //delete the associated schedule member
            $lastupdatetime = '0000-00-00 00:00:00';
            $arr = array(
                'ownerid' => $ownerid,
                'lastupdatetime' => $lastupdatetime
            );

            $schedule_result = $this->rest()->getResponse('services/' . $serviceid . '/schedules', 'get', $arr);
            if ($schedule_result['code'] == 200) {
                //check if this shared member is assigned to schedules

                $schedule_response = json_decode($schedule_result['response'])->schedules;
                if ($schedule_response) {
                    foreach ($schedule_response as $schedule_vals) {
                        $members = array();
                        foreach($schedule_vals->members as $mem){
                            array_push($members, $mem->memberid);
                        }

                        // if this shared member is assigned to schedules,delete the schedule if the shared member is the only member in the schedule
                        if (in_array($memberid, $members) && count($members) == 1) {

                            $delete_schedule_result = $this->rest()->getResponse('schedules/' . $schedule_vals->scheduleid, 'delete');
                            if ($delete_schedule_result['code'] == 200) {
                                $myschedules = Yii::app()->cache->get($ownerid . '_myschedules');
                                if ($myschedules) {
                                    foreach ($myschedules as $schedulekey => $schedulevals) {
                                        if ($schedulevals->scheduleid == $schedule_vals->scheduleid) {
                                            array_splice($myschedules, $schedulekey, 1);
                                        }
                                    }
                                    Yii::app()->cache->set($ownerid . '_myschedules', $myschedules, CACHETIME);
                                }
                                echo 'ok';
                            } else if ($delete_schedule_result['code'] == 202) {
                                echo "This schedule can’t be deleted.";
                            }

                        } else if (in_array($memberid, $members) && count($members) > 1) {
                            // if this shared member is assigned to schedules,update the schedule's members
                            $schedules_arr = array(
                                'ownerid' => $ownerid,
                                'lastupdatetime' => '00-00-00 00:00:00'
                            );

                            $cache_myschedules = Yii::app()->cache->get($ownerid . '_myschedules');
                            if ($cache_myschedules === array() || $cache_myschedules) {
                                $query = $cache_myschedules;
                            } else {
                                $schedules_result = $this->rest()->getResponse('services/' . $serviceid . '/schedules', 'get', $schedules_arr);
                                if ($schedules_result['code'] == 200) {
                                    $query = json_decode($schedules_result['response'])->schedules;
                                } else $query = array();
                            }
                            if ($query) {
                                foreach ($query as $vals) {
                                    // print_r($vals->members);exit;
                                    $vals_arr = array();
                                    foreach($vals->members as $mem){
                                        array_push($vals_arr, $mem->memberid);
                                    }

                                    if (in_array($memberid, $vals_arr)) {
                                        // delete the $memberid in array $vals_arr
                                        $key = array_search($memberid, $vals_arr);
                                        unset($vals_arr[$key]);

                                        $related_arr = array(
                                            'ownerid' => $ownerid,
                                            // 'scheduleid'=>$vals->scheduleid,
                                            'serviceid' => $vals->serviceid,
                                            'schedules' => array(
                                                'desp' => $vals->desp,
                                                'startdatetime' => $vals->startdatetime,
                                                'enddatetime' => $vals->enddatetime,
                                                'members' => $vals_arr,
                                            )
                                        );

                                        //put method to delete the related data in table on_duty
                                        $put_result = $this->rest()->getResponse('schedules/' . $vals->scheduleid, 'put', $related_arr);
                                        if ($put_result['code'] == 200) {
                                            $myschedules2 = Yii::app()->cache->get($ownerid . '_myschedules');
                                            if ($myschedules2) {
                                                foreach ($myschedules2 as $schedulevals2) {
                                                    if ($schedulevals2->scheduleid == $vals->scheduleid) {
                                                        $schedulevals2->desp = $vals->desp;
                                                        $schedulevals2->startdatetime = $vals->startdatetime;
                                                        $schedulevals2->enddatetime = $vals->enddatetime;
                                                        $schedulevals2->members = implode(',', $vals_arr);
                                                    }
                                                }
                                                Yii::app()->cache->set($ownerid . '_myschedules', $myschedules2, CACHETIME);
                                            }
                                        }

                                    }
                                }
                            }

                            // dump($myschedules2);exit;

                        }
                    }
                }
            }

        } else if ($result['code'] == 201) {
            echo "This shared member can't be deleted.";
        } else {
            echo 'Busy network, try it later.';
        }
    }

    /**
     * Performs the AJAX validation.
     * @param Service $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'service-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /*
    * Instantiation of a class RestClient
    */
    public function Rest()
    {
        $rest = new RestClient();
        return $rest;
    }

    /*
    * get all the schedules that can be deleted
    */
    /*public function actiongetSchedules(){
        $ownerid = $_SESSION['ownerid'];
        $arr = array(
            'ownerid'=>$ownerid,
            'lastupdatetime'=>'00-00-00 00:00:00'
        );
        $result = $this->rest()->getResponse('schedules','get',$arr);
        echo "<pre>";
        print_r(json_decode($result['response'])->schedules);
        echo "</pre>";
        exit;
    }*/

    /*
    * get the newest serviceid
    */
    /*public function actiongetNewServiceId(){
        $ownerid = $_SESSION['ownerid'];
        $arr = array(
            'ownerid'=>$ownerid,
            'lastupdatetime'=>$_SESSION['lastupdatetime'],
        );
        $result = $this->rest()->getResponse('services','get',$arr);
        $services = json_decode($result['response'])->services;
        $maxserviceid = $_SESSION['serviceid'];
        if($services){
            foreach($services as $vals){
                if($vals->serviceid > $maxserviceid){
                    $maxserviceid = $vals->serviceid;
                }
            }
        }
        echo $maxserviceid+1;
    }*/

    function actionTimezone()
    {
        $time = $_POST['time'];
        $utcoffset = $_POST['utcoffset'];
        $realdate = date("Y-m-d H:i:s", (($time / 1000) + $utcoffset * 3600));
        echo $realdate;
    }

}
