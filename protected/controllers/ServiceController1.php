<?php

class ServiceController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','create','update','delete'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/*
	* list of services 
	* get call
	*/
	public function actionIndex()
	{
		$response = $this->rest()->getResponse('services','get',array('ownerid'=>2));// another parameter--lastupdatetime 
		print_r(json_decode($response)->services);//json format
	}
	
	/*
	* create a service
	* post call
	*/
	public function actionCreate(){
		$response = $this->rest()->getResponse('services','post',array('ownerid' =>'36',
							'members'=>array(
							'serviceid'=>'11222',
							'servicename'=>'clean room',
							'desp'=>'some description',
							'mobile'=>'12456897',
							'startdatetime'=>'2012-09-12 02:45:46',//rest api返回的时间错误 serverid=0 alert=0等
							'enddatetime'=>'2012-10-12 02:45:46',
							'alert'=>'1'
							)
		));
		print_r($response);
		//$this->render('page',array('var'=>$var));//page is the view page of create members ,and $var is the variable passed to the view page.
	}
	
	/*
	* update a service
	* put call
	*/
	public function actionUpdate($id){
		$id = '0';
		$response = $this->rest()->getResponse('members/'.$id,'put',array('ownerid' =>$id,
						'members'=>array(
							'servicename'=>'clean room xx',
							'desp'=>'some descriptionxx',
							'mobile'=>'12456897',
							'startdatetime'=>'2012-09-12 02:45:46',//rest api返回的时间错误
							'enddatetime'=>'2012-10-12 02:45:46',
							'alert'=>'1'
			)
		));
		print_r($response);
	}
	
	/*
	* delete a service
	* delete call
	*/
	public function actionDelete($id){
		$id = '888888';
		$response = $this->rest()->getResponse('members/'.$id,'delete');
		print_r($response);
	}
	
	/*
	* Instantiation of a class RestClient
	*/
	public function Rest(){
		$rest = new RestClient();
		return $rest;
	}
}
