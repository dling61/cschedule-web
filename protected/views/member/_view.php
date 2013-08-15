<?php
/* @var $this MemberController */
/* @var $data Member */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('Member_Id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->Member_Id), array('view', 'id'=>$data->Member_Id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Member_Email')); ?>:</b>
	<?php echo CHtml::encode($data->Member_Email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Member_Name')); ?>:</b>
	<?php echo CHtml::encode($data->Member_Name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Mobile_Number')); ?>:</b>
	<?php echo CHtml::encode($data->Mobile_Number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Is_Registered')); ?>:</b>
	<?php echo CHtml::encode($data->Is_Registered); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Creator_Id')); ?>:</b>
	<?php echo CHtml::encode($data->Creator_Id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Created_Time')); ?>:</b>
	<?php echo CHtml::encode($data->Created_Time); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('Is_Deleted')); ?>:</b>
	<?php echo CHtml::encode($data->Is_Deleted); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Last_Modified')); ?>:</b>
	<?php echo CHtml::encode($data->Last_Modified); ?>
	<br />

	*/ ?>

</div>