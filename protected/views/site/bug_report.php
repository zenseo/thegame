<h1>Отправить сообщение об ошибке</h1>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id' => 'but_report_form',
));
?>
<?php
echo CHtml::hiddenField('BugReport[username]', Yii::app()->user->fullname);
echo CHtml::hiddenField('BugReport[user_role]', Yii::app()->user->role);
echo CHtml::hiddenField('BugReport[user_id]', Yii::app()->user->id);
?>
<div>
	<textarea class="span12" name="BugReport[message]" rows="5"></textarea>
</div>

<?php
$this->widget('bootstrap.widgets.TbButton', array(
	'label' => 'Отправить сообщение об ошибке',
	'buttonType' => 'submit',
	'type' => 'primary'
));
$this->endWidget();
?>