<article id="post-<?php echo $data->pos; ?>" class="post-1959 post type-post status-publish format-standard hentry category-forum cf">
    <header>
        <?php echo Tianya::ArticleHead($data->pos);?>
    </header>
    <div class="content">
    <?php
        if($data->id%10 == 1) {
            echo Tianya::ad728x90().'<div class="clear"></div>';
        }
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
    </footer>
</article>
