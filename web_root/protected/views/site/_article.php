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
google_ad_client = "ca-pub-4726192443658314";
/* 200x90-链接单元 */
google_ad_slot = "5798946537";
google_ad_width = 200;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div><div style="float: right;clear: both;"><script type="text/javascript"><!--
google_ad_client = "ca-pub-4726192443658314";
/* 200x90-链接单元 */
google_ad_slot = "5798946537";
google_ad_width = 200;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>
    <?php
    }
    ?>

    <?php echo Tianya::filterPost($data->text, $article->id, ceil($data->id/10));?>

    <?php
    if(($data->id%10)==1){
        ?>
<div style="clear:both;;"><script type="text/javascript"><!--
google_ad_client = "ca-pub-4726192443658314";
/* 728x15-链接单元 */
google_ad_slot = "4456137852";
google_ad_width = 728;
google_ad_height = 15;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>
    <?php
    }
    ?>

    <div rid="0" class="topic_potion">
        <a title="做记号，下次从这里读" onclick="SetMark(<?php echo $article->id.', '.$data->id.', '.$data->pos.', 0, \''.str_replace('\'', '', $article->title).'\''; ?>);" href="#<?php echo $data->pos;?>">Mark</a>
    </div>

    
</div>
