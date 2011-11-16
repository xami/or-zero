<div class="view">
    <div class="update_time">
		<span class="floor"><?php echo $data->pos==0?'顶楼':'<em>#</em>'.$data->pos; ?></span>
		<span>2011-10-18 00:59:51</span>
		<a name="p<?php echo $data->pos;?>"></a>
	</div>
    <?php
    if(($data->id%10)==1 && strlen($data->text)>2000){
        ?>
<div style="float: right;"><script type="text/javascript"><!--
google_ad_client = "pub-4726192443658314";
/* 336x280, 创建于 11-3-10 */
google_ad_slot = "4619865687";
google_ad_width = 336;
google_ad_height = 280;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>
    <?php
    }
    ?>
    <?php echo Tianya::filterPost($data->text, $article->id, ceil($data->id/10));?>
    <div rid="0" class="topic_potion">
        <a title="做记号，下次从这里读" onclick="SetMark(<?php echo $article->id.', '.$data->pos.', '.'0'.', \''.str_replace('\'', '', $article->title).'\''; ?>);" href="#<?php echo $data->pos;?>">Mark</a>
    </div>
</div>
