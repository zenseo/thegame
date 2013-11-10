<?php
$this->pageTitle = Yii::app()->name . ' - Главная страница';
$this->breadcrumbs = array(
	'Главная страница'
);
if (!isset($_COOKIE['yaProchel'])) {
	Yii::app()->user->setFlash('error', '<h1>Привет! Это сообщение для тебя!</h1>
		<p>Привет. Мы тут написали для тебя сообщение. Прочти пожалуйста.
		<div data-dismiss="alert" class="btn btn-primary" onclick="thatFuckedWarningIAlreadyReadManyTimesMotherFuckerBeach_oGodFuckThatWebDevelopmentDepartmentPlease()">Мне все понятно <i title="Закрыть сообщение" class="icon-close"></i></div>
		</p>');
}

?>


<!--Вывод общей информации по компаниии-->
<!-- Используется резиновая верска Twitter Bootstrap -->
<div id='main_page'> Главная страница</div>