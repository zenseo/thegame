<?php
/**
 * Класс-помощник с операциями файлов
 */

class FileHelper
{
	/**
	 * Просматривает директорию и выдает список файлов
	 *
	 * @param $path папка, которую нужно просканировать
	 * @return array список файлов в директории
	 */
	public function listenDirectory($path)
	{
		$dir = opendir($path);
		$files = array();
		while ($file = readdir($dir)) {
			if (($file != ".") && ($file != "..")) {
				$files[] = $file;
			}
		}
		closedir($dir);

		return $files;
	}

	/**
	 * Удаляет файл
	 *
	 * @param $file файл, который нужно удалить
	 * @return bool правда или ложь в зависимости от того удался файл или нет
	 */
	public function deleteFile($file)
	{
		if (file_exists($file)) {
			if (unlink($file)) {
				return true;
			}
			else {
				return false;
			}
		}
	}
}