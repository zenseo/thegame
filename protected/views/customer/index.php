<?php
/**
 * Выводит список всех записей
 *
 * @var $model Customer
 * @var $this CustomerController
 */

$this->breadcrumbs = array(
	"Customer",
);
$this->pageTitle = Yii::app()->name . " - Customer";

$this->renderPartial('_super_json_grid', array(
	'model' => $model,
	'grid_id' => $grid_id,
	'filter' => $filter,
	'columns' => $columns
));
$this->renderPartial('_create_modal', array(
	'model' => $model,
));