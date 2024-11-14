<?php
/*
Template Name: Single
*/

get_header();

$logined = false;
if (is_user_logged_in()) {
    $logined = true;

    $current_user = wp_get_current_user();
    
    // 获取用户收藏列表
    $user_id = $current_user->ID;
    $meta_key = 'favorite_doc';
    $favorites = get_user_meta($user_id, $meta_key, true);

}

?>

<main>
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <?=the_title('<h1 class="display-4">', '</h1>')?> 
            <p class="lead"><?=the_excerpt()?></p>
        </div>
    </div>

    <div class="single-content">
        <div style="float:right;margin-bottom:2rem;">
            <a type="button" class="btn btn-primary" href="<?=home_url('/' . 'download' . '/' . get_the_ID())?>" target="_blank">
                <img src="<?=get_template_directory_uri()?>/images/download-one.png" alt="Icon" width="24" height="24">
                下载
            </a>
            <div class="btn-group">
                <button type="button" class="btn btn-primary" id="btn_favorite" doc-title="<?=the_title()?>" doc-id="<?=the_ID()?>">
                    <img src="<?=get_template_directory_uri()?>/images/like.png" alt="Icon" width="24" height="24">
                    收藏
                </button>
                <?php if($logined): ?>
                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu">
                        <?php foreach($favorites as $key => $value):?>
                            <a class="dropdown-item" name="btn_favorite_sub" favorite-dir="<?=$key?>" doc-title="<?=the_title()?>" doc-id="<?=the_ID()?>"><?=$key?></a>
                        <?php endforeach;?>
                    </div>
                <?php endif; ?>
            </div>
            <button type="button" class="btn btn-primary" data-toggle="popover" title="分享链接" data-content="<?=esc_url( home_url( add_query_arg( null, null ) ) )?>" data-placement="bottom">
                <img src="<?=get_template_directory_uri()?>/images/share-one.png" alt="Icon" width="24" height="24">
                分享
            </button>
            <button type="button" class="btn btn-primary" id="single_print_btn">
                <img src="<?=get_template_directory_uri()?>/images/printer-one.png" alt="Icon" width="24" height="24">
                打印
            </button>
        </div>

        <?php
            the_content();
        ?>

        <?php
            wp_link_pages( array(
                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'your-theme-slug' ),
                'after'  => '</div>',
            ) );
        ?>
    </div>
   
</main>

<?php get_footer(); ?>