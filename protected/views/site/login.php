<?php
$this->pageTitle = Yii::app()->name . ' - Авторизация';
$this->breadcrumbs = array(
	'login',
);
?>
<div class="span8"></div>
<div class="span4">
	<?php
	/** @var TbActiveForm $form */
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id' => 'login_form',
		'htmlOptions' => array('class' => 'well'),
		// for inset effect
	));
	echo $form->textFieldRow($model, 'login', array('class' => 'span3'));
	echo $form->passwordFieldRow($model, 'password', array('class' => 'span3'));
	echo $form->checkboxRow($model, 'remember_me');
	$this->widget('bootstrap.widgets.TbButton', array(
		'buttonType' => 'submit',
		'label' => 'Войти'
	));
	$this->endWidget();
	unset($form);
	?>
</div>