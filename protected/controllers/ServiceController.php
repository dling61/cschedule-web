<?php
class ServiceController extends Controller
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
				'actions'=>array('index','view','create','update','admin','delete','getSchedules'),
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
			'lastupdatetime'=>'2013-08-07 03:42:04'
		);
		$result = $this->rest()->getResponse('services','get',$arr);
		echo '<pre>';
		print_r($result);
		echo '</pre>';
		// $this->render('view',array(
			// 'model'=>$this->loadModel($id),
		// ));
	}

	/*
	* create service
	*/
	public function actionCreate()
	{
		$model=new Service;
		//import Common.php
		include_once(dirname(Yii::app()->baseUrl).'protected/Common/Common.php');
		$common = new Common();
		
		if(isset($_POST['Service']))
		{ 	
			// $model->attributes=$_POST['Service'];
			
			//ownerid
			$ownerid = $_SESSION['ownerid'];
			$serviceid = ($_SESSION['serviceid'] == 0)?($_SESSION['ownerid'].'0001'):($_SESSION['serviceid']+1);
			
			//store utc time
			// $model->Start_Datetime = $common->dateToTimestamp($_POST['Service']['Start_Datetime']);
			// $model->End_Datetime = $common->dateToTimestamp($_POST['Service']['End_Datetime']);
			
			$arr = array(
				'ownerid' => $ownerid,
				'services' => array(
					'serviceid'=>$serviceid,
					'servicename'=>'test service2',
					'desp'=>'test service description2',
					'repeat'=>'1',
					'startdatetime'=>'',
					'enddatetime'=>'',
					'alert'=>'1',
				)
			);
			$result = $this->rest()->getResponse('services','post',$arr);
			// if success to create service,do something
			if($result['code'] == 200){
				//do something
				//update the serviceid session
				$_SESSION['serviceid'] = $serviceid;
			}
			else{
				//do something
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
		//import Common.php
		include_once(dirname(Yii::app()->baseUrl).'protected/Common/Common.php');
		$common = new Common();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Service']))
		{
			// $model->attributes=$_POST['Service'];
			//store utc time
			// $model->Start_Datetime = $common->dateToTimestamp($_POST['Service']['Start_Datetime']);
			// $model->End_Datetime = $common->dateToTimestamp($_POST['Service']['End_Datetime']);
			$ownerid = $_SESSION['ownerid'];
			
			$arr = array(
				'ownerid'=>$ownerid,
				'services'=>array(
					'servicename'=>'test service2 update',
					'desp'=>'test service description2 update',
					'repeat'=>'2',
					'startdatetime'=>'',
					'enddatetime'=>'',
					'alert'=>'2'
				)
			);
			$result = $this->rest()->getResponse('services/'.$id,'put',$arr);
			if($result['code'] == 200){
				//success to update the service
			}else if($result['code'] == 201){
				//can not update the service
			}else if($result['code'] == 202){
				//service does not exist
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
		$result = $this->rest()->getResponse('services/'.$id,'delete');
		if($result['code'] == 200){
			// success to delete the service
		}else if($result['code'] == 202){
			// this service can’t be deleted
		}
		print_r($result);exit;
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		// if(!isset($_GET['ajax']))
			// $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * get all the services created by the owner
	 */
	public function actionIndex()
	{
		$ownerid = $_SESSION['ownerid'];
		$arr = array(
			'ownerid'=>$ownerid,
			'lastupdatetime'=>'00-00-00 00:00:00'
		);
		$result = $this->rest()->getResponse('services','get',$arr);
		print_r($result);exit;
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Service('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Service']))
			$model->attributes=$_GET['Service'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Performs the AJAX validation.
	 * @param Service $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='service-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/*
	* Instantiation of a class RestClient
	*/
	public function Rest(){
		$rest = new RestClient();
		return $rest;
	}
	
	/*
	* get all the schedules that can be deleted
	*/
	public function actiongetSchedules(){
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
	}
}
