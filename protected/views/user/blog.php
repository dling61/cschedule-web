<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
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
		$str .= "<p class='size4'>".$vals['date']."</p><p>".$vals->{'regular-body'}."</p><hr>";
	}
	echo substr($str,0,-4);
}
?>                     
 
</div>


</div>

<?php include_once(dirname(dirname(__FILE__)).'/footer.php');?>

</body>
</html>
