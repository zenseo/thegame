<?php
/**
 * Модальное окно с формой создания новой записи
 *
 * @var $model Customer
 * @var $form TbActiveForm
 * @var $this CustomerController
 */

$this->beginWidget('bootstrap.widgets.TbModal', array(
	'id' => 'add_customer_modal',
	'htmlOptions' => array('class' => 'middle_modal')
)); ?>

	<div class="modal-header">
		<a class="close" onclick="closeModal(this)">&times;</a>
		<h4>Добавить запись</h4>
	</div>

	<div class="modal-body">
		<div class="row-fluid">
			<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
				'id' => 'add_customer_form',
				'type' => 'horizontal',
				'action' => '/customer/create',
				'method' => 'post',
				'enableAjaxValidation' => false,
			)); ?>
			<?php echo $form->textFieldRow($model, 'name', array(
				'class' => 'span5',
				'maxlength' => 255
			)); ?>
			<?php echo $form->textFieldRow($model, 'phone', array(
				'class' => 'span5',
				'maxlength' => 255
			)); ?>
			<?php echo $form->textFieldRow($model, 'email', array(
				'class' => 'span5',
				'maxlength' => 255
			)); ?>
			<?php echo $form->textFieldRow($model, 'address', array(
				'class' => 'span5',
				'maxlength' => 255
			)); ?>
			<?php echo $form->textFieldRow($model, 'gis_id', array(
				'class' => 'span5',
				'maxlength' => 20
			)); ?>
			<?php echo $form->textFieldRow($model, 'created', array('class' => 'span5')); ?>
			<?php echo $form->textFieldRow($model, 'updated', array('class' => 'span5')); ?>
			<?php echo $form->textFieldRow($model, 'responsible', array('class' => 'span5')); ?>
			<?php echo $form->textAreaRow($model, 'note', array(
				'rows' => 6,
				'cols' => 50,
				'class' => 'span8'
			)); ?>
			<?php echo $form->textFieldRow($model, 'in_work', array('class' => 'span5')); ?>
			<?php echo $form->textFieldRow($model, 'removed', array('class' => 'span5')); ?>
			<?php echo $form->textFieldRow($model, 'sales_status', array('class' => 'span5')); ?>
			<?php echo $form->textFieldRow($model, 'creator', array('class' => 'span5')); ?>
			<?php echo $form->textFieldRow($model, 'updater', array('class' => 'span5')); ?>

			<?php $this->endWidget(); ?>
		</div>
	</div>

	<div class="modal-footer">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'type' => 'primary',
			'label' => 'Добавить',
			'url' => '#',
			'htmlOptions' => array('onclick' => "ajaxForm('add_customer_form');return false;"),
		)); ?>
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'label' => 'Отмена',
			'url' => '#',
			'htmlOptions' => array('onclick' => 'closeModal(this);return false;'),
		)); ?>
	</div>

<?php $this->endWidget(); ?>