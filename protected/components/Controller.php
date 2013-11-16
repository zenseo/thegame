<?php

class Controller extends CController
{
	public $breadcrumbs;


	public function checkRequiredData($data=array()){
		foreach($data as $param){
			if(!isset($_POST[$param]) || empty($_POST[$param])){
				$this->throwException('Необходимо передать '.$param, 402);
			}
		}
	}


	/**
	 * Проверяет доступ к элементу авторизации.
	 * По сути это просто сокращенная запись Yii::app()->user->checkUserAccess
	 *
	 * @param $auth_item - элемент авторизации RBAC дерева
	 * @return boolean - возвращает true или false в зависимости от того можно или нельзя
	 */
	public function allowed($auth_item)
	{
		return Yii::app()->user->checkUserAccess($auth_item);
	}

	/**
	 * RBAC проверка доступности кода.
	 * Рубит процесс, если нет доступа
	 * к действию, выводит ошибку 403
	 *
	 * @param $auth_item - элемент авторизации RBAC дерева
	 * @param bool $message - Свое сообщение пользователю (необязательно)
	 * @throws CHttpException || JSON || boolean
	 *        возвращает ошибку, если не прошло проверку, либо кидает JSON с кодом status = 403
	 *        ИЛИ
	 *        true - если доступ разрешен
	 */
	public function checkAccess($auth_item, $message = false)
	{
		// Проверяем доступ... Если доступ есть - сразу возвращаем true
		if ($this->allowed($auth_item)) {
			return true;
		}

		// Доступ не получен - загружаем элемент авторизации
		$auth_item = Yii::app()->authManager->getAuthItem($auth_item);

		// Формируем сообщение для пользователя
		$message = $message ? $message : 'Нет доступа для совершения действия';

		// Помещаем описание элемента авторизации в сообщение.
		$message .= isset($auth_item->description) ? ' «' . $auth_item->description . '»' : ' «Неверное правило!»';

		// Если это аяксовый запрос - кидает json
		if (Yii::app()->getRequest()->isAjaxRequest) {
			$message = 'Ошибка 403 - ' . $message;
			echo CJSON::encode(array(
				'status' => 403,
				'message' => $message
			));
			Yii::app()->end();
		}
		else {
			// Ну а если обычный - кидаем эксепшн
			throw new CHttpException(403, $message);
		}

	}

	/**
	 * Проверяет на аяксовость
	 * @return mixed - аякс или нет
	 */
	public function isAjax()
	{
		return Yii::app()->request->isAjaxRequest;
	}

	/**
	 * Кидает эксепшн, если не аякс
	 * @param bool $message - сообщение пользователю заплутавшему в системе и решившему пошалить в строке браузера (необязательно)
	 * @throws CHttpException - эксепшн гласящий - "Вали отсюда по добру по здорову и возвращайся с АЯКСОМ!!!"
	 */
	public function denyNotAjax($message = false)
	{
		if (!$this->isAjax()) {
			throw new CHttpException(403, $message ? $message : 'Доступ открыт только для AJAX запросов');
		}
	}

	/**
	 * Показывает пользователю сообщение системы.
	 * @param bool $message - сообщение
	 */
	public function showMessage($message = 'Операция прошла успешно!')
	{
		// Если это аяксовый запрос - кидает json
		if (Yii::app()->request->isAjaxRequest) {
			echo CJSON::encode(array(
				'status' => 200,
				'message' => $message
			));
			Yii::app()->end();
		}
		else {
			// Ну а если обычный - кидаем флеш
			Yii::app()->user->setFlash('success', $message);
		}
	}

	/**
	 * Кидает пользователю исключение.
	 * Если запрос аяксовый - кидает аяксовое исключение,
	 * Если не аякс - кидает простое исключение...
	 * @param bool|string $message - сообщение об ошибке
	 * @param bool|int $code - код ошибки
	 * @param bool|string $type - тип эксепшна
	 * @throws
	 */
	public function throwException($message = 'Что-то сломалось...', $code = 500, $type = 'Exception')
	{
		// Если это аяксовый запрос - кидает json
		if (Yii::app()->getRequest()->isAjaxRequest) {
			echo CJSON::encode(array(
				'status' => $code,
				'message' => $message
			));
			Yii::app()->end();
		}
		else {
			// Ну а если обычный - кидаем эксепшн
			throw new $type($code, $message);
		}
	}


	public function SendEmail($to, $layout, $view, $data, $subject, $from = 'agilovr@gmail.com')
	{
		Yii::app()->mailer->IsSMTP();
		Yii::app()->mailer->Host = 'mail.google.com';
		Yii::app()->mailer->SMTPDebug = 0;
		Yii::app()->mailer->Username = "agilovr@gmail.com";
		Yii::app()->mailer->Password = "jxtymyflt;ysq";
		Yii::app()->mailer->CharSet = 'utf-8';
		Yii::app()->mailer->ContentType = 'text/html';
		Yii::app()->mailer->From = $from;
		Yii::app()->mailer->FromName = 'The Game 2';
		if (is_array($to) && count($to)) {
			foreach ($to as $addr) {
				Yii::app()->mailer->AddAddress($addr);
			}
		}
		else {
			Yii::app()->mailer->AddAddress($to);
		}
		Yii::app()->mailer->Subject = $subject;
		Yii::app()->mailer->GetView($view, $data, $layout = $layout, $html = true);
		if (Yii::app()->mailer->Send()) {
			return true;
		}
		else {
			return false;
		}
	}


	/**
	 * Устанавливает RBAC
	 * Подробнее об RBAC можно прочесть тут:
	 * yiiframework.ru/doc/cookbook/ru/access.rbac.file
	 */
	public function actionInstallRbac()
	{
		//		if (!Yii::app()->user->checkUserAccess('reinstallRbacTest')) {
		//			throw new CHttpException(403, 'Невозможно совершить дейтсвие "Переустановка RBAC". Доступ запрещен!');
		//		}
		//		if (!Yii::app()->request->isAjaxRequest) {
		//			throw new CHttpException(403, 'Любите шалить со строкой браузера?');
		//		}
		// Инициализируем компонент RBAC
		/** @var DbAuthManager $auth */
		$auth = Yii::app()->authManager;

		//сбрасываем все существующие правила
		$auth->clearAll();

		/**
		 * Тут описывается список моделей в которых
		 * присутствует разграничение прав доступа на основе ролей
		 *
		 * !!! Примечание !!! В каждой описанной выше модели
		 * должна присутствовать переменная public static $rbac_config
		 *
		 * Пример:
		 * <pre>
		 *        public static $rbac_config = array(
		 *            'view' => 'Просмотр карточки пользователя',
		 *            'index' => 'Просмотр списка пользователей',
		 *            'create' => 'Создание пользователя',
		 *            'update' => 'Обновление данных',
		 *        );
		 * </pre>
		 */

		$super_user = $auth->createRole('admin', 'Администратор');

		$models = array(
			'User',
			'Customer'
		);
		// Прогоняем все модели из списка
		foreach ($models as $model) {
			$rbac_config = 'rbac_config';
			if (isset($model::$rbac_config)) {
				$operations = $model::$rbac_config;
				foreach ($operations as $name => $description) {
					// Генерируем операции на основе конфига что-то вроде
					$auth->createOperation($name, $description);
					$super_user->addChild($name);// И сразу же их добавляем суперпользователю
				}
			}
		}
		/**
		 * Пользователи
		 */
		$own_user = 'return Yii::app()->user->id == $params["user"]->id;';
		$task = $auth->createTask('viewOwnDataUser', 'Видеть собственные данные', $own_user);
		$task->addChild('viewUser');
		$task = $auth->createTask('updateOwnDataUser', 'Обновление собственных данных', $own_user);
		$task->addChild('updateUser');

		/**
		 * Тестирование системы
		 */
		$auth->createOperation('panelTest', 'Доступ к панели тестирования');
		$auth->createOperation('changeOwnRoleTest', 'Изменение собственной роли');
		$auth->createOperation('changeUserIdTest', 'Действовать от лица пользователя');
		$auth->createOperation('reinstallRbacTest', 'Переустановить RBAC');


		$auth->createRole('blocked', 'Заблокирован');
		$auth->createRole('fired', 'Уволен');
		$user = $auth->createRole('user', 'Пользователь');
		$user->addChild('viewOwnDataUser');
		$user->addChild('updateOwnDataUser');

		/**
		 * Отдел развития
		 */
		// Сотрудник группы развития
		$role = $auth->createRole('grow_employee', 'Сотрудник группы развития');
		$role->addChild('user'); // Наследует все простые доступы от пользователя


		// Глава группы развития
		$role = $auth->createRole('grow_group_head', 'Глава группы развития');
		$role->addChild('grow_employee');

		// Глава отдела развития
		$role = $auth->createRole('grow_head', 'Глава отдела развития');
		$role->addChild('grow_group_head');


		/**
		 * Отдел мониторинга
		 */
		// Сотрудник группы мониторинга
		$role = $auth->createRole('monitoring_employee', 'Сотрудник группы мониторинга');
		$role->addChild('user'); // Наследует все простые доступы от пользователя

		// Глава группы мониторинга
		$role = $auth->createRole('monitoring_group_head', 'Глава группы мониторинга');
		$role->addChild('monitoring_employee');

		// Глава мониторинга
		$role = $auth->createRole('monitoring_head', 'Глава мониторинга');
		$role->addChild('monitoring_group_head');


		/**
		 * Продажи
		 */
		// Сотрудник группы продаж
		$role = $auth->createRole('sales_employee', 'Сотрудник группы продаж');
		$role->addChild('user'); // Наследует все простые доступы от пользователя

		// Глава группы продаж
		$role = $auth->createRole('sales_group_head', 'Глава группы продаж');
		$role->addChild('sales_employee');


		// Глава отдела продаж
		$role = $auth->createRole('sales_department_head', 'Глава отдела продаж');
		$role->addChild('sales_group_head');

		// Коммерческий директор
		$role = $auth->createRole('sales_head', 'Коммерческий директор');
		$role->addChild('sales_department_head');
		$role->addChild('monitoring_head');
		$role->addChild('grow_head');

		// Системный администратор
		$role = $auth->createRole('system_administrator', 'Системный администратор');
		$role->addChild('user');
		$role->addChild('indexUser');
		$role->addChild('viewUser');
		$role->addChild('createUser');
		$role->addChild('updateUser');

		// Ну а главарь
		// (он же admin) имеет доступ во все закоулки системы
		$super_user->addChild('sales_head');
		$super_user->addChild('system_administrator');
		$super_user->addChild('reinstallRbacTest');
		$super_user->addChild('panelTest'); //Доступ к панели тестирования
		//Сохраняем все это дело
		try {
			$auth->save();
			$this->showMessage('RBAC успешно установлен!');
		}
		catch (Exception $ex) {
			$this->throwException($ex->getCode(), $ex->getMessage());
		}
	}

}