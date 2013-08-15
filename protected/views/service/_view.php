<?php
/* @var $this ServiceController */
/* @var $data Service */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('Service_Id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->Service_Id), array('view', 'id'=>$data->Service_Id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Service_Name')); ?>:</b>
	<?php echo CHtml::encode($data->Service_Name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Description')); ?>:</b>
	<?php echo CHtml::encode($data->Description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SRepeat')); ?>:</b>
	<?php echo CHtml::encode($data->SRepeat); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Start_Datetime')); ?>:</b>
	<?php echo CHtml::encode(date('Y-m-d H:i:s',$data->Start_Datetime)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('End_Datetime')); ?>:</b>
	<?php echo CHtml::encode(date('Y-m-d H:i:s',$data->End_Datetime)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('UTC_Off')); ?>:</b>
	<?php echo CHtml::encode($data->UTC_Off); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('Alert')); ?>:</b>
	<?php echo CHtml::encode($data->Alert); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Creator_Id')); ?>:</b>
	<?php echo CHtml::encode($data->Creator_Id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Is_Deleted')); ?>:</b>
	<?php echo CHtml::encode($data->Is_Deleted); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Created_Time')); ?>:</b>
	<?php echo CHtml::encode($data->Created_Time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Last_Modified')); ?>:</b>
	<?php echo CHtml::encode($data->Last_Modified); ?>
	<br />

	*/ ?>

</div>