<?php
/**
 * Created by JetBrains PhpStorm.
 * User: lijia
 * Date: 11-9-28
 * Time: 下午1:43
 * To change this template use File | Settings | File Templates.
 */
 
class Tianya{
    function getChannels($id=0){
        $id=intval($id);
        if($id>0){
            $data['channels']=Channel::model()->with('items')->findAll('t.status=1 AND t.id=:id',array(':id'=>$id));
        }else{
            $data['channels']=Channel::model()->with('items')->findAll('t.status=1');
        }
        return $data;
    }


    function getItems($cid=0, $tid=0, $page_size=20){
        $cid=intval($cid);
        $tid=intval($tid);
        $criteria=new CDbCriteria;
        if($tid>0){
            $criteria->condition='`status`=1 AND `t`.`tid`=:tid';
		    $criteria->params=array(':tid'=>$tid);
        }else if($cid>0){
            $criteria->condition='`status`=1 AND `t`.`cid`=:cid';
		    $criteria->params=array(':cid'=>$cid);
        }else{
            $criteria->condition='`status`=1';
		    $criteria->params=array(':cid'=>$cid);
        }

		//$_article = Article::model()->find($criteria);
		//$data[article] = $_article;
		$sort=new CSort('Article');
		$sort->multiSort=false;
		$sort->defaultOrder="t.uptime DESC";
		$sort->sortVar='Article_sort';
		$sort->attributes = array(
            'page' => 't.page',		//原帖页数
			'pcount' => 't.pcount',	//整理条数
            'reach' => 't.reach',	//原帖访问量
			'reply' => 't.reply',	//原帖回复数
            'hot' => 't.hot',		//本站访问量
			'uptime' => 't.uptime',	//最后更新
        );

        $data['dataProvider']=new CActiveDataProvider('Article',array(
		    'criteria'=>$criteria,
			'sort'=>$sort,
		    'pagination'=>array(
		        'pageSize'=>$page_size,
		    ),
		));

        return $data;
    }

    function getArticle($id){
        $id=intval($id);
        $data['Article']=Article::model()->find('`id`='.$id.' AND `status`=1');

        return $data;
    }



    function setArticle($id){
        $id=intval($id);
        $article=Article::model()->find('`id`='.$id.' AND `status`=1');

        if(empty($article)){
            Yii::log(__FILE__.'::'.__LINE__.'::empty($article)', 'warning', 'Article');
            return -1;
        }
        if(empty($article->src)){
            Yii::log(__FILE__.'::'.__LINE__.'::empty($article->src)', 'warning', 'Article');
			return -2;
		}
        if(empty($article->item)||empty($article->channel)) {
			Yii::log(__FILE__.'::'.__LINE__.'::empty($article->item)||empty($article->channel)', 'warning', 'Article');
			return -3;
		}

        //设置数据库
        OZMysqlite::setDbPath(
            Yii::getPathOfAlias(
                'application.data.tianya.'.$article->cid.'.'.$article->tid.'.'.$article->aid.'.db'
            )
        );
        OZMysqlite::getDb();
        
        //文章信息表
        try{
			$_P =new P();
            $_C =new C();
		}catch(Exception $e){
//          OZMysqlite::createCacheTable('c');
//			OZMysqlite::createCacheTable('p');
            Yii::log(__FILE__.'::'.__LINE__.'::get $_P $_C', 'warning', 'Article');
			return -4;
		}


        $time=time();
//        $prev_page_id=$article->cto-1;
//		$cto=$article->cto;
//		$pcount=$article->pcount;
        if($article->cto<=1){
			$article->cto=1;
            $page=$_P->findByPk(1);
			if(empty($page)){
				$page=clone $_P;
			}
			$page->id=1;
			$page->link=$article->src;
			$page->count=0;
			$page->info='';
			$page->status=0;
			$page->mktime=$time;
			$page->uptime=$time;
			if(!$page->save()){
				Yii::log(__FILE__.'::'.__LINE__.'::!$page->save()', 'warning', 'Article');
			    return -5;
			}

		}else{
			$page=$_P->findByPk($article->cto);
			if(empty($page->id)){
				$article->cto=0;
				$article->save();
				Yii::log(__FILE__.'::'.__LINE__.'::empty($page->id)', 'warning', 'Article');
			    return true;
			}
		}


        $static_key=json_decode(Yii::app()->params['key']);
//        pd($static_key->idwriter);

        $page->link=html_entity_decode($page->link);
        $cut_src=parse_url($page->link);
        parse_str($cut_src['query'], $cut_query);
        $cut_query['idwriter']=$static_key->idwriter;
        $cut_query['key']=$static_key->key;
        $cut_query['chk']=$static_key->chk;
        $static_query=http_build_query($cut_query);
        $statci_src=$cut_src['scheme'].'://'.$cut_src['host'].
                $cut_src['path'].'?'.$static_query;

        $find=$this->getSrc($statci_src);

        if($find == -5){
            $article->status=-1;
            $article->save();
            return -13;
        }
//        pd($find);
        
        $next_page=$_P->findByPk($article->cto+1);
		if(empty($next_page)){
			$next_page=clone $_P;
			$next_page->id=$article->cto+1;
		}
        if(!isset($find['next_link'])||empty($find['next_link'])){
            Yii::log(__FILE__.'::'.__LINE__.'::empty($find[\'next_link\'])', 'warning', 'Article');
			return -7;
        }
		$next_page->link=$find['next_link'];
		$next_page->count=0;
		$next_page->info='';
		$next_page->status=-1;
		$next_page->mktime=$time;
		$next_page->uptime=$time;
		$article->title=$find['title'];
		$article->tag=$find['tag'];
		//$article->key=$key;
		$article->page=$find['page'];
		//作者保持固定不变,除非为空的情况
		(isset($find['un']) && !empty($find['un']) && !empty($article->un)) && $article->un=$find['un'];

		//$_article->pcount+=count($find['post']);
		$article->uptime=time();
		($page->id==1) && $article->reach=intval($find['reach']);
		($page->id==1) && $article->reply=intval($find['reply']);

        if(!isset($find['post'])||empty($find['post'])){
            $page->count=0;
        }else{
            $page->count=count($find['post']);
        }

		$page->status=1;


		if($page->save()===false){
			Yii::log(__FILE__.'::'.__LINE__.'::$page->save()===false', 'warning', 'Article');
			return -8;
		}

		if($next_page->save()===false){
			Yii::log(__FILE__.'::'.__LINE__.'::$next_page->save()===false', 'warning', 'Article');
			return -9;
		}

//		pr($find);
		if(isset($find['post']) && !empty($find['post'])) foreach($find['post'] as $post){
			$content = $_C->find('pos=:pos',array(':pos'=>$post['pos']));
			if(empty($content)){
				$content=clone $_C;
			}
			$content->pid=$page->id;
			$content->pos=$post['pos'];
			$content->text=$post['body'];
			$content->info='';
			$content->status=1;						//1正常，-1删除，2审核修改中（此时text内容进行serialize存储），3审核删除中
			$content->mktime=$time;
			$content->uptime=$time;

			if($content->save()===false){
				Yii::log(__FILE__.'::'.__LINE__.'::$content->save()===false', 'warning', 'Article');
			    return -10;
			}

		}

		$article->pcount=$_C->count();
		if(($next_page->id<=$find['page']) && ($next_page->link!==false))
			$article->cto=$next_page->id;

//		pr($article);
		if($article->save()===false){
			Yii::log(__FILE__.'::'.__LINE__.'::$article->save()===false', 'warning', 'Article');
			return -11;
		}

		if($article->page>$article->cto+1){
			Yii::log(__FILE__.'::'.__LINE__.'::success', 'warning', 'Tianya');
//            echo(json_encode(1));	//继续循环
			return true;
		}else{
			Yii::log(__FILE__.'::'.__LINE__.'::!$article->page>$article->cto+1', 'warning', 'Article');
			return -12;
		}
    }

    public function getSrc($link){
        $link=htmlspecialchars_decode($link);
        //取得uri数据
        $c=Tools::OZCurl($link, 30);
		if(!isset($c['Info']['http_code'])||$c['Info']['http_code']!=200){
			Yii::log(__FILE__.'::'.__LINE__.'::!isset($c[\'Info\'][\'http_code\'])||$c[\'Info\'][\'http_code\']!=200', 'warning', 'Article');
			return -1;
		}else{
            $html=$c['Result'];
        }

        $fulltitle=Tools::cutContent($html, '<title>', '</title>');				//取得标题
        if($fulltitle=='掌中天涯(beta)'){
            if(Tools::cutContent($html, '<div class="p lk">', '<br/>')=='该贴不存在'){
                return -5;
            }
        }

		//校验页面是否下载完成
		$nav=Tools::cutContent($html, '<div class="p3">', '</div>');

		if(strpos($nav, '只看楼主')===false || strpos($nav, '最新回帖')===false || strpos($nav, '去底部')===false){
			Yii::log(__FILE__.'::'.__LINE__.'::$nav error', 'warning', 'Article');
			return -2;
		}


		if(strpos($fulltitle, '[')===0 && $cut=strpos($fulltitle, ']')!==false){
			$tag=Tools::cutContent($fulltitle, '[', ']');
			$title_cut=explode(']', $fulltitle);
			$title=array_pop($title_cut);
		}else{
			$tag='';
			$title=$fulltitle;
		}

		$footer=Tools::cutContent($html, '<form  action="artgo.jsp"  method="get">', '</form>');
        if(empty($footer)){
            $footer=Tools::cutContent($html, '<form  action="art.jsp"  method="get">', '</form>');
        }
		if(strpos($footer, 'name="item"')===false || strpos($footer, 'name="id"')===false) {
			Yii::log(__FILE__.'::'.__LINE__.'$footer error', 'warning', 'Article');
			return -3;
		}

		$page_content=Tools::cutContent($html, '<div class="pg">', '</div>');
		$next_link=self::find_next_link($page_content);
		if(!Tools::is_url($next_link)){
			Yii::log(__FILE__.'::'.__LINE__.'$next_link error', 'warning', 'Article');
			return -4;
		}

		//帖子列表
		$find=self::find_author_post(Tools::cutContent($html, '<div class="p3">', '<form  action="artgo.jsp"  method="get">'));
		if(empty($find)){
            $find=self::find_author_post(Tools::cutContent($html, '<div class="p3">', '<form  action="art.jsp"  method="get">'));
        }
		$page_cut_1=Tools::cutContent($footer, '(', '页)');
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

        $find['title']=$title;
        $find['tag']=$tag;
        $find['next_link']=$next_link;
        return $find;
    }

    function setItem($id,$next_src=''){
		$tid=intval($id);
		$_item=Item::model()->findByPk($tid);
		if(empty($_item)){
            Yii::log(__FILE__.'::'.__LINE__.'::!$article->page>$article->cto+1', 'warning', 'Item');
			return -1;
        }

//		$page=intval($page)<0 ? 0 : intval($page);	//当前板块页码
//		if($page>100){   //更新前20页
//            return -2;
//        }

		//$this->_setting['expire']=3600;
		$_url='http://3g.tianya.cn/bbs/list.jsp?item='.$_item->key;
        if(!empty($next_src)){
            $_url=htmlspecialchars_decode($next_src);
            if(!Tools::is_url($_url)){
                return -2;
            }
        }
		$c=Tools::OZCurl($_url, 20);
		if(!isset($c['Info']['http_code'])||($c['Info']['http_code']!=200&&$c['Info']['http_code']!=0)){
			Yii::log(__FILE__.'::'.__LINE__.'::!isset($c[\'Info\'][\'http_code\'])||$c[\'Info\'][\'http_code\']!=200', 'warning', 'Item');
			return -3;
		}else{
            $html=$c['Result'];
        }

		$title=Tools::cutContent($html, '<br/>'."\r\n".'论坛-', "\r\n".'</div>');
		//校验页面是否下载完成
		$footer=Tools::cutContent($html, '<div class="lk">', '<br/>');
		if(empty($title) || strpos($footer, '下一页')===false || strpos($footer, $_item->key)===false){
            Yii::log(__FILE__.'::'.__LINE__.'::get item src error', 'warning', 'Item');
			return -4;
        }
        $_item->name=$title;
        
		//帖子列表
		$content=Tools::cutContent($html, '<div class="p">', '</div>');
		$find=self::find_article_info($content);

		if(isset($find['link']) && isset($find['content'])){
			if(empty($find['link']) || empty($find['content']) || (($count = count($find['link'])) !== count($find['content']))){
				Yii::log(__FILE__.'::'.__LINE__.'::get item src error', 'warning', 'Item');
			    return -5;
			}

			$article= new Article();
			$criteria=new CDbCriteria;

			$_article=array();
			for($i=0,$j=0;$i<$count;$i++){
				if(!isset($find['reach'][$i]) || $find['reach'][$i]<50000)	//没有100000访问量
					continue;
				if(!isset($find['reply'][$i]) || $find['reply'][$i]<1000)	//没有10000回复
					continue;
//                pr($find['link'][$i],"\r\n");
//                pr($find['reach'][$i],"\r\n");
				//有100000访问量 或者 有10000回复继续整理
//				if((!isset($find['reach'][$i]) || $find['reach'][$i]<100000) && (!isset($find['reply'][$i]) || $find['reply'][$i]<1000))
//					continue;

				//pr($find['content'][$i]);
				$aid=intval(Tools::cutContent($find['link'][$i], '&id=', '&idwriter=0&key=0&chk='));
				if($aid<0){
                    Yii::log(__FILE__.'::'.__LINE__.'::$aid<0', 'warning', 'Item');
                    continue;
                }
				if(strpos($find['content'][$i], '[')===0 && $cut=strpos($find['content'][$i], ']')!==false){
					$tag=Tools::cutContent($find['content'][$i], '[', ']');
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
				//pr($find['content'][$i]);

				if(isset($_article[$i]->id) && $_article[$i]->id>0){
					//不更新状态，跳过
					if($_article[$i]->status!=1) continue;
					if($_article[$i]->title != $title || $_article[$i]->tag != $tag || $_article[$i]->un != $un){
						$_article[$i]->title = $title;
						$_article[$i]->tag = $tag;
						!empty($un) && $_article[$i]->un = $un;
						$_article[$i]->reach = $find['reach'];
						$_article[$i]->reply = $find['reply'];
						$_article[$i]->save();
					}
				}else{
					$_article[$i] = clone $article;
					$_article[$i]->cid=$_item->cid;
					$_article[$i]->tid=$tid;
					$_article[$i]->aid=$aid;
					$_article[$i]->title=$title;
					$_article[$i]->tag=$tag;
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
				}
			}
            $j++;
		}

		//更新统计
		$total=Yii::app()->db->createCommand()
				->select('count(*) as count')
				->from('{{article}}')
				->where('tid=:tid AND cid=:cid', array(':tid'=>$_item->id, ':cid'=>$_item->cid))
				->queryRow();
		//pr($count);
		$_item->uptime=time();
		$_item->count=$total['count'];
		$_item->save();

        
        if($j>0){
//            pd($footer);
            preg_match("'\|<a\s+href=\"(.*?)\">下一页</a>'isx", $footer, $matches);
//            pd($matches);
            if(!empty($matches[1])){
                return 'http://3g.tianya.cn/bbs/'.$matches[1];
            }
            preg_match("'href=\"(.*?)\">下一页</a>'isx", $footer, $matches);
            if(!empty($matches[1])){
                return 'http://3g.tianya.cn/bbs/'.$matches[1];
            }
        }
        
        return true;
    }

    function setChannel($id){
		$key=intval($id);
		//配置轮询的范围
		if($key<0 || $key>19){
			Yii::log(__FILE__.'::'.__LINE__.'::$key<0 || $key>19', 'warning', 'Channel');
			return -1;
		}

		$_url='http://3g.tianya.cn/nav/more.jsp?chl='.$key;
		//缓存一周
		$c=Tools::OZCurl($_url, 3600*24*7);
		if(!isset($c['Info']['http_code'])||$c['Info']['http_code']!=200){
			Yii::log(__FILE__.'::'.__LINE__.'::!isset($c[\'Info\'][\'http_code\'])||$c[\'Info\'][\'http_code\']!=200', 'warning', 'Channel');
			return -2;
		}else{
            $html=$c['Result'];
        }

		//校验页面是否下载完成
		$title=Tools::cutContent($html, '<title>天涯导航_', '</title>');
		if(strlen($title)<2){
			Yii::log(__FILE__.'::'.__LINE__.'::strlen($title)<2', 'warning', 'Channel');
			return -3;
		}
		$footer=Tools::cutContent($html, '<div class="f" id="bottom">', '</div>');
		if(strpos($footer, '天涯首页')===false){
			Yii::log(__FILE__.'::'.__LINE__.'::strpos($footer, \'天涯首页\')===false', 'warning', 'Channel');
			return -4;
		}

		$channel = new Channel();
		$_channel = $channel->find('`key` LIKE :key', array(':key'=>$key));

		//更新频道的item列表
		$content=Tools::cutContent($html, '<div class="p">', '</div>');
		$find=self::find_item_info($content);
//		pd($find);

		if(isset($find['key']) && isset($find['name'])){
			if(empty($find['key']) || (($count = count($find['key'])) !== count($find['name']))){
                Yii::log(__FILE__.'::'.__LINE__.'::empty($find[\'key\']) || (($count = count($find[\'key\'])) !== count($find[\'name\'])', 'warning', 'Channel');
                return -5;
            }

			//保存频道
			if(isset($_channel->id) && $_channel->id>0){
				if($_channel->status!=1)
					//die(json_encode(++$key));	//控制js进入下一个channel查询
				{
					Yii::log(__FILE__.'::'.__LINE__.'::$_channel->status!=1', 'warning', 'Channel');
                    return -6;
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

        return ++$key;
    }


    public static function find_next_link($document) {
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
	    $key = array_search('下一页', $match['content']);
	    if(isset($match['link'][$key]))
	    	return 'http://3g.tianya.cn/bbs/'.$match['link'][$key];
	    return false;
	}

	public static function find_author_post($document) {
		$match=false;
	    preg_match_all("'<div\s+class=\"lk\">(.*?)</div>[\r\n]*?<div\s+class=\"sp\s+lk\">(.*?)</div>'isx",$document,$cut);
	    //print_r($cut);
	    if((isset($cut[1][0]) && isset($cut[2][0])) && ($count=count($cut[1]))===count($cut[2])){
//		    pr($cut);
//			pr($count);echo "\r\n\r\n\r\n\r\n";
		    $j=0;
			for($i=0;$i<$count;$i++){
				$head=$cut[1][$i];
				$body=$cut[2][$i];
//				pr($head);echo "\r\n\r\n";
//				pr($body);echo "\r\n";
				if(strpos($head, '楼主:')===0){	//匹配顶楼
					$match['reach']=intval(Tools::cutContent($head, '访问:', '回复:'));
					$match['reply']=intval(Tools::cutContent($head, '回复:', '<br/>'));
					unset($body_info);
					preg_match_all("'(.*?)(<br\/>[\s\W]{4,6})*?<a\shref=\"?rep\.jsp\?'isx",$body,$body_info);
					$match['post'][$j]['body']=$body_info[1][0];
					$match['post'][$j]['pos']=0;
					$j++;
				}else if(strpos($head, '<span class="red">楼主</span>')!==false){
					unset($body_info);
					preg_match_all("'(.*?)(<br\/>[\s\W]{4,6})*?<a\shref=\"?rep\.jsp\?[^>]+?>.*?(\d+?)[^\d]+</a>'isx",$body,$body_info);
//					pr($body_info);
					$match['post'][$j]['body']=$body_info[1][0];
					$match['post'][$j]['pos']=$body_info[3][0];
					$j++;
				}else{
					continue;
				}
                $un=Tools::cutContent($head, '">', '</a><br/>');
                if(!empty($un)){
                    $match['un']=$un;
                }

			}
	    }

	    return $match;
	}



	public static function find_article_info($document) {
	    preg_match_all("'<\s*a\s.*?href\s*=\s*([\"\'])?art(?(1)(.*?)\\1|([^\s\>]+))[^>]*>?(.*?)</a>'isx",$document,$links);

	    while(list($key,$val) = each($links[2])) {
	        if(!empty($val))
	            $match['link'][] = html_entity_decode('art'.$val);
	    }
	    while(list($key,$val) = each($links[3])) {
	        if(!empty($val))
	            $match['link'][] = html_entity_decode('art'.$val);
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
	    //print_r($info);die;
		while(list($key,$val) = each($info[2])) {
	        if(!empty($val))
	            $match['reach'][] = $val;
            else
                $match['reach'][] = 0;
	    }
		while(list($key,$val) = each($info[3])) {
	        if(!empty($val))
	            $match['reply'][] = $val;
            else
                $match['reply'][] = 0;
	    }
		while(list($key,$val) = each($info[4])) {
	        if(!empty($val))
	            $match['author'][] = $val;
            else
                $match['author'][] = '';
	    }

	    return $match;
	}

	public static function find_item_info($html) {
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

    /*
     * <div rid="0" class="topic_potion">
        <a title="做记号，下次从这里读" onclick="SetMark(<?php echo $article->id.', '.$data->id.', '.$data->pos.', 0, \''.str_replace('\'', '', $article->title).'\''; ?>);" href="#<?php echo $data->pos;?>">Mark</a>
       </div>
     */
    public static function ArticleMark($article_id, $data_id, $dat_pos, $article_title){

    }

    public static function share(){
        return
            $share=<<<EOF
<!-- JiaThis Button BEGIN -->
<div id="ckepop">
	<span class="jiathis_txt">分享到：</span>
	<a class="jiathis_button_qzone">QQ空间</a>
	<a class="jiathis_button_tsina">新浪微博</a>
	<a class="jiathis_button_tqq">腾讯微博</a>
	<a class="jiathis_button_renren">人人网</a>
	<a class="jiathis_button_douban">豆瓣</a>
	<a class="jiathis_button_fav">收藏夹</a>
</div>
<script type="text/javascript" src="http://v2.jiathis.com/code/jia.js" charset="utf-8"></script>
<!-- JiaThis Button END -->
EOF;
    }

    public static function link728x15(){
        return
            $ad=<<<EOF
<script type="text/javascript"><!--
google_ad_client = "ca-pub-4726192443658314";
/* 728x15-链接单元 */
google_ad_slot = "4456137852";
google_ad_width = 728;
google_ad_height = 15;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
EOF;
    }

    public static function link468x15(){
        return
$ad=<<<EOF
<script type="text/javascript"><!--
google_ad_client = "ca-pub-4726192443658314";
/* 468x15-链接单元 */
google_ad_slot = "1339611153";
google_ad_width = 468;
google_ad_height = 15;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
EOF;
    }

    public static function ad336x280(){
        return
            $ad=<<<EOF
<script type="text/javascript"><!--
google_ad_client = "ca-pub-4726192443658314";
/* 336x280-大矩形 */
google_ad_slot = "3354931463";
google_ad_width = 336;
google_ad_height = 280;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
EOF;
    }


    public static function ad250x250(){
        return
$ad=<<<EOF
<script type="text/javascript"><!--
google_ad_client = "ca-pub-4726192443658314";
/* 250x250-正方形 */
google_ad_slot = "4163535743";
google_ad_width = 250;
google_ad_height = 250;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
EOF;
    }

    public static function ad468x60(){
        return
            $ad=<<<EOF
<script type="text/javascript"><!--
google_ad_client = "ca-pub-4726192443658314";
/* 468x60-横幅 */
google_ad_slot = "8866488934";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
EOF;
    }

    public static function ad160x600(){
        return
            $ad=<<<EOF
<script type="text/javascript"><!--
google_ad_client = "ca-pub-4726192443658314";
/* 160x600-宽幅摩天大楼 */
google_ad_slot = "0203809730";
google_ad_width = 160;
google_ad_height = 600;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
EOF;
    }

    public static function ad728x90(){
        return
            $ad=<<<EOF
<script type="text/javascript"><!--
google_ad_client = "ca-pub-4726192443658314";
/* 728x90-首页横幅 */
google_ad_slot = "1018549157";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
EOF;
    }

    public static function Search(){
        return $f=
<<<EOF
<form action="/search" id="cse-search-box">
        <input type="hidden" name="cx" value="partner-pub-4726192443658314:4873446973" />
        <input type="hidden" name="cof" value="FORID:10" />
        <input type="hidden" name="ie" value="UTF-8" />
        <input type="text" name="q" size="34" />
        <input type="submit" name="sa" value="整站搜索" />
</form>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">google.load("elements", "1", {packages: "transliteration"});</script>
<script type="text/javascript" src="http://www.google.com/cse/t13n?form=cse-search-box&t13n_langs=en"></script>
<script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=cse-search-box&amp;lang=zh-Hans"></script>
<script type="text/javascript" src="http://www.google.com/cse/query_renderer.js"></script>
<div id="queries"></div>
<script src="http://www.google.com/cse/api/partner-pub-4726192443658314/cse/4873446973/queries/js?oe=UTF-8&amp;callback=(new+PopularQueryRenderer(document.getElementById(%22queries%22))).render"></script>
EOF;
    }

    public static function Footer($aid){
        $xml_src=isset($aid) ? 'sitemap/'.$aid.'.xml' : 'sitemap.xml';
        return '&copy; 2010 &ndash; '.date('Y').' '.$_SERVER['HTTP_HOST'].' all rights reseved.'.
        '&nbsp;-&nbsp;<a href="'.Yii::app()->request->hostInfo.'/sitemaps.xml">Sitempas</a>&nbsp;-&nbsp;'.
        '<a href="'. Yii::app()->request->hostInfo .'/'. $xml_src .'">Index</a>&nbsp;-&nbsp;'.
        '<a href="'.Yii::app()->request->hostInfo.'/rss.xml">RSS</a>&nbsp;-&nbsp;'.
        '<a href="'.Yii::app()->request->hostInfo.'/atom.xml">ATOM</a>&nbsp;-&nbsp;';
    }
     /*
      * <div class="update_time">
		<span class="floor"><?php echo $data->pos==0?'顶楼':'<em>#</em>'.$data->pos; ?></span>
		<span>2011-10-18 00:59:51</span>
		<a name="p<?php echo $data->pos;?>"></a>
	</div>
      */
    public static function ArticleHead($data_pos){

    }

    public static function filterPost($in='',$aid,$page){
        if(empty($in)){
            return false;
        }

        self::$dm=mb_substr(strip_tags($in),0,512);
        self::$aid=intval($aid);
        self::$page=intval($page);
        $in=preg_replace_callback('/<img\s+src="(.*?)"\/><a\s+href="(.*?)">(.*?)<\/a>/i',array('self','mk_link'),$in);
        /*
        $in=preg_replace_callback('/(<a\s+.*?href=\s*([\"\']?))([^\'^\"]*?)((?(2)\\2)[^>^\/]*?>)(.*?)(<\/a>)/isx',array('self','mk_herf'),$in);
        */
		return $in;
    }

    public static $dm='';
    public static function mk_herf($matches)
	{
		if(substr($matches[3],0,7)!=='http://'){
			return $matches[0];
		}
		$t=strip_tags($matches[5]);
        $t=str_replace("\r\n", '', $t);
		$src='/index.php/api/a?href='.urlencode($matches[3]).
             '&t='.urlencode($t).
             '&f='.urlencode($_SERVER['REQUEST_URI']).
             '&c='.urlencode(self::$dm);
		return $matches[1].$src.$matches[2].' target="_blank">'.$matches[5].$matches[6];
	}

    public static $aid=0;
    public static $page=0;
	public static function mk_link($matches)
	{
        $aid=self::$aid;
        $page=self::$page;
		if($matches[3]=='(原图)'){
			if(($op=strrpos($matches[1],'.'))===false){
				$ext1='';
			}
			$ext1=substr($matches[1], $op);

			if(($op=strrpos($matches[2],'.'))===false){
				$ext2='';
			}
			$ext2=substr($matches[2], $op);

			$img_s='http://api.orzero.com/index.php?r=api/img&w=925&ww=200&m='.urlencode(MCrypy::encrypt('Mtianya.com', Yii::app()->params['MCrypy'], 128)).'&src='.urlencode(MCrypy::encrypt($matches[2], Yii::app()->params['MCrypy'], 128));
            $img_b='http://api.orzero.com/index.php?r=api/img&ww=200&m='.urlencode(MCrypy::encrypt('Mtianya.com', Yii::app()->params['MCrypy'], 128)).'&src='.urlencode(MCrypy::encrypt($matches[2], Yii::app()->params['MCrypy'], 128));

			return '<a class="oz" style="max-width:600px;max-height:400px;" href="'.$img_b.'" target="_blank"><img src="'.$img_s.'" /></a>';
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

    public static function t($str='',$params=array(),$dic='tianya') {
		return Yii::t($dic, $str, $params);
	}

    public static function powered()
	{
		return '<a href="http://'.$_SERVER['HTTP_HOST'].'" rel="external">'.$_SERVER['HTTP_HOST'].'</a>';
	}

    public static function callImage($source_src,$width=0){
        if(empty($source_src))
            return false;
        if(!Tools::is_url($source_src))
            return false;

        $s = '&src='.urlencode(MCrypy::encrypt($source_src, Yii::app()->params['MCrypy'], 128));
        $w='';
        if(intval($width)>0){
            $w = '&w='.intval($width);
        }

        return 'http://api.orzero.com/img/'.$w.'/'.$s;
    }
}