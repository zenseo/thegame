<?php
/**
 * Просмотр и изменение данных записи
 *
 * @var $model Customer
 * @var $this CustomerController
 * @var $form TbActiveForm
 */
$this->breadcrumbs = array(
	"Клиенты" => '/customer/index',
	$model->name
);
$this->pageTitle = $model->name;

?>

<h3><?php echo $model->name ?></h3>

<!--Навигация табов-->
<ul class="nav nav-tabs">
	<li class="active"><a href="#information_tab" data-toggle="tab">Информация</a></li>
	<li><a href="#contacts_tab" data-toggle="tab">Контакты</a></li>
	<li><a href="#details_tab" data-toggle="tab">Реквизиты</a></li>
	<li><a href="#tasks_tab" data-toggle="tab">Задачи</a></li>
	<li><a href="#history_tab" data-toggle="tab">История</a></li>
</ul>


<!--Контент табов-->
<div class="tab-content">
	<!--Информация-->
	<div class="tab-pane active" id="information_tab">
		<?php
		// Форма клиента
		$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
			'id' => 'update_customer_form',
			'type' => 'horizontal',
			'action' => '/customer/update/' . $model->id,
			'method' => 'post',
			'enableAjaxValidation' => false,
		)); ?>
		<?php echo $form->hiddenField($model, 'gis_id'); ?>

		<div class="row-fluid">
			<div class="span5">
				<?php echo $form->textFieldRow($model, 'name', array(
					'class' => 'span12',
					'maxlength' => 255
				)); ?>
				<?php echo $form->textFieldRow($model, 'phone', array(
					'class' => 'span12',
					'maxlength' => 255
				)); ?>
				<?php echo $form->textFieldRow($model, 'email', array(
					'class' => 'span12',
					'maxlength' => 255
				)); ?>
			</div>
			<div class="span5">
				<div class="control-group ">
					<label class="control-label" for="responsible">
						<?php echo $model->getAttributeLabel('responsible_id'); ?>
					</label>

					<div class="controls">
						<?php
						$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
							'name' => 'responsible',
							// атрибут модели
							'source' => 'js:function(request, response) {
                            jQuery.getJSON("/user/autoCompleteUser", {
                            term: request.term}, response)
                        }',
							'value' => $model->responsible_id ? $model->responsible->full_name : '',
							'options' => array(
								'minLength' => '3',
								'showAnim' => 'fold',
								'select' => 'js: function(event, ui) {
                            this.value = ui.item.label;
                            $("#responsible_id").val(ui.item.id)
                            return false;
                        }',
							),
							'htmlOptions' => array(
								'class' => 'span12',
							),
						));

						echo $form->hiddenField($model, 'responsible_id', array('id' => 'responsible_id')); ?>                    </div>
				</div>

				<?php echo $form->dropDownListRow($model, 'in_work', $model->getMultiSelectsData('in_work'), array('class' => 'span12')); ?>
				<?php echo $form->dropDownListRow($model, 'sales_status', $model->getMultiSelectsData('sales_status'), array('class' => 'span12')); ?>


			</div>
			<div class="span2">
				<?php $this->widget('bootstrap.widgets.TbButton', array(
					'type' => 'primary',
					'label' => 'Сохранить',
					'url' => '#',
					'htmlOptions' => array('onclick' => "ajaxForm('update_customer_form');return false;"),
				)); ?>
				<br/>
				<br/>
				<?php $this->widget('bootstrap.widgets.TbButton', array(
					'type' => 'danger',
					'label' => 'Удалить',
					'url' => '#',
					'htmlOptions' => array(
						'onclick' => "if(confirm('Вы действиетльно хотите удалить {$model->name}?')){
							simpleJson('/customer/delete/{$model->id}', {},
								function(){
									window.location.href = '/customer/index';
								});return false;
						}else{
							return false;
						}
						"
					),
				)); ?>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span10">
				<?php echo $form->textFieldRow($model, 'address', array(
					'class' => 'span12',
					'maxlength' => 255
				)); ?>
				<?php echo $form->textAreaRow($model, 'note', array(
					'rows' => 6,
					'cols' => 50,
					'class' => 'span12'
				)); ?>
			</div>
		</div>

		<?php $this->endWidget(); // Конец формы клиента ?>

	</div>

	<!-- Контакты -->
	<div class="tab-pane" id="contacts_tab"></div>

	<!-- Реквизиты -->
	<div class="tab-pane" id="details_tab"></div>

	<!-- Задачи -->
	<div class="tab-pane" id="tasks_tab"></div>

	<!-- История -->
	<div class="tab-pane" id="history_tab"></div>
</div>


<!--Диалоги и функциональные кнопки-->
<?php
// Диалог добавления/просмотра реквизита
$this->widget('bootstrap.widgets.TbModal', array(
	'id' => 'dlg_addContractorsRequisites',
	'htmlOptions' => array('class' => 'middle_modal')
));
// Информация о задаче
$this->widget('bootstrap.widgets.TbModal', array(
	'id' => 'dlg_task',
	'htmlOptions' => array('class' => 'wide_modal')
));
// Диалог просмотра/добавления контакта
$this->widget('bootstrap.widgets.TbModal', array(
	'id' => 'dlg_addContact',
	'htmlOptions' => array('class' => 'middle_modal')
));
// Диалог добавления/просмотра счета
$this->widget('bootstrap.widgets.TbModal', array(
	'id' => 'dlg_addRequisitesBankAccounts',
	'htmlOptions' => array('class' => 'small_modal')
));
// Диалог просмотра исторического события по клиенту
$this->widget('bootstrap.widgets.TbModal', array(
	'id' => 'dlg_history',
	'htmlOptions' => array('class' => 'small_modal')
));

?>
