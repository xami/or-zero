<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}
    public function actionTest($id=2)
    {
        $tianya=new Tianya();
        $st=$tianya->setItem($id);
        pd($st);
    }

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex($id=88)
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
//        $c = Tools::OZCurl('http://www.baidu.com');
//        pd($c);
        $article=Article::model()->find('`id`='.$id.' AND `status`=1');
        //设置数据库
        OZMysqlite::setDbPath(
            Yii::getPathOfAlias(
                'application.data.tianya.'.
                $article->cid.'.'.
                $article->tid.'.'.
                $article->aid.'.db'
            )
        );
        OZMysqlite::$_ozdb=OZMysqlite::getDb();

        //文章信息表
        try{
			$_P =new P();
            $_C =new C();
		}catch(Exception $e){
            OZMysqlite::createCacheTable('c');
			OZMysqlite::createCacheTable('p');
		}

        $tianya=new Tianya();
        $st=$tianya->setArticle($id);

        //-6数据库文件被破坏,需要回归
        //true可以继续更新
        //-12已经更新到最后
        var_dump($st);
//		$this->render('index');
	}

    public function actionChannel()
	{
        $id=Yii::app()->request->getParam('id', 0);



        $tianya=new Tianya();
		$data=$tianya->getChannels($id);
        $data['cid']=$id;
//test
//foreach($data['channels'] as $channel){
//    echo $channel->name."\r\n";
//    foreach($channel->items as $item){
//        echo "\t".$item->name."\r\n";
//    }
//}

		$this->layout='//layouts/column4';
		$this->render('channels', $data);
	}

    public function actionItem()
	{
        $page_size=Yii::app()->request->getParam('page_size', 10);
        $cid=Yii::app()->request->getParam('cid', 0);
        $tid=Yii::app()->request->getParam('tid', 0);
        
        $this->layout='//layouts/column4';
        $tianya=new Tianya();
        
        $data=$tianya->getItems($cid, $tid, $page_size);

        $this->render('items', $data);
	}

    public function actionArticle()
	{
        //6822
        $id=Yii::app()->request->getParam('id', 0);
        $article=Article::model()->find('`id`='.$id.' AND `status`=1');
		if(empty($article)){
			$this->render('error', array('msg'=>'当前文章不存在或者已经被删除'));
            return;
		}
        $dbPath=Yii::getPathOfAlias(
            'application.data.tianya.'.
            $article->cid.'.'.
            $article->tid.'.'.
            $article->aid.'.db'
        );

        try{
            OZMysqlite::setDbPath($dbPath);
			$page =new C();
		}catch(Exception $e){
			OZMysqlite::createCacheTable('c');
            $page =new C();
		}
        
//		$criteria=new CDbCriteria;
//		$criteria->condition='status=1';
//		$criteria->order='pos ASC';
		$data['dataProvider']=new CActiveDataProvider('C',array(
//		    'criteria'=>$criteria,
		    'pagination'=>array(
		        'pageSize'=>10,
		    ),
		));

        $this->render('article', $data);
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}