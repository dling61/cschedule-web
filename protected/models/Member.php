<?php

/**
 * This is the model class for table "member".
 *
 * The followings are the available columns in table 'member':
 * @property integer $Member_Id
 * @property string $Member_Email
 * @property string $Member_Name
 * @property integer $Mobile_Number
 * @property integer $Is_Registered
 * @property integer $Creator_Id
 * @property string $Created_Time
 * @property integer $Is_Deleted
 * @property string $Last_Modified
 */
class Member extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Member the static model class
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
		return 'member';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Member_Email, Member_Name', 'required'),
			array('Member_Id, Mobile_Number, Is_Registered, Creator_Id, Is_Deleted,Created_Time,Last_Modified', 'numerical', 'integerOnly'=>true),
			array('Member_Email, Member_Name', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Member_Id, Member_Email, Member_Name, Mobile_Number, Is_Registered, Creator_Id, Created_Time, Is_Deleted, Last_Modified', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		//define relationship to list the related data
		return array(
			 'schedules'=>array(
                self::MANY_MANY,
                'Schedule',
                'on_duty(Schedule_Id,Member_Id)'
            ),
            'onduty'=>array(
                self::HAS_MANY,
                'Onduty',
                'Member_Id'
            ),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Member_Id' => 'Member',
			'Member_Email' => 'Member Email',
			'Member_Name' => 'Member Name',
			'Mobile_Number' => 'Mobile Number',
			'Is_Registered' => 'Is Registered',
			'Creator_Id' => 'Creator',
			'Created_Time' => 'Created Time',
			'Is_Deleted' => 'Is Deleted',
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

		$criteria->compare('Member_Id',$this->Member_Id);
		$criteria->compare('Member_Email',$this->Member_Email,true);
		$criteria->compare('Member_Name',$this->Member_Name,true);
		$criteria->compare('Mobile_Number',$this->Mobile_Number);
		$criteria->compare('Is_Registered',$this->Is_Registered);
		$criteria->compare('Creator_Id',$_SESSION['ownerid']);
		$criteria->compare('Created_Time',$this->Created_Time,true);
		$criteria->compare('Is_Deleted',$this->Is_Deleted);
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