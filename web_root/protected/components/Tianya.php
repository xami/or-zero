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

    function setArticle(){
        
    }

    function setList(){
        
    }

    function setChannel(){
        
    }

    public static function t($str='',$params=array(),$dic='tianya') {
		return Yii::t($dic, $str, $params);
	}
}