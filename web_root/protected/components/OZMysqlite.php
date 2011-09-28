<?php
/**
 * Created by JetBrains PhpStorm.
 * User: lijia
 * Date: 11-9-22
 * Time: 下午5:13
 * To change this template use File | Settings | File Templates.
 */
 
abstract class OZMysqlite extends CActiveRecord
{
    private static $_config=array('class'=>'CDbConnection', 'connectionString'=>'');
    private static $_path;
    public static $_ozdb;


//    public function getConfig()
//	{
//		return self::$_config;
//	}
//
//    public static function setConfig($config)
//	{
//		if(self::$_config===null || $config===null)
//			self::$_config=$config;
//		else
//			throw new CException(Yii::t('oz','OZDB application can only be created once.'));
//	}
    
    public function getDbConnection()
	{
		if(self::$_ozdb!==null)
			return self::$_ozdb;
		else
		{
			self::$_ozdb=$this->getDb();
			if(self::$_ozdb instanceof CDbConnection)
				return self::$_ozdb;
			else
				throw new CDbException(Yii::t('yii','Active Record requires a "db" CDbConnection application component.'));
		}
	}

    public function setDbPath($path){
        self::$_path=(string)$path;
        self::$_config['connectionString']='sqlite:'.self::$_path. DIRECTORY_SEPARATOR . 'orzero.sqlite';
        try{
            if(!is_dir(self::$_path))
				mkdir(self::$_path,0777,true);
        }catch(Exception $e){
            throw new CException(Yii::t('oz','OZDB can not create the dbpath.'));
        }
    }

	public function getDb()
	{
        if(!empty(self::$_config['connectionString'])){
            $component=Yii::createComponent(self::$_config);
            $component->init();
            return $component;
        }else{
            throw new CException(Yii::t('oz','OZDB set the dbpath first.'));
        }
	}

	public static function createCacheTable($tableName='p')
	{
		self::$_ozdb->setActive(true);
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
		}
//        else if($tableName=='i'){
//			$sql='CREATE INDEX "pos" ON "c" ("pos" ASC);';
//		}

		if(!empty($sql))
			self::$_ozdb->createCommand($sql)->execute();

//		self::$_ozdb->setActive(false);
	}

}