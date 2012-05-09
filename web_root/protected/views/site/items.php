<?php
//pd($channel);
if(!empty($channel)){
    $this->breadcrumbs=array(
    	str_replace('天涯', Yii::app()->name, $channel->name)=>'/channel/'.$channel->id.'/',
    );
    $this->pageTitle='我的天涯-'.$channel->name;
}
if(!empty($item)){
    $this->breadcrumbs=array(
    	str_replace('天涯', Yii::app()->name, $channel->name)=>'/channel/'.$channel->id.'/',
        str_replace('天涯', Yii::app()->name, $item->name)=>'/item/'.$item->id.'/',
    );
    $this->pageTitle='我的天涯-'.$channel->name.'-'.$item->name;
}

Yii::app()->clientScript->registerLinkTag(
'alternate',
'application/rss+xml',
'http://'.Yii::app()->params['domain'].'/sitemap.xml');
Yii::app()->clientScript->registerLinkTag(
'alternate',
'application/rss+xml',
'http://'.Yii::app()->params['domain'].'/rss.xml');
Yii::app()->clientScript->registerLinkTag(
'alternate',
'application/rss+xml',
'http://'.Yii::app()->params['domain'].'/atom.xml');

?>

<div class="items_content">
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'item_view',
	'ajaxUpdate'=>false,
)); ?>
</div>

<?php
if(isset($item->id)&&!empty($item->id)){
    $this->widget('application.components.AjaxBuild', array(
        'type' => 'item',
        'fid'=>$item->id,
    ));
}else if(isset($channel->id)&&!empty($channel->id)){
    $this->widget('application.components.AjaxBuild', array(
        'type' => 'channel',
        'fid'=>$channel->id,
    ));
}
?>