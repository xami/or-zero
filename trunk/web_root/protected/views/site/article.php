<?php 
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
//var marks={};
function SetMark(aid,cid,uid,title){
    if ($.cookie("marks") != null && $.cookie("marks") != "") {
        marks=$.evalJSON($.cookie("marks"));
    }else{
        marks={};
    }
    if(marks[aid]==undefined){
        marks[aid]={};
    }
    marks[aid]={'cid':cid, 'uid':uid, 'title':title};
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
                l='['+cmarks[aid].cid+'楼]';
            }else{
                l='[顶楼]';
            }
            var page=(cmarks[aid].cid/10|0)+1;
            h += '<div id="cmk_'+aid+'"><a href="'+'/article/'+aid+'/'+page+'.html#p'+cmarks[aid].cid+'">'+cmarks[aid].title+l+'</a>&nbsp;'
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
$cs->registerScript('lazyload',"
$(\"img\").lazyload({
placeholder  : \"/images/grey.gif\",
});
",CClientScript::POS_READY  );

$cs->registerScript('it',"init();");

$this->widget('application.components.AjaxBuild', array(
	'type' => 'article',
	'fid'=>$article->id,
));

?>