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
jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        var path = options.path ? '; path=' + options.path : '';
        var domain = options.domain ? '; domain=' + options.domain : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};

function SetMark(aid,cid,uid,title){
    var marks={};
    if ($.cookie("marks") != null && $.cookie("marks") != "") {
       marks=$.cookie("marks");
    }
    marks.aid['cid']=cid;
    marks.aid['uid']=uid;
    marks.aid['title']=title;

    
    alert(marks);

    $.cookie('marks', marks, {expires: 999999999, path: '/', domain: '<?php echo $_SERVER['REMOTE_HOST'];?>', secure: true});
}

</script>