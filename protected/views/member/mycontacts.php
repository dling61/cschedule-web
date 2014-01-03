<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>My Contacts</title>
<link href="./css/cschedule.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->baseUrl.'/css/jqModal.css';?>" rel="stylesheet" type="text/css" />

<script src="./datepicker/WdatePicker.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->baseUrl.'/js/jqModal.js';?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl.'/js/ajaxfileupload.js';?>"></script>

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
<div class="top2">
  <div class="logo2">
    <div class="xinxi"><span><?php echo $_SESSION['username'];?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo Yii::app()->createUrl('User/Logout')?>">Logout</a></span></div>
    <div class="nav1">
      <ul>
        <li><a href="<?php echo Yii::app()->createUrl('Service/Create')?>">Create an activity</a></li>
        <li><a href="<?php echo Yii::app()->createUrl('Service/Admin')?>">My activities</a></li>
        <li><a href="<?php echo Yii::app()->createUrl('Member/Admin')?>" class="Selected1">My contacts</a></li>
		<li><a href="<?php echo Yii::app()->createUrl('Schedule/Admin')?>">My schedules</a></li>
      </ul>
      <div class="clear"></div>
    </div>
  </div>
  <div class="title1">
    <div class="title1b">My contacts</div>
  </div>
</div>
<div class="main7">
<!-- js loading start -->
<div id="AjaxLoading" class="showbox">
	<div class="loadingWord"><img src="./images/waiting.gif">Please Wait...</div>
</div>
<!-- js loading end-->

	  <!--dialog content-->
	  	<div class="jqmWindowContacts" id="mailpopup">
  <div class="yuan"></div>
  <div class='main13roll'>
  <ul>
   <li class='main13bg1'></li>
   <span  id="dialog_contact">
   
   </span>
  </ul>
  </div>
  
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
	
	<td width="47%" align="right"><input type="button"  class="main13bu1" onclick='getMailChecked()'></td> 
	<td width="5%" align="center"></td> 
    <td width="48%" align="left"><span class='jqmClose'><input type="button" class="main13bu3"></span></td>	
	
  </tr>
</table>


</div>

   <!--dialog content-->
	
	 <!--dialog content-->
	<div class="jqmWindowContacts" id="csvpopup">
  <div class="yuan"></div>
  <div class='main13roll'>
  <ul>
   <li class='main13bg1'></li>
   <span  id="dialog_contact1">
   
   </span>
  </ul>
  </div>
  
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
   
	<td width="47%" align="right"><input type="button"  class="main13bu1" onclick='getMailChecked()'></td> 
	<td width="5%" align="center"></td> 
    <td width="48%" align="left"><span class='jqmClose'><input type="button" class="main13bu3"></span></td>	
  </tr>
</table>


</div>

<input id="contactid" type="hidden"></span>
<!--edit contact dialog content -->
<div class="jqmWindowEditContacts" id="editcontactspopup">
  <table width="280" border="0" cellspacing="0" cellpadding="0">
    
    <tr>
      <td width="77" height="44"><span class="fontsize1">Name</span><img src="./images/bg_100.png" width="14" height="14" /></td>
      <td width="203"><input type="text" class="mname" placeholder="Name" id="edit_name"></td>
    </tr>
	<tr id='tr1' style="display:none">
      <td>&nbsp;</td>
      <td><span class="wrong" id="error1"></span></td>
    </tr>
	
    <tr>
      <td height="44"><span class="fontsize1">Email</span><span><img src="./images/bg_100.png" /></span></td>
      <td><input type="text" id="edit_email" class="mname" placeholder="Email"></td>
    </tr>
	
	<tr id='tr2' style="display:none">
      <td>&nbsp;</td>
      <td><span class="wrong" id='error2'></span></td>
    </tr>
	
    <tr>
      <td height="64"><span class="fontsize1">Mobile</span></td>
      <td><input type="text" class="mname" placeholder="Mobile" id="edit_mobile"></td>
    </tr>
	<tr id='tr3' style="display:none">
      <td>&nbsp;</td>
      <td><span class="wrong" id='error3'></span></td>
    </tr>
	
    <tr>
      <td colspan="2"><table width="300" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="155" align="right"><input type="button" class="cname5" onclick="saveEditContact()"></td>
    <td width="12">&nbsp;</td>
    <td width="133"><span class="jqmClose"><input type="button" class="cname6"></span></td>
  </tr>
</table>
</td>
    </tr>
  </table>
</div>
<!--edit contact dialog content-->

	  
	  

<table width="474" border="0" cellspacing="0" cellpadding="0">
    <tr>
	 <td width="162"></td>
      <td width="160"><a href = "#createcontact"><input type="button"  class="conbu1" onclick="showCreateContact()"></a></td>
      <!--<td width="160"><a href = "#createfromcsv"><input type="button"  class="conbu2" onclick="showCreateFromCsv()"></a></td>-->
      <td width="152"><a href = "#createfromemail"><input type="button"  class="conbu3" onclick="showCreateFromEmail()"></a></td>
    </tr>
  </table></div>
  
<div class="main8">
  <ul>
    <li class="conbg1"></li>
	
	<?php
		$member_str = "";
		$i = 1;
		if($members){
			$count = count($members);
			foreach($members as $member_vals){
				if($i>1){
					if($i < $count){
						$member_str .= "<li class='conbg2'><table width='800' height='56' border='0' cellpadding='0' cellspacing='0'>
  <tr>
    <td width='170' align='center' id='name_".$member_vals->memberid."'>".$member_vals->membername."</td>
    <td width='171' align='center' id='email_".$member_vals->memberid."'>".$member_vals->memberemail."</td>
    <td width='171' align='center' id='mobile_".$member_vals->memberid."'>".$member_vals->mobilenumber."</td>
    <td width='171' align='center'>".$member_vals->createdtime."</td>
    <td width='58'><span class='table5'><a cursor:pointer; title='Edit' onclick='editContacts(".$member_vals->memberid.")'></a></span></td>
    <td width='59'><span class='table6'><a cursor:pointer; title='Delete'
 onclick='deleteContact(".$member_vals->memberid.")' ></a></span></td>
  </tr>
</table>
</li>
<li class='conbg4'></li>";
					}else{
						$member_str .= "<li class='conbg2'><table width='800' height='56' border='0' cellpadding='0' cellspacing='0'>
  <tr>
    <td width='170' align='center' id='name_".$member_vals->memberid."'>".$member_vals->membername."</td>
    <td width='171' align='center' id='email_".$member_vals->memberid."'>".$member_vals->memberemail."</td>
    <td width='171' align='center' id='mobile_".$member_vals->memberid."'>".$member_vals->mobilenumber."</td>
    <td width='171' align='center'>".$member_vals->createdtime."</td>
    <td width='58'><span class='table5'><a cursor:pointer; title='Edit' onclick='editContacts(".$member_vals->memberid.")'></a></span></td>
    <td width='59'><span class='table6'><a cursor:pointer; title='Delete'
onclick='deleteContact(".$member_vals->memberid.")'></a></span></td>
  </tr>
</table>
</li>
<li class='conbg3'></li>"; 
					}
				}
				$i++;
			
			}
		}
		echo $member_str;
	?>
  </ul>
</div>


<div class="main9" id="createcontactlist" style="display:none">
<div class="main9one" id='createcontact' style="display:none">
  <table width="760" border="0" cellspacing="0" cellpadding="0">
	
	<tr><td height='14' colspan='6' align='center'><span class='wrong' id='notice1'></span></td></tr>
	
    <tr>
	<td width="19"><img src="./images/bg_100.png" /></td>

      <td width="205"><input class="conwen1" placeholder="Name" id="enter_name"></td>
	  <td width="19"><img src="./images/bg_100.png" /></td>

      <td width="222"><input class="conwen1" placeholder="Email" id="enter_email"></td>
      <td width="217"><input class="conwen1" placeholder="Mobile" id="enter_mobile"></td>
      <td width="78"><input type="button" class="conbu4" onclick = 'enterContact()'></td>
    </tr>
  </table>
</div>

<div class="main9two" id="createfromcsv" style="display:none">
  <table width="471" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="227"><form enctype="multipart/form-data" method="post">      
		   <input type="file" name="file" id="csvfile">
           </td>
      <td width="150"><input type="button"  class="conbu4" onclick='getCsvContacts()'></td></form>
	  
	  <td width="290" align="right"><span class="wrong" id='notice2'></span></td>
    </tr>
  </table>
</div>

<div class="main9three" id="createfromemail" style="display:none">
  <table width="566" border="0" cellspacing="0" cellpadding="0">
  
  <tr>
        <td width="146" ></td>
        <td width="213" align="center"><span class="wrong" id='notice3'></span></td>
        <td width="207">&nbsp;</td>
  </tr>
  
    <tr>
      <td width="173" height="51">Choose your emali sever <span><img src="./images/bg_100.png"></span></td>
      <td width="208" height="52"><form id="form2" name="form2" method="post" action="">
        <label>
          <select name="select" class="conwen3" id='emailserver' onchange='changeEmail()'>
            <option name="emailserver" value='gmail' >Gmail</option>
			<option name="emailserver" value='yahoo' >Yahoo</option>
          </select>
          </label>
      </form></td>
      <td width="219">&nbsp;</td>
    </tr>
    <tr>
      <td height="52">Email Name <span><img src="./images/bg_100.png"></span></td>
      <td><input class="conwen1" placeholder="Email" id="account" type='text'></td>
      <td id='serversort'>@gmail.com</td>
    </tr>
    <tr>
      <td height="52">Password <span><img src="./images/bg_100.png"></span></td>
      <td><input type="password" class="conwen1" placeholder="Password" id="psw"></td>
      <td><span class="wrong">We do not remember your password</span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td height="52"><input type="button" class="conbu6" onclick='getMailContacts()'></td>
      <td>&nbsp;</td>
    </tr>
  </table>
</div>

</div>

<?php include_once(dirname(dirname(__FILE__)).'/footer.php');?>

</body>
<script language='javascript'>
jQuery(document).ready(function() {
    jQuery("#mailpopup").jqm({
        modal: true,
        overlay: 40,
        onShow: function(h) {
            h.w.fadeIn(500);
        },
        onHide: function(h) {
            h.o.remove();
            h.w.fadeOut(500)
        }
    });
	
	jQuery("#csvpopup").jqm({
        modal: true,
        overlay: 40,
        onShow: function(h) {
            h.w.fadeIn(500);
        },
        onHide: function(h) {
            h.o.remove();
            h.w.fadeOut(500)
        }
    });
	
	jQuery("#editcontactspopup").jqm({
        modal: true,
        overlay: 40,
        onShow: function(h) {
            h.w.fadeIn(500);
        },
        onHide: function(h) {
            h.o.remove();
            h.w.fadeOut(500)
        }
    });
	
});

//global variable
var homeUrl;
var homeUrl = "<?php echo Yii::app()->homeUrl;?>";

function deleteContact(i){
	<?php
		echo CHtml::ajax(
			array(
					"url" => CController::createUrl("Member/delete"),
					"data" => "js:{id : i}",
					"type"=>"POST",
					'beforeSend'=>"js:function(){
						if(confirm('Are you sure to delete the contact?')){
							$('.showbox').stop(true).animate({'margin-top':'300px','opacity':'1'},200);
						}else{
							return false;
						}
					}",
					"success"=>"js:function(data){
						if(data=='ok'){
							history.go(0);
						}else if(data == 'ajaxsessionout'){
							location.href = homeUrl;
						}else{
							alert('fail to delete the contact');
						}
					}",
				)
		);
	?>
}

function showCreateContact(){
	document.getElementById('createcontactlist').style.display = '';
	document.getElementById('createcontact').style.display = '';
	document.getElementById('createfromcsv').style.display = 'none';
	document.getElementById('createfromemail').style.display = 'none';
}

function showCreateFromCsv(){
	document.getElementById('createcontactlist').style.display = '';
	document.getElementById('createcontact').style.display = 'none';
	document.getElementById('createfromcsv').style.display = '';
	document.getElementById('createfromemail').style.display = 'none';
}

function showCreateFromEmail(){
	document.getElementById('createcontactlist').style.display = '';
	document.getElementById('createcontact').style.display = 'none';
	document.getElementById('createfromcsv').style.display = 'none';
	document.getElementById('createfromemail').style.display = '';
}

function enterContact(){
var name=document.getElementById('enter_name').value;
var email = document.getElementById('enter_email').value;
var mobile = document.getElementById('enter_mobile').value;

// var reg = /(\S)+[@]{1}(\S)+[.]{1}(\w)+/;
// var reg = /(\S)+[@]{1}(\S)+[.]{1}(com|!(com)(\w){1,4})/;
var reg = /^[\!\@\#\$\%\^\&\*\~\-\+\=\?\<\>\.\,\w]+@\w+(\.[a-zA-Z]{2,3}){1,2}$/;
	if(name ==''){
		document.getElementById('notice1').innerHTML = 'Name cannot be empty.';
		document.getElementById('enter_name').focus();
		return;
	}else if(email == ''){
		document.getElementById('notice1').innerHTML = 'Email cannot be empty.';
		document.getElementById('enter_email').focus();
		return;
	}else if(email !='' && !reg.test(email)){
		document.getElementById('notice1').innerHTML = 'Wrong Email format.';
		document.getElementById('enter_email').focus();
		return;
	}
	

var img = "<?php echo "<img src = '".Yii::app()->baseUrl."/images/loading.gif'>";?>";
var url = "<?php echo Yii::app()->createUrl('Member/admin')?>";

<?php
		echo CHtml::ajax(
				array(
					"url" => CController::createUrl("Member/CreateMember"),
					"data" => "js:{name : name, email : email, mobile : mobile}",
					"type"=>"POST",
					'beforeSend'=>"js:function(){
						$(\".showbox\").stop(true).animate({'margin-top':'300px','opacity':'1'},200);
					}",
					"success"=>"js:function(data){
						var obj = eval('('+data+')');
						location.href = url;
						// if(typeof(obj.data) == 'undefined'){
							// if(obj.tip == 'ok'){
								// location.href = url;
							// }else{
								// document.getElementById('notice1').innerHTML = obj.tip;
							// }
						// }else{
							// location.href = homeUrl;
						// } 
						$(\".showbox\").stop(true).animate({'margin-top':'250px','opacity':'0'},400);
					}",
				)
			);
?>
}

function getCsvContacts(){
	$('#csvpopup').jqmShow();
	$('#dialog_contact1').html("");
	

	
	var url = "<?php echo Yii::app()->createUrl('Member/GetCSVMembers');?>";
	$.ajaxFileUpload ({
		url : url,
		secureuri :false,
		fileElementId :'csvfile',
		dataType : 'json',
		success : function (json){
			// console.info(json);
			// var jsonStr=eval('('+json+')');

			if(typeof(json.data) != "undefined"){
				location.href = homeUrl;
				return;
			}
			
			var jsonStr = json;
			/*html = '';
			for(var i=0;i<jsonStr.length;i++){
				html+='<tr id=check'+i+'>';
				html+='<td><input name=\'checkbox\' type=\'checkbox\'></td>';
				html+='<td id=check'+i+'_name>'+jsonStr[i].name+'</td>';
				html+='<td id=check'+i+'_email>'+jsonStr[i].email+'</td>';
				html+='<td id=check'+i+'_mobile>'+jsonStr[i].mobile+'</td>';
				html+='</tr>';
			}*/
			
			html = '';
			var k = 1;
			for(var i=0;i<jsonStr.length;i++){
				html += "<li class='main13bg2'><table width='591' border='0' cellspacing='0' cellpadding='0'><tr><td width='56' height='36' align='center'>";
				html += "<input name='checkbox' type='checkbox'></td>";
				html += "<td width='178' align='center' id='check"+i+"_name'>"+jsonStr[i].name+"</td>";
				html += "<td width='171' align='center' id='check"+i+"_email'>"+jsonStr[i].email+"</td>";
				html += "<td width='186' align='center' id='check"+i+"_mobile'>"+jsonStr[i].mobile+"</td>";
				if(k < jsonStr.length){
					html += "</tr></table></li><li class='main13bg3'></li>";
				}else{
					html += "</tr></table></li>";
				}
				k++;
			}
			html += "<li class='main13bg4'></li>";
			
			
			$(html).appendTo('#dialog_contact1');				
		},
		error: function (json, status, e){
			alert(e);
		}
	});
	return false;
}

function getMailChecked(){
	var obj = document.getElementsByName('checkbox');
	var url = "<?php echo Yii::app()->createUrl('Member/admin'); ?>";
	var strs = new Array();
	var count = 0;
	
	var name_arr = new Array();
	var email_arr = new Array();
	var mobile_arr = new Array();
	
	var length = obj.length;
	
	for(var i=0;i<length;i++){	
		if(obj[i].checked){
			count++;
		}
	}
	if(count == 0){
		alert('Please select at least one contact!');
		return;
	}else{
		for(var i=0;i<obj.length;i++){
			if(obj[i].checked){
				var name = document.getElementById('check'+i+'_name').innerHTML;
				var email = document.getElementById('check'+i+'_email').innerHTML;
				var mobile = document.getElementById('check'+i+'_mobile').innerHTML;
				name_arr.push(name);
				email_arr.push(email);
				mobile_arr.push(mobile);
			}
		}
	
		var img = "<?php echo "<img src = '".Yii::app()->baseUrl."/images/loading.gif'>";?>";
		var url = "<?php echo Yii::app()->createUrl('Member/Admin')?>";
		
		<?php
			echo CHtml::ajax(
				array(
					"url" => CController::createUrl("Member/SaveMembers"),
					"data" => "js:{name : name_arr, email : email_arr, mobile : mobile_arr, count : count}",
					"type"=>"POST",
					'beforeSend'=>"js:function(){
						// document.getElementById('notice_dialog').innerHTML = img;
						$(\".showbox\").stop(true).animate({'margin-top':'300px','opacity':'1'},200);
					}",
					"success"=>"js:function(data){
						// document.getElementById('notice_dialog').innerHTML = '';
						$(\".showbox\").stop(true).animate({'margin-top':'250px','opacity':'0'},400);
						
						if(data == 'ajaxsessionout'){
							location.href = homeUrl;
						}else{
							alert(data);
							location.href = url;
						}
					}",
				)
			);
		?>
	}
}

function getMailContacts(){
	
	var emailserver = document.getElementById('emailserver').value;
	var acc = document.getElementById('account').value;
	var sort = document.getElementById('serversort').innerHTML;
	var account = acc+sort;
	var psw = document.getElementById('psw').value;
	
	if(acc == ''){
		$('#notice3').html('Email name can not be empty.');
		document.getElementById('account').focus();
		return;
	}
	if(psw == ''){
		$('#notice3').html('Password can not be empty.');
		document.getElementById('psw').focus();
		return;
	}
	$('#mailpopup').jqmShow();
	
	var img = "<?php echo "<img src = '".Yii::app()->baseUrl."/images/loading.gif'>";?>";

	if(emailserver == 'gmail'){
	<?php
		
		echo CHtml::ajax(
				array(
					"url" => CController::createUrl("Member/GetGmailMembers"),
					"data" => "js:{email : account, psw : psw}",
					"type"=>"POST",
					'beforeSend'=>"js:function(){
						document.getElementById('dialog_contact').innerHTML = img;
					}",
					"success"=>"js:function(json){
						var jsonStr=eval('('+json+')');
						
						
						if(typeof(jsonStr.data) == 'undefined'){
							html = '';
							if(typeof(jsonStr.error) == 'undefined'){
								var k = 1;
								for(var i=0;i<jsonStr.length;i++){
									html += \"<li class='main13bg2'><table width='591' border='0' cellspacing='0' cellpadding='0'><tr><td width='56' height='36' align='center'>\";
									html += \"<input name='checkbox' type='checkbox'></td>\";
									html += \"<td width='178' align='center' id='check\"+i+\"_name'>\"+jsonStr[i].title+\"</td>\";
									html += \"<td width='171' align='center' id='check\"+i+\"_email'>\"+jsonStr[i].email+\"</td>\";
									html += \"<td width='186' align='center' id='check\"+i+\"_mobile'>\"+jsonStr[i].mobile+\"</td>\";
									if(k < jsonStr.length){
										html += \"</tr></table></li><li class='main13bg3'></li>\";
									}else{
										html += \"</tr></table></li>\";
									}
									k++;
								}
							
							}else{
								html += \"<li class='main13bg2' style='background: url(./images/bg_202.png) repeat-y;'><table width='591' border='0' cellspacing='0' cellpadding='0'><tr><td height='36' align='center'><span class='wrong'>\"+jsonStr.error+\"</span></td></tr></table></li>\";
							}
							
							html += \"<li class='main13bg4'></li>\";
							document.getElementById('dialog_contact').innerHTML = '';
							$(html).appendTo('#dialog_contact');
							
						}else{
							location.href = homeUrl;
							return;
						}
						
					}",
				)
			);
?> 
}else if(emailserver == 'yahoo'){
	<?php
		
		echo CHtml::ajax(
				array(
					"url" => CController::createUrl("Member/GetYahooMembers"),
					"data" => "js:{email : account, psw : psw}",
					"type"=>"POST",
					'beforeSend'=>"js:function(){
						document.getElementById('dialog_contact').innerHTML = img;
					}",
					"success"=>"js:function(json){
						var jsonStr=eval('('+json+')');
						
						if(typeof(jsonStr.data) == 'undefined'){
							html = '';
							
							if(typeof(jsonStr.error) == 'undefined'){
								var k = 1;
								for(var i=0;i<jsonStr.length;i++){
									html += \"<li class='main13bg2'><table width='591' border='0' cellspacing='0' cellpadding='0'><tr><td width='56' height='36' align='center'>\";
									html += \"<input name='checkbox' type='checkbox'></td>\";
									html += \"<td width='178' align='center' id='check\"+i+\"_name'>\"+jsonStr[i].first_name+\"</td>\";
									html += \"<td width='171' align='center' id='check\"+i+\"_email'>\"+jsonStr[i].email_1+\"</td>\";
									html += \"<td width='186' align='center' id='check\"+i+\"_mobile'></td>\";
									if(k < jsonStr.length){
										html += \"</tr></table></li><li class='main13bg3'></li>\";
									}else{
										html += \"</tr></table></li>\";
									}
									k++;
								}
							}else{
								html += \"<li class='main13bg2' style='background: url(./images/bg_202.png) repeat-y;'><table width='591' border='0' cellspacing='0' cellpadding='0'><tr><td height='36' align='center'><span class='wrong'>\"+jsonStr.error+\"</span></td></tr></table></li>\";
							}
							html += \"<li class='main13bg4'></li>\";
						
							document.getElementById('dialog_contact').innerHTML = '';
							$(html).appendTo('#dialog_contact');
						}else{
							location.href = homeUrl;
							return;
						}
						
					}",
				)
			);
?> 
}
    
}

function changeEmail(){
	var t = document.getElementById("emailserver"); 
	var email = t.options[t.selectedIndex].value;
	if(email == 'yahoo'){
		document.getElementById('serversort').innerHTML = '@yahoo.com';
	}else if(email == 'gmail'){
		document.getElementById('serversort').innerHTML = '@gmail.com';
	}
}


function getMailChecked(){
	var obj = document.getElementsByName('checkbox');
	var url = "<?php echo Yii::app()->createUrl('Member/admin'); ?>";
	var strs = new Array();
	var count = 0;
	
	var name_arr = new Array();
	var email_arr = new Array();
	var mobile_arr = new Array();
	
	var length = obj.length;
	
	for(var i=0;i<length;i++){	
		if(obj[i].checked){
			count++;
		}
	}
	if(count == 0){
		alert('Please select at least one contact!');
		return;
	}else{
		for(var i=0;i<obj.length;i++){
			if(obj[i].checked){
				var name = document.getElementById('check'+i+'_name').innerHTML;
				var email = document.getElementById('check'+i+'_email').innerHTML;
				var mobile = document.getElementById('check'+i+'_mobile').innerHTML;
				name_arr.push(name);
				email_arr.push(email);
				mobile_arr.push(mobile);
			}
		}
	
		var img = "<?php echo "<img src = '".Yii::app()->baseUrl."/images/loading.gif'>";?>";
		var url = "<?php echo Yii::app()->createUrl('Member/Admin')?>";
		
		<?php
			echo CHtml::ajax(
				array(
					"url" => CController::createUrl("Member/SaveMembers"),
					"data" => "js:{name : name_arr, email : email_arr, mobile : mobile_arr, count : count}",
					"type"=>"POST",
					'beforeSend'=>"js:function(){
						// document.getElementById('notice_dialog').innerHTML = img;
						$(\".showbox\").stop(true).animate({'margin-top':'300px','opacity':'1'},200);
					}",
					"success"=>"js:function(data){
						// document.getElementById('notice_dialog').innerHTML = '';
						$(\".showbox\").stop(true).animate({'margin-top':'250px','opacity':'0'},400);
						
						if(data == 'ajaxsessionout'){
							location.href = homeUrl;
						}else{
							// alert(data);
							location.href = url;
						}
					}",
				)
			);
		?>
	}
}

function editContacts(i){
	$("#editcontactspopup").jqmShow();
	document.getElementById("contactid").value = i;
	
	document.getElementById('tr1').style.display = 'none';
	document.getElementById('tr2').style.display = 'none';
	document.getElementById('tr3').style.display = 'none';
	
	
	<?php
		echo CHtml::ajax(array(
			"url" => CController::createUrl("Member/View"),
			"data" => "js:{contact : i}",
			"type"=>"POST",
			// 'beforeSend'=>"js:function(){
				// $(\".showbox\").stop(true).animate({'margin-top':'300px','opacity':'1'},200);
			// }",
			"success"=>"js:function(str){
				// $(\".showbox\").stop(true).animate({'margin-top':'250px','opacity':'0'},400);
				
				var data = eval('('+str+')');
				
				if(typeof(data.data) == 'undefined'){
					document.getElementById('edit_name').value = data.name;
					document.getElementById('edit_email').value = data.email;
					document.getElementById('edit_mobile').value = data.mobile;
				}else{
					location.href = homeUrl;
					return;
				}
				
			}",
		));
	?>
}

function saveEditContact(){
var name = document.getElementById('edit_name').value;
var email = document.getElementById('edit_email').value;
var mobile = document.getElementById('edit_mobile').value;
var contact = document.getElementById('contactid').value;

var reg = /^[\!\@\#\$\%\^\&\*\~\-\+\=\?\<\>\.\,\w]+@\w+(\.[a-zA-Z]{2,3}){1,2}$/;
	if(name ==''){
		document.getElementById('tr1').style.display = '';
		document.getElementById('tr2').style.display = 'none';
		document.getElementById('tr3').style.display = 'none';
		
		document.getElementById('error1').innerHTML = 'Name cannot be empty.';
		document.getElementById('edit_name').focus();
		return;
	}else if(email == ''){
		document.getElementById('tr1').style.display = 'none';
		document.getElementById('tr2').style.display = '';
		document.getElementById('tr3').style.display = 'none';
	
		document.getElementById('error2').innerHTML = 'Email cannot be empty.';
		document.getElementById('edit_email').focus();
		return;
	}else if(email !='' && !reg.test(email)){
		document.getElementById('tr1').style.display = 'none';
		document.getElementById('tr2').style.display = '';
		document.getElementById('tr3').style.display = 'none';
	
		document.getElementById('error2').innerHTML = 'Wrong Email format.';
		document.getElementById('edit_email').focus();
		return;
	}
	

var img = "<?php echo "<img src = '".Yii::app()->baseUrl."/images/loading.gif'>";?>";
var url = "<?php echo Yii::app()->createUrl('Member/admin')?>";
<?php
		echo CHtml::ajax(
				array(
					"url" => CController::createUrl("Member/Update"),
					"data" => "js:{id: contact,name : name, email : email, mobile : mobile}",
					"type"=>"POST",
					'beforeSend'=>"js:function(){
						
						$(\".showbox\").stop(true).animate({'margin-top':'300px','opacity':'1'},200);
					}",
					"success"=>"js:function(str){
						$(\".showbox\").stop(true).animate({'margin-top':'250px','opacity':'0'},400);
						
						$(\".showbox\").stop(true).animate({'margin-top':'250px','opacity':'0'},400);
						if(str == 'ok'){
							document.getElementById('tr1').style.display = 'none';
							document.getElementById('tr2').style.display = 'none';
							document.getElementById('tr3').style.display = 'none';
							
							document.getElementById('name_'+contact).innerHTML = name;
							document.getElementById('email_'+contact).innerHTML = email;
							document.getElementById('mobile_'+contact).innerHTML = mobile;
							
							$('#editcontactspopup').jqmHide();
						}else if(str == 'ajaxsessionout'){
							location.href = homeUrl;
						}
					}",
				)
			);
?>
}
</script>


</html>
