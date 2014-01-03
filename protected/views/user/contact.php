<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>Contact us | CSchedule</title>
<link href="./css/cschedule.css" rel="stylesheet" type="text/css" />
<script src="./js/jquery.js" type="text/javascript"></script>
</head>

<style type="text/css">
.showbox{position:fixed;top:0;left:50%;z-index:9999;opacity:0;filter:alpha(opacity=0);margin-left:-80px;}
*html,*html body{background-image:url(about:blank);background-attachment:fixed;}
*html .showbox,*html .overlay{position:absolute;top:expression(eval(document.documentElement.scrollTop));}
#AjaxLoading{border:1px solid #908964;color:#37a;font-size:12px;font-weight:bold;}
#AjaxLoading div.loadingWord{width:180px;height:50px;line-height:50px;border:2px solid #D6E7F2;background:#fff;}
#AjaxLoading img{margin:10px 15px;float:left;display:inline;}
</style>

<body >

<div class="main11"><div class="top3"><a href="<?php echo Yii::app()->homeUrl;?>"></a></div></div>
<div class="main12"><div  class="size3">Contact Us</div><p><span class="main12font2">We are constantly try to improve our user experience, so your feedback is very important to us, please send us any questions,comments,or suggestions.<br>We'd love to hear from you.  </span>

<br>

<!-- js loading start -->
<div id="AjaxLoading" class="showbox">
	<div class="loadingWord"><img src="./images/waiting.gif">Please Wait...</div>
</div>
<!-- js loading end-->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="8%" height="51"><span class="fontsize1">Name</span></td>
    <td width="73%"><input id="name" class="conwen1"></td>
    <td width="19%">&nbsp;</td>
  </tr>
  <tr>
    <td height="53"><span class="fontsize1">Email</span></td>
    <td><input id="email" class="conwen1"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="128"><span class="fontsize1">Comments</span>&nbsp;</td>
    <td><textarea id="comments" cols="80" rows="8"></textarea>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="36%" align="right"><span class="sub"><a onclick="checkContact()"></a></span></td>
        <td width="3%">&nbsp;</td>
        <td width="61%"><span class="sub2"><a onclick="reset()"></a></span></td>
      </tr>
    </table></td>
    <td>&nbsp;</td>
  </tr>
</table>


</div>

<div class="footer" style="position: absolute; bottom: -2px; left: 3px;"   >
 <ul>
    <li><a href="<?php echo Yii::app()->createUrl('User/About');?>">About CSchedule</a> </li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;</li>
    <li><a href="<?php echo Yii::app()->createUrl('User/Help');?>">Help</a> </li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;</li>
	<li><a href="<?php echo Yii::app()->createUrl('User/Contact');?>">Feedback</a></li>
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
<script language="javascript">
function checkContact(){
	var name = $("#name").val();
	var email = $("#email").val();
	var comments = $("#comments").val();
	
	var reg = /^[\!\@\#\$\%\^\&\*\~\-\+\=\?\<\>\.\,\w]+@\w+(\.[a-zA-Z]{2,3}){1,2}$/;
	
	var content = comments+"<br>"+name+"<br>"+email;
	
	if(name == ""){
		alert("Name can not be empty!");
		$("#name").focus();
		return;
	}
	if(email == ""){
		alert("Email can not be empty!");
		$("#email").focus();
		return;
	}
	if(comments == ""){
		alert("Comments can not be empty!");
		$("#comments").focus();
		return;
	}
	
	if(email !='' && !reg.test(email)){
		alert('Wrong Email format!');
		$('#email').focus();
		return;
	}
	
	// url = "<?php echo Yii::app()->homeUrl;?>";
	url = "<?php echo Yii::app()->createUrl("Service/Admin");?>";
	<?php
		echo CHtml::ajax(array(
			"url"=>Yii::app()->createUrl("User/SubmitComments"),
			"type"=>"POST",
			"data"=>"js:{comments:comments,name:name,email:email}",
			"async"=>false,
			'beforeSend'=>"js:function(){
						$(\".showbox\").stop(true).animate({'margin-top':'300px','opacity':'1'},200);
			}",
			"success"=>"function(data){
				$(\".showbox\").stop(true).animate({'margin-top':'250px','opacity':'0'},400);
				if(data=='ok'){
					// alert('Thanks to all for your careful consideration and creative ideas.');
					location.href=url;
				}else{
					alert(data);
				}
			}"
			
		));
	?>
}

function reset(){
	document.getElementById("name").value = "";
	document.getElementById("email").value = "";
	document.getElementById("comments").value = "";
}
</script>

</html>
