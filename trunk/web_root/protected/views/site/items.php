<?php
//pd($channel);
if(!empty($channel)){
    $this->breadcrumbs=array(
    	str_replace('天涯', Yii::app()->name, $channel->name)=>'/channel/'.$channel->id.'/',
    );
    $this->pageTitle='M天涯-'.$channel->name;
}
if(!empty($item)){
    $this->breadcrumbs=array(
    	str_replace('天涯', Yii::app()->name, $channel->name)=>'/channel/'.$channel->id.'/',
        str_replace('天涯', Yii::app()->name, $item->name)=>'/item/'.$item->id.'/',
    );
    $this->pageTitle='M天涯-'.$channel->name.'-'.$item->name;
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
<div style="float: right;clear: both;margin-top: 2px;">
<script type="text/javascript"><!--
google_ad_client = "pub-4726192443658314";
/* 468x60, 创建于 11-3-3 */
google_ad_slot = "7613266296";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
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
}else if(isset($channel->id)&&!empty($channel->id)){
    $this->widget('application.components.AjaxBuild', array(
        'type' => 'channel',
        'fid'=>$channel->id,
    ));
}
?>