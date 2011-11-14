<?php

/**
 * This is the model class for table "{{file}}".
 *
 * The followings are the available columns in table '{{file}}':
 * @property integer $id
 * @property integer $aid
 * @property string $type
 * @property string $size
 * @property integer $pnum
 * @property string $name
 * @property string $src
 * @property string $fsrc
 * @property integer $time
 */
class File extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return File the static model class
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
		return '{{file}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('aid, type, size, pnum, name, src, time', 'required'),
			array('aid, pnum, time', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>20),
			array('size', 'length', 'max'=>10),
			array('name', 'length', 'max'=>32),
			array('src, fsrc', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, aid, type, size, pnum, name, src, fsrc, time', 'safe', 'on'=>'search'),
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
			'article'=>array(self::BELONGS_TO, 'Article', '', 'on'=>'t.aid=article.id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'aid' => 'Aid',
			'type' => 'Type',
			'size' => 'Size',
			'pnum' => 'Pnum',
			'name' => 'Name',
			'src' => 'Src',
			'fsrc' => 'Fsrc',
			'time' => 'Time',
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
		$criteria->compare('aid',$this->aid);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('size',$this->size,true);
		$criteria->compare('pnum',$this->pnum);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('src',$this->src,true);
		$criteria->compare('fsrc',$this->fsrc,true);
		$criteria->compare('time',$this->time);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}