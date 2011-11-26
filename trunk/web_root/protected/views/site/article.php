<?php
Yii::app()->clientScript->registerMetaTag(htmlentities($article->title).'M天涯,mtiany,M天涯阅读,M天涯小说,M天涯在线,M天涯文学,M天涯旅游,M天涯情感,M天涯自拍,M天涯写真,M天涯汽车,M天涯娱乐,M天涯八卦,M天涯新闻,M天涯历史,M天涯贴图,M天涯交友,M天涯鬼故事,M天涯闲话,M天涯杂谈', 'keywords');
Yii::app()->clientScript->registerMetaTag(htmlentities($article->title).'|'.htmlentities($article->item->name).'|'.htmlentities($article->channel->name).'天涯热帖同步整理,提供天涯只看楼主功能,天涯热帖脱水,M天涯,免费百宝箱,http://mtianya.com/', 'description');

$this->pageTitle=str_replace('天涯', Yii::app()->name, $article->title)
                .((isset($_REQUEST['C_page']) && $_REQUEST['C_page']>0) ? '(第'.$_REQUEST['C_page'].'页)' : '['.Yii::app()->name.']');

$this->breadcrumbs=array(
	str_replace('天涯', Yii::app()->name, $article->channel->name)=>'/channel/'.$article->channel->id.'/',
	str_replace('天涯', Yii::app()->name, $article->item->name)=>'/item/'.$article->item->id.'/',
	str_replace('天涯', Yii::app()->name, $article->title)=>'/link-'.$article->id.'.html',
);
?>

<h2>
[作者:<?php echo CHtml::link($article->un,
'/search?cx='.Yii::app()->params['google_search_ad'].'&cof=FORID:11&ie=UTF-8&q='.$article->un.'&author='.$article->un,
array('target'=>'_blank',
'author'=> CHtml::encode($article->un),
'title'=>CHtml::encode($article->un),
'rev'=>'contents')); ?>]&nbsp;
[整理:<?php echo CHtml::link(Yii::app()->name, '/article-'.$article->id.'.html',
array('title'=>'或零整理,'.CHtml::encode($article->title),
'author'=>Yii::app()->name,
'title'=>CHtml::encode($article->title).'我的天涯,天涯整理,天涯脱水整理,天涯易读整理,天涯只看楼主,天涯热帖',
'rev'=>'index',
));?>]&nbsp;
[<?php
if (is_numeric($article->item->key)) {
	$src='http://www.tianya.cn/techforum/content/'.$article->item->key.'/1/'.$article->aid.'.shtml';
} else {
	$src='http://www.tianya.cn/publicforum/content/'.$article->item->key.'/1/'.$article->aid.'.shtml';
}
$ensrc='http://'.Yii::app()->params['domain'].'/api/a?href='.rawurlencode(base64_encode($src)).'&t='.rawurlencode(base64_encode($article->title));
echo CHtml::link('源帖',$ensrc,array('target'=>'_blank','title'=>CHtml::encode($article->title)));
?>]</h2>

<?php
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_article',
	'ajaxUpdate'=>false,
    'viewData'=>array('article'=>$article),
));
?>
<div style="float: right;clear: both;"><script type="text/javascript"><!--
google_ad_client = "pub-4726192443658314";
/* 336x280, 创建于 11-3-10 */
google_ad_slot = "4619865687";
google_ad_width = 336;
google_ad_height = 280;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>
<div style="float: right;"><script type="text/javascript"><!--
google_ad_client = "pub-4726192443658314";
/* 336x280, 创建于 11-3-10 */
google_ad_slot = "4619865687";
google_ad_width = 336;
google_ad_height = 280;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>
<?php
//$this->widget('application.components.AjaxBuild', array(
//	'type' => 'article',
//	'fid' => $article->id,
//));
//$cs = Yii::app()->getClientScript();
//$cs->registerCoreScript('jquery');

?>
<script type="text/javascript">
<?php
$js_mark=<<<EOF
var marks={};
function SetMark(aid,cid,pos,uid,title){
    if ($.cookie("marks") != null && $.cookie("marks") != "") {
        marks=$.evalJSON($.cookie("marks"));
    }else{
        marks={};
    }
    if(marks[aid]==undefined){
        marks[aid]={};
    }
    marks[aid]={'cid':cid, 'pos':pos, 'uid':uid, 'title':title};
//    _trace(marks, 'alert');
    $.cookie('marks', $.toJSON( marks ), { expires: 360000, path: '/' });
    init();
//    _trace($.evalJSON($.cookie("marks")), 'alert');
}

function GetMarks(){
    var marks={};
    if ($.cookie("marks") != null && $.cookie("marks") != "") {
        marks=$.evalJSON($.cookie("marks"));
    }
//    _trace(marks);
    return marks;
}

function init(){
    var cmarks=GetMarks();
    var h='';
    var l='';
    for(aid in cmarks){
        if (cmarks[aid] != null && cmarks[aid] != "") {
            if(cmarks[aid].cid>0){
                l='['+cmarks[aid].pos+'楼]';
                var page=((cmarks[aid].cid-1)/10|0)+1;
            }else{
                l='[顶楼]';
                var page=1;
            }

            h += '<div id="cmk_'+aid+'"><a href="'+'/article/'+aid+'/'+page+'.html#p'+cmarks[aid].pos+'">'+cmarks[aid].title+l+'</a>&nbsp;'
              +  '<span style="color: red;padding:2px;font-size: 18px;cursor:pointer;" onclick="DelMark('+aid+');">x</span><br />';
        }
    }
    //_trace(cmarks);
    $("#ec").html(h);
}

function DelMark(aid){
    marks=GetMarks();
    if (marks[aid] != null && marks[aid] != "") {
        marks[aid]=null;
    }
    $.cookie('marks', $.toJSON( marks ));
    $("#cmk_"+aid).remove();
}
EOF;
$packer = new JavaScriptPacker($js_mark, 'Normal', true, false);
echo $packer->pack();
?>
</script>
<?php
$cs=Yii::app()->clientScript;
//$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.lazyload.js');
//$cs->registerScript('lazyload',"
//$(\"img\").lazyload({
//placeholder  : \"/images/grey.gif\",
////failurelimit : 10,
//});
//",CClientScript::POS_READY  );

$cs->registerScript('it',"init();");

$this->widget('application.components.AjaxBuild', array(
	'type' => 'article',
	'fid'=>$article->id,
));

?>