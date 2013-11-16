<?php
/**
 * Супер JSON Грид!!!
 *
 * @category Widget
 **/

Yii::import('ext.bootstrap.widgets.TbJsonGridView');

class SuperJsonGrid extends TbJsonGridView
{
	/**
	 * @var array Набор по сколько можно отображать
	 */
	public $pageSizes = array(
		10,
		20,
		50,
		100,
		200
	);

	/**
	 * @var string Переменная в сессии, где записано по сколько отображать
	 */
	public $pageSizeVarName = 'pageSize';

	/**
	 * @var bool Выбираем отображать кнопки "Отображать по..." или нет
	 */
	protected $_pageSizesEnabled = true;


	/**
	 * @var array - $toolbarButtons сюда передаются кнопки. Тупо кнопки =)
	 *
	=== Кнопка фильтра по колонкам ===
	'columns_filter' => array(
	'model' => $model, - сюда передаете модель, которую нужно фильтровать
	'current_columns' => $columns_filter, - одномерный массив текущих колонок таблицы
	),
	'buttons' => array(

	)
	 */
	public $toolbarButtons = array();

	/**
	 * @var string
	 */
	public $template = '{template}';

	/**
	 * @var
	 */
	public $ajaxUrl;

	/**
	 * @var string
	 */
	public $type = "striped hover";


	/**
	 * @var string - параметр, включающий колонку с чекбоксами
	 * получить массив идентификаторов чекнутых элементов можно
	 * при помощи функции getCheckedJsonGridRows()
	 */
	public $checkboxColumn = array();

	/**
	 * @var string
	 */
	public $summaryText = 'Элементы {start} &#151; {end} из {count}';

	/**
	 * Инициализация виджета
	 */
	public function init()
	{
		$this->initPageSizes();
		$this->template = "
<div class='index-grid-wrapper'>
	<div class='container-fluid'>
	<div class='row-fluid'>
		<div class='span9 index-grid-element'>{toolbar}</div><div class='span3 muted index-grid-element'>{summary}</div>
	</div>
	{items}
	<div class='row-fluid'>
		<div class='span8'>{pager}</div><div class='span4 index-grid-element'>{pageSizes}</div>
	</div></div></div><script>$(function () {	setGridFilterCookies('{$this->id}');});</script>{checkBoxColumnScripts}";

		if (!isset($this->checkboxColumn['class']) || empty($this->checkboxColumn['class'])) {
			$this->checkboxColumn['class'] = 'grid_checkbox_column';
		}
		if (!isset($this->checkboxColumn['main_checkbox_id']) || empty($this->checkboxColumn['main_checkbox_id'])) {
			$this->checkboxColumn['main_checkbox_id'] = 'grid_main_checkbox';
		}

		if (count($this->checkboxColumn)) {
			$column = array(
				'type' => 'raw',
				'header' => CHtml::checkBox('grid_main_checkbox', false, array(
					'class' => $this->checkboxColumn['class'] . '_main_checkbox',
					'id' => $this->checkboxColumn['main_checkbox_id']
				)),
				'filter' => false,
				'value' => '$data->getCheckboxForGrid()',
				'htmlOptions' => array(
					'class' => $this->checkboxColumn['class']
				)
			);
			array_unshift($this->columns, $column);
		}

		parent::init();
	}

	/**
	 * Выводит тулбар
	 * Рисует виджет фильтра по колонкам и подключает фильтр по колонкам к таблице.
	 */
	public function renderToolbar()
	{
		$options = $this->toolbarButtons;
		$result = array();

		// Если запрашивается кнопка фильтра колонок
		if (isset($options['columns_filter'])) {
			$filter = $options['columns_filter'];
			if (!isset($filter['model'])) {
				throw new CException('Вы забыли передать параметр "model" для кнопки фильтра колонок');
			}
			if (!isset($filter['current_columns'])) {
				throw new CException('Вы забыли передать параметр "current_columns" для кнопки фильтра колонок');
			}
			$result[] = $this->widget('GridColumnsFilter', array(
				'model' => $filter['model'],
				'current_columns' => $filter['current_columns'],
				'grid_id' => $this->id,
			), true);
		}
		// Если запрашивается кнопка сброса фильтра таблицы
		if (isset($options['flush_grid_filter'])) {
			$result[] = $this->widget('bootstrap.widgets.TbButton', array(
				'icon' => 'refresh',
				'label' => 'Сбросить фильтр таблицы',
				'htmlOptions' => array(
					'onclick' => 'flushFilterData("' . $this->id . '")'
				)
			), true);
		}
		// Если пришли доп. кнопки с действиями, то просто их выведем раньше фильтра
		if (isset($options['buttons']) && count($options['buttons'])) {
			foreach ($options['buttons'] as $button) {
				if ($button) {
					$result[] = $button;
				}
			}
		}

		echo count($result) ? implode('&nbsp;', $result) : "";
	}

	/**
	 * Sets "pageSize" parameter at instance of CPagination which belongs to data provider.
	 */
	protected function initPageSizes()
	{
		if (isset($_COOKIE[$this->id . '_pageSize'])) {
			$pageSize = $_COOKIE[$this->id . '_pageSize'];
		}
		else {
			$pageSize = Yii::app()->params['defaultPageSize'];
		}

		$pagination = $this->dataProvider->getPagination();
		if (!$this->enablePagination || $pagination === false
		) {
			$this->_pageSizesEnabled = false;
		}
		else {
			$this->_pageSizesEnabled = true;

			// Web-user specifies desired page size.
			if (($pageSizeFromRequest = Yii::app()->getRequest()->getParam('pageSize')) !== null) {
				$pageSizeFromRequest = (int)$pageSizeFromRequest;
				// Check whether given page size is valid or use default value
				if (in_array($pageSizeFromRequest, $this->pageSizes)) {
					$pagination->pageSize = $pageSizeFromRequest;
				}
			}
			// Check for value at session or use default value
			elseif (isset($pageSize)) {
				$pagination->pageSize = $pageSize;
			}
		}
	}

	/**
	 * Рисует кнопки размеров страниц
	 */
	public function renderPageSizes()
	{
		if (!$this->_pageSizesEnabled) {
			return;
		}
		$buttons = array();
		$currentPageSize = $this->dataProvider->getPagination()->pageSize;
		/* Перебор переключателей: */
		foreach ($this->pageSizes as $pageSize) {
			$buttons[] = array(
				'label' => $pageSize,
				'active' => $pageSize == $currentPageSize,
				'htmlOptions' => array(
					'class' => 'pageSize',
					'rel' => $pageSize,
				),
				'url' => '#',
			);
		}
		/* Отрисовываем переключатели PageSize'a: */
		$this->widget('bootstrap.widgets.TbButtonGroup', array(
			'size' => 'small',
			'type' => 'action',
			'toggle' => 'radio',
			'htmlOptions' => array(
				'data-id' => $this->id,
				'class' => 'pull-right',
			),
			'buttons' => $buttons,
		));
	}

	/**
	 * Рисует скрипт предназначенный для работы с
	 * колонкой чекбоксов.
	 */
	public function renderCheckBoxColumnScripts()
	{
		echo "<script type='text/javascript'>
		/**
		* Возвращает идентификаторы элементов таблицы в виде одномерного массива
		* @param grid_id - идентификатор таблицы
		* @returns false || array
		*/
		function getCheckedJsonGridRows(grid_id){
			var checkboxes = $('#'+grid_id+' td :checked');
			var ids = [];
			checkboxes.each(function(){
				ids.push(parseInt($(this).val()));
			});
			if(ids.length){
				return ids;
			}else{
				return false;
			}
		}
		$(function(){
			/**
			 * Работа с чекбоксами
			 */
			$('body').on('change', '#{$this->checkboxColumn["main_checkbox_id"]}', function () {
				var checkboxes = $('#{$this->id} td.{$this->checkboxColumn["class"]}>:checkbox');
				if($(this).prop('checked')){
					checkboxes.prop('checked', true)
				}else{
					checkboxes.prop('checked', false)
				}
			});

			/**
			 * Появление функций при нажатии чекбокса
			 */
			setInterval(function(){
				var checkboxes = $('#{$this->id} td.{$this->checkboxColumn["class"]}>:checked');
				if(checkboxes.length){
					$('#{$this->id} .show-on-checked').show();
				}else{
					$('#{$this->id} .show-on-checked').hide();
				}
			}, 100)
		});
		</script>";
	}

}
