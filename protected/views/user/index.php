<?php
/**
 * Выводит таблицу всех записей, в меганавороченном гриде...
 * @var $model User
 * @var $this Controller
 */
$this->breadcrumbs = array(
	"Пользователи",
);
$this->pageTitle = Yii::app()->name . "- пользователи";
$this->widget('SuperJsonGrid', array(
	"id" => $grid_id,
	'dataProvider' => $model->search(),
	'filter' => $model,
	"toolbarButtons" => array(
		"columns_filter" => array(
			"model" => $model,
			"current_columns" => $filter,
		),
		"flush_grid_filter" => true,
		'buttons' => array(
			$this->widget('bootstrap.widgets.TbButton', array(
					'label' => 'Пример групповой кнопки',
					'type' => 'primary',
					'htmlOptions' => array(
						'class' => 'show-on-checked'
					)
				), true),
		)
	),
	"summaryText" => "Пользователи {start} &#151; {end} из {count}",
	"checkboxColumn" => array(
		'class' => 'grid_checkbox_column',
		'main_checkbox_id' => 'my_mega_main_table_checkbox',
	),
	//	'type' => 'striped bordered condensed',
	'afterAjaxUpdate' => 'function(){console.log("Обновил грид!")}',
	'columns' => $columns,
	'htmlOptions' => array(
		'class' => 'index-grid-view grid-view'
	)
));