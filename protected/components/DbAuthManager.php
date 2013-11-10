<?php
class DbAuthManager extends CDbAuthManager
{
	public function init()
	{
		parent::init();
		// Для гостей у нас и так роль по умолчанию guest.
		if (!Yii::app()->user->isGuest) {
			// Связываем роль, заданную в БД с идентификатором пользователя,
			// возвращаемым UserIdentity.getId().
			$this->assign(Yii::app()->user->role, Yii::app()->user->id);
		}
		else {
			//			Yii::app()->controller->redirect(array('site/login'));
		}
	}

	/**
	 * Назначает пользователю роль
	 * Пришлось переопределить этот метод,
	 * так как он натыкался на PK и не мог создать запись
	 * Перед записью в таблицу назначений теперь чистятся все
	 * записи в этой таблице для пользователя
	 */
	public function assign($itemName, $userId, $bizRule = null, $data = null)
	{
		Yii::app()->db->createCommand()->delete($this->assignmentTable, array(
			'userid = ' . $userId
		));

		return parent::assign($itemName, $userId, $bizRule, $data);
	}
}

?>