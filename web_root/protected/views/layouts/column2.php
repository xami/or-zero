<?php
Yii::app()->clientScript->registerMetaTag('我的天涯,mtiany,我的天涯阅读,我的天涯小说,我的天涯在线,我的天涯文学,我的天涯旅游,我的天涯情感,我的天涯自拍,我的天涯写真,我的天涯汽车,我的天涯娱乐,我的天涯八卦,我的天涯新闻,我的天涯历史,我的天涯贴图,我的天涯交友,我的天涯鬼故事,我的天涯闲话,我的天涯杂谈', 'keywords');
Yii::app()->clientScript->registerMetaTag('天涯热帖同步整理,提供天涯只看楼主功能,天涯热帖脱水,我的天涯,免费百宝箱', 'description');

$this->beginContent('//layouts/main'); ?>
<div class="container">
	<div class="span-19">
		<div id="content">
			<?php echo $content; ?>
		</div><!-- content -->
	</div>
	<div class="span-5 last">
		<div id="sidebar">
		<?php
			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'Operations',
			));
			$this->widget('zii.widgets.CMenu', array(
				'items'=>$this->menu,
				'htmlOptions'=>array('class'=>'operations'),
			));
			$this->endWidget();
		?>
		</div><!-- sidebar -->
	</div>
</div>
<?php $this->endContent(); ?>