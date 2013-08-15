<?php
/* @var $this UserController */
/* @var $data User */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('User_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->User_id), array('view', 'id'=>$data->User_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Email')); ?>:</b>
	<?php echo CHtml::encode($data->Email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('User_Name')); ?>:</b>
	<?php echo CHtml::encode($data->User_Name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Password')); ?>:</b>
	<?php echo CHtml::encode($data->Password); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Mobile')); ?>:</b>
	<?php echo CHtml::encode($data->Mobile); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('User_Type')); ?>:</b>
	<?php echo CHtml::encode($data->User_Type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Verified')); ?>:</b>
	<?php echo CHtml::encode($data->Verified); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('Created_Time')); ?>:</b>
	<?php echo CHtml::encode($data->Created_Time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Last_Modified')); ?>:</b>
	<?php echo CHtml::encode($data->Last_Modified); ?>
	<br />

	*/ ?>

</div>