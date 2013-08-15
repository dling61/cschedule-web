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
	public function actionView(){
		$ownerid = $_SESSION['ownerid'];
		$arr = array(
			'ownerid'=>$ownerid,
			'lastupdatetime'=>'00-00-00 00:00:00'
		);
		$result = $this->rest()->getResponse('schedules','get',$arr);
		print_r($result);exit;
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Schedule;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		//import Common.php
		include_once(dirname(Yii::app()->baseUrl).'protected/Common/Common.php');
		$common = new Common();
		
		if(isset($_POST['Schedule']) && isset($_POST['members']))
		{
			// $model->attributes=$_POST['Schedule'];
			$ownerid = $_SESSION['ownerid'];
			$scheduleid = ($_SESSION['scheduleid'] == 0)?($_SESSION['ownerid'].'0001'):($_SESSION['scheduleid']+1);
			$arr = array(
				'ownerid'=>$ownerid,
				'serviceid'=>'600005',
				'schedules'=>array(
					'scheduleid'=>$scheduleid,
					'desp'=>'schedule test description 1',
					'startdatetime'=>'2013-02-02 00:00:00',
					'enddatetime'=>'2013-05-04 01:00:00',
					'utcoffset'=>'-3000',
					'members'=>array(600005,600006)
				)
			);
			
			$result = $this->rest()->getResponse('schedules','post',$arr);
			if($result['code'] == 200){
				$_SESSION['scheduleid'] = $scheduleid;
				//success to create schedule
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
		
		if(isset($_POST['Schedule']) && isset($_POST['members']))
		{
			$ownerid = $_SESSION['ownerid'];
			$arr = array(
				'ownerid'=>$ownerid,
				'serviceid'=>'600001',
				'schedules'=>array(
					'desp'=>'schedule test description 1 update',
					'startdatetime'=>'2013-02-02 00:00:00',
					'enddatetime'=>'2013-05-05 01:00:00',
					'utcoffset'=>'-3000',
					'members'=>array(600002)
				)
			);
			
			$result = $this->rest()->getResponse('schedules/'.$id,'put',$arr);
			if($result['code'] == 200){
				//success to update schedule
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
		$result = $this->rest()->getResponse('schedules/'.$id,'delete');
		if($result['code'] == 200){
			//success to delete the schedule
		}else if($result['code'] == 202){
			//this schedule can’t be deleted
		}
		print_r($result);exit;
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		// if(!isset($_GET['ajax']))
			// $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
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
	
	public function Rest(){
		$rest = new RestClient();
		return $rest;
	}
}
