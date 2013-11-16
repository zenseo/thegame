<?php
/**
 * Модальное окно с формой создания новой записи
 *
 * @var $model Customer
 * @var $form TbActiveForm
 * @var $this CustomerController
 */
?>


<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id' => 'update_customer_form',
	'type' => 'horizontal',
	'action' => '/customer/update/'.$model->id,
	'method' => 'post',
	'enableAjaxValidation' => false,
)); ?>

<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>255)); ?>
<?php echo $form->textFieldRow($model,'phone',array('class'=>'span5','maxlength'=>255)); ?>
<?php echo $form->textFieldRow($model,'email',array('class'=>'span5','maxlength'=>255)); ?>
<?php echo $form->textFieldRow($model,'address',array('class'=>'span5','maxlength'=>255)); ?>
<?php echo $form->textFieldRow($model,'gis_id',array('class'=>'span5','maxlength'=>20)); ?>
<?php echo $form->textFieldRow($model,'created',array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model,'updated',array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model,'responsible',array('class'=>'span5')); ?>
<?php echo $form->textAreaRow($model,'note',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>
<?php echo $form->textFieldRow($model,'in_work',array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model,'removed',array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model,'sales_status',array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model,'creator',array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model,'updater',array('class'=>'span5')); ?>


<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type' => 'primary',
		'label' => 'Сохранить',
		'url' => '#',
		'htmlOptions' => array('onclick' => "ajaxForm('update_customer_form');return false;"),
	)); ?>
</div>

<?php $this->endWidget(); ?>
