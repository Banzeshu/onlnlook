<?php
/*
Template Name: Download Page
*/
    get_header();

    global $wpdb;

    $post_id = get_query_var('post_id');
    $post = get_post($post_id);
    
    $atts = shortcode_parse_atts($post->post_content);
    $url = $atts['url'];
    $attachment_id = $atts['attachment_id'];
    $attachment_metadata = get_post_meta($attachment_id, '_wp_attachment_metadata');

    $sql = $wpdb->prepare("select p.ID, p.post_title from oil_posts p 
        inner join oil_term_relationships tr on p.ID = tr.object_id 
        inner join oil_term_relationships tr2 on tr2.term_taxonomy_id = tr.term_taxonomy_id
        where tr2.object_id = %d and p.ID <> %d", $post_id, $post_id);
    $related = $wpdb->get_results($sql);
    
?>

<main>
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1 class="display-4">下载文档</h1>
        </div>
    </div>
    <div class="container">
        <div><h3>下载<?=$post->post_title?></h3></div>
        <p><b>文件大小</b> : <?=round($attachment_metadata[0]['filesize'] / 1024, 2)?>KB <b>分类</b> : <?=get_the_category_list(' / ', 'multiple', $post_id)?></p>
        <div>
            <a type="button" class="btn btn-primary" href="<?=$url?>" download="<?=$post->post_title?>" id="download_doc_download_btn" doc-id="<?=$post_id?>">
                <img src="<?=get_template_directory_uri()?>/images/download-one.png" alt="Icon" width="24" height="24">
                点击下载
            </a>
        </div>
    </div>
    <div class="container" style="margin-top:3rem;">
        <div><h3>相关文档</h3></div>
        <?php foreach($related as $r): ?>
            <a href="" target="_blank"><?=$r->post_title?></a>
        <?php endforeach; ?>
    </div>
</main>


<?php get_footer(); ?>