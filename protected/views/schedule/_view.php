<?php
/* @var $this ScheduleController */
/* @var $data Schedule */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('Schedule_Id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->Schedule_Id), array('view', 'id'=>$data->Schedule_Id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Service_Id')); ?>:</b>
	<?php echo CHtml::encode($data->Service_Id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Start_DateTime')); ?>:</b>
	<?php echo CHtml::encode($data->Start_DateTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('End_DateTime')); ?>:</b>
	<?php echo CHtml::encode($data->End_DateTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Description')); ?>:</b>
	<?php echo CHtml::encode($data->Description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Creator_Id')); ?>:</b>
	<?php echo CHtml::encode($data->Creator_Id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Is_Deleted')); ?>:</b>
	<?php echo CHtml::encode($data->Is_Deleted); ?>
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