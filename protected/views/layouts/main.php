<?php
// Если идет аяксовый запрос, то сразу просто отдаем контент без лишней нагрузки на сервер
if (Yii::app()->request->isAjaxRequest) {
	echo $content;
}
else { // Если не аякс, то рисуем верстку
	$this->renderPartial('/layouts/head'); ?>

	<body>
	<!-- Главное меню -->
	<?php $this->renderPartial('/layouts/menu'); ?>
	<?php $this->renderPartial('/layouts/service'); ?>
	<!-- Навигатор -->
	<div class="container-fluid">
		<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
			'homeLink' => '<a href="/site/index">Начало</a>',
			'links' => $this->breadcrumbs
		)); ?>
	</div>
	<!-- Обработчик флешей-->
	<div class="container-fluid">
		<?php
		$this->widget('bootstrap.widgets.TbAlert', array(
			'block' => false,
			'fade' => true,
			'closeText' => '×',
			'alerts' => array(
				'error' => array(
					'block' => true,
					'fade' => true,
					'closeText' => '×'
				),
				'warning' => array(
					'block' => true,
					'fade' => true,
					'closeText' => '×'
				),
				'success' => array(
					'block' => true,
					'fade' => true,
					'closeText' => '×'
				),
			),
		));
		?>
	</div>

	<!-- Основной контент -->
	<div class="container-fluid">
		<?php echo $content; ?>
	</div>
	</body>
	</html>

<?php } ?>