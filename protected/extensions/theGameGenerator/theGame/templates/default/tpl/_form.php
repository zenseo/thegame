<?php
/**
 * Модальное окно с формой создания новой записи
 *
 * @var $model ModelClass
 * @var $form TbActiveForm
 * @var $this ControllerClass
 */
?>


<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id' => 'update_LowerMClass_form',
	'type' => 'horizontal',
	'action' => '/LowerMClass/update/'.$model->id,
	'method' => 'post',
	'enableAjaxValidation' => false,
)); ?>

BootstrapForm

<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type' => 'primary',
		'label' => 'Сохранить',
		'url' => '#',
		'htmlOptions' => array('onclick' => "ajaxForm('update_LowerMClass_form');return false;"),
	)); ?>
</div>

<?php $this->endWidget(); ?>
