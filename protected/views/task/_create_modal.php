<?php
/**
 * Модальное окно с формой создания новой записи
 *
 * @var $model Task
 * @var $form TbActiveForm
 * @var $this TaskController
 */

$this->beginWidget('bootstrap.widgets.TbModal', array(
	'id' => 'add_task_modal',
	'htmlOptions' => array('class' => 'middle_modal')
)); ?>

	<div class="modal-header">
		<a class="close" onclick="closeModal(this)">&times;</a>
		<h4>Добавить запись</h4>
	</div>

	<div class="modal-body">
		<div class="row-fluid">
			<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
				'id' => 'add_task_form',
				'type' => 'horizontal',
				'action' => '/task/create',
				'method' => 'post',
				'enableAjaxValidation' => false,
			)); ?>
			<?php echo $form->textFieldRow($model,'user_id',array('class'=>'span5')); ?>
			<?php echo $form->textFieldRow($model,'customer_id',array('class'=>'span5')); ?>
			<?php echo $form->textFieldRow($model,'type_id',array('class'=>'span5')); ?>
			<?php echo $form->textFieldRow($model,'date_start',array('class'=>'span5')); ?>
			<?php echo $form->textFieldRow($model,'time_start',array('class'=>'span5')); ?>
			<?php echo $form->textFieldRow($model,'goal_id',array('class'=>'span5')); ?>
			<?php echo $form->textFieldRow($model,'custom_goal',array('class'=>'span5','maxlength'=>255)); ?>
			<?php $this->endWidget(); ?>
		</div>
	</div>

	<div class="modal-footer">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'type' => 'primary',
			'label' => 'Добавить',
			'url' => '#',
			'htmlOptions' => array('onclick' => "ajaxForm('add_task_form');return false;"),
		)); ?>
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'label' => 'Отмена',
			'url' => '#',
			'htmlOptions' => array('onclick' => 'closeModal(this);return false;'),
		)); ?>
	</div>

<?php $this->endWidget(); ?>