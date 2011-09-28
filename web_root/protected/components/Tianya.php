<?php
/**
 * Created by JetBrains PhpStorm.
 * User: lijia
 * Date: 11-9-28
 * Time: 下午1:43
 * To change this template use File | Settings | File Templates.
 */
 
class Tianya{
    function getChannels(){
        $data['channels']=Channel::model()->with('items')->findAll('t.status=1');

        $this->render('channels', $data);
    }

    function getChannel($id){
        $id=intval($id);
        $data['channel']=Channel::model()->with('items')->find('t.status=1 AND t.id=:id',array(':id'=>$id));

        return $data;
    }

    function getItems(){
        $data['items']=Item::model()->findAll('`status`=1');

        return $data;
    }

    function getItem($id){
        $id=intval($id);
        $data['item']=Item::model()->find('`id`='.$id.' AND `status`=1');

        return $data;
    }

    function getArticles(){
        $data['Articles']=Article::model()->findAll('`status`=1');

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
}