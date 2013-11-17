<?php
/**
 * Выводит таблицу всех записей,
 * в меганавороченном гриде...
 * @var $model Customer
 * @var $this CustomerController
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
				'label' => 'Добавить клиента',
				'type' => 'primary',
				'htmlOptions' => array(
					'onclick' => 'showModal("add_customer_modal");return false;'
				),
			), true),
			$this->widget("bootstrap.widgets.TbButton", array(
				"label" => "Удалить",
				"type" => "danger",
				"htmlOptions" => array(
					"class" => "show-on-checked",
					"onclick" => "deleteRecords('/customer/delete','{$grid_id}');return false;"
				)
			), true),

		)
	),
	"summaryText" => "Customer {start} &#151; {end} из {count}",
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