<?php
/**
 * Манипуляции с картинками
 * Для работы этого класса необходимо расширение gd для php
 */

class SuperImage
{

	/**
	 * @var integer Качество изображения JPEG
	 */
	public $jpeg_quality = 100;

	/**
	 * @var integer Уровень сжатия PNG (0 = без компрессии).
	 */
	public $png_compression = 0;


	/**
	 * @var array Доступные расширения изображений для загрузки
	 */
	public $extensions = array(
		'jpg',
		'png'
	);

	/**
	 * @var integer Ограничение размера изображения
	 */
	public $max_size = 3145728; // 3Мб

	/**
	 * @var resource Картинка
	 */
	public $image;

	/**
	 * @var string Абсолютный путь до файла картники
	 */
	public $path;


	/**
	 * Проверяет файл
	 * @param $file
	 * @throws Exception
	 */
	public function validate($file)
	{
		$ext = strtolower(CFileHelper::getExtension($file['name']));
		if (!in_array($ext, $this->extensions)) {
			throw new Exception("Нельзя загружать файлы формата " . $ext);
		}
		if ($file['size'] > ($this->max_size)) {
			throw new Exception("Максимальный размер изображения " . $this->max_size / (1024 * 1024) . " MB");
		}
	}


	/**
	 * Загружает изображение на сервер
	 *
	 * @param $file файл из массива $_FILES
	 * @param $path куда загрузить
	 * @param string $as сохранить как
	 * @return array информация о загруженном файле
	 */
	public function upload($file, $path, $as = '')
	{
		$this->validate($file);
		$ext = strtolower(CFileHelper::getExtension($file['name']));
		if ($as) {
			$image = $as . "." . $ext;
		}
		else {
			$image = time() . "." . $ext;
		}

		if (move_uploaded_file($file['tmp_name'], $path . $image)) {
			return array(
				'path' => $path . $image,
				'image' => $image
			);
		}
	}


	/**
	 * Обрезает изображение и сохраняет его на диск
	 *
	 * В эту функцию нужно передать путь до файла, который
	 * будем обрезать и квадрат обрезки (массив с координатами, шириной и высотой квадрата).
	 * Опционально можно указать куда сохранить то, что мы вырезали.
	 * По умолчанию функция перезаписывает файл-источник.
	 *
	 * @param string $src абсолютный путь до файла картинки
	 * @param array $coordinates координаты по которым будем обрезать
	 * Пример массива:
	 *        array(
	 *            'x' => '100',
	 *            'y' => '125',
	 *            'width' => '295.5',
	 *            'height' => '394',
	 *        )
	 * @param string $save_as куда сохранить обрезанное изображение (опционально)
	 * @return bool|string абсолютный путь до обрезанного изображения или false, если ничего не получилось
	 */
	public function crop($src, array $coordinates, $save_as = '')
	{
		if (!strlen($save_as)) { // Если не передан параметр "Сохранить как" ...
			$save_as = $src; // ...перезаписываем источник
		}
		// Получаем тип изображения
		$this->load($src); // Загружаем картинку с диска
		$type = $this->getType();

		// Пишем координаты в переменные покороче
		$x = $coordinates['x'];
		$y = $coordinates['y'];
		$width = $coordinates['width'];
		$height = $coordinates['height'];

		// Создаем болванку изображения
		$tmp = $this->createImage($width, $height, $type);

		// Обрезаем изображение и вставляем результат в болванку, полностью замещаяя ее
		if (!imagecopyresampled($tmp, $this->image, 0, 0, $x, $y, $width, $height, $width, $height)) {
			return false;
		}
		// Сохраняем полученный результат
		$cropped = $this->save($tmp, $type, $save_as);

		return $cropped;
	}

	/**
	 * Создает изображение с учетом типа.
	 *
	 * Если тип изображения PNG - создает новую картинку
	 * с поддержкой прозрачности.
	 *
	 * @param $width ширина
	 * @param $height высота
	 * @param $type тип
	 * @return resource изображение
	 */
	private function createImage($width, $height, $type)
	{
		$image = imagecreatetruecolor($width, $height);
		if ($type == IMAGETYPE_PNG) {
			imagealphablending($image, false);
			$color = imagecolorallocatealpha($image, 0, 0, 0, 127);
			imagefill($image, 0, 0, $color);
			imagesavealpha($image, true);
		}

		return $image;
	}


	/**
	 * Умное изменение размеров изображения
	 *
	 * Изменяет размеры изображения по заданным параметрам
	 * сохраняет прозрачность формата .png, может выводить
	 * результат в браузер или писать на диск и многое другое.
	 *
	 * @param int $width ширина
	 * @param int $height высота
	 * @param bool $proportional сохранять пропорции
	 * @param string $output куда выводить
	 * @param bool $delete_original удалять ли оригинал
	 * @param bool $use_linux_commands использовать команды линукс
	 * @return bool|resource
	 */
	public function smartResize($width = 0, $height = 0, $proportional = true, $output = 'file', $delete_original = true, $use_linux_commands = false)
	{

		if ($height <= 0 && $width <= 0) {
			return false;
		}
		$info = getimagesize($this->path);
		list($width_old, $height_old) = $info;
		$type = $info[2];

		// Вычисление пропорций
		if ($proportional) {
			if ($width == 0) {
				$factor = $height / $height_old;
			}
			elseif ($height == 0) {
				$factor = $width / $width_old;
			}
			else {
				$factor = min($width / $width_old, $height / $height_old);
			}

			$final_width = round($width_old * $factor);
			$final_height = round($height_old * $factor);
		}
		else {
			$final_width = ($width <= 0) ? $width_old : $width;
			$final_height = ($height <= 0) ? $height_old : $height;
		}

		// Загрузка изображения
		$this->load($this->path);

		// Изменение размеров изображения
		$new = $this->createImage($final_width, $final_height, $type);
		imagecopyresampled($new, $this->image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);

		// Удаляем оригинал, если нужно
		if ($delete_original) {
			if ($use_linux_commands) {
				exec('rm ' . $this->image);
			}
			else {
				@unlink($this->image);
			}
		}

		// Выводим результат
		switch (strtolower($output)) {
			case 'browser':
				$mime = image_type_to_mime_type($type);
				header("Content-type: $mime");
				$this->save($new, $type);
				break;
			case 'file':
				$this->save($new, $type, $this->path);
			case 'return':
				return $new;
			default:
				return false;
		}
	}

	/**
	 * Загружает изображение с диска в свойство класса.
	 *
	 * @param string $file [optional] Абсолютный путь до картинки, которую нужно загрузить
	 * @return bool результат загрузки: прошла или нет
	 */
	function load($file = '')
	{
		// Если передан путь до картинки
		if (strlen($file)) { // Перегружает свойсво "Путь до изображения"
			$this->path = $file;
		}
		switch ($this->getType()) {
			case IMAGETYPE_GIF:
				$this->image = imagecreatefromgif($this->path);
				break;
			case IMAGETYPE_JPEG:
				$this->image = imagecreatefromjpeg($this->path);
				break;
			case IMAGETYPE_PNG:
				$this->image = imagecreatefrompng($this->path);
				break;
			default:
				return false;
		}

		return true;
	}

	/**
	 * Выводит изображение в браузер или сохраняет на диск.
	 *
	 * @param resource $image картинка, которую будем сохранять
	 * @param integer $type тип изображения
	 * @param string $filename [optional]  Путь, куда будем сохранять картнику.
	 * Если не задан или null - картинка будет выведена в браузер.
	 * @return bool true если все хорошо, false если что-то не так
	 */
	function save($image, $type, $filename = null)
	{
		switch ($type) {
			case IMAGETYPE_GIF:
				imagegif($image, $filename);
				break;
			case IMAGETYPE_JPEG:
				imagejpeg($image, $filename, $this->jpeg_quality);
				break;
			case IMAGETYPE_PNG:
				imagepng($image, $filename, $this->png_compression);
				break;
			default:
				return false;
		}

		return $filename;
	}


	/**
	 * Получение ширины изображения
	 * @return int Ширина изображения
	 */
	function getWidth()
	{
		return imagesx($this->image);
	}

	/**
	 * Получение высоты изображения
	 * @return int Высота изображения
	 */
	function getHeight()
	{
		return imagesy($this->image);
	}

	/**
	 * Получение типа изображения
	 * @return integer тип изображения (одна из глобальных констант PHP)
	 */
	public function getType()
	{
		$info = getimagesize($this->path);

		return $info[2];
	}

	/**
	 * Изменить до высоты
	 * @param $height высота
	 */
	function resizeToHeight($height)
	{
		$ratio = $height / $this->getHeight();
		$width = $this->width * $ratio;
		$this->resize($width, $height);
	}

	/**
	 * Изменить до ширины
	 * @param $width ширина
	 */
	function resizeToWidth($width)
	{
		$ratio = $width / $this->width;
		$height = $this->height * $ratio;
		$this->resize($width, $height);
	}

	/**
	 * Масштабирование
	 * @param $scale масштаб
	 */
	function scale($scale)
	{
		$width = $this->width * $scale / 100;
		$height = $this->height * $scale / 100;
		$this->resize($width, $height);
	}

	/**
	 * Изменение размера
	 * @param $width ширина
	 * @param $height высота
	 */
	function resize($width, $height)
	{
		$new_image = imagecreatetruecolor($width, $height);
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->width, $this->height);
		$this->image = $new_image;
	}
}