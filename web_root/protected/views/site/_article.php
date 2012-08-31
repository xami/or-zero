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

            <script type="text/javascript"><!--
            google_ad_client = "ca-pub-4726192443658314";
            /* 300x250-中矩形 */
            google_ad_slot = "6263226896";
            google_ad_width = 300;
            google_ad_height = 250;
            //-->
            </script>
            <script type="text/javascript"
                    src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
            </script>

        <?php
        endif;
        ?>
    </footer>
</article>
