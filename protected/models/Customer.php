<?php

/**
 * Это класс модели для таблицы "customer".
 *
 * Ниже описаны доступные поля для таблицы 'customer':
 *
 * @property integer $id Идентификатор клиента
 * @property string $name Название
 * @property string $phone Телефон
 * @property string $email Электронная почта
 * @property string $address Адрес
 * @property string $gis_id Идентификатор 2gis
 * @property string $created Создан
 * @property string $updated Последнее обновление
 * @property integer $responsible Ответсвенный
 * @property string $note Коментарий
 * @property integer $in_work В работе
 * @property integer $removed Удален
 * @property integer $sales_status Статус продаж
 * @property integer $creator Кто добавил
 * @property integer $updater Кто последний раз обновил
 *
 *
 * Ниже описаны доступные для модели зависимости:
 * @property LogCustomer[] $logCustomers
 * @property RelationCustomerContact[] $relationCustomerContacts
 * @property Requisites[] $requisites
 * @property Task[] $tasks
 */
class Customer extends ActiveRecord
{

	/**
	 * @var array Конфигурационный массив правил для RBAC
	 * TODO Отредактируйте коментарии к правилам, для лучшего понимания
	 * Например на пишите - ... =>  'Просмотр карточки пользователя'
	 * вместо ... => 'Просмотр карточки'
	 */
	public static $rbac_config = array(
		'viewCustomer' => 'Просмотр карточки',
		'indexCustomer' => 'Просмотр списка',
		'createCustomer' => 'Создание',
		'updateCustomer' => 'Обновление',
		'deleteCustomer' => 'Удаление',
	);


	/**
	 * @return string возвращает сроку привязанной к модели таблицы
	 */
	public function tableName()
	{
		return 'customer';
	}

	/**
	 * @return array правила валидации для атрибутов модели
	 */
	public function rules()
	{
		// NOTE: вам нужно лишь защитить атрибуты, которые будет вводить пользователь
		// можете удалить лишнее
		return array(
			array(
				'name',
				'required'
			),
			array(
				'responsible, in_work, removed, sales_status, creator, updater',
				'numerical',
				'integerOnly' => true
			),
			array(
				'name, phone, email, address',
				'length',
				'max' => 255
			),
			array(
				'gis_id',
				'length',
				'max' => 20
			),
			array(
				'updated, note',
				'safe'
			),


			// Следующее правило будет использовано в search().
			// @todo Пожалуйста удалите атрибуты, которые не должны экранироваться в поиске
			array(
				'id, name, phone, email, address, gis_id, created, updated, responsible, note, in_work, removed, sales_status, creator, updater',
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
			'logCustomers' => array(
				self::HAS_MANY,
				'LogCustomer',
				'customer'
			),
			'relationCustomerContacts' => array(
				self::HAS_MANY,
				'RelationCustomerContact',
				'customer'
			),
			'requisites' => array(
				self::HAS_MANY,
				'Requisites',
				'customer'
			),
			'tasks' => array(
				self::HAS_MANY,
				'Task',
				'customer'
			),

		);
	}

	/**
	 * @return array подписи атрибутов (атрибут=>подпись)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Идентификатор клиента',
			'name' => 'Название',
			'phone' => 'Телефон',
			'email' => 'Электронная почта',
			'address' => 'Адрес',
			'gis_id' => 'Идентификатор 2gis',
			'created' => 'Создан',
			'updated' => 'Последнее обновление',
			'responsible' => 'Ответсвенный',
			'note' => 'Коментарий',
			'in_work' => 'В работе',
			'removed' => 'Удален',
			'sales_status' => 'Статус продаж',
			'creator' => 'Кто добавил',
			'updater' => 'Кто последний раз обновил',

		);
	}

	/**
	 * @return array дефолтные атрибуты, выводящиеся в гриде
	 */
	public function attributeDefault()
	{
		return array(
			'id',
			'name',
			'phone',
			'email',
			'address',
			'gis_id',
			'created',
			'updated',
			'responsible',
			'note',
			'in_work',
			'removed',
			'sales_status',
			'creator',
			'updater',

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

	);

	/**
	 * @var array $grid_multi_selects - тут перечислены
	 * все атрибуты модели, которые должны иметь фильтр select2
	 * Важно!!! Для каждого из перечисленных атрибутов должны быть
	 * данные в методе getMultiSelectsData($attribute)
	 */
	public $grid_multi_selects = array();

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
		// Тут пример кода
		// TODO Удалите эти строки, если вам не нужны мультиселекты в таблице
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
	 * основываясь на CDbCriteria, сгенерированной из атрибутов модели
	 */
	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('phone', $this->phone, true);
		$criteria->compare('email', $this->email, true);
		$criteria->compare('address', $this->address, true);
		$criteria->compare('gis_id', $this->gis_id, true);
		$criteria->compare('responsible', $this->responsible);
		$criteria->compare('note', $this->note, true);
		$criteria->compare('in_work', $this->in_work);
		$criteria->compare('removed', $this->removed);
		$criteria->compare('sales_status', $this->sales_status);
		$criteria->compare('creator', $this->creator);
		$criteria->compare('updater', $this->updater);


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
	 * @return Customer статический класс модели
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


	// =)


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
	public function getMoreLink($attribute)
	{
		return '<a target="_blank" href="/' . strtolower(__CLASS__) . '/' . $this->id . '">' . $this->$attribute . '</a>';
	}

	/**
	 * Отдает чекбокс для индексной таблицы
	 * @return string
	 */
	public function getCheckboxForGrid()
	{
		return CHtml::checkBox(__CLASS__, false, array(
			'id' => $this->id,
			'value' => $this->id,
		));
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
						'header' => '#',
						'value' => '$data->getMoreLink("' . $row . '")',
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
					'id',
					'name',
					'phone',
					'email',
					'address',
					'gis_id',
					'created',
					'updated',
					'responsible',
					'note',
					'in_work',
					'removed',
					'sales_status',
					'creator',
					'updater',

				)
			),
			'other' => array(
				'label' => 'Прочие',
				'childs' => array()
			)
		);
	}

}
