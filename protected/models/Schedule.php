<?php

/**
 * This is the model class for table "schedule".
 *
 * The followings are the available columns in table 'schedule':
 * @property integer $Schedule_Id
 * @property integer $Service_Id
 * @property string $Start_DateTime
 * @property string $End_DateTime
 * @property string $Description
 * @property integer $Creator_Id
 * @property integer $Is_Deleted
 * @property string $Created_Time
 * @property string $Last_Modified
 */
class Schedule extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Schedule the static model class
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
		return 'schedule';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Start_DateTime, End_DateTime, Description,Is_Deleted', 'required'),
			array('Schedule_Id, Service_Id, Creator_Id, Is_Deleted,Start_DateTime,End_DateTime,Created_Time,Last_Modified', 'numerical', 'integerOnly'=>true),
			array('Description', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Schedule_Id, Service_Id, Start_DateTime, End_DateTime, Description, Creator_Id, Is_Deleted, Created_Time, Last_Modified', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		//define relationship to list the related data
		return array(
			 'members'=>array(
                self::MANY_MANY,
                'Member',
                'on_duty(Schedule_Id, Member_Id)',
            ),
            'onduty'=>array(
                self::HAS_MANY,
                'Onduty',
                'Schedule_Id',
            ),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Schedule_Id' => 'Schedule',
			'Service_Id' => 'Service',
			'Start_DateTime' => 'Start Date Time',
			'End_DateTime' => 'End Date Time',
			'Description' => 'Description',
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

		$criteria->compare('Schedule_Id',$this->Schedule_Id);
		$criteria->compare('Service_Id',$this->Service_Id);
		$criteria->compare('Start_DateTime',$this->Start_DateTime,true);
		$criteria->compare('End_DateTime',$this->End_DateTime,true);
		$criteria->compare('Description',$this->Description,true);
		$criteria->compare('Creator_Id',$this->Creator_Id);
		$criteria->compare('Is_Deleted',$this->Is_Deleted);
		$criteria->compare('Created_Time',$this->Created_Time,true);
		$criteria->compare('Last_Modified',$this->Last_Modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getMemberOptions(){
		$query = Yii::app()->db->createCommand("select Member_Id,Member_Name from member where Creator_Id = ".$_SESSION['ownerid'])->queryAll();
		$members = array();
		if($query){
			foreach($query as $val){
				$members[$val['Member_Id']] = $val['Member_Name'];
			}
		}
		return $members;
	}
	
	public function timestampToDate($timestamp){
		// import class Common
		include_once(dirname(Yii::app()->baseUrl)."protected/Common/Common.php");
		$common = new Common();
		return $common->timestampToDate($timestamp);
	}
}