<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>Create a schedule</title>
<link href="./css/cschedule.css" rel="stylesheet" type="text/css" />
<script src="./datepicker/WdatePicker.js" type="text/javascript"></script>
<script src="./js/jquery.js" type="text/javascript"></script>

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
		<li><a href="<?php echo Yii::app()->createUrl('Member/Admin')?>">My contacts</a></li>
        <li><a href="<?php echo Yii::app()->createUrl('Schedule/Admin')?>"  class="Selected1">My schedules</a></li>
       
      </ul>
      <div class="clear"></div>
    </div>
  </div>
  <div class="title1">
    <div class="title1b">Create a schedule</div>
  </div>
</div>

<?php
$timezones_str = "";
if($services){
	foreach($services as $timezonevals){
		$timezones_str .= "<input type='hidden' id='timezone_".$timezonevals->serviceid."' value='".$timezonevals->utcoff."'>";
	}
}
echo $timezones_str;
?>

<!-- js loading start -->
<div id="AjaxLoading" class="showbox">
	<div class="loadingWord"><img src="./images/waiting.gif">Please Wait...</div>
</div>
<!-- js loading end-->


<div class="main10"> <div class="main10top"></div><div class="main10inter"><table width="670" border="0" cellpadding="0" cellspacing="0">

<tr>
    <td height="30" colspan="4" align="center" valign="middle"><span class="wrong" id='notice'></span></td>
    </tr>
	
	
  <tr>
    <td width="66" height="46" valign="middle"><span class="fontsize1">Activity</span><span><img src="./images/bg_100.png" /></span></td>
    <td width="10" valign="middle"></td>
    <td><select name="select" class="cname4" id="activity" onchange='selectActivity(this)'>
		<?php
			$activity_str = '';
			if($services){
				foreach($services as $activity_vals){
					$activity_str .= "<option value ='".$activity_vals->serviceid."'>".$activity_vals->servicename."</option>";
				}
			}
			echo $activity_str;
		  ?>
        </select></td>
    <td width="22">&nbsp;</td>
  </tr>
  <tr>
    <td height="46"><span class="fontsize1">Start</span><span><img src="./images/bg_100.png" /></span></td>
    <td>&nbsp;</td>
    <td><input type="text" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',lang:'en'})" class="cname3" id='starttime'></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="46"><span class="fontsize1">End</span><span><img src="./images/bg_100.png" /></span></td>
    <td>&nbsp;</td>
    <td><input type="text" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',lang:'en'})" class="cname3" id='endtime'></td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td height="48" width="99"><span class="fontsize1">Timezone</span><span><img src="./images/bg_100.png" /></span></td>
    <td>&nbsp;</td>
    <td><select id='timezone' class="cname4" disabled>
	<?php
		$timezone_str = "";
		
		foreach(getPartTimezones() as $timezonekey=>$timezonevals){
			if($services){
				if($timezonekey*3600 == $services[0]->utcoff){
					$timezone_str .= "<option value='".$timezonekey."' selected>".$timezonevals."</option>";
				}else $timezone_str .= "<option value='".$timezonekey."'>".$timezonevals."</option>";
			}
		}
		echo $timezone_str;
	  ?>
	  <!--<option value='-11'>(GMT-11:00) Samoa</option><option value='-10'>(GMT-10:00) Hawaii</option><option value='-9'>(GMT-09:00) Alaska</option><option value='-8' selected>(GMT-08:00) Tijuana</option><option value='-7'>(GMT-07:00) Mazatlan</option><option value='-6'>(GMT-06:00) Central Time (US &amp; Canada)</option><option value='-5'>(GMT-05:00) Lima</option><option value='-4.5'>(GMT-04:30) Caracas</option><option value='-4'>(GMT-04:00) Santiago</option><option value='-3.5'>(GMT-03:30) Newfoundland</option><option value='-3'>(GMT-03:00) Buenos Aires</option>-->
	  
	  </select></td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td height="143" valign="top"><span class="fontsize1">Descripion</span></td>
    <td>&nbsp;</td>
    <td><label>
          <textarea name="textarea" class="cname2" id="description"></textarea>
          </label></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
   <td valign="top"><span class="fontsize1">On Duty</span><span><img src="./images/bg_100.png" /></span></td>
    <td>&nbsp;</td>
    <td><div class="cschbg1"></div>
	<div class="cschbg2"><table width="563" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td width="8"></td>
    <td width="30"></td>
    <td width="525"><div class="cschb">
      <ul>
		<span  id="selectedcontacts"></span>
		<div class="clear"></div>	 
      </ul>
    </div></td>
  </tr>
</table>
</div>
<div class="cschbg3"></div>
<div class="schtable">
  <ul id = "sharememberlist">

<?php
	if($sharedList){
		$i = 1;
		$str = "";
		$count = count($sharedList);
		
		$lines = floor($count/3)+1;
		
		foreach($sharedList as $sharedList_key=>$sharedList_vals){
			if(($i-1)%3 == 0){
				$str .= "<li class='schbg1'><table width='546' border='0' cellspacing='0' cellpadding='0'><tr>";
			}
								
			$str .= "<td width='35' align='center'><input name='contact_check' type='checkbox' id='".$sharedList_key."_check' onclick='is_Checked(".$sharedList_key.")'></td><td width='147' height='35' id='".$sharedList_key."_name'>".$sharedList_vals."</td>";
			
			if($lines > 1){
				if(($i-1)/(3*($lines-1)) == 1 && $count == ($lines-1)*3+1){
					$str .= "<td width='147' height='35'></td><td width='147' height='35'></td>";
				}
			
				if(($i-2)/(3*($lines-1)) == 1 && $count == ($lines-1)*3+2){
					$str .= "<td width='35'></td><td width='147' height='35'></td>";
				}
					
			}else if($lines == 1){
				if($count == 1 && $i == $count){
					$str .= "<td width='147' height='35'></td><td width='147' height='35'></td>";
				}
			
				if($count == 2 && $i == $count){
					$str .= "<td width='35'></td><td width='147' height='35'></td>";
				}
			}
			
			if($i%3 == 0 || $i == $count){
					$str .= "</tr></table></li><li class='schbg2'></li>";
			}		
			$i++;
		}
		echo $str;
	}else{
		echo "<div class='schtable2'>No shared Contacts.</div>";
	}
?>
  </ul>
</div>	<div class="schbg3"></div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="35">&nbsp;</td>
    <td>&nbsp;</td>
    <td><span class="color1">If you want to share with another contact ,please go to my activity "share" option</span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="43%" align="right">
        <label>
          <input type="submit" name="Submit" value="" onclick='submitSchedule()' class="cname5">
          </label>
		</td>
        <td width="6%">&nbsp;</td>
        <td width="51%">
        <label>
          <input type="button" class="cname6" onclick="js:location.href='<?php echo Yii::app()->createUrl('Schedule/admin');?>'">
          </label>
        </td>
      </tr>
    </table></td>
    <td>&nbsp;</td>
  </tr>
</table>
</div>
  <div class="main10buttom"></div></div> 

<?php include_once(dirname(dirname(__FILE__)).'/footer.php');?>

</body>

<script language='javascript'>
	var homeUrl = "<?php echo Yii::app()->homeUrl;?>";

	function submitSchedule(){
		var activity = $('#activity').val();
		var start = $('#starttime').val();
		var end = $('#endtime').val();
		var desp = $('#description').val();	
		var timezone=$('#timezone').val();
				
		if(activity == '' || activity == null){
			document.getElementById('notice').innerHTML = '<font color=\'red\'>Activity can not be empty.</font>';
			document.getElementById('activity').focus();
			return;
		}
		if(start == '' || start == null){
			document.getElementById('notice').innerHTML = '<font color=\'red\'>Start Time can not be empty.</font>';
			document.getElementById('starttime').focus();
			return;
		}
		if(end == '' || end == null){
			document.getElementById('notice').innerHTML = '<font color=\'red\'>End Time can not be empty.</font>';
			document.getElementById('endtime').focus();
			return;
		}
		
		var checkbox = document.getElementsByName('contact_check');
		// var participants = new Array();
		var participants = "";
		
		for(var i=0;i<checkbox.length;i++){
			if(checkbox[i].checked){
				var par = checkbox[i].id;
				// participants[i] = par.substring(0,par.length-6);
				// participants.push(par.substring(0,par.length-6));	
				participants += ","+par.substring(0,par.length-6);
			}
		}
		if(participants.length == 0){
			alert("Please select at least one shared contact!");
			return;
		}
		var onduty = participants.substr(1);
		
		var url = "<?php echo Yii::app()->createUrl('Schedule/admin');?>";
		<?php
			echo CHtml::ajax(
				array(
					"url" => CController::createUrl("Schedule/createSchedule"),
					"data" => "js:{activity:activity,start:start,end:end,desp:desp,onduty:onduty,timezone:timezone}",
					"type"=>"POST",
					'beforeSend'=>"js:function(){	
						$(\".showbox\").stop(true).animate({'margin-top':'300px','opacity':'1'},200);	
					}",
					"success"=>"js:function(data){
					// console.info(data);
						if(data == 'ajaxsessionout'){
							location.href = homeUrl;
							return;
						}
						if(data=='ok'){
							location.href=url;
						}else{
							document.getElementById('notice').innerHTML = '<font color = \'red\'>'+data+'</font>'
						}
						$(\".showbox\").stop(true).animate({'margin-top':'250px','opacity':'0'},400);
					}",
				)
			);
		?>
	}
	
	function selectActivity(choose){
		//alert(choose.value);
		$('#sharememberlist').html("");
		$('#selectedcontacts').html("");
		var activity = choose.value;
		
		var timezone = document.getElementById('timezone_'+activity).value;
		
		document.getElementById("timezone").value = timezone/3600;
		
		var img = "<img src= '<?php echo Yii::app()->baseUrl.'/images/loading.gif';?>'>";
		<?php
			echo CHtml::ajax(
				array(
					"url" => CController::createUrl("Schedule/GetSharedMembers"),
					"data" => "js:{activity:activity}",
					"type"=>"POST",
					'beforeSend'=>"js:function(){	
						document.getElementById('sharememberlist').innerHTML = img;	
					}",
					"success"=>"js:function(data){
						// console.info(data);
						
						if(data == 'ajaxsessionout'){
							location.href = homeUrl;
							return;
						}
						
						document.getElementById('sharememberlist').innerHTML = data;
					}",
				)
			);
		?>
	}
	
	function is_Checked(i){
		var status = document.getElementById(i+'_check').checked;
		if(status){
			var name = document.getElementById(i+'_name').innerHTML;
			$("<li id='"+i+"_selected'><table width='117' border='0' cellspacing='0' cellpadding='0'><tr><td width='75' height='25'><span class='name'><a href='#'>"+name+"</a></span></td><td width='25'><span class='cha' onclick='deleteContact("+i+")' style='cursor:pointer;'></span></td></tr></table></li>").appendTo('#selectedcontacts');
		}else{
			$('#'+i+'_selected').remove();
		}
	}
	
	function deleteContact(j){
		$('#'+j+'_selected').remove();
		document.getElementById(j+'_check').checked = false;
	}

</script>
</html>
