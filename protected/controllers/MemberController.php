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
				'actions'=>array('index','view','create','update','delete','admin','GetCSVMembers','GetGmailMembers','GetYahooMembers'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView()
	{
		$ownerid = $_SESSION['ownerid'];
		$arr = array(
			'ownerid'=>$ownerid,
			'lastupdatetime'=>'00-00-00 00:00:00'
		);
		$result = $this->rest()->getResponse('members','get',$arr);
		print_r($result);exit;
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Member;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Member']))
		{
			$model->attributes=$_POST['Member'];
			$ownerid = $_SESSION['ownerid'];	
			//set memberid
			$memberid = ($_SESSION['memberid'] == 0)?($_SESSION['ownerid'].'0001'):($_SESSION['memberid']+1);
			
			$arr = array(
				'ownerid' => $ownerid,
				'members' => array(
					'email'=>'littleworm1@qq.com',
					'memberid'=>$memberid,
					'membername'=>'littleworm1',
					'mobile'=>'123456789',
				)
			);
			$result = $this->rest()->getResponse('members','post',$arr);
			if($result['code'] == 200){
				//create member successfully
				//update the memberid session
				$_SESSION['memberid'] = $memberid;
			}else if($result['code'] == 201){
				//This member already exists
			}
			print_r($result);exit;
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Member']))
		{
			// $model->attributes=$_POST['Member'];
			$ownerid = $_SESSION['ownerid'];
			$arr = array(
				'ownerid'=>$ownerid,
				'members'=>array(
					'email'=>'littleworm1@qq.com',
					'membername'=>'littleworm1/update',
					'mobile'=>'987654321'
				)
			);
			$result  = $this->rest()->getResponse('Members/'.$id,'put',$arr);
			if($result['code'] == 200){
				//success to update the member
			}else if($result['code'] == 201){
				//can not update the member
			}else if($result['code'] == 202){
				//member doesn't exist
			}
			print_r($result);exit;
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{		
		$ownerid = $_SESSION['ownerid'];
		$arr = array(
			'ownerid'=>$ownerid,
			'lastupdatetime'=>'00-00-00 00:00:00'
		);
		$result = $this->rest()->getResponse('schedules','get',$arr);
		
		$query = json_decode($result['response'])->schedules;
		foreach($query as $vals){
			$vals_arr = explode(',',$vals->members);
			if(in_array($id,$vals_arr)){
				// delete the $id in array $vals_arr
				$key = array_search($id,$vals_arr);
				unset($vals_arr[$key]);
				//if the schedule's member is null,the schedule should be deleted
				if(count($vals_arr) == 0){
					$this->rest()->getResponse('schedules/'.$vals->scheduleid,'delete');
				}
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
				
				//put method to delete the related data in table on_duty
				$put_result = $this->rest()->getResponse('schedules/'.$vals->scheduleid,'put',$related_arr);
			}
		}
		//delete member
		$member_result = $this->rest()->getResponse('members/'.$id,'delete');
		if($member_result['code'] == 200){
			//success to delete the members
			echo 'xxx';
		}else if($member_result['code'] == 202){
			//this member can not be deleted
		}
	}

	/**
	 * get all the members created by the owner
	 */
	public function actionIndex()
	{
		$ownerid = $_SESSION['ownerid'];
		$arr = array(
			'ownerid'=>$ownerid,
			'lastupdatetime'=>'00-00-00 00:00:00'
		);
		$result = $this->rest()->getResponse('members','get',$arr);
		print_r($result);exit;
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
		$user = '';
		$password = '';
		include_once(Yii::app()->basePath.'/Common/Common.php');
		$common = new Common;
		$contacts = $common->getGmailContacts($user,$password);		

		foreach($contacts as $vals){
			$ownerid = $_SESSION['ownerid'];
			$memberid = ($_SESSION['memberid'] == 0)?($_SESSION['ownerid'].'0001'):($_SESSION['memberid']+1);
			$arr = array(
				'ownerid' => $ownerid,
				'members' => array(
					'email'=>$vals->email,
					'memberid'=>$memberid,
					'membername'=>$vals->title,
					'mobile'=>$vals->mobile,
				)
			);
			$result = $this->rest()->getResponse('members','post',$arr);	
			$_SESSION['memberid'] = $memberid;
		}
	}
	
	/*
	* get Yahoo contact list
	* insert the data to table member
	*/
	public function actionGetYahooMembers(){
		$login = 'littlewormqura@yahoo.com';
		$password = 'Wn555666999';
		include_once(Yii::app()->basePath.'/Common/Common.php');
		$common = new Common;
		$contacts = $common->getYahooContacts($login,$password);	
		
		foreach($contacts as $vals){
			$ownerid = $_SESSION['ownerid'];
			$memberid = ($_SESSION['memberid'] == 0)?($_SESSION['ownerid'].'0001'):($_SESSION['memberid']+1);
			$arr = array(
				'ownerid' => $ownerid,
				'members' => array(
					'email'=>$vals['email_1'],
					'memberid'=>$memberid,
					'membername'=>$vals['first_name'],
					'mobile'=>'111',
				)
			);
			$result = $this->rest()->getResponse('members','post',$arr);	
			$_SESSION['memberid'] = $memberid;
			echo $result['code'];
		}
	}
	
	/*
	* read the CSV file and get the contacts
	* insert the data to table member
	*/
	public function actionGetCSVMembers(){
		$f = 'C:\Users\dell\Desktop\qq.csv'; 
		$contacts = array();
		if(file_exists($f)){			//if the file exists
			$file = fopen($f,'r');		//open the file
			if($file){
				// if not the end of the file,loop
				while(! feof($file))	
				{
					array_push($contacts,fgets($file));  //put the data into array $contacts(one row is a value)
				}
			}
			fclose($file); //close the file
		}
		
		foreach($contacts as $vals){
			if($vals){
				$members = explode(',',$vals);
				
				$ownerid = $_SESSION['ownerid'];
				$memberid = ($_SESSION['memberid'] == 0)?($_SESSION['ownerid'].'0001'):($_SESSION['memberid']+1);
				$arr = array(
					'ownerid' => $ownerid,
					'members' => array(
						'email'=>$members[1],
						'memberid'=>$memberid,
						'membername'=>$members[0],
						'mobile'=>$members[2],
					)
				);
				$result = $this->rest()->getResponse('members','post',$arr);	
				$_SESSION['memberid'] = $memberid;
				echo $result['code'];
			}
		}
		print_r($contacts);
	}
	
	/*
	* Instantiation of a class RestClient
	*/
	public function Rest(){
		$rest = new RestClient();
		return $rest;
	}
}
