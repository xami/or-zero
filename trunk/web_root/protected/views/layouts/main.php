<!doctype html>
<!--[if IE 5]><html dir="ltr" lang="cn-ZH" class="ie5 no-js"><![endif]-->
<!--[if IE 6]><html dir="ltr" lang="cn-ZH" class="ie6 no-js"><![endif]-->
<!--[if IE 7]><html dir="ltr" lang="cn-ZH" class="ie7 no-js"><![endif]-->
<!--[if IE 8]><html dir="ltr" lang="cn-ZH" class="ie8 no-js"><![endif]-->
<!--[if gt IE 8]><!-->
<html dir="ltr" lang="cn-ZH" class="no-js"><!--<![endif]-->
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link rel="stylesheet" type="text/css" href="/css/m.css" />
</head>

<body class="single">

<div class="container" id="page">
	<div id="header" class="cf">
        <hgroup>
            <h1><?php echo CHtml::encode(Yii::app()->name);
                $A_count=Yii::app()->cache->get('all_article_count');
                if(empty($A_count)){
                    $article=Article::model()->with(array('item','channel'))->find('`channel`.`status`=1 AND `item`.`status`= 1 AND `t`.`status`=1');
                    $A_count=$article->count();
                    Yii::app()->cache->set('all_article_count', $A_count, 600);
                }
                ?></h1>
            <span id="key" class="fz"><?php echo '[脱水整理贴数:'.$A_count.']';?>天涯热帖脱水整理,只看楼主,欢迎推荐给您的朋友</span>
            <form action="/search" name="t" class="main_search">
                <input type="hidden" name="cx" value="<?php echo Yii::app()->params['google_search_ad'];?>" />
                <input type="hidden" name="cof" value="FORID:11" />
                <input type="hidden" name="ie" value="UTF-8" />
                <input type="radio" name="un" value="or" />作者
                <input type="radio" name="un" value="zero" />标题
                <input type="text" maxlength="80" size="36" name="q" />
                <input type="submit" value="站内搜索" />
            </form>
            <span id="add_post" class="lz">查找原帖,如果没有整理?<br /> 同时可以提交到我的天涯</span>

            <div id="mainmenu">
                <?php $this->widget('zii.widgets.CMenu',array(
                'items'=>array(
                    array('label'=>'首页', 'url'=>'http://'.Yii::app()->params['domain'].'/'),
                    array('label'=>'最新整理', 'url'=>'http://'.Yii::app()->params['domain'].'/list-1.html'),
                    array('label'=>'最近更新', 'url'=>'http://'.Yii::app()->params['domain'].'/list-uptime-1.html'),
                    array('label'=>'访问最多', 'url'=>'http://'.Yii::app()->params['domain'].'/list-hot-1.html'),
                    array('label'=>'整理最多', 'url'=>'http://'.Yii::app()->params['domain'].'/list-pcount-1.html'),
                    array('label'=>'作者列表', 'url'=>'http://'.Yii::app()->params['domain'].'/orzero-author.html'),
                    array('label'=>'文章标签', 'url'=>'http://'.Yii::app()->params['domain'].'/tags/'),
                ),
            )); ?>
            </div><!-- mainmenu -->

            <?php if(isset($this->breadcrumbs)):?>

            <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                'links'=>$this->breadcrumbs,
                'tagName'=>'h2',
            )); ?><!-- breadcrumbs -->
            <?php endif?>


        </hgroup>
    </div>
</div><!-- header -->

	<?php echo $content; ?>
<div style="text-align: right;"><?php echo Tianya::ad728x90();?></div>
<div class="clear"></div>

	<div id="footer">
		&copy; <?php echo date('Y'); ?> <?php echo Tianya::powered(); ?>&nbsp;&nbsp;
        <a href="/site/contact">联系站长</a>&nbsp;&nbsp;站长QQ : <span id="im"></span>
        <form action="http://<?php echo Yii::app()->params['domain']; ?>/search" id="cse-search-box">
            <input type="hidden" name="cx" value="partner-pub-4726192443658314:4873446973" />
            <input type="hidden" name="cof" value="FORID:10" />
            <input type="hidden" name="ie" value="UTF-8" />
            <input type="text" name="q" size="38" />
            <input type="submit" name="sa" value="&#x641c;&#x7d22;" />
        </form>
		<br />
        友情链接: <a href="<?php echo 'http://'.Yii::app()->params['domain'];?>/sitemap.xml<?php if(isset($this->aid)) echo $this->aid.'.xml';?>">siemap.xml</a>&nbsp;-&nbsp;
		<a href="<?php echo 'http://'.Yii::app()->params['domain'];?>/sitemaps.xml">sitempas.xml</a>&nbsp;-&nbsp;
        <a href="<?php echo 'http://'.Yii::app()->params['domain'];?>/rss.xml">RSS</a>&nbsp;-&nbsp;
        <a href="<?php echo 'http://'.Yii::app()->params['domain'];?>/atom.xml">ATOM</a>&nbsp;-&nbsp;
        <a href="http://www.feediy.com" target="_blank">网站地图生成工具</a>&nbsp;-&nbsp;

        <a href="http://www.google.com/#sclient=psy&hl=en&q=site:<?php echo $_SERVER['HTTP_HOST']; ?>" target="_blank">Google索引</a>&nbsp;-&nbsp;
        <a href="http://www.sogou.com/web?query=site%3A<?php echo $_SERVER['HTTP_HOST']; ?>" target="_blank">Sogou索引</a>&nbsp;-&nbsp;
        <a href="http://www.soso.com/q?pid=s.idx&w=site%3A<?php echo $_SERVER['HTTP_HOST']; ?>" target="_blank">Soso索引</a>&nbsp;-&nbsp;
        <a href="http://www.youdao.com/search?q=site%3A<?php echo $_SERVER['HTTP_HOST']; ?>" target="_blank">Youdao索引</a>&nbsp;-&nbsp;
        <a href="http://cn.bing.com/search?q=site%3A<?php echo $_SERVER['HTTP_HOST']; ?>" target="_blank">Bing索引</a>&nbsp;-&nbsp;
        <a href="http://siteexplorer.search.yahoo.com/search?p=<?php echo $_SERVER['HTTP_HOST']; ?>" target="_blank">Yahoo索引</a>


        <script type="text/javascript" src="http://www.google.com/jsapi"></script>
        <script type="text/javascript">google.load("elements", "1", {packages: "transliteration"});</script>
        <script type="text/javascript" src="http://www.google.com/cse/t13n?form=cse-search-box&t13n_langs=en"></script>

        <script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=cse-search-box&amp;lang=zh-Hans"></script>

        <script type="text/javascript" src="http://www.google.com/cse/query_renderer.js"></script>
        <div id="queries"></div>
        <script src="http://www.google.com/cse/api/partner-pub-4726192443658314/cse/4873446973/queries/js?oe=UTF-8&amp;callback=(new+PopularQueryRenderer(document.getElementById(%22queries%22))).render"></script>

	</div><!-- footer -->
<div id='AdLayer1' style='position: absolute;visibility:hidden;z-index:1'><?php echo Tianya::ad160x600();?></div>
<div id='AdLayer2' style='position:absolute;visibility:hidden;z-index:1'><?php echo Tianya::ad160x600();?></div>

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

  <?php
      $js_reload=<<<EOF
  var m_layer1,m_layer2;
  function initMove() {
      m_layer1=document.getElementById("AdLayer1");
      m_layer2=document.getElementById("AdLayer2");
      m_layer1.style.top = "-200px";
      m_layer1.style.visibility = 'visible'
      m_layer2.style.top = "-200px";
      m_layer2.style.visibility = 'visible'
      MoveLayers();
      window.onscroll=MoveLayers;
  }
  function MoveLayers() {
      var x = 5; // 左右边距
      var y = 100; // 顶距
      var st=document.documentElement.scrollTop;
      var cw=document.documentElement.clientWidth;
      var y = st + y;
      m_layer1.style.top = y+"px";
      m_layer1.style.left = x+"px";
      m_layer2.style.top = y+"px";
      m_layer2.style.left = cw-m_layer2.clientWidth-x+"px";
  }
  window.setTimeout("initMove()",600);
EOF;

  $packer = new JavaScriptPacker($js_reload, 'Normal', true, false); echo $js_reload;
//  echo $packed = $packer->pack();
  ?>



</script>
<?php
$this->widget('application.components.OZDo', array(
'size'=>38,
'htmlOptions'=>array('class'=>'do','id'=>'dol','style'=>'display:none;')
));
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile('/js/jquery-ui/js/jquery-ui-1.8.16.custom.min.js');
$cs->registerCSSFile('/js/jquery-ui/css/redmond/jquery-ui-1.8.16.custom.css');
$cs->registerScript('dialogdol', '$( "#dol" ).dialog({ title:"整理新帖", autoOpen: false, width: 480, position: "center", resizable: false });', CClientScript::POS_READY);
$cs->registerScript('add_post', '$( "#add_post" ).click(function() {$( "#dol" ).dialog( "open" );return false;});', CClientScript::POS_READY);
$cs->registerScript('im', '$("#im").html("4481195");', CClientScript::POS_READY);
?>
</body>
</html>