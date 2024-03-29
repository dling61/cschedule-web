<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>My schedules</title>
    <link href="./css/cschedule.css" rel="stylesheet" type="text/css"/>

    <link rel="stylesheet" type="text/css" href="./css/jquery.datetimepicker.css"/>
    <script type="text/javascript" src="./js/jquery1.10.js"></script>
    <script type="text/javascript" src="./js/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="./js/jquery.datetimepicker.js"></script>
    <script type="text/javascript" src="./js/moment.js"></script>

    <link href="<?php echo Yii::app()->baseUrl . '/css/jqModal.css'; ?>" rel="stylesheet" type="text/css"/>
    <script src="<?php echo Yii::app()->baseUrl . '/js/jqModal.js'; ?>" type="text/javascript"></script>


    <style type="text/css">
        .showbox {
            position: fixed;
            top: 0;
            left: 50%;
            z-index: 9999;
            opacity: 0;
            filter: alpha(opacity=0);
            margin-left: -80px;
        }

        * html, * html body {
            background-image: url(about:blank);
            background-attachment: fixed;
        }

        * html .showbox, * html .overlay {
            position: absolute;
            top: expression(eval(document.documentElement.scrollTop));
        }

        #AjaxLoading {
            border: 1px solid #908964;
            color: #37a;
            font-size: 12px;
            font-weight: bold;
            text-align: left;
        }

        #AjaxLoading div.loadingWord {
            width: 180px;
            height: 50px;
            line-height: 50px;
            border: 2px solid #D6E7F2;
            background: #fff;
        }

        #AjaxLoading img {
            margin: 10px 15px;
            float: left;
            display: inline;
        }

        #weekdays label{
            margin-right: 20px;
        }

        .divRepeats{
            width:680px;
        }

        #btnSubmitSchedule{
            display: none;;
        }
        #repeatSchedules{
            width:660px;
            border-right: 1px solid #d6dfe5;
            border-top: 1px solid #d6dfe5;
        }

        #repeatSchedules th, #repeatSchedules td{
            border-left: 1px solid #d6dfe5;
            border-bottom: 1px solid #d6dfe5;
        }
        #repeatSchedules th{
            background-color: #EFEFEF;
            padding: 10px;
        }

        #repeatSchedules tr:hover, #repeatSchedules tr:active, #repeatSchedules tr.active{
            background-color: #d3d3d3;
        }
        #repeatSchedules td{
            padding: 5px;
        }
        #repeatSchedules tr td:last-child{
            text-align: center;
        }
        .repeatParicipantChange{
            float: right;
        }
        .tableRepeatSchedules{
            max-height: 400px;
            overflow-y: scroll;
        }

        #divRepeatParticipants{
            margin-bottom: 20px;
        }
    </style>

</head>
<body>
<div class="top2">
    <div class="logo2">
        <div class="xinxi"><span><?php echo $_SESSION['username']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<a
                    href="<?php echo Yii::app()->createUrl('User/Logout') ?>">Logout</a></span></div>
        <div class="nav1">
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('Service/Create') ?>">Create an activity</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('Service/Admin') ?>">My activities</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('Member/Admin') ?>">My contacts</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('Schedule/Admin') ?>" class="Selected1">My schedules</a>
                </li>

            </ul>
            <div class="clear"></div>
        </div>
    </div>
    <div class="title1">
        <div class="title1b">My schedules</div>
    </div>
</div>

<!-- view schedule popup start-->
<div class="jqmWindowViewSchedule" id="viewschedulepopup">
    <div class="main10top"></div>
    <div class="main10inter">
        <table width="670" border="0" cellpadding="0" cellspacing="0">

            <tr>
                <td width="66" height="46" valign="middle"><span class="fontsize1">Activity</span></td>
                <td width="10" valign="middle"></td>
                <td><input type="text" id='viewname' class="cname" disabled></td>
                <td width="22">&nbsp;</td>
            </tr>

            <tr>
                <td height="46"><span class="fontsize1">Start</span></td>
                <td>&nbsp;</td>
                <td><input type="text" id='viewstart' class="cname3" disabled></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td height="46"><span class="fontsize1">End</span></td>
                <td>&nbsp;</td>
                <td><input type="text" id='viewend' class="cname3" disabled></td>
                <td>&nbsp;</td>
            </tr>

            <tr>
                <td height="46"><span class="fontsize1">TimeZone</span></td>
                <td>&nbsp;</td>
                <td><select id='viewtimezone' class="cname4" disabled>
                        <?php
                        $timezone_str = "";

                        foreach (getPartTimezones() as $timezonekey => $timezonevals) {
                            $timezone_str .= "<option value='" . $timezonekey . "'>" . $timezonevals . "</option>";
                        }
                        echo $timezone_str;
                        ?>

                    </select></td>
                <td>&nbsp;</td>
            </tr>

            <tr>
                <td height="46"><span class="fontsize1">Alert</span></td>
                <td>&nbsp;</td>
                <td><select id='viewalert' class="cname4" disabled>
                        <?php
                        $alert_str = "";

                        foreach (getAlerts() as $alertid => $alerttext) {
                            $alert_str .= "<option value='" . $alertid . "'>" . $alerttext . "</option>";
                        }
                        echo $alert_str;
                        ?>
                    </select></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td height="143" valign="top"><span class="fontsize1">Descripion</span></td>
                <td>&nbsp;</td>
                <td><label>
                        <textarea name="textarea" class="cname2" id="viewdesp" disabled></textarea>
                    </label></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td valign="top"><span class="fontsize1">On Duty</span></td>
                <td>&nbsp;</td>
                <td>
                    <table width="563" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                <div class="cschb2">
                                    <ul>
                                        <span id="viewonduty"></span>

                                        <div class="clear"></div>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </table>

                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td height="20">&nbsp;</td>
                <td>&nbsp;</td>
                <td></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="center"><span class="jqmClose"><input type="button" class="main13bu3"></span></td>
                <td>&nbsp;</td>
            </tr>
        </table>
    </div>
    <div class="main10buttom"></div>
</div>
<!-- view schedule popup end-->



<?php
$filter_ac_str = "<option name='activityfilter' value='all'>All</option>";
$filter_participant_str = "<option name='participantfilter' value='all'>All</option>";
if ($service) {
    if (isset($_GET['activity'])) {
        $activityid = $_GET['activity'];
    } else if (isset($activityselected)) {
        $activityid = $activityselected;
    } else {
        $activityid = "all";
    }

    foreach ($service as $servicekey => $servicevals) {
        $filter_ac_str .= "<option name='activityfilter' value='" . $servicekey . "' " . (($activityid == $servicekey) ? "selected" : "") . ">" . $servicevals . "</option>";
    }
}

if ($members) {
    foreach ($members as $mem) {
        $filter_participant_str .= "<option name='participantfilter' value='" . $mem->memberid . "' " . (isset($participantselected) ? (($participantselected == $mem->memberid) ? 'selected' : '') : '') . ">" . $mem->membername . "</option>";
    }
}
?>



<div class="jqmWindowConfirm" id="confirmChangeStatusPopup">
    <div id="confirm-general" class="confirm-status">
        <div>
            Do you want to accept or deny this schedule?
        </div>
        <div class="buttons">
            <button type="button" onclick="confirmAccept()" class="button">Accept</button>
            &nbsp;
            <button type="button" onclick="confirmDeny()" class="button">Deny</button>
            &nbsp;
            <button type="button" onclick="closeConfirmPopup()" class="button">Cancel</button>

        </div>
    </div>
    <div id="confirm-accept" class="confirm-status">
        <div>
            Do you want to accept this schedule?
        </div>
        <div class="buttons">
            <button type="button" onclick="confirmAccept()" class="button">Accept</button>
            &nbsp;
            <button type="button" onclick="closeConfirmPopup()" class="button">Cancel</button>

        </div>
    </div>
    <div id="confirm-deny" class="confirm-status">
        <div>
            Do you want to deny this schedule?
        </div>
        <div class="buttons">
            <button type="button" onclick="confirmDeny()" class="button">Deny</button>
            &nbsp;
            <button type="button" onclick="closeConfirmPopup()" class="button">Cancel</button>
        </div>
    </div>
</div>


<!-- edit schedule popup start -->
<div class="jqmWindowEditSchedule" id="editschedulepopup">
    <div class="main10top"></div>
    <div class="main10inter">
        <div>
            <h2 id="schedulePopupTitle" ></h2>
        </div>
        <table width="670" border="0" cellpadding="0" cellspacing="0">
            <input type="hidden" id='hidschedule'>
            <input type="hidden" id='hidactivity'>
            <tr>
                <td width="66" height="46" valign="middle"><span class="fontsize1">Activity</span><span><img
                            src="./images/bg_100.png"/></span></td>
                <td width="10" valign="middle"></td>
                <td><input type="text" id="editactivity" class="cname" disabled></td>
                <td width="22">&nbsp;</td>
            </tr>

            <tr id="editerror1" style="display:none">
                <td height="30" colspan="4" align="center" valign="middle"><span class="wrong" id="error1"></span></td>
            </tr>

            <tr>
                <td height="46"><span class="fontsize1">Start</span><span><img src="./images/bg_100.png"/></span></td>
                <td>&nbsp;</td>
                <td><input type="text" class="cname3" id="editstart"></td>
                <td>&nbsp;</td>
            </tr>

            <tr id="editerror2" style="display:none">
                <td height="30" colspan="4" align="center" valign="middle"><span class="wrong" id="error2"></span></td>
            </tr>

            <tr>
                <td height="46"><span class="fontsize1">End</span><span><img src="./images/bg_100.png"/></span></td>
                <td>&nbsp;</td>
                <td><input type="text" class="cname3" id="editend"></td>
                <td>&nbsp;</td>
            </tr>

            <tr>
                <td height="46" width="89"><span class="fontsize1">Timezone</span><span><img src="./images/bg_100.png"/></span>
                </td>
                <td>&nbsp;</td>
                <td><select id='edittimezone' class="cname4">
                        <?php
                        $timezone_str = "";

                        foreach (getPartTimezones() as $timezonekey => $timezonevals) {
                            $timezone_str .= "<option value='" . $timezonekey . "'>" . $timezonevals . "</option>";
                        }
                        echo $timezone_str;
                        ?>

                    </select></td>
                <td>&nbsp;</td>
            </tr>

            <tr>
                <td height="46" width="89"><span class="fontsize1">Alert</span><span><img src="./images/bg_100.png"/></span>
                </td>
                <td>&nbsp;</td>
                <td><select id='editalert' class="cname4">
                        <?php
                        $alert_str = "";

                        foreach (getAlerts() as $alertid => $alerttext) {
                            $alert_str .= "<option value='" . $alertid . "'>" . $alerttext . "</option>";
                        }
                        echo $alert_str;
                        ?>
                    </select></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td height="143" valign="top"><span class="fontsize1">Descripion</span></td>
                <td>&nbsp;</td>
                <td><label>
                        <textarea name="textarea" class="cname2" id="editdesp"></textarea>
                    </label></td>
                <td>&nbsp;</td>
            </tr>

            <tr id="editerror3" style="display:none">
                <td height="30" colspan="4" align="center" valign="middle"><span class="wrong" id="error3"></span></td>
            </tr>

            <tr>
                <td valign="top"><span class="fontsize1">On Duty</span><span><img src="./images/bg_100.png"/></span>
                </td>
                <td>&nbsp;</td>
                <td>
                    <div class="cschbg1"></div>
                    <div class="cschbg2">
                        <table width="563" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <div class="cschb">
                                        <ul>
                                            <span id="editonduty"></span>

                                            <div class="clear"></div>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="cschbg3"></div>
                    <div class="schtable">
                        <ul id="sharememberlist">

                        </ul>
                    </div>


                    <div class="schbg3"></div>
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td height="35">&nbsp;</td>
                <td>&nbsp;</td>
                <td width="575"><span class="color1">If you can't find a participant here, please go to the activity's "Participants" option to add it.</span>
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="43%" align="right">
                                <label>
                                    <input type="button" class="cname5" onclick="submitSchedule()">
                                </label>
                            </td>
                            <td width="6%">&nbsp;</td>
                            <td width="51%">
                                <label>
                                    <span class="jqmClose"><input type="button" class="cname6"></span>
                                </label>
                            </td>
                        </tr>
                    </table>
                </td>
                <td>&nbsp;</td>
            </tr>
        </table>
    </div>
    <div class="main10buttom"></div>
</div>
<!-- edit schedule popup end -->



<!-- repeat schedule popup start -->
<div class="jqmWindowEditSchedule" id="repeatschedulepopup">
    <div class="main10top"></div>
    <div class="main10inter">
        <form id='repeatForm' action="<?php echo Yii::app()->createUrl('Schedule/repeatSelected'); ?>" method='post'>
        <div class="popupClose">
            <span class="jqmClose">X</span>
        </div>
        <div>
            <h2 id="repeatSchedulePopupTitle" >Repeat Schedules</h2>

        </div>
        <input type="hidden" id='repeatActivityId' name="activity">
        <input type="hidden" id='repeatScheduleId'>
        <input type="hidden" id='repeatScheduleStart'>
        <input type="hidden" id='repeatScheduleEnd'>
        <input type="hidden" id='repeatScheduleDesc' name='desc'>
        <input type="hidden" id='repeatScheduleTimezone' name='tzid'>
        <input type="hidden" id='repeatScheduleAlert' name='alert'>
        <table width="700" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td width="66" height="46" valign="middle">
                    <span class="fontsize1">Repeat every</span><span><img
                            src="./images/bg_100.png"/></span></td>
                <td width="10" valign="middle"></td>
                <td>
                   <select id="repeatNumber" name="repeatNumber" class="cname4-mini" >
                        <?php for($i=0;$i<30;$i++){ ?>
                            <option value="<?php echo $i+1; ?>"><?php echo $i+1; ?></option>
                        <?php }?>
                   </select>
                    &nbsp;
                    <select id="repeatPeriod" name="repeatPeriod" class="cname4-mini" >
                        <option value="daily">day(s)</option>
                        <option value="weekly">week(s)</option>
                        <option value="monthly">month(s)</option>
                        <option value="yearly">year(s)</option>
                    </select>
                </td>
                <td width="22">&nbsp;</td>
            </tr>

            <tr style="display: none" id="weekdays">
                <td height="46"></td>
                <td>&nbsp;</td>
                <td>
                    <input type="checkbox" id="sun" value="0" class="weekdays"/><label for="sun">S</label>
                    <input type="checkbox" id="mon" value="1" class="weekdays"/><label for="mon">M</label>
                    <input type="checkbox" id="tue" value="2" class="weekdays"/><label for="tue">T</label>
                    <input type="checkbox" id="wed" value="3" class="weekdays"/><label for="wed">W</label>
                    <input type="checkbox" id="thu" value="4" class="weekdays"/><label for="thu">T</label>
                    <input type="checkbox" id="fri" value="5" class="weekdays"/><label for="fri">F</label>
                    <input type="checkbox" id="sat" value="6" class="weekdays"/><label for="sat">S</label>
                </td>
                <td>&nbsp;</td>
            </tr>

            <tr>
                <td height="46"><span class="fontsize1">From</span><span><img src="./images/bg_100.png"/></span></td>
                <td>&nbsp;</td>
                <td>
                    <input type="text" class="cname3" id="repeatStartAt"/>
                    &nbsp;&nbsp;&nbsp;
                    <span class="fontsize1">To</span>&nbsp;
                    <input type="text" class="cname3" id="repeatEndAt">
                </td>
                <td>&nbsp;</td>
            </tr>

            <tr id="repeatTimeErrorTr" style="display:none">
                <td height="30" colspan="4" align="center" valign="middle"><span class="wrong" id="repeatTimeError"></span></td>
            </tr>

            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>
                    <div style="text-align: center; margin: 15px auto;">
                        <input type="button" class="conbu4" id="addRepeatScheduleButton" onclick="addRepeatSchedule();"/>
                    </div>
                </td>
                <td>&nbsp;</td>
            </tr>
        </table>



        <div class="divRepeats">
            <hr/>
            <div class="tableRepeatSchedules">
                <table id="repeatSchedules" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr>
                        <th>
                            Start
                        </th>
                        <th>
                            End
                        </th>
                        <th style="width: 260px;">
                            On Duty
                        </th>
                        <th style="width: 50px;">
                            Delete
                        </th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <br/>
            <div style="text-align: center;">
                    <label>
                        <input id="btnSubmitSchedule" type="button" class="cname5" value="" onclick="validateSubmitRepeat(this);">
                    </label>
                    &nbsp;&nbsp;
                    <label>
                        <span class="jqmClose"><input type="button" class="cname6"></span>
                    </label>
            </div>
        </div>
        </form>
    </div>
    <div class="main10buttom"></div>
</div>
<!-- repeat schedule popup end -->


<!-- repeat schedule popup start -->
<div class="jqmWindowSmall modelGray" id="repeatUserSelect">
    <div>
        <div>
            <h2>Participants</h2>
        </div>

        <div id="divRepeatParticipants">


        </div>
        <div style="text-align: center;">
            <label>
                <input type="button" class="cname5" onclick="changeRepeatParticipants()">
            </label>
            &nbsp;&nbsp;
            <label>
                <span class="jqmClose"><input type="button" class="cname6"></span>
            </label>
        </div>
    </div>
</div>
<!-- repeat schedule popup end -->


<div class="main6">
    <!-- js loading start -->
    <div id="AjaxLoading" class="showbox">
        <div class="loadingWord"><img src="./images/waiting.gif">Please Wait...</div>
    </div>
    <!-- js loading end-->

    <?php
    // if(in_array(0,$servicerole) || in_array(1,$servicerole)){
    if (in_array(0, $servicerole)) {
        echo "<a href='" . Yii::app()->createUrl('Schedule/Create') . "'><input type='button' class='mname4'></a>";
    }
    ?>
</div>


<div class="filter">
    <form name='filterform' action="<?php echo Yii::app()->createUrl('Schedule/admin'); ?>" method='post'>
        <table width="934" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="48"><span class="fontweight">Activity&nbsp;&nbsp;&nbsp;</span></td>
                <td width="231">
                    <select class="mname" id="activityfilter" name="activityfilter">
                        <?php echo $filter_ac_str; ?>
                    </select></td>
                <td width="48"><span class="fontweight">OnDuty&nbsp;&nbsp;&nbsp;</span></td>
                <td width="231">
                    <select name="participantfilter" class="mname" id="participantfilter">
                        <?php echo $filter_participant_str; ?>
                    </select></td>
                <td width="48"><span class="fontweight">Period&nbsp;&nbsp;&nbsp;</span></td>
                <td width="231"><select class="mname" id="timeselectfilter" name="timeselectfilter">
                        <option name='timeselectfilter'
                                value='0' <?php echo(isset($timeselected) ? (($timeselected == 0) ? 'selected' : '') : ''); ?>>
                            All
                        </option>
                        <option name='timeselectfilter'
                                value='7' <?php echo(isset($timeselected) ? (($timeselected == 7) ? 'selected' : '') : ''); ?>>
                            Past one week
                        </option>
                        <option name='timeselectfilter'
                                value='14' <?php echo(isset($timeselected) ? (($timeselected == 14) ? 'selected' : '') : ''); ?>>
                            Past two weeks
                        </option>
                        <option name='timeselectfilter'
                                value='30' <?php echo(isset($timeselected) ? (($timeselected == 30) ? 'selected' : '') : ''); ?>>
                            Past one month
                        </option>
                        <option name='timeselectfilter'
                                value='90' <?php echo(isset($timeselected) ? (($timeselected == 90) ? 'selected' : '') : ''); ?>>
                            Past three months
                        </option>
                        &nbsp;
                    </select></td>
                <td width="93"><input type='submit' name='submit' class="mname2" value=""></td>
            </tr>
        </table>
    </form>
</div>


<div class="main4">
<ul>
<li class="tabletitle2"></li>

<?php
$schedule_str = "";


if (isset($_GET['activity'])) {
    if ($schedules && $service && $member) {
        $m = 1;
        $count = count($schedules);


        $activityname = $_GET['activity'];

        foreach ($schedules as $schedules_vals) {
            $real_start = date("m/d/Y h:i A", (strtotime($schedules_vals->startdatetime)));
            $real_end = date("m/d/Y h:i A", (strtotime($schedules_vals->enddatetime)));

            $startdate = explode(" ", $real_start);
            $enddate = explode(" ", $real_end);

            //时区的简写
            $tz = "<br>（" . getTimezoneAbbr($schedules_vals->tzid) . "）";

            // $startdate = explode(" ",$schedules_vals->startdatetime);
            // $enddate = explode(" ",$schedules_vals->enddatetime);
            $dateadded = explode(" ", $schedules_vals->createdtime);

            $member_str = "";
            $mem = ''; //checkbox的memberid集合

            if (is_array($schedules_vals->members)) {
                foreach ($schedules_vals->members as $members_vals) {
                    $members_vals = ((array)$members_vals);
                    $mem_id = $members_vals['memberid'];
                    if ($_SESSION['ownerid'].'0000'==$mem_id){
                        $member_str = "<a "
                            . "' id='" . $schedules_vals->scheduleid . '_' . $mem_id
                            . "' title='Name: " . $sharedList[$mem_id] . "\nEmail: " . $emails[$mem_id] . "\nPhone: " . $phones[$mem_id]
                            . "' class='confirm-" . $members_vals['confirm']
                            . "' href='javascript:void(0);'"
                            . "' onclick='showConfirm(this);'"
                            . "'>"
                            . $sharedList[$mem_id] . "</a>"
                            .$member_str;
                    }
                    else{
                        $member_str .= "<span name='membersid_" . $schedules_vals->scheduleid
                            . "' id='" . $schedules_vals->scheduleid . '_' . $mem_id
                            . "' title='Name: " . $sharedList[$mem_id] . "\nEmail: " . $emails[$mem_id] . "\nPhone: " . $phones[$mem_id]
                            . "' class='confirm-" . $members_vals['confirm']
                            . "'>"
                            . $sharedList[$mem_id] . "</span>";
                    }
                    $mem .= "_" . $mem_id;
                }
            }

            if ($schedules_vals->serviceid == $activityname) {
                $schedule_str .= "<li class='tablebg3' id='" . $schedules_vals->scheduleid . "'><table width='951' border='0' cellspacing='0' cellpadding='0'>
  <tr> <td width='53' align='center'>";
                if (in_array($servicerole[$schedules_vals->serviceid], array(0))) {
                    $schedule_str .= "<input name='ischeck' type='checkbox' id='" . $schedules_vals->scheduleid . "_" . $schedules_vals->serviceid . $mem . "_check' />";
                    // }else if($servicerole[$schedules_vals->serviceid] == 2){
                }
                else if (in_array($servicerole[$schedules_vals->serviceid], array(1, 2))) {
                    $schedule_str .= "<input name='ischeck' type='checkbox' disabled='disabled' id='" . $schedules_vals->scheduleid . "_" . $schedules_vals->serviceid . $mem . "_check' />";
                }
                $schedule_str .= "
   </td>
    <td width='176' align='center'><span class='fontweight' id='" . $schedules_vals->scheduleid . "_se'>" . $service[$schedules_vals->serviceid] . "</span></td>
    <td width='108' align='center' id='" . $schedules_vals->scheduleid . "_st'>" . $startdate[0] . "<br/>
" . $startdate[1] . " " . $startdate[2] . $tz . "</td>
    <td width='110' align='center' id='" . $schedules_vals->scheduleid . "_en'>" . $enddate[0] . "<br/>
" . $enddate[1] . " " . $enddate[2] . $tz . "</td>
    <td align='center' id='" . $schedules_vals->scheduleid . "_me'>" . $member_str . "</td>
    <td width='58'><span class='table4'><a cursor:pointer; title='View'
onclick=\"viewSchedule('" . $schedules_vals->scheduleid . "_" . $schedules_vals->serviceid . "')\"> </a></span></td>";



                    // if(in_array($servicerole[$schedules_vals->serviceid],array(0,1))){
                    if (in_array($servicerole[$schedules_vals->serviceid], array(0))) {
                        $schedule_str .= "
    <td width='58'><span class='table5'><a  cursor:pointer; title='Edit' onclick=\"editSchedule('" . $schedules_vals->scheduleid . "_" . $schedules_vals->serviceid . "')\"></a></span></td>
    <td width='59'><span class='table6'><a href='#' onclick='deleteSchedule(" . $schedules_vals->scheduleid . ")' cursor:pointer; title='Delete'></a></span></td>";
                        // }else if($servicerole[$schedules_vals->serviceid] == 2){
                    }
                    else if (in_array($servicerole[$schedules_vals->serviceid], array(1, 2))) {
                        $schedule_str .= " <td width='58'><span class='table5img'></span></td>  <td width='59'><span class='table6img'></span></td>";
                    }

                $schedule_str .= "</tr></table></li>";

                if ($m==$count){
                    $schedule_str .= "<li class='cutoff3'> </li>";
                }
                else{
                    $schedule_str .= "<li class='cutoff2'> </li>";
                }
                }
                $m++;
            }


        if ($m == 1) {
            $schedule_str .= "<li class='tablebg4'><font size='3'>When you have already created a schedule or you are participating in an activity created by someone else, you’ll see it here.</font></li>
<li class='cutoff3'></li>";
        }
    }
}

else if (isset($_POST['submit'])) {

    $i = 1;
    if ($schedules && $service && $member) {
        // $i = 1;
        $k = 0; //计算查询为空的次数
        $count = count($schedules);

        $activityname = $_POST['activityfilter'];
        $participantname = $_POST['participantfilter'];
        $timename = $_POST['timeselectfilter'];

        foreach ($schedules as $schedules_vals) {
            $real_start = date("m/d/Y h:i A", (strtotime($schedules_vals->startdatetime)));
            $real_end = date("m/d/Y h:i A", (strtotime($schedules_vals->enddatetime)));

            $startdate = explode(" ", $real_start);
            $enddate = explode(" ", $real_end);


            //时区的简写
            $tz = "<br>（" . getTimezoneAbbr($schedules_vals->tzid) . "）";

            // $startdate = explode(" ",$schedules_vals->startdatetime);
            // $enddate = explode(" ",$schedules_vals->enddatetime);
            $dateadded = explode(" ", $schedules_vals->createdtime);

            $member_str = "";
            $mem = ''; //checkbox的memberid集合

            if (is_array($schedules_vals->members)) {
                foreach ($schedules_vals->members as $members_vals) {
                    $members_vals = ((array)$members_vals);
                    $mem_id = $members_vals['memberid'];
                    if ($_SESSION['ownerid'].'0000'==$mem_id){
                        $member_str = "<a "
                            . "' id='" . $schedules_vals->scheduleid . '_' . $mem_id
                            . "' title='Name: " . $sharedList[$mem_id] . "\nEmail: " . $emails[$mem_id] . "\nPhone: " . $phones[$mem_id]
                            . "' class='confirm-" . $members_vals['confirm']
                            . "' href='javascript:void(0);'"
                            . "' onclick='showConfirm(this);'"
                            . "'>"
                            . $sharedList[$mem_id] . "</a>"
                            .$member_str;
                    }
                    else{
                        $member_str .= "<span name='membersid_" . $schedules_vals->scheduleid
                            . "' id='" . $schedules_vals->scheduleid . '_' . $mem_id
                            . "' title='Name: " . $sharedList[$mem_id] . "\nEmail: " . $emails[$mem_id] . "\nPhone: " . $phones[$mem_id]
                            . "' class='confirm-" . $members_vals['confirm']
                            . "'>"
                            . $sharedList[$mem_id] . "</span>";
                    }
                    $mem .= "_" . $mem_id;
                }
            }

            if ($timename == 0) {
                $time_condition = 1;
            } else {
                $time_condition = (int)(time($schedules_vals->createdtime) >= time() - $timename * 81400);
            }

            if ($activityname == 'all') {
                $activity_condition = 1;
            } else {
                $activity_condition = (int)($activityname == $schedules_vals->serviceid);
            }

            if ($participantname == 'all') {
                $participant_condition = 1;
            } else {
                $participant_condition = (int)(in_array($participantname, explode("_", $mem)));
            }

            if ($time_condition && $activity_condition && $participant_condition) {
                $schedule_str .= "<li class='tablebg3' id='" . $schedules_vals->scheduleid . "'><table width='951' border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td width='53' align='center'>";
                if (in_array($servicerole[$schedules_vals->serviceid], array(0))) {
                    $schedule_str .= "<input name='ischeck' type='checkbox' id='" . $schedules_vals->scheduleid . "_" . $schedules_vals->serviceid . $mem . "_check' />";
                    // }else if($servicerole[$schedules_vals->serviceid] == 2){
                }
                else if (in_array($servicerole[$schedules_vals->serviceid], array(1, 2))) {
                    $schedule_str .= "<input name='ischeck' type='checkbox' disabled='disabled' id='" . $schedules_vals->scheduleid . "_" . $schedules_vals->serviceid . $mem . "_check' />";
                }
                $schedule_str .= "
                </td>
    <td width='176' align='center'><span class='fontweight' id='" . $schedules_vals->scheduleid . "_se'>" . $service[$schedules_vals->serviceid] . "</span></td>
    <td width='108' align='center' id='" . $schedules_vals->scheduleid . "_st'>" . $startdate[0] . "<br/>
" . $startdate[1] . " " . $startdate[2] . $tz . "</td>
    <td width='110' align='center' id='" . $schedules_vals->scheduleid . "_en'>" . $enddate[0] . "<br/>
" . $enddate[1] . " " . $enddate[2] . $tz . "</td>
    <td align='center' id='" . $schedules_vals->scheduleid . "_me'>" . $member_str . "</td>
    <td width='58'><span class='table4'><a cursor:pointer; title='View'
onclick=\"viewSchedule('" . $schedules_vals->scheduleid . "_" . $schedules_vals->serviceid . "')\"> </a></span></td>";



                // if(in_array($servicerole[$schedules_vals->serviceid],array(0,1))){
                if (in_array($servicerole[$schedules_vals->serviceid], array(0))) {
                    $schedule_str .= "
    <td width='58'><span class='table5'><a  cursor:pointer; title='Edit' onclick=\"editSchedule('" . $schedules_vals->scheduleid . "_" . $schedules_vals->serviceid . "')\"></a></span></td>
    <td width='59'><span class='table6'><a href='#' onclick='deleteSchedule(" . $schedules_vals->scheduleid . ")' cursor:pointer; title='Delete'></a></span></td>";
                    // }else if($servicerole[$schedules_vals->serviceid] == 2){
                }
                else if (in_array($servicerole[$schedules_vals->serviceid], array(1, 2))) {
                    $schedule_str .= " <td width='58'><span class='table5img'></span></td>  <td width='59'><span class='table6img'></span></td>";
                }

                $schedule_str .= "</tr></table></li>";

                if ($i==$count){
                    $schedule_str .= "<li class='cutoff3'> </li>";
                }
                else{
                    $schedule_str .= "<li class='cutoff2'> </li>";
                }

                $i++;
            }

            // else{
            // if($k == 0){
            // $schedule_str .= "<li class='tablebg4'>No results found.</li>
// <li class='cutoff3'></li>";
            // }
            // $k++;
            // }
        }
    }

    // else{
    // $schedule_str .= "<li class='tablebg4'>No results found.</li>
// <li class='cutoff3'></li>";
    // }

    if ($i == 1) {
        $schedule_str .= "<li class='tablebg4'><font size='3'>When you have already created a schedule or you are participating in an activity created by someone else, you’ll see it here.</font></li><li class='cutoff3'></li>";
    }

}

else {
    if ($schedules && $service && $member) {
        $j = 1;
        $count = count($schedules);

        // dump($schedules);exit;
        foreach ($schedules as $schedules_vals) {
            //$real_start = date("m/d/Y h:i A", (strtotime($schedules_vals->startdatetime)));
            //$real_end = date("m/d/Y h:i A", (strtotime($schedules_vals->)));
            $real_start = (new DateTime($schedules_vals->startdatetime, new DateTimeZone('UTC')))
                ->setTimezone(new DateTimeZone(getTimezoneAbbr($schedules_vals->tzid)))->format('m/d/Y h:i A');
            $real_end = (new DateTime($schedules_vals->enddatetime, new DateTimeZone('UTC')))
                ->setTimezone(new DateTimeZone(getTimezoneAbbr($schedules_vals->tzid)))->format('m/d/Y h:i A');

            $startdate = explode(" ", $real_start);
            $enddate = explode(" ", $real_end);
            $dateadded = explode(" ", $schedules_vals->createdtime);


            //时区的简写
            $tz = "<br>（" . getTimezoneAbbr($schedules_vals->tzid) . "）";

            $member_str = "";
            //$member_stris_array($schedules_vals->members) = "";
            $mem = '';
            if (is_array($schedules_vals->members)) {
                foreach ($schedules_vals->members as $members_vals) {
                    $members_vals = ((array)$members_vals);
                    $mem_id = $members_vals['memberid'];
                    if ($_SESSION['useremail'] == $emails[$mem_id]){
                        $member_str = "<a "
                            . "' id='" . $schedules_vals->scheduleid . '_' . $mem_id
                            . "' title='Name: " . $sharedList[$mem_id] . "\nEmail: " . $emails[$mem_id] . "\nPhone: " . $phones[$mem_id]
                            . "' class='confirm-" . $members_vals['confirm']
                            . "' href='javascript:void(0);'"
                            . "' onclick='showConfirm(this);'"
                            . "'>"
                            . $sharedList[$mem_id] . "</a>"
                            .$member_str;
                    }
                    else{
                    $member_str .= "<span name='membersid_" . $schedules_vals->scheduleid
                        . "' id='" . $schedules_vals->scheduleid . '_' . $mem_id
                        . "' title='Name: " . $sharedList[$mem_id] . "\nEmail: " . $emails[$mem_id] . "\nPhone: " . $phones[$mem_id]
                        . "' class='confirm-" . $members_vals['confirm']
                        . "'>"
                        . $sharedList[$mem_id] . "</span>";
                    }
                    $mem .= "_" . $mem_id;
                }
            }



            $schedule_str .= "<li class='tablebg3' id='" . $schedules_vals->scheduleid . "'><table width='951' border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td width='53' align='center'>";
            if (in_array($servicerole[$schedules_vals->serviceid], array(0))) {
                $schedule_str .= "<input name='ischeck' type='checkbox' id='" . $schedules_vals->scheduleid . "_" . $schedules_vals->serviceid . $mem . "_check' />";
                // }else if($servicerole[$schedules_vals->serviceid] == 2){
            }
            else if (in_array($servicerole[$schedules_vals->serviceid], array(1, 2))) {
                $schedule_str .= "<input name='ischeck' type='checkbox' disabled='disabled' id='" . $schedules_vals->scheduleid . "_" . $schedules_vals->serviceid . $mem . "_check' />";
            }
            $schedule_str .= "
            </td>
    <td width='176' align='center'><span class='fontweight' id='" . $schedules_vals->scheduleid . "_se'>" . $service[$schedules_vals->serviceid] . "</span></td>
    <td width='108' align='center' id='" . $schedules_vals->scheduleid . "_st'>" . $startdate[0] . "<br/>
" . $startdate[1] . " " . $startdate[2] . $tz . "</td>
    <td width='110' align='center' id='" . $schedules_vals->scheduleid . "_en'>" . $enddate[0] . "<br/>
" . $enddate[1] . " " . $enddate[2] . $tz . "</td>
    <td align='center' id='" . $schedules_vals->scheduleid . "_me'>" . $member_str . "</td>
    <td width='58'><span class='table4'><a cursor:pointer; title='View'
onclick=\"viewSchedule('" . $schedules_vals->scheduleid . "_" . $schedules_vals->serviceid . "')\"> </a></span></td>";



            // if(in_array($servicerole[$schedules_vals->serviceid],array(0,1))){
            if (in_array($servicerole[$schedules_vals->serviceid], array(0))) {
                $schedule_str .= "
    <td width='58'><span class='table5'><a  cursor:pointer; title='Edit' onclick=\"editSchedule('" . $schedules_vals->scheduleid . "_" . $schedules_vals->serviceid . "')\"></a></span></td>
    <td width='59'><span class='table6'><a href='#' onclick='deleteSchedule(" . $schedules_vals->scheduleid . ")' cursor:pointer; title='Delete'></a></span></td>";
                // }else if($servicerole[$schedules_vals->serviceid] == 2){
            }
            else if (in_array($servicerole[$schedules_vals->serviceid], array(1, 2))) {
                $schedule_str .= " <td width='58'><span class='table5img'></span></td>  <td width='59'><span class='table6img'></span></td>";
            }

            $schedule_str .= "</tr></table></li>";

            if ($j==$count){
                $schedule_str .= "<li class='cutoff3'> </li>";
            }
            else{
                $schedule_str .= "<li class='cutoff2'> </li>";
            }

            $j++;
        }
    } else {
        $schedule_str .= "<li class='tablebg4'><font size='3'>When you have already created a schedule or you are participating in an activity created by someone else, you’ll see it here.</font></li>
<li class='cutoff3'></li>";
    }
}

echo $schedule_str;
?>

</ul>
</div>
<div class="main5">
    <input type="button" class="mname3" onclick='repeatSelected()'> &nbsp;<span style="font-size: 12px">* Create repeated schedules for your activity</span>
</div>

<?php include_once(dirname(dirname(__FILE__)) . '/footer.php'); ?>

</body>
<script type='text/javascript'>
$(function () {
    $("#viewschedulepopup").jqm({
        modal: true,
        overlay: 40,
        onShow: function (h) {
            h.w.fadeIn(500);
        },
        onHide: function (h) {
            h.o.remove();
            h.w.fadeOut(500)
        }
    });

    $("#editschedulepopup").jqm({
        modal: true,
        overlay: 40,
        onShow: function (h) {
            h.w.fadeIn(500);
        },
        onHide: function (h) {
            h.o.remove();
            h.w.fadeOut(500)
        }
    });

    $("#repeatschedulepopup").jqm({
        modal: true,
        overlay: 40,
        onShow: function (h) {
            h.w.fadeIn(500);
        },
        onHide: function (h) {
            h.o.remove();
            h.w.fadeOut(500)
        }
    });

    $("#confirmChangeStatusPopup").jqm({
        modal: true,
        overlay: 40,
        onShow: function (h) {
            h.w.fadeIn(500);
        },
        onHide: function (h) {
            h.o.remove();
            h.w.fadeOut(500)
        }
    });

    $("#repeatPeriod").change(function(){
        if ($(this).val()=="weekly"){
            $("#weekdays").show();
        }
        else{
            $("#weekdays").hide();
        }
    })


    $("#repeatUserSelect").jqm({
        modal: true,
        overlay: 40,
        onShow: function (h) {
            h.w.fadeIn(500);
        },
        onHide: function (h) {
            h.o.remove();
            h.w.fadeOut(500)
        }
    });

    $("#repeatForm").submit(function(e)
    {
        $(".showbox").stop(true).animate({'margin-top':'300px','opacity':'1'},200);
    });
});

var homeUrl;
var homeUrl = "<?php echo Yii::app()->homeUrl;?>";
var schedulesurl = "<?php echo Yii::app()->createUrl("Schedule/Admin");?>";


function deleteSchedule(i) {
    <?php
        echo CHtml::ajax(
            array(
                    "url" => CController::createUrl("Schedule/delete"),
                    "data" => "js:{id : i}",
                    "type"=>"POST",
                    'beforeSend'=>"js:function(){
                        if(confirm('Are you sure to delete the schedule?')){
                            $(\".showbox\").stop(true).animate({'margin-top':'300px','opacity':'1'},200);
                        }else{
                            return false;
                        }
                    }",
                    "success"=>"js:function(data){
                            // $(\".showbox\").stop(true).animate({'margin-top':'250px','opacity':'0'},400);
                            // if(data=='ok'){
                                // history.go(0);
                                location.href = schedulesurl;
                            // }else{
                                // alert('Fail to delete the schedule.');
                            // }

                            if(data == 'ajaxsessionout'){
                                location.href = homeUrl;
                            }

                    }",
                )
        );
    ?>
}


var shareMembers = [];
var repeatScheduleMemberIds = "";
var repeatScheduleMemberDisplay = "";
function repeatSelected() {
    var length = document.getElementsByName('ischeck').length;
    var selected = document.getElementsByName('ischeck');
    $("#addRepeatScheduleButton").hide();
    var activityid = new Array();
    var count = 0;
    for (var i = 0; i < length; i++) {
        if (selected[i].checked) {
            count++;
            var selectedids = selected[i].id;
            activityid.push(selectedids);
        }
    }
    if (count == 0) {
        alert('Please select a schedule!');
        return;
    } else if (count> 1){
        alert('Please select one schedule only!');
        return;
    }

    $("#btnSubmitSchedule").hide();
    $("#repeatSchedules > tbody").html("");
    repeatItemsTotal = 0;



    var arr = activityid[0].split('_');
    var activity = arr[1];

    document.getElementById("repeatScheduleId").value = arr[0];
    document.getElementById("repeatActivityId").value = arr[1];

    var img = "<img src='<?php echo Yii::app()->baseUrl.'/images/loading.gif';?>'>";
    <?php
        echo CHtml::ajax(
            array(
                "url" => CController::createUrl("Schedule/GetSharedMembersData"),
                "data" => "js:{activity:activity}",
                "type"=>"POST",
                "async"=>false,
                'beforeSend'=>"js:function(){
                    document.getElementById('sharememberlist').innerHTML = img;
                }",
                "success"=>"js:function(data){

                    if(data == 'ajaxsessionout'){
                        location.href = homeUrl;
                        return;
                    }

                    $('#divRepeatParticipants').html('');
                    shareMembers = $.parseJSON(data);

                    //document.getElementById('sharememberlist').innerHTML = data;
                }",
            )
        );
    ?>

    <?php
        echo CHtml::ajax(array(
            "url" => CController::createUrl("Schedule/View"),
            "data" => "js:{schedule: arr[0],activity : arr[1]}",
            "type"=>"POST",
            "success"=>"js:function(json){
                var data = eval('('+json+')');

                if(typeof(data.data) == 'undefined'){
                    document.getElementById('repeatSchedulePopupTitle').innerHTML = 'Repeat Schedules of ' + data.name;
                    document.getElementById('repeatScheduleStart').value = data.start;
                    document.getElementById('repeatScheduleEnd').value = data.end;
                    document.getElementById('repeatScheduleDesc').value = data.desp;
                    document.getElementById('repeatScheduleTimezone').value = data.tzid;
                    document.getElementById('repeatScheduleAlert').value = data.alert;
                    repeatScheduleMemberIds = data.memberIds;
                    initilizeRepeatSelectMembers();

                }else{
                    location.href = homeUrl;
                }

            }",
        ));
    ?>
    $("#repeatschedulepopup").jqmShow();
}

function initilizeRepeatSelectMembers(){
    memberIds = repeatScheduleMemberIds.split(",");
    memberSelected = [];
    $(shareMembers).each(function(index, member){
        if ($.inArray(member.memberid, memberIds) >= 0)
            memberSelected.push(member.membername);

        if (member.sharedrole=="0") {
            $('#divRepeatParticipants').append('<input checked="checked" disabled type="checkbox" value="'+member.memberid+'" id="'+member.memberid+'"/> <label for="'+member.memberid+'">'+member.membername+'</label> <br/>' );
        }
        else{
            $('#divRepeatParticipants').append('<input type="checkbox" value="'+member.memberid+'" id="'+member.memberid+'"/> <label for="'+member.memberid+'">'+member.membername+'</label> <br/>' );
        }
    });

    repeatScheduleMemberDisplay = memberSelected.join(", ");
    $("#addRepeatScheduleButton").show();

    $("#repeatSchedules > tbody").append('<tr>'
        + '<td class="start">'
        +'<span>'+$("#repeatScheduleStart").val()+'</span> '
        + '</td>'
        + '<td class="end">'
        +'<span>'+$("#repeatScheduleEnd").val()+'</span> '
        + '</td>'
        + '<td class="participants">'
        +'<span class="members">'+repeatScheduleMemberDisplay+'</span>'
        + '</td>'
        + '<td>&nbsp;'
        + '</td>'
        + '</tr>');
}



function addRepeatSchedule(){
    var start = $('#repeatStartAt').val();
    var end = $('#repeatEndAt').val();



    if (start == '' || start == null) {
        document.getElementById('repeatTimeErrorTr').style.display = "";

        document.getElementById('repeatTimeError').innerHTML = 'Start Time can not be empty.';
        document.getElementById('repeatStartAt').focus();
        return;
    }
    if (end == '' || end == null) {
        document.getElementById('repeatTimeErrorTr').style.display = "";

        document.getElementById('repeatTimeError').innerHTML = 'End Time can not be empty.';
        document.getElementById('repeatEndAt').focus();
        return;
    }

    start = moment(start, "MM/DD/YYYY");
    end = moment(end, "MM/DD/YYYY");
    if (start > end) {
        document.getElementById('repeatTimeErrorTr').style.display = "";

        document.getElementById('repeatTimeError').innerHTML = 'Start should be smaller than End.';
        document.getElementById('repeatStartAt').focus();
        return;
    }



    var weekdaySelected = [];

    $("#weekdays input[type=checkbox]:checked").each(function(){
        weekdaySelected.push($(this).val());
    });

    if ($("#repeatPeriod")== "weekly" && weekdays.length==0 ){
        document.getElementById('repeatTimeErrorTr').style.display = "";

        document.getElementById('repeatTimeError').innerHTML = 'Please select days of week';
        document.getElementById('repeatStartAt').focus();
        return;
    }

    document.getElementById('repeatTimeErrorTr').style.display = "none";
    var schduleStart = moment($("#repeatScheduleStart").val(), "MM/DD/YYYY hh:mm A");
    var schduleEnd =moment($("#repeatScheduleEnd").val(), "MM/DD/YYYY hh:mm A");
    var timeDiff = schduleEnd - schduleStart;


    while(start <= end){
        if ($("#repeatPeriod").val()== "weekly"){
            for (var i=0; i < 7; i++){
                if (i >= start.days() && $.inArray(i+"",  weekdaySelected)>=0){
                    start = moment(start).add("days", i-start.days());
                    if (start<end)
                        addRepeatScheduleRow(start, schduleStart, timeDiff);
                }
            }
        }
        else{
            addRepeatScheduleRow(start, schduleStart, timeDiff);
        }


        if ($("#repeatPeriod").val() == "daily"){
            start = moment(start).add('days', $("#repeatNumber").val());
        }
        else if ($("#repeatPeriod").val()== "weekly"){
            start =  moment(start).add('days', 1);
        }
        else if ($("#repeatPeriod").val()== "monthly"){
            start =  moment(start).add('months',  $("#repeatNumber").val());
        }
        else if ($("#repeatPeriod").val()== "yearly"){
            start =  moment(start).add('years',  $("#repeatNumber").val());
        }
    }

    $('.datetimepicker').datetimepicker({
        step: 5,
        format: 'm/d/Y g:i A',
        formatTime: 'g:i A'
    });

    $("#btnSubmitSchedule").show();
}

var repeatItemsTotal = 0;

function addRepeatScheduleRow(start, schduleStart, timeDiff){
    $(".divRepeats").show();
    if (repeatItemsTotal >40){
        document.getElementById('repeatTimeErrorTr').style.display = "";
        document.getElementById('repeatTimeError').innerHTML = 'Sorry, you are not allow to repeat more than 40 schedules.';
        return;
    }
    repeatItemsTotal+=1;

    var startDateTime =  moment(start).add('hours', schduleStart.format("H")).add('minutes', schduleStart.minutes());
    var startTime = startDateTime.format("MM/DD/YYYY hh:mm A");
    var endTime = moment(startDateTime).add('milliseconds', timeDiff).format("MM/DD/YYYY hh:mm A");

    $("#repeatSchedules > tbody").append('<tr>'
        + '<td class="start">'
        +'<input type="text" name="start[]" value="'+startTime+'" class="datetimepicker" /> '
        + '</td>'
        + '<td class="end">'
        +'<input type="text" name="end[]" value="'+endTime+'" class="datetimepicker" /> '
        + '</td>'
        + '<td class="participants">'
        +'<input type="hidden" name="members[]" value="'+repeatScheduleMemberIds+'" /> '
        +'<span class="members">'+repeatScheduleMemberDisplay+'</span>'
        +'<a class="repeatParicipantChange" href="javascript:void(0)" onclick="selectParticipant(this)">Change</a>'
        + '</td>'
        + '<td>'
        +'<button type="button" onclick="deleteRepeatSchedule(this)" >X</button> '
        + '</td>'
        + '</tr>');
}

function deleteRepeatSchedule(button){
    $(button).parent().parent().remove();
    repeatItemsTotal -=1;
    if (repeatItemsTotal==0)
        $("#btnSubmitSchedule").hide();
}

var changeParticipantRow;
function selectParticipant(button){
    changeParticipantRow = $(button).parent().parent(); // get <tr>
    var ids = $(changeParticipantRow).find(".participants input").val().split(",");

    $("#repeatUserSelect").jqmShow();
    $("#divRepeatParticipants input[type=checkbox]").removeAttr("checked");
    $(ids).each(function(index, id){
        $("#"+id).attr("checked", "checked");
    });
}


function changeRepeatParticipants(){
    var ids = [];
    var names = [];
    $("#divRepeatParticipants input[type=checkbox]:checked").each(function(index, checkbox){
            for (var i=0;i<shareMembers.length;i++){
                if (shareMembers[i].memberid==$(checkbox).val()){
                    ids.push(shareMembers[i].memberid);
                    names.push(shareMembers[i].membername);
                    break;
                }
            }
        }
    );

    $($(changeParticipantRow).find(".participants input")[0]).val(ids.join(","));
    $(changeParticipantRow).find(".participants span.members").html(names.join(", "));

    $("#repeatUserSelect").jqmHide();
}

function validateSubmitRepeat(){
    if ($("#repeatSchedules .participants input[value=]").length>0){
        alert("Schedule must have at least 1 participant.");
        return false;
    }

    var startDates = $("#repeatSchedules .start input");
    var endDates = $("#repeatSchedules .end input");

    for (var i=0;i < startDates.length;i ++){
        start = moment($(startDates[i]).val(),  "MM/DD/YYYY hh:mm A");
        end = moment($(endDates[i]).val(),  "MM/DD/YYYY hh:mm A");

        if (start>end){
            alert("Start should be smaller than End.");
            $(startDates[i]).focus();
            return false;
        }
    }

    $("#repeatForm").submit();
}


function viewSchedule(i) {
    $("#viewschedulepopup").jqmShow();

    var arr = i.split('_');

    //var timezone = document.getElementById('timezone_' + arr[1]).value;
    // alert(timezone);
    <?php
        echo CHtml::ajax(array(
            "url" => CController::createUrl("Schedule/View"),
            "data" => "js:{schedule: arr[0],activity : arr[1]}",
            "type"=>"POST",
            "success"=>"js:function(json){
                var data = eval('('+json+')');
                // alert(data);

                if(typeof(data.data) == 'undefined'){

                    document.getElementById('viewname').value = data.name;
                    document.getElementById('viewstart').value = data.start;
                    document.getElementById('viewend').value = data.end;
                    document.getElementById('viewdesp').value = data.desp;
                    document.getElementById('viewonduty').innerHTML = data.onduty;
                    document.getElementById('viewtimezone').value = data.tzid;
                    document.getElementById('viewalert').value = data.alert;
                }else{
                    location.href = homeUrl;
                }

            }",
        ));
    ?>
}

var isRepeat = false;
function editSchedule(i, repeat) {

    if ((typeof repeat !== 'undefined') && (repeat)){
        $("#schedulePopupTitle").html("Repeat schedule");
        isRepeat = true;
    }
    else{
        $("#schedulePopupTitle").html("Edit schedule");
        isRepeat = false;
    }

    $("#editschedulepopup").jqmShow();

    document.getElementById('sharememberlist').innerHTML = "";
    document.getElementById('editonduty').innerHTML = "";
    document.getElementById('editstart').value = "";
    document.getElementById('editend').value = "";
    document.getElementById('editdesp').value = "";


    var arr = i.split('_');
    var activity = arr[1];

    document.getElementById("hidschedule").value = isRepeat ? 0 : arr[0];
    document.getElementById("hidactivity").value = arr[1];

    document.getElementById("editactivity").value = document.getElementById(arr[0] + "_se").value;


    var img = "<img src= '<?php echo Yii::app()->baseUrl.'/images/loading.gif';?>'>";
    <?php
        echo CHtml::ajax(
            array(
                "url" => CController::createUrl("Schedule/GetSharedMembers"),
                "data" => "js:{activity:activity}",
                "type"=>"POST",
                "async"=>false,
                'beforeSend'=>"js:function(){
                    document.getElementById('sharememberlist').innerHTML = img;
                }",
                "success"=>"js:function(data){
                    if(data == 'ajaxsessionout'){
                        location.href = homeUrl;
                        return;
                    }

                    document.getElementById('sharememberlist').innerHTML = data;
                }",
            )
        );
    ?>

    <?php
        echo CHtml::ajax(array(
            "url" => CController::createUrl("Schedule/View"),
            "data" => "js:{schedule: arr[0],activity : arr[1]}",
            "type"=>"POST",
            "success"=>"js:function(json){
                var data = eval('('+json+')');

                if(typeof(data.data) == 'undefined'){
                    document.getElementById('editactivity').value = data.name;
                    document.getElementById('editstart').value = data.start;
                    document.getElementById('editend').value = data.end;
                    document.getElementById('editdesp').value = data.desp;
                    document.getElementById('edittimezone').value = data.tzid;
                    document.getElementById('editalert').value = data.alert;

                    document.getElementById('editonduty').innerHTML = data.onduty;

                    var selected_ids = document.getElementsByName('selectedmembers');

                    for(var k=0;k<selected_ids.length;k++){
                        var selected = selected_ids[k].id;
                        document.getElementById(selected.substring(0,selected.length-9)+'_check').checked = true;
                    }

                }else{
                    location.href = homeUrl;
                }

            }",
        ));
    ?>


}

function is_Checked(i) {
    var status = document.getElementById(i + '_check').checked;
    if (status) {
        var name = document.getElementById(i + '_name').innerHTML;

        $("<li id='" + i + "_selected'><table border='0' cellspacing='0' cellpadding='0'><tr><td><span class='name'><a href='#'>" + name + "</a></span></td><td width='25'><span class='cha' onclick='deleteContact(" + i + ")' style='cursor:pointer;'></span></td></tr></table></li>").appendTo('#editonduty');
    } else {
        $('#' + i + '_selected').remove();
    }
}

function deleteContact(i) {
    $('#' + i + '_selected').remove();
    document.getElementById(i + '_check').checked = false;
}

function submitSchedule() {
    var activity = $('#hidactivity').val();
    var start = $('#editstart').val();
    var end = $('#editend').val();
    var desp = $('#editdesp').val();
    var timezone = $('#edittimezone').val();
    var alertId = $('#editalert').val();

    var schedule = $("#hidschedule").val();

    if (start == '' || start == null) {
        document.getElementById('editerror1').style.display = "";
        document.getElementById('editerror2').style.display = "none";
        document.getElementById('editerror3').style.display = "none";

        document.getElementById('error1').innerHTML = 'Start Time can not be empty.';
        document.getElementById('editstart').focus();
        return;
    }
    if (end == '' || end == null) {
        document.getElementById('editerror1').style.display = "none";
        document.getElementById('editerror2').style.display = "";
        document.getElementById('editerror3').style.display = "none";

        document.getElementById('error2').innerHTML = 'End Time can not be empty.';
        document.getElementById('editend').focus();
        return;
    }

    if (start > end) {
        document.getElementById('editerror1').style.display = "";
        document.getElementById('editerror2').style.display = "none";
        document.getElementById('editerror3').style.display = "none";

        document.getElementById('error1').innerHTML = 'Start should be smaller than End.';
        document.getElementById('editstart').focus();
        return;
    }


    var checkbox = document.getElementsByName('contact_check');
    var participants = "";
    var part_name = "";

    for (var i = 0; i < checkbox.length; i++) {
        if (checkbox[i].checked) {
            var par = checkbox[i].id;
            participants += "," + par.substring(0, par.length - 6);

            var p_name = $('#' + (par.substring(0, par.length - 6)) + '_name').html();
            part_name += "<br>" + p_name;
        }
    }
    if (participants.length == 0) {
        document.getElementById('editerror1').style.display = "none";
        document.getElementById('editerror2').style.display = "none";
        document.getElementById('editerror3').style.display = "";
        document.getElementById('error3').innerHTML = 'Please select at least one shared contact!';
        return;
    }
    var onduty = participants.substr(1);
    var names = part_name.substr(4);
    var url = "<?php echo Yii::app()->createUrl('Schedule/admin');?>";
    if (isRepeat==false){
    <?php
        echo CHtml::ajax(
            array(
                "url" => CController::createUrl("Schedule/EditSchedule"),
                "data" => "js:{schedule:schedule,activity:activity,start:start,end:end,desp:desp,onduty:onduty,timezone:timezone,names:names, alert:alertId}",
                "type"=>"POST",
                'beforeSend'=>"js:function(){
                    $(\".showbox\").stop(true).animate({'margin-top':'300px','opacity':'1'},200);
                }",
                "success"=>"js:function(data){

                    if(data == 'ajaxsessionout'){
                        location.href = homeUrl;
                        return;
                    }
                    var json = eval('('+data+')');
                    if(json.status == 'ok'){
                        $(\"#editschedulepopup\").jqmHide();
                        $('#'+schedule+'_st').html(json.start);
                        $('#'+schedule+'_en').html(json.end);
                        $('#'+schedule+'_me').html(decodeURI(json.participant));

                    }else{
                        document.getElementById('error1').innerHTML = '<font color = \'red\'>'+data+'</font>'
                    }

                    $(\".showbox\").stop(true).animate({'margin-top':'250px','opacity':'0'},400);
                }",
            )
        );
    ?>
    }
    else{
        <?php
           echo CHtml::ajax(
               array(
                   "url" => CController::createUrl("Schedule/createSchedule"),
                   "data" => "js:{activity:activity,start:start,end:end,desp:desp,onduty:onduty,timezone:timezone,names:names, alert:alertId}",
                   "type"=>"POST",
                   'beforeSend'=>"js:function(){
                       $(\".showbox\").stop(true).animate({'margin-top':'300px','opacity':'1'},200);
                   }",
                   "success"=>"js:function(data){

                       if(data == 'ajaxsessionout'){
                           location.href = homeUrl;
                           return;
                       }

                       if(data == 'ok'){
                            alert(\"New schedule is created.\");
                            location.reload();
                       }else{
                            alert(\"Sorry, please try late.\");
                       }

                       $(\".showbox\").stop(true).animate({'margin-top':'250px','opacity':'0'},400);
                   }",
               )
           );
       ?>
    }
}

var currentStatus;
function showConfirm(checkbox){
    currentStatus = checkbox;
    $(".confirm-status").hide();
    if ($(currentStatus).hasClass('confirm-0')){
        $("#confirm-general").show();
    }
    else if ($(currentStatus).hasClass('confirm-1')){
        $("#confirm-deny").show();
    }
    else if ($(currentStatus).hasClass('confirm-2')){
        $("#confirm-accept").show();
    }
    $("#confirmChangeStatusPopup").jqmShow();
}

function closeConfirmPopup(){
    $("#confirmChangeStatusPopup").jqmHide();
}

function confirmAccept(){
    $("#confirmChangeStatusPopup").jqmHide();
    sendConfirmStatus(1);
}

function confirmDeny(){
    $("#confirmChangeStatusPopup").jqmHide();
    sendConfirmStatus(2);
}

function sendConfirmStatus(confirm){
    $(".showbox").stop(true).animate({'margin-top':'300px','opacity':'1'},200);

    var ids =  $(currentStatus).attr('id');
    <?php
        echo CHtml::ajax(
            array(
                "url" => CController::createUrl("Schedule/UpdateJoinStatus"),
                "data" => "js:{ids:ids,confirm:confirm}",
                "type"=>"POST",
                "success"=>"js:function(data){
                    $(\".showbox\").stop(true).animate({'margin-top':'250px','opacity':'0'},400);
                    if(data == 'ok'){
                        $(currentStatus).removeClass('confirm-0').removeClass('confirm-1').removeClass('confirm-2');
                        $(currentStatus).addClass('confirm-'+confirm);
                    }
                    else{
                         alert('Sorry, please try late.');
                    }
                }",
                "error"=>"js:function(data){
                    $(\".showbox\").stop(true).animate({'margin-top':'250px','opacity':'0'},400);
                     alert('Sorry, please try late.');
                }"
            )
        );
    ?>
}


</script>

<!--日期样式-->

<script language='javascript'>
    $('#editstart').datetimepicker({
        step: 5,
        format: 'm/d/Y g:i A',
        formatTime: 'g:i A'
    });
    $('#editend').datetimepicker({
        step: 5,
        format: 'm/d/Y g:i A',
        formatTime: 'g:i A'
    });
    $('#repeatStartAt').datetimepicker({
        timepicker:false,
        format: 'm/d/Y'

    });
    $('#repeatEndAt').datetimepicker({
        timepicker:false,
        format: 'm/d/Y'
    });
</script>
</html>
