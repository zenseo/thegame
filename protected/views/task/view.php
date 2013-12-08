<?php
/**
 * Просмотр и изменение данных записи
 *
 * @var $model Task
 * @var $this TaskController
 */
$this->breadcrumbs = array(
	"task" => '/task/index',
	$model->id
);
?>

	<h3>task #<?php echo $model->id ?></h3>
<?php
$this->renderPartial('_form', array(
	'model' => $model,
));