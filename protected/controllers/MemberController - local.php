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
			'postOnly + delete', // we only allow deletion via POST request
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
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
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
			$model->Creator_Id = $ownerid; //store creator_id
			//create Member_Id
			$query = Yii::app()->db->createCommand("select max(Member_Id) as member_id from member where locate(".$ownerid.",Member_Id) = 1")->queryAll();
			if($query){
				//if the owner has created member,the member id plus 1
				if($query[0]['member_id'] != '')
					$model->Member_Id = $query[0]['member_id']+1;
				//if the owner has no service,the member id is ownerid+'0001'
				else $model->Member_Id = (int)($ownerid."0001");
			}
			
			
			$arr = array(
				'ownerid' => $ownerid,
				'services' => array(
					'serviceid'=>$model->Member_Id,
					'servicename'=>'test service2',
					'desp'=>'test service description2',
					'repeat'=>'1',
					'startdatetime'=>'',
					'enddatetime'=>'',
					'alert'=>'1',
				)
			);
			$result = $this->rest()->getResponse('services','post',$arr);
			print_r($result);exit;
			
			if($model->save())
				$this->redirect(array('view','id'=>$model->Member_Id));
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
			$model->attributes=$_POST['Member'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->Member_Id));
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
		//delete data in table onduty that has relations with the member 
		Yii::app()->db->createCommand("delete from onduty where Member_Id = ".$id)->execute();
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Member',array(
			'criteria'=>array(
				'with'=>array('onduty'),
				'condition'=>'Creator_Id = '.$_SESSION['ownerid'],
				'together'=>true
			)
		));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Member('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Member']))
			$model->attributes=$_GET['Member'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Member the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Member::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
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
			$member_id = $this->setMemberId(); //get Member_Id
			
			Yii::app()->db->createCommand("insert into member(Member_Id,Member_Name,Member_Email,Mobile_Number) values('".$member_id."','".$vals->title."','".$vals->email."','".$vals->mobile."')")->execute(); //insert data to table member
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
			$member_id = $this->setMemberId(); //get Member_Id
			
			Yii::app()->db->createCommand("insert into member(Member_Id,Member_Name,Member_Email,Mobile_Number) values('".$member_id."','".$vals['first_name']."','".$vals['email_1']."','".'111'."')")->execute(); //insert data to table member
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
				
				$member_id = $this->setMemberId(); //get Member_Id
			
				Yii::app()->db->createCommand("insert into member(Member_Id,Member_Name,Member_Email,Mobile_Number) values('".$member_id."','".$members[0]."','".$members[1]."','".$members[2]."')")->execute(); //insert data to table member
			}
		}
		print_r($contacts);
	}
	
	/*
	* set Member_Id
	*/
	public function setMemberId(){
		$ownerid = $_SESSION['ownerid'];
		//create Member_Id
		$query = Yii::app()->db->createCommand("select max(Member_Id) as member_id from member where locate(".$ownerid.",Member_Id) = 1")->queryAll();
		if($query){
			//if the owner has created member,the member id plus 1
			if($query[0]['member_id'] != '')
			$member_id = $query[0]['member_id']+1;
			//if the owner has no service,the member id is ownerid+'0001'
			else $member_id = (int)($ownerid."0001");
		}
		return $member_id;
	}
}
