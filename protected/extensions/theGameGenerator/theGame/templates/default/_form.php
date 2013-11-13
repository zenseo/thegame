<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php echo '
/**
 * @var $model ' . $this->modelClass . '
 * @var $form TbActiveForm
 */';
?>
<?php
echo "<?php \$form=\$this->beginWidget('ext.bootstrap.widgets.TbActiveForm',array(
	'id'=>'" . strtolower($this->modelClass) . "_form',
	'enableAjaxValidation'=>false,
)); ?>\n"; ?>

<p class="help-block">Поля с <span class="required">*</span> обязательны для заполнения.</p>

<?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>

<?php
foreach ($this->tableSchema->columns as $column) {
	if ($column->autoIncrement) {
		continue;
	}
	?>
	<?php echo "<?php echo " . $this->generateActiveRow($this->modelClass, $column) . "; ?>\n"; ?>

<?php
}
?>
<div class="form-actions">
	<?php echo "<?php \$this->widget('ext.bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>\$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>\n"; ?>
</div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>
