<?php
// @todo Разобраться с базовым классом и заняться оптимизацией модели
// @todo Найти способ прегенерировать даты и мультиселекты
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
 * @property ' . $column->type . ' $' . $column->name .' '. $column->comment;
}
$markers[] = 'columnsCommentPlaceholder';
$data[] = $columnsComment;

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


// Поиск
$search = '';
foreach ($columns as $name => $column) {
	if ($column->type === 'string') {
		$search .= "\t\t\$criteria->compare('$name',\$this->$name,true);\n";
	}
	// Пропускаем даты... Для них у нас в файле dummy.php припасено сладенькое...
	else if ($column->type === 'date' || $column->type === 'timestamp') {
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
}else{
	$data[] = '// =)';
}

// Замещаем маркеры и выводим зрителям =)
echo str_replace($markers, $data, $tpl);