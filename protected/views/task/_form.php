<?php
/**
 * Модальное окно с формой создания новой записи
 *
 * @var $model Task
 * @var $form TbActiveForm
 * @var $this TaskController
 */
?>


<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id' => 'update_task_form',
	'type' => 'horizontal',
	'action' => '/task/update/' . $model->id,
	'method' => 'post',
	'enableAjaxValidation' => false,
)); ?>

<?php echo $form->textFieldRow($model,'user_id',array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model,'customer_id',array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model,'contact_id',array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model,'type_id',array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model,'status_id',array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model,'date_start',array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model,'time_start',array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model,'goal_id',array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model,'custom_goal',array('class'=>'span5','maxlength'=>255)); ?>
<?php echo $form->textFieldRow($model,'achievement',array('class'=>'span5')); ?>
<?php echo $form->textAreaRow($model,'result',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>
<?php echo $form->textAreaRow($model,'note',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>
<?php echo $form->textFieldRow($model,'created',array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model,'updated',array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model,'creator_id',array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model,'updater_id',array('class'=>'span5')); ?>


<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type' => 'primary',
		'label' => 'Сохранить',
		'url' => '#',
		'htmlOptions' => array('onclick' => "ajaxForm('update_task_form');return false;"),
	)); ?>
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type' => 'danger',
		'label' => 'Удалить',
		'url' => '#',
		'htmlOptions' => array(
			'onclick' => "if(confirm('Вы действиетльно хотите удалить {$model->id}?')){
							simpleJson('/task/delete/{$model->id}', {},
								function(){
									window.location.href = '/task/index';
								});return false;
						}else{
							return false;
						}
						"
		),
	)); ?>
</div>

<?php $this->endWidget(); ?>
