<?
class WebUser extends CWebUser
{
	/**
	 * Возвращает роль пользователя
	 * @return string
	 *
	 */
	function getRole()
	{

		// Проверяем можно ли пользоваетлю использовать фальшивую роль
		if ($this->checkPersonalAccess('changeOwnRoleTestTest')) {
			if (isset($_COOKIE['tester_params'])) {
				$params = CJSON::decode($_COOKIE['tester_params']);
				if (isset($params['tester_fake_role'])) {
					return $params['tester_fake_role'];
				}
			}
		}
		$user = User::model()->findByPk($this->id);

		return $user->role;
	}

	/**
	 * Формирует массив правил доступа для конкретного пользователя.
	 * Эти данные необходимы для расширенной функции
	 * @param $user_id integer - идентификатор пользователя для которого получаем разрешения (необязательный параметр)
	 * @return array - возвращает многомерный массив, содержащий 2 подмассива (разрешенные действия и запрещенные действия)
	 */
	function getPersonalAccess($user_id = null)
	{
		if (!$user_id) {
			$user_id = $this->id;
		}
		$user = User::model()->findByPk($user_id);
		if ($user) {
			$allowed = $user->allowed ? explode(',', $user->allowed) : array();
			$forbidden = $user->forbidden ? explode(',', $user->forbidden) : array();
		}
		else {
			$allowed = array();
			$forbidden = array();
		}


		return array(
			'allowed' => $allowed,
			'forbidden' => $forbidden
		);
	}

	/**
	 * Метод который возвращает true или false в зависимости от того
	 * можно ли данному конкретному пользователю совершать запрашиваемое действие или нет
	 * проверяет только личные настройки доступа
	 *
	 * @param $action string - действие, которое проверяется
	 * @param $params array - параметры для "Бизнес правила" в RBAC
	 * @return boolean
	 */
	function checkPersonalAccess($action)
	{
		$access_list = $this->personalAccess;
		if (in_array($action, $access_list['forbidden'])) {
			return false;
		}
		if (in_array($action, $access_list['allowed'])) {
			return true;
		}

		return false;
	}

	/**
	 * Метод который возвращает true или false в зависимости от того
	 * можно ли данному конкретному пользователю совершать запрашиваемое действие или нет
	 *
	 * @param $action string - действие, которое проверяется
	 * @param $params array - параметры для "Бизнес правила" в RBAC
	 * @return boolean
	 */
	function checkUserAccess($action, $params = array())
	{
		$access_list = $this->personalAccess;
		if (in_array($action, $access_list['forbidden'])) {
			return false;
		}
		if (in_array($action, $access_list['allowed'])) {
			return true;
		}
		else {
			return $this->checkAccess($action, $params);
		}

	}

	/**
	 * Возвращает полное имя пользователя
	 * @return string
	 */
	public function getFullName()
	{
		$model = User::model()->findByPk($this->id);

		return $model->full_name;
	}
}