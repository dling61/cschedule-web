<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>Create an Activity</title>
<link href="./css/cschedule.css" rel="stylesheet" type="text/css" />
<script src="./datepicker/WdatePicker.js" type="text/javascript"></script>
<link href="<?php echo Yii::app()->baseUrl.'/css/jqModal.css';?>" rel="stylesheet" type="text/css" />
<script src="<?php echo Yii::app()->baseUrl.'/js/jqModal.js';?>" type="text/javascript"></script>

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
        <li><a href="<?php echo Yii::app()->createUrl('Service/Create')?>"  class="Selected1">Create an activity</a></li>
        <li><a href="<?php echo Yii::app()->createUrl('Service/Admin')?>">My activities</a></li>  
        <li><a href="<?php echo Yii::app()->createUrl('Member/Admin')?>">My contacts</a></li>
		<li><a href="<?php echo Yii::app()->createUrl('Schedule/Admin')?>">My schedules</a></li>
      </ul>
      <div class="clear"></div>
    </div>
  </div>
  <div class="title1">
    <div class="title1b">Create an activity</div>
  </div>
</div>

<div class="main3">

<!-- js loading start -->
<div id="AjaxLoading" class="showbox">
	<div class="loadingWord"><img src="./images/waiting.gif">Please Wait...</div>
</div>
<!-- js loading end-->


<!--dialog content-->
<!--<div class="main14">-->
<div class="jqmWindowShareContacts" id="sharepopup">
 <span id="hiddenactivity"></span>
 <span id="selectedemail"></span>
 
<div class="main10top"></div><div class="main10inter"><table width="670"  border="0" cellpadding="0" cellspacing="0"><tr>
    <td height="30" colspan="4" align="center" valign="middle"><span style=" font-size:18px;">Participants</span></td>
    </tr>
<!--<tr>
    <td height="30" colspan="4" align="center" valign="middle"><span class="wrong">错误信息</span></td>
    </tr>-->
  
  <tr id="shareloading">
  
  </tr>
  
  </table>
  <br>
  <table style="width:563px; border:1px solid #dbe2e7;margin-left:60px;">
  
  <tr>
    <td height="45">&nbsp;</td>
    <td>&nbsp;</td>
    <td><span class="color1">Can't find out a contact, add here.</span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan='3'><span class="wrong" id="notice"></span></td>
  </tr>
  <tr>
    <td height="25" valign="top"></td>
    <td></td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="3%" align="right"><img src="./images/bg_100.png" /></td>
        <td width="26%"><input type="text" class="sharebg209" placeholder="Name" id="name"></td>
        <td width="2%"><img src="./images/bg_100.png"></td>
        <td width="27%"><input type="text" class="sharebg209" placeholder="Email" id="email"></td>
        <td width="28%"><input type="text" class="sharebg209" placeholder="Mobile" id="mobile"></td>
        <td width="14%"><input class="conbu4" onclick="addNewContact()"></td>
      </tr>  
    </table></td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td height="25" valign="top"></td>
    <td></td>
    <td></td>
    <td>&nbsp;</td>
  </tr></div></table>
  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="43%" align="right">
        <label>
          <input type="button" class="cname5" onclick="submitSharedMembers()">
          </label>
      </td>
        <td width="6%">&nbsp;</td>
        <td width="51%">
        <label>
          <input type="button" class="cname6" onclick="cancelPopup()">
          </label>
     </td>
      </tr>
    </table></td>
    <td>&nbsp;</td>
  </tr>
</div>
  <!--<div class="main10buttom"> </div>-->
</div>
<!--dialog content-->



 

  <table width="365" border="0" cellspacing="0" cellpadding="0">
	<tr>
      <td width="89" height="20"></td>
      <td colspan="2" align="center"><span class="wrong" id="error"></span> </td>
    </tr>
  
    <tr>
      <td width="89" height="48"><span class="fontsize1">Name</span><span><img src="./images/bg_100.png" /></span></td>
      <td colspan="2"><input id="activityname" type="text" class="cname"></td>
    </tr>
   <!-- <tr>
      <td height="48"><span class="fontsize1">Start</span><span><img src="./images/bg_100.png" /></span></td>
      <td colspan="2"><input type="text" id="starttime" onfocus="WdatePicker({dateFmt:'yyyy/MM/dd HH:mm',lang:'en'})" / class="cname3"></td>
    </tr>
    <tr>
      <td height="48"><span class="fontsize1">End</span><span><img src="./images/bg_100.png" /></span></td>
      <td colspan="2"><input type="text" id="endtime" onfocus="WdatePicker({dateFmt:'yyyy/MM/dd HH:mm',lang:'en'})" / class="cname3"></td>
    </tr>-->

	<!--<tr>
      <td height="48"><span class="fontsize1">Timezone</span><span><img src="./images/bg_100.png" /></span></td>
      <td colspan="2"><select id="timezone"  class="cname4">
	  <?php
/*		$timezone_str = "<option value='none'>Please select a timezone</option>";
		foreach(getPartTimezones() as $timezonekey=>$timezonevals){
			$timezone_str .= "<option value='".$timezonekey."'>".$timezonevals."</option>";
		}
		echo $timezone_str;
	  */?>
	  </select></td>
    </tr>-->

    <tr>
      <td height="140"><span class="fontsize1">Description</span></td>
      <td colspan="2">
        <label>
          <textarea name="textarea" class="cname2" id="description"></textarea>
          </label>
          </td>
    </tr>
   <!-- <tr>
      <td height="48"><span class="fontsize1">Repeat</span></td>
      <td colspan="2"><form id="form2" name="form2" method="post" action="">
        <label>
          <select name="select" class="cname4" id="repeat">
            <option name="repeat" value='0' >None</option>
			<option name="repeat" value='1' >Every day</option>
			<option name="repeat" value='2' >Every week</option>
			<option name="repeat" value='3' >Every 2 weeks</option>
			<option name="repeat" value='4' >Every month</option>
			<option name="repeat" value='5' >Every year</option>
          </select>
          </label>
      </form>      </td>
    </tr>-->

   <!-- <tr>
      <td height="48"><span class="fontsize1">Alert</span></td>
      <td colspan="2"><form id="form2" name="form2" method="post" action="">
        <label>
          <select name="select" class="cname4" id="alert">
            <option name="alert" value='0' >None</option>
			<option name="alert" value='1' >5 minutes before</option>
			<option name="alert" value='2' >15 minutes before</option>
			<option name="alert" value='3' >30 minutes before</option>
			<option name="alert" value='4' >1 hour before</option>
			<option name="alert" value='5' >2 hour before</option>
			<option name="alert" value='6' >1 day before</option>
			<option name="alert" value='7' >2 day before</option>
			<option name="alert" value='8' >3 day before</option>
			<option name="alert" value='9' >7 day before</option>
          </select>
          </label>
      </form></td>
    </tr>-->
	
	<!--<tr>
	<td height="48">&nbsp;</td>
	<td colspan="2" class="font1" style="font-weight:bold;">
	<hr>Based on the "Alert" setting, CSchedule will send reminder emails to those "on duty" in the schedules associated with this activity.<hr></td>
	</tr>-->
    
	<tr>
      <td height="48">&nbsp;</td>
      <td width="144"><form id="form3" name="form3" method="post">
        <label>
          <input type="button" onclick="servicePath()" class="cname5">
          </label>
      </form>
      </td>
      <td width="132"><form id="form4" name="form4" method="post">
        <label>
          <input type="button" name="Submit2" onclick="cancel()" value="" / class="cname6">
          </label>
      </form>
      </td>
    </tr>
  </table>
</div>

<?php include_once(dirname(dirname(__FILE__)).'/footer.php');?>

</body>
<script language='javascript'>
	var timezoneOffset = -(new Date().getTimezoneOffset()/60);
	// alert(timezoneOffset);
	// document.getElementById("timezone").value = timezoneOffset;

	jQuery(document).ready(function() {
		jQuery("#sharepopup").jqm({
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
	
	function   formatDate(now){     
          var   year=now.getFullYear();     
          var   month=now.getMonth()+1;     
          var   date=now.getDate();     
          var   hour=now.getHours();     
          var   minute=now.getMinutes();         
          return   year+"-"+month+"-"+date+" "+hour+":"+minute;     
	}
	
	/*function checkValue(){
		var name = document.getElementById('activityname').value;
		var starttime = document.getElementById('starttime').value;
		var endtime = document.getElementById('endtime').value;
		var desp = document.getElementById('description').value;
		var repeat = document.getElementById('repeat').value;
		var alerts = document.getElementById('alert').value;
		var timezone = document.getElementById('timezone').value;
		if(name == ''){
			document.getElementById('error').innerHTML = 'Name cannot be blank.';
			document.getElementById('activityname').focus();
			return false;
		}else if(starttime == ''){
			document.getElementById('error').innerHTML = 'Start Time cannot be blank.';
			document.getElementById('starttime').focus();
			return false;
		}else if(endtime == ''){
			document.getElementById('error').innerHTML = 'End Time cannot be blank.';
			document.getElementById('endtime').focus();
			return false;
		}else if(timezone == "none"){
			document.getElementById('error').innerHTML = 'Please select a timezone.';
			document.getElementById('timezone').focus();
			return false;
		}
		
		if(starttime >= endtime){
			document.getElementById('error').innerHTML = 'Start should be smaller than End.';
			document.getElementById('starttime').focus();
			return false;
		}
		
		// var d = new Date();
		// var clientutc = d.getTimezoneOffset()/60;  
		
		// var utc_starttime = (Date.parse(starttime)-clientutc*3600*1000)/1000;
		// var utc_endtime = (Date.parse(endtime)-clientutc*3600*1000)/1000;
		
		var url = "<?php echo Yii::app()->createUrl('Service/Admin');?>";
		
		var homeUrl = "<?php echo Yii::app()->homeUrl;?>"
		
			<?php
			echo CHtml::ajax(
				array(
					"url" => CController::createUrl("Service/InsertService"),
					"data" => "js:{name : name, starttime : starttime, endtime : endtime, desp : desp, repeat : repeat,alerts : alerts,timezone:timezone}",
					"type"=>"POST",
					'beforeSend'=>"js:function(){
						$(\".showbox\").stop(true).animate({'margin-top':'300px','opacity':'1'},200);
					}",
					"success"=>"js:function(data){
						if(data == 'ok'){
							location.href=url;
						}else if(data =='ajaxsessionout'){
							location.href = homeUrl;
						}else{
							document.getElementById('error').innerHTML = 'The network is busy, please try it later.';
							// return false;
						}
						$(\".showbox\").stop(true).animate({'margin-top':'250px','opacity':'0'},400);
					}",
				)
			);
	?>
	}*/

	
	function cancel(){
		location.href = "<?php echo Yii::app()->createUrl('Service/admin');?>";
	}
	
	function servicePath(){
		var name = document.getElementById('activityname').value;
		var desp = document.getElementById('description').value;
		if(name == ''){
			document.getElementById('error').innerHTML = 'Name cannot be blank.';
			document.getElementById('activityname').focus();
			return false;
		}
		
		var url = "<?php echo Yii::app()->createUrl('Service/Admin');?>";
		
		var homeUrl = "<?php echo Yii::app()->homeUrl;?>";
		
			<?php
			echo CHtml::ajax(
				array(
					"url" => CController::createUrl("Service/ServicePath"),
					"data" => "js:{name : name, desp : desp}",
					"type"=>"POST",
					'beforeSend'=>"js:function(){
						$(\".showbox\").stop(true).animate({'margin-top':'300px','opacity':'1'},200);
					}",
					"success"=>"js:function(data){
						if(data =='ajaxsessionout'){
							location.href = homeUrl;
						}else{							
							$('#sharepopup').jqmShow();
							
							document.getElementById(\"name\").value = \"\";
							document.getElementById(\"email\").value = \"\";

	
							document.getElementById('shareloading').innerHTML = '';
							$('#shareloading').html(data); 
						}
						$(\".showbox\").stop(true).animate({'margin-top':'250px','opacity':'0'},400);
						
					}",
				)
			);
	?>
	}

function showAddButton(){
	document.getElementById("addnewcontact").style.display = "";
}

function hideAddButton(){
	document.getElementById("name").value = "";
	document.getElementById("email").value = "";
	document.getElementById("mobile").value = "";
	
	document.getElementById("notice").innerHTML = "";
	
	document.getElementById("addnewcontact").style.display = "none";
}

function addNewContact(){
	document.getElementById('notice').innerHTML = '';
	
	var name = document.getElementById("name").value;
	var email = document.getElementById("email").value;
	var mobile = document.getElementById("mobile").value;
	var reg = /^[\!\@\#\$\%\^\&\*\~\-\+\=\?\<\>\.\,\w]+@\w+(\.[a-zA-Z]{2,3}){1,2}$/;
	if(name == ""){
		document.getElementById("notice").innerHTML = "Name can not be empty.";
		document.getElementById("name").focus();
		return;
	}
	
	if(name != ''){
		var namearr = name.split('@');
		if(namearr.length > 1){
			document.getElementById('notice').innerHTML = 'No @ in the name.';
			document.getElementById('name').focus();
			return;
		}
	}
	if(email == ""){
		document.getElementById("notice").innerHTML = "Email can not be empty.";
		document.getElementById("email").focus();
		return;
	}
	if(email !='' && !reg.test(email)){
		document.getElementById('notice').innerHTML = 'Wrong Email format.';
		document.getElementById('email').focus();
		return;
	}
	
	var url = "<?php echo Yii::app()->homeUrl;?>";
	
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
						if(typeof(obj.data) != 'undefined'){
							
							// $(\"<input type='hidden' name='o_share' id='oshare_\"+obj.id+\"' value='-1' >\").appendTo('#newadded_share');
							// $(\"<input type='hidden' name='n_share' id='nshare_\"+obj.id+\"' value='-1' >\").appendTo('#newadded_share');	
							// $(\"<li class='sharebg6'><table width='527' border='0' cellspacing='0' cellpadding='0'><tr><td width='35'>&nbsp;</td><td width='154' height='33' id='name_\"+obj.id+\"'>\"+name+\"</td><td width='222' id='email_\"+obj.id+\"'>\"+email+\"</td><td width='116'><select  id='\"+obj.id+\"' name = 'selectdMembers' onchange='changerole(\"+obj.id+\")'><option value='-1'>Noshare</option><option value ='2'>Participant</option><option value ='1'>Organizer</option></select></td></tr></table></li>\").appendTo('#shareloading');
							// $(\"<li class='sharebg6'><table width='527' border='0' cellspacing='0' cellpadding='0'><tr><td width='35'>&nbsp;</td><td width='154' height='33' id='name_\"+obj.id+\"'>\"+name+\"</td><td width='222' id='email_\"+obj.id+\"'>\"+email+\"</td><td width='116'><select  id='\"+obj.id+\"' name = 'selectdMembers' onchange='changerole(\"+obj.id+\")'><option value='-1'>Noshare</option><option value ='2'>Participant</option></select></td></tr></table></li>\").appendTo('#shareloading');
							
							if(obj.tip == 'ok'){
								$(\"<li><table border='0' cellspacing='0' cellpadding='0'><tr><td width='25'><input name='contact_check' type='checkbox' id='\"+obj.id+\"_check' onclick='is_Checked(\"+obj.id+\")'></td><td id='\"+obj.id+\"_name'>\"+name+\"</td></tr></table></li>\").appendTo('#addnewcontact');
								
								$('#notice').html('');
								$('#email').val('');
								$('#name').val('');
								$('#mobile').val('');
							}else{
								document.getElementById('notice').innerHTML = obj.data;
							}
							
						}else{
							location.href = url;
						}
						$(\".showbox\").stop(true).animate({'margin-top':'250px','opacity':'0'},400);
					}",
				)
			);
?>
}

function cancelPopup(){
	$("#sharepopup").jqmHide();
	var serviceurl = "<?php echo Yii::app()->CreateUrl('Service/Admin');?>";
	location.href = serviceurl;
}

function changerole(v){
	$('#sharecontacts').show();
	$('#done').hide();
	
	var val = document.getElementById(v).value;
	document.getElementById('nshare_'+v).value = val;	
}

	
function submitSharedMembers(){
	var activity = $('#sharedActivityid').val();
	
	var members = '';
	var names = '';
	var emails = '';
	var mobiles = '';
	
	var checked = document.getElementsByName("contact_check");
	for(var i=0;i<checked.length;i++){
		if(checked[i].checked){
			var id = checked[i].id;
			names += ','+$('#'+id.substr(0,id.length-6)+'_name').html();
			emails += ','+$('#'+id.substr(0,id.length-6)+'_all').val();
			members += '_'+id.substr(0,id.length-6);
			mobiles += ','+$('#'+id.substr(0,id.length-6)+'_allmobile').val();
		}
	}

	var url = "<?php echo Yii::app()->homeUrl;?>";
	var serviceurl = "<?php echo Yii::app()->CreateUrl('Service/Admin');?>";
	<?php
		echo CHtml::ajax(array(
			"url" => CController::createUrl("Service/AddSharedMembers"),
			"data" => "js:{activity : activity, members : members.substr(1),emails:emails.substr(1),names:names.substr(1),mobiles:mobiles.substr(1)}",
			"type"=>"POST",
			'beforeSend'=>"js:function(){
				$(\".showbox\").stop(true).animate({'margin-top':'300px','opacity':'1'},200);
			}",
			"success"=>"js:function(str){
				$(\".showbox\").stop(true).animate({'margin-top':'250px','opacity':'0'},400);
				
				if(str == 'ajaxsessionout'){
					location.href = url;
					return;
				}

				$('#sharepopup').jqmHide();
				location.href = serviceurl;
			}",
		));
	?>
}

function cancelSharedMembers(){
	var o_share = document.getElementsByName('o_share');
	var n_share = document.getElementsByName('n_share');
	if(o_share && n_share){
		for(var i=0;i<o_share.length;i++){
			var id = n_share[i].id;
			document.getElementById(id).value = o_share[i].value;
			
			if(o_share[i].value == -1)
				var index = 0;
			if(o_share[i].value == 2)
				var index = 1;
			if(o_share[i].value == 1)
				var index = 2;
			document.getElementById(id.substr(7)).selectedIndex = index;
		}
	}
	
	document.getElementById('sharecontacts').style.display = 'none';
	
	$('#done').show();
}

function done(){
		var activity = $('#sharedActivityid').val();
		var leadurl = "<?php echo Yii::app()->createUrl("Schedule/Admin");?>";
		location.href = leadurl+"&activity="+activity;
}

function is_Checked(i){
	var status = document.getElementById(i+'_check').checked;
	if(status){
		var name = document.getElementById(i+'_name').innerHTML;
			
		$("<li id='"+i+"_selected'><table border='0' cellspacing='0' cellpadding='0'><tr><td><span class='name'><a href='#'>"+name+"</a></span></td><td width='25' onclick='deleteContact("+i+")'><span class='cha' style='cursor:pointer;'></span></td></tr></table></li>").appendTo('#editonduty');
	}else{
		$('#'+i+'_selected').remove();
	}
}

function deleteContact(i){
	$('#'+i+'_selected').remove();
	$('#'+i+'_check').removeAttr("checked");;
}
</script>
</html>
