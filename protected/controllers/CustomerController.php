<?php
/**
 * Class CustomerController
 */

class CustomerController extends Controller
{
	/**
	 * Отображает модель
	 *
	 * @param integer $id идентификатор модели
	 */
	public function actionView($id)
	{
		$this->checkAccess('viewCustomer');
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
		$this->checkAccess('createCustomer');
		// Проверяем - пришли ли данные
		$this->checkRequiredData(array(
			'Customer',
		));
		// Если нам переданы данные - начинаем обработку
		$model = new Customer;

		// Перегружаем свойства модели пришедшими данными и
		$model->attributes = $_POST['Customer'];
		// пытаемся сохранить запись в базе данных
		if ($model->save()) {
			// Если все прошло успешно - перенаправляем пользователя
			echo CJSON::encode(array(
				// На страницу просмотра только что созданной записи
				'redirect' => '/Customer/' . $model->id
			));
			Yii::app()->end();
		}
		else {
			// Если что-то пошло не так - выдаем пользователю сообщение об ошибке.
			$this->throwException(CHtml::errorSummary($model), 402);
		}
	}

	/**
	 * Обновляет данные модели
	 * @param $id идентификатор модели
	 */
	public function actionUpdate($id)
	{
		$this->checkAccess('updateCustomer');
		$this->denyNotAjax();
		// Проверяем - пришли ли данные
		$this->checkRequiredData(array(
			'Customer',
		));
		$model = $this->loadModel($id);
		$model->attributes = $_POST['Customer'];
		if ($model->save()) {
			$this->showMessage($model->name . ' успешно изменен!');
		}
		else {
			$this->throwException(CHtml::errorSummary($model), 500);
		}
	}


	/**
	 * Удаляет
	 * @param integer $id
	 */
	public function actionDelete($id = 0)
	{
		$this->checkAccess('deleteCustomer');
		try {
			// Если нам пришло много идентификаторов
			if (isset($_REQUEST['ids']) //
				&& is_array($_REQUEST['ids']) //
				&& count($_REQUEST['ids'])
			) {
				// Удаляем всех
				Customer::model()->deleteAllByAttributes(array('id' => $_REQUEST['ids']));
				$this->showMessage('Клиенты успешно удалены!');
			}
			else if ($id !== 0) {
				$this->loadModel($id)->delete();
				$this->showMessage('Клиент успешно удален!');
			}
			else {
				throw new Exception('Переданы не все параметры!', 402);
			}
		}
		catch (Exception $e) {
			$this->throwException('Не удалось удалить Customer: ' . $e->getMessage(), $e->getCode());
		}
	}


	/**
	 * Выводит список всех записей
	 */
	public function actionIndex()
	{
		$this->checkAccess('indexCustomer');
		$model = new Customer('search');
		$grid_id = "customer_grid";
		$model->unsetAttributes(); // чистим дефолтные значения
		if (isset($_GET['Customer'])) {
			$model->attributes = $_GET['Customer'];
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

		// Дабы JsonGrid нормально парсился рисуем его отдельно
		if ($this->isAjax()) {
			$this->renderPartial('_super_json_grid', array(
				'model' => $model,
				'grid_id' => $grid_id,
				'filter' => $columns_filter,
				'columns' => $model->columnsGrid($columns_filter)
			));
		}
		else { // Если нет - выводим обычный индекс
			$this->render('index', array(
				'model' => $model,
				'grid_id' => $grid_id,
				'filter' => $columns_filter,
				'columns' => $model->columnsGrid($columns_filter)
			));
		}
	}


	/**
	 * Возвращает данные модели, найденные по ключу
	 * если данные модели не найдены - выдает исключение.
	 *
	 * @param integer идентификатор модели
	 * @return null|ActiveRecord
	 */
	public function loadModel($id)
	{
		$model = Customer::model()->findByPk($id);
		if ($model === null) {
			$this->throwException('Модель класса Customer не найдена в базе данных', 404);
		}

		return $model;
	}

}
