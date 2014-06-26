<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>My Activities</title>
<link href="<?php echo Yii::app()->baseUrl.'/css/cschedule.css';?>" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->baseUrl.'/css/jqModal.css';?>" rel="stylesheet" type="text/css" />
<script src="<?php echo Yii::app()->baseUrl.'/js/jqModal.js';?>" type="text/javascript"></script>

<script src="./datepicker/WdatePicker.js" type="text/javascript"></script>


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
        <li><a href="<?php echo Yii::app()->createUrl('Service/Admin')?>" class="Selected1">My activities</a></li>
        <li><a href="<?php echo Yii::app()->createUrl('Member/Admin')?>">My contacts</a></li>
		<li><a href="<?php echo Yii::app()->createUrl('Schedule/Admin')?>">My schedules</a></li>
      </ul>
      <div class="clear"></div>
    </div>
  </div>
  <div class="title1">
    <div class="title1b">My activities</div>
  </div>
</div>

<!--dialog content-->
<!--<div class="main14">-->
<div class="jqmWindowShareContacts" id="sharepopup">
  <div class="sharebg1"></div>
  <div class="sharebg2">
    <div class="sharebg4"></div>
    <div class="sharebg5">
      <ul id="shareloading">
	  

      </ul>
	  <span id='newadded_share'></span>
	  <span id="hiddenactivity"></span>
    </div>
	<div class="sharebg8"></div>
	<div class="sharebg9"></div>
	<div class="share10"><table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
    <td colspan="5" align="center"><span class="wrong" id="notice"></span></td>
    </tr>
	
  <tr>
    <td width="4%" align="right"><img src="./images/bg_100.png" /></td>
    <td width="29%"><input type="text" class="sharebg11" placeholder="Name" id="name" onkeyup="showAddButton()"></td>
    <td width="3%"><img src="./images/bg_100.png"></td>
    <td width="32%"><input type="text" class="sharebg11" placeholder="Email" id="email" onfocus="showAddButton()"></td>
    <td width="32%"><input type="text" class="sharebg11" placeholder="Mobile" id="mobile" onfocus="showAddButton()"></td>
  </tr>
 <tr><td height="20"></td>
 </tr>
   <tr>
    <td width="4%" align="right"></td>
    <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
	
<!-- add a new contact-->
  <tr id="addnewcontact" style="display:none">
    <td width="46%" align="right"><input type="button" class="conbu4" onclick="addNewContact()"></td>
    <td width="6%">&nbsp;</td>
    <td width="48%"><input type="button" class="cname6" onclick="hideAddButton()"></td>
  </tr>
<!-- add a new contact--> 

</table>
</td>
    </tr>
</table>
</div>
<div class="sharebg8"></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="46%">&nbsp;</td>
    <td width="5%">&nbsp;</td>
    <td width="49%">&nbsp;</td>
  </tr>
  
 <!-- share contacts--> 
  <tr id="sharecontacts" style="display:none">
    <td align="right">  <input type="button" class="cname5" onclick="submitSharedMembers()"></td>
    <td>&nbsp;</td>
    <td><input type="button" class="cname6" onclick="cancelSharedMembers()"></td>
  </tr>
 <!-- share contacts--> 
 
  <tr id="done">
    <td colspan="3" align="center"><span class='jqmClose'><input type="button" class="main13bu3"></span></td>
    </tr>
</table>


  </div>
  <div class="sharebg3"></div>
</div>
<!--dialog content-->



<!--view dialog content-->
<div class="jqmWindowViewActivity" id="viewactivitypopup">
  <table width="365" border="0" cellspacing="0" cellpadding="0">
 
    <tr>
      <td width="89" height="48"><span class="fontsize1">Name</span></td>
      <td><input type="text" id='viewname' class="cname" disabled></td>
    </tr>
    <tr>
      <td height="48"><span class="fontsize1">Start</span></td>
      <td><input type="text" id='viewstart' class="cname3" disabled></td>
    </tr>
    <tr>
      <td height="48"><span class="fontsize1">End</span></td>
      <td><input type="text" class="cname3" id='viewend' disabled></td>
    </tr>
	
	<tr>
      <td height="48"><span class="fontsize1">Timezone</span></td>
      <td><select id='viewtimezone' class="cname4" disabled><option value='-11'>US/Samoa</option><option value='-10'>US/Hawaii</option><option value='-9'>US/Alaska</option><option value='-8'>US/Pacific</option><option value='-7'>US/Arizona &amp; US/Mountain</option><option value='-6'>US/Central</option><option value='-5'>US/Eastern &amp; US/East-Indiana</option><option value='-4'>Canada/Atlantic</option><option value='-3.5'>Canada/Newfoundland</option>
	  
	  </select></td>
    </tr>
	
    <tr>
      <td height="140"><span class="fontsize1">Descripion</span></td>
      <td>
        <label>
          <textarea name="textarea" id="viewdesp" class="cname2" disabled></textarea>
          </label>
          </td>
    </tr>
    <tr>
      <td height="48"><span class="fontsize1">Repeat</span></td>
      <td>
        <label>
          <select name="select" class="cname4" id="viewrepeat" disabled>
            <option value="0">None</option>
		  <option value="1">Every day</option>
		  <option value="2">Every week</option>
		  <option value="3">Every 2 weeks</option>
		  <option value="4">Every month</option>
		  <option value="5">Every year</option>
          </select>
          </label>
           </td>
    </tr>
    <tr>
      <td height="48"><span class="fontsize1">Alert</span></td>
      <td>
        <label>
        <select name="select2" class="cname4" id="viewalert" disabled>
           <option value="0">None</option>
		  <option value="1">5 minutes before</option>
		  <option value="2">15 minutes before</option>
		  <option value="3">30 minutes before</option>
		  <option value="4">1 hour before</option>
		  <option value="5">2 hour before</option>
		  <option value="6">1 day before</option>
		  <option value="7">2 day before</option>
		  <option value="8">3 day before</option>
		  <option value="9">7 day before</option>
        </select>
        </label>
     </td>
    </tr>
    <tr>
      <td height="48">&nbsp;</td>
      <td align="center"> <span class="jqmClose"><input type="button" class="main13bu3"></span></td>
    </tr>
  </table>
</div><!--view dialog content-->

<input id="activityid" type="hidden"></span>
<!--edit dialog content-->
<div class="jqmWindowEditActivity" id="editactivitypopup">
  <table width="365" border="0" cellspacing="0" cellpadding="0">
	<tr id="tr1" style="display:none">
      <td width="89" height="20"></td>
      <td colspan="2" align="center"><span class="wrong" id="error1"></span> </td>
    </tr>
	
	
    <tr>
      <td width="89" height="48"><span class="fontsize1">Name</span><img src="./images/bg_100.png" width="14" height="14"></td>
      <td colspan="2"><input type="text" class="cname" id="editname"></td>
    </tr>
	
	<tr id="tr2" style="display:none">
      <td width="89" height="20"></td>
      <td colspan="2" align="center"><span class="wrong" id="error2"></span> </td>
    </tr>
	
    <tr>
      <td height="48"><span class="fontsize1">Start</span><img src="./images/bg_100.png" width="14" height="14"></td>
      <td colspan="2"><input type="text" class="cname3" id="editstart" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',lang:'en'})"></td>
    </tr>
	
	<tr id="tr3" style="display:none">
      <td width="89" height="20"></td>
      <td colspan="2" align="center"><span class="wrong" id="error3"></span> </td>
    </tr>
	
    <tr>
      <td height="48"><span class="fontsize1">End</span><img src="./images/bg_100.png" width="14" height="14"></td>
      <td colspan="2"><input type="text" class="cname3" id="editend" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',lang:'en'})"></td>
    </tr>
	
	<tr>
      <td height="48"><span class="fontsize1">Timezone</span><img src="./images/bg_100.png" width="14" height="14"></td>
      <td colspan="2"><select id='edittimezone' class="cname4" disabled>
	  <option value='-11'>US/Samoa</option><option value='-10'>US/Hawaii</option><option value='-9'>US/Alaska</option><option value='-8'>US/Pacific</option><option value='-7'>US/Arizona &amp; US/Mountain</option><option value='-6'>US/Central</option><option value='-5'>US/Eastern &amp; US/East-Indiana</option><option value='-4'>Canada/Atlantic</option><option value='-3.5'>Canada/Newfoundland</option>
	  
	  </select></td>
    </tr>
	
    <tr>
      <td height="140"><span class="fontsize1">Descripion</span></td>
      <td colspan="2">
        <label>
          <textarea name="textarea" class="cname2" id="editdesp"></textarea>
          </label>
      </td>
    </tr>
    <tr>
      <td height="48"><span class="fontsize1">Repeat</span></td>
      <td colspan="2">
        <label>
          <select name="select" class="cname4" id="editrepeat">
		  
		  <option value="0">None</option>
		  <option value="1">Every day</option>
		  <option value="2">Every week</option>
		  <option value="3">Every 2 weeks</option>
		  <option value="4">Every month</option>
		  <option value="5">Every year</option>
		  
          </select>
          </label>
      </td>
    </tr>
    <tr>
      <td height="48"><span class="fontsize1">Alert</span></td>
      <td colspan="2">
        <label>
          <select name="select" class="cname4" id="editalert">
			
		  <option value="0">None</option>
		  <option value="1">5 minutes before</option>
		  <option value="2">15 minutes before</option>
		  <option value="3">30 minutes before</option>
		  <option value="4">1 hour before</option>
		  <option value="5">2 hour before</option>
		  <option value="6">1 day before</option>
		  <option value="7">2 day before</option>
		  <option value="8">3 day before</option>
		  <option value="9">7 day before</option>
		  
          </select>
          </label>
      </td>
    </tr>
    <tr>
      <td height="48">&nbsp;</td>
      <td width="144">
        <input type="button" class="cname5" onclick="saveActivity()">
        </label>
      </td>
      <td width="132">
        <label>
          <span class="jqmClose"><input type="button" class="cname6"></span>
          </label>
      </td>
    </tr>
  </table>
</div><!--edit dialog content-->



<div class="main2">

<!-- js loading start -->
<div id="AjaxLoading" class="showbox">
	<div class="loadingWord"><img src="./images/waiting.gif">Please Wait...</div>
</div>
<!-- js loading end-->

  <ul>
    <li class="tabletitle"></li>
	<?php
		$str = "";
		if($services){
			$i = 1;
			$count = count($services);
			foreach($services as $vals){
				if($i < $count){
					if($vals->sharedrole == 0){
						$str .= "<li class='tablebg'>
      <table width='812' border='0' cellspacing='0' cellpadding='0'>
        <tr>
          <td width='521'><span class='table1' id='activityname_".$vals->serviceid."'>".$vals->servicename."</span></td>
          <td width='58'><span class='table2'><a onclick='sharePopup(".$vals->serviceid.")' cursor:pointer; title='Share'
></a></span></td>
          <td width='58'><span class='table3'><a href='".Yii::app()->createUrl('Schedule/Admin',array('activity'=>$vals->serviceid))."' cursor:pointer; title='Schedules'
></a></span></td>
          <td width='58'><span class='table4' onclick='viewActivity(".$vals->serviceid.")'><a cursor:pointer; title='View'
></a></span></td>
          <td width='58'><span class='table5'><a onclick='editActivity(".$vals->serviceid.")' cursor:pointer; title='Edit'
></a></span></td>
          <td width='58'><span class='table6'><a onclick='deleteActivity(".$vals->serviceid.")' cursor:pointer; title='Delete'
></a></span></td>
        </tr>
      </table>
    </li><li class='cutoff'></li>";
					}else if($vals->sharedrole == 1){
						$str .= "<li class='tablebg'>
      <table width='812' border='0' cellspacing='0' cellpadding='0'>
        <tr>
          <td width='521'><span class='table1' id='activityname_".$vals->serviceid."'>".$vals->servicename."</span></td>
          <td width='58'><span class='table2'><a onclick='sharePopup(".$vals->serviceid.")' cursor:pointer; title='Share'
></a></span></td>
          <td width='58'><span class='table3'><a href='".Yii::app()->createUrl('Schedule/Admin',array('activity'=>$vals->serviceid))."' cursor:pointer; title='Schedules'
></a></span></td>
          <td width='58'><span class='table4' onclick='viewActivity(".$vals->serviceid.")'><a cursor:pointer; title='View'
></a></span></td>
          <td width='58'><span class='table5'><a onclick='editActivity(".$vals->serviceid.")' cursor:pointer; title='Edit'
></a></span></td>
          <td width='58'><span class='table6'><a cursor:pointer; title='Delete'
></a></span></td>
        </tr>
      </table>
    </li><li class='cutoff'></li>";
					}else if($vals->sharedrole == 2){
						$str .= "<li class='tablebg'>
      <table width='812' border='0' cellspacing='0' cellpadding='0'>
        <tr>
          <td width='521'><span class='table1' id='activityname_".$vals->serviceid."'>".$vals->servicename."</span></td>
         <td width='58'><span class='table2'><a  style='cursor:pointer;' title='Share'
></a></span></td>
          <td width='58'><span class='table3'><a href='".Yii::app()->createUrl('Schedule/Admin',array('activity'=>$vals->serviceid))."' cursor:pointer; title='Schedules'
></a></span></td>
          <td width='58'><span class='table4' onclick='viewActivity(".$vals->serviceid.")'><a cursor:pointer; title='View'
></a></span></td>
         <td width='58'><span class='table5'><a style='cursor:pointer;' title='Edit'
></a></span></td>
          <td width='58'><span class='table6'><a style='cursor:pointer;' title='Delete'
></a></span></td>
        </tr>
      </table>
    </li><li class='cutoff'></li>";
					}
					
				}else{
					if($vals->sharedrole == 0){
						$str .= "<li class='tablebg2'>
      <table width='812' border='0' cellspacing='0' cellpadding='0'>
        <tr>
          <td width='521'><span class='table1' id='activityname_".$vals->serviceid."'>".$vals->servicename."</span></td>
          <td width='58'><span class='table2'><a onclick='sharePopup(".$vals->serviceid.")' cursor:pointer; title='Share'
></a></span></td>
          <td width='58'><span class='table3'><a href='".Yii::app()->createUrl('Schedule/Admin',array('activity'=>$vals->serviceid))."' cursor:pointer; title='Schedules'
></a></span></td>
          <td width='58'><span class='table4' onclick='viewActivity(".$vals->serviceid.")'><a cursor:pointer; title='View'
></a></span></td>
          <td width='58'><span class='table5'><a  onclick='editActivity(".$vals->serviceid.")' cursor:pointer; title='Edit'
></a></span></td>
          <td width='58'><span class='table6'><a onclick='deleteActivity(".$vals->serviceid.")' cursor:pointer; title='Delete'
></a></span></td>
        </tr>
      </table>
    </li>";
			}else if($vals->sharedrole == 1){
				$str .= "<li class='tablebg2'>
      <table width='812' border='0' cellspacing='0' cellpadding='0'>
        <tr>
          <td width='521'><span class='table1' id='activityname_".$vals->serviceid."'>".$vals->servicename."</span></td>
          <td width='58'><span class='table2'><a onclick='sharePopup(".$vals->serviceid.")' cursor:pointer; title='Share'
></a></span></td>
          <td width='58'><span class='table3'><a href='".Yii::app()->createUrl('Schedule/Admin',array('activity'=>$vals->serviceid))."' cursor:pointer; title='Schedules'
></a></span></td>
          <td width='58'><span class='table4' onclick='viewActivity(".$vals->serviceid.")'><a cursor:pointer; title='View'
></a></span></td>
          <td width='58'><span class='table5'><a  onclick='editActivity(".$vals->serviceid.")' cursor:pointer; title='Edit'
></a></span></td>
          <td width='58'><span class='table6'><a cursor:pointer; title='Delete'
></a></span></td>
        </tr>
      </table>
    </li>";
			}else if($vals->sharedrole == 2){
				$str .= "<li class='tablebg2'>
      <table width='812' border='0' cellspacing='0' cellpadding='0'>
        <tr>
          <td width='521'><span class='table1' id='activityname_".$vals->serviceid."'>".$vals->servicename."</span></td>
          <td width='58'><span class='table2'><a  style='cursor:pointer;' title='Share'
></a></span></td>
          <td width='58'><span class='table3'><a href='".Yii::app()->createUrl('Schedule/Admin',array('activity'=>$vals->serviceid))."' cursor:pointer; title='Schedules'
></a></span></td>
          <td width='58'><span class='table4' onclick='viewActivity(".$vals->serviceid.")'><a cursor:pointer; title='View'
></a></span></td>
          <td width='58'><span class='table5'><a style='cursor:pointer;' title='Edit'
></a></span></td>
          <td width='58'><span class='table6'><a style='cursor:pointer;' title='Delete'
></a></span></td>
        </tr>
      </table>
    </li>";
			}
		}
			$i++;
			}
		}else $str.="<li class='tablebg2'>
      <table width='812' border='0' cellspacing='0' cellpadding='0'>
        <tr>
          <td width='521'><span class='table1'>No activities found.</span></td>
          <td width='58'><span class='table2'><a  style='cursor:pointer;' title='Share'
></a></span></td>
          <td width='58'><span class='table3'><a  style='cursor:pointer;' title='Schedules'
></a></span></td>
          <td width='58'><span class='table4'><a style='cursor:pointer;' title='View'
></a></span></td>
          <td width='58'><span class='table5'><a style='cursor:pointer;' title='Edit'
></a></span></td>
          <td width='58'><span class='table6'><a style='cursor:pointer;' title='Delete'
></a></span></td>
        </tr>
      </table>
    </li>";
		
		echo $str;
	?>
	
  </ul>
</div>

<?php include_once(dirname(dirname(__FILE__)).'/footer.php');?>

</body>

<script language='javascript'>
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
	
	jQuery("#viewactivitypopup").jqm({
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
	
	jQuery("#editactivitypopup").jqm({
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

function   formatDate(now)   {     
          var   year=now.getFullYear();     
          var   month=now.getMonth()+1;     
          var   date=now.getDate();     
          var   hour=now.getHours();     
          var   minute=now.getMinutes();         
          return   year+"/"+month+"/"+date+" "+hour+":"+minute;     
}

function sharePopup(q){
	$('#sharepopup').jqmShow();
	
	var url = "<?php echo Yii::app()->homeUrl;?>";
	
	document.getElementById("name").value = "";
	document.getElementById("email").value = "";
	document.getElementById("mobile").value = "";
	
	document.getElementById("notice").innerHTML = "";
	
	document.getElementById("addnewcontact").style.display = "none";
	document.getElementById("sharecontacts").style.display = "none";
	
	$("<input type='hidden' value='"+q+"' id='sharedActivityid'>").appendTo('#hiddenactivity');
	
	
	var img = "<img src = '<?php echo Yii::app()->baseUrl.'/images/loading.gif';?>'>";
	<?php
		echo CHtml::ajax(array(
			"url" => CController::createUrl("Service/GetSharedMembers"),
			"data" => "js:{activityid:q}",
			"type"=>"POST",
			'beforeSend'=>"js:function(){
				document.getElementById('shareloading').innerHTML = img;
			}",
			"success"=>"js:function(data){
				if(data == 'ajaxsessionout'){
								location.href = url;
				}else{
					document.getElementById('shareloading').innerHTML = '';
					$('#shareloading').html('');
					$('#shareloading').html(data);
				}
			}",
		));
	?>
	
}

function deleteActivity(i){	
	var url = "<?php echo Yii::app()->homeUrl;?>";
	<?php
		echo CHtml::ajax(
			array(
					"url" => CController::createUrl("Service/delete"),
					"data" => "js:{id : i}",
					"type"=>"POST",
					'beforeSend'=>"js:function(){
						if(confirm('Are you sure to delete the activity and the schedules associated with it?')){
							$(\".showbox\").stop(true).animate({'margin-top':'300px','opacity':'1'},200);
						}else{
							return false;
						}
					}",
					"success"=>"js:function(data){
							if(data=='ok'){
								history.go(0);
							}else if(data == 'ajaxsessionout'){
								location.href = url;
							}else{
								alert('Fail to delete the activity.');
								// alert(data);
							}
							
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
	var name = document.getElementById("name").value;
	var email = document.getElementById("email").value;
	var mobile = document.getElementById("mobile").value;
	var reg = /^[\!\@\#\$\%\^\&\*\~\-\+\=\?\<\>\.\,\w]+@\w+(\.[a-zA-Z]{2,3}){1,2}$/;
	if(name == ""){
		document.getElementById("notice").innerHTML = "Name can not be empty.";
		document.getElementById("name").focus();
		return;
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
							
							$(\"<input type='hidden' name='o_share' id='oshare_\"+obj.id+\"' value='-1' >\").appendTo('#newadded_share');
							$(\"<input type='hidden' name='n_share' id='nshare_\"+obj.id+\"' value='-1' >\").appendTo('#newadded_share');	
							$(\"<li class='sharebg6'><table width='527' border='0' cellspacing='0' cellpadding='0'><tr><td width='35'>&nbsp;</td><td width='154' height='33' id='name_\"+obj.id+\"'>\"+name+\"</td><td width='222' id='email_\"+obj.id+\"'>\"+email+\"</td><td width='116'><select  id='\"+obj.id+\"' name = 'selectdMembers' onchange='changerole(\"+obj.id+\")'><option value='-1'>Unshare</option><option value ='2'>Participant</option><option value ='1'>Organizer</option></select></td></tr></table></li>\").appendTo('#shareloading');
							
							$('#email').val('');
							$('#name').val('');
							$('#mobile').val('');
							
						}else if(obj.tip == 'ok'){
							location.href = url;
						}else{
							document.getElementById('notice1').innerHTML = obj.tip;
							
						}
						$(\".showbox\").stop(true).animate({'margin-top':'250px','opacity':'0'},400);
					}",
				)
			);
?>
}

function changerole(v){
	$('#sharecontacts').show();
	$('#done').hide();
	
	var val = document.getElementById(v).value;
	document.getElementById('nshare_'+v).value = val;	
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

function submitSharedMembers(){
	var activity = $('#sharedActivityid').val();

	var o_share = document.getElementsByName('o_share');
	var n_share = document.getElementsByName('n_share');
	
	var ids = new Array();
	for(var i=0;i<o_share.length;i++){
		var memberid = o_share[i].id;
		ids.push(memberid.substr(7));
	}
	
	var members = new Array();
	var roles = new Array();
	
	var name = new Array();
	var email = new Array();
	
	if(ids){
		for(var j=0;j<ids.length;j++){
			var oshareid = o_share[j].id;
			var nshareid = n_share[j].id;
			
			if(oshareid.substr(7) == nshareid.substr(7) && o_share[j].value != n_share[j].value){
				members.push(nshareid.substr(7));
				roles.push(n_share[j].value);
				
				name.push($("#name_"+oshareid.substr(7)).html());
				email.push($("#email_"+oshareid.substr(7)).html());
			}
		}
	}
	

	var url = "<?php echo Yii::app()->homeUrl;?>";
	<?php
		echo CHtml::ajax(array(
			"url" => CController::createUrl("Service/AddSharedMembers"),
			"data" => "js:{activity : activity, members : members, roles : roles,name : name,email : email}",
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
				
				//alert(str);
				$('#sharecontacts').hide();
				$('#done').show();
			}",
		));
	?>
}

function viewActivity(i){
	$("#viewactivitypopup").jqmShow();
	
	d = new Date();
	var clientutc = d.getTimezoneOffset()/60;
	
	var url = "<?php echo Yii::app()->homeUrl;?>";
	<?php
		echo CHtml::ajax(array(
			"url" => CController::createUrl("Service/View"),
			"data" => "js:{activity : i}",
			"type"=>"POST",
			// 'beforeSend'=>"js:function(){
				// $(\".showbox\").stop(true).animate({'margin-top':'300px','opacity':'1'},200);
			// }",
			"success"=>"js:function(str){
				// $(\".showbox\").stop(true).animate({'margin-top':'250px','opacity':'0'},400);
				var data = eval('('+str+')');
				
				if(typeof(data.data) == 'undefined'){
					document.getElementById('viewname').value = data.name;
					document.getElementById('viewstart').value = data.start;
					document.getElementById('viewend').value = data.end;
					document.getElementById('viewdesp').value = data.desp;
					document.getElementById('viewrepeat').value = data.repeat;
					document.getElementById('viewalert').value = data.alert;
					document.getElementById('viewtimezone').value = data.timezone;					
				}else{
					// $(\"#viewactivitypopup\").jqmHide();
					location.href = url;
				}
				
			}",
		));
	?>
}

function editActivity(i){
	$("#editactivitypopup").jqmShow();
	document.getElementById("activityid").value = i;
	
	document.getElementById('tr1').style.display = "none";
	document.getElementById('tr2').style.display = "none";
	document.getElementById('tr3').style.display = "none";
	
	var url = "<?php echo Yii::app()->homeUrl;?>";
	
	<?php
		echo CHtml::ajax(array(
			"url" => CController::createUrl("Service/View"),
			"data" => "js:{activity : i}",
			"type"=>"POST",
			// 'beforeSend'=>"js:function(){
				// $(\".showbox\").stop(true).animate({'margin-top':'300px','opacity':'1'},200);
			// }",
			"success"=>"js:function(str){
				// $(\".showbox\").stop(true).animate({'margin-top':'250px','opacity':'0'},400);
				var data = eval('('+str+')');
				
				if(typeof(data.data) == 'undefined'){
					document.getElementById('editname').value = data.name;
					document.getElementById('editstart').value = data.start;
					document.getElementById('editend').value = data.end;
					document.getElementById('editdesp').value = data.desp;
					document.getElementById('editrepeat').value = data.repeat;
					document.getElementById('editalert').value = data.alert;
					document.getElementById('edittimezone').value = data.timezone;
				}else{
					location.href = url;
				}
				
			}",
		));
	?>
	
	
}

function saveActivity(){
	var id = document.getElementById("activityid").value;
	
	var activity = document.getElementById("editname").value;
	var starttime = document.getElementById('editstart').value;
	var endtime = document.getElementById('editend').value; 
	var desp = document.getElementById('editdesp').value; 
	var repeat = document.getElementById('editrepeat').value;
	var alerts = document.getElementById('editalert').value;
	

	if(activity == ''){
		document.getElementById('tr1').style.display = "";
		document.getElementById('tr2').style.display = "none";
		document.getElementById('tr3').style.display = "none";
		
		document.getElementById('error1').innerHTML = 'Name cannot be blank.';
		document.getElementById('editname').focus();
		return false;
	}else if(starttime == ''){
		document.getElementById('tr1').style.display = "none";
		document.getElementById('tr2').style.display = "";
		document.getElementById('tr3').style.display = "none";
	
		document.getElementById('error2').innerHTML = 'Start Time cannot be blank.';
		document.getElementById('editstart').focus();
		return false;
	}else if(endtime == ''){
		document.getElementById('tr1').style.display = "none";
		document.getElementById('tr2').style.display = "none";
		document.getElementById('tr3').style.display = "";
		
		document.getElementById('error3').innerHTML = 'End Time cannot be blank.';
		document.getElementById('editend').focus();
		return false;
	}
		
	if(starttime >= endtime){
		
		document.getElementById('tr1').style.display = "none";
		document.getElementById('tr2').style.display = "";
		document.getElementById('tr3').style.display = "none";
		
		document.getElementById('error2').innerHTML = 'Start should be smaller than End.';
		document.getElementById('editstart').focus();
		return false;
	}
	
	var url = "<?php echo Yii::app()->homeUrl;?>";
	<?php
		echo CHtml::ajax(array(
			"url" => CController::createUrl("Service/Update"),
			"data" => "js:{id : id, name : activity, start : starttime, end : endtime, desp : desp, repeat : repeat, alerts : alerts}",
			"type"=>"POST",
			'beforeSend'=>"js:function(){
				$(\".showbox\").stop(true).animate({'margin-top':'300px','opacity':'1'},200);
			}",
			"success"=>"js:function(str){
				$(\".showbox\").stop(true).animate({'margin-top':'250px','opacity':'0'},400);
				if(str == 'ok'){
					document.getElementById('activityname_'+id).innerHTML =  activity;
					$('#editactivitypopup').jqmHide();
				}else if(str == 'ajaxsessionout'){
					location.href = url;
				}				
			}",
		));
	?>
}
</script>
</html>
