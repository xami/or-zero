<div class="view">
    <div class="update_time">
		<span class="floor"><?php echo $data->pos==0?'顶楼':'<em>#</em>'.$data->pos; ?></span>
		<span>2011-10-18 00:59:51</span>
		<a name="p<?php echo $data->pos;?>"></a>
	</div>
	<?php echo Tianya::filterPost($data->text, $article->id, ceil($data->id/10)); ?>
    <div rid="0" class="topic_potion">
        <a title="做记号，下次从这里读" onclick="SetMark(<?php echo $article->id.', '.$data->pos.', '.'0'.', \''.str_replace('\'', '', $article->title).'\''; ?>);" href="#<?php echo $data->pos;?>">Mark</a>
    </div>
</div>
