<?php 
$this->pageTitle=str_replace('天涯', Yii::app()->name, $article->title)
                .((isset($_REQUEST['C_page']) && $_REQUEST['C_page']>0) ? '(第'.$_REQUEST['C_page'].'页)' : '['.Yii::app()->name.']');
//        .', '.str_replace('天涯', Yii::app()->name, $article->item->name)
//        .', '.str_replace('天涯', Yii::app()->name, $article->channel->name).', '.Yii::app()->name;

$this->breadcrumbs=array(
	str_replace('天涯', Yii::app()->name, $article->channel->name)=>'/or/'.$article->channel->id.'/',
	str_replace('天涯', Yii::app()->name, $article->item->name)=>'/ero/'.$article->item->id.'/',
	str_replace('天涯', Yii::app()->name, $article->title)=>'/orzero/'.$article->id.'/',
);
?>
<style type="text/css">
h1{font-size: 16px;}
h2{font-size: 14px;}
.view{
    border-bottom: 1px solid #004276;
    padding: 0;
}
.list-view .summary {
    border-bottom: 1px solid #004276;
    margin: 0;
}
.update_time {
    background: none repeat scroll 0 0 #DDDDDD;
    height: 24px;
    margin: 0;
}
.topic_potion {
    padding: 8px 4px 0;
    text-align: right;
}
.topic_potion a {
    text-decoration: none;
    border: 1px solid #004276;
    color: #004276;
    padding: 0 4px;
}
.update_time span.floor {
    color: #CCCCCC;
    display: block;
    float: right;
    font-size: 18px;
    font-style: italic;
    font-weight: bold;
    margin-right: 5px;
}
</style>
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
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
?>
<script type="text/javascript">
    jQuery.cookie = function (key, value, options) {

        // key and at least value given, set cookie...
        if (arguments.length > 1 && String(value) !== "[object Object]") {
            options = jQuery.extend({}, options);

            if (value === null || value === undefined) {
                options.expires = -1;
            }

            if (typeof options.expires === 'number') {
                var days = options.expires, t = options.expires = new Date();
                t.setDate(t.getDate() + days);
            }

            value = String(value);

            return (document.cookie = [
                encodeURIComponent(key), '=',
                options.raw ? value : encodeURIComponent(value),
                options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
                options.path ? '; path=' + options.path : '',
                options.domain ? '; domain=' + options.domain : '',
                options.secure ? '; secure' : ''
            ].join(''));
        }

        // key and possibly options given, get cookie...
        options = value || {};
        var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
        return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
    };


var _trace = function (x, traceType) {
    var type = typeof(x), message = '';

    switch (type) {
        case 'object':
            message = traceObj(x, traceType);
            break;
        default:
            message = typeof(x) + ': ' + x + (traceType && traceType == 'alert' ? '\n' : '<br>');
            break;
    }

    if (traceType && traceType == 'alert') {
        alert(message)
    } else {
        document.write(message)
    }

    function traceObj(x, traceType) {
        // 初始化对象属性
        if (traceObj.tabNum === undefined) {
            traceObj.tabNum = 0;
        }

        var notice = '';
        if (traceType && traceType == 'alert') {
            var tab = '\t', br = '\n';
        } else {
            var tab = '&nbsp;&nbsp;&nbsp;&nbsp;', br = '<br>';
        }

        notice += typeof(x) + br;
        for (var t = 0; t <traceObj.tabNum; t++) {
            notice += tab;
        }
        notice += '(' + br;
        for (var i in x) {
            for (var t = 0; t <= traceObj.tabNum; t++) {
                notice += tab;
            }
            if (typeof(x[i]) == 'object') {
                notice += '[' + i + '] => ';
                traceObj.tabNum++; // 增加缩进
                notice += traceObj(x[i], traceType);
                traceObj.tabNum--; // 减少缩进
            } else {
                notice += '[' + i + ']' + ' => ' + typeof(x[i]) + ': ' + x[i] + br;
            }
        }
        for (var t = 0; t <traceObj.tabNum; t++) {
            notice += tab;
        }
        notice += ')' + br;
        return notice;
    }
}
var marks;
function SetMark(aid,cid,uid,title){

    if ($.cookie("marks") != null && $.cookie("marks") != "") {
       marks=$.cookie("marks");
    }else{
        marks={};
    }
    _trace(marks, 'alert');
    
    if(marks[aid]==undefined){
        marks[aid]=[];
    }
    marks[aid]['cid']=cid;
    marks[aid]['uid']=uid;
    marks[aid]['title']=title;
    
    _trace(marks, 'alert');

    $.cookie('marks', marks, {expires: 360000, path: '/', domain: '<?php echo $_SERVER['SERVER_NAME'];?>', secure: true});
    _trace($.cookie("marks"), 'alert');
}
</script>
