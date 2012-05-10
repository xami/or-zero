<?php
Yii::app()->clientScript->registerMetaTag($article->title.'我的天涯,mtiany,我的天涯阅读,我的天涯小说,我的天涯在线,我的天涯文学,我的天涯旅游,我的天涯情感,我的天涯自拍,我的天涯写真,我的天涯汽车,我的天涯娱乐,我的天涯八卦,我的天涯新闻,我的天涯历史,我的天涯贴图,我的天涯交友,我的天涯鬼故事,我的天涯闲话,我的天涯杂谈', 'keywords');
Yii::app()->clientScript->registerMetaTag($article->title.'|'.$article->item->name.'|'.$article->channel->name.'天涯热帖同步整理,提供天涯只看楼主功能,天涯热帖脱水,我的天涯,免费百宝箱', 'description');

$this->pageTitle=str_replace('天涯', Yii::app()->name, $article->title)
                .((isset($_REQUEST['C_page']) && $_REQUEST['C_page']>0) ? '(第'.$_REQUEST['C_page'].'页)' : '['.Yii::app()->name.']');

$this->breadcrumbs=array(
	str_replace('天涯', Yii::app()->name, $article->channel->name)=>'/channel/'.$article->channel->id.'/',
	str_replace('天涯', Yii::app()->name, $article->item->name)=>'/item/'.$article->item->id.'/',
	str_replace('天涯', Yii::app()->name, $article->title)=>'/link-'.$article->id.'.html',
);
?>
<section id="content">
<h1 class="mg">
作者:<?php echo Tools::createSearch($article->un, false); ?>&nbsp; &nbsp;
整理:<?php echo CHtml::link(Yii::app()->name, '/article-'.$article->id.'.html',
array('title'=>'或零整理,'.CHtml::encode($article->title),
'author'=>Yii::app()->name,
'title'=>CHtml::encode($article->title).'我的天涯,天涯整理,天涯脱水整理,天涯易读整理,天涯只看楼主,天涯热帖',
'rev'=>'index',
));?>&nbsp; &nbsp;
源帖:<?php
if (is_numeric($article->item->key)) {
	$src='http://www.tianya.cn/techforum/content/'.$article->item->key.'/1/'.$article->aid.'.shtml';
} else {
	$src='http://www.tianya.cn/publicforum/content/'.$article->item->key.'/1/'.$article->aid.'.shtml';
}
$en_src=Yii::app()->request->hostInfo.'/api/a?href='.urlencode($src).'&t='.urlencode($article->title).
    '&f='.urlencode(Yii::app()->request->hostInfo.'/article/'.$article->id.'/index.html');
echo CHtml::link($src, $en_src, array('target'=>'_blank','title'=>CHtml::encode($article->title)));
?>
</h1>


<?php
$this->widget('application.components.OListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_article',
	'ajaxUpdate'=>false,
    'viewData'=>array('article'=>$article),
));
?>

<script type="text/javascript">
<?php
$js_mark=file_get_contents(Yii::app()->request->hostInfo.'/js/mark.js');
$packer = new JavaScriptPacker($js_mark, 'Normal', true, false);
echo $packer->pack();
?>

</script>

<?php
$cs=Yii::app()->clientScript;
$cs->registerScript('it',"init();");

$this->widget('application.components.AjaxBuild', array(
	'type' => 'article',
	'fid'=>$article->id,
));
?>

