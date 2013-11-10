<?php

/**
 * This is the model class for table "broadcast_mediaplans".
 *
 * The followings are the available columns in table 'broadcast_mediaplans':
 *
 * @property integer $id
 * @property string $c_id
 * @property integer $user_id
 * @property integer $id_broadcast_contractors
 * @property string $doc_date
 * @property string $removal_date
 * @property string $date_from
 * @property string $date_to
 * @property boolean $issetted
 * @property string $note
 * @property string $created
 * @property integer $type_id
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property CrmContractors $idBroadcastContractors
 * @property BroadcastMediaplansType $type
 * @property Users $user
 * @property BroadcastGroup[] $broadcastGroups
 * @property LogsTransactions[] $logsTransactions
 */
class BroadcastMediaplans extends ActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'broadcast_mediaplans';
	}

	/**
	 * Поведение, сохраняющее связанные таблицы
	 * @return array
	 */
	public function behaviors()
	{
		return array(
			'CAdvancedArBehavior' => array(
				'class' => 'application.extensions.CAdvancedArBehavior'
			)
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array(
				'id_broadcast_contractors',
				'required'
			),
			array(
				'id, user_id, id_broadcast_contractors, type_id, status',
				'numerical',
				'integerOnly' => true
			),
			array(
				'c_id',
				'length',
				'max' => 10
			),
			array(
				'note',
				'length',
				'max' => 100
			),
			array(
				'id, doc_date, removal_date, date_from, date_to, issetted, created',
				'safe'
			),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array(
				'id, c_id, user_id, id_broadcast_contractors, doc_date, removal_date, date_from, date_to, issetted, note, created, type_id',
				'safe',
				'on' => 'search'
			),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idBroadcastContractors' => array(
				self::BELONGS_TO,
				'CrmContractors',
				'id_broadcast_contractors'
			),
			'type' => array(
				self::BELONGS_TO,
				'BroadcastMediaplansType',
				'type_id'
			),
			'user' => array(
				self::BELONGS_TO,
				'Users',
				'user_id'
			),
			'broadcastGroups' => array(
				self::HAS_MANY,
				'BroadcastGroup',
				'id_broadcast_mediaplans'
			),
			'logsTransactions' => array(
				self::HAS_MANY,
				'LogsTransactions',
				'broadcast_mediaplans_id'
			),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'c_id' => 'ИД из 1С',
			'user_id' => 'Пользователь',
			'id_broadcast_contractors' => 'Клиент',
			'doc_date' => 'Doc Date',
			'removal_date' => 'Removal Date',
			'date_from' => 'Дата начала',
			'date_to' => 'Дата конца',
			'issetted' => 'Поставлен',
			'note' => 'Заметка',
			'created' => 'Создан',
			'type_id' => 'Тип',
			'status' => 'Статус'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('c_id', $this->c_id, true);
		$criteria->compare('user_id', $this->user_id);
		$criteria->compare('id_broadcast_contractors', $this->id_broadcast_contractors);
		$criteria->compare('doc_date', $this->doc_date, true);
		$criteria->compare('removal_date', $this->removal_date, true);

		if (isset($this->date_from) && trim($this->date_from) != "") {
			$this->date_from = date("Y-m-d", strtotime($this->date_from)) . ' 00:00:00';
			$criteria->addCondition("t.date_from > '" . $this->date_from . "'");
		}

		if (isset($this->date_to) && trim($this->date_to) != "") {
			$this->date_to = date("Y-m-d", strtotime($this->date_to)) . ' 23:59:59';
			$criteria->addCondition("t.date_to < '" . $this->date_to . "'");
		}

		$criteria->compare('issetted', $this->issetted);
		$criteria->compare('note', $this->note, true);
		$criteria->compare('created', $this->created, true);
		$criteria->compare('type_id', $this->type_id);
		$criteria->compare('status', $this->status);


		$result = new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
			),
			'sort' => array(
				'defaultOrder' => 'id DESC',
			),
		));

		return $result;
	}

	/**
	 * @return string Имя клиента
	 */
	public function getClientName()
	{
		$client = "-";
		if ($this->id_broadcast_contractors) {
			$client_model = CrmContractors::model()->findByPk($this->id_broadcast_contractors);
			$client = $client_model ? $client_model->name : "-";
		}

		return $client;
	}

	/**
	 * @return string статус медиаплана
	 */
	public function getStatusText()
	{
		$statusArr = array(
			'2' => 'Продажа',
			'1' => 'Архив',
			'0' => 'На эфире'
		);

		return isset($statusArr[$this->status]) ? $statusArr[$this->status] : "-";
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 *
	 * @param string $className active record class name.
	 * @return BroadcastMediaplans the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function attributeDefault()
	{
		return array(
			'id',
			'c_id',
			'status',
			'id_broadcast_contractors',
			'date_from',
			'date_to',
			'user_id',
			'created',
			//	'note',
			'type_id'
		);
	}

	public function columnsGrid($columns, $model_grid_filter = null)
	{
		$result = array();

		$all_tracks = $this->findAll("id_broadcast_contractors>0");

		$clients_ids = array();
		foreach ($all_tracks as $track) {
			$clients_ids[] = $track->id_broadcast_contractors;
		}


		$cli = CrmContractors::model()->findAllByPk($clients_ids, array('order' => 'name ASC'));
		if (!count($cli)) {
			throw new CHttpException(400, "Нет клиентов с медиапланами!");
		}


		foreach ($columns as $row) {
			switch ($row) {
				case 'id':
					$result[] = array(
						'name' => 'id',
						'type' => 'raw',
						'value' => '$data->morelink',
						'htmlOptions' => array(
							'style' => 'max-width: 50px;',
							'class' => 'text-overflow'
						)
					);
					break;

				case 'c_id':
					$result[] = array(
						'name' => 'c_id',
						'htmlOptions' => array(
							'style' => 'max-width: 100px;',
							'class' => 'text-overflow'
						)
					);
					break;

				case 'status':
					$result[] = array(
						'name' => 'status',
						'filter' => CHtml::listData(array(
							array(
								'id' => 0,
								'name' => 'На эфире'
							),
							array(
								'id' => 1,
								'name' => 'Архив'
							)
						), 'id', 'name'),
						'value' => '($data->status !== null) ? $data->statustext : ""',
						'htmlOptions' => array(
							'style' => 'max-width: 150px; width: 150px;',
							'class' => 'text-overflow'
						)
					);
					break;

				case 'note':
					$result[] = array(
						'name' => 'note',
						'htmlOptions' => array(
							'style' => 'max-width: 200px;',
							'class' => 'text-overflow'
						)
					);
					break;

				case 'date_from':
					$result[] = array(
						'name' => 'date_from',
						'filter' => Yii::app()->controller->widget('bootstrap.widgets.TbDatePicker', array(
							'name' => 'date_from',
							'model' => $this,
							'attribute' => 'date_from',
							'htmlOptions' => array(
								'id' => 'date_from_datepicker',
								'size' => '10',

							),
						), true),
						'htmlOptions' => array(
							'style' => 'max-width: 100px;',
							'class' => 'text-overflow'
						),
						'value' => 'isset($data["date_from"]) ? date("d.m.Y", strtotime($data["date_from"])) : ""'
					);
					break;

				case 'date_to':
					$result[] = array(
						'name' => 'date_to',
						'filter' => Yii::app()->controller->widget('bootstrap.widgets.TbDatePicker', array(
							'name' => 'date_to',
							'model' => $this,
							'attribute' => 'date_to',
							'htmlOptions' => array(
								'id' => 'date_to_datepicker',
								'size' => '10',

							),
						), true),
						'htmlOptions' => array(
							'style' => 'max-width: 100px;',
							'class' => 'text-overflow'
						),
						'value' => 'isset($data["date_to"]) ? date("d.m.Y", strtotime($data["date_to"])) : ""'
					);
					break;
				case 'created':
					$result[] = array(
						'name' => 'created',
						'filter' => Yii::app()->controller->widget('bootstrap.widgets.TbDatePicker', array(
							'name' => 'created',
							'model' => $this,
							'attribute' => 'created',
							'htmlOptions' => array(
								'id' => 'created_datepicker',
								'size' => '10',
								//'value' => (isset($model_grid_filter->production_date) ? date("d.m.Y", strtotime($model_grid_filter->production_date)) : "")
							),
						), true),
						'htmlOptions' => array(
							'style' => 'max-width: 100px;',
							'class' => 'text-overflow'
						),
						'value' => 'isset($data["created"]) ? date("d.m.Y", strtotime($data["created"])) : ""'
					);
					break;

				case 'user_id':
					$result[] = array(
						'name' => 'user_id',
						//	'filter' => CHtml::listData(Users::model()->findAll(array('order' => 'name ASC')), 'id', 'name'),
						'filter' => Yii::app()->controller->widget('bootstrap.widgets.TbSelect2ValReworked', array(
							'name' => 'BroadcastMediaplans[user_id][]',
							'data' => CHtml::listData(Users::model()->findAll(array('order' => 'name ASC')), 'id', 'name'),
							'val' => isset($this->staff_id) ? $this->staff_id : '',
							'options' => array( //									'width' => "97.4%",
							),
							'htmlOptions' => array(
								'multiple' => 'multiple',
								'id' => 'multiuser'
							),
						), true),
						'value' => 'isset($data->user->personal) ? ($data->user->personal->fam . " " . $data->user->personal->name . " " . $data->user->personal->otch) : (isset($data->user->name)) ? $data->user->name : ""',
						'htmlOptions' => array(
							'style' => 'max-width: 200px;',
							'class' => 'text-overflow'
						)
					);
					break;

				case 'type_id':
					$result[] = array(
						'name' => 'type_id',
						//	'filter' => CHtml::listData(Users::model()->findAll(array('order' => 'name ASC')), 'id', 'name'),
						'filter' => Yii::app()->controller->widget('bootstrap.widgets.TbSelect2ValReworked', array(
							'name' => 'BroadcastMediaplans[type_id][]',
							'data' => CHtml::listData(BroadcastMediaplansType::model()->findAll(array('order' => 'title ASC')), 'id', 'title'),
							'val' => isset($this->type_id) ? $this->type_id : '',
							'options' => array( //									'width' => "97.4%",
							),
							'htmlOptions' => array(
								'multiple' => 'multiple',
								'id' => 'multitype'
							),
						), true),
						'value' => 'isset($data->type->title) ? ($data->type->title) : "-"',
						'htmlOptions' => array(
							'style' => 'max-width: 200px;',
							'class' => 'text-overflow'
						)
					);
					break;


				case 'id_broadcast_contractors':
					$result[] = array(
						'name' => 'id_broadcast_contractors',
						'filter' => Yii::app()->controller->widget('bootstrap.widgets.TbSelect2ValReworked', array(
							'name' => 'BroadcastMediaplans[id_broadcast_contractors][]',
							'data' => CHtml::listData($cli, 'id', 'name'),
							'val' => isset($this->crm_contractors_id) ? $this->crm_contractors_id : '',
							'options' => array( //									'width' => "97.4%",
							),
							'htmlOptions' => array(
								'multiple' => 'multiple',
								'class' => 'normalmultiselect maxwidthmultiselect',
								//'style' => 'max-width: 456px !important',
								'id' => 'multiclients'
							),
						), true),
						'value' => '$data["clientname"]',
						'htmlOptions' => array(
							'style' => 'max-width: 700px; width: 40%;',
							'class' => 'text-overflow'
						)
					);
					break;

				default:

					$result[] = $row;
					break;

			}

		}


		return $result;

	}

	/**
	 * @return array Колонки фильтра
	 */
	public function getFilter()
	{
		return array(
			'main' => array(
				'label' => 'Основные',
				'childs' => array(
					'id',
					'c_id',
					'user_id',
					'status',
					'created',
				)
			),
			'other' => array(
				'label' => 'Прочие',
				'childs' => array(
					'date_from',
					'date_to',
					'id_broadcast_contractors',
					'created',
					'note',
					'type_id'
				)
			)
		);
	}

	/**
	 * Ссылка на ролик
	 * @return string
	 */
	public function getMorelink()
	{
		$link = Yii::app()->urlManager->createUrl('/mediaplans/view', array('id' => $this->id));

		return Yii::app()->user->checkUserAccess('viewMediaplans')
			? CHtml::link($this->id, $link, array('target' => '_blank')) : $this->id;
	}

	/**
	 * @return array - rules for GridColumnsFilter
	 */
	public function getColumnsRules()
	{
		return array(
			'required' => array(
				'id',
				'c_id',
			)
		);
	}
}
