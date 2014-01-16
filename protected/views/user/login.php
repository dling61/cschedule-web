<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>CSchedule</title>
<link href="<?php echo Yii::app()->baseUrl."/css/cschedule.css";?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src='<?php echo Yii::app()->baseUrl."/js/jquery.js";?>'></script>
</head>

<body>
<?php

if(isset($_COOKIE['cschedule_user']) && $_COOKIE['cschedule_user']!=''){

	$user = $_COOKIE['cschedule_user'];

}else $user = '';

?>

<div class="bigbg">
  <div class="top">
    <div class="logo"><a href="#"><img src="images/bg_03.png" /></a><br/><div class="learn"><a href="#"><img src="images/bg_22.png" /></a></div></div>
	
	<!-- sign in form start-->
  <div class="log" id='signinform'><table width="254" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="15" colspan="2" align="center"><span class="wrong" id="error_info"></span></td>
    </tr>
  <tr>
    <td height="43" colspan="2"><input type="text" class="emailk" placeholder="Email" id="email" value="<?php echo $user;?>"></td>
    </tr>
  <tr>
    <td height="43" colspan="2"><input type="password" class="password1" placeholder="Password" id="password"></td>
    </tr>
  <tr style="font-size:12px;">
    <td width="20" height="26"><form id="form1" name="form1" method="post">
      <label>
        <input type="checkbox" name="checkbox" id="remember">
        </label>
    </form>
       </td>
    <td width="234"><span class="remember"> Remember me  </span>&nbsp;&nbsp;  <span class="forgot"><a href="<?php echo Yii::app()->createUrl('User/Resetpw')?>"> Forgot your password</a></span></td>
  </tr>
  <tr>
    <td height="46" colspan="2"><input type="button" class="login" onclick='checkValue()'>
    </td>
    </tr>
  <tr>
    <td colspan="2"><input type="button" class="signup" onclick='changeForm()'></td>
    </tr>
</table>
</div>
<!-- sign in form end-->

<!-- sign up form start-->
<div class="loged" style='display:none' id='signupform'><table width="254" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="20" colspan="2" align="center"><span class="wrong" id="error_info2"></span></td>
    </tr>
  <tr>
    <td height="43" colspan="2"><input type="text" class="email2" placeholder='Email' id="signup_email"></td>
    </tr>
 <tr>
    <td height="43" colspan="2"><input type="text" class="email2" placeholder="Username" id="signup_username"></td>
    </tr>
	<tr>
    <td height="43" colspan="2"><input type="password" class="email2" placeholder="Password" id="signup_password"></td>
    </tr>
	<tr>
    <td height="43" colspan="2"><input type="text" class="email2" placeholder="Mobile(optional)" id="signup_mobile"></td>
    </tr>
  
  <tr>
    <td height="46" colspan="2"><input type="button" class="signup" onclick='checkSignUpValue()'>
    </td>
    </tr>
  <tr>
    <td colspan="2"><input type="button" class="login" onclick='changeForm2()'></td>
    </tr>
</table>
</div>
<!-- sign up form end-->

  <div class="clear"></div></div>
</div>

<div class="main">
  <dl class="back1">
  <dt> <span class="weight fontcolor1">Create schedules for all</span></dt> <dd><span class="weight fontcolor1"> your group activities.</span></dd>
  </dl>
  <dl class="back2">
  <dt> <span class="weight fontcolor1">Share schedules with your </span></dt> <dd><span class="weight fontcolor1">contacts.</span></dd>
  </dl>
  <dl class="back3">
  <dt> <span class="weight fontcolor1">Get reminders on your phone or</span></dt> <dd><span class="weight fontcolor1"> email about schedules. </span></dd>
  </dl>
</div>	

<?php include_once(dirname(dirname(__FILE__)).'/footer.php');?>

</body>

<script language = 'javascript'>
function checkValue(){

	var email = document.getElementById('email').value;

	var password = document.getElementById('password').value;

	var remember = document.getElementById('remember').checked;

	var reg = /(\S)+[@]{1}(\S)+[.]{1}(\w)+/;

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

	var img = "<?php echo "<img src = '".Yii::app()->baseUrl."/images/loading.gif'>";?>";

	var url = "<?php echo Yii::app()->createUrl('Service/Admin');?>";

	<?php

			echo CHtml::ajax(

				array(

					"url" => CController::createUrl("User/SignIn"),

					"data" => "js:{email : email, password : password, remember : remember}",

					"type"=>"POST",
					
					"async"=>false,

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

function changeForm(){
	document.getElementById('signinform').style.display = 'none';
	document.getElementById('signupform').style.display = '';
}

function checkSignUpValue(){
	var email = document.getElementById('signup_email').value;
	var username = document.getElementById('signup_username').value;
	var password = document.getElementById('signup_password').value;
	var mobile = document.getElementById('signup_mobile').value;	
	// var regu= /^([a-zA-Z0-9]|[._]){2,10}$/; //verify special char
	
	//var reg = /(\S)+[@]{1}(\S)+[.]{1}(\w)+/; //verify email
	var reg = /^[\!\@\#\$\%\^\&\*\~\-\+\=\?\<\>\.\,\w]+@\w+(\.[a-zA-Z]{2,3}){1,2}$/;
	if(email ==''){
		document.getElementById('error_info2').innerHTML = '<font color = \'red\'>Email cannot be empty.</font>';
		document.getElementById('signup_email').focus();
		return;
	}
	if(username == ''){
		document.getElementById('error_info2').innerHTML = '<font color = \'red\'>Username cannot be empty.</font>';
		document.getElementById('signup_username').focus();
		return;
	}
	if(password == ''){
		document.getElementById('error_info2').innerHTML = '<font color = \'red\'>Password cannot be empty.</font>';
		document.getElementById('signup_password').focus();
		return;
	}
	if(email !='' && !reg.test(email)){
		document.getElementById('error_info2').innerHTML = '<font color = \'red\'>Wrong Email format.</font>';
		document.getElementById('signup_email').focus();
		return;
	}
	
	var img = "<?php echo "<img src = '".Yii::app()->baseUrl."/images/loading.gif'>";?>";
	var url = "<?php echo Yii::app()->createUrl('User/Login');?>";
	<?php echo CHtml::ajax(
		array(
					"url" => CController::createUrl("User/SignUp"),
					"data" => "js:{email : email, username : username ,password : password, mobile : mobile}",
					"type"=>"POST",
					
					"async"=>false,
					
					'beforeSend'=>'js:function(){document.getElementById(\'error_info2\').innerHTML = img;}',
					"success"=>"js:function(data){
						if(data=='ok'){
							location.href=url;
						}else{
							document.getElementById('error_info2').innerHTML = '<font color = \'red\'>'+data+'</font>'
							}
						}",
				)
			);
	?>
}

function changeForm2(){
	document.getElementById('signinform').style.display = '';
	document.getElementById('signupform').style.display = 'none';
}
</script>

</html>
