<?php
/* @var $this ServiceController */
/* @var $model Service */

$this->breadcrumbs=array(
	'Services'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Service', 'url'=>array('index')),
	array('label'=>'Create Service', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#service-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Services</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'service-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'Service_Id',
		'Service_Name',
		'Description',
		'SRepeat',
		array(
			'name'=>'Start_Datetime',
			'value'=>'$data->timestampToDate($data->Start_Datetime)'
		),
		array(
			'name'=>'End_Datetime',
			'value'=>'$data->timestampToDate($data->End_Datetime)'
		),
		array(
			'name'=>'Created_Time',
			'value'=>'$data->timestampToDate($data->Created_Time)'
		),
		array(
			'name'=>'Last_Modified',
			'value'=>'$data->timestampToDate($data->Last_Modified)'
		),
		/*
		'UTC_Off',
		'Alert',
		'Creator_Id',
		'Is_Deleted',
		'Created_Time',
		'Last_Modified',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

<script type="text/javascript">
    var d = new Date();
    var localZone = d.getTimezoneOffset()/60;
	// alert(localZone);
</script>
