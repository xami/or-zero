<?php

class OZCommon
{
	public static $type='tianya';
	
	public static function getPopeyeItem()
	{
		$item=array();
		$files=File::model()->popeye()->findAll();
		
		$i=0;
		foreach($files as $file){
			$dir=Yii::getPathOfAlias('application.data.'.self::$type.
								'.'.$file->article->cid.
								'.'.$file->article->tid.
								'.'.$file->article->aid.
								'.img');
			
			$item[$i]['src']=self::mkFileApiLink($dir.DIRECTORY_SEPARATOR.
			$file->name.'.'.$file->type, $file->type, 510);
			
			$item[$i]['fsrc']=self::mkFileApiLink($dir.DIRECTORY_SEPARATOR.
			$file->name.'.'.$file->type, $file->type, 240, 173);
			
			$item[$i]['alt']=$file->article->title;
			
			if(isset($count[$file->article->aid])){
				$icount=$count[$file->article->aid];
			}else{
				$icount=$count[$file->article->aid]=File::model()->count('aid='.$file->aid.' AND fsrc!=\'\'');
			}
			
			$item[$i]['info']='<span>帖子:</span><a href="/orzero/'.$file->article->id.'/index.html">'.$file->article->title.'</a><br />'.
			'图片总数:'.$icount.'<br />'.
			'所在页:<a href="/orzero/'.$file->article->id.'/'.($file->pnum+1).'.html">第'.($file->pnum+1).'页</a>';
			$i++;
		}
		
		return $item;
	}
	
	public static $_pos;
	public static $_isTop=0;
	public function batText($temp, $pos)
	{
		$count=strlen($temp);
		self::$_pos=$pos;
		self::$dm=rawurlencode(MCrypy::encrypt(base64_encode(self::subString_UTF8(strip_tags($temp),0,512)), Yii::app()->params['mcpass'], 128));
		if(!empty($temp)){
			$temp=preg_replace_callback('/<img\s+src="(.*?)"\/><a\s+href="(.*?)">(.*?)<\/a>/i',array('self','mk_link'),$temp);
			$temp=preg_replace_callback('/(<a\s+.*?href=\s*([\"\']?))([^\'^\"]*?)((?(2)\\2)[^>^\/]*?>)(.*?)(<\/a>)/isx',array('self','mk_herf'),$temp);
		}
		self::$_isTop++;
		if(self::$_isTop==1){
			if($count>6000){
				return '
<div class="right">
<script type="text/javascript"><!--
google_ad_client = "pub-4726192443658314";
/* 160x600, 创建于 10-5-2 */
google_ad_slot = "6942291543";
google_ad_width = 160;
google_ad_height = 600;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>'.$temp;				
			}else{
				return '
<div class="right">
<script type="text/javascript"><!--
google_ad_client = "pub-4726192443658314";
/* 336x280, 创建于 11-3-10 */
google_ad_slot = "4619865687";
google_ad_width = 336;
google_ad_height = 280;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>'.$temp;
			}
		}else if(self::$_isTop==10){
			if($count>6000){
				return '
<div class="right">
<script type="text/javascript"><!--
google_ad_client = "pub-4726192443658314";
/* 160x600, 创建于 10-5-2 */
google_ad_slot = "6942291543";
google_ad_width = 160;
google_ad_height = 600;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>'.$temp;				
			}else if($count>2000){
				return '
<div class="right">
<script type="text/javascript"><!--
google_ad_client = "pub-4726192443658314";
/* 336x280, 创建于 11-3-10 */
google_ad_slot = "4619865687";
google_ad_width = 336;
google_ad_height = 280;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>'.$temp;
			}else if($count>200){
				return '
<div class="right">
<script type="text/javascript"><!--
google_ad_client = "pub-4726192443658314";
/* 468x60, 创建于 11-3-3 */
google_ad_slot = "7613266296";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>'.$temp;
			}
		}else{
			return $temp;
		}
		
	}
	
	public static $link;
	public static $dm;
	public function mk_herf($matches)
	{
		if(substr($matches[3],0,7)!=='http://'){
			return $matches[0];
		}
		$t=strip_tags($matches[5]);
		if(strlen($t)>128){
			$t=mb_substr($t, 0, 128);
		}
		$src='/api/a?href='.rawurlencode(MCrypy::encrypt('a='.base64_encode($matches[3]).
		'&t='.rawurlencode($t).'&r='.base64_encode(self::$link), Yii::app()->params['mcpass'], 128)).'&m='.self::$dm;
		return $matches[1].$src.$matches[2].' target="_blank">'.$matches[5].$matches[6];
	} 

	public static $_aid;
	public function mk_link($matches)
	{
		if($matches[3]=='(原图)'){
//			$op=strrpos($matches[1],'.');
//			if($op===false){
//				$ext1='';
//			}else{
//				$ext1=substr($matches[1], $op);
//			}
//			
//			$op=strrpos($matches[2],'.');
//			if($op===false){
//				$ext2='';
//			}else{
//				$ext2=substr($matches[2], $op);
//			}
			
			$S=File::model()->find('`src` LIKE :src AND aid=:aid', array(':src'=>$matches[1], ':aid'=>self::$_aid));
			if(!empty($S)){
				$dir=Yii::getPathOfAlias('application.data.'.self::$type.
								'.'.$S->article->cid.
								'.'.$S->article->tid.
								'.'.$S->article->aid.
								'.img');
				$img_s=self::mkFileApiLink($dir.DIRECTORY_SEPARATOR.$S->name.'.'.$S->type, $S->type, 510, 350);
			}else{
				$img_s=self::mkImageApiLink($matches[1], '', self::$_aid, self::$_pos);
			}
			
			$B=File::model()->find('`src` LIKE :src AND aid=:aid', array(':src'=>$matches[2], ':aid'=>self::$_aid));
			if(!empty($B)){
				$dir=Yii::getPathOfAlias('application.data.'.self::$type.
								'.'.$B->article->cid.
								'.'.$B->article->tid.
								'.'.$B->article->aid.
								'.img');
				$img_b=self::mkFileApiLink($dir.DIRECTORY_SEPARATOR.$B->name.'.'.$B->type, $B->type, 600, 400);
			}else{
				$img_b=self::mkImageApiLink($matches[2], $matches[1], self::$_aid, self::$_pos);
			}
			
			
//			$from='page='.self::getPagination()->currentPage.'&aid='.self::$_aid.'&src=';
//			$img_s='/index.php/f/?_='.rawurlencode(MCrypy::encrypt($from.$matches[1], Yii::app()->params['mcpass'], 128)).$ext1;
//			$img_b='/index.php/f/?_='.rawurlencode(MCrypy::encrypt($from.$matches[2].'&fsrc='.$matches[1], Yii::app()->params['mcpass'], 128)).$ext2;
			
			return '<a class="oz" rel="www.orzero.com" href="'.$img_b.'"><img src="'.$img_s.'"/></a><a target="_blank" href="'.$img_b.'">(原图)</a>';
		}else{
			return $matches[0];
		}
	}
	
	private static $_pagination;
	public function getPagination()
	{
		if(self::$_pagination===null)
		{
			self::$_pagination=new CPagination;
			self::$_pagination->pageVar='C_page';
		}
		return self::$_pagination;
	}
	
	public function jqueryFancybox()
	{
		$homeLink='/js/jqueryFancybox/fancybox/';
		$cs = Yii::app()->getClientScript();
		$cs->registerScriptFile($homeLink.'jquery.mousewheel-3.0.4.pack.js');
		$cs->registerScriptFile($homeLink.'jquery.fancybox-1.3.4.pack.js');
		$cs->registerCssFile($homeLink.'jquery.fancybox-1.3.4.css');
$js=<<<EOF
$("a[rel=orzero]").fancybox({
	'transitionIn'		: 'none',
	'transitionOut'		: 'none',
	'titlePosition' 	: 'over',
	'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
		return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
	}
});
EOF;

		$cs->registerScript('fancybox', $js, CClientScript::POS_READY);
	}
	
	public function jqueryLightbox()
	{
		$homeLink='/js/jqueryLightbox/';
		$cs = Yii::app()->getClientScript();
		$cs->registerScriptFile($homeLink.'js/jquery.lightbox-0.5.pack.js');
		$cs->registerCssFile($homeLink.'css/jquery.lightbox-0.5.css');
$js="
$(function() {
	$('div.items div.view a.oz').lightBox({
		overlayOpacity: 0.6,
		imageLoading: '".$homeLink."images/lightbox-ico-loading.gif',
		imageBtnClose: '".$homeLink."images/lightbox-btn-close.gif',
		imageBtnPrev: '".$homeLink."images/lightbox-btn-prev.gif',
		imageBtnNext: '".$homeLink."images/lightbox-btn-next.gif',
		imageBlank: '".$homeLink."images/lightbox-blank.gif',
		containerResizeSpeed: 350,
		txtImage: '<a href=\"http://www.orzero.com\">或零整理</a>',
		txtOf: '/'
					
	});
});
";

		$cs->registerScript('fancybox', $js, CClientScript::POS_READY);
	}
	
	public static function mkFileApiLink($file, $type, $width_new=0, $height_new=0, $key='orzero')
	{
		$id=serialize(array('file'=>$file, 'type'=>$type, 'width'=>$width_new, 'height'=>$height_new, 'key'=>$key));
		return YII::app()->createUrl('api/file', array('name'=>MCrypy::encrypt($id, Yii::app()->params['mcpass'], 128).'.'.$type));
	}
	
	public function mkImageApiLink($src, $fsrc, $aid, $pnum, $width_new=0, $height_new=0, $key='orzero')
	{
		$ext=self::getExt($src);
		$id=serialize(array('src'=>$src, 'fsrc'=>$fsrc, 'aid'=>$aid, 'pnum'=>$pnum, 'width'=>$width_new, 'height'=>$height_new, 'key'=>$key));
		return YII::app()->createUrl('api/image', array('src'=>MCrypy::encrypt($id, Yii::app()->params['mcpass'], 128).$ext));
	}
	
	public function getExt($src)
	{
		$op=strrpos($src,'.');
		if($op===false){
			$ext='';
		}else{
			$ext=substr($src, $op);
			if(preg_match('/^\.[a-zA-Z]+$/i', $ext, $m)){
				$ext=$m[0];
			}
		}
		return strtolower($ext);
	}
	
	public function saveImage($src, $fsrc, $aid, $pnum)
	{
		if(self::is_url($src)===false){
			return false;
		}

		if(($op=strrpos($src,'.'))===false){
			return false;
		}
		$ext=substr($src, $op);
		switch ($ext) {
			//case ".pdf": $ctype="application/pdf"; break;
			//case ".exe": $ctype="application/octet-stream"; break;
			//case ".zip": $ctype="application/zip"; break;
			//case ".doc": $ctype="application/msword"; break;
			//case ".xls": $ctype="application/vnd.ms-excel"; break;
			//case ".ppt": $ctype="application/vnd.ms-powerpoint"; break;
			case ".gif": $ftype="image/gif"; break;
			case ".png": $ftype="image/png"; break;
			case ".jpeg":
			case ".jpg": $ftype="image/jpg"; break;
			default: $ftype="application/force-download";
	    }
	    if(empty($ftype)){
	    	return false;
	    }
	    $article=Article::model()->with('channel')->findByPk($aid);
	    if(empty($article)){
	    	return false;
	    }
	    
	    self::$type=$article->channel->type;
	    $dir=self::getIMGPath($aid);
	    
	    if(!is_dir($dir))
			mkdir($dir,0777,true);
		
		//目前只有get方式
		$key=md5($src);
		$file=$dir.DIRECTORY_SEPARATOR.$key.$ext;
		if(!is_file($file)){
			if(self::getSnoopy($src)===false){
				return false;
			}
			//原站点缓存失败
			if(substr(self::$_results,0,1)=='<'){
				return false;
			}
			$size=strlen(self::$_results);
			if(file_put_contents($file, self::$_results)!==false){
				$fm=new File;			
				$fm->find('`src`=:src', array(':src'=>$src));
				$fm->aid=$aid;
				$fm->type=substr($ext,1);
				$fm->size=$size;
				$fm->pnum=$pnum;
				$fm->name=$key;
				$fm->src=$src;
				$fm->fsrc=$fsrc;
				$fm->time=time();
				$fm->save();
			}else{
				return false;
			}
			
			//大于100k图片加水印
			if($size>60*1024){
				Yii::import('application.extensions.image.Image');
				$image = new Image($file);
				
				$height=$image->height;
				$width=$image->width;
				if($width>1440){
					$height=intval((1440/$width)*$height);
					$width=1440;
				}
				if($height>900){
					$width=intval((900/$height)*$width);
					$height=900;
				}
				$image->resize($width, $height);
				$image->watermark();
				$image->save();
			}
		}
		
		if(is_file($file)){
			$results=file_get_contents($file);
			if($results!==false){
				$size=strlen($results);
				if(headers_sent()) die('Headers Sent');
//				if(ini_get('zlib.output_compression')) ini_set('zlib.output_compression', 'Off');			
				header("Pragma: public"); // required
				header("Cache-Control: max-age=864000");//24小时
				header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
				header('Expires:'.gmdate('D, d M Y H:i:s', time() + '864000').'GMT');  
				header("Cache-Control: private",false); // required for certain browsers
				header("Content-Type: $ftype"); 
				header("Content-Transfer-Encoding: binary");
			    header("Content-Length: ".$size);
			    ob_clean();
			    flush();
			    echo $results;
			    return true;
			}
		}
	}
	
	public function articleFeed($type='rss', $ctype='', $size=20, $tid=0, $cid=0)
	{	
		if($type!='rss' && $type!='atom'){
			return false;
		}
		
		$size= intval($size)>0 ? ((intval($size)>100) ? 100 : intval($size)) : 1;
		
		$tid=intval($tid);
		$cid=intval($cid);
		
		$criteria=new CDbCriteria;
		$criteria->with=array('channel', 'item');
		if(!empty($ctype)){
			$criteria->condition='`t`.`status`=1 AND `item`.`status`=1 AND `channel`.`status`=1 AND `channel`.`type`=:ctype';
			$criteria->params=array(':ctype'=>$ctype);
		}else{
			$criteria->condition='`t`.`status`=1 AND `item`.`status`=1 AND `channel`.`status`=1';
		}
		
		if($tid>0){
			$criteria->addCondition('t.tid='.$tid);
		}else if($cid>0){
			$criteria->addCondition('t.cid='.$cid);
		}

		$sort=new CSort('Article');
		$sort->multiSort=false; 
		$sort->defaultOrder="`t`.`mktime` DESC";
		$sort->sortVar='sort';
		$sort->attributes = array(
            'page' => 't.page',		//源帖页数
			'pcount' => 't.pcount',	//整理条数
            'reach' => 't.reach',	//源帖访问量
			'reply' => 't.reply',	//源帖回复数
            'hot' => 't.hot',		//本站访问量
			'mktime' => 't.mktime',	//最后创建
			'uptime' => 't.uptime',	//最后更新
        );
		
		$dataProvider=new CActiveDataProvider('Article', array(
			'criteria'=>$criteria,
			'sort'=>$sort,
			'pagination'=>array('pageSize'=>$size,),
		));
		$cache=YII::app()->cache;
		
		$sort_key=$dataProvider->getSort()->getOrderBy();
//		pr($sort_key);
		$sort_lang='';
		switch (trim($sort_key)){
			case '`t`.`page` DESC':
				$sort_lang='源帖页数最多';break;
			case '`t`.`page`':
				$sort_lang='源帖页数最少';break;
			case '`t`.`pcount` DESC':
				$sort_lang='或零整理最多';break;
			case '`t`.`pcount`':
				$sort_lang='或零整理最少';break;
			case '`t`.`reach` DESC':
				$sort_lang='源帖访问最多';break;
			case '`t`.`reach`':
				$sort_lang='源帖访问最少';break;
			case '`t`.`hot` DESC':
				$sort_lang='或零访问最多';break;
			case '`t`.`hot`':
				$sort_lang='或零访问最少';break;
			case '`t`.`mktime` DESC':
				$sort_lang='或零最新整理';break;
			case '`t`.`mktime`':
				$sort_lang='或零最早整理';break;
			case '`t`.`uptime` DESC':
				$sort_lang='或零最近更新';break;
			case '`t`.`uptime`':
				$sort_lang='或零最早更新';break;
		}
		
		
		Yii::import('application.vendors.*');
		require_once('Zend/Feed.php');
		require_once('Zend/Feed/'.ucfirst($type).'.php');
		
		
		$articles=$dataProvider->getData();
		$host=YII::app()->getRequest()->getHostInfo();
//		pr($articles);
		
		$data=false;
		$data=unserialize($cache->get(__FUNCTION__.'//'.$type.'//'.$ctype.'//'.$size.'//'.$tid.'//'.$cid.'//'.$sort_key));
	    if($data===false){
	    	$entries=array();
		    foreach($articles as $article)
		    {
//		    	pr($article->id);pr($article->item->name);pr($article->channel->name);echo '|';
		        $entries[]=array(
		            'title'=>$article->title,
		            'link'=>$host.'/orzero/'.$article->id.'/index.html',
		        	'description'=>htmlentities(self::getCText($article->id)),
		        	'guid'=>$article->id,
		        	'content'=>	self::getCText($article->id, 0, 4),
		            'source'=>array('title'=>$article->title, 'url'=>$host.'/orzero/'.$article->id.'/index.html'),
		        	'category' 	=>	array(
			        					array(
			        						'term' => $article->item->name,
	                                        'scheme' => $host.'/ero/'.$article->item->id.'/',
										),
										array(
			        						'term' => $article->channel->name,
	                                        'scheme' => $host.'/orz/'.$article->channel->id.'/',
										),
									),
		        				
		            'lastUpdate'=>$article->uptime,
		        );
//		        pd(self::getCText($article->id, 0));
		    }
		    $data=array(
		        'title'   => $sort_lang.($tid>0?('::'.$article->item->name):'').($cid>0?('::'.$article->channel->name):''),
		        'link'    => CHtml::encode($host.Yii::app()->getRequest()->getUrl()),
		    	'lastUpdate' => time(),
		    	'description' => '或零整理::'.ucfirst($type).'::'.$host.Yii::app()->getRequest()->getUrl(),
		    	'generator'	=> 'api.orzero.com',
		        'charset' => 'UTF-8',
		    	'author' =>	 'orzero.com',
		        'entries' => $entries,
		    );
			
		    if($type=='rss' || $type=='atom'){
				$feed_to_save=Zend_Feed::importArray($data, $type);
				@file_put_contents(dirname(Yii::app()->BasePath).DIRECTORY_SEPARATOR.$type.'.xml', $feed_to_save->saveXML());
			}
			
		    $cache->set(__FUNCTION__.'//'.$type.'//'.$ctype.'//'.$size.'//'.$tid.'//'.$cid.'//'.$sort_key, serialize($data), 600);
	    }
	    
	    //json输出
//	    $google=false;
//	    if($google===false){
//	    	$j=array("responseData"=>array('feed'=>$data), "responseDetails"=>null, "responseStatus"=>200);
//	    	echo 'jsonp'.substr((microtime(true)*1000),0,13).'('.json_encode($j).')';
//	    	return;
//	    }
	    
	    // generate and render RSS feed
	    $feed=Zend_Feed::importArray($data, $type);
		$feed->send();
			
	}
	
	
	
	public function getCText($aid, $start=0, $end=0)
	{
		$start=intval($start)>0 ? intval($start) : 0;
		$end=intval($end)>$start ? intval($end) : 0;
		
		//切换数据库
		OZPC::$OZPath=self::getOZPath($aid);
		OZPC::init();
		
		$c = new C();
		
		$text='';
		if($end==0){
			$_c = $c->findByPk($start);
			if(isset($_c->text)){
				$text=self::batText($_c->text, 0);
			}
		}else{
			for($i=$start;$i<=$end;$i++){
				$_c = $c->findByPk($i);
				if(isset($_c->text)){
					$text.=self::batText($_c->text,$i);
				}
			}
		}
		return $text;
	}
	
	public function getOZPath($aid)
	{
		$article=Article::model()->findByPk(intval($aid));
		if(empty($article)){
			return false;
		}
		self::$type=$article->channel->type;
		if(empty(self::$type)){
			return false;
		}
		
		$dir=Yii::getPathOfAlias('application.data.'.self::$type.'.'.
									$article->cid.'.'.
									$article->tid.'.'.
									$article->aid.'.db');
		if(!is_dir($dir))
			mkdir($dir,0777,true);
		return $dir;
	}
	
	public function getIMGPath($aid)
	{
		$article=Article::model()->findByPk(intval($aid));
		if(empty($article)){
			return false;
		}
		self::$type=$article->channel->type;
		if(empty(self::$type)){
			return false;
		}
		
		$dir=Yii::getPathOfAlias('application.data.'.self::$type.'.'.
									$article->cid.'.'.
									$article->tid.'.'.
									$article->aid.'.img');
		if(!is_dir($dir))
			mkdir($dir,0777,true);
		return $dir;
	}
	
	
	function is_url($url){
		$validate=new CUrlValidator();
		if(empty($url)){
			return false;
		}
		if($validate->validateValue($url)===false){
			return false;
		}
	    return true;
	}
	
	public static $_snoopy;
	public static $_results;
	public function getSnoopy($URI, $formvars="", $expire=60)
	{
		if(self::$_snoopy == null){
			self::$_snoopy = new Snoopy();
			self::$_snoopy->agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
			self::$_snoopy->rawheaders["Pragma"] = "no-cache";
		}
		
		if(empty($URI)){
			return self::$_snoopy;
		}
		
		if(self::is_url($URI)===false){
			return false;
		}
		self::$_snoopy->referer = $URI;

		$cache = Yii::app()->cache;		//默认缓存60秒远程数据
		if(is_array($formvars) && !empty($formvars)){
			$key = $URI.serialize($formvars);
			if((self::$_results=$cache->get($key))!==false)
				return self::$_results;
			if(self::$_snoopy->submit($URI,$formvars)===false){
				return false;
			}else{
				self::$_results=self::$_snoopy->results;
				$cache->set($key, self::$_results, $expire);
				return self::$_results;
			}
		}else{
			$key = $URI;
			if((self::$_results=$cache->get($key))!==false)
				return self::$_results;
			if(self::$_snoopy->fetch($URI)===false){
				return false;
			}else{
				self::$_results=self::$_snoopy->results;
				$cache->set($key, self::$_results, $expire);
				return self::$_results;
			}
		}
		return false;
	}
	
	
	public function upArticle($id=0,$pr=true)
	{	
		$time=time();
		$_err=-1;
		
		$_article=Article::model()->findByPk($id);
		if(empty($_article)){
			if($pr==true) die(json_encode($_err--.'a'));
		}
		
		//切换数据库
		OZPC::$OZPath=self::getOZPath($id);
		OZPC::init();
		$_page =new P();
		$_content =new C();
		
		//必须有链接，否则无法更新
		if(empty($_article->src)){
			if($pr==true) die(json_encode($_err--.'b'));
		}

		
		//没有板块信息
		if(empty($_article->item)) {
			if($pr==true) die(json_encode($_err--.'c'));
		}
		
		//第一次整理
		if($_article->cto<=1){
			$_article->cto=1;

			$page=$_page->findByPk(1);
			if(empty($page)){
				$page=clone $_page;
			}
			$page->id=1;
			$page->link=$_article->src;
			$page->count=0;
			$page->info='';
			$page->status=0;
			$page->mktime=$time;
			$page->uptime=$time;
			if($page->save()===false){
				if($pr==true) die(json_encode($_err--.'d'));
			}
		}else{
			$page=$_page->findByPk($_article->cto);
			if(empty($page)){
				$_article->cto=0;
				$_article->save();
				if($pr==true){
					die(json_encode($_err--.'e'));		//没有设置当前链接,直接中断整理
				}
			}
		}
		
		//取得uri数据
		if(($html=self::getSnoopy(html_entity_decode($page->link),'',300))===false){
			if($pr==true){
				die(json_encode($_err--.'f'));
			}
		}
		
		//校验页面是否下载完成
		$nav=self::cutContent($html, '<div class="p3">', '</div>');
		if(strpos($nav, '只看楼主')===false || strpos($nav, '最新回帖')===false || strpos($nav, '去底部')===false){
			if($pr==true) die(json_encode($_err--.'g'));
		}
		
			
		$fulltitle=self::cutContent($html, '<title>', '</title>');				//取得标题
		if(strpos($fulltitle, '[')===0 && $cut=strpos($fulltitle, ']')!==false){
			$tag=self::cutContent($fulltitle, '[', ']');
			$title_cut=explode(']', $fulltitle);
			$title=array_pop($title_cut);
		}else{
			$tag='';
			$title=$fulltitle;
		}
		
		
		$footer=self::cutContent($html, '<form  action="artgo.jsp"  method="get">', '</form>');
		if(strpos($footer, 'name="item"')===false || strpos($footer, 'name="id"')===false) {
			if($pr==true) die(json_encode($_err--.'h'));
		}
		
		$page_content=self::cutContent($html, '<div class="pg">', '</div>');
		$next_link=self::find_next_link($page_content);
		if(self::is_url($next_link)===false){
			if($pr==true) die(json_encode($_err--.'i'));
		}
		
		$next_page=$_page->findByPk($_article->cto+1);
		if(empty($next_page)){
			$next_page=clone $_page;
			$next_page->id=$_article->cto+1;
		}
		$next_page->link=$next_link;
		$next_page->count=0;
		$next_page->info='';
		$next_page->status=-1;
		$next_page->mktime=$time;
		$next_page->uptime=$time;
		
		//帖子列表
		$find=self::find_author_post(self::cutContent($html, '<div class="p3">', '<form  action="artgo.jsp"  method="get">'));
		if($find==false){
			if($pr==true) die(json_encode($_err--.'n'));
		}
		
		$page_cut_1=self::cutContent($footer, '(', '页)');
		if(!empty($page_cut_1)){
			$page_cut_2=explode('/', $page_cut_1);
			if(!isset($page_cut_2[1]) || empty($page_cut_2[1])){
				$find['page']=0;
			}else{
				$find['page']=intval($page_cut_2[1])>0 ? intval($page_cut_2[1]) : 0;
			}
		}else{
			$find['page']=0;
		}
		
		
		$_article->title=trim($title);
		$_article->tag=trim($tag);
		$_article->page=$find['page'];
		
		//作者保持固定不变,除非为空的情况
		(isset($find['un']) && !empty($find['un']) && !empty($_article->un)) && $_article->un=$find['un'];
		
		$_article->uptime=time();
		($page->id==1) && $_article->reach=isset($find['reach']) ? intval($find['reach']) : 0;
		($page->id==1) && $_article->reply=isset($find['reply']) ? intval($find['reply']) : 0;
		
		$page->count=isset($find['post']) ? count($find['post']) : 0;
		$page->status=1;

		
		if($page->save()===false){
			if($pr==true) die(json_encode($_err--.'j'));
		}
			
		if($next_page->save()===false){
			if($pr==true) die(json_encode($_err--.'k'));
		}
		
		if(isset($find['post']) && !empty($find['post'])) foreach($find['post'] as $post){
			$content = $_content->find('pos=:pos',array(':pos'=>$post['pos']));
			if(empty($content)){
				$content=clone $_content;
			}
			$content->pid=$page->id;
			$content->pos=$post['pos'];
			$content->text=$post['body'];
			$content->info='';
			$content->status=1;						//1正常，-1删除，2审核修改中（此时text内容进行serialize存储），3审核删除中
			$content->mktime=$time;
			$content->uptime=$time;

			if($content->save()===false){
				if($pr==true) die(json_encode($_err.'_'.$content->pos.'l'));
			}
				
		}
		$_err--;
		
		$_article->pcount=$_content->count();
		if(($next_page->id<=$find['page']) && ($next_page->link!==false))
			$_article->cto=$next_page->id;
		
//		pr($_article);
		if($_article->save()===false){
			if($pr==true) die(json_encode($_err--.'m'));
		}

		if($_article->page>$_article->cto+1){
			if($pr==true) 
				die(json_encode(1));	//继续循环
		}else{
			if($pr==true) die(json_encode($_err--.'n'));
		}
	}
	
	public function upItem($id=0,$pr=true)
	{
		$_err=-1;
		$tid=intval($id);
		$_item=Item::model()->findByPk($tid);
		if(empty($_item)) {
			if($pr)
				die(json_encode($_err--.'a'));
			else 
				return false;
		}
		
		$page=intval($_REQUEST['loop'])<0 ? 0 : intval($_REQUEST['loop']);	//当前板块页码
		if($page>20) {
			if($pr)
				die(json_encode($_err--.'b'));
			else 
				return false;
		}						//更新前20页
		
		$_url='http://3g.tianya.cn/bbs/list.jsp?item='.$_item->key.'&p='.$page;
		if(($html=self::getSnoopy($_url,'',600))===false){
			if($pr)
				die(json_encode($_err--.'c'));
			else 
				return false;
		}
		
		$title=self::cutContent($html, '<br/>'."\r\n".'论坛-', "\r\n".'</div>');
		
		//校验页面是否下载完成
		if($_item->name!=$title) {
			if($pr)
				die(json_encode($_err--.'d'));
			else 
				return false;
		}
		$footer=self::cutContent($html, '<div class="lk">', '<br/>');
		if(strpos($footer, '下一页')===false ||strpos($footer, $_item->key)===false){
			if($pr)
				die(json_encode($_err--.'e'));
			else 
				return false;
		}
		
		//帖子列表
		$content=self::cutContent($html, '<div class="p">', '</div>');
		$find=self::find_article_info($content);
		
		if(isset($find['link']) && isset($find['content'])){
			if(empty($find['link']) || (($count = count($find['link'])) !== count($find['content']))){
			if($pr)
				die(json_encode($_err--.'f'));
			else 
				return false;
		}
			
			$article= new Article();
			$criteria=new CDbCriteria;
			
			$_article=array();
			for($i=0;$i<$count;$i++){
				if(!isset($find['reach'][$i]) || !isset($find['reply'][$i]))		//原帖未初始化
					continue;
				if($find['reply'][$i]<1000 || $find['reach'][$i]<100000)	//没有100000访问量 或者 没有10000回复
					continue;
				
				$aid=intval(self::cutContent($find['link'][$i], '&id=', '&idwriter=0&key=0&chk='));
				if($aid<0){
					if($pr)
						die(json_encode($_err--.'g'));
					else 
						return false;
				}
				if(strpos($find['content'][$i], '[')===0 && $cut=strpos($find['content'][$i], ']')!==false){
					$tag=self::cutContent($find['content'][$i], '[', ']');
					$title_cut=explode(']', $find['content'][$i]);
					$title=array_pop($title_cut);
				}else{
					$tag='';
					$title=$find['content'][$i];
				}
				$un=$find['author'][$i];
				$time=time();
				$src='http://3g.tianya.cn/bbs/'.$find['link'][$i];
				
				$criteria->condition='`tid`=:tid AND `aid`=:aid';
				$criteria->params=array(':tid'=>$tid, ':aid'=>$aid);
				$_article[$i] = $article->find($criteria);
				
				if(isset($_article[$i]->id) && $_article[$i]->id>0){
					//不更新状态，跳过
					if($_article[$i]->status!=1) continue;
					if($_article[$i]->title != $title || $_article[$i]->tag != $tag || $_article[$i]->un != $un){
						$_article[$i]->title = trim($title);
						$_article[$i]->tag = trim($tag);
						!empty($_article[$i]->un) && $_article[$i]->un = $un;
						$_article[$i]->reach = $find['reach'];
						$_article[$i]->reply = $find['reply'];
						$_article[$i]->save();
					}
				}else{
					$_article[$i] = clone $article;
					$_article[$i]->cid=$_item->cid;
					$_article[$i]->tid=$tid;
					$_article[$i]->aid=$aid;
					$_article[$i]->title=trim($title);
					$_article[$i]->tag=trim($tag);
					$_article[$i]->key='';
					$_article[$i]->page=0;
					$_article[$i]->un=$un;
					$_article[$i]->cto=0;
					$_article[$i]->pcount=0;
					$_article[$i]->mktime=$time;
					$_article[$i]->uptime=$time;
					$_article[$i]->src=urldecode($src);
					$_article[$i]->status=1;
					$_article[$i]->reach = $find['reach'][$i];
					$_article[$i]->reply = $find['reply'][$i];
					$_article[$i]->hot = 0;
					$_article[$i]->save();
					try{
						self::upArticle($_article[$i]->id,false);
					}catch(Exception $e){
						if(($page-3)>1){
							pd($page-3);
						}else{
							pd(1);
						}
					}
				}
			}
		}
		
		//更新统计
		if($page==20){
			$total=Yii::app()->db->createCommand()
					->select('count(*) as count')
					->from('{{article}}')
					->where('tid=:tid AND cid=:cid', array(':tid'=>$_item->id, ':cid'=>$_item->cid))
					->queryRow();
			//pr($count);
			$_item->uptime=time();
			$_item->count=$total['count'];
			$_item->save();
		}
		
		{
			if($pr)
				die(json_encode(++$page));
			else 
				return true;
		}
	}
	
	public function upChannel($id=0,$pr=true)
	{
		$_err=-1;
		$key=intval($id);
		//配置轮询的范围
		if($key<0 || $key>19){
			if($pr)
				die(json_encode($_err--.'a'));
			else 
				return false;
		}
		
		$_url='http://3g.tianya.cn/nav/more.jsp?chl='.$key;
		//缓存一周
		if(($html=self::getSnoopy($_url,'',3600*24*7))===false){
			if($pr)
				die(json_encode($_err--.'b'));
			else 
				return false;
		}
		
		//校验页面是否下载完成
		$title=self::cutContent($html, '<title>天涯导航_', '</title>');
		if(strlen($title)<2){
			if($pr)
				die(json_encode($_err--.'c'));
			else 
				return false;
		}
		$footer=self::cutContent($html, '<div class="f" id="bottom">', '</div>');
		if(strpos($footer, '天涯首页')===false){
			if($pr)
				die(json_encode($_err--.'d'));
			else 
				return false;
		}
		
		$channel = new Channel();
		$_channel = $channel->find('`key` LIKE :key', array(':key'=>$key));
		
		//更新频道的item列表
		$content=self::cutContent($html, '<div class="p">', '</div>');
		$find=self::find_item_info($content);		
		//print_r($find);
		
		if(isset($find['key']) && isset($find['name'])){
			if(empty($find['key']) || (($count = count($find['key'])) !== count($find['name']))){
			if($pr)
				die(json_encode($_err--.'e'));
			else 
				return false;
		}
			
			
			//保存频道
			if(isset($_channel->id) && $_channel->id>0){
				if($_channel->status!=1)
					//die(json_encode(++$key));	//控制js进入下一个channel查询
				{
					if($pr)
						die(json_encode($_err--.'f'));
					else 
						return false;
				}
				
				if(($title != $_channel->name) || ($count != $_channel->count)){
					$_channel->name = trim($title);
					$_channel->count = $count;
					$_channel->save();
				}
			}else{
				$_channel = clone $channel;
				$_channel->key = $key;
				$_channel->name = trim($title);
				$_channel->count = $count;
				$_channel->status = 1;
				$_channel->uptime = time();
				$_channel->type = 'tianya';
				$_channel->save();
			}
			$item=new Item();
			$_item=array();
			for($i=0;$i<$count;$i++){
				$_item[$i] = $item->find('`cid`=:cid AND `key` LIKE :key', 
					array(':cid'=>$_channel->id,':key'=>$find['key'][$i]));
				
				if(isset($_item[$i]->id) && $_item[$i]->id>0){
					//不更新item跳过
					if($_item[$i]->status!=1) continue;
					if($_item[$i]->name != $find['name'][$i]){
						$_item[$i]->name = $find['name'][$i];
						$_item[$i]->save();
					}
				}else{
					$_item[$i] = clone $item;
					$_item[$i]->cid=$_channel->id;
					$_item[$i]->key=$find['key'][$i];
					$_item[$i]->name=$find['name'][$i];
					$_item[$i]->count=0;
					$_item[$i]->status=1;
					$_item[$i]->save();
				}
			}
		}
		
		//die(json_encode(++$key));	//控制js进入下一个channel查询
		{
			if($pr)
				die(json_encode(++$key));
			else 
				return false;
		}
	}
	
	
	//取得字段间字符
	public static function cutContent($content='', $start='', $end='')
	{	
		$e_start=explode($start, $content);
		if(!isset($e_start[1]))
			return false;
		$e_end=explode($end, $e_start[1]);
		if(!isset($e_end[1]))
			return false;
		return $e_end[0];
	}
	
	/**
	 * 取得文章信息第二版
	 * 便于直接文章转向
	 * 重写去掉冗余流程
	 */
	public function getTYPE($html)
	{
		if(empty(self::$type)){
			throw new CException('没有指定类型'); 
		}
		
		if(!method_exists(__CLASS__, 'get_'.self::$type)){
			throw new CException('不存在验证指定类型的信息取得方法'); 
		}
		
		//取得页面的信息,便于入库article表
		return call_user_func(array(__CLASS__, 'get_'.self::$type), $html);
	}
	
	public function isTYPE($src)
	{
		if(empty(self::$type)){
			throw new CException('没有指定类型'); 
		}
		
		if(!method_exists(__CLASS__, 'is_'.self::$type)){
			throw new CException('不存在验证指定类型的验证方法'); 
		}
		
		//校验页面是否下载完成,并且是否为当前类型的帖子
		return call_user_func(array(__CLASS__, 'is_'.self::$type), $src);
	}
	
	public function get_tianya($html)
	{
		$info=array();
		
		//title,tag
		$full_title=trim(self::cutContent($html, '<title>', '</title>'));
		$title_tag=self::cut_title_tag($full_title);
		$info['tag']=$title_tag['tag'];
		$info['title']=$title_tag['title'];
		
		//reach,replay,un
		$head=self::cutContent($html, '<div class="lk">楼主:', 'div class="lk">');
		$info['reach']=intval(self::cutContent($head, '访问:', '回复:'));
		$info['reply']=intval(self::cutContent($head, '回复:', '<br/>'));
		$info['un']=self::cutContent($head, '">', '</a><br/>');
		
		//page
		$footer=self::cutContent($html, 'action="art.jsp"', '</form>');
		$info['page']=intval(self::cutContent($footer, '(1/', '页)'));
		
		return $info;
	}
	
	public function cut_title_tag($full_title)
	{
		$full_title=trim($full_title);
		if(empty($full_title)){
			return false;
		}
		$cut=strpos($full_title, ']');
		if(strpos($full_title, '[')===0 && $cut!==false){
			$tag=self::cutContent($full_title, '[', ']');
			$title=mb_substr($full_title, $cut+1);
		}else{
			$tag='';
			$title=$full_title;
		}

		return array(
			'tag'=>$tag,
			'title'=>$title,
		);
	}
	
	public function is_tianya($src)
	{
		//是否是正常的url
		if(self::is_url($src)===false){
			return array('500'=>'请提供正确的链接地址,并且需要在前面加http://');
		}
		
		//解析帖子信息
		//如果是普通版链接,转换成3G版
		$pu=parse_url($src);
		if($pu['host']=='www.tianya.cn' || $pu['host']=='tianya.cn'){
			$path=explode('/', $pu['path']);
			if(count($path)!=6 || !empty($path[0])){
				return array('500'=>'只支持主板和副版的内容整理');
			}
			$key=$path[3];
			if(strlen($key)<2){
				return array('500'=>'只支持主板和副版的内容整理');
			}
			$aid=intval($path[5]);
			if($aid<1){
				return array('500'=>'只支持主板和副版的内容整理');
			}
			$rt_src='http://3g.tianya.cn/bbs/art.jsp?item='.$key.'&id='.$aid;
		}else if($pu['host']=='3g.tianya.cn'){
			parse_str($pu['query'], $path);
			if(empty($path['item']) || empty($path['id']) || !is_numeric($path['id'])){
				return array('500'=>'只支持主板和副版的内容整理');
			}
			$key=trim($path['item']);
			if(strlen($key)<2){
				return array('500'=>'只支持主板和副版的内容整理');
			}
			$aid=intval($path['id']);
			if($aid<1){
				return array('500'=>'只支持主板和副版的内容整理');
			}
			$rt_src='http://3g.tianya.cn/bbs/art.jsp?item='.$key.'&id='.$aid;
		}else{
			return array('500'=>'只支持主板和副版的内容整理');
		}

		//取得板块和频道编号
		$item=Item::model()->find('`key` LIKE :key AND status=1', array(':key'=>$key));
		if(!isset($item->id) || empty($item->id)){
			return array('500'=>'很抱歉,此版块内容不予整理');
		}
		if(!isset($item->channel->id) || empty($item->channel->id)){
			return array('500'=>'很抱歉,此版块内容不予整理');
		}
		
		//取得当前链接html内容
		$html=self::getSnoopy($rt_src, '', 3600);
		if($html===false){
			return array('500'=>'获取内容失败,网络繁忙或者对方服务忙,请稍后再试');
		}
		
		//校验是否是首页,不是则重新取得首页内容
		$pg=self::cutContent($html, '<div class="pg">', '</div>');
		preg_match_all("'<\s*a\s.*?href\s*=\s*([\"\'])?(?(1)(.*?)\\1|([^\s\>]+))[^>]*>?(.*?)</a>'isx",$pg,$links);
		if(!isset($links[4][0])){
			return array('550'=>'帖子页数还未满一页,需要整理吗?不需要吗?需要吗?...');
		}
		if($links[4][0]=='首页'){	//不是首页,则会显示跳转到首页的链接
			if(!isset($links[2][0]) || empty($links[2][0])){
				return array('500'=>'无法正常分析此页内容,我承认是我的失败');
			}
			$index_parse=parse_url($links[2][0]);
			if(!isset($index_parse['path']) || $index_parse['path']!='art.jsp'){
				return array('500'=>'无法正常分析此页内容,我承认是我的失败');
			}
			if(!isset($index_parse['query']) || empty($index_parse['query'])){
				return array('500'=>'无法正常分析此页内容,我承认是我的失败');
			}
			
			parse_str(html_entity_decode($index_parse['query']), $index_str);
			
			if(!isset($index_str[item]) || $index_str[item]!=$key){
				return array('500'=>'无法正常分析此页内容,我承认是我的失败');
			}
			if(!isset($index_str[id]) || intval($index_str[id])!=$index_str[id]){
				return array('500'=>'无法正常分析此页内容,我承认是我的失败');
			}
			
			//重新取得首页内容
			$aid=intval($index_str[id]);
			$rt_src='http://3g.tianya.cn/bbs/art.jsp?item='.$key.'&id='.$aid;
			$html=self::getSnoopy($rt_src);
			if($html===false){
				return array('500'=>'获取内容失败,网络繁忙或者对方服务忙,请稍后再试');
			}
		}else if($links[4][0]!='下一页'){
			return array('500'=>'无法正常分析此页内容,我承认是我的失败');
		}
		
		
		
		//正文内容之前的头部验证
		$nav=self::cutContent($html, '<div class="p3">', '</div>');
		if(strpos($nav, '只看楼主')===false || strpos($nav, '最新回帖')===false || strpos($nav, '去底部')===false){
			return array('500'=>'获取远程链接内容失败或者不是天涯的帖子');
		}
		
		//正文内容之后的翻页表单验证,顺便校验页面是否完整
		//多于1页,会有get形式的翻页表单,如果没有则返回错误
//        pd($html);
		$form_get=self::cutContent($html, 'action="art.jsp"', '</form>');
		if(strpos($form_get, 'idwriter')===false || strpos($form_get, 'item')===false || strpos($form_get, '转到该页')===false){
			return array('500'=>'获取远程链接内容失败或者不是天涯的帖子');
		}
		
		
		return array('200'=>
		array(
			'html'=>$html,
			'cid'=>$item->channel->id,
			'tid'=>$item->id,
			'aid'=>$aid,
			'src'=>$rt_src,
		));
	}
	
	//取得下页链接
	function find_next_link($document) {   
	    preg_match_all("'<\s*a\s.*?href\s*=\s*([\"\'])?(?(1)(.*?)\\1|([^\s\>]+))[^>]*>?(.*?)</a>'isx",$document,$links);                       
	    while(list($key,$val) = each($links[2])) {
	        if(!empty($val))
	            $match['link'][] = $val;
	    }
	    while(list($key,$val) = each($links[3])) {
	        if(!empty($val))
	            $match['link'][] = $val;
	    }       
	    while(list($key,$val) = each($links[4])) {
	        if(!empty($val))
	            $match['content'][] = $val;
	    }
	    //pd($match['link']);
	    if(!isset($match['content']) || empty($match['content'])){
	    	return false;
	    }
	    $key = array_search('下一页', $match['content']);
	    if(isset($match['link'][$key]))
	    	return 'http://3g.tianya.cn/bbs/'.$match['link'][$key];
	    return false;
	}
	
	//取得楼主回复以及帖子信息
	function find_author_post($document) {   
		$match=false;
	    preg_match_all("'<div\s+class=\"lk\">(.*?)</div>[\r\n]*?<div\s+class=\"sp\s+lk\">(.*?)</div>'isx",$document,$cut);                       
	    if((isset($cut[1][0]) && isset($cut[2][0])) && ($count=count($cut[1]))===count($cut[2])){
		    $j=0;
			for($i=0;$i<$count;$i++){
				$head=$cut[1][$i];
				$body=$cut[2][$i];

				if(strpos($head, '楼主:')===0){	//匹配顶楼
					$match['reach']=intval(self::cutContent($head, '访问:', '回复:'));
					$match['reply']=intval(self::cutContent($head, '回复:', '<br/>'));
					unset($body_info);
					preg_match_all("'(.*?)(<br\/>[\W]{6})*?<a\shref=\"?rep\.jsp\?'isx",$body,$body_info);
					if(!isset($body_info[1][0]))
						return false;
					$match['post'][$j]['body']=$body_info[1][0];
					$match['post'][$j]['pos']=0;
					$j++;
					$match['un']=self::cutContent($head, '">', '</a><br/>');
				}else if(strpos($head, '<span class="red">楼主</span>')!==false){
					unset($body_info);
					preg_match_all("'(.*?)(<br\/>[\W]{6})*?<a\shref=\"?rep\.jsp\?[^>]+?>.*?(\d+?)[^\d]+</a>'isx",$body,$body_info);
					$match['post'][$j]['body']=$body_info[1][0];
					$match['post'][$j]['pos']=$body_info[3][0];
					$j++;
				}else{
					continue;
				}
			}
	    }
	    
	    return $match;
	}
	
	//取得帖子信息
	function find_article_info($document) {   
	    preg_match_all("'<\s*a\s.*?href\s*=\s*([\"\'])?(?(1)(.*?)\\1|([^\s\>]+))[^>]*>?(.*?)</a>'isx",$document,$links);                       
	    while(list($key,$val) = each($links[2])) {
	        if(!empty($val))
	            $match['link'][] = html_entity_decode($val);
	    }
	    while(list($key,$val) = each($links[3])) {
	        if(!empty($val))
	            $match['link'][] = html_entity_decode($val);
	    }       
	    while(list($key,$val) = each($links[4])) {
	        if(!empty($val))
	            $match['content'][] = $val;
	    }
	    /*
	    while(list($key,$val) = each($links[0])) {
	        if(!empty($val))
	            $match['all'][] = $val;
	    }
	    */
	    //访问数，回复数，作者
	    preg_match_all("'<span\s.*?class=\s*([\"\'])?(?(1)gray\\1)[^>]*?>\s*?\((\d+?)/(\d+?)\s+?(.*?)\)</span>'isx",$document,$info);
		while(list($key,$val) = each($info[2])) {
	        if(!empty($val))
	            $match['reach'][] = $val;
	    }
		while(list($key,$val) = each($info[3])) {
	        if(!empty($val))
	            $match['reply'][] = $val;
	    }
		while(list($key,$val) = each($info[4])) {
	        if(!empty($val))
	            $match['author'][] = $val;
	    }
	    
	    return $match;
	}
	
	//取得频道信息
	function find_item_info($html) {    
		preg_match_all("'<\s*a\s.*?href\s*=\s*([\"\'])?(?(1)(.*?)\\1|([^\s\>]+))[^>]*>?(.*?)</a>'isx",$html,$links);  
		while(list($key,$val) = each($links[2])) {
			if(!empty($val)){
				$parse_url=parse_url($val);
				parse_str($parse_url['query'], $parse1);
				$match['key'][] = $parse1['item'];
			}
		}		
		while(list($key,$val) = each($links[3])) {
			if(!empty($val)){
				$parse_url=parse_url($val);
				parse_str($parse_url['query'], $parse2);
				$match['key'][] = $parse2['item'];
			}
		}
		while(list($key,$val) = each($links[4])) {
			if(!empty($val))
				$match['name'][] = $val;
		}
	    /*
	    while(list($key,$val) = each($links[0])) {
	        if(!empty($val))
	            $match['all'][] = $val;
	    }
	    */              
	    return $match;
	}
	
	public function t($str='',$params=array()) {
		return Yii::t('site', $str, $params);
	}
	
	public function getMenu($type)
	{
		$cache=YII::app()->cache;
		$menu=$cache->get('ORZERO::MENU');
		if($menu!==false){
			return unserialize($menu);
		}
		
		$host=YII::app()->getRequest()->getHostInfo();
		$channels=Channel::model()->findAll('type=:type AND status=1', array(':type'=>$type));
		$menu=array();
		if(!empty($channels)){
			foreach($channels as $channel){
				$c=array();
				$c['label']=str_replace(self::t($type), '或零', $channel->name).'('.Item::model()->count('cid=:cid AND status=1', array(':cid'=>$channel->id)).')';
				$c['url']=$host.'/orz/'.$channel->id.'/';
				$i=array();
				if(!empty($channel->items)){
					foreach($channel->items as $item){
						$i[]=array(
							'label'=>str_replace(self::t($type), '或零', $item->name).'('.Article::model()->count('tid=:tid AND status=1', array(':tid'=>$item->id)).')',
							'url'=>$host.'/ero/'.$item->id.'/',
						);
					}
				}
				$c['items']=$i;
				$menu[]=$c;
			}
		}
		
		$cache->set('ORZERO::MENU', serialize($menu), 3600*24);
		return $menu;
	}
	
	public function dialog_page($aid, $key, $title, $pcount, $click_id, $dialog_id, $c_class='bt_c')
	{
		$host=YII::app()->getRequest()->getHostInfo();
		$dialog_body="\r\n";
		for($page=1;$page<=intval(($pcount/10)+1);$page++){
			$dialog_body.='<a href="'.$host.'/orzero/'.intval($aid).'/'.$page.'.html">第'.$page.'页</a>'."\r\n";
		}
		
		$s="\r\n".'<span id="'.$click_id.'" class="'.$c_class.'">'.CHtml::encode($key).'</span>';
		$s.='<span id="'.$dialog_id.'">'.$dialog_body.'</span>'."\r\n";
		
		Yii::app()->getClientScript()->registerCss($dialog_id, '#'.$dialog_id.'{display:none;}');
		Yii::app()->getClientScript()->registerScript($dialog_id, '$( "#'.$dialog_id.'" ).dialog({ title:"'.CHtml::encode($title).'", autoOpen: false, width: 740, resizable: false });', CClientScript::POS_READY);
		Yii::app()->getClientScript()->registerScript($click_id, '$( "#'.$click_id.'" ).click(function() {$( "#'.$dialog_id.'" ).dialog( "open" );return false;});', CClientScript::POS_READY);
		
		return $s;
	}
	
	public static function subString_UTF8($str, $start, $lenth)
    {
        $len = strlen($str);
        $r = array();
        $n = 0;
        $m = 0;
        for($i = 0; $i < $lenth; $i++) {
            $x = substr($str, $i, 1);
            $a  = base_convert(ord($x), 10, 2);
            $a = substr('00000000'.$a, -8);
            if ($n < $start){
                if (substr($a, 0, 1) == 0) {
                }elseif (substr($a, 0, 3) == 110) {
                    $i += 1;
                }elseif (substr($a, 0, 4) == 1110) {
                    $i += 2;
                }
                $n++;
            }else{
                if (substr($a, 0, 1) == 0) {
                    $r[ ] = substr($str, $i, 1);
                }elseif (substr($a, 0, 3) == 110) {
                    $r[ ] = substr($str, $i, 2);
                    $i += 1;
                }elseif (substr($a, 0, 4) == 1110) {
                    $r[ ] = substr($str, $i, 3);
                    $i += 2;
                }else{
                    $r[ ] = '';
                }
                if (++$m >= $lenth){
                    break;
                }
            }
        }
        //return $r;
        $o=join('', $r);
        if($lenth<$len){
        	$o .= '…'; 
        }
        return $o;
    } // End subString_UTF8;
	
    public static $pscws;
	public function cut_keys($str='', $add=true){
		if(empty($str) || strlen($str)<6){
			return false;
		}
		
		$key='pscws:'.md5($str).($add?'+':'-');
		$out=YII::app()->cache->get($key);
		$out=false;
		if($out!==false){
			return unserialize($out);
		}
		
		if(empty(self::$pscws)){
			spl_autoload_unregister(array('YiiBase','autoload'));
			$dir=Yii::getPathOfAlias('application.extensions.pscws');
			require_once $dir.DIRECTORY_SEPARATOR.'pscws3.class.php';
			self::$pscws = new PSCWS3($dir.DIRECTORY_SEPARATOR.'dict'.DIRECTORY_SEPARATOR.'dict.xdb');
			spl_autoload_register(array('YiiBase','autoload'));
		}
		
		$in=array();
		foreach (self::$pscws->segment(iconv('UTF-8','GBK//IGNORE',$str)) as $s){
			if(strlen($s)>3)
				$in[]=iconv('GBK','UTF-8//IGNORE',$s);
		}
		
//		$o=array();
//		if(!empty($in)){
//			$c=count($in);
//			$t='';
//			for($i=0;$i<$c;$i++){
//				if(strpos($str, $t.$in[$i])){
//					$t=$t.$in[$i];
//				}else{
//					if(!empty($t)){
//						$o[]=$t;
//						$t='';
//					}
//				}
//			}
//			if(!empty($t)){
//				$o[]=$t;
//				$t='';
//			}
//		}
//		pr($in);
		
		
		YII::app()->cache->set($key, serialize($in), 3600);
		return $in;
	}
}