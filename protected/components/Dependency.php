<?php

class Dependency implements ICacheDependency
{
	// Список тегов, поступивших в конструкторе
	public $_tags = null;

	// Ссылка на объект реализующий интерфейс \ICache
	public $_backend;

	// Ассоциативный массив версий тегов
	public $_tag_versions = null;

	/**
	 * Принимает на вход кучу тегов, которыми помечается кеш
	 */
	function __construct(array $tags)
	{
		$this->_tags = $tags;
	}

	function initBackend()
	{
		$this->_backend = Yii::app()->cache;
	}

	/**
	 * Этот метод вызывается до сохранения данных в кеш.
	 * В нём мы устанавливаем версии тегов указанных в
	 * конструкторе и затем сохраненных в property:_tags
	 */
	public function evaluateDependency()
	{
		$this->initBackend();
		$this->_tag_versions = null;

		if ($this->_tags === null || !is_array($this->_tags)) {
			return;
		}

		if (!$this->_backend) {
			return;
		}

		$tagsWithVersion = array();

		foreach ($this->_tags as $tag) {
			foreach ($tag as $t) {
				$mangledTag = Helper::mangleTag($t);
				$tagVersion = $this->_backend->get($mangledTag);
				if ($tagVersion === false) {
					$tagVersion = Helper::generateNewTagVersion();
					$this->_backend->set($mangledTag, $tagVersion, 0);
				}
				$tagsWithVersion[$t] = $tagVersion;
			}
		}

		$this->_tag_versions = $tagsWithVersion;

		return;
	}

	/**
	 * Возвращает true, если данные кеша устарели
	 */
	public function getHasChanged()
	{
		$this->initBackend();

		if ($this->_tag_versions === null || !is_array($this->_tag_versions)) {
			return true;
		}

		// Выдергиваем текущие версии тегов сохраненных с записью в кеше
		$allMangledTagValues = $this->_backend->mget(Helper::mangleTags(array_keys($this->_tag_versions)));
		// Перебираем теги сохраненные в dependency. Т.е. здесь
		foreach ($this->_tag_versions as $tag => $savedTagVersion) {

			$mangleTag = Helper::mangleTag($tag);

			// Тег мог "протухнуть", тогда считаем кеш измененным
			if (!isset($allMangledTagValues[$mangleTag])) {
				return true;
			}

			$actualTagVersion = $allMangledTagValues[$mangleTag];

			// Если сменилась версия тега, то кеш изменили
			if ($actualTagVersion !== $savedTagVersion) {
				return true;
			}
		}

		return false;
	}
}
