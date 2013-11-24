<?php
/**
 * Модальное окно с формой создания нового контакта
 *
 * @var $model Contact
 * @var $customer Customer
 * @var $form TbActiveForm
 * @var $this ContactController
 */

$this->beginWidget('bootstrap.widgets.TbModal', array(
	'id' => 'add_contact_modal',
	'htmlOptions' => array('class' => 'small_modal')
)); ?>

	<div class="modal-header">
		<a class="close" onclick="closeModal(this)">&times;</a>
		<h4>Добавить контакт</h4>
	</div>

	<div class="modal-body">
		<div class="row-fluid">
			<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
				'id' => 'add_contact_form',
				'type' => 'horizontal',
				'action' => '/contact/create',
				'method' => 'post',
				'enableAjaxValidation' => false,
			));
			$model->customer_id = $customer->id;
			echo $form->hiddenField($model, 'customer_id')
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
			'label' => 'Добавить',
			'url' => '#',
			'htmlOptions' => array('onclick' => "addNewContact();return false;"),
		)); ?>
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'label' => 'Отмена',
			'url' => '#',
			'htmlOptions' => array('onclick' => 'closeModal(this);return false;'),
		)); ?>
	</div>
	<script>
		/**
		 * Добавляет новый контакт
		 */
		function addNewContact() {
			ajaxForm('add_contact_form', 'Добавляю новый контакт', function () {
				$('#add_contact_modal').modal('hide');
				updateGrid('customer_contacts_grid');
				showMessage('success', 'Контакт добавлен!');
			});
		}
	</script>

<?php $this->endWidget(); ?>