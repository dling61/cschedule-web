<?php
/* @var $this MemberController */
/* @var $model Member */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'member-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'Member_Email'); ?>
		<?php echo $form->textField($model,'Member_Email',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'Member_Email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Member_Name'); ?>
		<?php echo $form->textField($model,'Member_Name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'Member_Name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Mobile_Number'); ?>
		<?php echo $form->textField($model,'Mobile_Number'); ?>
		<?php echo $form->error($model,'Mobile_Number'); ?>
	</div>
	
	<?php 
		echo $form->hiddenField($model,'Created_Time');
	?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<!-- get UTC Time -->
<script language = 'javascript'>
var timestamp = Math.round(new Date().getTime()/1000) ;
var time = document.getElementById('Member_Created_Time');
time.value = timestamp;
</script>

