<?php

/**
 * Модель для формирования главного меню сайта
 * меню формируется на основе разрешений пользователя
 */
class Dictionary extends ActiveRecord
{
	/**
	 * @var - Табличка текущего словаря
	 */
	public $table_name;

	/**
	 * @var - Индикатор, сигнализирующий нам - вытаскивать из
	 * базы только активные записи или нет
	 */
	public $only_active_records;

	/**
	 * Конструируем фабрику словарей
	 * @param string $dictionary - название словаря
	 * @param bool $only_active_records - вытаскивать только активные или нет
	 * @throws Exception
	 */
	public function __construct($dictionary, $only_active_records = true)
	{
		if (!$dictionary) {
			throw new Exception('Для инициализации словаря необходимо указать его название!', 500);
		}
		$this->table_name = 'dictionary_' . $dictionary;
		$this->only_active_records = $only_active_records;
		parent::__construct();
	}

	/**
	 * Переопределение родительской функции findAll()
	 * В зависимости от того нужно там только активные или все записи
	 * мы ищем только активные, либо все =)
	 */
	public function findAll($condition = '', $params = array())
	{
		if ($this->only_active_records) {
			return Yii::app()->db->createCommand()->select('id, name')->from($this->table_name)->where('active=:active', array(':active' => 1))->queryAll();
		}
		else {
			return Yii::app()->db->createCommand()->select('id, name')->from($this->table_name)->queryAll();
		}
	}

	/**
	 * Стартует модель. Не знаю что конкретно делает эта функция
	 * @param string $className
	 * @return CActiveRecord
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Отдает текущую табличку
	 * @return string
	 */
	public function tableName()
	{
		return $this->table_name;
	}
}