<?php
/* @var $this ServiceController */
/* @var $model Service */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'Service_Id'); ?>
		<?php echo $form->textField($model,'Service_Id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Service_Name'); ?>
		<?php echo $form->textField($model,'Service_Name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Description'); ?>
		<?php echo $form->textField($model,'Description',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'SRepeat'); ?>
		<?php echo $form->textField($model,'SRepeat'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Start_Datetime'); ?>
		<?php echo $form->textField($model,'Start_Datetime'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'End_Datetime'); ?>
		<?php echo $form->textField($model,'End_Datetime'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'UTC_Off'); ?>
		<?php echo $form->textField($model,'UTC_Off'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Alert'); ?>
		<?php echo $form->textField($model,'Alert'); ?>
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