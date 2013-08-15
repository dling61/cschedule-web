<?php
/* @var $this ServiceController */
/* @var $model Service */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'service-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'Service_Name'); ?>
		<?php echo $form->textField($model,'Service_Name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'Service_Name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Description'); ?>
		<?php echo $form->textField($model,'Description',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'Description'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'Alert'); ?>
		<?php echo $form->textField($model,'Alert'); ?>
		<?php echo $form->error($model,'Alert'); ?>
	</div>
	
		
	<div class="row">
		<?php echo $form->labelEx($model,'SRepeat'); ?>
		<?php echo $form->textField($model,'SRepeat'); ?>
		<?php echo $form->error($model,'SRepeat'); ?>
	</div>
	

	<div class="row">
		<?php echo $form->labelEx($model,'Start_Datetime'); ?>
		<?php //echo $form->textField($model,'Start_Datetime'); ?>
		<?php 
			$this->widget('ext.timepicker.timepicker', array(
				'model'=>$model,
				'name'=>'Start_Datetime',
			));
		?>
		<?php echo $form->error($model,'Start_Datetime'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'End_Datetime'); ?>
		<?php 
			$this->widget('ext.timepicker.timepicker', array(
				'model'=>$model,
				'name'=>'End_Datetime',
			));
		?>
		<?php echo $form->error($model,'End_Datetime'); ?>
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
var time = document.getElementById('Service_Created_Time');
time.value = timestamp;
</script>
