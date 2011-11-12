<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <link type="text/css" rel="stylesheet" href="/css/layout-default-latest.css" />
    <link rel="stylesheet" href="/css/article.css"  type="text/css" />

    <script type="text/javascript" src="/js/jquery-latest.js"></script>
    <script type="text/javascript" src="/js/jquery-ui-latest.js"></script>
    <script type="text/javascript" src="/js/jquery.layout-latest.js"></script>
    <script type="text/javascript" src="/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="/js/jquery.json-2.3.js"></script>
<script type="text/javascript">
    <?php
$js_lay=<<<EOF
var outerLayout, westLayout, eastLayout;
$(document).ready(function() {
    outerLayout = $('body').layout({
            minSize:			100	// ALL panes
        ,	west__size:			200
        ,	east__size:			200
        ,	useStateCookie:		true
        ,	slidable:				false		// when closed, pane can 'slide open' over other panes - closes on mouse-out
//            ,	closable:				false
        ,	resizable:				false		// when open, pane can be resized
    });
    westLayout = $('div.ui-layout-west').layout({
            minSize:				50	// ALL panes
        ,	center__paneSelector:	".west-center"
        ,	south__paneSelector:	".west-south"
        ,	south__size:			300
        ,	resizable:				false
    });
    eastLayout = $('div.ui-layout-east').layout({
            minSize:				50	// ALL panes
        ,	center__paneSelector:	".east-center"
        ,	south__paneSelector:	".east-south"
        ,	south__size:			80
        ,	resizable:				false
    });
});
EOF;
    
    $packer = new JavaScriptPacker($js_lay, 'Normal', true, false);
    echo $packer->pack();
    ?>
</script>
</head>

<body>
<div class="ui-layout-center" id="ulc">
    <?php $this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>$this->breadcrumbs,
	)); ?><!-- breadcrumbs -->
    <h1>
        <a href="<?php echo Yii::app()->request->baseUrl; ?>"><?php echo CHtml::encode($this->pageTitle); ?></a>
    </h1>
    <?php echo $content; ?>
</div>

<div class="ui-layout-west">
	<div class="west-center" id="wc">

    </div>
	<div class="west-south" id="ws">  West - South </div>
</div>

<div class="ui-layout-east">
	<div class="east-center" id="ec">

	</div>

	<div class="east-south" id="es">  East - South </div>
</div>

</body>
</html>