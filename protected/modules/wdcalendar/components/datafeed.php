<?php
//include_once("dbconfig.php");
//include_once("functions.php");
require_once dirname( __FILE__ ) . '/functions.php';

function addCalendar($st, $et, $sub, $ade){
  $ret = array();
  try{
    $db = new DBConnection();
    $db->getConnection();
	
	$query = Yii::app()->db->createCommand("select max(Schedule_Id) as scheduleid from schedule")->queryAll();
	if($query){
		$scheduleid = $query[0]['scheduleid']+1; 
	}else $scheduleid=1;
	
    // $sql = "insert into `jqcalendar` (`subject`, `starttime`, `endtime`, `isalldayevent`) values ('"
	 $sql = "insert into `schedule` (`Schedule_Id`,`Description`, `Start_DateTime`, `End_DateTime`, `Is_Deleted`) values ('"
	  .$scheduleid."', '"
      .mysql_real_escape_string($sub)."', '"
      .js2PhpTime($st)."', '"
      .js2PhpTime($et)."', '"
      .mysql_real_escape_string($ade)."' )";
    //echo($sql);
		if(mysql_query($sql)==false){
      $ret['IsSuccess'] = false;
      $ret['Msg'] = mysql_error();
    }else{
      $ret['IsSuccess'] = true;
      $ret['Msg'] = 'add success';
      $ret['Data'] = mysql_insert_id();
    }
	}catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}


function addDetailedCalendar($st, $et, $sub, $ade, $dscr, $loc, $color, $tz){
  $ret = array();
  try{
    $db = new DBConnection();
    $db->getConnection();
    // $sql = "insert into `jqcalendar` (`subject`, `starttime`, `endtime`, `isalldayevent`, `description`, `location`, `color`) values ('"
	$sql = "insert into `schedule` (`Description`, `Start_DateTime`, `End_DateTime`, `Is_Deleted`) values ('"
      .mysql_real_escape_string($sub)."', '"
      .js2PhpTime($st)."', '"
      .js2PhpTime($et)."', '"
      .mysql_real_escape_string($ade)."')";
      // .mysql_real_escape_string($dscr)."', '"
      // .mysql_real_escape_string($loc)."', '"
    //echo($sql);
		if(mysql_query($sql)==false){
      $ret['IsSuccess'] = false;
      $ret['Msg'] = mysql_error();
    }else{
      $ret['IsSuccess'] = true;
      $ret['Msg'] = 'add success';
      $ret['Data'] = mysql_insert_id();
    }
	}catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}

function listCalendarByRange($sd, $ed){
  $ret = array();
  $ret['events'] = array();
  $ret["issort"] =true;
  $ret["start"] = php2JsTime($sd);
  $ret["end"] = php2JsTime($ed);
  $ret['error'] = null;
  try{
    $db = new DBConnection();
    $db->getConnection();
    // $sql = "select * from `jqcalendar` where `starttime` between '"
	$sql = "select * from `schedule` where `Start_DateTime` between '"
      .$sd."' and '". $ed."'";
    $handle = mysql_query($sql);
    //echo $sql;
    while ($row = mysql_fetch_object($handle)) {
      //$ret['events'][] = $row;
      //$attends = $row->AttendeeNames;
      //if($row->OtherAttendee){
      //  $attends .= $row->OtherAttendee;
      //}
      //echo $row->StartTime;
      $ret['events'][] = array(
        $row->Schedule_Id,
        $row->Description,
        php2JsTime($row->Start_DateTime),
        php2JsTime($row->End_DateTime),
        $row->Is_Deleted,
        0, //more than one day event
        //$row->InstanceType,
        0,//Recurring event,
        // $row->Color,
        1,//editable
        // $row->Location, 
        ''//$attends
      );
    }
	}catch(Exception $e){
     $ret['error'] = $e->getMessage();
  }
  return $ret;
}

function listCalendar($day, $type){
  $phpTime = js2PhpTime($day);
  //echo $phpTime . "+" . $type;
  switch($type){
    case "month":
      $st = mktime(0, 0, 0, date("m", $phpTime), 1, date("Y", $phpTime));
      $et = mktime(0, 0, -1, date("m", $phpTime)+1, 1, date("Y", $phpTime));
      break;
    case "week":
      //suppose first day of a week is monday 
      $monday  =  date("d", $phpTime) - date('N', $phpTime) + 1;
      //echo date('N', $phpTime);
      $st = mktime(0,0,0,date("m", $phpTime), $monday, date("Y", $phpTime));
      $et = mktime(0,0,-1,date("m", $phpTime), $monday+7, date("Y", $phpTime));
      break;
    case "day":
      $st = mktime(0, 0, 0, date("m", $phpTime), date("d", $phpTime), date("Y", $phpTime));
      $et = mktime(0, 0, -1, date("m", $phpTime), date("d", $phpTime)+1, date("Y", $phpTime));
      break;
  }
  //echo $st . "--" . $et;
  return listCalendarByRange($st, $et);
}

function updateCalendar($id, $st, $et){
  $ret = array();
  try{
    $db = new DBConnection();
    $db->getConnection();
    // $sql = "update `jqcalendar` set"
      // . " `starttime`='" . php2MySqlTime(js2PhpTime($st)) . "', "
      // . " `endtime`='" . php2MySqlTime(js2PhpTime($et)) . "' "
      // . "where `id`=" . $id;
	  
	  $sql = "update `schedule` set"
      . " `Start_DateTime`='" . php2MySqlTime(js2PhpTime($st)) . "', "
      . " `Start_DateTime`='" . php2MySqlTime(js2PhpTime($et)) . "' "
      . "where `Schedule_Id`=" . $id;
    //echo $sql;
		if(mysql_query($sql)==false){
      $ret['IsSuccess'] = false;
      $ret['Msg'] = mysql_error();
    }else{
      $ret['IsSuccess'] = true;
      $ret['Msg'] = 'Succefully';
    }
	}catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}

function updateDetailedCalendar($id, $st, $et, $sub, $ade, $dscr, $loc, $color, $tz){
  $ret = array();
  try{
    $db = new DBConnection();
    $db->getConnection();
    // $sql = "update `jqcalendar` set"
      // . " `starttime`='" . php2MySqlTime(js2PhpTime($st)) . "', "
      // . " `endtime`='" . php2MySqlTime(js2PhpTime($et)) . "', "
      // . " `subject`='" . mysql_real_escape_string($sub) . "', "
      // . " `isalldayevent`='" . mysql_real_escape_string($ade) . "', "
      // . " `description`='" . mysql_real_escape_string($dscr) . "', "
      // . " `location`='" . mysql_real_escape_string($loc) . "', "
      // . " `color`='" . mysql_real_escape_string($color) . "' "
      // . "where `id`=" . $id;
	  
	  $sql = "update `schedule` set"
      . " `Start_DateTime`='" . php2MySqlTime(js2PhpTime($st)) . "', "
      . " `End_DateTime`='" . php2MySqlTime(js2PhpTime($et)) . "', "
      . " `Description`='" . mysql_real_escape_string($sub) . "', "
      // . " `isalldayevent`='" . mysql_real_escape_string($ade) . "', "
      // . " `description`='" . mysql_real_escape_string($dscr) . "', "
      // . " `location`='" . mysql_real_escape_string($loc) . "', "
      . " `color`='" . mysql_real_escape_string($color) . "' "
      . "where `Schedule_Id`=" . $id;
	  
    //echo $sql;
		if(mysql_query($sql)==false){
      $ret['IsSuccess'] = false;
      $ret['Msg'] = mysql_error();
    }else{
      $ret['IsSuccess'] = true;
      $ret['Msg'] = 'Succefully';
    }
	}catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}

function removeCalendar($id){
  $ret = array();
  try{
    $db = new DBConnection();
    $db->getConnection();
    // $sql = "delete from `jqcalendar` where `id`=" . $id;
	$sql = "delete from `schedule` where `schedule_id`=" . $id;
		if(mysql_query($sql)==false){
      $ret['IsSuccess'] = false;
      $ret['Msg'] = mysql_error();
    }else{
      $ret['IsSuccess'] = true;
      $ret['Msg'] = 'Succefully';
    }
	}catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}




header('Content-type:text/javascript;charset=UTF-8');
$method = $_GET["method"];
switch ($method) {
    case "add":
        $ret = addCalendar($_POST["CalendarStartTime"], $_POST["CalendarEndTime"], $_POST["CalendarTitle"], $_POST["IsAllDayEvent"]);
        break;
    case "list":
        $ret = listCalendar($_POST["showdate"], $_POST["viewtype"]);
        break;
    case "update":
        $ret = updateCalendar($_POST["calendarId"], $_POST["CalendarStartTime"], $_POST["CalendarEndTime"]);
        break; 
    case "remove":
        $ret = removeCalendar( $_POST["calendarId"]);
        break;
    case "adddetails":
        $st = $_POST["stpartdate"] . " " . $_POST["stparttime"];
        $et = $_POST["etpartdate"] . " " . $_POST["etparttime"];
        if(isset($_GET["id"]) && (int)$_GET['id']){
            $ret = updateDetailedCalendar($_GET["id"], $st, $et, 
                $_POST["Subject"], isset($_POST["IsAllDayEvent"])?1:0, $_POST["Description"], 
                $_POST["Location"], $_POST["colorvalue"], $_POST["timezone"]);
        }else{
            $ret = addDetailedCalendar($st, $et,                    
                $_POST["Subject"], isset($_POST["IsAllDayEvent"])?1:0, $_POST["Description"], 
                $_POST["Location"], $_POST["colorvalue"], $_POST["timezone"]);
        }        
        break; 


}
echo json_encode($ret); 