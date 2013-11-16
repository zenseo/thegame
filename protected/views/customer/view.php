<?php
/**
 * Просмотр и изменение данных записи
 *
 * @var $model Customer
 * @var $this CustomerController
 */
$this->breadcrumbs = array(
	"customer" => '/customer/index',
	$model->id
);
?>

	<h3>customer #<?php echo $model->id ?></h3>
<?php
$this->renderPartial('_form', array(
	'model' => $model,
));