<?php
class MemberController extends Controller
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
	* list of members 
	* get call
	*/
	public function actionIndex()
	{
		$response = $this->rest()->getResponse('members','get',array('ownerid'=>36));// another parameter--lastupdatetime 
		print_r($response);//json format
	}
	
	/*
	* create a member
	* post call
	*/
	public function actionCreate(){
		$response = $this->rest()->getResponse('members','post',array('ownerid' =>'36',
						'members'=>array(
							'email'=>'xiaoxiaochongxx@msn.com',
							'memberid'=>'8888',
							'membername'=>'xiaoxiaoxx',
							'mobile'=>'12456897'	
			)
		));
		print_r($response);
		//$this->render('page',array('var'=>$var));//page is the view page of create members ,and $var is the variable passed to the view page.
	}
	
	/*
	* update a member
	* put call
	*/
	public function actionUpdate($id){
		$id = '2147483647';
		$response = $this->rest()->getResponse('members/'.$id,'put',array('ownerid' =>$id,
						'members'=>array(
							'email'=>'xiaoxiaochongxx@msn.com',
							'membername'=>'xiaoxiaoxx',
							'mobile'=>'12456897'	
			)
		));
		print_r($response);
	}
	
	/*
	* delete a member
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
