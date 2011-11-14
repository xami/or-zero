<div class="view">
<?php
echo CHtml::link(CHtml::encode($data->item->name), '/item/'.$data->item->id.'/',array('class'=>'bt_c dlink')).'&nbsp;'.
CHtml::link(CHtml::encode($data->title), '/article/'.$data->id.'/index.html', array('target'=>'_blank')).
'(已整理:'.(int)(($data->pcount/20)+1).'页/访问:'.$data->hot.'次&nbsp;|&nbsp;作者:'
.CHtml::link($data->un, '/search?cx='.Yii::app()->params['google_search_ad'].'&cof=FORID:11&ie=UTF-8&q='
.$data->un.'&un=or').'&nbsp;|&nbsp;原帖:访问'.$data->reach.'/回复'.$data->reply.')<!--'.CHtml::link('[原帖]', $data->src).'-->';
?>
</div>