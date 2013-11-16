<?php
$this->breadcrumbs = array(
	"Пользователи",
);
$this->pageTitle = Yii::app()->name . "- пользователи";

$this->renderPartial('_super_grid', array(
	'model' => $model,
	'grid_id' => $grid_id,
	'filter' => $filter,
	'columns' => $columns
));
$this->renderPartial('modals/_create_modal', array(
	'model' => $model,
));

