<?php

/**
 * This is the model class for table "{{search}}".
 *
 * The followings are the available columns in table '{{search}}':
 * @property integer $id
 * @property string $key
 * @property integer $uid
 * @property integer $mktime
 * @property integer $uptime
 * @property integer $ccount
 * @property integer $rcount
 */
class Searchs extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Search the static model class
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
		return '{{search}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('key, uid, mktime, uptime, ccount, rcount', 'required'),
			array('uid, mktime, uptime, ccount, rcount', 'numerical', 'integerOnly'=>true),
			array('key', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, key, uid, mktime, uptime, ccount, rcount', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'key' => 'Key',
			'uid' => 'Uid',
			'mktime' => 'Mktime',
			'uptime' => 'Uptime',
			'ccount' => 'Ccount',
			'rcount' => 'Rcount',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('key',$this->key,true);
		$criteria->compare('uid',$this->uid);
		$criteria->compare('mktime',$this->mktime);
		$criteria->compare('uptime',$this->uptime);
		$criteria->compare('ccount',$this->ccount);
		$criteria->compare('rcount',$this->rcount);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}