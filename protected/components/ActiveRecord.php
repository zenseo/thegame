<?php

class ActiveRecord extends CActiveRecord
{

	//	// Время кеширования страницы
	//	const CACHE_DURATION = 3000;
	//
	//	protected function beforeFind()
	//	{
	//		$tags = array($this->tableName());
	//		$this->cache(self::CACHE_DURATION, new Dependency(array($tags)));
	//		parent::beforeFind();
	//	}
	//
	//	protected function afterSave()
	//	{
	//		Helper::deleteByTags($this->tableName());
	//		parent::afterSave();
	//	}
	//
	//	protected function afterDelete()
	//	{
	//		Helper::deleteByTags($this->tableName());
	//		parent::afterDelete();
	//	}

	/**
	 * После того, как нашла
	 * @return bool
	 */
	public function afterFind()
	{
		parent::afterFind();

		// Обработка дат (приводит даты в человеческий вид)
		if (isset($this->dates_for_convert) && is_array($this->dates_for_convert)) {
			$attributes = $this->dates_for_convert;
			foreach ($attributes as $attribute) {
				if (!empty($this->$attribute)) {
					$this->$attribute = date(Yii::app()->params['dateFormat'], strtotime($this->$attribute));
				}
			}
		}

		return true;
	}

	/**
	 * Обрабатывает модель перед сохранением
	 * @return bool
	 */
	public function beforeSave()
	{
		parent::beforeSave();

		// Обработка дат (конвертирует их в timestamp)
		if (isset($this->dates_for_convert) && is_array($this->dates_for_convert)) {
			$attributes = $this->dates_for_convert;
			foreach ($attributes as $attribute) {
				if (!empty($this->$attribute)) {
					$this->$attribute = date(Yii::app()->params['timestamp'], strtotime($this->$attribute));
				}
			}
		}

		return true;
	}

	/**
	 * Создает фильтр для выбора периода дат при помощи виджета {@link TbDateRangePicker}
	 * @param $attribute - атрибут модели
	 * @return string - {@link TbDateRangePicker}
	 */
	public function getPeriodFilter($attribute)
	{
		return Yii::app()->controller->widget('bootstrap.widgets.TbDateRangePicker', array(
			'model' => $this,
			'attribute' => $attribute,
			'callback' => 'js:function(start, end){
					console.log($($(this)[0].element[0]).trigger("change"));
				}',
			'options' => array(
				'format' => Yii::app()->params['dateFormatForDateRange'],
			)
		), true);
	}

	/**
	 * Создает фильтр мультиселект
	 * @param $attribute - атрибут модели
	 * @param bool $data - данные для мультиселекта (необязательный параметр)
	 * @return mixed - виджет мультиселекта для фильтра CGridView
	 */
	public function getMultiSelectFilter($attribute, $data = false)
	{
		// Если нам не передали данные - пытаемся найти их с помощью стандартного метода
		if (!$data) {
			$data = $this->getMultiSelectsData($attribute);
		}

		return Yii::app()->controller->widget('bootstrap.widgets.TbSelect2', array(
			'model' => $this,
			'attribute' => $attribute,
			'data' => $data,
			'val' => isset($this->$attribute) ? $this->$attribute : '',
			'htmlOptions' => array(
				'multiple' => 'multiple'
			)
		), true);
	}

	/**
	 * Обрабатывает пришедший период дат
	 * (строку вида "05.10.2012 - 08.12.2013")
	 * в валидную CDbCriteria
	 *
	 * @param $criteria CDbCriteria - передаем текущую критерию
	 * @param $attribute
	 * @internal param string $attibute - имя атрибута модели
	 * @return CDbCriteria - критерию
	 */
	public function getSearchDate($criteria, $attribute)
	{
		// Разбиваем пришедшую строку на 2 элемента
		$data = explode('-', $this->$attribute);
		$timestamp_date = Yii::app()->params['timestamp_date'];
		// Если нам пришел период
		if (count($data) == 2) {
			// Преобразуем критерию в период
			$from = date($timestamp_date, strtotime($data[0]));
			$to = date($timestamp_date, strtotime($data[1]));
			$criteria->addCondition($attribute . " >= '" . $from . " 00:00:00'");
			$criteria->addCondition($attribute . " <= '" . $to . " 23:59:59'");
		}
		else if (count($data) > 0 && !empty($data[0])) {

			// Если простая дата - делаем простой период в рамках одной даты
			$day = date($timestamp_date, strtotime($data[0]));
			$criteria->addCondition($attribute . " >= '" . $day . " 00:00:00'");
			$criteria->addCondition($attribute . " <= '" . $day . " 23:59:59'");
		}

		return $criteria;
	}
}