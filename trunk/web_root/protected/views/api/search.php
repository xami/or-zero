<?php
$t=isset($_REQUEST['q']) ? trim($_REQUEST['q']) : '我的天涯';
$this->breadcrumbs=array(
	$t=>array('api/searchs',
		'cx'=>'partner-pub-4726192443658314:4873446973',
		'cof'=>'FORID:11',
		'ie'=>'UTF-8',
		'sa'=>'&#x641c;&#x7d22;',
		'q'=>$t,
	),
);

$this->pageTitle='或零 - 查找 : '.$t.' - '.Yii::app()->params['domain'];

//Yii::app()->clientScript->registerMetaTag(CHtml::encode($this->author), 'author');
//Yii::app()->clientScript->registerMetaTag(CHtml::encode($this->keywords), 'keywords', 'keywords');
//Yii::app()->clientScript->registerMetaTag(CHtml::encode($this->description), 'description');


Yii::app()->clientScript->registerCss('search', '
.list-view{width:725px;float:left;margin-left:5px;}
#cse-search-results{margin-left: 5px;}
');
Yii::app()->clientScript->registerScript('search', '
$("#cse-search-results iframe").attr("height", 1050);
', CClientScript::POS_READY);
?>

<div class="adl">
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
</div>

<?php 
//最近阅读
//$baseUrl='/search?cx='.Yii::app()->params['google_search_ad'].'&cof=FORID:11&ie=UTF-8&q=';
//$html=false;
//$html=Yii::app()->cache->get('search::recente');
//if($html===false){
//	$html='<div style="clear:both;"></div>';
//	//最近搜索
//	$criteria=new CDbCriteria;
//	$criteria->order='`uptime` DESC, `rcount` DESC';
//	$criteria->condition='`ccount`>1 AND `rcount`>0';
//	$criteria->limit=300;
//	$serachs=Searchs::model()->findAll($criteria);
//	$html.='<div class="item_e ui-dialog-content ui-widget-content"><span class="bt_c bt_e">'.
//		CHtml::link('最近搜索', '/tag/').'</span>';
//	foreach($serachs as $serach){
//		$html.='<span class="bt_e">'.CHtml::link($serach->key.'('.$serach->rcount.'结果)', array($baseUrl.$serach->key)).'</span>';
//	}
//	$html.='</div>'."\r\n</div>";
//
//	Yii::app()->cache->set('search::recente', $html, 300);
//}
//echo $html;


$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_search',
)); 


?>

<div id="cse-search-results"></div>
<script type="text/javascript">
  var googleSearchIframeName = "cse-search-results";
  var googleSearchFormName = "cse-search-box";
  var googleSearchFrameWidth = 730;
  var googleSearchDomain = "www.google.com";
  var googleSearchPath = "/cse";
</script>
<script type="text/javascript" src="http://www.google.com/afsonline/show_afs_search.js"></script>


