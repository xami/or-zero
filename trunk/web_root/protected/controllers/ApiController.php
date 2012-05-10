<?php

class ApiController extends Controller
{

	public function actions()
	{
	}

    

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
    public function actionArticle()
	{
        $id=Yii::app()->request->getParam('id', 0);
        $tianya=new Tianya();
        $st=$tianya->setArticle($id);
        pd($st);
    }

    public function actionItem()
	{
        $id=Yii::app()->request->getParam('id', 0);
        $src=Yii::app()->request->getParam('src', '');
        $tianya=new Tianya();
        $st=$tianya->setItem($id, $src);
        pd($st);
    }

    public function actionChannel()
	{
        $id=Yii::app()->request->getParam('id', 0);
        $tianya=new Tianya();
        $st=$tianya->setChannel($id);
        pd($st);
    }


    public function actionF()
	{
        header("Location: ".$_SERVER['HTTP_HOST']);
        die;
        $g=trim($_REQUEST['_']);
        $aid=trim($_REQUEST['aid']);
        $page=trim($_REQUEST['page']);
		if(($op=strrpos($g,'.'))!==false){
			$info=substr($g, 0, $op);
		}else{
			$info=$g;
		}

		$src=MCrypy::decrypt(rawurldecode($info), Yii::app()->params['mcpass'], 128);
		if(Tools::is_url($src)===false){
			return false;
		}

		if(($op=strrpos($src,'.'))===false){
			return false;
		}
		$ext=substr($src, $op);

        $key=md5($src);
        $dir=   Yii::getPathOfAlias('application.data.tianya.img').DIRECTORY_SEPARATOR.
                substr($key, 0, 1).DIRECTORY_SEPARATOR.
                substr($key, 1, 1).DIRECTORY_SEPARATOR.
                substr($key, 2, 2).DIRECTORY_SEPARATOR.
                substr($key, 4, 4).DIRECTORY_SEPARATOR.
                substr($key, 8, 8);
	    if(!is_dir($dir))
			mkdir($dir,0777,true);
		$file=$dir.DIRECTORY_SEPARATOR.substr($key, 16, 16).$ext;
//		pd($file);
        $results='';
        if(!is_file($file)){
            $results=Tools::OZSnoopy($src);
            @file_put_contents($file, $results);
            
            $size = getimagesize($file);
            if(empty($size[2]) || $size[2]<1 || $size[2]>16){
                return false;
            }
            //大于100k图片加水印
			if(strlen($results)>60*1024){
				//$im=imagecreatefromstring($results);
				Yii::import('application.extensions.image.Image');
				$image = new Image($file);

				$height=$image->height;
				$width=$image->width;
				if($width>2880){
					$height=intval((2880/$width)*$height);
					$width=2880;
				}
				if($height>1800){
					$width=intval((1800/$height)*$width);
					$height=1800;
				}
//				$waterfile=Yii::getPathOfAlias('application.data').DIRECTORY_SEPARATOR.'';
				$image->resize($width, $height);
				$image->watermark("Mtianya.com");
				$image->save();
			}
            
            $fm=File::model()->find('`src`=:src', array(':src'=>$src));
            if(empty($fm)){
                $fm=new File;
            }
            $fm->aid=$aid;
            $fm->type=substr($ext,1);
            $fm->size=strlen($results);
            $fm->pnum=$page;
            $fm->name=$key;
            $fm->src=$src;
            $fm->fsrc='';
            $fm->time=time();
            $fm->save();
        }
        $results=file_get_contents($file);
        $size = getimagesize($file);

		if($results!==false){
			if(headers_sent()) die('Headers Sent');
			if(ini_get('zlib.output_compression')) ini_set('zlib.output_compression', 'Off');
			header("Pragma: public"); // required
			header("Cache-Control: max-age=864000");//24小时
			header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
			header('Expires:'.gmdate('D, d M Y H:i:s', time() + '864000').'GMT');
			header("Cache-Control: private",false); // required for certain browsers
			header("Content-Type: ".$size['mime']);
			header("Content-Transfer-Encoding: binary");
		    header("Content-Length: ".strlen($results));
		    echo $results;
            flush();

		    return true;
		}

	}

	public function actionA()
	{
		$href    = urldecode(Yii::app()->request->getParam('href',''));
		$title   = urldecode(Yii::app()->request->getParam('t',''));
        $content = urldecode(Yii::app()->request->getParam('c',''));
        $refer   = urldecode(Yii::app()->request->getParam('f',''));

		if(Tools::is_url($href)===false){
//			return;
		}
		$js_reload="setTimeout(r,30000);function r(){location.reload();}";
		$packer = new JavaScriptPacker($js_reload, 'Normal', true, false);
		$packed = $packer->pack();

		echo
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>a {text-decoration: none;}</style>
'."<title>外链转到:$title($href)</title></head><body>"
.'
<div style="width:700px;">

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
'."
<script language='javascript'>$packed</script>
<h1 style=\"font-size:20px;margin:-5px 0 0 0;\">$title</h1>".

'
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
</script><div style="clear:both;">
'.$content.'</div>
<form action="http://'.Yii::app()->params['domain'].'/search" id="cse-search-box">
  <div>
    <input type="hidden" name="cx" value="'.Yii::app()->params['google_search_ad'].'" />
    <input type="hidden" name="cof" value="FORID:11" />
    <input type="hidden" name="ie" value="UTF-8" />
    <input type="text" name="q" size="40" />
    <input type="submit" name="sa" value="&#x641c;&#x7d22;" />
  </div>
</form>
<script type="text/javascript" src="http://www.google.com.hk/cse/brand?form=cse-search-box&amp;lang=zh-Hans"></script>
<script>
(function () {
  var ie = !!(window.attachEvent && !window.opera);
  var wk = /webkit\/(\d+)/i.test(navigator.userAgent) && (RegExp.$1 < 525);
  var fn = [];
  var run = function () { for (var i = 0; i < fn.length; i++) fn[i](); };
  var d = document;
  d.ready = function (f) {
    if (!ie && !wk && d.addEventListener)
      return d.addEventListener("DOMContentLoaded", f, false);
    if (fn.push(f) > 1) return;
    if (ie)
      (function () {
        try { d.documentElement.doScroll("left"); run(); }
        catch (err) { setTimeout(arguments.callee, 0); }
      })();
    else if (wk)
      var t = setInterval(function () {
        if (/^(loaded|complete)$/.test(d.readyState))
          clearInterval(t), run();
      }, 0);
  };
})();
var sl=document.getElementsByName("siteurl")[0];
sl.setAttribute("value","http://'.Yii::app()->params['domain'].'");
</script>
<div>'.

"
<a href='$href'>$title</a>
<h2>
<a href='$href'>$title<span style=\"color:red;\">[点此跳转]</span></a>&nbsp;&nbsp;
<a href='$refer'><span style=\"color:green;\">[返回阅读]</span></a>
</h2>
</div>
</div>
</body>
</html>";

	}

    public function actionFeed()
	{
        $host=Yii::app()->params['domain'];
        $type=strtolower(Yii::app()->request->getParam('controller', ''));
        if($type=='rss'){
            $feed=Yii::app()->cache->get($host.'feed/rss');
        }else if($type=='atom'){
            $feed=Yii::app()->cache->get($host.'feed/atom');
        }else{
            return false;
        }

        if(empty($feed)){
            Yii::import('application.vendors.*');
            require_once('Zend/Feed.php');
            require_once('Zend/Feed/Rss.php');
            require_once('Zend/Feed/Atom.php');

            // retrieve the latest 20 posts
            $articles=Article::model()->findAll(array(
                'condition'=>'cto>1',
                'order'=>'mktime DESC',
                'limit'=>50,
            ));

            // convert to the format needed by Zend_Feed
            $entries=array();
            foreach($articles as $article)
            {
                OZMysqlite::setDbPath(
                    Yii::getPathOfAlias(
                        'application.data.tianya.'.$article->cid.'.'.$article->tid.'.'.$article->aid.'.db'
                    )
                );
                OZMysqlite::getDb();
                $top=C::model()->findByPk(1);
                if(!empty($top)){
                    if($type=='rss'){
                        $entries[]=array(
                            'title'=>$article->title,
                            'link'=>'http://'.Yii::app()->params['domain'].'/article/'.$article->id.'/index.html',
                            'description'=>html_entity_decode(htmlspecialchars(Tianya::filterPost($top->text,$article->id,1), ENT_NOQUOTES, 'UTF-8')),
                            'lastUpdate'=>$article->mktime,
                        );
                    }else if($type=='atom'){
                        $entries[]=array(
                            'title'=>$article->title,
                            'link'=>'http://'.Yii::app()->params['domain'].'/article/'.$article->id.'/index.html',
                            'description'=>html_entity_decode(Tianya::filterPost($top->text,$article->id,1), ENT_NOQUOTES, 'UTF-8'),
                            'lastUpdate'=>$article->mktime,
                        );
                    }
                }
            }
            // generate and render RSS feed
            //$feed->send();
            header('Content-Type: text/xml');
		    if($type='rss'){
                $rss=Zend_Feed::importArray(array(
                    'title'   => '我的天涯阅读订阅-RSS',
                    'link'    => 'http://'.Yii::app()->params['domain'].'/',
                    'charset' => 'UTF-8',
                    'author' =>'http://'.Yii::app()->params['domain'].'',
                    'entries' => $entries,
                ), 'rss');
                Yii::app()->cache->set($host.'feed/rss', $rss->saveXML(), 600);
                echo $rss->saveXML();
            }else if($type='atom'){
                $atom=Zend_Feed::importArray(array(
                    'title'   => '我的天涯阅读订阅-ATOM',
                    'link'    => 'http://'.Yii::app()->params['domain'].'/',
                    'charset' => 'UTF-8',
                    'author' =>'http://'.Yii::app()->params['domain'].'',
                    'entries' => $entries,
                ), 'atom');
                Yii::app()->cache->set($host.'feed/atom', $atom->saveXML(), 600);
                echo $atom->saveXML();
            }
        }else{
            header('Content-Type: text/xml');
		    echo $feed;
        }
	}

    public function actionDo()
	{
//		http%3A%2F%2F3g.tianya.cn%2Fbbs%2Fart.jsp%3Fitem%3Dtravel%26id%3D329241%26idwriter%3D0%26key%3D0%26chk%3D
//		echo urlencode('http://3g.tianya.cn/bbs/art.jsp?item=travel&id=329241&idwriter=0&key=0&chk=');
//		http://3g.tianya.cn/bbs/art.jsp%3Fitem%3Dfree%26id%3D2152303
//		if (!Yii::app()->request->isAjaxRequest) {
//			e(json_encode(array(
//				'responseStatus'=>'500',
//				'responseDetails'=>'禁止非Ajax方法调用',
//				'responseData'=>null,
//			)));
//		}
		$src=isset($_REQUEST['src']) ? urldecode(trim($_REQUEST['src'])) : '';
		$type=isset($_REQUEST['type']) ? trim($_REQUEST['type']) : 'tianya';

		if(!Tools::is_url($src)){
			pd(json_encode(array(
				'responseStatus'=>'500',
				'responseDetails'=>'请提供正确的链接地址,并且需要在前面加http://',
				'responseData'=>null,
			)));
		}

		//目前只支持tianya
		if(!in_array($type,array('tianya'))){
			pd(json_encode(array(
				'responseStatus'=>'500',
				'responseDetails'=>'不支持此类型',
				'responseData'=>null,
			)));
		}

		OZCommon::$type=$type;
		$C1=OZCommon::isTYPE($src);
		$C1_ST=array_pop(array_keys($C1));


		if($C1_ST!=200){
			pd(json_encode(array(
				'responseStatus'=>$C1_ST,
				'responseDetails'=>$C1[$C1_ST],
				'responseData'=>null,
			)));
		}

		$article=Article::model()->find('aid=:aid AND tid=:tid AND cid=:cid', array(
			':aid'=>$C1[200]['aid'],
			':tid'=>$C1[200]['tid'],
			':cid'=>$C1[200]['cid'],
		));

		if(empty($article)){
			$article=new Article();
			$C2=OZCommon::getTYPE($C1[200]['html']);
			$now=time();

			$article->cid=$C1[200]['cid'];
			$article->tid=$C1[200]['tid'];
			$article->aid=$C1[200]['aid'];

			$article->title=$C2['title'];
			$article->tag=$C2['tag'];
			$article->key='';
			$article->page=$C2['page'];
			$article->un=$C2['un'];
			$article->cto=0;
			$article->pcount=0;
			$article->mktime=$now;
			$article->uptime=$now;
			$article->src=$C1[200]['src'];
			$article->status=1;
			$article->reach=$C2['reach'];
			$article->reply=$C2['reply'];
			$article->hot=0;

			if($article->page>50 && $article->reach>100000 && $article->reply>1000){
				$article->save();
			}
		}else{
			pd(json_encode(array(
				'responseStatus'=>'220',
				'responseDetails'=>'或零整理',
				'responseData'=>array(
					'link'=>'http://'.Yii::app()->params['domain'].'/article/'.$article->id.'/index.html',
					'title'=>$article->title,
					'un'=>$article->un,
					'page'=>$article->page,
					'reach'=>$article->reach,
					'reply'=>$article->reply,
					'aid'=>$article->id,
					'tid'=>$article->item->id,
					'cid'=>$article->channel->id,
				),
			)));
		}
//		pd($article);
//		pr($C1);
//		pr($C2);
		if(isset($article->id) && $article->id>0){
			pd(json_encode(array(
				'responseStatus'=>'200',
				'responseDetails'=>'或零整理',
				'responseData'=>array(
					'link'=>$this->host.'/orzero/'.$article->id.'/index.html',
					'title'=>$article->title,
					'un'=>$article->un,
					'page'=>$article->page,
					'reach'=>$article->reach,
					'reply'=>$article->reply,
					'aid'=>$article->id,
					'tid'=>$article->item->id,
					'cid'=>$article->channel->id,
				),
			)));
		}else{
			if($article->page<100)
				$reson='原帖页数('.$article->page.')';
			else if($article->reach<100)
				$reson='原帖访问量('.$article->reach.')';
			else if($article->reply<100)
				$reson='原帖回复数('.$article->reply.')';
			pd(json_encode(array(
				'responseStatus'=>'301',
				'responseDetails'=>'<span class="red">'.$reson.'不满足</span><span class="green">[整理条件]</span>,<span class="red">请更换其他的帖子或阅读原帖</span>',
				'responseData'=>array(
					'link'=>$src,
					'title'=>$article->title,
					'un'=>$article->un,
					'page'=>$article->page,
					'reach'=>$article->reach,
					'reply'=>$article->reply,
					'tid'=>$article->item->id,
					'cid'=>$article->channel->id,
				),
			)));
		}
	}

    public function actionMaps()
	{
//		$this->google_phone_ad();

		$page_size=20;


		$_page=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 1;
		$_page=($_page<1) ? 1 : $_page;

		$criteria=new CDbCriteria;
		$criteria->condition='`status` = 1';
//		$criteria->order='`id` DESC';
		$criteria->limit=$page_size;
		$criteria->offset=($_page-1)*$page_size;

		$count=Article::model()->count($criteria);
		if($count==0){
			$max_page=1;
		}else{
			$max_page=ceil($count/$page_size);
		}
		$_page=($_page>$max_page)?$max_page:$_page;

//		$sort=new CSort('Article');
//		$sort->multiSort=false;
//		$sort->defaultOrder="t.uptime DESC";
//		$sort->sortVar='Article_sort';
//		$sort->attributes = array(
//            'page' => 't.page',		//原帖页数
//			'pcount' => 't.pcount',	//整理条数
//            'reach' => 't.reach',	//原帖访问量
//			'reply' => 't.reply',	//原帖回复数
//            'hot' => 't.hot',		//本站访问量
//			'uptime' => 't.uptime',	//最后更新
//        );
		$sort=isset($_REQUEST['A_sort']) ? trim($_REQUEST['A_sort']) : '';
        switch ($sort):
		    case 'page':
		        $criteria->order='`page` DESC';
		        break;
		    case 'pcount':
		        $criteria->order='`pcount` DESC';
		        break;
		    case 'reach':
		        $criteria->order='`reach` DESC';
		        break;
		    case 'reply':
		        $criteria->order='`reply` DESC';
		        break;
		    case 'hot':
		        $criteria->order='`hot` DESC';
		        break;
		    case 'uptime':
		        $criteria->order='`uptime` DESC';
		        break;
		    default:
		    	$sort='';
		        $criteria->order='`id` DESC';
		endswitch;

		$articles=Article::model()->findAll($criteria);

		$list='<div class="list">';
		$pre=true;
		if(!empty($articles)){
			$j=1;
			foreach($articles as $article){
				$title=htmlspecialchars($article->title);
				if($j==14){
                    $list.='';
				}
				$class=$pre?' class="t"':' class="f"';
                $list.='<div'.$class.'><a href="http://'.Yii::app()->params['domain'].'/article/'.$article->id.
				'/index.html" target="_blank" title="'.$title.'">'.$title.
				'</a>&nbsp;[作者:<a href="http://'.Yii::app()->params['domain'].'/search?cx='.Yii::app()->params['google_search_ad'].'&cof=FORID:11&ie=UTF-8&un=or&q='.$article->un.'">'.$article->un.'</a>]'.
				'&nbsp;[阅读:'.$article->hot.'次]'.
				'&nbsp;[整理:'.$article->pcount.'楼]'.
				'<br />';

				$pre=$pre?false:true;

				if($article->pcount==0){
					$page=1;
				}else{
					$page=ceil($article->pcount/10);
				}

                $list.='<div class="w700">';
				for($i=1;$i<=$page;$i++){
                    $list.='<a href="http://'.Yii::app()->params['domain'].'/article/'.$article->id.
					'/'.$i.'.html" target="_blank" title="[第'.$i.'页]'.$title.'">'.$i.'</a>&nbsp;';
					if($i>90){
                        $list.='......&nbsp;';
                        $list.='<a href="http://'.Yii::app()->params['domain'].'/article/'.$article->id.
						'/'.($page-1).'.html" target="_blank" title="[第'.($page-1).'页]'.$title.'">'.($page-1).'</a>&nbsp;';
                        $list.='<a href="http://'.Yii::app()->params['domain'].'/article/'.$article->id.
						'/'.$page.'.html" target="_blank" title="[第'.$page.'页]'.$title.'">'.$page.'</a>&nbsp;';
						break;
					}
				}
                $list.='&nbsp;<a href="http://'.Yii::app()->params['domain'].'/sitemap/'.$article->id.'.xml">索引</a>&nbsp;'.
				'<a href="http://'.Yii::app()->params['domain'].'/link-'.$article->id.'.html">直达</a>&nbsp;';
                $list.='</div>';

				//$html.='[热度:'.$article->hot.']';
                $list.='</div>';

				$j++;
			}
		}
		$list.='</div>';

		if(strlen($sort)>0){
			$sort=$sort.'-';
		}

		$footer='<div class="footer">';
		for($i=1;$i<=$max_page;$i++){
			if($i==$_page){
                $footer.='<a href="http://'.Yii::app()->params['domain'].'/list-'.$sort.
				$i.'.html" title="[我的天涯整理第'.$i.'页]"><span class="current">第'.$i.'页</span></a>&nbsp;';
			}else{
                $footer.='<a href="http://'.Yii::app()->params['domain'].'/list-'.$sort.
				$i.'.html" title="[我的天涯整理第'.$i.'页]">第'.$i.'页</a>&nbsp;';
			}

		}
        $footer.='&nbsp;&nbsp;<a href="http://'.Yii::app()->params['domain'].'/sitemap.xml" target="_blank" style="color:green">网站地图</a>';
        $footer.='&nbsp;&nbsp;<a href="http://'.Yii::app()->params['domain'].'/sitemaps.xml" target="_blank" style="color:green">文章地图</a>';
        $footer.='</div>';


        $title='';
        if(empty($_REQUEST['A_sort'])){
            $title='最新整理';
        }else if($_REQUEST['A_sort']=='uptime'){
            $title='最近更新';
        }else if($_REQUEST['A_sort']=='hot'){
            $title='访问最多';
        }else if($_REQUEST['A_sort']=='pcount'){
            $title='整理最多';
        }

        $this->layout='//layouts/column2';
        $this->render('list', array('title'=>$title, 'list'=>$list, 'footer'=>$footer));
	}

    public function actionUlink()
	{
		$expire=900;
		$html=Yii::app()->cache->get('orzero::author');
		if(empty($html)){
				$as = Yii::app()->db->createCommand()
					    ->select('distinct (un)')
					    ->from('tbl_article')
					    ->where('status=1')
					    ->group('un')
					    ->order('count(un) DESC')
					    ->queryAll();

            $html='';
				$i=1;
				foreach($as as $a){
                    $html.='<a href="/search?cx='.Yii::app()->params['google_search_ad'].'&cof=FORID%3A11&ie=UTF-8&un=or&q='.
					$a['un'].'">'.$a['un'].'</a>&nbsp;';

						if(($i%2000)==0){
                            $html.='';
						}
						if($i==700){
                            $html.='';
						}

						$i++;
					}

			Yii::app()->cache->set('orzero::author', $html, $expire);
		}

        $this->layout='//layouts/column2';
        $this->render('list', array('title'=>'作者列表', 'list'=>$html, 'footer'=>''));

	}

    public function actionAlink()
	{
		$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

		$expire=600;
		$baseUrl='http://'.Yii::app()->params['domain'];

		if($id>0){
			$article = Yii::app()->db->createCommand()
			    ->select('id, pcount, uptime, title')
			    ->from('tbl_article')
			    ->where('status=1 AND id=:id', array(':id'=>$id))
			    ->order('mktime DESC')
			    ->queryRow();


			$this->google_phone_ad();

			$html='';
			$html.='<div class="header"><div class="nav"><a class="home" href="'.$baseUrl.'" title="我的天涯">返回首页</a>&nbsp;&raquo;&nbsp;||&nbsp;'.
		'<a class="home" href="'.$baseUrl.'/list-1.html" title="我的天涯最新整理">最新整理</a>&nbsp;||&nbsp;'.
		'<a class="home" href="'.$baseUrl.'/list-uptime-1.html" title="我的天涯最近更新">最近更新</a>&nbsp;||&nbsp;'.
		'<a class="home" href="'.$baseUrl.'/list-hot-1.html" title="我的天涯访问最多">访问最多</a>&nbsp;||&nbsp;'.
		'<a class="home" href="'.$baseUrl.'/list-pcount-1.html" title="我的天涯整理最多">整理最多</a>&nbsp;||&nbsp;'.
		'<a class="home" href="'.$baseUrl.'/orzero-author.html" title="我的天涯作者列表">作者列表</a>&nbsp;||&nbsp;</div>'.
'
<div class="search">
<form action="/search" name="t">
<input type="hidden" name="cx" value="'.Yii::app()->params['google_search_ad'].'" />
<input type="hidden" name="cof" value="FORID:11" />
<input type="hidden" name="ie" value="UTF-8" />
<input type="radio" name="un" value="or" />作者
<input type="radio" name="un" value="zero" />标题
<input type="text" maxlength="100" size="50" name="q" />
<input type="submit" value="站内搜索" />
</form>
'.
$this->google_phone_ad()
.'
</div>
</div>
	<div class="ad_link">
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
<br />
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
<br />
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
	<div class="ad">
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
	</div>

';
			if($article['pcount']==0){
					$page=1;
				}else{
					$page=ceil($article['pcount']/10);
				}

				$html.='<div class="w700 f"><h1><a href="http://'.Yii::app()->params['domain'].'/article/'.$article['id'].
					'/index.html" target="_self" title="'.$article['title'].'">'.$article['title'].'</a></h1>
<div class="ad_h">
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
</div>
<div class="ad_h">
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
</div>';
				for($i=1;$i<=$page;$i++){
					$html.='<a href="http://'.Yii::app()->params['domain'].'/article/'.$article['id'].
					'/'.$i.'.html" target="_blank" title="[第'.$i.'页]'.$article['title'].'">[第'.$i.'页]</a>&nbsp;'."\r\n";
				}

			echo
'<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>'.$article['title'].'</title>
<style>
.main,form{padding:0;margin:0;}
.nav{display:inline-block;}
.home{color:#000000;font-family:arial,sans-serif;font-size:14px;font-weight:bold;white-space:nowrap;}
.current{color:red;font-weight:bold;}
.ad{float:right;}
.ad_h{float:left;}
.ad_link{float:left;}
.w700{width:940px;clear:both;}
.list{padding:5px;clear:both;}
.search{float:right;clear:both;}
.header{margin-bottom:10px;padding-bottom:2px;border-bottom:1px solid #C9D7F1;font-size:12pt;line-height:16pt;display:block;}
.footer{margin-top:10px;padding-top:5px;border-top:1px solid #C9D7F1;font-size:12pt;line-height:16pt;display:block;}
.t{padding-right:350px;background:none repeat scroll 0 0 #FCFCFC;border-bottom:1px solid #EEEEEE;border-top:1px solid #EEEEEE;display:block;font-family:Menlo,Consolas,"Courier New",Courier,mono;margin:4px 0;padding:2px;/*white-space:pre-wrap;*/word-wrap:break-word;}
.f{padding-right:350px;background:none repeat scroll 0 0 #F4F5F7;border-bottom:1px solid #EEEEEE;border-top:1px solid #EEEEEE;display:block;font-family:Menlo,Consolas,"Courier New",Courier,mono;margin:4px 0;padding:2px;/*white-space:pre-wrap;*/word-wrap:break-word;}
</style>
</head>
<body>

<div class="main">
'.$html.$this->google_phone_ad().
'</div>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push([\'_setAccount\', \'UA-7387085-1\']);
  _gaq.push([\'_setDomainName\', \'.'.Yii::app()->params['domain'].'\']);
  _gaq.push([\'_trackPageview\']);
  (function() {
    var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
    ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';
    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src=\'" + _bdhmProtocol + "hm.baidu.com/h.js%3F52ee3503e2a49b8134aa2f700075d417\' type=\'text/javascript\'%3E%3C/script%3E"));
</script>
</body>
</html>';

		}
	}

    public function actionSearchs()
	{
		$q=isset($_REQUEST['q']) ? trim($_REQUEST['q']) : '我的天涯';
		$un=isset($_REQUEST['un']) ? trim($_REQUEST['un']) : 'zero';
//		$tag=isset($_REQUEST['tag']) ? trim($_REQUEST['tag']) : '';

		if(strlen($q)>0){
			$criteria=new CDbCriteria;
			//$criteria->with=array('item');
			$criteria->condition='`t`.`status`=1';
			//$criteria->order='`t`.`uptime` DESC';
			$criteria->order='`uptime` DESC';

			if($un=='or'){
				//$criteria->compare('t.un',$q,true);
				$criteria->compare('un',$q,true);
			}else if($un=='zero' || empty($un)){
				//$criteria->compare('t.title',$q,true);
				$criteria->compare('title',$q,true);
			}

//			if(strlen($tag)>0){
//				//$criteria->addSearchCondition('t.tag', $tag, true, 'OR');
//				$criteria->addSearchCondition('tag', $tag, true, 'OR');
//			}

			$data['dataProvider']=new CActiveDataProvider('Article',array(
			    'criteria'=>$criteria,
			    'pagination'=>array(
			        'pageSize'=>15,
			    ),
			));

			$criteria=new CDbCriteria;
			//$criteria->condition='`t`.`status`=1 AND `title` LIKE :ycp0';
			$criteria->condition='`status`=1 AND `title` LIKE :ycp0';
			$criteria->params=array(':ycp0'=>'%'.$q.'%');
			$rcount = Article::model()->count($criteria);

			$criteria=new CDbCriteria;
			$criteria->condition='`key` LIKE :ycp0';
			$criteria->params=array(':ycp0'=>'%'.$q.'%');
			$_search=Searchs::model()->find($criteria);
			$uid=intval(Yii::app()->user->id);
			if(empty($_search)){
				$search=new Searchs();
				$search->key=$q;
				$search->uid=$uid;
				$search->mktime=time();
				$search->uptime=0;
				$search->ccount=1;
				$search->rcount=$rcount;
				$search->save();
			}else{
				$_search->uid=$uid;
				$_search->uptime=time();
				$_search->ccount++;
				$_search->rcount=$rcount;
				$_search->save();
			}

		}else{
			$criteria=new CDbCriteria;
			//$criteria->with=array('item');
			//$criteria->condition='`t`.`status`=1';
			$criteria->condition='`status`=1';
			//$criteria->order='`t`.`uptime` DESC';
			$criteria->order='`uptime` DESC';
			$data['dataProvider']=new CActiveDataProvider('Article',array(
			    'criteria'=>$criteria,
			    'pagination'=>array(
			        'pageSize'=>15,
			    ),
			));
		}

        $this->layout='//layouts/column2';
		$this->render('search',$data);
	}

	public function actionSitemap()
	{
        $host=Yii::app()->params['domain'];
		$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

//		$criteria=new CDbCriteria;
//		$criteria->limit=4000;
		$expire=600;
		$baseUrl=Yii::app()->params['domain'];

		if($id>0){
			$article=Yii::app()->cache->get($host.'xml::article'.$id);
			if(empty($article)){
//				$criteria->condition='status=1 AND id='.$id;
//				$article=Article::model()->find($criteria);
				$article = Yii::app()->db->createCommand()
				    ->select('id, pcount, uptime')
				    ->from('tbl_article')
				    ->where('status=1 AND id=:id', array(':id'=>$id))
				    ->order('mktime DESC')
				    ->queryRow();
				Yii::app()->cache->set($host.'xml::article'.$id, $article, $expire);
			}

			$xml="<?xml version='1.0' encoding='UTF-8'?>"."\r\n".
				'<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"'."\r\n".
				'         xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"'."\r\n".
				'         xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\r\n";
			if(!empty($article['pcount'])){
				$count=ceil($article['pcount']/10);
				for($i=1;$i<=$count;$i++){
					if($i==$count){
						$prio=1;
					}else{
						$prio=0.5;
					}
					$xml_item="\t".'<url>'."\r\n"
							."\t\t".'<loc>http://'.$baseUrl.'/article/'.$article['id'].'/'.$i.'.html'.'</loc>'."\r\n"
							."\t\t".'<lastmod>'.date(DateTime::W3C, $article['uptime']).'</lastmod>'."\r\n"
							."\t\t".'<changefreq>hourly</changefreq>'."\r\n"
							."\t\t".'<priority>'.$prio.'</priority>'."\r\n"
							."\t".'</url>'."\r\n";
					$xml.=$xml_item;
				}
			}else{
                $xml_item="\t".'<url>'."\r\n"
                        ."\t\t".'<loc>http://'.$baseUrl.'/article/'.$article['id'].'/1.html'.'</loc>'."\r\n"
                        ."\t\t".'<lastmod>'.date(DateTime::W3C, time()).'</lastmod>'."\r\n"
                        ."\t\t".'<changefreq>hourly</changefreq>'."\r\n"
                        ."\t\t".'<priority>0.8</priority>'."\r\n"
                        ."\t".'</url>'."\r\n";
                $xml.=$xml_item;
            }
			$xml.='</urlset>';
			header('Content-Type: text/xml');
			echo $xml;
		}else{

			$xml=Yii::app()->cache->get($host.'xml::index');
			if(empty($xml)){

				$article=Yii::app()->cache->get($host.'xml::article');
				if(empty($article)){
	//				$criteria->condition='status=1';
	//				$criteria->order='mktime DESC';
	//				$article=Article::model()->findAll($criteria);
					$article = Yii::app()->db->createCommand()
					    ->select('id, uptime')
					    ->from('tbl_article')
					    ->where('status=1')
					    ->order('mktime DESC')
					    ->queryAll();
					Yii::app()->cache->set($host.'xml::article', $article, $expire);
				}

				$item=Yii::app()->cache->get($host.'xml::item');
				if(empty($item)){
	//				$criteria->condition='status=1';
	//				$criteria->order='count DESC';
	//				$item=Item::model()->findAll($criteria);
					$item = Yii::app()->db->createCommand()
					    ->select('id')
					    ->from('tbl_item')
					    ->where('status=1')
					    ->queryAll();
					Yii::app()->cache->set($host.'xml::item', $item, $expire*10);
				}

				$channel=Yii::app()->cache->get($host.'xml::channel');
				if(empty($channel)){
	//				$criteria->condition='status=1';
	//				$criteria->order='count DESC';
	//				$channel=Channel::model()->findAll($criteria);
					$channel = Yii::app()->db->createCommand()
					    ->select('id')
					    ->from('tbl_channel')
					    ->where('status=1')
					    ->queryAll();
					Yii::app()->cache->set($host.'xml::channel', $channel, $expire*100);
				}



				$xml="<?xml version='1.0' encoding='UTF-8'?>"."\r\n".
					'<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"'."\r\n".
					'         xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"'."\r\n".
					'         xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\r\n";

				if(!empty($article)){
					foreach($article as $a){
//						!isset($max_cto) && $max_cto=$a->cto;
						$xml_item="\t".'<url>'."\r\n"
								."\t\t".'<loc>http://'.$baseUrl.'/article/'.$a['id'].'/index.html'.'</loc>'."\r\n"
								."\t\t".'<lastmod>'.date(DateTime::W3C, $a['uptime']).'</lastmod>'."\r\n"
								."\t\t".'<changefreq>hourly</changefreq>'."\r\n"
								."\t\t".'<priority>0.7</priority>'."\r\n"
								."\t".'</url>'."\r\n";

//								"\t".'<url>'."\r\n"
//								."\t\t".'<loc>'.$baseUrl.'/sitemap/'.$a->id.'.xml'.'</loc>'."\r\n"
//								."\t\t".'<lastmod>'.date(DateTime::W3C, $a->uptime).'</lastmod>'."\r\n"
//								."\t\t".'<changefreq>hourly</changefreq>'."\r\n"
//								."\t\t".'<priority>0.9</priority>'."\r\n"
//								."\t".'</url>'."\r\n";
						$xml.=$xml_item;
					}
				}

				if(!empty($item)){
					foreach($item as $i){
//						!isset($max_item_count) && $max_item_count=$i->count;
						$xml_item="\t".'<url>'."\r\n"
								."\t\t".'<loc>http://'.$baseUrl.'/item/'.$i['id'].'/</loc>'."\r\n"
								."\t\t".'<changefreq>daily</changefreq>'."\r\n"
								."\t\t".'<priority>0.6</priority>'."\r\n"
								."\t".'</url>'."\r\n";
						$xml.=$xml_item;
					}
				}

				if(!empty($channel)){
					foreach($channel as $c){
//						!isset($max_channel_count) && $max_channel_count=$c->count;
						$xml_item="\t".'<url>'."\r\n"
								."\t\t".'<loc>http://'.$baseUrl.'/channel/'.$c['id'].'/</loc>'."\r\n"
								."\t\t".'<changefreq>weekly</changefreq>'."\r\n"
								."\t\t".'<priority>0.5</priority>'."\r\n"
								."\t".'</url>'."\r\n";
						$xml.=$xml_item;
					}
				}

				$xml.='</urlset>';

				Yii::app()->cache->set($host.'xml::index', $xml, $expire);
				if($baseUrl=='http://'.Yii::app()->params['domain']){
					@file_put_contents(dirname(Yii::app()->BasePath).DIRECTORY_SEPARATOR.'sitemap.xml', $xml);
				}

			}
//			header('Location: /sitempa.xml');
			header('Content-Type: text/xml');
			echo $xml;
//			readfile(dirname(Yii::app()->BasePath).DIRECTORY_SEPARATOR.'sitemap.xml');
		}
//		$this->redirect('/sitemap.xml');
//		pr(Yii::getPathOfAlias('webroot'));
	}

	public function actionSitemaps()
	{
        $host=Yii::app()->params['domain'];
		$baseUrl='http://'.Yii::app()->params['domain'];
		$expire=60;

		$article=Yii::app()->cache->get($host.'sitemaps::article');
		if(empty($article)){
			$article = Yii::app()->db->createCommand()
		    ->select('id, uptime')
		    ->from('tbl_article')
		    ->where('status=1 AND pcount>0')
		    ->order('uptime DESC')
		    ->queryAll();
		    Yii::app()->cache->set($host.'sitemaps::article', $article, $expire);
		}

	    $xml=Yii::app()->cache->get($host.'sitemaps::xml');
	    if(empty($xml)){
	    	$xml="<?xml version='1.0' encoding='UTF-8'?>"."\r\n".
				'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\r\n";


				if(!empty($article)){
					foreach($article as $a){
						$xml_item="\t".'<sitemap>'."\r\n"
								."\t\t".'<loc>'.$baseUrl.'/sitemap/'.$a['id'].'.xml'.'</loc>'."\r\n"
								."\t\t".'<lastmod>'.date(DateTime::W3C, $a['uptime']).'</lastmod>'."\r\n"
								."\t".'</sitemap>'."\r\n";
						$xml.=$xml_item;
					}
				}

			$xml.='</sitemapindex>';

	    	Yii::app()->cache->set($host.'sitemaps::xml', $xml, $expire*10);
			if($baseUrl=='http://'.Yii::app()->params['domain']){
				@file_put_contents(dirname(Yii::app()->BasePath).DIRECTORY_SEPARATOR.'sitemaps.xml', $xml);
			}
	    }
	    header('Content-Type: text/xml');
		echo $xml;
	}

    public function actionTag()
	{
		$criteria=new CDbCriteria;
		$criteria->condition='`rcount`>0';
		$criteria->order='`rcount` DESC, `ccount` DESC';
		$data['dataProvider']=new CActiveDataProvider('Searchs',array(
		    'criteria'=>$criteria,
		    'pagination'=>array(
		        'pageSize'=>200,
		    ),
		));
		$this->render('tag',$data);
	}

	public function google_phone_ad(){
		require_once('GAD.php');
	}
	    
    
}