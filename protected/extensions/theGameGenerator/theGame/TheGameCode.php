<?php
/**
 *## TheGameCode class file.
 *
 * @author Roman Agolov <agilovr@gmail.com>
 * @copyright Copyright &copy; Roman Agolov 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

Yii::import('gii.generators.crud.CrudCode');

/**
 *## Class TheGameCode
 */
class TheGameCode extends CrudCode
{
	public function generateActiveRow($modelClass, $column)
	{
		if ($column->type === 'boolean') {
			return "\$form->checkBoxRow(\$model,'{$column->name}')";
		}
		else if (stripos($column->dbType, 'text') !== false) {
			return "\$form->textAreaRow(\$model,'{$column->name}',array('rows'=>6, 'cols'=>50, 'class'=>'span8'))";
		}
		else {
			if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
				$inputField = 'passwordFieldRow';
			}
			else {
				$inputField = 'textFieldRow';
			}

			if ($column->type !== 'string' || $column->size === null) {
				return "\$form->{$inputField}(\$model,'{$column->name}',array('class'=>'span5'))";
			}
			else {
				return "\$form->{$inputField}(\$model,'{$column->name}',array('class'=>'span5','maxlength'=>$column->size))";
			}
		}
	}
}
