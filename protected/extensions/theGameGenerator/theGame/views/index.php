<?php
/**
 * @var CModel $model
 */
$class = get_class($model);
Yii::app()->clientScript->registerScript('gii.crud', "
$('#{$class}_controller').change(function(){
	$(this).data('changed',$(this).val()!='');
});
$('#{$class}_model').bind('keyup change', function(){
	var controller=$('#{$class}_controller');
	if(!controller.data('changed')) {
		var id=new String($(this).val().match(/\\w*$/));
		if(id.length>0)
			id=id.substring(0,1).toLowerCase()+id.substring(1);
		controller.val(id);
	}
});
");
?>
<h1>Генератор Игры</h1>

<p>Этот генератор генерирует год для CRUD операций для заданного AR классаф.</p>

<?php
/** @var CCodeForm $form */
$form = $this->beginWidget('CCodeForm', array('model' => $model)); ?>

<div class="row">
	<?php echo $form->labelEx($model, 'model'); ?>
	<?php echo $form->textField($model, 'model', array('size' => 65)); ?>
	<div class="tooltip">
		Класс чувствителен к регистру.
	</div>
	<?php echo $form->error($model, 'model'); ?>
</div>

<div class="row">
	<?php echo $form->labelEx($model, 'controller'); ?>
	<?php echo $form->textField($model, 'controller', array('size' => 65)); ?>
	<div class="tooltip">
		Контроллер чувствителен к регистру. Контроллеры обычно имеют похожие имена
		с моделями, которые они обслуживают
	</div>
	<?php echo $form->error($model, 'controller'); ?>
</div>

<div class="row sticky">
	<?php echo $form->labelEx($model, 'baseControllerClass'); ?>
	<?php echo $form->textField($model, 'baseControllerClass', array('size' => 65)); ?>
	<div class="tooltip">
		Это клас, который будет расширять будущий контроллер.
	</div>
	<?php echo $form->error($model, 'baseControllerClass'); ?>
</div>

<?php $this->endWidget(); ?>
