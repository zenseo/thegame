<?php
echo '
<?php
/** 
 * @var $model ' . $this->modelClass . ' 
 * @var $this Controller
 */
$this->breadcrumbs = array(
	"' . $this->modelClass . '" => array("index"),
	"' . $this->modelClass . ' #" . $model->id,
);
$page_title = "' . $this->modelClass . ' #{$model->id}";
$this->pageTitle = $page_title . " - " . Yii::app()->name;
?>
	<h1><?php echo $page_title; ?> </h1>


<?php $this->widget("TbDLView", array(
	"model" => $model,
	"options" => array(
		"title" => "Общая информация",
		"dd_class" => "lead",
	),
	"attributes" => array(
		';

foreach ($this->tableSchema->columns as $column) {
	echo '"' . $column->name . '",
		';

}

?>
<?php echo '),
));'; ?>