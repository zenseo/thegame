<?php
/**
 * Разные полезные утилиты
 */
class Utilities
{
	/**
	 * @todo - Храниение сессий в редис
	 * @todo - Брендирование и изменение домена
	 */

	/**
	 * Возвращает массив данных для селекта
	 * Да Нет
	 * @return array
	 */
	public static function YesNo()
	{
		return array(
			1 => 'Да',
			0 => 'Нет'
		);
	}

	/**
	 * Возвращает массив данных для селекта
	 * Мужской Женский
	 * @return array
	 */
	public static function ManWoman($variant = 1)
	{
		switch ($variant) {
			case 1:
				$data = array(
					1 => 'Мужской',
					0 => 'Женский'
				);
				break;
			case 2:
				$data = array(
					1 => 'Мужчина',
					0 => 'Женщина'
				);
				break;
			case 3:
				$data = array(
					1 => 'Муж.',
					0 => 'Жен.'
				);
				break;
			default:
				$data = false;
				break;
		}
		return $data;
	}


	/**
	 * Превращает русский текст в транслит
	 * @param $russian_text - входной русский текст
	 * @return string - выходит транслит
	 */
	public static function transliteration($russian_text)
	{
		$transliteration = array(
			"а" => "a",
			"б" => "b",
			"в" => "v",
			"г" => "g",
			"д" => "d",
			"е" => "e",
			"ё" => "yo",
			"ж" => "j",
			"з" => "z",
			"и" => "i",
			"й" => "i",
			"к" => "k",
			"л" => "l",
			"м" => "m",
			"н" => "n",
			"о" => "o",
			"п" => "p",
			"р" => "r",
			"с" => "s",
			"т" => "t",
			"у" => "u",
			"ф" => "f",
			"х" => "h",
			"ц" => "c",
			"ч" => "ch",
			"ш" => "sh",
			"щ" => "sh",
			"ы" => "i",
			"э" => "e",
			"ю" => "u",
			"я" => "ya",
			"А" => "A",
			"Б" => "B",
			"В" => "V",
			"Г" => "G",
			"Д" => "D",
			"Е" => "E",
			"Ё" => "Yo",
			"Ж" => "J",
			"З" => "Z",
			"И" => "I",
			"Й" => "I",
			"К" => "K",
			"Л" => "L",
			"М" => "M",
			"Н" => "N",
			"О" => "O",
			"П" => "P",
			"Р" => "R",
			"С" => "S",
			"Т" => "T",
			"У" => "U",
			"Ф" => "F",
			"Х" => "H",
			"Ц" => "C",
			"Ч" => "Ch",
			"Ш" => "Sh",
			"Щ" => "Sh",
			"Ы" => "I",
			"Э" => "E",
			"Ю" => "U",
			"Я" => "Ya",
			"ь" => "",
			"Ь" => "",
			"ъ" => "",
			"Ъ" => ""
		);
		$output = strtr($russian_text, $transliteration);

		return $output;
	}

	/**
	 * Ищет строку, число или пару ключ=>значение в многомерном массиве
	 * @param string || integer || array $needle - значение, по которому будет производиться поиск
	 * может быть массивом с одним элементовм ключ => значение
	 * может быть строкой или числом
	 * @param array $array - массив, в котором будет производиться поиск
	 * @param string $children_item_name - название элемента массива, содержащего "детей"
	 * @return array - возвращает подмассив в котором был найден элемент
	 */
	public function multiArraySearch($needle, $array, $children_item_name = 'children')
	{
		// Если то, что мы ищем - это пара ключ=>значение
		if (is_array($needle)) {
			$search = array();
			foreach ($needle as $key => $value) {
				$search['key'] = $key;
				$search['value'] = $value;
			}
			// Прогоняем массив через вложенный цикл поиска по совпадению пары
			foreach ($array as $item) {
				foreach ($item as $key => $value) {
					if ($key == $search['key'] && $value == $search['value']) {
						return $item;
					}
				};
				// Не нашли? Не беда - продолжаем наши скитания по массиву...
				// Не забыв, конечно, передать туда в качестве массива ребенка
				if (isset($item[$children_item_name]) && is_array($item[$children_item_name])) {
					$find = $this->multiArraySearch($needle, $item[$children_item_name], $children_item_name);
					if ($find) {
						return $find;
					}
				}
			}
		}
		else {
			// Почти все то же самое только чутка попроще
			foreach ($array as $a) {
				if (array_search($needle, $a)) {
					return $a;
				}
				elseif (isset($a[$children_item_name]) && is_array($a[$children_item_name])) {
					$this->multiArraySearch($needle, $a[$children_item_name], $children_item_name);
				}
			}
		}
	}


	/**
	 * Метод который возвращает многомерный иерархичный массив,
	 * если ему передать одномерный массив, который содержит в себе ссылки типа parent_id
	 *
	 * Пример:
	 * Входные данные:
	 * array(
	 *        array(id => 3, parent_id => 0, data => 'Смысловая нагрузка'),
	 *        array(id => 1, parent_id => 0, data => 'Название чего-нибудь'),
	 *        array(id => 2, parent_id => 1, data => 'Грандиозный план...')
	 * );
	 *
	 * Выходные данные:
	 * array(
	 *        id => 1,
	 *        data => 'Название чего-нибудь',
	 *        children = array(
	 *            id => 2,
	 *            data => 'Грандиозный план...'
	 *        )
	 * ),
	 * array(id => 3,
	 *        data => 'Смысловая нагрузка')
	 * );
	 *
	 * @param $array - массив, который будем преобразовывать
	 * @param string $id - название элемента массива -- идентификатора
	 * @param string $parent - название элемента массива, откуда брать родителей
	 * @param string $children - название элемента массива, куда будем класть детей
	 * @return array - многомерный массив-дерево
	 */
	public static function toTree($array, $id = 'id', $parent = 'parent', $children = 'children')
	{
		$links = array();
		$tree = array();
		// Запускаем цикл
		for ($i = 0; $i < count($array); $i++) {
			$item = $array[$i]; // Для сокращения кода
			if (!$item[$parent]) { // Если у элемента нет родителя
				$tree[$item[$id]] = $item; // Создаем в выходном массиве элемент верхнего уровня
				if (isset($links[$item[$id]][$children])) { // Проверяем, если у элемента уже есть экземпляр в ссылках
					$tree[$item[$id]][$children] = $links[$item[$id]][$children]; // Записываем в выходной массив список этих элементов
				}
				$links[$item[$id]] = & $tree[$item[$id]]; //Создаем ссылку на этот элемент из выходного массива
			}
			else {
				$links[$item[$parent]][$children][$item[$id]] = $item; // Создаем элемент списка уже включающий в себя родителя
				if (isset($links[$item[$id]][$children])) { //Если у этого есть дети
					$links[$item[$parent]][$children][$item[$id]][$children] = $links[$item[$id]][$children]; //Копируем их к в древовидную структуру
				}
				$links[$item[$id]] = & $links[$item[$parent]][$children][$item[$id]]; // Создаем ссылку на этот элемент
			}
		}

		// На выходе получаем иерархичный многомерный массив
		return $tree;
	}

	/**
	 * Преобразует первую букву в uppercase для русского utf-8
	 *
	 * @param $string - какую строку нужно сконвертить
	 * @param string $e - настройка кодировки
	 * @return string - возвращаемая строка
	 */
	public static function utfUcfirst($string, $e = 'utf-8')
	{
		if (function_exists('mb_strtoupper') && function_exists('mb_substr') && !empty($string)) {
			$string = mb_strtolower($string, $e);
			$upper = mb_strtoupper($string, $e);
			preg_match('#(.)#us', $upper, $matches);
			$string = $matches[1] . mb_substr($string, 1, mb_strlen($string, $e), $e);
		}
		else {
			$string = ucfirst($string);
		}

		return $string;
	}


	/**
	 * Проверяет верно ли скобочное выражение
	 * для проверки используется стек.
	 * @param string $data входные данные
	 * @return bool
	 */
	public function isCorrect($data){
		$stack = array();
		$brackets = array(
			'(' => ')',
			'{' => '}'
		);
		for ($i = 0; $i < strlen($data); $i++) {
			$symbol = $data[$i];
			if(isset($brackets[$symbol])){
				array_unshift($stack, $symbol);
			}else{
				if(!count($stack))return false;
				$last = array_shift($stack);
				if($brackets[$last] !== $symbol){
					return false;
				}
			}
		}

		return true;
	}// Могу пояснить каждую строчку в этой функции. Писал сам.
}