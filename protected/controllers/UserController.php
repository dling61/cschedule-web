<?php

class UserController extends Controller
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
				'actions'=>array('ResetPw','view','create','update','delete','login','SetToken','Info'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/*
	* render a register page
	*/
	public function actionRegister(){
		$this->render('register');
	}
	
	/*
	* user register  -SignUp
	* post call
	*/	
	public function actionSignUp(){
		$arr = array();
		if(isset($_POST['email'])){
			$arr['email'] = mysql_real_escape_string($_POST['email']);
			$arr['username'] = mysql_real_escape_string($_POST['username']);
			$arr['password'] = mysql_real_escape_string($_POST['password']);
			// if(isset($_POST['mobile']) && $_POST['mobile'] != '')
			$arr['mobile'] = mysql_real_escape_string($_POST['mobile']);
			
			$result = $this->rest()->getResponse('creator?action=register','post',$arr); //return a array

			if($result['code'] == 200){
				echo 'ok';
			}else if($result['code'] == 201){
				echo 'The user already exists';
			}
		}
	}
	
	/*
	* user login,render a login page
	*/
	public function actionLogin(){
		//use cookie to implement auto login
		if(isset($_COOKIE['cschedule_user']) && $_COOKIE['cschedule_user']!=''){
			$ckuser =  $_COOKIE['cschedule_user'];
		}else $ckuser = 'x'; //数据库有一个email，psw为空的用户
		if(isset($_COOKIE['cschedule_psw']) && $_COOKIE['cschedule_psw']!=''){
			$ckpsw =  encrypt($_COOKIE['cschedule_psw'], 'D', 'MyCSchedule');
		}else $ckpsw = '';
		$arr = array();
		$arr['email'] = $ckuser;
		$arr['password'] = $ckpsw;
		$result = $this->rest()->getResponse('creator?action=signin','post',$arr);
		if($result['code'] == 200){
			$this->redirect(Yii::app()->createUrl('Service/admin'));
		}else{
			$this->render('login');
		}
	}
	
	/*
	* handle the post data and login
	* post call
	*/
	public function actionSignIn(){
		$arr = array();
		if(isset($_POST['email'])){
			$arr['email'] = mysql_real_escape_string($_POST['email']);
			$arr['password'] = mysql_real_escape_string($_POST['password']);
			$result = $this->rest()->getResponse('creator?action=signin','post',$arr);
			if($result['code'] == 200){
				setcookie('cschedule_user', mysql_real_escape_string($_POST['email']), time()+3600*24*14);
				if($_POST['remember'] === 'true'){
					setcookie('cschedule_psw', encrypt(mysql_real_escape_string($_POST['password']), 'E', 'MyCSchedule'), time()+3600*24*14);
				}
				session_start();
				$response = json_decode($result['response']);
				
				//if login in successfull,store the users' session.Session key -- ownerid/username/serviceid/memberid/scheduleid
				foreach($response as $key=>$value){
					$_SESSION[$key] = $value;
				}
				echo 'ok';
			}else if($result['code'] == 401){
			//do something
				echo 'Incorrect email or password.';
			}
		}
	}
	
	/*
	* user login out
	* destory the session
	*/
	public function actionLogout(){
		//if user logout,destory the session
		session_start();
		unset($_SESSION);
		session_destroy(); 
		//destroy cookie
		setcookie("cschedule_user", "", time() - 3600);
		setcookie("cschedule_psw", "", time() - 3600);
		$this->redirect(Yii::app()->createUrl('User/Login'));
	}
	
	/*
	* user password reset
	* post call
	*/	
	public function actionResetPw(){
		$response = $this->rest()->getResponse('creator?action=resetpw','post',array(
			'email'=>'xxyy@345.com',
			'password'=>'1'
		)); 
		print_r($response);
		//$this->render('page',array('var'=>$var));//page is the view page of create members ,and $var is the variable passed to the view page.
	}
	
	/*
	* user set token
	* post call
	*/
	public function actionSetToken(){
		$response = $this->rest()->getResponse('creator?action=settoken','post',array(
			'userid'=>'46',
			'udid'=>'ebere23432r2w43tr43t36t3t3',
			'token'=>'sfsfsfe132425242411vfv'
		)); 
		print_r($response);
		//$this->render('page',array('var'=>$var));//page is the view page of create members ,and $var is the variable passed to the view page.
	}
	
	/*
	* Instantiation of a class RestClient
	*/
	public function Rest(){
		$rest = new RestClient();
		return $rest;
	}
}
