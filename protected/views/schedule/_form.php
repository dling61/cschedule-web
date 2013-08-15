<?php
/* @var $this ScheduleController */
/* @var $model Schedule */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'schedule-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'Start_DateTime'); ?>
		<?php 
			$this->widget('ext.timepicker.timepicker', array(
				'model'=>$model,
				'name'=>'Start_DateTime',
			));
		?>
		<?php echo $form->error($model,'Start_DateTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'End_DateTime'); ?>
		<?php 
			$this->widget('ext.timepicker.timepicker', array(
				'model'=>$model,
				'name'=>'End_DateTime',
			));
		?>
		<?php echo $form->error($model,'End_DateTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Description'); ?>
		<?php echo $form->textField($model,'Description',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'Description'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'Is_Deleted'); ?>
		<?php echo $form->textField($model,'Is_Deleted'); ?>
		<?php echo $form->error($model,'Is_Deleted'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'members'); ?>
		<?php echo CHtml::checkboxList('members','members',$model->getMemberOptions(),array('separator'=>'&nbsp;','labelOptions'=>array('style'=>'display:inline'))); ?>
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
var time = document.getElementById('Schedule_Created_Time');
time.value = timestamp;
</script>