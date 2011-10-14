<?php

class ApiController extends Controller
{
    public $tianya;

	public function actions()
	{
		$this->tianya=new Tianya();
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

        
        
        $this->tianya->setArticle($id);
    }

    public function actionItem()
	{
        $id=Yii::app()->request->getParam('id', 0);
        $this->tianya->setItem($id);
    }

    public function actionChannel()
	{
        $id=Yii::app()->request->getParam('id', 0);
        $this->tianya->setChannel($id);
    }
    
}