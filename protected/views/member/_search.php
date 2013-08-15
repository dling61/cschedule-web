<?php
/* @var $this MemberController */
/* @var $model Member */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'Member_Id'); ?>
		<?php echo $form->textField($model,'Member_Id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Member_Email'); ?>
		<?php echo $form->textField($model,'Member_Email',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Member_Name'); ?>
		<?php echo $form->textField($model,'Member_Name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Mobile_Number'); ?>
		<?php echo $form->textField($model,'Mobile_Number'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Is_Registered'); ?>
		<?php echo $form->textField($model,'Is_Registered'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Creator_Id'); ?>
		<?php echo $form->textField($model,'Creator_Id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Created_Time'); ?>
		<?php echo $form->textField($model,'Created_Time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Is_Deleted'); ?>
		<?php echo $form->textField($model,'Is_Deleted'); ?>
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