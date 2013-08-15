<?php
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>
<script type="text/javascript" src='<?php echo Yii::app()->baseUrl."/js/jquery-1.3.2.min.js";?>'></script>
<h1>Login</h1>

<p>Please fill out the following form with your login credentials:</p>

<div class="form">
	<p class="note">Fields with <span class="required">*</span> are required.</p>
	
	<form action = '<?php echo Yii::app()->createUrl('User/Login');?>' name = 'signup' method = 'POST'>
	Email <span class="required">*</span><div class='row'><input type= 'text' id= 'email' name = 'email'></div>
	Password <span class="required">*</span><div class='row'><input type= 'password' id= 'password' name= 'password'></div>
	<span id= 'error_info'></span>
	<div class='row'><input type= 'submit' value='submit' onclick='checkValue()'></div>
	</form>
</div>
<p class="hint">
	&raquo; <a href='<?php echo Yii::app()->createUrl('User/Register')?>'>Register for a new account</a>
</p>
	
<script language= 'javascript'>
	function checkValue(){
		var email = document.getElementById('email').value;
		var password = document.getElementById('password').value;
		<?php
			echo CHtml::ajax(
				array(
					// "url" => CController::createUrl("User/Login"),
					"url"=>'http://127.0.0.1/index.php',
					"data" => "js:{email : email, password : password}",
					"type"=>"POST",
					// 'error'=>"js:function(){alert('ooook')}",
					"success"=>"js:function(data){alert(data)}",
				)
			);
		?>

	}
</script>