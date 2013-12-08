<?php
$markers = array();
$data = array();
// Больше информации можно получить из var_dump
//var_dump($this);exit;

// Добавляем название класса контроллера в нижнем регистре
$markers[] = 'LowerCClass';
$data[] = strtolower($this->controllerClass);

// Добавляем название класса контроллера для начала
$markers[] = 'ControllerClass';
$data[] = $this->controllerClass;

// Добавляем название класса модели
$markers[] = 'ModelClass';
$data[] = $this->modelClass;

// Добавляем название класса модели в нижнем регистре
$markers[] = 'LowerMClass';
$data[] = strtolower($this->modelClass);

// Загружаем шаблончик
$tpl = file_get_contents(dirname(__FILE__) . '/tpl/_super_json_grid.php');

// Замещаем маркеры и выводим зрителям =)
echo str_replace($markers, $data, $tpl);