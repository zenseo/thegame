<?php
/**
 * Модальное окно с формой создания нового контакта
 *
 * @var $model Contact
 * @var $customer Customer
 * @var $form TbActiveForm
 * @var $this ContactController
 */
?>

<div class="modal-header">
	<a class="close" onclick="closeModal(this)">&times;</a>
	<h4>Просмотр контакта</h4>
</div>

<div class="modal-body">
	<div class="row-fluid">
		<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
			'id' => 'view_contact_form',
			'type' => 'horizontal',
			'action' => '/contact/update/' . $model->id,
			'method' => 'post',
			'enableAjaxValidation' => false,
		));
		echo $form->hiddenField($model, 'id');
		echo $form->hiddenField($model, 'customer_id');
		?>

		<?php echo $form->textFieldRow($model, 'lastname', array(
			'class' => 'span8',
			'maxlength' => 150
		)); ?>
		<?php echo $form->textFieldRow($model, 'firstname', array(
			'class' => 'span8',
			'maxlength' => 150
		)); ?>
		<?php echo $form->textFieldRow($model, 'surename', array(
			'class' => 'span8',
			'maxlength' => 150
		)); ?>
		<?php echo $form->textFieldRow($model, 'phone', array(
			'class' => 'span8',
			'maxlength' => 150
		)); ?>
		<?php echo $form->textFieldRow($model, 'icq', array('class' => 'span8')); ?>
		<?php echo $form->textFieldRow($model, 'email', array(
			'class' => 'span8',
			'maxlength' => 150
		)); ?>
		<?php echo $form->textFieldRow($model, 'icq', array('class' => 'span8')); ?>
		<?php echo $form->textAreaRow($model, 'comment', array(
			'rows' => 3,
			'class' => 'span8'
		)); ?>
		<?php $this->endWidget(); ?>
	</div>
</div>

<div class="modal-footer">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type' => 'primary',
		'label' => 'Сохранить',
		'url' => '#',
		'htmlOptions' => array('onclick' => "updateContact();return false;"),
	)); ?>
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'label' => 'Закрыть',
		'url' => '#',
		'htmlOptions' => array('onclick' => 'closeModal(this);return false;'),
	)); ?>
</div>
<script>
	/**
	 * Обновляет контакт
	 */
	function updateContact() {
		ajaxForm('view_contact_form', 'Обновляю контакт', function () {
			$('#view_contact_modal').modal('hide');
			updateGrid('customer_contacts_grid');
			showMessage('success', this.message);
		});
	}
</script>
