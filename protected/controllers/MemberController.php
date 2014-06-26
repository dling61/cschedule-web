<?php

class MemberController extends Controller
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
				'actions'=>array('index','view','create','createMember','update','delete','admin','GetCSVMembers','GetGmailMembers','GetYahooMembers','SaveMembers'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function beforeAction($action){
         // Check only when the user is logged in
         if(isset($_SESSION['ownerid'])){
             if ( Yii::app()->user->getState('userSessionTimeout') < time() ) {
               // timeout
			   	if(in_array(strtolower($action->getId()),array('createmember','getgmailmembers','getyahoomembers','view','getcsvmembers'))){
                 	echo "{'data':'ajaxsessionout'}";
				}else if(in_array(strtolower($action->getId()),array('delete','savemembers','update'))){
                 	echo "ajaxsessionout";
				}else{
					// Yii::app()->user->logout();
                 	$this->redirect(array('/user/login'));
					// $this->redirect(array('/user/privacy'));
				}
             } else {
                 Yii::app()->user->setState('userSessionTimeout', time() + Yii::app()->params['sessionTimeoutSeconds']) ;
				 // echo $_SESSION['ownerid'];exit;
                 return true; 
            }
        }else{
             return true;
        }
    }

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView()
	{	
		if(isLogin()){
			$this->redirect(Yii::app()->createUrl('User/login'));
			exit;
		}
		
		$id = $_POST['contact'];
		
		$ownerid = $_SESSION['ownerid'];
		$cache_mymembers = Yii::app()->cache->get($ownerid.'_mymembers');
		if($cache_mymembers === array() || $cache_mymembers){
			$members = $cache_mymembers;
		}else{ 		
			$arr = array(
				'ownerid'=>$ownerid,
				'lastupdatetime'=>'0000-00-00 00:00:00'
			);
			$result = $this->rest()->getResponse('members','get',$arr);
			if($result['code'] == 200){
				$members = json_decode($result['response'])->members;
				Yii::app()->cache->set($ownerid.'_mymembers',$members,CACHETIME);
			}else{
				$members = array();
			}
		}
		
		$str = "";
		if($members){
			foreach($members as $vals){
				if($vals->memberid == $id){
					$str .= "{'name':'".my_nl2br($vals->membername)."','email':'".$vals->memberemail."','mobile':'".$vals->mobilenumber."'}";	
				}
			}
		}
		echo $str;
	}

	/*
	* create member
	*/
	public function actionCreate()
	{	if(isLogin()){
			$this->redirect(Yii::app()->createUrl('User/login'));
		}else
			$this->render('createcontacts');
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreateMember()
	{
		if(isset($_POST['email']) && $_POST['email'] != '')
		{
			$ownerid = $_SESSION['ownerid'];	
			//set memberid
			$memberid = ($_SESSION['memberid'] == 0)?($_SESSION['ownerid'].'0001'):($_SESSION['memberid']+1);
			
			$arr = array(
				'ownerid' => $ownerid,
				'members' => array(
					'email'=>$_POST['email'],
					'memberid'=>$memberid,
					'membername'=>$_POST['name'],
					'mobile'=>$_POST['mobile'],
				)
			);
			$result = $this->rest()->getResponse('members','post',$arr);
			if($result['code'] == 200){
				//create member successfully
				//update the memberid session
				$_SESSION['memberid'] = $memberid;
				echo "{'tip':'ok','data':'success to create contact','contactid':'".$memberid."','membername':'".$_POST['name']."','email':'".$_POST['email']."','id':'".$memberid."'}";
				
				$cache_mymembers = Yii::app()->cache->get($ownerid.'_mymembers');
				if(!$cache_mymembers){
					Yii::app()->cache->set($ownerid.'_mymembers',array(),CACHETIME);
				}
				$cache_mymembers = Yii::app()->cache->get($ownerid.'_mymembers');
				if($cache_mymembers === array() || $cache_mymembers){
					$addmember = new stdClass();
					$addmember->memberid = $memberid;
					$addmember->memberemail = $_POST['email'];
					$addmember->membername = $_POST['name'];
					$addmember->mobilenumber = $_POST['mobile'];
					$addmember->createdtime = date('Y-m-d H:i:s');
					array_push($cache_mymembers,$addmember);
					Yii::app()->cache->set($ownerid.'_mymembers',$cache_mymembers,CACHETIME);
				}
			}else if($result['code'] == 201){
				//This member already exists
				echo "{'tip':'fail','data':'This member already exists.'}";
			}
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
		if(isset($_POST))
		{			
			$ownerid = $_SESSION['ownerid'];
			$email = $_POST['email'];
			$mobile = $_POST['mobile'];
			$name = $_POST['name'];
			$id = $_POST['id'];
			$arr = array(
				'ownerid'=>$ownerid,
				'members'=>array(
					'email'=>$email,
					'membername'=>$name,
					'mobile'=>$mobile
				)
			);
			$result  = $this->rest()->getResponse('Members/'.$id,'put',$arr);
			if($result['code'] == 200){
				$cache_mymembers = Yii::app()->cache->get($ownerid."_mymembers");
				if($cache_mymembers){
					foreach($cache_mymembers as $cache_mymembers_vals){
						if($cache_mymembers_vals->memberid == $id){
							$cache_mymembers_vals->memberemail = $email;
							$cache_mymembers_vals->membername = $name;
							$cache_mymembers_vals->mobilenumber = $mobile;
						}
					}
					Yii::app()->cache->set($ownerid.'_mymembers',$cache_mymembers,CACHETIME);
				}				
				echo 'ok';
				//success to update the member
			}else if($result['code'] == 201){
				echo 'Can not update the member.';
				//can not update the member
			}else if($result['code'] == 202){
				//member doesn't exist
				echo 'Member doesn\'t exist.';
			}
		}
	}
	
	
	public function actionAdmin(){
		if(isLogin()){
			$this->redirect(Yii::app()->createUrl('User/login'));
			exit;
		}
		
		$ownerid = $_SESSION['ownerid'];
		
		$cache_mymembers = Yii::app()->cache->get($ownerid."_mymembers");
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
		
		// dump($members);exit;
		
		$this->render('mycontacts',array('members'=>$members));
	}
	/**
	 * Delete member,data in on_duty table.
	 */
	public function actionDelete()
	{	
		$id = $_POST['id'];
		$ownerid = $_SESSION['ownerid'];
		$arr = array(
			'ownerid'=>$ownerid,
			'lastupdatetime'=>'00-00-00 00:00:00'
		);
		
		$cache_myschedules = Yii::app()->cache->get($ownerid.'_myschedules');
		if($cache_myschedules === array() || $cache_myschedules){
			$query = $cache_myschedules;
		}else{
			$result = $this->rest()->getResponse('schedules','get',$arr);
			if($result['code'] == 200){
				$query = json_decode($result['response'])->schedules;
			}else $query = array();
		}
		
		$shared_serviceids = array();
		if($query){
			foreach($query as $vals){
				$vals_arr = explode(',',$vals->members);
				if(in_array($id,$vals_arr)){
					//shared services
					array_push($shared_serviceids,$vals->serviceid);
					
				
					// delete the $id in array $vals_arr
					$key = array_search($id,$vals_arr);
					unset($vals_arr[$key]);
					
					$myschedules = Yii::app()->cache->get($ownerid.'_myschedules');
					
					//if the schedule's member is null,the schedule should be deleted
					if(count($vals_arr) == 0){
						$scheduleresult = $this->rest()->getResponse('schedules/'.$vals->scheduleid,'delete');
						if($scheduleresult['code'] == 200){
						
							foreach($myschedules as $myschedules_key=>$myschedules_vals){
								if($myschedules_vals->scheduleid == $vals->scheduleid){
									array_splice($myschedules,$myschedules_key,1);
								}
							}
							
							Yii::app()->cache->set($ownerid.'_myschedules',$myschedules,CACHETIME);
						}						
					}else{
						$related_arr = array(
							'ownerid'=>$ownerid,
							// 'scheduleid'=>$vals->scheduleid,
							'serviceid'=>$vals->serviceid,
							'schedules'=>array(
								'desp'=>$vals->desp,
								'startdatetime'=>$vals->startdatetime,
								'enddatetime'=>$vals->enddatetime,
								'members'=>$vals_arr,						
							)					
						);
						
						//put method to delete the related members in schedule
						$put_result = $this->rest()->getResponse('schedules/'.$vals->scheduleid,'put',$related_arr);
						
						if($put_result['code'] == 200){
							foreach($myschedules as $myschedules_val){
								if($myschedules_val->scheduleid == $vals->scheduleid){
									$myschedules_val->members = implode(',',$vals_arr);									
								}
							}
							
							Yii::app()->cache->set($ownerid.'_myschedules',$myschedules,CACHETIME);
						}
					
					}	
				}
			}
		}
		
		//delete sharedmembers		
		if($shared_serviceids){
			foreach($shared_serviceids as $serviceid){
				$shared_result = $this->rest()->getResponse('services/'.$serviceid.'/sharedmembers/'.$id,'delete');
				if($shared_result['code'] == 200){
					$cache_sharedMembers = Yii::app()->cache->get($serviceid.'_sharedmembers');
					// dump($cache_sharedMembers);exit;
					
					//delete the sharedmember in cache
					if($cache_sharedMembers){
						foreach($cache_sharedMembers as $cache_sharedMembers_key=>$cache_sharedMembers_vals){
							if($cache_sharedMembers_vals->memberid == $id){
								array_splice($cache_sharedMembers,$cache_sharedMembers_key,1);
							}
						}
					}
					Yii::app()->cache->set($serviceid.'_sharedmembers',$cache_sharedMembers,CACHETIME);
				}
			}
		}
		
		
		
		//delete member
		$member_result = $this->rest()->getResponse('members/'.$id,'delete');
		if($member_result['code'] == 200){
			$cache_mymembers = Yii::app()->cache->get($ownerid.'_mymembers');
			if($cache_mymembers){
				foreach($cache_mymembers as $cache_mymembers_key=>$cache_mymembers_vals){
					if($cache_mymembers_vals->memberid == $id){
						array_splice($cache_mymembers,$cache_mymembers_key,1);
					}
				}
				Yii::app()->cache->set($ownerid.'_mymembers',$cache_mymembers,CACHETIME);
			}
		
			//success to delete the members
			echo 'ok';
		}else if($member_result['code'] == 202){
			//this member can not be deleted
			echo 'this member can not be deleted';
		}
	}

	/**
	 * Performs the AJAX validation.
	 * @param Member $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='member-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/*
	* get Gmail contact list
	* insert the data to table member
	*/
	public function actionGetGmailMembers(){
		set_time_limit(1200);
		if(isset($_POST['email'])){
			$user = $_POST['email'];
			$password = $_POST['psw'];
			
			include_once(Yii::app()->basePath.'/Common/Common.php');
			$common = new Common;
			$contacts = $common->getGmailContacts($user,$password);		
			echo json_encode($contacts);
		}
	}
	
	public function actionSaveMembers(){
		// $successcount = 0;
		// $emailexistcount = 0;
		// $idexistcount = 0;
			
		if(isset($_POST['email'])){
			$ownerid = $_SESSION['ownerid'];
			
			$count = $_POST['count'];
			$email = $_POST['email'];
			$mobile = $_POST['mobile'];
			$name = $_POST['name'];
			
			for($i = 0;$i < $count;$i++){
				$memberid = ($_SESSION['memberid'] == 0)?($_SESSION['ownerid'].'0001'):($_SESSION['memberid']+1);
				$arr = array(
					'ownerid' => $ownerid,
					'members' => array(
						'email'=>$email[$i],
						'memberid'=>$memberid,
						'membername'=>$name[$i],
						'mobile'=>$mobile[$i],
					)
				);
				$result = $this->rest()->getResponse('members','post',$arr);
				
				if($result['code'] == 200){
					$_SESSION['memberid'] = $memberid;
					$cache_members = Yii::app()->cache->get($ownerid.'_mymembers');
					if(!$cache_members){
						Yii::app()->cache->set($ownerid.'_mymembers',array(),CACHETIME);
					}
					$cache_mymembers = Yii::app()->cache->get($ownerid.'_mymembers');
					
					if($cache_mymembers === array() || $cache_mymembers){
						$addmember = new stdClass();
						$addmember->memberid = $memberid;
						$addmember->memberemail = $email[$i];
						$addmember->membername = $name[$i];
						$addmember->mobilenumber = $mobile[$i];
						$addmember->createdtime = date('Y-m-d H:i:s');
						array_push($cache_mymembers,$addmember);
					}
					Yii::app()->cache->set($ownerid.'_mymembers',$cache_mymembers,CACHETIME);
					// $successcount++;
				}
				
				else if($result['code'] == 201){
					// echo $_POST['email'].' already exists';
					// $emailexistcount++;
				}else if($result['code'] == 401){
					// echo "Member Id already exists";
					// $idexistcount++;
				}else{
					// echo 'Busy network, try it later.';
				}
				
			}	
		}
		// echo $successcount." contacts have been imported successfully.";
	}
	
	/*
	* get Yahoo contact list
	* insert the data to table member
	*/
	public function actionGetYahooMembers(){
		// $login = 'tonyding201312';
		// $password = 'CSchedule123';
		
		set_time_limit(1200);
		if(isset($_POST['email'])){
			$login = $_POST['email'];
			$password = $_POST['psw'];
			
			include_once(Yii::app()->basePath.'/Common/Common.php');
			$common = new Common;
			$contacts = $common->getYahooContacts($login,$password);	
			echo json_encode($contacts);
		}
	}
	
	/*
	* read the CSV file and get the contacts
	* insert the data to table member
	*/
	public function actionGetCSVMembers(){
		
    $extend = explode(".",$_FILES["file"]["name"]);

    $key = count($extend)-1;

    $ext = ".".$extend[$key];

    $newfile = time().$ext;

    move_uploaded_file($_FILES["file"]["tmp_name"],"./upload/" . $newfile);

    @unlink($_FILES['file']);

   
		$f = './upload/'.$newfile; 
		$contacts = array();
		$s_contact =array();
		if(file_exists($f)){			//if the file exists
			$file = fopen($f,'r');		//open the file
			if($file){
				// if not the end of the file,loop
				while(! feof($file))	
				{
					$content = explode(',',fgets($file));
					if(isset($content[0]))
						$s_contact['name'] = trim($content[0]);
					else $s_contact['name'] = "";
					if(isset($content[1]))
						$s_contact['email'] = trim($content[1]);
					else $s_contact['email'] = "";
					if(isset($content[2]))
						$s_contact['mobile'] = trim($content[2]);
					else $s_contact['mobile'] = "";
					
					if($s_contact['name'] && $s_contact['email'])
						array_push($contacts,$s_contact);  //put the data into array $contacts(one row is a value)
					// array_push($contacts,fgets($file));  //put the data into array $contacts(one row is a value)
				}
			}
			fclose($file); //close the file
		}
		echo json_encode($contacts);
	}
	
	/*
	* Instantiation of a class RestClient
	*/
	public function Rest(){
		$rest = new RestClient();
		return $rest;
	}
}
