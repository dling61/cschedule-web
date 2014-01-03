<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>forgot password</title>
<link href="./css/cschedule.css" rel="stylesheet" type="text/css" />
</head>
<body >

<div class="main11"><div class="top3"><a href="<?php echo Yii::app()->homeUrl;?>"></a></div></div>
<div class="main12"><p class="main12font">Forgot password<p><span class="main12font2"> Enter the email address you used to register, and we will send you a secure link to reset your password.</span><br/><br/><form method="post">
<input class="conwen1" placeholder="Email" name="email"><br/><br/>
<input type="submit" value="" class="main12button">
</form>
</div>

<div class="footer" style="position: absolute;
bottom: 0; ">
  <ul>
    <li><a href="<?php echo Yii::app()->createUrl('User/About');?>">About CSchedule</a> </li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;</li>
    <li><a href="<?php echo Yii::app()->createUrl('User/Help');?>">Help</a> </li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;</li>
    <li><a href="<?php echo Yii::app()->createUrl('User/Privacy');?>">Privacy</a></li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;Copyright&copy;
      E2WSTUDY,LLC </li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/bg_14.png" />&nbsp;&nbsp;&nbsp;&nbsp;</li>
    <li>
      <input type="button" class="forapp">
    </li>
    <li>
      <input type="button" class="foraid">
    </li>
  </ul>
</div>

</body>
</html>

