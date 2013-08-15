<?php
/* @var $this ServiceController */
/* @var $model Service */

$this->breadcrumbs=array(
	'Services'=>array('index'),
	$model->Service_Id,
);

$this->menu=array(
	array('label'=>'List Service', 'url'=>array('index')),
	array('label'=>'Create Service', 'url'=>array('create')),
	array('label'=>'Update Service', 'url'=>array('update', 'id'=>$model->Service_Id)),
	array('label'=>'Delete Service', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->Service_Id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Create Schedule', 'url'=>array('Schedule/create','serviceid'=>$model->Service_Id)),
	array('label'=>'Manage Service', 'url'=>array('admin')),
);
?>

<h1>View Service #<?php echo $model->Service_Id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'Service_Id',
		'Service_Name',
		'Description',
		'SRepeat',
		array(
			'name'=>'Start_Datetime',
			'value'=>date('Y-m-d H:i:s',$model->Start_Datetime)
		),
		array(
			'name'=>'End_Datetime',
			'value'=>date('Y-m-d H:i:s',$model->End_Datetime)
		),
		'UTC_Off',
		'Alert',
		'Creator_Id',
		'Is_Deleted',
		'Created_Time',
		'Last_Modified',
	),
)); ?>

