<?php
/**
 * Генератор контроллера
 */
?>
<?php echo "<?php\n"; ?>

class <?php echo $this->controllerClass; ?> extends <?php echo "Controller\n"; ?>
{

/**
* Displays a particular model.
* @param integer $id the ID of the model to be displayed
*/
public function actionView($id)
{
$this->checkAccess('view<?php echo $this->modelClass; ?>');
$model = $this->loadModel($id);
$this->render('view', array(
'model' => $model,
));
}

/**
* Creates a new model.
* If creation is successful, the browser will be redirected to the 'view' page.
*/
public function actionCreate()
{
$this->checkAccess('create<?php echo $this->modelClass; ?>');
$model=new <?php echo $this->modelClass; ?>;
if (isset($_POST['<?php echo $this->modelClass; ?>'])) {
$model->attributes = $_POST['<?php echo $this->modelClass; ?>'];
if ($model->save()) {
$this->redirect(array(
'view',
'id' => $model->id
));
}
}
if (isset($_GET['<?php echo $this->modelClass; ?>'])) {
$model->attributes = $_GET['<?php echo $this->modelClass; ?>'];
}
$this->render('create', array(
'model' => $model,
));
}


public function actionUpdate($id)
{
$this->checkAccess('update<?php echo $this->modelClass; ?>');

$model = $this->loadModel($id);
if (isset($_POST['<?php echo $this->modelClass; ?>'])) {
$model->attributes = $_POST['<?php echo $this->modelClass; ?>'];
if ($model->save()) {
$this->redirect(array(
'view',
'id' => $model->id
));
}
}

$this->render('update', array(
'model' => $model,
));
}


public function actionDelete($id)
{
$this->checkAccess('delete<?php echo $this->modelClass; ?>');

try {
$this->loadModel($id)->delete();
$this->showMessage('<?php echo $this->modelClass; ?> успешно удален');
}
catch (CDbException $e) {
$this->throwException('CDbException', 'Не удалось удалить <?php echo $this->modelClass; ?>', 500);
}
}


public function actionIndex()
{
$this->checkAccess('index<?php echo $this->modelClass; ?>');
$model = new <?php echo $this->modelClass; ?>('search');
$grid_id = "<?php echo $this->modelClass; ?>_grid";
$model->unsetAttributes(); // clear any default values

if (isset($_GET['pageSize'])) {
Yii::app()->user->setState('pageSize', (int)$_GET['pageSize']);
unset($_GET['pageSize']);
}

if (isset($_GET['<?php echo $this->modelClass; ?>'])) {
$model->attributes = $_GET['<?php echo $this->modelClass; ?>'];
}
else {
if (isset($_COOKIE[$grid_id])) {
$model->attributes = CJSON::decode($_COOKIE[$grid_id]);
}
}

// Получаем список колонок
if(isset($_COOKIE[$grid_id . '_columns'])){
$columns_filter = explode(',', $_COOKIE[$grid_id . '_columns']);
}else{
$columns_filter = $model->attributeDefault();
}

$this->render('index', array(
'model' => $model,
'grid_id' => $grid_id,
'filter' => $columns_filter,
'columns' => $model->columnsGrid($columns_filter)
));
}


/**
* Returns the data model based on the primary key given in the GET variable.
* If the data model is not found, an HTTP exception will be raised.
* @param integer the ID of the model to be loaded
*/
public function loadModel($id)
{
$model=<?php echo $this->modelClass; ?>::model()->findByPk($id);
if($model===null){
$this->throwException('CHttpException','Модель класса <?php echo $this->modelClass; ?> не найдена в базе данных', 404 );
}
return $model;
}

/**
* Performs the AJAX validation.
* @param CModel the model to be validated
*/
protected function performAjaxValidation($model)
{
if(isset($_POST['ajax']) && $_POST['ajax']==='<?php echo strtolower($this->modelClass); ?>_form')
{
echo CActiveForm::validate($model);
Yii::app()->end();
}
}
}
