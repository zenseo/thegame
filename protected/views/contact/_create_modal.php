<?php
/**
 * Модальное окно с формой создания новой записи
 *
 * @var $model Contact
 * @var $form TbActiveForm
 * @var $this ContactController
 */

$this->beginWidget('bootstrap.widgets.TbModal', array(
	'id' => 'add_contact_modal',
	'htmlOptions' => array('class' => 'middle_modal')
)); ?>

	<div class="modal-header">
		<a class="close" onclick="closeModal(this)">&times;</a>
		<h4>Добавить запись</h4>
	</div>

	<div class="modal-body">
		<div class="row-fluid">
			<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
				'id' => 'add_contact_form',
				'type' => 'horizontal',
				'action' => '/contact/create',
				'method' => 'post',
				'enableAjaxValidation' => false,
			)); ?>
			<?php echo $form->textFieldRow($model,'lastname',array('class'=>'span5','maxlength'=>150)); ?>
<?php echo $form->textFieldRow($model,'firstname',array('class'=>'span5','maxlength'=>150)); ?>
<?php echo $form->textFieldRow($model,'surename',array('class'=>'span5','maxlength'=>150)); ?>
<?php echo $form->textAreaRow($model,'comment',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>
<?php echo $form->textFieldRow($model,'email',array('class'=>'span5','maxlength'=>150)); ?>
<?php echo $form->textFieldRow($model,'icq',array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model,'last_contact',array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model,'created',array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model,'updated',array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model,'creator_id',array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model,'updater_id',array('class'=>'span5')); ?>

			<?php $this->endWidget(); ?>
		</div>
	</div>

	<div class="modal-footer">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'type' => 'primary',
			'label' => 'Добавить',
			'url' => '#',
			'htmlOptions' => array('onclick' => "ajaxForm('add_contact_form');return false;"),
		)); ?>
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'label' => 'Отмена',
			'url' => '#',
			'htmlOptions' => array('onclick' => 'closeModal(this);return false;'),
		)); ?>
	</div>

<?php $this->endWidget(); ?>