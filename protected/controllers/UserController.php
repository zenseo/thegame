<?php

class UserController extends Controller
{

	/**
	 * Displays a particular model.
	 *
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->checkAccess('viewUser');
		$model = $this->loadModel($id);
		$this->render('view', array(
			'model' => $model,
		));
	}

	/**
	 * Создает новую запись в базе данных
	 * Если запишь прошла успешно - перенаправляет пользователя на страницу просмотра
	 * вновь созданной записи.
	 */
	public function actionCreate()
	{
		// Проверяем можно ли совершать это действие
		$this->checkAccess('createUser');
		$model = new User;
		// Если нам переданы данные - начинаем обработку
		if (isset($_POST['User'])) {
			// Перегружаем свойства модели пришедшими данными и
			$model->attributes = $_POST['User'];
			$model->password = md5($model->password);
			// пытаемся сохранить запись в базе данных
			if ($model->save()) {
				// Если все прошло успешно - перенаправляем пользователя
				echo CJSON::encode(array(
					// На страницу просмотра только что созданной записи
					'redirect' => '/user/' . $model->id
				));
				Yii::app()->end();
			}
			else {
				// Если что-то пошло не так - выдаем пользователю сообщение об ошибке.
				$this->throwException(CHtml::errorSummary($model), 402);
			}
		}
		else {
			$this->throwException('Недостаточно данных!', 402);
		}
	}

	/**
	 * Загружает фотографию пользователя на сервер
	 * @param $id Идентификатор пользователя
	 */
	public function actionUploadPhoto($id)
	{
		/**
		 * @var $user User
		 */
		try {
			if (!isset($_FILES['user_photo'])) {
				$this->throwException('Недостаточно данных!', 402);
			}
			$image = new SuperImage();
			$user = $this->loadModel($id);
			$directory = Yii::app()->params['user_photos_path'];
			$file = $_FILES['user_photo'];
			$image->load($_FILES['user_photo']['tmp_name']);
			$real_height = $image->getHeight();
			if ($image->getWidth() / $real_height < User::USER_PHOTO_ASPECT_RATIO) {
				throw new Exception('Отношение ширины изображения к длинне не должно быть меньше ' . User::USER_PHOTO_ASPECT_RATIO);
			}
			$image->validate($file);
			$file_helper = new FileHelper();

			// Чистим все старые файлы перед загрузкой
			foreach ($image->extensions as $ext) {
				$file_helper->deleteFile($directory . $id . '.' . $ext);
				$file_helper->deleteFile($directory . $id . 'ava' . '.' . $ext);
			}
			$uploaded_image = $image->upload($file, $directory, $id);

			//			$image->load($uploaded_image['path']);
			//			if ($image->getWidth() > User::USER_PHOTO_HEIGHT * 2) {
			//				$image->file = $uploaded_image['path'];
			//				$image->smartResize(0, User::USER_PHOTO_HEIGHT * 2);
			//			}
			$user->photo = $uploaded_image['image'];
			if ($user->save()) {
				echo CJSON::encode(array(
					'status' => 200,
					'image' => Yii::app()->params['user_photos_base_url'] . $user->photo . '?' . time(),
					'real_height' => $real_height
				));
			}
			else {
				$this->throwException(CHtml::errorSummary($user), 402);
			}
		}
		catch (Exception $ex) {
			echo CJSON::encode(array(
				'status' => $ex->getCode(),
				'message' => $ex->getMessage()
			));
		}
	}


	public function actionCropUserPhoto($id)
	{
		if (!isset($_POST['coordinates'])) {
			$this->throwException('Недостаточно данных!', 402);
		}
		/** @var $user User */
		$user = $this->loadModel($id);
		$image = new SuperImage();
		$src = Yii::app()->params['user_photos_path'] . $user->photo;
		$image->path = $image->crop($src, $_POST['coordinates']);
		$image->load();
		if ($image->getHeight() > User::USER_PHOTO_HEIGHT) {
			$image->smartResize(0, User::USER_PHOTO_HEIGHT);
			$image->load();
		}
		echo CJSON::encode(array(
			'status' => 200,
			'image' => Yii::app()->params['user_photos_base_url'] . $user->photo . '?' . time(),
			'real_height' => $image->getHeight()
		));
	}

	public function actionCropUserAvatar($id)
	{
		if (!isset($_POST['coordinates'])) {
			$this->throwException('Недостаточно данных!', 402);
		}
		/** @var $user User */
		$user = $this->loadModel($id);
		$image = new SuperImage();
		$src = Yii::app()->params['user_photos_path'] . $user->photo;
		list($name, $ext) = explode('.', $user->photo);
		$image->path = $image->crop($src, $_POST['coordinates'], Yii::app()->params['user_photos_path'] . $name . '_ava.' . $ext);
		if ($image->getHeight() > User::USER_AVATAR_HEIGHT) {
			$image->smartResize(0, User::USER_AVATAR_HEIGHT);
		}
		$user->avatar = $name . '_ava.' . $ext;
		$user->save();
		echo CJSON::encode(array(
			'status' => 200,
			'ava' => Yii::app()->params['user_photos_base_url'] . $user->avatar . '?' . time(),
			'image' => Yii::app()->params['user_photos_base_url'] . $user->photo . '?' . time(),
		));
	}


	public function actionUpdate($id)
	{
		$this->checkAccess('updateUser');

		$model = $this->loadModel($id);
		if (isset($_POST['User'])) {
			$model->attributes = $_POST['User'];
			if ($model->save()) {
				$this->showMessage('Данные успешно сохранены!');
			}
			else {
				$this->throwException(CHtml::errorSummary($model), 402);
			}
		}
	}


	/**
	 * Изменяет пароль пользователя
	 * Необходимо 2 пароля, совпадающих друг с другом
	 * @param $id
	 */
	public function actionUpdatePassword($id)
	{
		$this->checkAccess('updatePasswordUser');
		$this->checkRequiredData(array(
			'new_password',
			'confirm_password'
		));

		if ($_POST['new_password'] === $_POST['confirm_password']) {
			$model = $this->loadModel($id);
			$model->password = md5($_POST['new_password']);
			if ($model->save()) {
				$this->showMessage('Пароль был успешно изменен!');
			}
			else {
				$this->throwException(CHtml::errorSummary($model), 402);
			}
		}
		else {
			$this->throwException('Пароли не совпадают!', 403);
		}
	}

	/**
	 * Удаляет
	 * @param integer $id
	 */
	public function actionDelete($id = 0)
	{
		$this->checkAccess('deleteUser');
		try {
			// Если нам пришло много идентификаторов
			if (isset($_REQUEST['ids']) //
				&& is_array($_REQUEST['ids']) //
				&& count($_REQUEST['ids'])
			) {
				// Удаляем всех
				User::model()->deleteAllByAttributes(array('id' => $_REQUEST['ids']));
				$this->showMessage('User успешно удалены!');
			}
			else if ($id !== 0) {
				$this->loadModel($id)->delete();
				$this->showMessage('User успешно удален');
			}
			else {
				throw new Exception('Переданы не все параметры!', 402);
			}
		}
		catch (Exception $e) {
			$this->throwException('Не удалось удалить ModelClass: ' . $e->getMessage(), $e->getCode());
		}
	}

	public function actionIndex()
	{
		$this->checkAccess('indexUser');
		$model = new User('search');
		$grid_id = "user_grid";
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['User'])) {
			$model->attributes = $_GET['User'];
		}
		else {
			if (isset($_COOKIE[$grid_id])) {
				$model->attributes = CJSON::decode($_COOKIE[$grid_id]);
			}
		}

		// Получаем список колонок
		if (isset($_COOKIE[$grid_id . '_columns'])) {
			$columns_filter = explode(',', $_COOKIE[$grid_id . '_columns']);
		}
		else {
			$columns_filter = $model->attributeDefault();
		}

		if ($this->isAjax()) {
			$this->renderPartial('_super_grid', array(
				'model' => $model,
				'grid_id' => $grid_id,
				'filter' => $columns_filter,
				'columns' => $model->columnsGrid($columns_filter)
			));
		}
		else {
			$this->render('index', array(
				'model' => $model,
				'grid_id' => $grid_id,
				'filter' => $columns_filter,
				'columns' => $model->columnsGrid($columns_filter)
			));
		}
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 *
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model = User::model()->findByPk($id);
		if ($model === null) {
			$this->throwException('CHttpException', 'Модель класса User не найдена в базе данных', 404);
		}

		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 *
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'user_form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


	/**
	 * Автодополнение пользователя
	 */
	public function actionAutoCompleteUser()
	{
		$term = Yii::app()->request->getParam('term', null);
		if (!$term) {
			exit;
		}
		$model = new User();
		$criteria = new CDbCriteria();

		// Разбиваем пришедший нам запрос на ключевые слова
		$keywords = explode(' ', $term);
		foreach ($keywords as $keyword) {
			// И ищем каждое ключевое слово в фамилии имени или отчестве пользователя
			$criteria->addSearchCondition('firstname', $keyword, true, "OR");
			$criteria->addSearchCondition('lastname', $keyword, true, "OR");
			$criteria->addSearchCondition('surename', $keyword, true, "OR");
		}

		$all_users = $model->model()->findAll($criteria);
		$users = array();
		foreach ($all_users as $u) {
			// Если тип элемента авторизации - 2 (роль)
			// То создаем элемент массива
			if ($u->role != 'fired') {
				$user = array();
				$user['id'] = $u->id;
				$user['login'] = $u->login;
				$user['label'] = $u->full_name . ($u->role? ' (' . $u->role . ')' : '');
				$users[] = $user;
			}
		}
		echo CJSON::encode($users);
	}

}
