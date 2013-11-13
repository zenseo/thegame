<?php
/**
 * @var $model User
 * @var $form TbActiveForm
 */
$this->breadcrumbs = array(
	"Пользователи" => '/user/index',
	$model->full_name
);
?>

<div class="span3">
	<h3>Фото</h3>
	<?php
	if ($model->photo) {
		$photo_url = Yii::app()->params['user_photos_base_url'] . $model->photo;
	}
	else {
		$photo_url = '/images/np' . $model->gender . '.png';
	}
	?>
	<img id="user_photo" src="<?php echo $photo_url ?>"/>
	<?php $this->renderPartial('crop_user_photo', array('model' => $model)); ?>
	<br/>
	<br/>
	<div class="btn btn-primary" onclick="$('#crop_photo_modal').modal('show')">Загрузить фото</div>
</div>
<div class="span9">
	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id' => 'user_form',
		'type' => 'horizontal',
		'enableAjaxValidation' => false,
	)); ?>
	<h3><?php echo $model->fio ?>
		<div id="save_profile_button" style="display: none;" class="btn btn-success">Сохранить изменения</div>
	</h3>
	<script>
		$(function () {
			// Если изменился хотя бы один элемент формы - показываем кнопку сохранить
			$('#user_form *').on('change', function () {
				$('#save_profile_button').show();
			})
		});
	</script>
	<ul class="nav nav-tabs" id="myTab">
		<li class="active"><a href="#main_data" data-toggle="tab">Основное</a></li>
		<li><a href="#personal_data" data-toggle="tab">Личные данные</a></li>
		<li><a href="#change_password" data-toggle="tab">Смена пароля</a></li>
		<li><a href="#access_settings" data-toggle="tab">Настройки доступа</a></li>
	</ul>

	<div class="tab-content">
		<!-- Основная ифнормация-->
		<div class="tab-pane active" id="main_data">

			<?php echo $form->textFieldRow($model, 'login', array(
				'class' => 'span5',
				'maxlength' => 45
			)); ?>

			<?php echo $form->dropDownListRow($model, 'role', $model->getMultiSelectsData('role'), array(
				'empty' => '',
				'class' => 'span5'
			)); ?>
			<?php echo $form->textFieldRow($model, 'department', array('class' => 'span5')); ?>
			<?php echo $form->dropDownListRow($model, 'position', $model->getMultiSelectsData('position'), array(
				'empty' => '',
				'class' => 'span5'
			)); ?>
			<div class="control-group">
				<label class="control-label">Зарегистрирован</label>

				<div class="controls vertical_form_text_value">
					<?php echo $model->created ?>
					<?php if ($model->updated) { ?>
						<small>Последнее обновление данных - <?php echo $model->updated ?></small>
					<?php } ?>
				</div>
			</div>
		</div>
		<!-- Личные данные -->
		<div class="tab-pane" id="personal_data">
			<?php echo $form->textFieldRow($model, 'lastname', array(
				'class' => 'span5',
				'maxlength' => 45
			)); ?>

			<?php echo $form->textFieldRow($model, 'firstname', array(
				'class' => 'span5',
				'maxlength' => 45
			)); ?>

			<?php echo $form->textFieldRow($model, 'surename', array(
				'class' => 'span5',
				'maxlength' => 45
			)); ?>
			<?php echo $form->dropDownListRow($model, 'gender', $model->getMultiSelectsData('gender'), array('class' => 'span5')); ?>
			<?php echo $form->textFieldRow($model, 'email', array(
				'class' => 'span5',
				'maxlength' => 255
			)); ?>
			<?php echo $form->textFieldRow($model, 'phone', array(
				'class' => 'span5',
				'maxlength' => 45
			)); ?>

			<?php echo $form->textFieldRow($model, 'birth_date', array('class' => 'span5')); ?>
			<?php $this->endWidget(); ?>

		</div>
		<!-- Смена пароля -->
		<div class="tab-pane" id="change_password">...</div>
		<!-- Настройки доступа -->
		<div class="tab-pane" id="access_settings">...</div>
	</div>

</div>

