<?php
/* @var $this MemberController */
/* @var $model Member */

$this->breadcrumbs=array(
	'Members'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Member', 'url'=>array('index')),
	array('label'=>'Create Member', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#member-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Members</h1>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'member-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'Member_Id',
		'Member_Email',
		'Member_Name',
		'Mobile_Number',
		'Is_Registered',
		'Creator_Id',
		array(
			'name'=>'Created_Time',
			'value'=>'$data->timestampToDate($data->Created_Time)'
		),
		array(
			'name'=>'Last_Modified',
			'value'=>'$data->timestampToDate($data->Last_Modified)'
		),
		/*
		'Created_Time',
		'Is_Deleted',
		'Last_Modified',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
