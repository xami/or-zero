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

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?>
<div class="search" style="float: right;font-size: 20px;"><form action="/search" name="t">
<input type="hidden" name="cx" value="<?php echo Yii::app()->params['google_search_ad'];?>" />
<input type="hidden" name="cof" value="FORID:11" />
<input type="hidden" name="ie" value="UTF-8" />
<input type="radio" name="un" value="or" />作者
<input type="radio" name="un" value="zero" />标题
<input type="text" maxlength="80" size="40" name="q" />
<input type="submit" value="站内搜索" />
</form> </div></div>
	</div><!-- header -->

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'首页', 'url'=>'http://'.Yii::app()->params['domain'].'/'),
                
                array('label'=>'最新整理', 'url'=>'http://'.Yii::app()->params['domain'].'/list-1.html'),
                array('label'=>'最近更新', 'url'=>'http://'.Yii::app()->params['domain'].'/list-uptime-1.html'),
                array('label'=>'访问最多', 'url'=>'http://'.Yii::app()->params['domain'].'/list-hot-1.html'),
                array('label'=>'整理最多', 'url'=>'http://'.Yii::app()->params['domain'].'/list-pcount-1.html'),
                array('label'=>'作者列表', 'url'=>'http://'.Yii::app()->params['domain'].'/orzero-author.html'),

//				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
//				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> By Mtianya.
		All Rights Reserved.<br/>
		<?php echo Tianya::powered(); ?>&nbsp;-&nbsp;
        <a href="<?php echo 'http://'.Yii::app()->params['domain'];?>/sitemap.xml<?php if(isset($this->aid)) echo $this->aid.'.xml';?>">siemap.xml</a>&nbsp;-&nbsp;
		<a href="<?php echo 'http://'.Yii::app()->params['domain'];?>/sitemaps.xml">sitempas.xml</a>&nbsp;-&nbsp;
        <a href="<?php echo 'http://'.Yii::app()->params['domain'];?>/rss.xml">RSS</a>&nbsp;-&nbsp;
        <a href="<?php echo 'http://'.Yii::app()->params['domain'];?>/atom.xml">ATOM</a><br />

        <a href="http://www.feediy.com" target="_blank">网站地图生成工具</a>&nbsp;-&nbsp;
        <a href="http://www.xxer.info" target="_blank">XXER</a>&nbsp;-&nbsp;
        <a href="http://www.orzero.com" target="_blank">或零</a>&nbsp;-&nbsp;
        <a href="http://www.orzero.net" target="_blank">或零日志</a>&nbsp;-&nbsp;
        
        <a href="http://www.google.com/#sclient=psy&hl=en&q=site:mtianya.com" target="_blank">Google索引</a>&nbsp;-&nbsp;
        <a href="http://www.sogou.com/web?query=site%3Amtianya.com" target="_blank">Sogou索引</a>&nbsp;-&nbsp;
        <a href="http://www.soso.com/q?pid=s.idx&w=site%3Amtianya.com" target="_blank">Soso索引</a>&nbsp;-&nbsp;
        <a href="http://www.youdao.com/search?q=site%3Amtianya.com" target="_blank">Youdao索引</a>&nbsp;-&nbsp;
        <a href="http://cn.bing.com/search?q=site%3Amtianya.com" target="_blank">Bing索引</a>&nbsp;-&nbsp;
        <a href="http://siteexplorer.search.yahoo.com/search?p=mtianya.com" target="_blank">Yahoo索引</a>&nbsp;-&nbsp;


        <form action="http://<?php echo Yii::app()->params['domain']; ?>/search" id="cse-search-box">
          <div>
            <input type="hidden" name="cx" value="partner-pub-4726192443658314:4873446973" />
            <input type="hidden" name="cof" value="FORID:10" />
            <input type="hidden" name="ie" value="UTF-8" />
            <input type="text" name="q" size="55" />
            <input type="submit" name="sa" value="&#x641c;&#x7d22;" />
          </div>
        </form>
        <script type="text/javascript" src="http://www.google.com/jsapi"></script>
        <script type="text/javascript">google.load("elements", "1", {packages: "transliteration"});</script>
        <script type="text/javascript" src="http://www.google.com/cse/t13n?form=cse-search-box&t13n_langs=en"></script>

        <script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=cse-search-box&amp;lang=zh-Hans"></script>

        <script type="text/javascript" src="http://www.google.com/cse/query_renderer.js"></script>
        <div id="queries"></div>
        <script src="http://www.google.com/cse/api/partner-pub-4726192443658314/cse/4873446973/queries/js?oe=UTF-8&amp;callback=(new+PopularQueryRenderer(document.getElementById(%22queries%22))).render"></script>

        

	</div><!-- footer -->

</div><!-- page -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-27014735-1']);
  _gaq.push(['_setDomainName', '.mtianya.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>