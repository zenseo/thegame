<?php

/**
 * Модель для формирования главного меню сайта
 * меню формируется на основе разрешений пользователя
 */
class MainMenu extends CActiveRecord
{

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'main_menu';
	}

	/**
	 * Выполняет простой запрос к базе данных вытаскивает из нее все активные записи
	 * вырезает из выборки все, что нельзя пользователю (используя checkUserAccess)
	 * и формирует из выборки многомерный массив, для bootstrap.widgets.TbNavbar
	 */
	public static function getMenu()
	{

		// Инициализация переменной-контейнера
		$items = array();

		// Выборка всего меню из базы данных
		$items_query = self::model()->findAllByAttributes(array('active' => 1)); // выбирает из базы только если запись активна

		foreach ($items_query as $i) {
			if (Yii::app()->user->checkUserAccess($i->rule) || empty($i->rule)) {
				$items[] = $i->attributes;
			}
		}

		for ($i = 0; $i < count($items); $i++) {
			// удаляем свойство active, потому что bootstrap.widgets.TbMenu
			// преобразует все элементы в "активные ссылки"
			unset($items[$i]['active']);
			// Добавляем элемент 'url'. Он необходим для формирования меню
			$items[$i]['url'] = Yii::app()->urlManager->createUrl($items[$i]['action']);
		}

		$data_sort = array();
		// Сортируем получившийся массив по полю sort
		foreach ($items as $key => $arr) {
			$data_sort[$key] = $arr['sort'];
		}
		array_multisort($data_sort, SORT_NUMERIC, $items);

		// Возвращаем готовый к применению в bootstrap.widgets.TbMenu
		return Utilities::toTree($items, 'id', 'parent', 'items');
	}
}