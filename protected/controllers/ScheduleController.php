<?php
class ScheduleController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','create','update','admin','delete','createSchedule','repeatSelected','Search',
                    'CheckFolder','Calendar','Test','ScheduleInfo','GetSharedMembers','GetSharedMembersData', 'view','EditSchedule', 'UpdateJoinStatus'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function beforeAction($action){
         // Check only when the user is logged in
         if (isset($_SESSION['ownerid']))  {
             if ( Yii::app()->user->getState('userSessionTimeout') < time() ) {
               // timeout
			   if(in_array(strtolower($action->getId()),array('view'))){
                 	echo "{'data':'ajaxsessionout'}";
				}else if(in_array(strtolower($action->getId()),array('createschedule','getsharedmembers','getsharedmembersdata','delete','repeatselected','EditSchedule'))){
                 	echo "ajaxsessionout";
				}else{
					Yii::app()->user->logout();
                 	$this->redirect(array('/user/login'));
				}
             } else {
                 Yii::app()->user->setState('userSessionTimeout', time() + Yii::app()->params['sessionTimeoutSeconds']) ;
                 return true; 
            }
         } else {
             return true;
        }
    }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		if(isLogin()){
			$this->redirect(Yii::app()->createUrl('User/login'));
		}else{
			$ownerid = $_SESSION['ownerid'];
			$arr = array(
				'ownerid'=>$ownerid,
				'lastupdatetime'=>'00-00-00 00:00:00'
			);
			
			$cache_myservices = Yii::app()->cache->get($ownerid.'_myservices');
			if($cache_myservices === array() || $cache_myservices){
				$services = $cache_myservices;
			}else{
				$result = $this->rest()->getResponse('services','get',$arr);
				if($result['code'] == 200){
					$services = json_decode($result['response'])->services;
					Yii::app()->cache->set($ownerid.'_myservices',$services,CACHETIME);
				}else $services = array();
			}
			
			$ownSharedRole = array();
			if($services){
				foreach($services as $servicevals){
					// if($servicevals->sharedrole == 0 || $servicevals->sharedrole == 1){
					if($servicevals->sharedrole == 0){
						$ownSharedRole[$servicevals->serviceid] = $servicevals->sharedrole;
					}
				}
			}
			
			$myownservices = array_keys($ownSharedRole);
			// dump($myownservices);exit;

			$cache_members = Yii::app()->cache->get($ownerid.'_mymembers');
			if($cache_members === array() || $cache_members){
				$members = $cache_members;
			}else{
				$m_result = $this->rest()->getResponse('members','get',$arr);
				if($m_result['code'] == 200){
					$members = json_decode($m_result['response'])->members;
					Yii::app()->cache->set($ownerid.'_mymembers',$members,CACHETIME);
				}else $members = array();
			}
			
			// dump($services);exit;
			
			$sharedList = array();
			if($myownservices){
				$serviceid = $myownservices[0];
				// echo $serviceid;exit;
				$cache_sharedMembers = Yii::app()->cache->get($serviceid.'_sharedmembers');
				// $sharedList = array();
				if($cache_sharedMembers === array() || $cache_sharedMembers){
					foreach($cache_sharedMembers as $cache_sharedMembers_vals){
						$sharedList[$cache_sharedMembers_vals->memberid] = $cache_sharedMembers_vals->membername;
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
						
						//dump($sharedmembers_result);exit;
						
						if($sharedmembers_result){
							foreach($sharedmembers_result as $sharedmembers_result_vals){
								$sharedList[$sharedmembers_result_vals->memberid] = $sharedmembers_result_vals->membername;
							}						
							Yii::app()->cache->set($serviceid.'_sharedmembers',$sharedmembers_result,CACHETIME);
						}
					}
				}
			}
			
			// dump($sharedList);
			
			$this->render('createschedule',array(
				'services'=>$services,
				'members'=>$members,
				'sharedList'=>$sharedList
			));
		}
	}
	
	public function actioncreateSchedule(){
		if(isset($_POST['activity']))
		{
			$ownerid = $_SESSION['ownerid'];
			
			$serviceid = $_POST['activity'];
			$desp = $_POST['desp'];
			$start = $_POST['start'];
			$end = $_POST['end'];

			$scheduleid = ($_SESSION['scheduleid'] == 0)?($_SESSION['ownerid'].'0001'):($_SESSION['scheduleid']+1);
			$tzid = $_POST['timezone'];
            $alert = $_POST['alert'];

            $onduties = explode(",",$_POST['onduty']);
            $members = array();
            foreach($onduties as $member){
                array_push($members, array('memberid'=>$member, 'confirm'=>0));
            }

            $timezone = "UTC";
            foreach (getPartTimezones() as $timezonekey => $timezoneval) {
                if($timezonekey==$tzid) {
                    $timezone = $timezoneval;
                    break;
                }
            }

			//$real_start = date('Y-m-d H:i:s',(strtotime($start)));
			//$real_end = date('Y-m-d H:i:s',(strtotime($end)));

            $real_start = (new DateTime($start, new DateTimeZone($timezone)))
                ->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
            $real_end = (new DateTime($end, new DateTimeZone($timezone)))
                ->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');

			$arr = array(
				'ownerid'=>$ownerid,
				'serviceid'=>$serviceid,
				'schedules'=>array(
					'scheduleid'=>$scheduleid,
					'desp'=>$desp,
					'startdatetime'=>$real_start,
					'enddatetime'=>$real_end,
					'tzid'=>$tzid,
					'members'=>$members,
                    'alert'=>$alert
				)
			);

			$result = $this->rest()->getResponse('schedules','post',$arr);
			if($result['code'] == 200){
				$_SESSION['scheduleid'] = $scheduleid;
				
				$myschedules = Yii::app()->cache->get($ownerid.'_myschedules');
                if($myschedules === array() || $myschedules){
                    $myschedules = Yii::app()->cache->get($ownerid.'_myschedules');
                    $addschedule = new stdClass();
                    $addschedule->serviceid = $serviceid;
                    $addschedule->scheduleid = $scheduleid;
                    $addschedule->desp = $desp;
                    $addschedule->startdatetime = $real_start;
                    $addschedule->enddatetime = $real_end;
                    $addschedule->tzid = $tzid;
                    $addschedule->alert = $alert;
                    $addschedule->members = $members;
                    $addschedule->createdtime = date('Y-m-d H:i:s');

                    array_push($myschedules,$addschedule);
                    Yii::app()->cache->set($ownerid.'_myschedules',$myschedules,CACHETIME);
                }
				//success to create schedule
				echo 'ok';
			}else{
				//do something
				echo 'Fail to create the schedule.';
			}
		}	 
	}

	
	public function actionAdmin(){
		if(isLogin()){
			$this->redirect(Yii::app()->createUrl('User/login'));
		}else{
			if(isset($_POST['submit'])){
				$activityselected = $_POST['activityfilter'];
				$participantselected = $_POST['participantfilter'];
				$timeselected = $_POST['timeselectfilter'];
			}else{
				$activityselected = 'all';
				$participantselected = 'all';
				$timeselected = 0;
			}
		
		
			$ownerid = $_SESSION['ownerid'];
			$lastupdatetime = '0000-00-00 00:00:00';
			$arr = array(
				'ownerid'=>$ownerid,
				'lastupdatetime'=>$lastupdatetime
			);
			
			/*  with /Schedules GET	
			$cache_myschedules = Yii::app()->cache->get($ownerid.'_myschedules');
			if($cache_myschedules === array() || $cache_myschedules){
				$schedules = $cache_myschedules;
			}else{
				$result = $this->rest()->getResponse('schedules','get',$arr);
				if($result['code'] == 200){
					$schedules = json_decode($result['response'])->schedules;
					Yii::app()->cache->set($ownerid.'_myschedules',$schedules);
				}
			}*/
			
			$cache_myservices = Yii::app()->cache->get($ownerid.'_myservices');
			$service = array();
			$timezones = getPartTimezones();

			$servicerole = array();
			if($cache_myservices === array() || $cache_myservices){
				$services = $cache_myservices;
				if($services){
					foreach($services as $servicevals){
						$service[$servicevals->serviceid] = $servicevals->servicename;
						//$timezones[$servicevals->serviceid] = $servicevals->utcoff;
						$servicerole[$servicevals->serviceid] = $servicevals->sharedrole;
					}
				}
			}else{
				$s_result = $this->rest()->getResponse('services','get',$arr);
				if($s_result['code'] == 200){
					$services = json_decode($s_result['response'])->services;		
					Yii::app()->cache->set($ownerid.'_myservices',$services,CACHETIME);
					
					if($services){
						foreach($services as $servicevals){
							$service[$servicevals->serviceid] = $servicevals->servicename;
							//$timezones[$servicevals->serviceid] = $servicevals->utcoff;
							$servicerole[$servicevals->serviceid] = $servicevals->sharedrole;
						}
					}
				}
			}
			
			$sharedList = array();
			$emails = array();
			$phones = array();

			$cache_mymembers = Yii::app()->cache->get($ownerid.'_mymembers');
			$member = array();

			if($cache_mymembers === array() || $cache_mymembers){
				$members = $cache_mymembers;
				if($members){
					foreach($members as $membervals){
						$member[$membervals->memberid] = $membervals->membername;
                        $sharedList[$membervals->memberid] = $membervals->membername;
                        $emails[$membervals->memberid] = $membervals->memberemail;
                        $phones[$membervals->memberid] = $membervals->mobilenumber;
					}
				}
			}else{
				$members_result = $this->rest()->getResponse('members','get',$arr);
				if($members_result['code'] == 200){
					$members = json_decode($members_result['response'])->members;
					Yii::app()->cache->set($ownerid.'_mymembers',$members,CACHETIME);
				
					if($members){
						foreach($members as $membervals){
							$member[$membervals->memberid] = $membervals->membername;
                            $sharedList[$membervals->memberid] = $membervals->membername;
                            $emails[$membervals->memberid] = $membervals->memberemail;
                            $phones[$membervals->memberid] = $membervals->mobilenumber;
						}
					}	
				}else $members = array();
			}
			$member[$ownerid.'0000'] = $_SESSION['username'];
			$mixed_members = $member+$sharedList;
			
			$cache_myschedules = Yii::app()->cache->get($ownerid.'_myschedules');
			$schedules = array();
//			if($cache_myschedules === array() || $cache_myschedules){
//				$schedules = $cache_myschedules;
//			}
//            else{
				if($service){
					foreach ($service as $servicekey=>$serviceval) {
						$result = $this->rest()->getResponse('services/'.$servicekey.'/schedules','get',$arr);
						if($result['code'] == 200){
							$schedules = array_merge(json_decode($result['response'])->schedules,$schedules);
						}


                        $result = $this->rest()->getResponse('services/'.$servicekey.'/sharedmembers','get',$arr);

                        if($result['code'] == 200){
                            $sharedmembers_result = json_decode($result['response'])->sharedmembers;
                            if($sharedmembers_result){
                                foreach($sharedmembers_result as $sharedmember){
                                    $member[$sharedmember->memberid] = $sharedmember->membername;
                                    $sharedList[$sharedmember->memberid] = $sharedmember->membername;
                                    $emails[$sharedmember->memberid] = $sharedmember->memberemail;
                                    $phones[$sharedmember->memberid] = $sharedmember->mobilenumber;
                                }
                            }
                        }
					}

                    usort($schedules, array($this, "compareSchedule"));
					Yii::app()->cache->set($ownerid.'_myschedules',$schedules,CACHETIME);			
				}
			//}
			
			// dump($schedules);exit;	

			$this->render('myschedules',array(
				'schedules'=>$schedules,
				'service'=>$service,
				'member'=>$member,
				'members'=>$members,
				'timezones'=>$timezones,
				'sharedList'=>$sharedList,
				'servicerole'=>$servicerole,
				'activityselected'=>$activityselected,
				'participantselected'=>$participantselected,
				'timeselected'=>$timeselected,
				'mixed_members'=>$mixed_members,
				'emails'=>$emails,
				'phones'=>$phones
			));
		}
	}


    private function compareSchedule($a, $b)
    {
        $result =  $a->startdatetime < $b->startdatetime;
        return $result;
    }

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{		
		if(isset($_POST['id']))
		{
			$ownerid = $_SESSION['ownerid'];
			$id = $_POST['id'];
			$serviceid = $_POST['name'];
			$start = $_POST['start'];
			$end = $_POST['end'];
			$desp = $_POST['desp'];
			$members = $_POST['members'];
			$arr = array(
				'ownerid'=>$ownerid,
				'serviceid'=>$serviceid,
				'schedules'=>array(
					'desp'=>$desp,
					'startdatetime'=>$start,
					'enddatetime'=>$end,
					'utcoffset'=>'0',
					// 'members'=>array(850083)
					'members'=>$members
				)
			);
			$cache_member_string = implode(",",$members);
			$result = $this->rest()->getResponse('schedules/'.$id,'put',$arr);
			if($result['code'] == 200){
				$myschedules = Yii::app()->cache->get($ownerid.'_myschedules');
				if($myschedules){
					foreach($myschedules as $schedulevals){
						if($schedulevals->scheduleid == $id){
							$schedulevals->desp = $desp;
							$schedulevals->startdatetime = $start;
							$schedulevals->enddatetime = $end;
							$schedulevals->members = $cache_member_string;
						}
					}
					Yii::app()->cache->set($ownerid.'_myschedules',$myschedules,CACHETIME);
				}
				//success to update schedule
				echo 'ok';
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
		// for($i = 0;$i<10; $i++){
			// $j = 850140+$i;
			// $this->rest()->getResponse('schedules/850169','delete');
		// }
		if(isLogin()){
			$this->redirect(Yii::app()->createUrl('User/login'));
		}else{
			if(isset($_POST['id'])){
				$id = $_POST['id'];
				$ownerid = $_SESSION['ownerid'];
				$result = $this->rest()->getResponse('schedules/'.$id,'delete');
				if($result['code'] == 200){
					$myschedules = Yii::app()->cache->get($ownerid.'_myschedules');
					if($myschedules){
						foreach($myschedules as $schedulekey=>$schedulevals){
							if($schedulevals->scheduleid == $id){
								array_splice($myschedules,$schedulekey,1);
							}
						}
						Yii::app()->cache->set($ownerid.'_myschedules',$myschedules,CACHETIME);
					}
					echo 'ok';
				}else if($result['code'] == 202){
					echo "This schedule can’t be deleted.";
				}
			}
		}
	}
	
	public function actionrepeatSelected(){
		if(isLogin()){
			$this->redirect(Yii::app()->createUrl('User/login'));
		}else{
            if(isset($_POST['activity']))
            {
                $ownerid = $_SESSION['ownerid'];

                $serviceid = $_POST['activity'];
                $desp = $_POST['desc'];
                $tzid = $_POST['tzid'];
                $alert = $_POST['alert'];

                $start_array = $_POST['start'];
                $end_array = $_POST['end'];
                $member_array = $_POST['members'];

                $timezone = "UTC";
                foreach (getPartTimezones() as $timezonekey => $timezoneval) {
                    if($timezonekey==$tzid) {
                        $timezone = $timezoneval;
                        break;
                    }
                }

                for ($i = 0; $i< sizeof($start_array); $i++){
                    $start = $start_array[$i];
                    $end = $end_array[$i];
                    $onduties = explode(",",$member_array[$i]);
                    $scheduleid = ($_SESSION['scheduleid'] == 0)?($_SESSION['ownerid'].'0001'):($_SESSION['scheduleid']+1);

                    $members = array();
                    foreach($onduties as $member){
                        array_push($members, array('memberid'=>$member, 'confirm'=>0));
                    }


                    $real_start = (new DateTime($start, new DateTimeZone($timezone)))
                        ->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
                    $real_end = (new DateTime($end, new DateTimeZone($timezone)))
                        ->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');

                    $arr = array(
                        'ownerid'=>$ownerid,
                        'serviceid'=>$serviceid,
                        'schedules'=>array(
                            'scheduleid'=>$scheduleid,
                            'desp'=>$desp,
                            'startdatetime'=>$real_start,
                            'enddatetime'=>$real_end,
                            'tzid'=>$tzid,
                            'members'=>$members,
                            'alert'=>$alert
                        )
                    );


                    $result = $this->rest()->getResponse('schedules','post',$arr);
                    if($result['code'] == 200){
                        $_SESSION['scheduleid'] = $scheduleid;

                        $myschedules = Yii::app()->cache->get($ownerid.'_myschedules');
                        if($myschedules === array() || $myschedules){
                            $myschedules = Yii::app()->cache->get($ownerid.'_myschedules');
                            $addschedule = new stdClass();
                            $addschedule->serviceid = $serviceid;
                            $addschedule->scheduleid = $scheduleid;
                            $addschedule->desp = $desp;
                            $addschedule->startdatetime = $real_start;
                            $addschedule->enddatetime = $real_end;
                            $addschedule->tzid = $tzid;
                            $addschedule->alert = $alert;
                            $addschedule->members = $members;
                            $addschedule->createdtime = date('Y-m-d H:i:s');

                            array_push($myschedules,$addschedule);
                            Yii::app()->cache->set($ownerid.'_myschedules',$myschedules,CACHETIME);
                        }
                    }
                }
            }
            $this->redirect(Yii::app()->createUrl('Schedule/admin'));
		}
	}
	
	public function actionSearch(){
		if(isLogin()){
			$this->redirect(Yii::app()->createUrl('User/login'));
		}else{
			$schedule_str ='';
			$ownerid = $_SESSION['ownerid'];
			$arr = array(
				'ownerid'=>$ownerid,
				'lastupdatetime'=>'00-00-00 00:00:00'
			);
			$result = $this->rest()->getResponse('schedules','get',$arr);
			if($result['code'] == 200){
				$schedules = json_decode($result['response'])->schedules;
			}else $schedules = false;
			
			$m_result = $this->rest()->getResponse('members','get',$arr);
			$member = array();
			if($m_result['code'] == 200){
				$members = json_decode($m_result['response'])->members;
				if($members){
					foreach($members as $m_vals){
						$member[$m_vals->memberid] = $m_vals->membername;
					}
				}
			}
			
			$service = array();
			$s_result = $this->rest()->getResponse('services','get',$arr);
			if($s_result['code'] == 200){
				//set lastupdatetime session
				$_SESSION['lastupdatetime'] = date('Y-m-d H:i:s');
				$services = json_decode($s_result['response'])->services;		
				if($services){
					foreach($services as $s_vals){
						$service[$s_vals->serviceid] = $s_vals->servicename;
					}
				}
			}
			
			if($schedules){
				
				foreach($schedules as $schedules_vals){
					//get membername
					$arr_members =  explode(',',$schedules_vals->members);
					
					$member_str = '';
					$mem = '';
					$i = 0;
					
					$mem = '';
					
					foreach($arr_members as $member_vals){
						if($i<2){
							$member_str .= "<li name='membersid' id='".$schedules_vals->scheduleid.'_'.$member_vals."'>".$member[$member_vals]."</li>";
						}
							$mem .= "_".$member_vals;
							$i++;
					}
					if($i>1) $member_str .="<span>...</span>" ;
					
					$schedule_str .= "<tr class='a1' id='".$schedules_vals->scheduleid."'><td><input name='ischeck' type='checkbox' id='".$schedules_vals->scheduleid."_".$schedules_vals->serviceid.$mem."_check'/></td><td id='".$schedules_vals->scheduleid."_se'>".$service[$schedules_vals->serviceid]."</td><td id='".$schedules_vals->scheduleid."_st'>".$schedules_vals->startdatetime."</td><td id='".$schedules_vals->scheduleid."_en'>".$schedules_vals->enddatetime."</td><td id='".$schedules_vals->scheduleid."_de'>".$schedules_vals->desp."</td><td id='".$schedules_vals->scheduleid."_me'>".$member_str."</td><td>".$schedules_vals->createdtime."</td><td><img id='".$schedules_vals->scheduleid."_del' src='./images/delete.png' width='16' height='16' onclick=\"deleteSchedule(".$schedules_vals->scheduleid.")\" />&nbsp;&nbsp<img src='./images/smalledit.png' id='".$schedules_vals->scheduleid."_edit' width='16' height='16' onclick=\"editSchedule(".$schedules_vals->scheduleid.")\" /><img src='./images/delete.png' id='".$schedules_vals->scheduleid."_cancle' width='16' height='16' onclick=\"cancleSchedule(".$schedules_vals->scheduleid.")\" style='display:none'/>&nbsp;&nbsp<img src='./images/ok.png' id='".$schedules_vals->scheduleid."_save' width='16' height='16' style='display:none'  onclick=\"saveSchedule('".$schedules_vals->scheduleid."_".$schedules_vals->serviceid.$mem."')\" style='display:none'/></td></tr>"; //scheduleid serviceid  memberid
				}
			}
			echo $schedule_str;
		}
	}
	public function Rest(){
		$rest = new RestClient();
		return $rest;
	}
	
	public function actionCalendar(){
		$this->render('calendar');
	}
	
	public function actionTest(){
		// if(isLogin()){
			// $this->redirect(Yii::app()->createUrl('User/login'));
		// }else{
			$ownerid = $_SESSION['ownerid'];
			$lastupdatetime = '0000-00-00 00:00:00';
			$arr = array(
				'ownerid'=>$ownerid,
				'lastupdatetime'=>$lastupdatetime
			);
			
			$cache_myservices = Yii::app()->cache->get($ownerid.'_myservices');
			$service = array();
			if($cache_myservices === array() || $cache_myservices){
				$services = $cache_myservices;
				if($services){
					foreach($services as $servicevals){
						$service[$servicevals->serviceid] = $servicevals->servicename;
					}
				}
			}else{
				$s_result = $this->rest()->getResponse('services','get',$arr);
				if($s_result['code'] == 200){
					$services = json_decode($s_result['response'])->services;		
					Yii::app()->cache->set($ownerid.'_myservices',$services,CACHETIME);
					
					if($services){
						foreach($services as $servicevals){
							$service[$servicevals->serviceid] = $servicevals->servicename;
						}
					}
				}
			}

			$cache_myschedules = Yii::app()->cache->get($ownerid.'_myschedules');
			$schedules = array();
			if($cache_myschedules === array() || $cache_myschedules){
				$schedules = $cache_myschedules;
			}else{
				if($service){
					foreach ($service as $servicekey=>$serviceval) {
						$result = $this->rest()->getResponse('services/'.$servicekey.'/schedules','get',$arr);
						if($result['code'] == 200){
							$schedules = array_merge(json_decode($result['response'])->schedules,$schedules);
							
						}
					}
					Yii::app()->cache->set($ownerid.'_myschedules',$schedules,CACHETIME);			
				}
			}

			$cal_schedules = array();
			$single_schedule = array(); // single schedule info
			if($schedules){
				foreach($schedules as $cal_vals){
					$single_schedule['scheduleid'] = $cal_vals->scheduleid;
					$single_schedule['activity'] = $service[$cal_vals->serviceid];
					$single_schedule['activityid'] = $cal_vals->serviceid;
					$single_schedule['start'] = $cal_vals->startdatetime;
					array_push($cal_schedules,$single_schedule);
				}
			}
			print_r(json_encode($cal_schedules));
			
		// }
	}
	
	public function actionScheduleInfo(){
		$ownerid = $_SESSION['ownerid'];
		$lastupdatetime = '0000-00-00 00:00:00';
		$arr = array(
			'ownerid'=>$ownerid,
			'lastupdatetime'=>$lastupdatetime
		);
			
		if(isset($_POST['activityid'])){
			$serviceid = $_POST['activityid'];
			$scheduleid = $_POST['scheduleid'];
			
			$cache_myservices = Yii::app()->cache->get($ownerid.'_myservices');
			$service = array();
			if($cache_myservices === array() || $cache_myservices){
				$services = $cache_myservices;
				if($services){
					foreach($services as $servicevals){
						$service[$servicevals->serviceid] = $servicevals->servicename;
					}
				}
			}else{
				$s_result = $this->rest()->getResponse('services','get',$arr);
				if($s_result['code'] == 200){
					$services = json_decode($s_result['response'])->services;		
					Yii::app()->cache->set($ownerid.'_myservices',$services,CACHETIME);
					
					if($services){
						foreach($services as $servicevals){
							$service[$servicevals->serviceid] = $servicevals->servicename;
						}
					}
				}
			}
			
			$cache_mymembers = Yii::app()->cache->get($ownerid.'_mymembers');
			$member = array();
			if($cache_mymembers === array() || $cache_mymembers){
				$members = $cache_mymembers;
				if($members){
					foreach($members as $membervals){
						$member[$membervals->memberid] = $membervals->membername;
					}
				}		
			}else{
				$members_result = $this->rest()->getResponse('members','get',$arr);
				if($members_result['code'] == 200){
					$members = json_decode($members_result['response'])->members;
					Yii::app()->cache->set($ownerid.'_mymembers',$members,CACHETIME);
				
					if($members){
						foreach($members as $membervals){
							$member[$membervals->memberid] = $membervals->membername;
						}
					}	
				}
			}
			
			
			$cache_myschedules = Yii::app()->cache->get($ownerid.'_myschedules');
			// $schedules = array();
			// if($cache_myschedules === array() || $cache_myschedules){
				// $schedules = $cache_myschedules;
			// }else{	
				$result = $this->rest()->getResponse('services/'.$serviceid.'/schedules','get',$arr);
				if($result['code'] == 200){
					$schedules = json_decode($result['response'])->schedules;			
				}else
					$schedules = array();
				
				$schedule_info = array();
				if($schedules){
					foreach($schedules as $schedules_vals){
						if($schedules_vals->scheduleid == $scheduleid){
							$schedule_info['activity'] = $service[$schedules_vals->serviceid];
							$schedule_info['start'] = $schedules_vals->startdatetime;
							$schedule_info['end'] = $schedules_vals->enddatetime;
							$schedule_info['desp'] = $schedules_vals->desp;
							
							$m = explode(",",$schedules_vals->members);
							
							foreach($m as $k=>$v){
								$m[$k] = $member[$v];
							}
							
							$schedule_info['members'] = implode(',',$m);
						}
					}
				}
				echo  json_encode($schedule_info);
				// Yii::app()->cache->set($ownerid.'_myschedules',$schedules);			
			// }	
		}	
	}
	
	/*public function CheckFolder($foldername){
		$basepath = Yii::app()->basePath.'/data/';
		if(is_dir($basepath.$foldername)){
			$this->checkFile($foldername);
		}else{
			mkdir($basepath.$foldername);
			$this->checkFile($foldername);
		}
	}
	
	public function checkFile($foldername){
		$basepath = Yii::app()->basePath.'/data/';
		if(!file_exists($basepath.$foldername."/myschedules.php")){
			touch($basepath.$foldername."/myschedules.php");
		}
		if(!file_exists($basepath.$foldername."/mymembers.php")){
			touch($basepath.$foldername."/mymembers.php");
		}
		if(!file_exists($basepath.$foldername."/activityname.php")){
			touch($basepath.$foldername."/activityname.php");
		}
		if(!file_exists($basepath.$foldername."/membername.php")){
			touch($basepath.$foldername."/membername.php");
		}
		if(!file_exists($basepath.$foldername."/lastupdatetime.txt")){
			touch($basepath.$foldername."/lastupdatetime.txt");
		}
	}*/
	
	public function actionGetSharedMembers(){
        $ownerid = $_SESSION['ownerid'];

        if(isset($_POST['activity'])){
            $serviceid = $_POST['activity'];

            $cache_sharedMembers = Yii::app()->cache->get($serviceid.'_sharedmembers');
            $sharedList = array();
            if($cache_sharedMembers === array() || $cache_sharedMembers){
                foreach($cache_sharedMembers as $cache_sharedMembers_vals){
                    $sharedList[$cache_sharedMembers_vals->memberid] = $cache_sharedMembers_vals->membername;
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
                    if($sharedmembers_result){
                        foreach($sharedmembers_result as $sharedmembers_result_vals){
                            $sharedList[$sharedmembers_result_vals->memberid] = $sharedmembers_result_vals->membername;
                        }
                        Yii::app()->cache->set($serviceid.'_sharedmembers',$sharedmembers_result,CACHETIME);
                    }
                }
            }

            $shared_members_str = "";
            $count = count($sharedList);

            $lines = floor($count/3)+1;

            if($sharedList){
                $i = 1;
                foreach($sharedList as $sharedList_key=>$sharedList_vals){
                    if(($i-1)%3 == 0){
                        $shared_members_str .= "<li class='schbg1'><table width='546' border='0' cellspacing='0' cellpadding='0'><tr>";
                    }

                    $shared_members_str .= "<td width='35' align='center'><input name='contact_check' type='checkbox' id='".$sharedList_key."_check' onclick='is_Checked(".$sharedList_key.")'></td><td width='147' height='35' id='".$sharedList_key."_name'>".$sharedList_vals."</td>";

                    if($lines > 1){
                        if(($i-1)/(3*($lines-1)) == 1 && $count == ($lines-1)*3+1){
                            $shared_members_str .= "<td width='147' height='35'></td><td width='147' height='35'></td>";
                        }

                        if(($i-2)/(3*($lines-1)) == 1 && $count == ($lines-1)*3+2){
                            $shared_members_str .= "<td width='35'></td><td width='147' height='35'></td>";
                        }
                    }else if($lines == 1){
                        if($count == 1 && $i == $count){
                            $shared_members_str .= "<td width='147' height='35'></td><td width='147' height='35'></td>";
                        }

                        if($count == 2 && $i == $count){
                            $shared_members_str .= "<td width='35'></td><td width='147' height='35'></td>";
                        }
                    }


                    if($i%3 == 0 || $i == $count){
                        $shared_members_str .= "</tr></table></li><li class='schbg2'></li>";
                    }

                    $i++;
                }
            }else $shared_members_str .= "<div class='schtable2'>No shared Contacts.</div>";
            echo $shared_members_str;
        }
    }

    public function actionGetSharedMembersData(){
        $ownerid = $_SESSION['ownerid'];

        if(isset($_POST['activity'])){
            $serviceid = $_POST['activity'];

            $cache_sharedMembers = Yii::app()->cache->get($serviceid.'_sharedmembers');
            $data = array();
            if($cache_sharedMembers === array() || $cache_sharedMembers){
                $data = $cache_sharedMembers;
            }else{
                $lastupdatetime = '0000-00-00 00:00:00';
                $arr = array(
                    'ownerid'=>$ownerid,
                    'lastupdatetime'=>$lastupdatetime
                );
                $result = $this->rest()->getResponse('services/'.$serviceid.'/sharedmembers','get',$arr);

                if($result['code'] == 200){
                    $sharedmembers_result = json_decode($result['response'])->sharedmembers;
                    if($sharedmembers_result){
                        $data = $sharedmembers_result;
                        Yii::app()->cache->set($serviceid.'_sharedmembers',$sharedmembers_result,CACHETIME);
                    }
                }
            }


            echo  json_encode($data);
        }
    }


    public function actionView(){
		if(isLogin()){
			$this->redirect(Yii::app()->createUrl('User/login'));
			exit;
		}
		
		if(isset($_POST)){
			$activityid = $_POST['activity'];
			$scheduleid = $_POST['schedule'];
			$ownerid = $_SESSION['ownerid'];
			$cache_myservices = Yii::app()->cache->get($ownerid.'_myservices');

			$services = array();
			$servicename = array();
			
			$arr = array(
					'ownerid'=>$ownerid,
					'lastupdatetime'=>'0000-00-00 00:00:00'
			);



			if($cache_myservices === array() || $cache_myservices){
				$services = $cache_myservices;
			}else{
				$result = $this->rest()->getResponse('services','get',$arr);
				if($result['code'] == 200){
					$services = json_decode($result['response'])->services;
					Yii::app()->cache->set($ownerid.'_myservices',$services,CACHETIME);
				}
			}

			if($services){
				foreach($services as $vals){
					$servicename[$vals->serviceid] = $vals->servicename;
				}
			}

            $member = array();
            $cache_mymembers = Yii::app()->cache->get($ownerid.'_mymembers');

            if($cache_mymembers === array() || $cache_mymembers){
                $members = $cache_mymembers;
            }else{
                $members_result = $this->rest()->getResponse('members','get',$arr);
                if($members_result['code'] == 200){
                    $members = json_decode($members_result['response'])->members;
                    Yii::app()->cache->set($ownerid.'_mymembers',$members,CACHETIME);
                }
            }

            if($members){
                foreach($members as $membervals){
                    $member[$membervals->memberid] = $membervals->membername;
                }
            }

            $schedules = array();


            if($activityid){
                $result = $this->rest()->getResponse('services/'.$activityid.'/schedules','get',$arr);
                if($result['code'] == 200){
                    $schedules = array_merge(json_decode($result['response'])->schedules,$schedules);
                }
            }

            $str = "";
            if($schedules){
                foreach($schedules as $schedulesval){
                    if($schedulesval->scheduleid == $scheduleid){
                        $memberstr = "";
                        $memberIds = "";

                        foreach($schedulesval->members as $mem){
                            if (in_array( $mem->memberid, array_keys($member))){
                                $memberstr .= "<li id=\"".$mem->memberid."_selected\" name=\"selectedmembers\">"
                                    ."<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">"
                                    ."<tr><td>"
                                    ."<span class=\"name\">".$member[$mem->memberid]."</span></td>"
                                    ."<td width=\"25\"><span class=\"cha\" onclick=\"deleteContact(".$mem->memberid.")\" style=\"cursor:pointer;\"></span></td>"
                                    ."</tr></table></li>";
                                $memberIds.= ",".$mem->memberid;
                            }
                        }

                        if (strlen($memberIds) >0){
                            $memberIds = substr($memberIds, 1);
                        }

                        $real_start = (new DateTime($schedulesval->startdatetime, new DateTimeZone('UTC')))
                            ->setTimezone(new DateTimeZone(getTimezoneAbbr($schedulesval->tzid)))->format('m/d/Y h:i A');
                        $real_end = (new DateTime($schedulesval->enddatetime, new DateTimeZone('UTC')))
                            ->setTimezone(new DateTimeZone(getTimezoneAbbr($schedulesval->tzid)))->format('m/d/Y h:i A');

                        $str .= "{'name':'".$servicename[$activityid]
                            ."','start':'".$real_start
                            ."','end':'".$real_end
                            ."','desp':'".$schedulesval->desp
                            ."','alert':'".$schedulesval->alert
                            ."','tzid':'".$schedulesval->tzid
                            ."','memberIds':'".$memberIds
                            ."','onduty':'".$memberstr."'}";
                    }
                }
            }

            echo $str;

        }

	}
	
	public function actionEditSchedule(){
		if(isset($_POST['activity']))
		{
            $ownerid = $_SESSION['ownerid'];

            $serviceid = $_POST['activity'];
            $scheduleid = $_POST['schedule'];
            $desp = $_POST['desp'];
            $start = $_POST['start'];
            $end = $_POST['end'];
            $tzid = $_POST['timezone'];
            $alert = $_POST['alert'];

            $onduties = explode(",",$_POST['onduty']);
            $members = array();
            foreach($onduties as $member){
                array_push($members, array('memberid'=>$member, 'confirm'=>0));
            }

            //对应的member name
            $names = $_POST['names'];
            $timezone = "UTC";
            foreach (getPartTimezones() as $timezonekey => $timezoneval) {
                if($timezonekey==$tzid) {
                    $timezone = $timezoneval;
                    break;
                }
            }
            //$start = date('Y-m-d H:i:s',(strtotime($_POST['start'])));
            //$end = date('Y-m-d H:i:s',(strtotime($_POST['end'])));

            $real_start = (new DateTime($start, new DateTimeZone($timezone)))
                ->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
            $real_end = (new DateTime($end, new DateTimeZone($timezone)))
                ->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');

            $arr = array(
                'ownerid'=>$ownerid,
                'serviceid'=>$serviceid,
                'schedules'=>array(
                    'desp'=>$desp,
                    'startdatetime'=>$real_start,
                    'enddatetime'=>$real_end,
                    'tzid'=>$tzid,
                    'members'=>$members,
                    'alert'=>$alert
                )
            );

            $result = $this->rest()->getResponse('schedules/'.$scheduleid,'put',$arr);
            if($result['code'] == 200){

                $myschedules = Yii::app()->cache->get($ownerid.'_myschedules');
                if($myschedules){
                    foreach($myschedules as $myschedules_vals){
                        if($myschedules_vals->scheduleid == $scheduleid){
                            $myschedules_vals->startdatetime = $start;
                            $myschedules_vals->enddatetime = $end;
                            $myschedules_vals->desp = $desp;
                            $myschedules_vals->members = $members;
                            $myschedules_vals->alert = $alert;
                            $myschedules_vals->tzid = $timezone;
                        }
                    }
                }
				
				Yii::app()->cache->set($ownerid.'_myschedules',$myschedules,CACHETIME);

				$tz = "<br>（".getTimezoneAbbr($tzid)."）";
				$re_start = date("m/d/Y h:i A",strtotime($_POST['start'])).$tz;
				$re_end = date("m/d/Y h:i A",strtotime($_POST['end'])).$tz;



                $emails = array();
                $phones = array();
                $names = array();
                $members_data = array();

                $cache_mymembers = Yii::app()->cache->get($ownerid.'_mymembers');
                if($cache_mymembers === array() || $cache_mymembers){
                    $members_data = $cache_mymembers;

                }else{
                    $members_result = $this->rest()->getResponse('members','get',$arr);
                    if($members_result['code'] == 200){
                        $members_data = json_decode($members_result['response'])->members;
                        Yii::app()->cache->set($ownerid.'_mymembers',$members_data,CACHETIME);
                    }
                }

                if($members_data){
                    foreach($members_data as $membervals){
                        $names[$membervals->memberid] = $membervals->membername;
                        $emails[$membervals->memberid] = $membervals->memberemail;
                        $phones[$membervals->memberid] = $membervals->mobilenumber;
                    }
                }

                $member_str = "";
                foreach($members as $member){
                    $mem_id = $member["memberid"];
                    if ($_SESSION['ownerid'].'0000'==$mem_id){
                        $member_str = "<a "
                            . "' id='" . $scheduleid . '_' . $mem_id
                            . "' title='Name: " . $names[$mem_id] . "\nEmail: " . $emails[$mem_id] . "\nPhone: " . $phones[$mem_id]
                            . "' class='confirm-0"
                            . "' href='javascript:void(0);'"
                            . "' onclick='showConfirm(this);'"
                            . "'>"
                            . $names[$mem_id] . "</a>"
                            .$member_str;
                    }
                    else{
                        $member_str .= "<span name='membersid_" . $scheduleid
                            . "' id='" .$scheduleid . '_' . $mem_id
                            . "' title='Name: " . $names[$mem_id] . "\nEmail: " . $emails[$mem_id] . "\nPhone: " . $phones[$mem_id]
                            . "' class='confirm-0"
                            . "'>"
                            . $names[$mem_id] . "</span>";
                    }
                }
                $return = json_encode(array('status'=>"ok",
                    'start'=>$re_start,
                    'end'=>$re_end,
                    'participant'=>$member_str));
				echo $return;
			}else{
				//do something
				echo 'Fail to create the schedule.';
			}
		}	 
	}

    public function actionUpdateJoinStatus(){
        $ids = explode("_", $_POST['ids']);
        $confirm =  $_POST['confirm'];

        if (is_array($ids) && count($ids)==2){
            $link = 'schedules/'.$ids[0].'/onduty/'.$ids[1];
            $arr = array(
                'ownerid'=> $_SESSION['ownerid'],
                'confirm'=> $confirm
            );

            $result = $this->rest()->getResponse($link, 'put', $arr);
            if($result['code'] == 200){
                echo 'ok';
            }
        }

    }
	
}
