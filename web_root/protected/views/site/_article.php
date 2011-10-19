<div class="view">
    <div class="update_time">
		<span class="floor"><?php echo $data->pos==0?'楼主':$data->pos; ?><em>#</em></span>
		<span>2011-10-18 00:59:51</span>
		<a name="in0"></a>
	</div>
	<?php echo $data->text; ?>
    <div rid="0" class="topic_potion">
        <a title="做记号，下次从这里读" onclick="SetMark(<?php echo $article->id.', '.$data->id.', '.'0'.', \''.str_replace('\'', '', $article->title).'\''; ?>);" href="#<?php echo $data->pos;?>">Mark</a>
    </div>
</div>
