<?php 
$this->pageTitle=str_replace('天涯', Yii::app()->name, $article->title)
                .((isset($_REQUEST['C_page']) && $_REQUEST['C_page']>0) ? '(第'.$_REQUEST['C_page'].'页)' : '['.Yii::app()->name.']');

$this->breadcrumbs=array(
	str_replace('天涯', Yii::app()->name, $article->channel->name)=>'/or/'.$article->channel->id.'/',
	str_replace('天涯', Yii::app()->name, $article->item->name)=>'/ero/'.$article->item->id.'/',
	str_replace('天涯', Yii::app()->name, $article->title)=>'/orzero/'.$article->id.'/',
);
?>

<h2>
[作者:<?php echo CHtml::link($article->un,
'/search?cx=partner-pub-4726192443658314:lofclyqlq8w&cof=FORID:11&ie=UTF-8&q='.$article->un.'&author='.$article->un, 
array('target'=>'_blank',
'author'=> CHtml::encode($article->un),
'title'=>CHtml::encode($article->un),
'rev'=>'contents')); ?>]&nbsp;
[整理:<?php echo CHtml::link(Yii::app()->name, '/orzero-'.$article->id.'-index.html',
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
$ensrc='http://www.orzero.com/f/a?href='.rawurlencode(MCrypy::encrypt('a='.base64_encode($src).'&t='.$article->title, Yii::app()->params['mcpass'], 128));
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
    $.cookie('marks', $.toJSON( marks ));
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
            h += '<div id="cmk_'+aid+'"><a href="'+'/index.php/site/article?id='+aid+'#p'+cmarks[aid].cid+'">'+cmarks[aid].title+l+'</a>&nbsp;'
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
$cs->registerScript('it',"init();");
?>