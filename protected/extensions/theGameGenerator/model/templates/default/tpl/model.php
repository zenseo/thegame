<?php

/**
 * Это класс модели для таблицы "tableNamePlaceholder".
 *
 * Ниже описаны доступные поля для таблицы 'tableNamePlaceholder':
 * columnsCommentPlaceholder
 *
 * relationsCommentPlaceholder
 */
class ClassNamePlaceholder extends BaseClassNamePlaceholder
{
	/**
	 * @return string возвращает сроку привязанной к модели таблицы
	 */
	public function tableName()
	{
		return 'tableNamePlaceholder';
	}

	/**
	 * @return array правила валидации для атрибутов модели
	 */
	public function rules()
	{
		// NOTE: вам нужно лишь защитить атрибуты, которые будет вводить пользователь
		// можете удалить лишнее
		return array(
			"ModelRulesPlaceholder",

			// Следующее правило будет использовано в search().
			// @todo Пожалуйста удалите атрибуты, которые не должны экранироваться в поиске
			array(
				'SearchRulesPlaceholder',
				'safe',
				'on' => 'search'
			),
		);
	}

	/**
	 * @return array правила зависимостей
	 */
	public function relations()
	{
		// NOTE: возможно вам нужно настроить эти зависимости.
		// Классы для зависимостей были сгенерированы автоматически! Проверьте их наличие.
		return array(
			"RelationRulesPlaceholder"
		);
	}

	/**
	 * @return array подписи атрибутов (атрибут=>подпись)
	 */
	public function attributeLabels()
	{
		return array(
			"AttributeLabelsPlaceholder"
		);
	}

	/**
	 * @return array дефолтные атрибуты, выводящиеся в гриде
	 */
	public function attributeDefault()
	{
		return array(
			"AttributeDefaultPlaceholder"
		);
	}


	/**
	 * @var array $dates_for_convert - тут перечислены
	 * атрибуты, типа DATE, TIMESTAMP.
	 * Даты будут конвертироваться методами afterFind() и beforeSave()
	 * в классе {@link ActiveRecord}
	 *
	 */
	public $dates_for_convert = array(
		'created',
		'updated',
		'birth_date',
		'passport_date',
		'labor_contract_date',
	);

	/**
	 * @var array $grid_multi_selects - тут перечислены
	 * все атрибуты модели, которые должны иметь фильтр select2
	 * Важно!!! Для каждого из перечисленных атрибутов должны быть
	 * данные в методе getMultiSelectsData($attribute)
	 */
	public $grid_multi_selects = array(
		'gender',
		'role',
		'status',
		'position',
	);

	/**
	 * Метод, который отдает данные для фильтра типа мультиселект
	 * @return array - возвращает массив данных
	 */
	public function getMultiSelectsData($attribute)
	{
		// Если мультиселект не описан - не нужно ничего проверять
		if (!in_array($attribute, $this->grid_multi_selects)) {
			return;
		}
		switch ($attribute) {
			case 'gender':
				$data = Utilities::ManWoman();
				break;
			case 'role':
				$data = CHtml::listData(Yii::app()->authManager->getRoles(), 'name', 'description');
				break;
			case 'status':
				$dictionary = new Dictionary('user_status');
				$data = CHtml::listData($dictionary->findAll(), 'id', 'name');
				break;
			case 'position':
				$dictionary = new Dictionary('user_position');
				$data = CHtml::listData($dictionary->findAll(), 'id', 'name');
				break;
			default:
				$data = false;
				break;
		}

		return $data;
	}


	/**
	 * Пытается найти в базе список моделей, основываясь на атрибутах текущей модели
	 *
	 * Типичное применение:
	 * - Загружите модель данными, которые пришли вам из формы
	 * - Вызовите этот метод, он вернет вам модели, которые нашел в базе
	 * основываясь на конфигурации загруженной модели
	 * - Передайте эту функцию в любой виджет основанный на CGridView
	 *
	 * @return CActiveDataProvider  - поставщик данных, который возвращает модели
	 * основываясь на атрибутах текущей модели
	 */
	public function search()
	{
		$criteria = new CDbCriteria;
"SearchPlaceholder";

		// Автоматическое добавление поиска по датам на основе
		// массива $this->dates_for_convert
		foreach ($this->dates_for_convert as $attribute) {
			$criteria = $this->getSearchDate($criteria, $attribute);
		}

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Возвращает статическую модель для указанного AR класса.
	 * Обратите внимание, что вы должны иметь этот метод во всех ваших CActiveRecord потомках!
	 *
	 * @param string $className имя класса активной записи.
	 * @return ClassNamePlaceholder статический класс модели
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Обрабатывает модель, после того, как нашел
	 */
	public function afterFind()
	{
		parent::afterFind();
		// Какие-нибудь свои преобразования типа
		// $this->whalalla = 'mywhalalla';
	}


	public $alternateConnectPlaceholder;



	/**
	 * Пишет данные для ряда фильтр котороего является
	 * мультиселектом.
	 * @param $row - атрибут модели
	 */
	public function getMultiSelectsRowValue($row)
	{
		$row_data = $this->getMultiSelectsData($row);
		if (!empty($row_data) && count($row_data) && isset($row_data[$this->$row])) {
			return $row_data[$this->$row];
		}
	}

	/**
	 * Ссылка на просмотр карточки
	 * @param $attribute - атрибут модели, который превращаем в ссылку
	 * @return string - html-разметка ссылки, которая будет вставлена как значение атрибута в гриде
	 */
	public function getMoreLink($attribute){
		return '<a target="_blank" href="/'.strtolower(__CLASS__).'/'. $this->id .'">'. $this->$attribute .'</a>';
	}

	/**
	 * Колонки для грида
	 * @param $columns
	 * @return array
	 */
	public function columnsGrid($columns)
	{
		foreach ($columns as $row) {
			switch ($row) {
				case 'id':
					$result[] = array(
						'name' => 'id',
						'type' => 'raw',
						'value' =>'$data->getMoreLink("'.$row.'")',
						'htmlOptions' => array(
							'class' => 'grid_id_column'
						)
					);
					break;
				default:
					// Если элемент присутствует в списке дат - делаем ему дейтренж
					if (in_array($row, $this->dates_for_convert)) {
						$result[] = array(
							'name' => $row,
							'filter' => $this->getPeriodFilter($row),
							'htmlOptions' => array(
								'class' => 'grid_middle_column'
							)
						);
					}
					// Если элемент присутствует в списке мультиселектов делаем ему мультиселект
					else if (in_array($row, $this->grid_multi_selects)) {
						$result[] = array(
							'name' => $row,
							'filter' => $this->getMultiSelectFilter($row),
							'value' => '$data->getMultiSelectsRowValue("' . $row . '");',
							'htmlOptions' => array(
								'class' => 'grid_middle_column'
							)
						);
					}
					else {
						$result[] = array(
							'name' => $row,
							'htmlOptions' => array(
								'class' => 'grid_middle_column'
							)
						);
					}
					break;
			}
		}

		return $result;
	}

	/**
	 * @return array - правила для {@link GridColumnsFilter}
	 */
	public function getColumnsRules()
	{
		return array(
			'required' => array(
				'id'
			)
		);
	}

	/**
	 * @return array Колонки фильтра
	 */
	public function getFilter()
	{
		return array(
			'main' => array(
				'label' => 'Основные',
				'childs' => array(
					"AttributeDefaultPlaceholder"
				)
			),
			'other' => array(
				'label' => 'Прочие',
				'childs' => array()
			)
		);
	}

}
