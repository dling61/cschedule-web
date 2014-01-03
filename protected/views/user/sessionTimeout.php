<?php
$this->pageTitle=Yii::app()->name . ' - Session Timeout';
?>

<h1>Session Timeout</h1>

<div class="error">
<?php echo "Session timed out. Please <a href='".Yii::app()->createUrl('User/Login')."'><strong>Login</strong></a> again to continue."; ?>
</div>