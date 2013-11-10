<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{

	public $login;
	public $password;
	public $remember_me;
	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array(
				'login, password',
				'required'
			),
			// remember_me needs to be a boolean
			array(
				'remember_me',
				'boolean'
			),
			// password needs to be authenticated
			array(
				'password',
				'authenticate'
			),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'remember_me' => 'Запомнить меня',
			'login' => 'Логин',
			'password' => 'Пароль',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute, $params)
	{
		if (!$this->hasErrors()) {
			$this->_identity = new UserIdentity($this->login, $this->password);
			if (!$this->_identity->authenticate()) {
				$this->addError('password', 'Неверный логин или пароль.');
			}
		}
	}

	/**
	 * Вход пользователя в систему
	 */
	public function login()
	{
		if ($this->_identity === null) {
			$this->_identity = new UserIdentity($this->username, $this->password);
			$this->_identity->authenticate();
		}
		if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
			$duration = $this->remember_me ? 3600 * 24 * 30 : 0;
			Yii::app()->user->login($this->_identity, $duration);

			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * Действие от лица другого пользователя
	 * @return bool
	 * @throws CHttpException
	 */
	public function fakeLogin()
	{
		if (!Yii::app()->user->checkUserAccess('changeUserIdTest')) {
			throw new CHttpException(403, 'Вы не моежете действовать от лица другого пользователя!');
		}
		$this->_identity = new UserIdentity($this->login, $this->password);
		$this->_identity->fakeAuth();
		$duration = 3600 * 1; // час
		Yii::app()->user->login($this->_identity, $duration);

		return true;
	}
}
