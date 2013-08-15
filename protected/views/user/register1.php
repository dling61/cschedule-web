<?php
$this->pageTitle=Yii::app()->name . ' - Sign up';
$this->breadcrumbs=array(
	'Sign up',
);
?>

<h1>Sign Up</h1>

<p>Please fill out the following form with your login credentials:</p>

<div class="form">
	<p class="note">Fields with <span class="required">*</span> are required.</p>
	
	<form action = '<?php echo Yii::app()->createUrl('User/Register');?>' name = 'signup' method = 'POST'>
	Email <span class="required">*</span><div class='row'><input type= 'text' id= 'email' name = 'email'></div>
	Username <span class="required">*</span><div class='row'><input type= 'text' id= 'username' name = 'username'></div>
	Password <span class="required">*</span><div class='row'><input type= 'password' id= 'password' name= 'password'></div>
	Re-enter Password <span class="required">*</span><div class='row'><input type= 'password' id= 'repassword'></div>
	Mobile <div class='row'><input type= 'text' id= 'mobile' name = 'mobile'></div>
	<div class='row'><input type= 'submit' value='submit' onclick='return checkValue()'></div>
	</form>
</div>

<script language= 'javascript'>
	function checkValue(){
		var email = document.getElementById('email').value;
		var username = document.getElementById('username').value;
		var password = document.getElementById('password').value;
		var repassword = document.getElementById('repassword').value;
		if(email == ''){
			alert('Email can not be blank');
			return false;
		}
		if(username == ''){
			alert('Username can not be blank');
			return false;
		}
		if(password == ''){
			alert('Password can not be blank');
			return false;
		}
		if(repassword == ''){
			alert('Re-enter password can not be blank');
			return false;
		}
		if(password != repassword){
			alert('Passwords don\'t match');
			return false;
		}
	}
</script>