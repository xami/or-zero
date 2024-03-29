<?php
class AjaxBuild extends CController
{
	public $type;
	public $fid;
	
	public function run($actionID='') {
		$ajaxSetup = 'jQuery.ajaxSetup({'.
			'type:\'POST\','.
			'url:\''.CController::createUrl('api/'.$this->type).'\','.
			'cache:false,'.
			'success:reRunAjax'.
		'});';
		//echo $this->type;
		$fid = intval($this->fid);
		if($fid>=0){
			if($this->type=='channel'){
				$data=Channel::model()->find('`key`='.$fid);
			}else if($this->type=='item'){
				$data=Item::model()->findByPk($fid);
			}else if($this->type=='article'){
				$data=Article::model()->findByPk($fid);
			}else return false;
			//print_r($data);
			if(empty($data)) return false;
		}
		//pr($fid);
		//channel	：id对应频道的关键词
		//item		: id对应具体的板块的具体页数,板块id由item指定
        if($this->type=='article'){
            $reRunAjax = 'function reRunAjax(loop){loop=parseInt(loop);'.
					'if(loop>0){jQuery.ajax({"data":{"id":'.$data->id.', "loop":loop}});}}'.
					'jQuery.ajax({"data":{"id":'.$data->id.', "loop":0}});';
        }else if($this->type=='item'){
            $reRunAjax = 'function reRunAjax(src){'.
					'if(src.length>10){jQuery.ajax({"data":{"id":'.$data->id.', "src":src}});}}'.
					'jQuery.ajax({"data":{"id":'.$data->id.', "src":""}});';
        }else if($this->type=='channel'){
            $reRunAjax = 'function reRunAjax(loop){loop=parseInt(loop);'.
					'if(loop>0){jQuery.ajax({"data":{"id":loop}});}}'.
					'jQuery.ajax({"data":{"id":'.$data->id.'}});';
        }

		
		$cs=Yii::app()->clientScript;
		$cs->registerCoreScript('jquery');
		echo CHtml::script('jQuery(function($){'.$ajaxSetup.$reRunAjax.'});');
	}
}