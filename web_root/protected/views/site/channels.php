<?php 
if(empty($channels->id)) {
//	$channels->name='全部板块';
	$the_link='/or/';
}else{
	$the_link='/or/'.$channels->id.'/';
}
//$this->pageTitle=$channels->name.'::'.Yii::app()->name.','.str_replace('天涯', '或零', $channels->name).',小说,情感,文学,新闻,娱乐,写真,八卦,国际,资讯,社会,民生,历史,女人,杂谈,或零整理,WWW.ORZERO.COM';
//Yii::app()->clientScript->registerMetaTag(str_replace('天涯', '或零', $channels->name).',或零整理,orzero,或零阅读,或零小说,或零在线,或零玄幻,穿越,或零文学,或零旅游,或零情感,社会,股票,股市,房产,摄影,自拍,写真,汽车,时尚,娱乐,八卦,女人,男人,情人,小三,美女,帅哥,新闻,历史,贴图,交友,鬼故事,闲话,杂谈,七十后,八十后,九十后', 'keywords');
//Yii::app()->clientScript->registerMetaTag(str_replace('天涯', '或零', $channels->name).',或零整理,或零在线阅读,或零天涯热贴整理,或零同步阅读,或零只看楼主,或零免费百宝箱,http://www.orzero.com/orz/'.$channel->id.'/', 'description');
$this->breadcrumbs=array(
//	str_replace('天涯', '或零', $channels->name)=>$the_link,
//	CHtml::encode(Tianya::t('Plate lists')),
);
?>
<div id="ad_header_channel">
<div id="ad1">
</div>
<div id="ad2">
</div>
<!-- JiaThis Button BEGIN -->
<div style="float:left;clear:both;">
<div id="jiathis_style_32x32">
<a class="jiathis_button_qzone"></a>
<a class="jiathis_button_tieba"></a>
<a class="jiathis_button_renren"></a>
<a class="jiathis_button_fanfou"></a>
<a class="jiathis_button_tsohu"></a>
<a class="jiathis_button_tsina"></a>
<a class="jiathis_button_xiaoyou"></a>
<a class="jiathis_button_yahoo"></a>
<a class="jiathis_button_douban"></a>
<a class="jiathis_button_xianguo"></a>
<a class="jiathis_button_tqq"></a>
<a class="jiathis_button_zhuaxia"></a>
<a class="jiathis_button_fav"></a>
<a class="jiathis_button_email"></a>
<a class="jiathis_button_tianya"></a>
</div>
</div>
<!-- JiaThis Button END -->
</div>

<?php //echo $html;?>

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