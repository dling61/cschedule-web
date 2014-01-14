<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>forgot password</title>
<link href="../css/cschedule.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js"></script>

<style type="text/css">
.showbox{position:fixed;top:0;left:50%;z-index:9999;opacity:0;filter:alpha(opacity=0);margin-left:-80px;}
*html,*html body{background-image:url(about:blank);background-attachment:fixed;}
*html .showbox,*html .overlay{position:absolute;top:expression(eval(document.documentElement.scrollTop));}
#AjaxLoading{border:1px solid #908964;color:#37a;font-size:12px;font-weight:bold;}
#AjaxLoading div.loadingWord{width:180px;height:50px;line-height:50px;border:2px solid #D6E7F2;background:#fff;}
#AjaxLoading img{margin:10px 15px;float:left;display:inline;}
</style>

</head>
<body >
<input type="hidden" id="email" value="<?php echo isset($_GET['email'])?$_GET['email']:"";?>">
<input type="hidden" id="sig" value="<?php echo isset($_GET['sig'])?$_GET['sig']:"";?>">

<!-- js loading start -->
<div id="AjaxLoading" class="showbox">
	<div class="loadingWord"><img src="./images/waiting.gif">Please Wait...</div>
</div>
<!-- js loading end-->

<div class="main11"><div class="top3"><a href="<?php echo 'http://'.$_SERVER['SERVER_NAME'];?>"></a></div></div>
<div class="main12"><p class="main12font">Forgot password<p><span class="main12font2">Resetting password for <span class="main12font3"><?php echo isset($_GET['email'])?$_GET['email']:"";?></span></span><table width="360" border="0" cellspacing="0" cellpadding="0">
<tr id="tr1" style="display:none">
    <td height="27" colspan="2" align="center"><span class="wrong" id="error"></span></td>
</tr>

  <tr>
    <td width="130" height="58"><span class="main12font2">New password </span></td>
    <td width="230"><input type="password" class="conwen1" placeholder="New password" id="psw"></td>
  </tr>
  <tr>
    <td height="51"><span class="main12font2">Retype new password </span></td>
    <td><input class="conwen1" type="password" placeholder="Retype new password" id="repsw"></td>
  </tr>
  <tr>
    <td height="52" colspan="2" align="center"><input type="button"  class="main12button2" onclick="checkValue()"></td>
    </tr>
</table>
<br/>
<br/><br/>
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

<script language="javascript">
function checkValue(){
	var psw = document.getElementById('psw').value;
	var repsw = document.getElementById('repsw').value;
	var email = document.getElementById('email').value;
	var sig = document.getElementById('sig').value;

	if(psw == ""){
		document.getElementById('tr1').style.display = "";
		document.getElementById('error').innerHTML = "Please enter the new password.";
		document.getElementById('psw').focus();
		return;
	}else if(repsw == ""){
		document.getElementById('tr1').style.display = "";
		document.getElementById('error').innerHTML = "Please enter the password again";
		document.getElementById('repsw').focus();
		return;
	}else if(psw != repsw){
		document.getElementById('tr1').style.display = "";
		document.getElementById('error').innerHTML = "Please keep the two password be the same.";
		document.getElementById('psw').focus();
		return;
	}
	
	$.ajax({
		url: "../index.php?r=User/Reset",
		type:"post",
		data:"email="+email+"&psw="+psw+"&sig="+sig,
		// beforeSend: function(){
			// $(".showbox").stop(true).animate({'margin-top':'300px','opacity':'1'},200);
		// }
		success: function(data){
			if(data != 'ok'){
				alert(data);
			}
			if(data == 'ok'){
				location.href='../index.php';
			}
			
			// $(".showbox").stop(true).animate({'margin-top':'250px','opacity':'0'},400);
		}
	});
}
</script>
</html>
