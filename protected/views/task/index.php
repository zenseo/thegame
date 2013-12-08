<?php
/**
 * Выводит список всех записей
 *
 * @var $model Task
 * @var $this TaskController
 */

$this->breadcrumbs = array(
	"Task",
);
$this->pageTitle = Yii::app()->name . " - Task";

$this->renderPartial('_super_json_grid', array(
	'model' => $model,
	'grid_id' => $grid_id,
	'grid_data_provider' => $grid_data_provider,
	'filter' => $filter,
	'columns' => $columns
));
$this->renderPartial('_create_modal', array(
	'model' => $model,
));