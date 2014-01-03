<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>Create an Activity</title>
<link href="./css/cschedule.css" rel="stylesheet" type="text/css" />
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

  <table width="365" border="0" cellspacing="0" cellpadding="0">
	<tr>
      <td width="89" height="20"></td>
      <td colspan="2" align="center"><span class="wrong" id="error"></span> </td>
    </tr>
  
    <tr>
      <td width="89" height="48"><span class="fontsize1">Name</span><span><img src="./images/bg_100.png" /></span></td>
      <td colspan="2"><input id="activityname" type="text" class="cname"></td>
    </tr>
    <tr>
      <td height="48"><span class="fontsize1">Start</span><span><img src="./images/bg_100.png" /></span></td>
      <td colspan="2"><input type="text" id="starttime" onfocus="WdatePicker({dateFmt:'yyyy/MM/dd HH:mm',lang:'en'})" / class="cname3"></td>
    </tr>
    <tr>
      <td height="48"><span class="fontsize1">End</span><span><img src="./images/bg_100.png" /></span></td>
      <td colspan="2"><input type="text" id="endtime" onfocus="WdatePicker({dateFmt:'yyyy/MM/dd HH:mm',lang:'en'})" / class="cname3"></td>
    </tr>
	
	<tr>
      <td height="48"><span class="fontsize1">Timezone</span><span><img src="./images/bg_100.png" /></span></td>
      <td colspan="2"><select id="timezone"  class="cname4">
	  <?php
		$timezone_str = "<option value='none'>Please select a timezone</option>";
		foreach(getPartTimezones() as $timezonekey=>$timezonevals){
			$timezone_str .= "<option value='".$timezonekey."'>".$timezonevals."</option>";
		}
		echo $timezone_str;
	  ?>
	  </select></td>
    </tr>
	
    <tr>
      <td height="140"><span class="fontsize1">Descripion</span></td>
      <td colspan="2">
        <label>
          <textarea name="textarea" class="cname2" id="description"></textarea>
          </label>
          </td>
    </tr>
    <tr>
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
    </tr>
    <tr>
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
    </tr>
    <tr>
      <td height="48">&nbsp;</td>
      <td width="144"><form id="form3" name="form3" method="post">
        <label>
          <input type="button" onclick="checkValue()" class="cname5">
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
	function   formatDate(now){     
          var   year=now.getFullYear();     
          var   month=now.getMonth()+1;     
          var   date=now.getDate();     
          var   hour=now.getHours();     
          var   minute=now.getMinutes();         
          return   year+"-"+month+"-"+date+" "+hour+":"+minute;     
	}
	
	function checkValue(){
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
	}

	
	function cancel(){
		location.href = "<?php echo Yii::app()->createUrl('Service/admin');?>";
	}
</script>
</html>
