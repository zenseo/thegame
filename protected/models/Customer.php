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
 * @property integer $responsible_id Ответсвенный
 * @property string $note Коментарий
 * @property integer $in_work В работе
 * @property integer $removed Удален
 * @property integer $sales_status Статус продаж
 * @property integer $creator_id Кто добавил
 * @property integer $updater_id Кто последний раз обновил
 *
 *
 * Ниже описаны доступные для модели зависимости:
 * @property LogCustomer[] $logCustomers
 * @property RelationCustomerContact[] $relationCustomerContacts
 * @property Requisites[] $requisites
 * @property Task[] $tasks
 * @property User $creator
 * @property User $updater
 * @property User $responsible
 */
class Customer extends ActiveRecord
{


	////////////////////////////////////////////////////
	/////// Константы статусов продажи клиентов ////////
	////////////////////////////////////////////////////
	/**
	 * @var integer Потенциальный клиент
	 */
	const SALES_STATUS_POTENTIAL = 1;

	/**
	 * @var integer Заинтересованный
	 */
	const SALES_STATUS_INTERESTED = 2;

	/**
	 * @var integer В процессе покупки
	 */
	const SALES_STATUS_IN_PROGRESS = 3;

	/**
	 * @var integer Купил один раз
	 */
	const SALES_STATUS_ONE_SALE = 4;

	/**
	 * @var integer Постоянный клиент
	 */
	const SALES_STATUS_PERMANENT = 5;

	/**
	 * @var integer Закрылся
	 */
	const SALES_STATUS_DEAD = 6;

	/**
	 * @var integer Отказ
	 */
	const SALES_STATUS_DISAGREE = 7;

	/**
	 * @var integer Архив
	 */
	const SALES_STATUS_ARCHIVE = 8;

	/**
	 * @var integer Зомби
	 */
	const SALES_STATUS_ZOMBIE = 9;


	/**
	 * @var array Конфигурационный массив правил для RBAC
	 * TODO Отредактируйте коментарии к правилам, для лучшего понимания
	 * Например на пишите - ... =>  'Просмотр карточки пользователя'
	 * вместо ... => 'Просмотр карточки'
	 */
	public static $rbac_config = array(
		'viewCustomer' => 'Просмотр карточки клиента',
		'indexCustomer' => 'Просмотр списка клиентов',
		'createCustomer' => 'Создание клиента',
		'updateCustomer' => 'Обновление данных клиента',
		'deleteCustomer' => 'Удаление клиента',
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
				'email',
				'email'
			),
			array(
				'phone',
				'match',
				'message' => 'Номер телефона должен быть в формате +79998887766 или 89998887755',
				'pattern' => '/\+?\d{11,15}/'
			),
			array(
				'responsible_id, in_work, removed, sales_status, creator_id, updater_id',
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
				'id, name, phone, email, address, gis_id, created, updated, responsible_id, note, in_work, removed, sales_status, creator_id, updater_id',
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

			// Ответственный
			'responsible' => array(
				self::BELONGS_TO,
				'User',
				'responsible_id'
			),
			'creator' => array(
				self::BELONGS_TO,
				'User',
				'creator_id'
			),
			'updater' => array(
				self::BELONGS_TO,
				'User',
				'updater_id'
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
			'responsible_id' => 'Ответсвенный',
			'note' => 'Коментарий',
			'in_work' => 'В работе',
			'removed' => 'Удален',
			'sales_status' => 'Статус продаж',
			'creator_id' => 'Кто добавил',
			'updater_id' => 'Кто обновил',

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
			'created',
			'updated',
			'responsible_id',
			'note',
			'in_work',
			'sales_status',
			'creator_id',
			'updater_id',

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
		'updated'

	);

	/**
	 * @var array $grid_multi_selects - тут перечислены
	 * все атрибуты модели, которые должны иметь фильтр select2
	 * Важно!!! Для каждого из перечисленных атрибутов должны быть
	 * данные в методе getMultiSelectsData($attribute)
	 */
	public $grid_multi_selects = array(
		'in_work',
		'sales_status',
		'responsible_id',
		'creator_id',
		'updater_id',
	);

	/**
	 * Метод, который отдает данные для фильтра типа мультиселект
	 * @param $attribute атрибут модели, для которого нужно получить данные
	 * @return boolean|array - возвращает массив данных или false, если не описано
	 * получение данных или атрибут не присутствует в массиве $this->grid_multi_selects
	 */
	public function getMultiSelectsData($attribute)
	{
		// Если мультиселект не описан - не нужно ничего проверять
		if (!in_array($attribute, $this->grid_multi_selects)) {
			return;
		}
		switch ($attribute) {
			case 'in_work':
				$data = Utilities::YesNo();
				break;
			case 'sales_status':
				$dictionary = new Dictionary('customer__sales_status');
				$data = CHtml::listData($dictionary->findAll(), 'id', 'name');
				break;
			case 'updater_id':
				$data = CHtml::listData(User::activeUsers(), 'id', 'full_name');
				break;
			case 'creator_id':
				$data = CHtml::listData(User::activeUsers(), 'id', 'full_name');
				break;
			case 'responsible_id':
				$data = CHtml::listData(User::activeUsers(), 'id', 'full_name');
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
//		$criteria->compare('gis_id', $this->gis_id, true);
		$criteria->compare('note', $this->note, true);
		$criteria->compare('in_work', $this->in_work);
		$criteria->compare('removed', 0);
		$criteria->compare('sales_status', $this->sales_status);
		$criteria->compare('creator_id', $this->creator_id);
		$criteria->compare('updater_id', $this->updater_id);
		$criteria->compare('responsible_id', $this->responsible_id);


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
					'created',
					'updated',
					'responsible_id',
					'note',
					'in_work',
					'sales_status',
					'creator_id',
					'updater_id',

				)
			),
			'other' => array(
				'label' => 'Прочие',
				'childs' => array()
			)
		);
	}

}
