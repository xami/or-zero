<?php
//pd($channel);
if(!empty($channel)){
    $this->breadcrumbs=array(
    	str_replace('天涯', Yii::app()->name, $channel->name)=>'/channel/'.$channel->id.'/',
    );
}
if(!empty($item)){
    $this->breadcrumbs=array(
    	str_replace('天涯', Yii::app()->name, $channel->name)=>'/channel/'.$channel->id.'/',
        str_replace('天涯', Yii::app()->name, $item->name)=>'/item/'.$item->id.'/',
    );
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
<div id="ad_header_channel">
<div id="ad1">
</div>
<div id="ad2">
</div>
</div>

<div class="items_content">
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'item_view',
	'ajaxUpdate'=>false,
)); ?>
</div>

<div id="ad_footer">
<div id="ad1" style="width:180px;">

</div>
<div id="ad2">

</div>
</div>

<?php
if(isset($item->id)&&!empty($item->id)){
    $this->widget('application.components.AjaxBuild', array(
        'type' => 'item',
        'fid'=>$item->id,
    ));
}

?>