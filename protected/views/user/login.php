<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CSchedule</title>

<!--<link href="cschedule.css" rel="stylesheet" type="text/css" />-->
<link href='<?php echo Yii::app()->baseUrl."/css/cschedule.css";?>' rel="stylesheet" type="text/css" />
<script type="text/javascript" src='<?php echo Yii::app()->baseUrl."/js/jquery-1.3.2.min.js";?>'></script>
<style type="text/css">
body {
	margin: auto;
}
</style>
</head>

<body>
<?php
if(isset($_COOKIE['cschedule_user']) && $_COOKIE['cschedule_user']!=''){
	$user = $_COOKIE['cschedule_user'];
}else $user = '';
?>
<form id="signup" name="signup" method="post" action="">
<div class="login" id="login">
  <div class="login_introduction">
  <div class="introduction"><h1></h1> <ul>A social network to organize activities and share schedules.</ul></div>
  <div class="loginput">
    <div id="login_input">
     <input class="email" name="Email" id='email' type="text" placeholder='Email' value="<?php echo $user;?>">
    </div>
    <div  id="login_input">
	<input class="password" name="Password" id='password' type="password" placeholder='Password'></div>
	
	<span id="error_info"></span>
    <div id="remind"> 
    <input type="checkbox" name="checkbox" id="remember" <?php if(isset($_COOKIE['cschedule_psw']) && $_COOKIE['cschedule_psw']!=''){?>
       checked="checked" <?php } ?>/>
    Remember me two weeks
  </div>
    <div id="logsbu1"><img src="<?php echo Yii::app()->baseUrl.'/images/home/login.png';?>" width="170" height="24" onclick='checkValue()'/></div>
  </form>
    <div id="logsbu1">
  <a href='<?php echo Yii::app()->createUrl('User/Register');?>'><img src="<?php echo Yii::app()->baseUrl.'/images/home/signup.png';?>" width="170" height="24" /></a></div>
  </div>
  </div>
  <!-- <div class="foot" id="foot">about us     |   how it works    |     Privacy        copyright @xxxxxx</div>-->
</div>
<!--#include file="foot.html" -->
</body>
<script language = 'javascript'>
function checkValue(){
	var email = document.getElementById('email').value;
	var password = document.getElementById('password').value;
	var remember = document.getElementById('remember').checked;

	var reg = /^([\w\.\_]{2,10})@(\w{1,}).([a-z]{2,4})$/;
	if(email ==''){
		document.getElementById('error_info').innerHTML = '<font color = \'red\'>Email cannot be empty.</font>';
		document.getElementById('email').focus();
		return;
	}else if(password == ''){
		document.getElementById('error_info').innerHTML = '<font color = \'red\'>Password cannot be empty.</font>';
		document.getElementById('password').focus();
		return;
	}else if(email !='' && !reg.test(email)){
		document.getElementById('error_info').innerHTML = '<font color = \'red\'>Wrong Email format.</font>';
		document.getElementById('email').focus();
		return;
	}
	var img = "<?php echo "<img src = '".Yii::app()->baseUrl."/images/home/loading.gif'>";?>";
	var url = "<?php echo Yii::app()->createUrl('Service/admin');?>";
	<?php
			echo CHtml::ajax(
				array(
					"url" => CController::createUrl("User/SignIn"),
					"data" => "js:{email : email, password : password, remember : remember}",
					"type"=>"POST",
					'beforeSend'=>"js:function(){
						document.getElementById('error_info').innerHTML = img;
					}",
					"success"=>"js:function(data){
						if(data=='ok'){
							location.href=url;
						}else{
							document.getElementById('error_info').innerHTML = '<font color = \'red\'>'+data+'</font>'
							}
						}",
				)
			);
	?>
}
</script>

</html>
