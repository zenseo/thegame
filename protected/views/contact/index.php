<?php
/**
 * Выводит список всех записей
 *
 * @var $model Contact
 * @var $this ContactController
 */

$this->breadcrumbs = array(
	"Contact",
);
$this->pageTitle = Yii::app()->name . " - Contact";

$this->renderPartial('grids/_super_json_grid', array(
	'model' => $model,
	'grid_id' => $grid_id,
	'grid_data_provider' => $grid_data_provider,
	'filter' => $filter,
	'columns' => $columns
));
$this->renderPartial('_create_modal', array(
	'model' => $model,
));