<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/orzero.css" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-7387085-1']);
  _gaq.push(['_setDomainName', '.orzero.com']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><h1><a href="http://www.orzero.com"><?php echo CHtml::encode(Yii::app()->name); ?>网络热帖同步整理</a></h1>

<div style="float:right;">

<form action="http://www.orzero.com/search" id="cse-search-box">
  <div>
    <input type="hidden" name="cx" value="partner-pub-4726192443658314:lofclyqlq8w" />
    <input type="hidden" name="cof" value="FORID:11" />
    <input type="hidden" name="ie" value="UTF-8" />
    <input type="text" name="q" size="40" />
    <input type="submit" name="sa" value="&#x641c;&#x7d22;" />
  </div>
</form>
<script type="text/javascript" src="/js/google_search.js"></script> 
<script type="text/javascript">
var ifclick=false;
var sa=document.getElementsByName("sa")[0];
var q=document.getElementsByName("q")[0];
sa.setAttribute("value","或零站内搜索");
q.style.cssText = 'border: 1px solid #7e9db9; padding: 2px 10px;color:#666666;width:auto;';
q.value='可以输入文章标题、作者或者文章内容';
q.clicked=false;
var b = function() {
	if (q.value == '') {
		q.style.cssText = 'border: 1px solid #7e9db9; padding: 2px 10px;color:#666666;width:auto;';
		q.value='可以输入文章标题、作者或者文章内容';
		q.clicked=false;
	}
};

var f = function() {
	if(q.clicked==false){
		q.value='';
		q.style.color = '#000000';
		q.clicked=true;
	}
};

q.onfocus = f;
q.onblur = b;
</script>
</div>

<a style="display:inline-block;text-align:center;font-size:12px;" class="bt_c dlink" href="javascript:void((function(s,d,e){try{}catch(e){}var f='http://v.t.sina.com.cn/share/share.php?',u=d.location.href,p=['url=',e(u),'&title=',e(d.title),'&appkey=2924220432'].join('');function a(){if(!window.open([f,p].join(''),'mb',['toolbar=0,status=0,resizable=1,width=620,height=450,left=',(s.width-620)/2,',top=',(s.height-450)/2].join('')))u.href=[f,p].join('');};if(/Firefox/.test(navigator.userAgent)){setTimeout(a,0)}else{a()}})(screen,document,encodeURIComponent));"><div style=" cursor:pointer;"> <span style="text-align:center;">分享到新浪微博</span>        </div></a>
<a href="javascript:void(0)" onclick="postToWb();" class="bt_c dlink" style="display:inline-block;font-size:12px;">转播到腾讯微博</a><script type="text/javascript">
	function postToWb(){
		var _t = encodeURI(document.title);
		var _url = encodeURIComponent(document.location);
		var _appkey = encodeURI("appkey");//你从腾讯获得的appkey
		var _pic = encodeURI('');//（例如：var _pic='图片url1|图片url2|图片url3....）
		var _site = '';//你的网站地址
		var _u = 'http://v.t.qq.com/share/share.php?url='+_url+'&appkey='+_appkey+'&site='+_site+'&pic='+_pic+'&title='+_t;
		window.open( _u,'', 'width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no' );
	}
</script>



		</div>

	</div><!-- header -->

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'首页', 'url'=>'http://www.orzero.com/'),
				array('label'=>'频道', 'url'=>'http://www.orzero.com/or/'),
				array('label'=>'汇总', 'url'=>'http://www.orzero.com/zero/'),
				array('label'=>'排序', 'url'=>'http://www.orzero.com/all/'),
				array('label'=>'图片', 'url'=>'http://www.orzero.com/imgs/'),
				array('label'=>'地图', 'url'=>'http://www.orzero.com/site/map/'),
				array('label'=>'关于', 'url'=>'http://www.orzero.net/2011/03/orzero.html', 'linkOptions'=>array('target'=>'_blank')),
				array('label'=>'联系', 'url'=>array('http://www.orzero.com/site/contact')),
				//array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				//array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
//				array('url'=>Yii::app()->getModule('user')->loginUrl, 'label'=>Yii::app()->getModule('user')->t("Login"), 'visible'=>Yii::app()->user->isGuest),
//				array('url'=>Yii::app()->getModule('user')->registrationUrl, 'label'=>Yii::app()->getModule('user')->t("Register"), 'visible'=>Yii::app()->user->isGuest),
//				array('url'=>Yii::app()->getModule('user')->profileUrl, 'label'=>Yii::app()->getModule('user')->t("Profile"), 'visible'=>!Yii::app()->user->isGuest),
//				array('url'=>Yii::app()->getModule('user')->logoutUrl, 'label'=>Yii::app()->getModule('user')->t("Logout").' ('.Yii::app()->user->name.')', 'visible'=>!Yii::app()->user->isGuest),
			),
		)); ?>
	</div><!-- mainmenu -->

	<?php $this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>$this->breadcrumbs,
	)); ?><!-- breadcrumbs -->

	<?php echo $content; ?>

	<div id="footer">

		<div>Copyright &copy; <?php echo date('Y'); ?> By <?php echo CHtml::link('ORZERO', 'http://home.orzero.com'); ?>
		All Rights Reserved, <?php echo Yii::powered(); ?><br/>
<?php echo CHtml::link('OR\'ZERO BLOG', 'http://www.orzero.net'); ?>-
<?php echo CHtml::link('SITEMAP', 'http://www.orzero.com/sitemap.xml'); ?>-
<?php echo CHtml::link('OZERO RSS', 'http://www.orzero.com/rss.xml'); ?>-
<?php echo CHtml::link('OZERO ATOM', 'http://www.orzero.com/atom.xml'); ?>-
<?php echo CHtml::link('Feed RSS', 'http://feeds.feedburner.com/OrzeroReaderRss'); ?>-
<?php echo CHtml::link('Feed ATOM', 'http://feeds.feedburner.com/OrzeroReaderAtom'); ?>-
<?php echo CHtml::link('SOSO', 'http://www.soso.com/q?bs=site%3Awww.orzero.com&w=site%3Awww.orzero.com'); ?>-
<?php echo CHtml::link('GOOGLE', 'http://www.google.com/#sclient=psy&q=site:www.orzero.com&fp=1'); ?>-
<?php echo CHtml::link('BAIDU', 'http://www.baidu.com/s?wd=site%3Awww.orzero.com'); ?>-
<?php echo CHtml::link('FFUP', 'http://www.ffup.info/'); ?>-
<?php echo CHtml::link('AVUP', 'http://www.avup.info/'); ?>-
<?php echo CHtml::link('SNLG', 'http://www.snlg.info/'); ?>-
<?php echo CHtml::link('XXER', 'http://www.xxer.info/'); ?>-
<?php echo CHtml::link('英文版索引', 'http://translate.google.com/translate?hl=en&sl=zh-CN&tl=en&u=http%3A%2F%2Fwww.orzero.com%2F'); ?></div>
<script type="text/javascript"><!--
google_ad_client = "pub-4726192443658314";
/* 728x15, orzero.com 11-4-5 */
google_ad_slot = "6609878802";
google_ad_width = 728;
google_ad_height = 15;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
	</div><!-- footer -->

</div><!-- page -->


</body>
</html>