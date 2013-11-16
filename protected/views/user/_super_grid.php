<?php
/**
 * Выводит таблицу всех записей, в меганавороченном гриде...
 * @var $model User
 * @var $this Controller
 */
$this->widget("SuperJsonGrid", array(
	"id" => $grid_id,
	"dataProvider" => $model->search(),
	"filter" => $model,
	"toolbarButtons" => array(
		"columns_filter" => array(
			"model" => $model,
			"current_columns" => $filter,
		),
		"flush_grid_filter" => true,
		"buttons" => array(
			$this->widget("bootstrap.widgets.TbButton", array(
				'label' => 'Добавить пользователя',
				'type' => 'primary',
				'htmlOptions' => array(
					'onclick' => 'showModal("add_user_modal")'
				),
			), true),
			$this->widget("bootstrap.widgets.TbButton", array(
				"label" => "Удалить",
				"type" => "danger",
				"htmlOptions" => array(
					"class" => "show-on-checked",
					"onclick" => "deleteRecords('/user/delete','{$grid_id}')"
				)
			), true),
		)
	),
	"summaryText" => "Пользователи {start} &#151; {end} из {count}",
	"checkboxColumn" => array(
		"class" => "grid_checkbox_column",
		"main_checkbox_id" => "my_mega_main_table_checkbox",
	),
	//	"type" => "striped bordered condensed",
	"afterAjaxUpdate" => "function(){}",
	"columns" => $columns,
	"htmlOptions" => array(
		"class" => "index-grid-view grid-view"
	)
));