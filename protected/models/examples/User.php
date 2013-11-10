<?php

/**
 * This is the model class for table "acl_user".
 *
 * The followings are the available columns in table 'users':
 *
 * @property integer $id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $ip
 * @property integer $personal_id
 * @property string $sid
 * @property boolean $authstate
 * @property integer $role_id
 * @property string $last_session
 * @property integer $blocked
 *
 * The followings are the available model relations:
 * @property Personal $personal
 * @property Object[] $objects
 * @property Object[] $objects1
 * @property ObjRequest[] $objRequests
 * @property ObjRequestHistory[] $objRequestHistories
 * @property ObjRequestHistory[] $objRequestHistories1
 * @property ObjRequestHistory[] $objRequestHistories2
 * @property MsgRequest[] $msgRequests
 * @property LogsObject[] $logsObjects
 * @property Filter[] $filters
 */
class User extends ActiveRecord
{

	public $max;
	public $blocked;
	public $repeat_password;
	public $photo;
	public $fake_id;

	const SCENARIO_UPDATE_PASSWORD = 'update_password';

	/**
	 * Returns the static model of the specified AR class.
	 *
	 * @param string $className active record class name.
	 * @return AclUser the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
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
				'personal_id, role_id, blocked',
				'numerical',
				'integerOnly' => true
			),
			array(
				'name',
				'length',
				'max' => 150
			),
			array(
				'username',
				'length',
				'max' => 20
			),
			array(
				'password',
				'length',
				'max' => 64
			),
			array(
				'sid',
				'length',
				'max' => 100
			),
			array(
				'ip, authstate, last_session',
				'safe'
			),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array(
				'id, name, username, password, ip, personal_id, sid, authstate, role_id, last_session, blocked',
				'safe',
				'on' => 'search'
			),
			array(
				'password, repeat_password',
				'required',
				'on' => self::SCENARIO_UPDATE_PASSWORD
			),
			array(
				'password',
				'compare',
				'compareAttribute' => 'repeat_password',
				'on' => self::SCENARIO_UPDATE_PASSWORD
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
			'personal' => array(
				self::BELONGS_TO,
				'Personal',
				'personal_id'
			),
			'manager' => array(
				self::HAS_MANY,
				'Object',
				'manager_id'
			),
			'administrator' => array(
				self::HAS_MANY,
				'Object',
				'administrator_id'
			),
			'studio' => array(
				self::HAS_MANY,
				'Object',
				'studio_id'
			),
			'responsibleit' => array(
				self::HAS_MANY,
				'Object',
				'responsibleit_id'
			),
			'responsible' => array(
				self::HAS_MANY,
				'Object',
				'responsible_id'
			),
			'objects' => array(
				self::HAS_MANY,
				'Object',
				'usercreate'
			),
			'objects1' => array(
				self::HAS_MANY,
				'Object',
				'usermod'
			),
			'objRequests' => array(
				self::HAS_MANY,
				'ObjRequest',
				'manager_mod'
			),
			'objRequestHistories' => array(
				self::HAS_MANY,
				'ObjRequestHistory',
				'user_id'
			),
			'objRequestHistories1' => array(
				self::HAS_MANY,
				'ObjRequestHistory',
				'usercreate'
			),
			'objRequestHistories2' => array(
				self::HAS_MANY,
				'ObjRequestHistory',
				'usermod'
			),
			'msgRequests' => array(
				self::HAS_MANY,
				'MsgRequest',
				'user_to'
			),
			'logsObjects' => array(
				self::HAS_MANY,
				'LogsObject',
				'user_id'
			),
			'filters' => array(
				self::HAS_MANY,
				'Filter',
				'user_id'
			),
			'orgrole' => array(
				self::HAS_MANY,
				'AuthAssignment2',
				'userid'
			),
			//			'personalAccess' => array(self::BELONGS_TO, 'UserPersonalAccessRules', 'id'),
			'personalAccess' => array(
				self::HAS_ONE,
				'UserPersonalAccessRules',
				'user_id'
			)
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'fake_id' => 'Пользователь',
			'name' => 'Имя',
			'username' => 'Логин',
			'password' => 'Пароль',
			'ip' => 'Последний ip-адрес',
			'personal_id' => 'Сотрудник',
			'sid' => 'SID',
			'authstate' => 'Статус авторизации',
			'role_id' => 'Роль',
			'last_session' => 'Последний визит',
			'blocked' => 'Заблокировать',
			'role' => 'Выбрать роль',
			'repeat_password' => 'Подтверждение пароля',
		);
	}

	public function updatePassword($password)
	{
		try {
			$this->updateAll(array(
				'password' => $password,
				'id = ' . Yii::App()->user->id
			));

			return true;
		}
		catch (Exception $exc) {
			return false;
		}
	}

	public function hashPassword($password)
	{
		return bin2hex(mhash(MHASH_SHA256, $password));
	}

	public function validatePassword($password)
	{
		return $this->hashPassword($password) === $this->password;
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('username', $this->username, true);
		$criteria->compare('password', $this->password, true);
		$criteria->compare('ip', $this->ip, true);
		$criteria->compare('personal_id', $this->personal_id);
		$criteria->compare('sid', $this->sid, true);
		$criteria->compare('authstate', $this->authstate);
		$criteria->compare('role_id', $this->role_id);
		$criteria->compare('last_session', $this->last_session, true);
		$criteria->compare('blocked', $this->blocked);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function getNewUser()
	{
		return $this->with('personal')->findAll(array('condition' => "t.created >= (current_date - interval '7 days') AND personal.is_fired = FALSE"));
	}
}