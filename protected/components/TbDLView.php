<?php

/**
 * Class TbDLView - виджет, который автоматически генерирует список (тег - <dl>)
 * из атрибутов модели
<dl class="dl-horizontal">
<dt>Название атрибута</dt>
<dd class="lead">Данные атрибута</dd>
</dl>
 *
 * @author romi45
 */
class TbDLView extends CWidget
{

	public $model; // модель, из которой будем генерировать список
	public $attributes = array(); // массив с названиями атрибутов или подмассивами ключ=>значение для формирования списка
	/*
	 * 	Пример массива атрибутов
		array(
			'created',
			'modified',
			'production_date',
			array( 'value' => implode(", ", $categories), 'label' => 'Категории ролика' ),
			array( 'value' => $model->music_author[0]->name, 'label' => 'Автор музыки' ),
			array( 'value' => 'Любой текст', 'label' => 'Любой ярлык' ),
		)
	*/
	public $options = array(); // Массив с настройками
	/*
	 *  Доступные настройки:
	 * 		$title - заголовок списка
	 * 		$class - строка (класс для элемента <dl></dl>
	 * 		$dt_class - строка (класс для элемента <dt></dt>
	 * 		$dd_class - строка (класс для элемента <dd></dd>
	 */

	// Запуск виджета
	public function init()
	{
		parent::init();
	}

	public function run()
	{
		if (!isset($this->model) || !isset($this->attributes)) {
			throw new CException(Yii::t('zii', 'Вы забыли передать параметр "model" или "attributes"'));
		}
		$this->renderList();
	}

	/**
	 * Непостредственно рисует список на основе данных класса
	 */
	private function renderList()
	{
		// Устанавливаем значения по-умолчанию
		$title = (isset($this->options['title'])) ? '<legend><h2>' . $this->options['title'] . '</h2></legend>' : '';
		$class = (isset($this->options['class'])) ? $this->options['class'] : 'dl-horizontal';
		$dt_class = (isset($this->options['dt_class'])) ? $this->options['dt_class'] : '';
		$dd_class = (isset($this->options['dd_class'])) ? $this->options['dd_class'] : '';

		// Определяем модель
		$model = $this->model;

		// Начинаем рисовать...
		$list = $title . '<dl class="' . $class . '">';
		foreach ($this->attributes as $attrubute) {
			$key = is_array($attrubute) ? $attrubute['label'] : $model->getAttributeLabel($attrubute);
			$value = is_array($attrubute) ? $attrubute['value'] : $model->$attrubute;
			$value = $value ? $value : "—";
			$list .= '<dt class="' . $dt_class . '">' . $key . '</dt><dd class="' . $dd_class . '" class="lead">' . $value . '</dd>';
		}
		// Последний штрих... и...
		$list .= '</dl>';

		// Фанфары - отдаем всю эту красоту в браузер!!!
		echo $list;
	}
}