<?php
/**
 * Выводит таблицу всех записей контактов в карточке клиента
 * @var $model Contact
 * @var $this ContactController
 */
$this->widget("SuperJsonGrid", array(
	"id" => $grid_id,
	"ajaxUrl" => '/customer/contactsGrid/' . $customer_id,
	"dataProvider" => $grid_data_provider,
	"enablePagination" => false,
	"toolbarButtons" => array(
		"buttons" => array(
			$this->widget("bootstrap.widgets.TbButton", array(
				'label' => 'Добавить контакт',
				'type' => 'primary',
				'htmlOptions' => array(
					'onclick' => 'showModal("add_contact_modal");return false;'
				),
			), true),
			$this->widget("bootstrap.widgets.TbButton", array(
				"label" => "Удалить",
				"type" => "danger",
				"htmlOptions" => array(
					"class" => "show-on-checked",
					"onclick" => "deleteRecords('/contact/delete','{$grid_id}');return false;"
				)
			), true),

		)
	),
	"summaryText" => "Контакты {start} &#151; {end} из {count}",
	"checkboxColumn" => array(
		"class" => "grid_checkbox_column",
		"main_checkbox_id" => "client_contacts_table_main_checkbox",
	),
	"afterAjaxUpdate" => "function(){}",
	"columns" => $columns
));