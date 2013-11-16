<?php
/**
 * Модальное окно с формой создания новой записи
 *
 * @var $model ModelClass
 * @var $form TbActiveForm
 * @var $this ControllerClass
 */

$this->beginWidget('bootstrap.widgets.TbModal', array(
	'id' => 'add_LowerMClass_modal',
	'htmlOptions' => array('class' => 'middle_modal')
)); ?>

	<div class="modal-header">
		<a class="close" onclick="closeModal(this)">&times;</a>
		<h4>Добавить запись</h4>
	</div>

	<div class="modal-body">
		<div class="row-fluid">
			<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
				'id' => 'add_LowerMClass_form',
				'type' => 'horizontal',
				'action' => '/LowerMClass/create',
				'method' => 'post',
				'enableAjaxValidation' => false,
			)); ?>
			BootstrapForm
			<?php $this->endWidget(); ?>
		</div>
	</div>

	<div class="modal-footer">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'type' => 'primary',
			'label' => 'Добавить',
			'url' => '#',
			'htmlOptions' => array('onclick' => "ajaxForm('add_LowerMClass_form');return false;"),
		)); ?>
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'label' => 'Отмена',
			'url' => '#',
			'htmlOptions' => array('onclick' => 'closeModal(this);return false;'),
		)); ?>
	</div>

<?php $this->endWidget(); ?>