<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php
echo "<?php\n";
$nameColumn = $this->guessNameColumn($this->tableSchema->columns);
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$this->modelClass'=>array('index'),
	\$model->{$nameColumn},
);\n";
?>
<?php echo "<?php echo \$this->renderPartial('_form',array('model'=>\$model)); ?>"; ?>
