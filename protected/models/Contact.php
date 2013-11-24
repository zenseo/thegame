<?php

/**
 * Это класс модели для таблицы "contact".
 *
 * Ниже описаны доступные поля для таблицы 'contact':
 * 
 * @property integer $id Идентификатор контакта
 * @property string $lastname Фамилия
 * @property string $firstname Имя
 * @property string $surename Отчество
 * @property string $comment Комментарий
 * @property string $email Email
 * @property integer $icq ICQ
 * @property string $last_contact Последний контакт
 * @property string $created Создан
 * @property string $updated Обновлен
 * @property integer $creator_id Кто создал
 * @property integer $updater_id Кто обновил
 * @property string $phone Телефон
 * @property integer $customer_id Клиент
 * @property string $position Должность
 *
 * 
 * Ниже описаны доступные для модели зависимости:
 * @property User $creator
 * @property User $updater
 * @property Customer $customer
 * @property Requisites[] $requisites
 * @property Task[] $tasks
 */
class Contact extends ActiveRecord
{

	/**
	 * @var array Конфигурационный массив правил для RBAC
	 */
	public static $rbac_config = array(
		'viewContact' => 'Просмотр карточки контакта',
		'indexContact' => 'Просмотр списка контактов',
		'createContact' => 'Создание контакта',
		'updateContact' => 'Обновление контакта',
		'deleteContact' => 'Удаление контакта',
	);


	/**
	 * @return string возвращает сроку привязанной к модели таблицы
	 */
	public function tableName()
	{
		return 'contact';
	}

	/**
	 * @return array правила валидации для атрибутов модели
	 */
	public function rules()
	{
		// NOTE: вам нужно лишь защитить атрибуты, которые будет вводить пользователь
		// можете удалить лишнее
		return array(
			array('firstname', 'required'),
			array('icq, creator_id, updater_id, customer_id', 'numerical', 'integerOnly'=>true),
			array('lastname, firstname, surename, email', 'length', 'max'=>150),
			array('phone, position', 'length', 'max'=>45),
			array('comment, last_contact, updated', 'safe'),
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
			

			// Следующее правило будет использовано в search().
			// @todo Пожалуйста удалите атрибуты, которые не должны экранироваться в поиске
			array(
				'id, lastname, firstname, surename, comment, email, icq, last_contact, created, updated, creator_id, updater_id, phone, customer_id, position',
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
			'creator' => array(self::BELONGS_TO, 'User', 'creator_id'),
			'updater' => array(self::BELONGS_TO, 'User', 'updater_id'),
			'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
			'requisites' => array(self::HAS_MANY, 'Requisites', 'director_id'),
			'tasks' => array(self::HAS_MANY, 'Task', 'contact_id'),
			
		);
	}

	/**
	 * @return array подписи атрибутов (атрибут=>подпись)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Идентификатор контакта',
			'lastname' => 'Фамилия',
			'firstname' => 'Имя',
			'surename' => 'Отчество',
			'comment' => 'Комментарий',
			'email' => 'Email',
			'icq' => 'ICQ',
			'last_contact' => 'Последний контакт',
			'created' => 'Создан',
			'updated' => 'Обновлен',
			'creator_id' => 'Кто создал',
			'updater_id' => 'Кто обновил',
			'phone' => 'Телефон',
			'customer_id' => 'Клиент',
			'position' => 'Должность',
			
		);
	}

	/**
	 * @return array дефолтные атрибуты, выводящиеся в гриде
	 */
	public function attributeDefault()
	{
		return array(
			'id',
			'customer_id',
			'lastname',
			'firstname',
			'surename',
			'comment',
			'email',
			'icq',
			'last_contact',
			'created',
			'updated',
			'phone',
			'position',
		);
	}

	/**
	 * @return array дефолтные атрибуты, выводящиеся в гриде
	 */
	public function customerGridAttributeDefault()
	{
		return array(
			'lastname',
			'firstname',
			'surename',
			'email',
			'phone',
			'position',
//			'icq',
			'last_contact',
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
		'last_contact',
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
	 * @param $attribute атрибут модели, для которого нужно получить данные
	 * @return boolean|array - возвращает массив данных или false, если не описано
	 * получение данных или атрибут не присутствует в массиве $this->grid_multi_selects
	 */
	public function getMultiSelectsData($attribute)
	{
		// Если мультиселект не описан - не нужно ничего проверять
		if (!in_array($attribute, $this->grid_multi_selects)) {
			return false;
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
	 * @param $customer_id [optional] идентификатор клиента, по которому мы можем иногда ограничить поиск
	 * @return CActiveDataProvider  - поставщик данных
	 * основываясь на CDbCriteria, сгенерированной из атрибутов модели
	 */
	public function search($customer_id = null)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('surename',$this->surename,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('icq',$this->icq);
		$criteria->compare('creator_id',$this->creator_id);
		$criteria->compare('updater_id',$this->updater_id);
		$criteria->compare('phone',$this->phone,true);
		if(isset($customer_id)){
			$criteria->compare('customer_id',$customer_id);
		}else{
			$criteria->compare('customer_id',$this->customer_id);
		}
		$criteria->compare('position',$this->position,true);


		// Автоматическое добавление поиска по датам на основе
		// массива $this->dates_for_convert
		foreach ($this->dates_for_convert as $attribute) {
			$criteria = $this->getSearchDate($criteria, $attribute);
		}

		/* Пример превращения текстового ввода в идентифкиатор
		if (isset($this->creator_id)) {
			$user_criteria = new CDbCriteria();
			// Разбиваем пришедший нам запрос на ключевые слова
			$keywords = explode(' ', $this->creator_id);
			foreach ($keywords as $keyword) {
				// И ищем каждое ключевое слово в фамилии имени или отчестве пользователя
				$user_criteria->addSearchCondition('firstname', $keyword, true, "OR");
				$user_criteria->addSearchCondition('lastname', $keyword, true, "OR");
				$user_criteria->addSearchCondition('surename', $keyword, true, "OR");
			}
			$users = User::model()->findAll($criteria, array('select' => 'id'));
			if ($users) {
				$user_ids = array();
				foreach ($users as $user) {
					$user_ids[] = $user->id;
				}
				$criteria->compare('creator_id', $user_ids);
			}
		}
		*/

		$data_provider_config = array(
			'criteria' => $criteria
		);

		// Если у нас задан идентификатор клинета - делаем аякс
		// апдейт через контроллер клиента
		if(isset($customer_id)){
			$data_provider_config['pagination'] =array(
				'route'=>'/customer/contactsGrid/'
			);
			$data_provider_config['sort'] =array(
				'route'=>'/customer/contactsGrid/'
			);
		}

		return new CActiveDataProvider($this, $data_provider_config);
	}

	/**
	 * Возвращает статическую модель для указанного AR класса.
	 * Обратите внимание, что вы должны иметь этот метод во всех ваших CActiveRecord потомках!
	 *
	 * @param string $className имя класса активной записи.
	 * @return Contact статический класс модели
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
			'lastname',
			'firstname',
			'surename',
			'comment',
			'email',
			'icq',
			'last_contact',
			'created',
			'updated',
			'creator_id',
			'updater_id',
			'phone',
			'customer_id',
			'position',
			
				)
			),
			'other' => array(
				'label' => 'Прочие',
				'childs' => array()
			)
		);
	}

}
