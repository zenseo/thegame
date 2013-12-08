<?php
/**
 * Class TaskController
 */

class TaskController extends Controller
{
	/**
	 * Отображает модель
	 *
	 * @param integer $id идентификатор модели
	 */
	public function actionView($id)
	{
		$this->checkAccess('viewTask');
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
		$this->checkAccess('createTask');
		// Проверяем - пришли ли данные
		$this->checkRequiredData(array(
			'Task',
		));
		// Если нам переданы данные - начинаем обработку
		$model = new Task;

		// Перегружаем свойства модели пришедшими данными и
		$model->attributes = $_POST['Task'];
		// пытаемся сохранить запись в базе данных
		if ($model->save()) {
			// Если все прошло успешно - перенаправляем пользователя
			echo CJSON::encode(array(
				// На страницу просмотра только что созданной записи
				'redirect' => '/Task/' . $model->id
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
		$this->checkAccess('updateTask');
		$this->denyNotAjax();
		// Проверяем - пришли ли данные
		$this->checkRequiredData(array(
			'Task',
		));
		$model = $this->loadModel($id);
		$model->attributes = $_POST['Task'];
		if ($model->save()) {
			$this->showMessage('Task успешно изменен!');
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
		$this->checkAccess('deleteTask');
		try {
			// Если нам пришло много идентификаторов
			if (isset($_REQUEST['ids']) //
				&& is_array($_REQUEST['ids']) //
				&& count($_REQUEST['ids'])
			) {
				// Удаляем всех
				Task::model()->deleteAllByAttributes(array('id' => $_REQUEST['ids']));
				$this->showMessage('Task успешно удалены');
			}
			else if ($id !== 0) {
				$this->loadModel($id)->delete();
				$this->showMessage('Task успешно удален');
			}
			else {
				throw new Exception('Переданы не все параметры!', 402);
			}
		}
		catch (Exception $e) {
			$this->throwException('Не удалось удалить Task: ' . $e->getMessage(), $e->getCode());
		}
	}


	/**
	 * Выводит список всех записей
	 */
	public function actionIndex()
	{
		$this->checkAccess('indexTask');
		$model = new Task('search');
		$grid_id = "task_grid";
		$model->unsetAttributes(); // чистим дефолтные значения
		if (isset($_GET['Task'])) {
			$model->attributes = $_GET['Task'];
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
				'grid_data_provider' => $model->search(),
				'filter' => $columns_filter,
				'columns' => $model->columnsGrid($columns_filter)
			));
		}
		else { // Если нет - выводим обычный индекс
			$this->render('index', array(
				'model' => $model,
				'grid_id' => $grid_id,
				'grid_data_provider' => $model->search(),
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
		$model = Task::model()->findByPk($id);
		if ($model === null) {
			$this->throwException('Модель класса Task не найдена в базе данных', 404);
		}

		return $model;
	}

}
