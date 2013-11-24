<?php
/**
 * Модальное окно с формой создания новой записи
 *
 * @var $model Contact
 * @var $form TbActiveForm
 * @var $this ContactController
 */
?>


<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id' => 'update_contact_form',
	'type' => 'horizontal',
	'action' => '/contact/update/' . $model->id,
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


<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type' => 'primary',
		'label' => 'Сохранить',
		'url' => '#',
		'htmlOptions' => array('onclick' => "ajaxForm('update_contact_form');return false;"),
	)); ?>
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type' => 'danger',
		'label' => 'Удалить',
		'url' => '#',
		'htmlOptions' => array(
			'onclick' => "if(confirm('Вы действиетльно хотите удалить {$model->id}?')){
							simpleJson('/contact/delete/{$model->id}', {},
								function(){
									window.location.href = '/contact/index';
								});return false;
						}else{
							return false;
						}
						"
		),
	)); ?>
</div>

<?php $this->endWidget(); ?>
