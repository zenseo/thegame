<?php
/**
 *## TheGameGenerator class file.
 *
 * @author Roman Agilov <agilovr@gmail.com>
 * @copyright Copyright &copy; Agilov Roman 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

Yii::import('gii.generators.crud.CrudGenerator');

/**
 *## Class TheGameGenerator
 *
 * @package booster.gii
 */
class TheGameGenerator extends CrudGenerator
{
	public $codeModel = 'ext.theGameGenerator.theGame.TheGameCode';
}