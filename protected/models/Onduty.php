<?php

/**
 * This is the model class for table "onduty".
 *
 * The followings are the available columns in table 'onduty':
 * @property integer $Service_Id
 * @property integer $Schedule_Id
 * @property integer $Member_Id
 * @property integer $Is_Deleted
 * @property string $Created_Time
 * @property string $Last_Modified
 */
class Onduty extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Onduty the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'onduty';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Service_Id, Schedule_Id, Member_Id, Created_Time, Last_Modified', 'required'),
			array('Service_Id, Schedule_Id, Member_Id, Is_Deleted', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Service_Id, Schedule_Id, Member_Id, Is_Deleted, Created_Time, Last_Modified', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		//define relationship to list the related data
		return array(
			'schedule'=>array(
                self::BELONGS_TO,
                'Schedule',
                'Schedule_Id',
            ),
            'member'=>array(
                self::BELONGS_TO,
                'Member',
                'Member_Id',
            ),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Service_Id' => 'Service',
			'Schedule_Id' => 'Schedule',
			'Member_Id' => 'Member',
			'Is_Deleted' => 'Is Deleted',
			'Created_Time' => 'Created Time',
			'Last_Modified' => 'Last Modified',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('Service_Id',$this->Service_Id);
		$criteria->compare('Schedule_Id',$this->Schedule_Id);
		$criteria->compare('Member_Id',$this->Member_Id);
		$criteria->compare('Is_Deleted',$this->Is_Deleted);
		$criteria->compare('Created_Time',$this->Created_Time,true);
		$criteria->compare('Last_Modified',$this->Last_Modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}