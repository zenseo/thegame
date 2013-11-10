<h1>Тестирование системы</h1>
<?php
$auth = Yii::app()->authManager;
$auth_items = $auth->roles;

$all_roles = array();
$all_roles_name = array();
foreach ($auth_items as $t) {
	$role['id'] = $t->name;
	$role['name'] = $t->description;
	$all_roles_name[$t->name] = $t->description;
	$all_roles[] = $role;
}


$model = User::model()->findByPk(Yii::app()->user->id);
if (isset($_COOKIE['tester_params'])) {
	$params = CJSON::decode($_COOKIE['tester_params']);
	if (isset($params['tester_fake_role'])) {
		if (Yii::app()->user->checkUserAccess('changeOwnRoleTest')) {
			$model->role = $params['tester_fake_role'];
		}
	}
}
?>
<div class="row">
	<?php if (Yii::app()->user->checkPersonalAccess('changeOwnRoleTest')): ?>
		<div class="span6">
			<h3>Изменение своей роли в системе</h3>
			<?php
			$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
				'id' => 'testerForm',
				'type' => 'inline',
			));


			$this->widget('bootstrap.widgets.TbSelect2', array(
				'model' => $model,
				'attribute' => 'role',
				'data' => CHtml::listData($all_roles, 'id', 'name'),
				'htmlOptions' => array(
					'id' => 'tester_fake_role'
				),
			));
			?>

			<?php
			$this->widget('bootstrap.widgets.TbButton', array(
				'label' => 'Сменить роль',
				'type' => 'primary',
				'htmlOptions' => array('onclick' => 'setTesterParams()')
			));
			?>

			<?php
			$this->widget('bootstrap.widgets.TbButton', array(
				'label' => 'Cбросить роль',
				'htmlOptions' => array('onclick' => 'resetTesterParams()')
			));
			$this->endWidget();
			?>
		</div>
	<?php endif; ?>

	<?php if (Yii::app()->user->checkPersonalAccess('changeUserIdTest')): ?>
		<div class="span6">
			<h3>Действия от лица пользователя</h3>
			<?php
			$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
				'id' => 'testerForm',
				'type' => 'inline',
			));

			$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
				'name' => 'CrmContragentName',
				// атрибут модели
				'source' => 'js:function(request, response) {
                            jQuery.getJSON("/user/autoCompleteUser", {
                            term: request.term}, response)
                        }',
				'options' => array(
					'minLength' => '3',
					'showAnim' => 'fold',
					'select' => 'js: function(event, ui) {
                            this.value = ui.item.label;
                            $("#user_login").val(ui.item.login)
                            return false;
                        }',
				),
				'htmlOptions' => array(),
			));
			echo $form->hiddenField($model, 'login', array(
				'id' => 'user_login'
			))

			?>
			<?php
			$this->widget('bootstrap.widgets.TbButton', array(
				'label' => 'Войти в систему',
				'buttonType' => 'submit',
				'type' => 'primary'
			));
			$this->endWidget();
			?>
		</div>
	<?php endif; ?>
</div>
<div class="row">
	<?php if (Yii::app()->user->checkUserAccess('reinstallRbacTest')): ?>
		<div class="span6">
			<h3>Переустановка RBAC</h3>

			<div class="btn btn-primary" onclick="simpleJson('/site/installRbac', {}, window.location.reload())">
				Переустановить RBAC
			</div>
		</div>
	<?php endif; ?>
</div>

<?php
//Class Reference
//CPhpAuthManager
//CPhpAuthManager.authFile
//CPhpAuthManager.authItems
//CPhpAuthManager.addItemChild()
//CPhpAuthManager.assign()
//CPhpAuthManager.checkAccess()
//CPhpAuthManager.clearAll()
//CPhpAuthManager.clearAuthAssignments()
//CPhpAuthManager.createAuthItem()
//CPhpAuthManager.detectLoop()
//CPhpAuthManager.getAuthAssignment()
//CPhpAuthManager.getAuthAssignments()
//CPhpAuthManager.getAuthItem()
//CPhpAuthManager.getAuthItems()
//CPhpAuthManager.getItemChildren()
//CPhpAuthManager.hasItemChild()
//CPhpAuthManager.init()
//CPhpAuthManager.isAssigned()
//CPhpAuthManager.load()
//CPhpAuthManager.loadFromFile()
//CPhpAuthManager.removeAuthItem()
//CPhpAuthManager.removeItemChild()
//CPhpAuthManager.revoke()
//CPhpAuthManager.save()
//CPhpAuthManager.saveAuthAssignment()
//CPhpAuthManager.saveAuthItem()
//CPhpAuthManager.saveToFile()
?>