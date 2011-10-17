<?php

class ApiController extends Controller
{

	public function actions()
	{
	}

    

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
    public function actionArticle()
	{
        $id=Yii::app()->request->getParam('id', 0);
        $tianya=new Tianya();
        $st=$tianya->setArticle($id);
        pd($st);
    }

    public function actionItem()
	{
        $id=Yii::app()->request->getParam('id', 0);
        $tianya=new Tianya();
        $st=$tianya->setItem($id);
        pd($st);
    }

    public function actionChannel()
	{
        $id=Yii::app()->request->getParam('id', 0);
        $tianya=new Tianya();
        $st=$tianya->setChannel($id);
        pd($st);
    }
    
}