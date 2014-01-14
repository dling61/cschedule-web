<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<title>forgot password</title>
<link href="../css/cschedule.css" rel="stylesheet" type="text/css" />


</head>
<body >

<div class="main11"><div class="top3"><a href="<?php echo 'http://'.$_SERVER['SERVER_NAME'];?>"></a></div></div>
<div class="main12"><p class="main12font">Forgot password<p><span class="main12font2">We have sent an email to <span class="main12font3"><?php echo isset($_GET['email'])?$_GET['email']:"";?></span> with the instruction to reset your password.</span><br/><br/><span class="main12font2"> Note: The email may take a minute or two to arrive, if you don't see it soon, please check your spam folder in case it is delivered there.</span><br/><br/>
<span></span>


</div>

<div class="footer" style="position: absolute;
bottom: 0; ">
  <ul>
    <li><a href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/index.php?r=User/About';?>">About CSchedule</a> </li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;</li>
    <li><a href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/index.php?r=User/Help';?>">Help</a> </li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;</li>
	<li><a href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/index.php?r=User/Blog';?>">Blog</a> </li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;</li>
	<li><a href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/index.php?r=User/Contact';?>">Feedback</a> </li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;</li>
    <li><a href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/index.php?r=User/Privacy';?>">Privacy</a></li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;Copyright&copy;
      E2WSTUDY,LLC </li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/bg_14.png">&nbsp;&nbsp;&nbsp;&nbsp;</li>
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
