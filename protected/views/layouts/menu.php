<?php
// задаем действие пользователя 'Выход'
$user_action = array(
	array(
		'label' => 'Выход',
		'url' => Yii::app()->CreateUrl('site/logout')
	)
);

// формирование главного меню
$this->widget('bootstrap.widgets.TbNavbar', array(
	'id' => 'main_menu',
	'fluid' => true,
	// картинка бренда прописана по абсолютному пути, из-за mod_rewrite
	'brand' => '<img src="/images/logo.png">',
	'items' => array(
		array(
			'class' => 'bootstrap.widgets.TbMenu',
			'items' => MainMenu::getMenu()
		),

		//правый блок
		array(
			'class' => 'bootstrap.widgets.TbMenu',
			'htmlOptions' => array('class' => 'pull-right'),
			'encodeLabel' => false,
			'items' => array(
				array(
					'label' => '<i class="fa fa-user"></i>' . (Yii::app()->user->isGuest ? 'Гость'
						: Yii::app()->user->fullname),
					'url' => '',
					'items' => array(
						array(
							'label' => '<i class="fa fa-user"></i>Профиль',
							'url' => Yii::app()->CreateUrl('user/'.Yii::app()->user->id)
						),
						array(
							'label' => '<i class="fa fa-info"></i>Помощь',
							'url' => Yii::app()->CreateUrl('site/logout')
						),
						array(
							'label' => '<i class="fa fa-comments-o"></i>Обратная связь',
							'url' => Yii::app()->CreateUrl('site/logout')
						),
						array(
							'label' => '<i class="fa fa-power-off"></i>Выход',
							'url' => Yii::app()->CreateUrl('site/logout')
						)
					)
				),
			)
		)
	)
));


?>

