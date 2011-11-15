<style type="text/css">
.list-view .pager {
    clear: both;
}
</style>

<?php
$page=isset($_REQUEST['Searchs_page']) ? intval($_REQUEST['Searchs_page']) : 1;
$page= ($page>0) ? $page : 1;
$this->pageTitle=Yii::app()->name . ' - 标签 - [第'.$page.'页],www.orzero.com';
$this->breadcrumbs=array(
	'所有标签',
);


//Yii::app()->clientScript->registerMetaTag(CHtml::encode($this->author), 'author');
//Yii::app()->clientScript->registerMetaTag(CHtml::encode($this->keywords), 'keywords', 'keywords');
//Yii::app()->clientScript->registerMetaTag(CHtml::encode($this->description), 'description');

$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_tag',
	'ajaxUpdate'=>false,
)); ?>
