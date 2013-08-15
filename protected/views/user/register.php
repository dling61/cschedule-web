<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>

<!--<link href="cschedule.css" rel="stylesheet" type="text/css" />-->
<link href='<?php echo Yii::app()->baseUrl."/css/cschedule.css";?>' rel="stylesheet" type="text/css" />
<script type="text/javascript" src='<?php echo Yii::app()->baseUrl."/js/jquery-1.3.2.min.js";?>'></script>
</head>

<body>

<div class="login_introduction">
  <div class="sign_logo"><img src="<?php echo Yii::app()->baseUrl.'/images/home/logo.png';?>" width="237" height="37" />
  </div>
  <div class="sign">
    <div id="login_input">
     <input class="signup" name="Email" id='email' type="text" placeholder='Email'/>
    </div>
    <div  id="login_input"> <input class="signup" name="Username" id='username' type="text" placeholder='Username'/></div>
    <div  id="login_input"> <input class="signup" name="Password" id='password' type="password" placeholder='password'/></div>
    <div  id="login_input"> <input class="signup" name="Mobile" id='mobile' type="text" placeholder='Mobile' /></div>
    
    <span id='error_info'></span>

    <div id="logsbu1"><img src="<?php echo Yii::app()->baseUrl.'/images/home/signup.png';?>" width="170" height="24" onclick='checkValue()'/></div>

  </div>
</div>
</body>
<script language = 'javascript'>
function checkValue(){
	var email = document.getElementById('email').value;
	var username = document.getElementById('username').value;
	var password = document.getElementById('password').value;
	var mobile = document.getElementById('mobile').value;	
	
	var reg = /^([\w\.\_]{2,10})@(\w{1,}).([a-z]{2,4})$/;
	if(email ==''){
		document.getElementById('error_info').innerHTML = '<font color = \'red\'>Email cannot be empty.</font>';
		document.getElementById('email').focus();
		return;
	}
	if(username == ''){
		document.getElementById('error_info').innerHTML = '<font color = \'red\'>Username cannot be empty.</font>';
		document.getElementById('username').focus();
		return;
	}
	if(password == ''){
		document.getElementById('error_info').innerHTML = '<font color = \'red\'>Password cannot be empty.</font>';
		document.getElementById('password').focus();
		return;
	}
	if(email !='' && !reg.test(email)){
		document.getElementById('error_info').innerHTML = '<font color = \'red\'>Wrong Email format.</font>';
		document.getElementById('email').focus();
		return;
	}
	
	var img = "<?php echo "<img src = '".Yii::app()->baseUrl."/images/home/loading.gif'>";?>";
	var url = "<?php echo Yii::app()->createUrl('User/Login');?>";
	<?php echo CHtml::ajax(
		array(
					"url" => CController::createUrl("User/SignUp"),
					"data" => "js:{email : email, username : username ,password : password, mobile : mobile}",
					"type"=>"POST",
					'beforeSend'=>'js:function(){document.getElementById(\'error_info\').innerHTML = img}',
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

