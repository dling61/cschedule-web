<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $User_id
 * @property string $Email
 * @property string $User_Name
 * @property string $Password
 * @property integer $Mobile
 * @property string $User_Type
 * @property integer $Verified
 * @property string $Created_Time
 * @property string $Last_Modified
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Email, User_Name, Password, Mobile, User_Type, Created_Time, Last_Modified', 'required'),
			array('Mobile, Verified', 'numerical', 'integerOnly'=>true),
			array('Email, User_Name, Password', 'length', 'max'=>100),
			array('User_Type', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('User_id, Email, User_Name, Password, Mobile, User_Type, Verified, Created_Time, Last_Modified', 'safe', 'on'=>'search'),
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
			'User_id' => 'User',
			'Email' => 'Email',
			'User_Name' => 'User Name',
			'Password' => 'Password',
			'Mobile' => 'Mobile',
			'User_Type' => 'User Type',
			'Verified' => 'Verified',
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

		$criteria->compare('User_id',$this->User_id);
		$criteria->compare('Email',$this->Email,true);
		$criteria->compare('User_Name',$this->User_Name,true);
		$criteria->compare('Password',$this->Password,true);
		$criteria->compare('Mobile',$this->Mobile);
		$criteria->compare('User_Type',$this->User_Type,true);
		$criteria->compare('Verified',$this->Verified);
		$criteria->compare('Created_Time',$this->Created_Time,true);
		$criteria->compare('Last_Modified',$this->Last_Modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}