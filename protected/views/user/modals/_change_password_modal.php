<?php
/**
 * @var $model User
 * @var $form TbActiveForm
 */

$this->beginWidget('bootstrap.widgets.TbModal', array(
	'id' => 'change_user_password_modal',
	'htmlOptions' => array('class' => 'small_modal')
)); ?>

	<div class="modal-header">
		<a class="close" onclick="closeModal(this)">&times;</a>
		<h4>Смена пароля</h4>
	</div>

	<div class="modal-body">
		<div class="row-fluid">
			<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
				'id' => 'user_change_password_form',
				'type' => 'horizontal',
				'action' => '/user/updatePassword/' . $model->id,
				'enableAjaxValidation' => false,
			)); ?>
			<?php echo $form->passwordFieldRow($model, 'new_password', array(
				'class' => 'span5',
				'name' => 'new_password',
				'maxlength' => 45
			)); ?>

			<?php echo $form->passwordFieldRow($model, 'confirm_password', array(
				'class' => 'span5',
				'name' => 'confirm_password',
				'maxlength' => 45
			)); ?>
			<?php $this->endWidget(); ?>
		</div>
	</div>

	<div class="modal-footer">
		<div class="btn btn-primary"
			 onclick="ajaxForm('user_change_password_form', 'Смена...',function(){
			 	hideModal('change_user_password_modal');
			 	showMessage('success', this.message);
			 })"
			>Сменить пароль
		</div>
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'label' => 'Отмена',
			'url' => '#',
			'htmlOptions' => array('onclick' => 'closeModal(this)'),
		)); ?>
	</div>

<?php $this->endWidget(); ?>