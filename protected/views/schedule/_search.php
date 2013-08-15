<?php
/* @var $this ScheduleController */
/* @var $model Schedule */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'Schedule_Id'); ?>
		<?php echo $form->textField($model,'Schedule_Id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Service_Id'); ?>
		<?php echo $form->textField($model,'Service_Id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Start_DateTime'); ?>
		<?php echo $form->textField($model,'Start_DateTime',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'End_DateTime'); ?>
		<?php echo $form->textField($model,'End_DateTime',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Description'); ?>
		<?php echo $form->textField($model,'Description',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Creator_Id'); ?>
		<?php echo $form->textField($model,'Creator_Id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Is_Deleted'); ?>
		<?php echo $form->textField($model,'Is_Deleted'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Created_Time'); ?>
		<?php echo $form->textField($model,'Created_Time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Last_Modified'); ?>
		<?php echo $form->textField($model,'Last_Modified'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->