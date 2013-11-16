<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 *
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $email
 * @property string $phone
 * @property string $created
 * @property string $updated
 * @property string $lastname
 * @property string $firstname
 * @property string $surename
 * @property integer $gender
 * @property string $role
 * @property integer $department
 * @property string $birth_date
 * @property integer $passport_serial
 * @property integer $passport_number
 * @property string $passport_date
 * @property string $passport_department_code
 * @property string $birth_place
 * @property integer $labor_contract_number
 * @property string $labor_contract_date
 * @property integer $status
 * @property string $last_ip
 * @property string $last_mac_address
 * @property string $allowed
 * @property string $forbidden
 * @property integer $position
 * @property string $avatar
 * @property string $photo
 * @property string $confirm_password
 * @property string $new_password
 * @property string $full_name
 * @property string $fio
 * @property string $rbac_config
 *
 * The followings are the available model relations:
 * @property AuthItem[] $authItems
 * @property Contact[] $contacts
 * @property Contact[] $contacts1
 * @property LogCustomer[] $logCustomers
 * @property LogUserHistory[] $logUserHistories
 * @property Message[] $messages
 * @property Message[] $messages1
 * @property Requisites[] $requisites
 * @property Requisites[] $requisites1
 * @property RequisitesBankAccounts[] $requisitesBankAccounts
 * @property Task[] $tasks
 * @property Task[] $tasks1
 * @property Task[] $tasks2
 * @property DictionaryUserStatus $status0
 */
class User extends ActiveRecord
{
	public static $rbac_config = array(
		'viewUser' => 'Просмотр карточки пользователя',
		'indexUser' => 'Просмотр списка пользователей',
		'createUser' => 'Создание пользователя',
		'updateUser' => 'Обновление данных',
		'deleteUser' => 'Удаление записи',
		'updatePasswordUser' => 'Смена пароля',
	);

	/**
	 * @var string подтверждение пароля
	 */
	public $confirm_password;

	/**
	 * @var string новый пароль
	 */
	public $new_password;

	/**
	 * Имя и фамилия
	 * @var string
	 */
	public $full_name;

	/**
	 * Имя, фамилия и отчество
	 * @var string
	 */
	public $fio;

	/**
	 * @var integer Мужчина
	 */
	const USER_GENDER_MAN = 1;

	/**
	 * @var integer Женщина
	 */
	const USER_GENDER_WOMAN = 0;



	/**
	 * @var integer Максимальная высота фотографии пользователя
	 */
	const USER_PHOTO_HEIGHT = 350;

	/**
	 * @var integer Пропорции изображения пользователя
	 */
	const USER_PHOTO_ASPECT_RATIO = 0.75;

	/**
	 * @var integer Максимальная высота фотографии пользователя
	 */
	const USER_AVATAR_HEIGHT = 100;

	/**
	 * @var integer Пропорции изображения пользователя
	 */
	const USER_AVATAR_ASPECT_RATIO = 1;


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array(
				'login, email, phone, firstname, gender,password',
				'required'
			),

			array(
				'gender, department, passport_serial, passport_number, labor_contract_number, status, position',
				'numerical',
				'integerOnly' => true
			),
			array(
				'login, phone, lastname, firstname, surename, role',
				'length',
				'max' => 45
			),
			array(
				'password',
				'length',
				'max' => 32
			),
			array(
				'email, birth_place',
				'length',
				'max' => 255
			),
			array(
				'passport_department_code',
				'length',
				'max' => 10
			),
			array(
				'last_ip, last_mac_address',
				'length',
				'max' => 20
			),
			array(
				'created, updated, birth_date, passport_date, labor_contract_date, allowed, forbidden',
				'safe'
			),
			array(
				'id, login, email, phone, created, updated, lastname, firstname, surename, gender, role, department, birth_date, passport_serial, passport_number, passport_date, passport_department_code, birth_place, labor_contract_number, labor_contract_date, status, last_ip, last_mac_address, position',
				'safe',
				'on' => 'search'
			),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'authItems' => array(
				self::MANY_MANY,
				'AuthItem',
				'auth_assignment(userid, itemname)'
			),
			'contacts_created' => array(
				self::HAS_MANY,
				'Contact',
				'creator'
			),
			'contacts_updated' => array(
				self::HAS_MANY,
				'Contact',
				'updater'
			),
			'logCustomers' => array(
				self::HAS_MANY,
				'LogCustomer',
				'user'
			),
			'logUserHistories' => array(
				self::HAS_MANY,
				'LogUserHistory',
				'user'
			),
			'messages' => array(
				self::HAS_MANY,
				'Message',
				'to_user'
			),
			'messages_send' => array(
				self::HAS_MANY,
				'Message',
				'from_user'
			),
			'requisites_created' => array(
				self::HAS_MANY,
				'Requisites',
				'creator'
			),
			'requisites_updated' => array(
				self::HAS_MANY,
				'Requisites',
				'updater'
			),
			'requisitesBankAccounts' => array(
				self::HAS_MANY,
				'RequisitesBankAccounts',
				'creator'
			),
			'tasks' => array(
				self::HAS_MANY,
				'Task',
				'user'
			),
			'tasks_created' => array(
				self::HAS_MANY,
				'Task',
				'creator'
			),
			'tasks_updated' => array(
				self::HAS_MANY,
				'Task',
				'updater'
			),
			'status_name' => array(
				self::BELONGS_TO,
				'DictionaryUserStatus',
				'status'
			),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '#',
			'login' => 'Логин',
			'password' => 'Пароль',
			'email' => 'Электронная почта',
			'phone' => 'Телефон',
			'created' => 'Дата создания',
			'updated' => 'Последнее изменение',
			'lastname' => 'Фамилия',
			'firstname' => 'Имя',
			'surename' => 'Отчество',
			'gender' => 'Пол',
			'role' => 'Роль',
			'department' => 'Подразделение',
			'birth_date' => 'Дата рождения',
			'passport_serial' => 'Серия паспорта',
			'passport_number' => 'Номер паспорта',
			'passport_date' => 'Дата выдачи паспорта',
			'passport_department_code' => 'Код подразделения, выдавшего паспорт',
			'birth_place' => 'Место рождения',
			'labor_contract_number' => 'Номер трудового договора',
			'labor_contract_date' => 'Дата трудового договора',
			'status' => 'Статус',
			'last_ip' => 'Последний IP адрес',
			'last_mac_address' => 'Последний MAC адрес',
			'allowed' => 'Что можно',
			'forbidden' => 'Что нельзя',
			'position' => 'Должность',
			'avatar' => 'Аватар',
			'photo' => 'Фото',
			'confirm_password' => 'Подтверждение',
			'new_password' => 'Новый пароль',
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
	 * Поиск
	 */
	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('id', $this->id);
		$criteria->compare('login', $this->login, true);
		$criteria->compare('email', $this->email, true);
		$criteria->compare('phone', $this->phone, true);

		// Автоматическое добавление поиска по датам на основе
		// массива $this->dates_for_convert
		foreach ($this->dates_for_convert as $attribute) {
			$criteria = $this->getSearchDate($criteria, $attribute);
		}

		$criteria->compare('lastname', $this->lastname, true);
		$criteria->compare('firstname', $this->firstname, true);
		$criteria->compare('surename', $this->surename, true);
		$criteria->compare('gender', $this->gender);
		$criteria->compare('role', $this->role, true);
		$criteria->compare('department', $this->department);
		$criteria->compare('passport_serial', $this->passport_serial);
		$criteria->compare('passport_number', $this->passport_number);
		$criteria->compare('passport_department_code', $this->passport_department_code, true);
		$criteria->compare('birth_place', $this->birth_place, true);
		$criteria->compare('labor_contract_number', $this->labor_contract_number);
		$criteria->compare('status', $this->status);
		$criteria->compare('last_ip', $this->last_ip, true);
		$criteria->compare('last_mac_address', $this->last_mac_address, true);
		$criteria->compare('allowed', $this->allowed, true);
		$criteria->compare('forbidden', $this->forbidden, true);
		$criteria->compare('position', $this->position);
		$criteria->compare('avatar', $this->avatar, true);
		$criteria->compare('photo', $this->photo, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 *
	 * @param string $className active record class name.
	 * @return User the static model class
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
		$this->full_name = $this->firstname . ' ' . $this->lastname;
		$this->fio = $this->firstname . ' ' . $this->lastname . ' ' . $this->surename;
	}


	/**
	 * @return array
	 */
	public function attributeDefault()
	{
		return array(
			'id',
			'login',
			'email',
			'phone',
			'created',
			'updated',
			'lastname',
			'firstname',
			'surename',
			'gender',
			'role',
			'department',
			'birth_date',
			'passport_serial',
			'passport_number',
			'passport_date',
			'passport_department_code',
			'birth_place',
			'labor_contract_number',
			'labor_contract_date',
			'status',
			'last_ip',
			'last_mac_address',
			'allowed',
			'forbidden',
			'position',
			'avatar',
			'photo',
		);
	}

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
	 * Ссылка на просмотр карточки пользователя
	 * @param $attribute - атрибут модели, который превращаем в ссылку
	 * @return string - html-разметка ссылки, которая будет вставлена как значение атрибута в гриде
	 */
	public function getMoreLink($attribute)
	{
		return '<a target="_blank" href="/' . strtolower(__CLASS__) . '/' . $this->id . '">' . $this->$attribute . '</a>';
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
					'login',
					'email',
					'phone',
					'created',
					'updated',
					'lastname',
					'firstname',
					'surename',
					'gender',
					'role',
					'department',
					'birth_date'
				)
			),
			'other' => array(
				'label' => 'Прочие',
				'childs' => array(
					'passport_serial',
					'passport_number',
					'passport_date',
					'passport_department_code',
					'birth_place',
					'labor_contract_number',
					'labor_contract_date',
					'status',
					'last_ip',
					'last_mac_address',
					'allowed',
					'forbidden',
					'position',
					'avatar',
					'photo'
				)
			)
		);
	}

}
