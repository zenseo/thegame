<?php
/**
 * Просмотр и изменение данных записи
 *
 * @var $model Contact
 * @var $this ContactController
 */
$this->breadcrumbs = array(
	"contact" => '/contact/index',
	$model->id
);
?>

	<h3>contact #<?php echo $model->id ?></h3>
<?php
$this->renderPartial('_form', array(
	'model' => $model,
));