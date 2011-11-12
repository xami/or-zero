<?php
//$this->pageTitle=$channels->name.'::'.Yii::app()->name.','.str_replace('天涯', '或零', $channels->name).',小说,情感,文学,新闻,娱乐,写真,八卦,国际,资讯,社会,民生,历史,女人,杂谈,或零整理,WWW.ORZERO.COM';
//Yii::app()->clientScript->registerMetaTag(str_replace('天涯', '或零', $channels->name).',或零整理,orzero,或零阅读,或零小说,或零在线,或零玄幻,穿越,或零文学,或零旅游,或零情感,社会,股票,股市,房产,摄影,自拍,写真,汽车,时尚,娱乐,八卦,女人,男人,情人,小三,美女,帅哥,新闻,历史,贴图,交友,鬼故事,闲话,杂谈,七十后,八十后,九十后', 'keywords');
//Yii::app()->clientScript->registerMetaTag(str_replace('天涯', '或零', $channels->name).',或零整理,或零在线阅读,或零天涯热贴整理,或零同步阅读,或零只看楼主,或零免费百宝箱,http://www.orzero.com/orz/'.$channel->id.'/', 'description');
//$this->breadcrumbs=array(
//	'首页'=>'/',
//	CHtml::encode(Tianya::t('Plate lists')),
//);

Yii::app()->clientScript->registerLinkTag(
'alternate',
'application/rss+xml',
'http://www.orzero.com/sitemap.xml');
Yii::app()->clientScript->registerLinkTag(
'alternate',
'application/rss+xml',
'http://www.orzero.com/rss.xml');
Yii::app()->clientScript->registerLinkTag(
'alternate',
'application/rss+xml',
'http://www.orzero.com/atom.xml');

?>
<div id="ad_header_channel">
<div id="ad1">
</div>
<div id="ad2">
</div>
</div>

<div class="channels_content">
<?php
$baseUrl=YII::app()->request->hostInfo;
if($cid==0){
    Yii::app()->clientScript->registerCssFile($baseUrl.'/css/channels.css');
}else{
    Yii::app()->clientScript->registerCssFile($baseUrl.'/css/channel.css');
}
$i=1;
foreach($channels as $channel){
    $dl_class=($i%2==1)?'dl_left':'dl_right';
    echo '  <dl class="'.$dl_class.'">';
    echo '      <dt><a class="title" href="'.$baseUrl.'/channel/'.$channel->id.'/">'.$channel->name.'</a></dt>';
    echo '      <dd class="item_sort">';
    $j=1;
    foreach($channel->items as $item){
        echo '          <a href="'.$baseUrl.'/item/'.$item->id.'">'.$item->name.'</a>';
        if($cid==0){
            if($j++ > 7){
                break;
            }
        }
    }
    echo '      </dd>';
    echo '  </dl>'."\r\n";
    $i++;
}
?>
</div>

<div id="ad_footer">
<div id="ad1" style="width:180px;">

</div>
<div id="ad2">

</div>
</div>

<?php
//$this->widget('application.components.AjaxBuild', array(
//	'type' => 'channel',
//	'fid'=>$channels->key,
//));
?>