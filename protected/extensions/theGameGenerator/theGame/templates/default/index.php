<?php
echo '<?php
/**
 * Выводит таблицу всех записей, в меганавороченном гриде...
 * @var $model ' . $this->modelClass . '
 * @var $this Controller
 */
$this->breadcrumbs = array(
	"' . $this->modelClass . '",
);
$this->pageTitle = "' . $this->modelClass . ' - " . Yii::app()->name;

$this->widget("EzvukGrid", array(
	"id" => $grid_id,
	"name" => "' . $this->modelClass . '",
	// Заголовок таблицы
	"fluid" => true,
	"selectableRows" => 1,
	"dataProvider" => $model->search(),
	//Данные для грида
	"filter" => $model,
	// Данные фильтра грида
	"columns" => $columns,
	//Колонки грида
	// Настройки тулбара
	"toolbarOptions" => array(
		"columns_filter" => array(
			"model" => $model,
			"current_columns" => $filter,
		),
	),
	//Фильтр для тулбара/фильтра
	"summaryText" => "' . $this->modelClass . ' {start} &#151; {end} из {count}",
	//Необязательное поле
	"multiselects" => array(
		"some_multiselect_id" => "some_multiselect_model_attribute" //  Тут указывайте идентификаторы мультиселектов со значениями их атрибутов модели, которые вы впилили в супергрид. Ибо надо...
	),
	"datepickers" => array(
		"some_datepicker_id" => "some_datepicker_model_attribute" //  Тут указывайте идентификаторы дейтпикеров со значениями их атрибутов модели, которые вы впилили в супергрид. Ибо надо...
	)

));';