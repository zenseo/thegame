<?php
$this->beginWidget('bootstrap.widgets.TbModal', array(
	'id' => 'crop_photo_modal',
	'htmlOptions' => array('class' => 'middle_modal')
));
?>
<div class="modal-header">
	<a class="close" onclick="closeModal(this)">&times;</a>
	<h4>Настройка изображения</h4>
</div>
<div class="modal-body">
	<div id="upload_user_photo_message"></div>
	<div id="begin_container" style="text-align: center">
			<span style="margin: 50px auto" id="choose_file" class="btn btn-primary"
				  onclick="document.getElementById('user_photo_file').click();">
			Выбрать изображение
			</span>
	</div>
	<div id="photo_container" style="display: none; text-align: center">
		<img id="crop_photo" class="user_photo_for_crop" src=""/>
	</div>
	<div id="avatar_container" style="display: none; text-align: center">
		<img id="crop_avatar" class="user_photo_for_crop" src=""/>
	</div>

	<!-- Форма загрузки изображения -->
	<form id="upload_user_photo_form" method="post" enctype="multipart/form-data" action="/user/uploadPhoto/<?php echo $model->id ?>">
		<input type="file" name="user_photo" id="user_photo_file" class="hidden">
	</form>


</div>
<div class="modal-footer">
	<div id="lets_crop_user_photo" style="display: none" class="btn btn-primary" onclick="sendUserPhotoSelection()">Применить</div>
	<div id="lets_crop_avatar" style="display: none" class="btn btn-primary" onclick="sendUserAvatarSelection()">Применить</div>
	<div class="btn" data-dismiss="modal">Отмена</div>
</div>
<?php $this->endWidget(); ?>

<script>
	var photo_jcrop_api,
		avatar_jcrop_api,
		corrective = 1,
		user_photo_height = <?php echo User::USER_PHOTO_HEIGHT ?>,
		user_photo_aspect_ratio = <?php echo User::USER_PHOTO_ASPECT_RATIO ?>,
		user_photo_width = user_photo_height * user_photo_aspect_ratio,

		user_avatar_height = <?php echo User::USER_AVATAR_HEIGHT ?>,
		user_avatar_aspect_ratio = <?php echo User::USER_AVATAR_ASPECT_RATIO ?>,
		user_avatar_width = user_avatar_height * user_avatar_aspect_ratio;
	/**
	 * Закрывает модальное окно и чистит
	 * все, что было сделано
	 */
	function closeThisModal() {
		$('#crop_photo').hide();
		$('#crop_photo_modal').modal('hide');
		$('#upload_user_photo_message').html('');
		$('#begin_container').show();
		$('#crop_photo').attr('src', '');
		$('#crop_photo').attr('style', '');
		$('#crop_avatar').attr('src', '');
		$('#crop_avatar').attr('style', '');
		$('#avatar_container').hide();
		$('#lets_crop_avatar').hide();
		$('#lets_crop_user_photo').hide();
		photo_jcrop_api.destroy();
		avatar_jcrop_api.destroy();
	}

	/**
	 * Инициирует обрезку фотографии
	 */
	function cropUserPhoto(data) {
		corrective = data.real_height / user_photo_height;
		var min_size = [user_photo_width / corrective, user_photo_height / corrective];
		var set_select = [0, 0, user_photo_width / corrective / 2, user_photo_height / corrective / 2]
		$('#upload_user_photo_message').html('');
		$('#crop_photo').attr('src', data.image);
		$('#photo_container').show();
		$('#lets_crop_avatar').hide();
		$('#lets_crop_user_photo').show();
		$('#crop_photo').Jcrop({
			bgColor: 'white',
			aspectRatio: user_photo_aspect_ratio,
			minSize: min_size,
			setSelect: set_select
		}, function () {
			photo_jcrop_api = this;
		});
	}

	/**
	 * Инициирует обрезку аватара
	 */
	function cropUserAvatar(data) {
		$('#begin_container').hide();
		$('#photo_container').hide();
		$('#avatar_container').show();
		$('#lets_crop_avatar').show();
		$('#lets_crop_user_photo').hide();
		$('#crop_avatar').attr('src', data.image);
		$('#avatar_preview').attr('src', data.image);
		corrective = 1;
		var min_size = [user_avatar_width, user_avatar_height];
		var set_select = [0, 0, user_avatar_width, user_avatar_height];
		$('#crop_avatar').Jcrop({
			bgColor: 'white',
			aspectRatio: user_avatar_aspect_ratio,
			minSize: min_size,
			setSelect: set_select
		}, function () {
			avatar_jcrop_api = this;
		});
	}

	function sendUserPhotoSelection() {
		var coordinates = photo_jcrop_api.tellSelect();
		simpleJson(
			'/user/cropUserPhoto/<?php echo $model->id ?>',
			{
				coordinates: {
					x: coordinates.x * corrective,
					y: coordinates.y * corrective,
					width: coordinates.w * corrective,
					height: coordinates.h * corrective
				}
			}, function () {
				cropUserAvatar(this);
			}

		)
	}

	function sendUserAvatarSelection() {
		var coordinates = avatar_jcrop_api.tellSelect();
		simpleJson(
			'/user/cropUserAvatar/<?php echo $model->id ?>',
			{
				coordinates: {
					x: coordinates.x * corrective,
					y: coordinates.y * corrective,
					width: coordinates.w * corrective,
					height: coordinates.h * corrective
				}
			}, function () {
				closeThisModal();
				$('#user_photo').attr('src', '');
				$('#user_photo').attr('src', this.image);
			}
		)
	}

	$(function () {
		$('body').on('change', '#user_photo_file', function () {
			$('#begin_container').hide();
			$('#upload_user_photo_message').html('Загрузка...');
			$("#upload_user_photo_form").ajaxForm({
				dataType: 'json',
				success: function (json) {
					if (json.status == 200) {
						cropUserPhoto(json);
					}
					else {
						$('#upload_user_photo_message').html(json.message);
						$('#photo_container').hide();
						$('#begin_container').show();
					}
				}
			}).submit();
		});
	});

</script>