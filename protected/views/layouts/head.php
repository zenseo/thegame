<!DOCTYPE html>
<html lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title><?php echo $this->pageTitle; ?></title>
	<meta name="language" content="ru"/>
	<link href="/images/favicon.png" rel="shortcut icon"/>
	<link rel="stylesheet" type="text/less" href="/themes/default/less/master.less?<?php echo time() ?>">
	<?php
	$cs = Yii::app()->getClientScript();

	$cssMap = array(
		'/css/jcrop/jquery.Jcrop.css',
	);

	$jsMap = array(
		'functions.js',
		'ready.js',
		'jquery.cookie.js',
		'less.js',
		'jquery.form.js',
		'jquery.jCrop.js',
	);

	foreach ($cssMap as $css) {
		$cs->registerCssFile(Yii::app()->request->baseUrl . $css);
	}

	foreach ($jsMap as $js) {
		$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/' . $js, CClientScript::POS_END);
	}
	?>
</head>