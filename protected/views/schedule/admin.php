<?php
/* @var $this ScheduleController */
/* @var $model Schedule */

$this->breadcrumbs=array(
	'Schedules'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Schedule', 'url'=>array('index')),
	array('label'=>'Create Schedule', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#schedule-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Schedules</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php
// import class Common
include_once(dirname(Yii::app()->baseUrl)."protected/Common/Common.php");
$common = new Common();
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'schedule-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'Schedule_Id',
		'Service_Id',
		array(
			'name'=>'Start_DateTime',
			'value'=>'date(\'Y-m-d H:i:s\',$data->Start_DateTime)'
		),
		array(
			'name'=>'End_DateTime',
			'value'=>'date(\'Y-m-d H:i:s\',$data->End_DateTime)'
		),
		'Description',
		'Creator_Id',
		//'Is_Deleted',
		// array(
			// 'name'=>'Created_Time',
			// 'value'=>'$common->timestampToDate($data->Created_Time)'
		// ),
		// array(
			// 'name'=>'End_DateTime',
			// 'value'=>'$common->timestampToDate(Last_Modified)'
		// ),
		'Created_Time',
		'Last_Modified',
		
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
