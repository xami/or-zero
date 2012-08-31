<article id="post-<?php echo $data->pos; ?>" class="post-1959 post type-post status-publish format-standard hentry category-forum cf">
    <header>
        <?php echo Tianya::ArticleHead($data->pos);?>
    </header>
    <div class="content">
    <?php
//        if($data->id%10 == 1) {
//            echo Tianya::link728x15().'<div class="clear"></div>';
//        }
        echo Tianya::filterPost($data->text, $article->id, ceil($data->id/10));
//        if($data->id%3 == 2) {
//            echo '<div style="margin-left:25px">'.Tianya::link728x15().'</div>';
//        }
    ?>
    </div>
    <footer>
        <?php
            Tianya::ArticleMark($article->id, $data->id, $data->pos, $article->title);
        ?>
        <?php
        if($data->id%10 == 1):
            ?>

            <!-- Begin: adBrite, Generated: 2012-08-31 11:51:08  -->
            <script type="text/javascript">
                var AdBrite_Title_Color = '3D81EE';
                var AdBrite_Text_Color = '000000';
                var AdBrite_Background_Color = 'FFFFFF';
                var AdBrite_Border_Color = 'CCCCCC';
                var AdBrite_URL_Color = '6131BD';
                var AdBrite_Page_Url = '';
                try{var AdBrite_Iframe=window.top!=window.self?2:1;var AdBrite_Referrer=document.referrer==''?document.location:document.referrer;AdBrite_Referrer=encodeURIComponent(AdBrite_Referrer);}catch(e){var AdBrite_Iframe='';var AdBrite_Referrer='';}
            </script>
            <script type="text/javascript">document.write(String.fromCharCode(60,83,67,82,73,80,84));document.write(' src="http://ads.adbrite.com/mb/text_group.php?sid=940852&zs=3330305f323530&ifr='+AdBrite_Iframe+'&ref='+AdBrite_Referrer+'&purl='+encodeURIComponent(AdBrite_Page_Url)+'" type="text/javascript">');document.write(String.fromCharCode(60,47,83,67,82,73,80,84,62));</script>
            <div><a target="_top" href="http://www.adbrite.com/mb/commerce/purchase_form.php?opid=940852&afsid=1" style="font-weight:bold;font-family:Arial;font-size:13px;">Your Ad Here</a></div>
            <!-- End: adBrite -->

        <?php
        endif;
        ?>
    </footer>
</article>
