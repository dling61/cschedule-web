<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Blog|CSchedule</title>
<link href="./css/cschedule.css" rel="stylesheet" type="text/css" />
</head>
<body >

<div class="main11"><div class="top3"><a href="<?php echo Yii::app()->homeUrl;?>"></a></div></div>
<div class="main12"><div><div  class="size3">CSchedule Blog</div>  <br/> 

<?php
$str = "";
if($posts){
	foreach($posts as $vals){
		//获取的时间数组
		$o_timearr = explode(",",$vals['date']);
		$n_timearr = explode(" ",$o_timearr[1]);
		$date = $o_timearr[0].', '.$n_timearr[2]." ".$n_timearr[1]." ".$n_timearr[3]." ".$n_timearr[4];
		
		$str .= "<p style='font-size:16px;'>".$date."</p><p>".$vals->{'regular-body'}."</p><hr>";
	}
	echo substr($str,0,-4);
}
?>                     
 
</div>


</div>

<?php include_once(dirname(dirname(__FILE__)).'/footer.php');?>

</body>
</html>
