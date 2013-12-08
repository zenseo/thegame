<?php
$markers = array();
$data = array();

// Добавляем маркер в автозамену
$markers[] = 'tableNamePlaceholder';
$data[] = $tableName;

// Генерим PHP doc для колонок
$columnsComment = '';
//var_dump($columns);exit;
foreach ($columns as $column) {
	$columnsComment .= '
 * @property ' . $column->type . ' $' . $column->name . ' ' . $column->comment;
}
$markers[] = 'columnsCommentPlaceholder';
$data[] = $columnsComment;

$gridMultiSelects = '';
// Генерим PHP doc для зависимостей
if (!empty($relations)) {
	$relationsComment = '
 * Ниже описаны доступные для модели зависимости:';
	foreach ($relations as $name => $relation) {
		$relationsComment .= '
 * @property ';
		if (preg_match("~^array\(self::([^,]+), '([^']+)', '([^']+)'\)$~", $relation, $matches)) {
			$relationType = $matches[1];
			$relationModel = $matches[2];

			switch ($relationType) {
				case 'HAS_ONE':
					$rel = $relationModel . ' $' . $name;
					break;
				case 'BELONGS_TO':
					$rel = $relationModel . ' $' . $name;
					break;
				case 'HAS_MANY':
					$rel = $relationModel . '[] $' . $name;
					break;
				case 'MANY_MANY':
					$rel = $relationModel . '[] $' . $name;
					break;
				default:
					$rel = 'mixed $' . $name;
			}
			$relationsComment .= $rel;
		}
	}
	$markers[] = 'relationsCommentPlaceholder';
	$data[] = $relationsComment;
}

// Имя класса
$markers[] = 'ClassNamePlaceholder';
$data[] = $modelClass;

// Имя класса в нижнем регистре
$markers[] = 'LowerCNPlaceholder';
$data[] = strtolower($modelClass);

// Базовый класс
$markers[] = 'BaseClassNamePlaceholder';
$data[] = $this->baseClass;

// Правила валидации
$modelRules = '';
foreach ($rules as $rule) {
	$modelRules .= $rule . ",
			";
}
$markers[] = '"ModelRulesPlaceholder",';
$data[] = $modelRules;

// Правила поиска
$markers[] = 'SearchRulesPlaceholder';
$data[] = implode(', ', array_keys($columns));

// Зависимости
$relationRules = '';
foreach ($relations as $name => $relation) {
	$relationRules .= "'$name' => $relation,
			";
}
$markers[] = '"RelationRulesPlaceholder"';
$data[] = $relationRules;


// Ярлыки атрибутов модели
$attributeLabels = '';
foreach ($labels as $name => $label) {
	$attributeLabels .= "'$name' => '$label',
			";
}
$markers[] = '"AttributeLabelsPlaceholder"';
$data[] = $attributeLabels;


// Дефолтные атрибуты, выводящиеся в гриде
$attributeDefault = '';
foreach ($labels as $name => $label) {
	$attributeDefault .= "'$name',
			";
}
$markers[] = '"AttributeDefaultPlaceholder"';
$data[] = $attributeDefault;


// Даты для конвертации
$DatesForConvert = '';
$dateTypes = array(
	'datetime',
	'timestamp',
	'date',
);
foreach ($columns as $column) {

	if (isset($column->dbType) && (in_array(strtolower($column->dbType), $dateTypes))) {
		$DatesForConvert .= "'{$column->name}',
			";
	}
}
$markers[] = '"DatesForConvert"';
$data[] = $DatesForConvert;


// Поиск
$search = '';
foreach ($columns as $name => $column) {
	// Даты пропускаем, потому что для них есть автоматический сборщик поиска
	if (isset($column->dbType) && (in_array(strtolower($column->dbType), $dateTypes))) {
		continue;
	}
	else if ($column->type === 'string') {
		$search .= "\t\t\$criteria->compare('$name',\$this->$name,true);\n";
	}
	else {
		$search .= "\t\t\$criteria->compare('$name',\$this->$name);\n";
	}
}
$markers[] = '"SearchPlaceholder";';
$data[] = $search;

// Если у нас альтернативный коннект
$markers[] = 'public $alternateConnectPlaceholder;';
if ($connectionId != 'db') {
	$dbConnect = '
	/**
	* @return CDbConnection коннект к базе, использующийся в этом классе
	*/
	public function getDbConnection()
	{
	return Yii::app()->' . $connectionId . ';
	}
	';
	$data[] = $dbConnect;
}
else {
	$data[] = '// =)';
}

// Замещаем маркеры и выводим зрителям =)
echo str_replace($markers, $data, $tpl);