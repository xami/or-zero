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


    function getItems($cid=0, $tid=0, $page_size=10){
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
        $article=Article::model()->find('`id`='.$id.' AND `status`=1');

        if(empty($article)){
            Yii::log(__FILE__.'::'.__LINE__.'::empty($article)', 'warning', 'Tianya');
            return -1;
        }
        if(empty($article->src)){
            Yii::log(__FILE__.'::'.__LINE__.'::empty($article->src)', 'warning', 'Tianya');
			return -2;
		}
        if(empty($article->item)||empty($article->channel)) {
			Yii::log(__FILE__.'::'.__LINE__.'::empty($article->item)||empty($article->channel)', 'warning', 'Tianya');
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
            Yii::log(__FILE__.'::'.__LINE__.'::get $_P $_C', 'warning', 'Tianya');
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
				Yii::log(__FILE__.'::'.__LINE__.'::!$page->save()', 'warning', 'Tianya');
			    return -5;
			}

		}else{
			$page=$_P->findByPk($article->cto);
			if(empty($page->id)){
				$article->cto=0;
				$article->save();
				Yii::log(__FILE__.'::'.__LINE__.'::empty($page->id)', 'warning', 'Tianya');
			    return -6;
			}
		}

        $find=$this->getSrc($page->link);
        $next_page=$_P->findByPk($article->cto+1);
		if(empty($next_page)){
			$next_page=clone $_P;
			$next_page->id=$article->cto+1;
		}
        if(!isset($find['next_link'])||empty($find['next_link'])){
            Yii::log(__FILE__.'::'.__LINE__.'::empty($find[\'next_link\'])', 'warning', 'Tianya');
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

		$page->count=count($find['post']);
		$page->status=1;


		if($page->save()===false){
			Yii::log(__FILE__.'::'.__LINE__.'::$page->save()===false', 'warning', 'Tianya');
			return -8;
		}

		if($next_page->save()===false){
			Yii::log(__FILE__.'::'.__LINE__.'::$next_page->save()===false', 'warning', 'Tianya');
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
				Yii::log(__FILE__.'::'.__LINE__.'::$content->save()===false', 'warning', 'Tianya');
			    return -10;
			}

		}

		$article->pcount=$_C->count();
		if(($next_page->id<=$find['page']) && ($next_page->link!==false))
			$article->cto=$next_page->id;

//		pr($article);
		if($article->save()===false){
			Yii::log(__FILE__.'::'.__LINE__.'::$article->save()===false', 'warning', 'Tianya');
			return -11;
		}

		if($article->page>$article->cto+1){
			Yii::log(__FILE__.'::'.__LINE__.'::success', 'warning', 'Tianya');
//            echo(json_encode(1));	//继续循环
			return true;
		}else{
			Yii::log(__FILE__.'::'.__LINE__.'::!$article->page>$article->cto+1', 'warning', 'Tianya');
			return -12;
		}
    }

    public function getSrc($link){
        $link=htmlspecialchars_decode($link);
        //取得uri数据
        $c=Tools::OZCurl($link, 30);
		if(!isset($c['Info']['http_code'])||$c['Info']['http_code']!=200){
			Yii::log(__FILE__.'::'.__LINE__.'::!isset($c[\'Info\'][\'http_code\'])||$c[\'Info\'][\'http_code\']!=200', 'warning', 'Tianya');
			return -1;
		}else{
            $html=$c['Result'];
        }

		//校验页面是否下载完成
		$nav=Tools::cutContent($html, '<div class="p3">', '</div>');

		if(strpos($nav, '只看楼主')===false || strpos($nav, '最新回帖')===false || strpos($nav, '去底部')===false){
			Yii::log(__FILE__.'::'.__LINE__.'::$nav error', 'warning', 'Tianya');
			return -2;
		}

		$fulltitle=Tools::cutContent($html, '<title>', '</title>');				//取得标题
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
			Yii::log(__FILE__.'::'.__LINE__.'$footer error', 'warning', 'Tianya');
			return -3;
		}

		$page_content=Tools::cutContent($html, '<div class="pg">', '</div>');
		$next_link=self::find_next_link($page_content);
		if(!Tools::is_url($next_link)){
			Yii::log(__FILE__.'::'.__LINE__.'$next_link error', 'warning', 'Tianya');
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

    function setItem(){
        
    }

    function setChannel(){
        
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
	    //print_r($info);die;
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

    public static function t($str='',$params=array(),$dic='tianya') {
		return Yii::t($dic, $str, $params);
	}
}