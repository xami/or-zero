<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <link type="text/css" rel="stylesheet" href="/css/layout-default-latest.css" />

    <style type="text/css">
    /* give outer-panes their own colors */
    .ui-layout-center	{ background: #CEE; }
    .ui-layout-west	 	{ background: #CCF; }
    .ui-layout-east		{ background: #CFC; }
    .west-center, .west-south{ background: #F0F0F0; }
    .east-center, .east-south{ background: #F0F0F0; }
    </style>

    <script type="text/javascript" src="/js/jquery-latest.js"></script>
    <script type="text/javascript" src="/js/jquery-ui-latest.js"></script>
    <script type="text/javascript" src="/js/jquery.layout-latest.js"></script>
    <script type="text/javascript">
    var outerLayout, westLayout, eastLayout;
    $(document).ready(function() {
        outerLayout = $('body').layout({
                minSize:			100	// ALL panes
            ,	west__size:			200
            ,	east__size:			200
            ,	useStateCookie:		true
            ,	slidable:				false		// when closed, pane can 'slide open' over other panes - closes on mouse-out
            ,	closable:				false
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
    </script>
</head>

<body>
<div class="ui-layout-center">
    <?php $this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>$this->breadcrumbs,
	)); ?><!-- breadcrumbs -->
    <h1>
        <a href="<?php echo Yii::app()->request->baseUrl; ?>"><?php echo CHtml::encode($this->pageTitle); ?></a>
    </h1>
    <?php echo $content; ?>
</div>

<div class="ui-layout-west">
	<div class="west-center">

    </div>
	<div class="west-south">  West - South </div>
</div>

<div class="ui-layout-east">
	<div class="east-center">

	</div>

	<div class="east-south">  East - South </div>
</div>

</body>
</html>