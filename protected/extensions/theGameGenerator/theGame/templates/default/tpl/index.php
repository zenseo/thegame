<?php
/**
 * Выводит список всех записей
 *
 * @var $model ModelClass
 * @var $this ControllerClass
 */

$this->breadcrumbs = array(
	"ModelClass",
);
$this->pageTitle = Yii::app()->name . " - ModelClass";

$this->renderPartial('_super_json_grid', array(
	'model' => $model,
	'grid_id' => $grid_id,
	'filter' => $filter,
	'columns' => $columns
));
$this->renderPartial('_create_modal', array(
	'model' => $model,
));