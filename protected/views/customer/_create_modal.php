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
	'htmlOptions' => array('class' => 'small_modal')
)); ?>

	<div class="modal-header">
		<a class="close" onclick="closeModal(this)">&times;</a>
		<h4>Добавить клиента</h4>
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
				'class' => 'span11',
				'maxlength' => 255
			)); ?>
			<div class="control-group ">
				<label class="control-label" for="responsible_id_user">
					<?php echo $model->getAttributeLabel('responsible_id'); ?>
				</label>

				<div class="controls">
					<?php
					$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
						'name' => 'responsible',
						// атрибут модели
						'source' => 'js:function(request, response) {
                            jQuery.getJSON("/user/autoCompleteUser", {
                            term: request.term}, response)
                        }',
						'value' => $model->responsible_id ? $model->responsible->full_name : '',
						'options' => array(
							'minLength' => '3',
							'showAnim' => 'fold',
							'select' => 'js: function(event, ui) {
                            this.value = ui.item.label;
                            $("#responsible_id").val(ui.item.id)
                            return false;
                        }',
						),
						'htmlOptions' => array(
							'class' => 'span11',
						),
					));

					echo $form->hiddenField($model, 'responsible_id', array('id' => 'responsible_id')); ?>                    </div>
			</div>
			<?php echo $form->textFieldRow($model, 'phone', array(
				'class' => 'span11',
				'maxlength' => 255
			)); ?>
			<?php echo $form->textFieldRow($model, 'email', array(
				'class' => 'span11',
				'maxlength' => 255
			)); ?>
			<?php echo $form->textFieldRow($model, 'address', array(
				'class' => 'span11',
				'maxlength' => 255
			)); ?>
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