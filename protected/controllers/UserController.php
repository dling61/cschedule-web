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
				'actions'=>array('ResetPw','view','create','update','delete','login','SetToken','Info','ResetPswRequest','verifyEmail','Reset','About','Help','Privacy','Contact','SubmitComments','SessionTimeout','Blog'),
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
	
	public function actionSessionTimeout()
	{
		$this->render('sessionTimeout');
	}
	
	/*
	* user register  -SignUp
	* post call
	*/	
	public function actionSignUp(){
		$arr = array();
		if(isset($_POST['email'])){
			$arr['email'] = ($_POST['email']);
			$arr['username'] = ($_POST['username']);
			$arr['password'] = ($_POST['password']);
			// if(isset($_POST['mobile']) && $_POST['mobile'] != '')
			$arr['mobile'] = ($_POST['mobile']);
			
			$result = $this->rest()->getResponse('creator?action=register','post',$arr,NULL,true); //return a array

			if($result['code'] == 200){
				setcookie('cschedule_user', ($arr['email']), time()+3600*24*14);
				setcookie('cschedule_psw', encrypt(($arr['password']), 'E', 'MyCSchedule'), time()+30);
				echo 'ok';
			}else if($result['code'] == 201){
				echo 'The user already exists';
			}else{
				echo 'Busy network,try it later.';
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
		} //数据库有一个email，psw为空的用户
		if(isset($_COOKIE['cschedule_psw']) && $_COOKIE['cschedule_psw']!=''){
			$ckpsw =  encrypt($_COOKIE['cschedule_psw'], 'D', 'MyCSchedule');
		}
		if(isset($ckuser) && isset($ckpsw)){
			$arr = array();
			$arr['email'] = $ckuser;
			$arr['password'] = $ckpsw;
			$result = $this->rest()->getResponse('creator?action=signin','post',$arr,NULL,true);
			if($result['code'] == 200){
		
				session_start();
				$response = json_decode($result['response']);
				
				//if login in successfull,store the users' session.Session key -- ownerid/username/serviceid/memberid/scheduleid
				foreach($response as $key=>$value){
					$_SESSION[$key] = $value;
				}
				Yii::app()->user->setState('userSessionTimeout', time() + Yii::app()->params['sessionTimeoutSeconds']) ;
				
				$this->redirect(Yii::app()->createUrl('Service/admin'));
			}
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
			$arr['email'] = $_POST['email'];
			$arr['password'] = $_POST['password'];
			//set user's email
			setcookie('cschedule_user',$_POST['email'], time()+3600*24*14);
			
			$result = $this->rest()->getResponse('creator?action=signin','post',$arr,NULL,true);
			if($result['code'] == 200){
				// setcookie('cschedule_user',$_POST['email'], time()+3600*24*14);
				if($_POST['remember'] === 'true'){
					setcookie('cschedule_psw', encrypt($_POST['password'], 'E', 'MyCSchedule'), time()+3600*24*14);
				}else setcookie('cschedule_psw', encrypt($_POST['password'], 'E', 'MyCSchedule'), time()+3600*0.5);
				session_start();

				$response = json_decode($result['response']);
				
				//if login in successfull,store the users' session.Session key -- ownerid/username/serviceid/memberid/scheduleid
				foreach($response as $key=>$value){
					$_SESSION[$key] = $value;
				}
				echo 'ok';
                $_SESSION['useremail'] = $_POST['email'];
				Yii::app()->user->setState('userSessionTimeout', time() + Yii::app()->params['sessionTimeoutSeconds']) ;
				
			}else if($result['code'] == 401){
			//do something
				echo 'Incorrect email or password.';
			}else{
				echo 'Busy network,try it later.';
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
		// setcookie("cschedule_user", "", time() - 3600);
		setcookie("cschedule_psw", "", time() - 3600);
		$this->redirect(Yii::app()->createUrl('User/Login'));
	}
	
	/*
	* user password reset
	* post call
	*/	
	public function actionResetPw(){
		if(isset($_POST['email'])){
			$email = $_POST['email'];
			$response = $this->rest()->getResponse('creator?action=resetpw','post',array(
				'email'=>$email,
			),NULL,true); 
			if($response['code'] == 200){
				// $this->redirect('/cschedule/forgotpsw2.php');
				echo "<script>location.href='./cschedule/forgotpsw2.php?email=".$email."';</script>";
			}
			// else{
				// dump($response['code']);exit;
			// }
		}
		
		$this->render("forgotpsw1");
	}
	
	public function actionReset(){
		if(isset($_POST['psw'])){
			$psw = $_POST['psw'];
			$email = $_POST['email'];
			$token = $_POST['sig'];
			
			$response = $this->rest()->getResponse('creator?action=setpassword','post',array(
				'email'=>$email,
				'password'=>$psw,
				'token'=>$token
			),NULL,true); 
			if($response['code'] == 200){
				echo "ok";
			}else if($response['code'] == 201){
				echo "No set password request for this user";
			}else{
				echo "Busy network, try it later.";
			}
		}
	}
	
	public function actionBlog(){
		$request_url = 'http://cschedule.tumblr.com/api/read?start=0&num=1000';
		$xml = simplexml_load_file($request_url);
		$posts = $xml->xpath("/tumblr/posts/post"); 
		
		// foreach($posts as $post) {
			// $date = $post['date'];
			// $regular_body = $post->{'regular-body'};
		// }
		$this->render('blog',array('posts'=>$posts));
	}
	
	
	
	/*public function actionResetPswRequest(){
		$id = 12;
		$email = '2422915252@qq.com';
		
		//first to check if the email real exists,need to use a rest api to return http code.If code is 200,return userid.
		
		$auth = authcode($id.'/'.$email.'/'.time(), 'ENCODE', 'CScheduleSafeEmail@123CSchedule', 3600*48);  
		
		$subject = "CSchedule password change request";
		$content = "<p>We have received a password change request for your CSchedule account: <a href='mailto:".$email."' target='_blank'>".$email."</a>.</p>
<br>

<p>If you made this request, then please click on the link below. </p>

<p><a href = '".Yii::app()->request->hostInfo.Yii::app()->createUrl('User/verifyEmail',array('token'=>$auth))."'>Reset Password</a></p>

<br>
<p>This link will work for 2 hours or until you reset your password.</p>
<br>
<p>If you did not ask to change your password, then please ignore this email. Another user may 
have entered your username by mistake. No changes will be made to your account. </p>
<br>
<p>Thank You, <p>
<p>CSchedule Team</p>";
		
		$mailer = Yii::app()->phpMailer->_mailer;
		$mailer->Subject = $subject;
		$mailer->Body = $content;
		$mailer->AddAddress('2422915252@qq.com');
		if($mailer->send()) 
		{
			echo 'Email has been sent successfully.';
		}
		else echo "Fail to send the Email!";
		$mailer->ClearAddresses();
		unset($mailer); 
	}
	
	public function actionverifyEmail(){
		if(isset($_GET['token'])){
			$token = $_GET['token'];
			$de_token = authcode($token,'DECODE','CScheduleSafeEmail@123CSchedule');
			$auth = explode('/',$de_token);
			
			// than send  userid and email to check if this is the right user,needs a rest api
			$id = 12;
			$email = '2422519292@qq.com';
			if(true){
				//if email is right, render a resetpassword view page
				$this->render('resetpsw',array(
					'id'=> $id,
					'email'=>$email
				));
			}
		}else{
			//do something
			echo 'no token';
		}
	}*/
	
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
	
	public function actionAbout(){
		$this->render('about');
	}
	
	public function actionHelp(){
		$this->render('help');
	}
	
	public function actionPrivacy(){
		$this->render('privacy');
	}
	
	public function actionContact(){
		session_start();
		
		if(isset($_SESSION['ownerid']))
			$this->render('contact');
		else $this->redirect(Yii::app()->homeUrl);
	}
	
	public function actionSubmitComments(){
		session_start();
		if(isset($_POST['comments'])){
			$name = $_POST['name'];
			$email = $_POST['email'];
			$comments = $_POST['comments'];
			$ownerid = $_SESSION['ownerid'];
			
			$feedback = $comments."<p>====== User Info ======<br>Username: ".$name."<br>Email: ".$email."</p>";
			// echo $feedback;exit;
			
			$response = $this->rest()->getResponse('feedback','post',array(
				'ownerid'=>$ownerid,
				'feedback'=>$feedback,
			),NULL,false); 
			if($response['code'] == 200){
				echo "ok";
			}else if($response['code'] == 201){
				echo "Not valid content in the feedback.";
			}else{
				echo "Busy network, try it later.";
			}
		}
	}
	/*
	* Instantiation of a class RestClient
	*/
	public function Rest(){
		$rest = new RestClient();
		return $rest;
	}
}
