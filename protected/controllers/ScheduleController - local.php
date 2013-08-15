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
				'actions'=>array('index','view','create','update','admin','delete'),
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
	public function actionCreate($serviceid)
	{
		$model=new Schedule;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		//import Common.php
		include_once(dirname(Yii::app()->baseUrl).'protected/Common/Common.php');
		$common = new Common();
		
		if(isset($_POST['Schedule']) && isset($_POST['members']))
		{
			$model->attributes=$_POST['Schedule'];
			$ownerid = $_SESSION['ownerid'];
			
			$members = $_POST['members'];
			
			//create schedule_id
			$query = Yii::app()->db->createCommand("select max(Schedule_Id) as schedule_id from schedule where locate(".$ownerid.",Schedule_Id) = 1")->queryAll();
			if($query){
				//if the owner has created schedule,the schedule id plus 1
				if($query[0]['schedule_id'] != '')
					$model->Schedule_Id = $query[0]['schedule_id']+1;
				//if the owner has no schedule,the schedule id is ownerid+'0001'
				else $model->Schedule_Id = (int)($ownerid."0001");
			}
			
			$model->Service_Id = $serviceid;
			$model->Creator_Id = $_SESSION['ownerid'];
			
			//store utc time
			$model->Start_DateTime = $common->dateToTimestamp($_POST['Schedule']['Start_DateTime']);
			$model->End_DateTime = $common->dateToTimestamp($_POST['Schedule']['End_DateTime']);
			
			if($model->save()){
				//insert the data to onduty
				foreach($members as $vals){
					Yii::app()->db->createCommand("insert into onduty(Service_Id,Schedule_Id,Member_Id,Created_Time) values(".$model->Service_Id.",".$model->Schedule_Id.",".$vals.",".$model->Created_Time.")")->execute();
				}
				$this->redirect(array('view','id'=>$model->Schedule_Id));
			}
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
		
		if(isset($_POST['Schedule']) && isset($_POST['members']))
		{
			$model->attributes=$_POST['Schedule'];
			$members = $_POST['members'];
			if($model->save()){
				foreach($members as $vals){
					Yii::app()->db->createCommand("delete from onduty where Schedule_Id =".$id)->execute();
					Yii::app()->db->createCommand("insert into onduty(Service_Id,Schedule_Id,Member_Id,Created_Time) values(".$model->Service_Id.",".$model->Schedule_Id.",".$vals.",".$model->Created_Time.")")->execute();
				}
				$this->redirect(array('view','id'=>$model->Schedule_Id));
			}
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
		Yii::app()->db->createCommand("delete from onduty where Schedule_Id = ".$id)->execute();
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
		$dataProvider=new CActiveDataProvider('Schedule');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Schedule('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Schedule']))
			$model->attributes=$_GET['Schedule'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Schedule the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Schedule::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Schedule $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='schedule-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
