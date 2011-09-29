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
    public function actionTest()
    {
//        $mysqlite = new OZMysqlite();
        try{
			$_page =new P();
			$_content =new C();
		}catch(Exception $e){
			OZMysqlite::createCacheTable('p');
			OZMysqlite::createCacheTable('c');
			OZMysqlite::createCacheTable('i');
//			$this->_article->pcount=0;
//			$this->_article->cto=0;
//			$this->_article->save();
//			if($pr){
//				die(json_encode(-1));
//			}
		}
        $_page->save();
        $p=$_page->findByPk(1);
        pr($_page);
//        pr($mysqlite->config);
//        $mysqlite->getDb();
    }

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
        
        
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
        $id=Yii::app()->request->getParam('id', 0);
        $article=Article::model()->find('`id`='.$id.' AND `status`=1');
		if(empty($article)){
			$this->render('items', array());
            return;
		}
        $dbPath=Yii::getPathOfAlias(
            'application.data.tianya.'.
            $article->cid.'.'.
            $article->tid.'.'.
            $article->aid.'.db'
        );

        OZMysqlite::setDbPath($dbPath);
        try{
			$page =new P();
		}catch(Exception $e){
			OZMysqlite::createCacheTable('p');
            $page =new P();
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
        
        $this->layout='//layouts/column4';
        $tianya=new Tianya();

        $data=$tianya->getItems($cid, $tid, $page_size);

        $this->render('items', $data);
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