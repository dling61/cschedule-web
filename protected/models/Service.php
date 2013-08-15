<?php

/**
 * This is the model class for table "service".
 *
 * The followings are the available columns in table 'service':
 * @property integer $Service_Id
 * @property string $Service_Name
 * @property string $Description
 * @property integer $SRepeat
 * @property integer $Start_Datetime
 * @property integer $End_Datetime
 * @property integer $UTC_Off
 * @property integer $Alert
 * @property integer $Creator_Id
 * @property integer $Is_Deleted
 * @property string $Created_Time
 * @property string $Last_Modified
 */
class Service extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Service the static model class
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
		return 'service';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Service_Name,Description, Alert,Start_Datetime,End_Datetime', 'required'),
			array('Service_Id, SRepeat, UTC_Off, Alert, Creator_Id, Is_Deleted,Created_Time, Last_Modified', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Service_Id, Service_Name, Description, SRepeat, Start_Datetime, End_Datetime, UTC_Off, Alert, Creator_Id, Is_Deleted, Created_Time, Last_Modified', 'safe', 'on'=>'search'),
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
			
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Service_Id' => 'Service',
			'Service_Name' => 'Service Name',
			'Description' => 'Description',
			'SRepeat' => 'Srepeat',
			'Start_Datetime' => 'Start Datetime',
			'End_Datetime' => 'End Datetime',
			'UTC_Off' => 'Utc Off',
			'Alert' => 'Alert',
			'Creator_Id' => 'Creator',
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
		$criteria->compare('Service_Name',$this->Service_Name,true);
		$criteria->compare('Description',$this->Description,true);
		$criteria->compare('SRepeat',$this->SRepeat);
		$criteria->compare('Start_Datetime',$this->Start_Datetime);
		$criteria->compare('End_Datetime',$this->End_Datetime);
		$criteria->compare('UTC_Off',$this->UTC_Off);
		$criteria->compare('Alert',$this->Alert);
		$criteria->compare('Creator_Id',$_SESSION['ownerid']);
		$criteria->compare('Is_Deleted',$this->Is_Deleted);
		$criteria->compare('Created_Time',$this->Created_Time,true);
		$criteria->compare('Last_Modified',$this->Last_Modified,true);
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function timestampToDate($timestamp){
		// import class Common
		include_once(dirname(Yii::app()->baseUrl)."protected/Common/Common.php");
		$common = new Common();
		return $common->timestampToDate($timestamp);
	}
}