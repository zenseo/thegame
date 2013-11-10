<?php

/**
 * Class GridColumnsFilter - виджет, который генерирует интерфейс для управления колонками гида
 *
 * @author romi45
 */
class GridColumnsFilter extends CWidget
{
	/**
	 * @var модель, из которой будем генерировать список колонок
	 */
	public $model;

	/**
	 * @var array массив с текущими колонками грида (всегда одномерный)
	 */
	public $current_columns = array();

	/**
	 * @var array массив с колонками таблицы по-умолчанию
	 */
	public $default_columns = array();

	/**
	 * @var array массив со всеми колонками грида (может быть многомерным)
	 */
	public $all_columns = array();

	/**
	 * @var array массив со всеми настройками по колонкам грида (например можно задать обязательные колонки)
	 */
	public $columns_rules = array();
	/**
	 * @var string - идентификатор грида
	 */
	public $grid_id = '';

	/*
	 * 	Пример массива всех колонок
		array(
			'id',
			'last_task_date',
			'information' => array(
				'label' => 'Информация',
				'childs' => array(
					'name',
					'category_id',
				)
			),
			'sales' => array(
				'label' => 'Продажи',
				'childs' => array(
					'user_id',
					'segment',
				)
			),
		);
	*/

	public $options = array(); // Массив с настройками
	/*
	 *  Доступные настройки:
	 * 		$button_label - текст кнопки "Сохранить"
	 * 		$modal_button_label - текст кнопки "Сохранить"
	 * 		$callback - javascript-функция, которая будет исполняться после всех действий виджета
	 */

	// Запуск виджета
	public function init()
	{
		parent::init();

		// Get all possible columns of model (for that we must specify special functions getFilter())
		// getFilter() must return an multilevel array with model attributes, that must be shown in grid
		if (!$this->model->filter) {
			throw new CException('В переданной вами модели не определена функция getFilter()');
		}
		$this->all_columns = $this->model->filter;

		// Get all possible columns of model (for that we must specify special functions getFilter())
		// getFilter() must return an multilevel array with model attributes, that must be shown in grid
		if (!$this->model->attributeDefault()) {
			throw new CException('В переданной вами модели не определена функция attributeDefault()');
		}
		else {
			$this->default_columns = $this->model->attributeDefault();
		}


		// Temporary array for array_multisort
		$multisort_array = array();

		// Count children of every element and fill up $multisort_array
		foreach ($this->all_columns as $array) {
			$multisort_array[] = isset($array['childs']) ? count($array['childs']) : 0;
		}

		// If we have many groups of columns make SORT_DESC
		// coz it's must pretty than ASC in this case
		if (count($this->all_columns) > 3) {
			array_multisort($multisort_array, SORT_DESC, $this->all_columns);
		}
		else {
			array_multisort($multisort_array, SORT_ASC, $this->all_columns);
		}

		// If we have list of main columns, then it must be always on top
		if (isset($this->all_columns['main'])) {
			$tmp = array('main' => $this->all_columns['main']);
			unset($this->all_columns['main']);
			$this->all_columns = $tmp + $this->all_columns;
		}
		// Get all rules of columns (it is not required)
		if (method_exists($this->model, 'getColumnsRules')) {
			$this->columns_rules = $this->model->columnsRules;
		}
	}

	public function run()
	{
		if (!isset($this->model) || !isset($this->current_columns)) {
			throw new CException('Вы забыли передать параметр "model" или "current_columns"');
		}
		$list = $this->makeList($this->all_columns, true);
		$draggable = $this->makeDraggableList($this->current_columns);

		if (isset($this->options) && isset($this->options['modal_button_label'])) {
			$button_text = $this->options['modal_button_label'];
		}
		else {
			$button_text = 'Настройка колонок таблицы';
		}
		$this->widget('bootstrap.widgets.TbButton', array(
			'label' => $button_text,
			'icon' => 'filter',
			'htmlOptions' => array('onclick' => '$("#grid_columns_settings_modal").modal("show")'),
		));

		$this->beginWidget('bootstrap.widgets.TbModal', array(
			'id' => 'grid_columns_settings_modal',
			'htmlOptions' => array('class' => 'wide_modal')
		));
		?>

		<div class="modal-header">
			<a class="close" onclick="closeModal(this)">&times;</a>

			<h3>Настройка колонок таблицы</h3>
		</div>

		<div class="modal-body" style="overflow-x:hidden;">
			<p class="muted">Выберите нужные вам колонки таблицы, затем нажмите кнопку "Применить" и дождитесь
				обновления таблицы.</p>

			<div class="row-fluid">
				<a href="#" class="dashed" id="select_all_columns_filter">Выбрать все</a>&nbsp;&nbsp;&nbsp;
				<a href="#" class="muted dashed" id="deselect_all_columns_filter">Убрать все</a>&nbsp;&nbsp;&nbsp;
				<a href="#" class="muted dashed" id="flush_columns_filter">Значения по-умолчанию</a>
			</div>
			<!--			<div class="container-fluid">-->
			<div class="row-fluid">
				<div class="span8">
					<?php echo $list; ?>
				</div>
				<div class="span4 grid_columns_draggable_list_wrapper">
					<h4>Порядок колонок</h4>

					<p class="muted">
						<small>Передвигайте названия колонок вверх-вниз. Таким образом вы можете менять порядок
							отображения колонок.
						</small>
					</p>
					<ul id="grid_columns_draggable_list" class="grid_columns_draggable_list">
						<?php echo $draggable; ?>
					</ul>
					<p class="muted">
						<small>Так же вы можете удалять ненужные колонки из списка, нажимая
							на &laquo;крестик&raquo;</small>
					</p>
				</div>
			</div>
		</div>

		<div class="modal-footer">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'type' => 'primary',
				'label' => 'Применить',
				'icon' => 'white ok',
				'htmlOptions' => array('id' => 'write_filter_to_cookies'),
			)); ?>
		</div>

		<?php $this->endWidget();
		$this->renderScripts();
	}

	/**
	 * Непостредственно рисует список на основе данных класса
	 */
	private function makeList($array, $first = false)
	{
		if ($first === true) {
			$return = '<ul id="grid_columns_list" class="grid_columns_list">';
		}
		else {
			$return = '<ul>';
		}
		foreach ($array as $item) {
			$checked = '';
			$disabled = '';
			$required = '';

			// If current item in current_columns array
			if (in_array($item, $this->current_columns)) {
				// Make it checked...
				$checked = 'checked';
			}

			// If current item in current_columns array
			if (in_array($item, $this->default_columns)) {
				// Make it checked...
				$default = 'data="default"';
			}
			else {
				$default = '';
			}


			// If we have specified rules...
			if (!empty($this->columns_rules)) {
				// If we have required rules - make all of required elements disabled and checked
				if (isset($this->columns_rules['required']) && is_array($this->columns_rules['required'])) {
					if (in_array($item, $this->columns_rules['required'])) {
						$disabled = 'disabled';
						$checked = 'checked';
						$required = 'required';
					}
				}
			}


			if (isset($item['childs']) && !is_string($item['childs'])) {
				$return .= '
					<li class="grid_columns_filter_list_item">
						<h4>
							' . $item['label'] . '
						</h4>
						' . (is_array($item['childs']) ? $this->makeList($item['childs']) : '') . '
					</li>';
			}
			else {
				$return .= '
					<li>
						<label>
							<input type="checkbox" name="' . $item . '"  ' . $checked . ' ' . $disabled . ' ' . $default . ' ' . $required . '/> ' . $this->model->getAttributeLabel($item) . '
						</label>
					</li>';
			}
		}
		$return .= '</ul>';

		return $return;
	}

	/**
	 * Рисует список колонок для их сортировки
	 */
	private function makeDraggableList($array)
	{
		$return = '';
		foreach ($array as $item) {
			$destruction_button = '<a class="close" onclick="removeColumnFromDraggable(this)">&times;</a>';
			// If we have specified rules...
			if (!empty($this->columns_rules)) {
				// If we have required rules - make all of required elements disabled and checked
				if (isset($this->columns_rules['required']) && is_array($this->columns_rules['required'])) {
					if (in_array($item, $this->columns_rules['required'])) {
						$destruction_button = '';
					}
				}
			}
			$return .= '
			<li data="' . $item . '">
				' . $this->model->getAttributeLabel($item) . ' ' . $destruction_button . '
			</li>';
		}

		return $return;
	}


	/**
	 * Непостредственно рисует список на основе данных класса
	 */
	private function renderScripts()
	{
		?>
		<script>

			function removeColumnFromDraggable(close_button) {
				var model_attribute = $(close_button).parent().attr('data');
				$('#grid_columns_list input[name="' + model_attribute + '"]').prop('checked', false);
				$(close_button).parent().remove();
			}
			$(function () {
				var grid_id = "<?php echo $this->grid_id  ?>";

				$('#grid_columns_list input:checkbox').on('change', function () {
					// И делаем с ними что хотим
					if ($(this).prop('checked')) {
						$('#grid_columns_draggable_list').append('<li data="' + $(this).attr('name') + '">' + $(this).parent().text() + '<a class="close" onclick="removeColumnFromDraggable(this)">&times;</a></li>');
					}
					else {
						$('#grid_columns_draggable_list').find('li[data="' + $(this).attr('name') + '"]').remove();
					}
				});

				// Список можно сортировать...
				$('#grid_columns_draggable_list').sortable();

				/**
				 * По нажатию кнопки "Применить" все отмеченные чекбоксы пишутся в кукис, а грид перезагружается
				 */
				$('#write_filter_to_cookies').on('click', function () {
					var columns = [];
					var cookie_id = grid_id + "_columns";
					$('#grid_columns_draggable_list li').each(function () {
						columns.push($(this).attr('data'));
					});
					columns.join();
					$.cookie(cookie_id, columns, { expires: 365, path: '/' });
					window.location.reload();
					$('#grid_columns_settings_modal').modal('hide');
				});

				/**
				 * По нажатию кнопки "Cбросить фильтр"...
				 */
				$('#flush_columns_filter').on('click', function () {
					$('#grid_columns_draggable_list').html('');
					$('#grid_columns_list input:checkbox').each(function () {
						if ($(this).attr('data') == 'default') {
							var destruction_button = '<a class="close" onclick="removeColumnFromDraggable(this)">&times;</a>'
							if ($(this).attr('required')) {
								destruction_button = '';
							}
							$('#grid_columns_draggable_list').append('<li data="' + $(this).attr('name') + '">' + $(this).parent().text() + ' ' + destruction_button + '</li>');
							$(this).prop('checked', true);
						}
						else {
							$(this).prop('checked', false);
						}
					});
				});
				/**
				 * По нажатию кнопки "Выбрать все" - чекаем все чекбоксы
				 */
				$('#select_all_columns_filter').on('click', function () {
					$('#grid_columns_draggable_list').html('');
					$('#grid_columns_list input:checkbox').each(function () {
						var destruction_button = '<a class="close" onclick="removeColumnFromDraggable(this)">&times;</a>'
						if ($(this).attr('required')) {
							destruction_button = '';
						}
						$('#grid_columns_draggable_list').append('<li data="' + $(this).attr('name') + '">' + $(this).parent().text() + ' ' + destruction_button + '</li>');
						$(this).prop('checked', true);
					});
				});

				/**
				 * По нажатию кнопки "Убрать все" - чекаем все чекбоксы
				 */
				$('#deselect_all_columns_filter').on('click', function () {
					$('#grid_columns_draggable_list').html('');
					$('#grid_columns_list input:checkbox').each(function () {
						var destruction_button = '<a class="close" onclick="removeColumnFromDraggable(this)">&times;</a>'
						if ($(this).attr('required')) {
							$(this).prop('checked', true);
							destruction_button = '';
							$('#grid_columns_draggable_list').append('<li data="' + $(this).attr('name') + '">' + $(this).parent().text() + ' ' + destruction_button + '</li>');
						}
						else {
							$(this).prop('checked', false);
						}
					});
				});

			});
		</script>
	<?php
	}
}