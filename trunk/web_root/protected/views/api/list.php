<?php
if(!isset($_REQUEST['id'])||empty($_REQUEST['id'])){
    $this->pageTitle='我的天涯,天涯热帖同步整理';
}else{
    $this->pageTitle=$title;
}
$cs = Yii::app()->getClientScript();
$cs->registerCSSFile('/css/l.css');

Yii::app()->clientScript->registerLinkTag(
    'alternate',
    'application/rss+xml',
    'http://'.Yii::app()->params['domain'].'/sitemap.xml');
Yii::app()->clientScript->registerLinkTag(
    'alternate',
    'application/rss+xml',
    'http://'.Yii::app()->params['domain'].'/rss.xml');
Yii::app()->clientScript->registerLinkTag(
    'alternate',
    'application/rss+xml',
    'http://'.Yii::app()->params['domain'].'/atom.xml');

?>

<div>
    <?php echo $list;?>
    <?php echo $footer;?>
</div>

