<?php

/**
 * Аутентификация пользователя
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	private $login;

	/**
	 * Constructor.
	 *
	 * @param string $login username
	 * @param string $password password
	 */
	public function __construct($login, $password)
	{
		$this->login = $login;
		$this->password = $password;
	}

	public function authenticate()
	{
		$user = User::model()->find('LOWER(login)=?', array(strtolower($this->login)));

		if (!$user) {

			$this->errorCode = self::ERROR_USERNAME_INVALID;
		}
		elseif ($user->role == 'blocked') {

			$this->errorCode = self::ERROR_USERNAME_INVALID;
		}
		elseif (md5($this->password) != $user->password) {

			$this->errorCode = self::ERROR_PASSWORD_INVALID;

		}
		else {
			$this->_id = $user->id;
			$password = md5($this->password);
			$this->setState('password', $password);
			$this->errorCode = self::ERROR_NONE;

			return $this;
		}
	}

	public function fakeAuth()
	{
		if (!Yii::app()->user->checkUserAccess('changeUserIdTest')) {
			throw new CHttpException(403, 'Вы не моежете действовать от лица другого пользователя!');
		}

		$user = User::model()->find('LOWER(login)=?', array(strtolower($this->login)));
		$this->_id = $user->id;
		$password = $user->password;
		$this->setState('password', $password);
		$this->errorCode = self::ERROR_NONE;

	}

	/**
	 * Возвращает идентификатор
	 * @return mixed|string
	 */
	public function getId()
	{
		return $this->_id;
	}

	/**
	 * Возвращает логин
	 */
	public function getName()
	{
		return $this->login;
	}

}

?>