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

    <link rel="stylesheet" type="text/css" href="/css/layout-default-latest.css" />
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
    <script type="text/javascript" src="/js/jquery-latest.js"></script>
    <script type="text/javascript" src="/js/jquery-ui-latest.js"></script>
    <script type="text/javascript" src="/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="/js/jquery.json-2.3.js"></script>
    <script type="text/javascript" src="/js/jquery.lazyload.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/o.css" />
</head>

<body class="single single-post single-format-standard singular">

<div class="container" id="wrap">

    <header id="head" class="cf">
        <hgroup>
            <h1>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>"><?php echo CHtml::encode($this->pageTitle); ?></a>
            </h1>
            <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                'links'=>$this->breadcrumbs,
                'tagName'=>'h2',
                'htmlOptions'=>array()
            )); ?>
        </hgroup>
        <nav></nav>
    </header>
    <div id="body" class="cf">
        <?php echo $content; ?>
    </div>

    <footer id="foot">
        <?php echo Tianya::Footer($GLOBALS['aid']);?>
    </footer>

</div>

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