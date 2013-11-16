<?php
/**
 * @var $model User
 * @var $form TbActiveForm
 */

$this->beginWidget('bootstrap.widgets.TbModal', array(
	'id' => 'add_user_modal',
	'htmlOptions' => array('class' => 'middle_modal')
)); ?>

	<div class="modal-header">
		<a class="close" onclick="closeModal(this)">&times;</a>
		<h4>Добавить пользователя</h4>
	</div>

	<div class="modal-body">
		<div class="row-fluid">
			<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
				'id' => 'add_user_form',
				'type' => 'horizontal',
				'action' => '/user/create',
				'method' => 'post',
				'enableAjaxValidation' => false,
			)); ?>
			<?php echo $form->textFieldRow($model, 'firstname', array(
				'class' => 'span12',
				'maxlength' => 45
			)); ?>
			<?php echo $form->textFieldRow($model, 'login', array(
				'class' => 'span12',
				'maxlength' => 45
			)); ?>
			<?php echo $form->textFieldRow($model, 'password', array(
				'class' => 'span12',
				'maxlength' => 45
			)); ?>
			<?php echo $form->textFieldRow($model, 'email', array(
				'class' => 'span12',
				'maxlength' => 45
			)); ?>
			<?php echo $form->textFieldRow($model, 'phone', array(
				'class' => 'span12',
				'maxlength' => 45
			)); ?>
			<?php echo $form->dropDownListRow($model, 'gender', $model->getMultiSelectsData('gender'), array(
				'class' => 'span12'
			)); ?>
			<?php echo $form->dropDownListRow($model, 'role', $model->getMultiSelectsData('role'), array(
				'class' => 'span12'
			)); ?>
			<?php $this->endWidget(); ?>
		</div>
	</div>

	<div class="modal-footer">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'type' => 'primary',
			'label' => 'Добавить',
			'url' => '#',
			'htmlOptions' => array('onclick' => "ajaxForm('add_user_form')"),
		)); ?>
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'label' => 'Отмена',
			'url' => '#',
			'htmlOptions' => array('onclick' => 'closeModal(this)'),
		)); ?>
	</div>

<?php $this->endWidget(); ?>