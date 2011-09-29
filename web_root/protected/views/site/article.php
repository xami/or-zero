<?php 
$this->pageTitle=((isset($_REQUEST['C_page']) && $_REQUEST['C_page']>0) ? '[第'.$_REQUEST['C_page'].'页]' : '[或零]').
//str_replace('天涯', '或零', $article->title).'::'.str_replace('天涯', '或零', $article->item->name).'::'.
Yii::app()->name.',网路热帖同步整理'; 
//$this->breadcrumbs=array(
//	str_replace('天涯', '或零', $article->channel->name)=>'/or/'.$article->channel->id.'/',
//	str_replace('天涯', '或零', $article->item->name)=>'/ero/'.$article->item->id.'/',
//	str_replace('天涯', '或零', $article->title)=>'/orzero/'.$article->id.'/',
//);
//Yii::app()->clientScript->registerMetaTag($article->title.','.str_replace('天涯', '或零', $article->item->name).','.str_replace('天涯', '或零', $article->channel->name).',或零整理,或零在线,或零阅读,ORZERO', 'keywords');
//Yii::app()->clientScript->registerMetaTag('或零整理,在线阅读,'.$article->title.',脱水整理版地址:'.'http://www.orzero.com/orzero/'.$article->id.'/index.html', 'description');
//Yii::app()->clientScript->registerMetaTag('all', 'robots');
//Yii::app()->clientScript->registerMetaTag('或零(www.orzero.com)', 'author');
//Yii::app()->clientScript->registerLinkTag(
//    'link',
//    'text/html',
//    'http://www.orzero.com/site/item/'.$article->item->id,
//	'',
//	array('title'=>str_replace('天涯', '或零', $article->item->name))
//);
?>

<div id="ad_header">
<div id="ad1">
<script type="text/javascript"><!--
google_ad_client = "pub-4726192443658314";
/* 728x90, 创建于 10-1-10 */
google_ad_slot = "2961714380";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>
<div id="ad2" style="width:180px;">

<!-- JiaThis Button BEGIN -->
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
<script type="text/javascript" src="http://v1.jiathis.com/code/jia.js" charset="utf-8"></script>
<!-- JiaThis Button END -->

<style>
a.orzero_link{color:#ffffff;}
.keys,[name=keys]{display:none;}

</style>

</div>
</div>
<h1><?php echo CHtml::link(str_replace('天涯', '或零', CHtml::encode($article->title)), '/或零/'.$article->id.'/', array(name=>"link")); ?></h1>

<h2 style="float:left;margin-bottom:5px;display:inline-block;">[作者:<?php echo CHtml::link($article->un, 
'/search?cx=partner-pub-4726192443658314:lofclyqlq8w&cof=FORID:11&ie=UTF-8&q='.$article->un.'&author='.$article->un, 
array('target'=>'_blank',
'author'=> CHtml::encode($article->un),
'title'=>CHtml::encode($article->un),
'rev'=>'contents')); ?>]&nbsp;[<?php echo CHtml::link('或零整理', '/orzero-'.$article->id.'-index.html',
array('title'=>'或零整理,'.CHtml::encode($article->title),
'author'=>'www.orzero.com',
'title'=>CHtml::encode($article->title).'或零,或零整理,脱水整理,易读整理,只看楼主,天涯热帖',
'rev'=>'index',
));?>]&nbsp;[<?php
if (is_numeric($article->item->key)) {
	$src='http://www.tianya.cn/techforum/content/'.$article->item->key.'/1/'.$article->aid.'.shtml';
} else {
	$src='http://www.tianya.cn/publicforum/content/'.$article->item->key.'/1/'.$article->aid.'.shtml';
}

$ensrc='http://www.orzero.com/f/a?href='.rawurlencode(MCrypy::encrypt('a='.base64_encode($src).'&t='.$article->title, Yii::app()->params['mcpass'], 128));
echo CHtml::link('源帖',
$ensrc,
array('target'=>'_blank','title'=>CHtml::encode($article->title)));
?>]</h2>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_article',
	'ajaxUpdate'=>false,
)); 
Yii::app()->clientScript->registerMetaTag($article->title.','.implode(',', Orzero::$key).','.
str_replace('天涯', '或零', $article->item->name).','.
str_replace('天涯', '或零', $article->channel->name).',或零整理,或零小说,或零阅读,ORZERO', 'keywords');
?>

<?php
$this->widget('application.components.AjaxBuild', array(
	'type' => 'article',
	'fid' => $article->id,
)); ?>

<script type="text/javascript"><!--
$(function() {
	$('div.items div.view a.oz').lightBox({
//		overlayBgColor: '#FFF',
		overlayOpacity: 0.6,
		imageLoading: '/images/lightbox-ico-loading.gif',
		imageBtnClose: '/images/lightbox-btn-close.gif',
		imageBtnPrev: '/images/lightbox-btn-prev.gif',
		imageBtnNext: '/images/lightbox-btn-next.gif',
		imageBlank: '/images/lightbox-blank.gif',
		containerResizeSpeed: 350,
		txtImage: '<a href="http://www.orzero.com">或零整理</a>',
		txtOf: '/'
					
	});
});
//--></script>

<div id="ad_footer">
<div id="ad1" style="width:440px;display:block;">
<script type="text/javascript"><!--
window.googleAfmcRequest = {
  client: 'ca-mb-pub-4726192443658314',
  ad_type: 'text_image',
  output: 'html',
  channel: '5496976161',
  format: '320x50_mb',
  oe: 'utf8',
  color_border: '336699',
  color_bg: 'FFFFFF',
  color_link: '0000FF',
  color_text: '000000',
  color_url: '008000',
};
//--></script>
<script type="text/javascript" 
   src="http://pagead2.googlesyndication.com/pagead/show_afmc_ads.js"></script>
</div>
<div id="ad2">
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
</div>