<?php
// Выбираем режим работы приложения.
// Режим работы приложения задается константой YII_APP_MODE
// (она в тупую задефайнина в index.php)

// В условии ниже описаны различия конфигурации PROD и DEV

// Настройки приложения вынесены вверх для удобства редактирования
$params = array(
	'defaultPageSize' => 50,
	'theme' => 'default',
	'adminEmail' => 'agilovr@gmail.com',
	'dateFormat' => 'd.m.Y',
	'dateFormatForDateRange' => 'DD.MM.YYYY',
	'timestamp' => 'Y-m-d H:i:s',
	'timestamp_date' => 'Y-m-d',
	'timestamp_time' => 'H:i:s',
	'datetimeFormat' => 'd.m.Y H:i',
	'his' => 'H:i:s',
	'hi' => 'H:i',
	'user_photos_path' => UPLOADS . '/user_photos/',
	'user_photos_base_url' => '/uploads/' . DB . '/user_photos/',
	'files' => UPLOADS . '/files',
);


if (YII_APP_MODE == 'dev') {
	// Подключение к базе данных для разработки
	$db_connection = array(
		'enableProfiling' => true,
		'enableParamLogging' => true,
		'connectionString' => 'mysql:host=localhost;dbname=' . DB,
		'username' => DB,
		'password' => DB,
		'charset' => 'utf8',
	);
	$modules = array(
		'gii' => array(
			'class' => 'system.gii.GiiModule',
			'password' => 'asdasd',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters' => array(
				'127.0.0.1',
				'::1'
			),
			'newFileMode' => 0644,
			'newDirMode' => 0755,
			'generatorPaths' => array(
				'bootstrap.gii'
			),
		),
	);
}
else {
	// Подключение к продакшн базе данных
	$db_connection = array(
		// выключаем профайлер
		'enableProfiling' => false,
		// не показываем значения параметров
		'enableParamLogging' => false,
		'connectionString' => 'mysql:host=localhost;dbname=' . DB,
		'username' => DB,
		'password' => DB,
		'charset' => 'utf8'
	);
	$modules = array();
}

$config = array(
	'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
	'name' => 'The Game 2',
	'preload' => array(
		'bootstrap'
	),
	'import' => array(
		'application.models.*',
		'application.components.*',
		'application.extensions.CAdvancedArBehavior',
		// Поведение для автоматического сохранения связанных данных в модели
	),
	'aliases' => array(
		//If you manually installed it
		'xupload' => 'ext.xupload'
	),
	'defaultController' => 'site/index',
	'modules' => $modules,
	'components' => array(
		'user' => array(
			'allowAutoLogin' => true,
			'class' => 'WebUser',
			'loginUrl' => array('site/login'),
		),
		// Настройки сессий
		'session' => array(
			'class' => 'system.web.CDbHttpSession',
			'connectionID' => 'db',
			'sessionTableName' => 'session',
			// Табличка
			'autoCreateSessionTable' => false
			// Отключение автосоздания таблицы для сессий
		),
		'urlManager' => array(
			'urlFormat' => 'path',
			'showScriptName' => false,
			'rules' => array(
				'<controller:\w+>/<id:\d+>' => '<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
			),
		),

		'db' => $db_connection,
		'errorHandler' => array(
			'errorAction' => 'site/error',
		),
		// Настройки менеджера авторизации
		'authManager' => array(
			// Класс с мелкими настройками унаследованный от СDbAuthManager
			'class' => 'DbAuthManager',
			'connectionID' => 'db',
			// Нужный параметр, необходим для коннекта... =)
			'itemTable' => 'auth_item',
			// Таблица с элементами авторизации
			'itemChildTable' => 'auth_item_child',
			// Таблица с детьми элеменов авторизации
			'assignmentTable' => 'auth_assignment',
			// Таблица с назначениями ролей пользователям
			'defaultRoles' => array('guest'),
			'showErrors' => YII_DEBUG,
		),
		// Включаем Yii Booster
		'bootstrap' => array(
			'class' => 'ext.bootstrap.components.Bootstrap',
			'responsiveCss' => true,
		),
		'clientScript' => array(
			'scriptMap' => array(
				'jquery.js' => '/js/jquery-2.0.3.min.js',
			)
		)
	),
	'params' => $params
);
return $config;
