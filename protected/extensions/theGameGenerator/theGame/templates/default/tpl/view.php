<?php
/**
 * Просмотр и изменение данных записи
 *
 * @var $model ModelClass
 * @var $this ControllerClass
 */
$this->breadcrumbs = array(
	"LowerMClass" => '/LowerMClass/index',
	$model->id
);
?>

	<h3>LowerMClass #<?php echo $model->id ?></h3>
<?php
$this->renderPartial('_form', array(
	'model' => $model,
));