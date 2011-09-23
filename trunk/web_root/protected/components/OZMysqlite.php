<?php
/**
 * Created by JetBrains PhpStorm.
 * User: lijia
 * Date: 11-9-22
 * Time: 下午5:13
 * To change this template use File | Settings | File Templates.
 */
 
class OZMysqlite extends CActiveRecord
{
    private static $_config=array('class'=>'CDbConnection');
    public static $ozdb;

    public function getConfig()
	{
		return self::$_config;
	}

    public static function setConfig($config)
	{
		if(self::$_config===null || $config===null)
			self::$_config=$config;
		else
			throw new CException(Yii::t('oz','OZDB application can only be created once.'));
	}
    
    public function getDbConnection()
	{
		if(self::$ozdb!==null)
			return self::$ozdb;
		else
		{
			self::$ozdb=$this->getDb();
			if(self::$ozdb instanceof CDbConnection)
				return self::$ozdb;
			else
				throw new CDbException(Yii::t('yii','Active Record requires a "db" CDbConnection application component.'));
		}
	}

	public function getDb()
	{
        self::$_config['connectionString']='x';
        $component=Yii::createComponent(self::$_config);
        $component->init();
        pr($component->dbPath);

        return $component;
	}

	public function createCacheTable($tableName='p')
	{
		$db=self::getDbConnection();
		$db->setActive(true);
		if($tableName=='p'){
			$sql=<<<EOD
CREATE  TABLE  IF NOT EXISTS "p"
("id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE ,
"link" VARCHAR NOT NULL  UNIQUE ,
"count" INTEGER NOT NULL  DEFAULT 0,
"info" VARCHAR,
"status" INTEGER NOT NULL  DEFAULT 0,
"mktime" INTEGER NOT NULL  DEFAULT 0,
"uptime" INTEGER NOT NULL  DEFAULT 0);
EOD;
		}else if($tableName=='c'){
			$sql=<<<EOD
CREATE  TABLE IF NOT EXISTS "c"
("id" INTEGER PRIMARY KEY  NOT NULL  UNIQUE ,
"pid" INTEGER ,
"pos" INTEGER NOT NULL  UNIQUE ,
"text" TEXT,
"info" VARCHAR,
"status"  NOT NULL  DEFAULT 1,
"mktime" INTEGER NOT NULL  DEFAULT 0,
"uptime" INTEGER NOT NULL  DEFAULT 0);
CREATE INDEX "pos" ON "c" ("pos" ASC);
EOD;
		}else if($tableName=='i'){
			$sql='CREATE INDEX "pos" ON "c" ("pos" ASC);';
		}

		if(!empty($sql))
			$db->createCommand($sql)->execute();

		$db->setActive(false);
	}

}