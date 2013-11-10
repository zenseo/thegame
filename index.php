<?php
// Устанавливаем пути до файла фреймворка
// фреймворк должен лежать на 1 уровень каталога выше, чем рабочая директория

define('YII_APP_MODE', 'dev');
//define("YII_APP_MODE", "prod");

// Получаем базу данных для подключениея
$server_name_array = explode('.', $_SERVER['SERVER_NAME']);
if (count($server_name_array) > 1) { // Если к нам обратились через домен 3-го уровня
	$db_key = $server_name_array[0]; // Базу данных будем искать по ключу этого поддомена
}
else {
	die('Неверное обращение к серверу!');
}
// Задаем основной ключ приложения (логин макропользователя)
define('DB', $db_key);
// Задаем
define('UPLOADS', dirname(__FILE__) . '/uploads/' . DB);


if (YII_APP_MODE == 'dev') {
	defined('YII_DEBUG') or define('YII_DEBUG', true);
	defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
	error_reporting(E_ALL);
}

// и до файла конфигурации
$config = dirname(__FILE__) . '/protected/config/main.php';

$yii = '../yii/framework/yiilite.php';

// запускаем экземпляр приложения yii
require_once($yii);

Yii::createWebApplication($config)->run();
?>