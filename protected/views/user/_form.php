<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'Email'); ?>
		<?php echo $form->textField($model,'Email',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'Email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'User_Name'); ?>
		<?php echo $form->textField($model,'User_Name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'User_Name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Password'); ?>
		<?php echo $form->passwordField($model,'Password',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'Password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Mobile'); ?>
		<?php echo $form->textField($model,'Mobile'); ?>
		<?php echo $form->error($model,'Mobile'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'User_Type'); ?>
		<?php echo $form->textField($model,'User_Type',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'User_Type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Verified'); ?>
		<?php echo $form->textField($model,'Verified'); ?>
		<?php echo $form->error($model,'Verified'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Created_Time'); ?>
		<?php echo $form->textField($model,'Created_Time'); ?>
		<?php echo $form->error($model,'Created_Time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Last_Modified'); ?>
		<?php echo $form->textField($model,'Last_Modified'); ?>
		<?php echo $form->error($model,'Last_Modified'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->