<?php 
    get_header(); 
    global $wpdb;

    $update_cnt = $wpdb->get_var("select count(1) from {$wpdb->prefix}posts p where p.post_status = 'publish' and p.post_type = 'post' and p.post_date >= curdate()");

    $brand_cnt = $wpdb->get_var("select count(1) from {$wpdb->prefix}term_taxonomy tt where tt.parent = 0 and tt.taxonomy = 'category'");

    $file_cnt = $wpdb->get_var("select count(1) from {$wpdb->prefix}posts p where p.post_type = 'attachment' and p.post_mime_type = 'application/pdf'");

    $user_cnt = $wpdb->get_var("select count(1) from {$wpdb->prefix}users u");

    $download_cnt = get_option('doc_download_count_all') ?? 0;

    $upload_dir = ABSPATH . '/wp-content/uploads';
    $output = shell_exec("du -sh " . $upload_dir);

    $brands = $wpdb->get_results("select t.term_id, t.name from {$wpdb->prefix}terms t inner join {$wpdb->prefix}term_taxonomy tt on t.term_id = tt.term_id where tt.parent = 0 and tt.taxonomy = 'category' order by t.name");

    $hot_doc = $wpdb->get_results("select p.post_title, p.ID, p2.guid from oil_posts p
        left join oil_postmeta pm on p.ID = pm.post_id and pm.meta_key = 'doc_download_count'
        left join oil_postmeta pm2 on p.ID = pm2.post_id and pm2.meta_key = '_thumbnail_id'
        left join oil_posts p2 on p2.ID = pm2.meta_value 
        where p.post_status = 'publish' and p.post_type = 'post'
        order by pm.meta_value desc
        limit 0, 12");
?>
<main>
    <div class="index-jumbotron jumbotron" style="border-radius:0">
        <h1 class="display-4"><?=get_bloginfo('name')?></h1>
        <!-- <p class="lead"><?=get_bloginfo('description')?></p> 
        <hr class="my-4">-->
        
        <form action="<?=home_url('/')?>" class="index-search-form">
            <div class="input-group">
                <input class="form-control form-control-lg" type="search" placeholder="搜索" aria-label="Search" name="s" aria-describedby="search-buttons-index">
            </div>
        </form>
        <br/>
        <br/>
        <br/>
    </div>

    <div style="display:flex;justify-content: center;margin-top:-6rem;">
        <div class="card" >
            <div class="card-body">
                <div class="container">
                    <div class="row" style="padding:1rem 0rem 1rem 0rem">
                        <div class="col-6 col-md-4">
                            <span class="badge index-sum-tag d-flex">
                                <img src="<?=get_template_directory_uri()?>/images/update-rotation.png" alt="Icon" width="48" height="48">
                                <div style="font-size:1rem;line-height:1.5rem;">
                                    <div>今日更新: </div>
                                    <div><?=$update_cnt?> 册</div>
                                </div>                                
                            </span>
                        </div>
                        <div class="col-6 col-md-4">
                            <span class="badge index-sum-tag d-flex">
                                <img src="<?=get_template_directory_uri()?>/images/all-application.png" alt="Icon" width="48" height="48">
                                <div style="font-size:1rem;line-height:1.5rem;">
                                    <div>品牌总数: </div>
                                    <div><?=$brand_cnt?> 个</div>
                                </div>
                            </span>
                        </div>
                        <div class="col-6 col-md-4">
                            <span class="badge index-sum-tag d-flex">
                                <img src="<?=get_template_directory_uri()?>/images/pdf_file-pdf.png" alt="Icon" width="48" height="48">
                                <div style="font-size:1rem;line-height:1.5rem;">
                                    <div>文件数量: </div>
                                    <div><?=$file_cnt?> 个</div>
                                </div>
                            </span>
                        </div>

                        <div class="col-6 col-md-4">
                            <span class="badge index-sum-tag d-flex">
                                <img src="<?=get_template_directory_uri()?>/images/data-file.png" alt="Icon" width="48" height="48">
                                <div style="font-size:1rem;line-height:1.5rem;">
                                    <div>数据总量: </div>
                                    <div>1122 册</div>
                                </div>
                            </span>
                        </div>
                        <div class="col-6 col-md-4">
                            <span class="badge index-sum-tag d-flex">
                                <img src="<?=get_template_directory_uri()?>/images/download-three.png" alt="Icon" width="48" height="48">
                                <div style="font-size:1rem;line-height:1.5rem;">
                                    <div>总下载量: </div>
                                    <div><?=$download_cnt?> 次</div>
                                </div>
                            </span>
                        </div>
                        <div class="col-6 col-md-4">
                            <span class="badge index-sum-tag d-flex">
                                <img src="<?=get_template_directory_uri()?>/images/every-user.png" alt="Icon" width="48" height="48">
                                <div style="font-size:1rem;line-height:1.5rem;">
                                    <div>总用户数: </div>
                                    <div><?=$user_cnt?> 人</div>
                                </div>
                            </span>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container" style="margin-top:5rem;">
        <h2><?=$brand_cnt?>个品牌</h2>
        <div class="row">
            <?php foreach($brands as $b):  ?>
                <a href="<?=get_category_link($b->term_id)?>" class="col-6 col-md-2" target="_blank" style="margin-top:1rem;"><?=$b->name?></a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="container" style="margin-top:5rem;">
        <h2>热门文档</h2>
        <div class="row">
            <?php foreach($hot_doc as $d):  ?>
                <div class="col-xl-2 col-lg-3 col-6">
                    <div class="card" style="width: 10rem;">
                        <img src="<?=$d->guid?>" class="card-img-top" alt="<?=$d->post_title?>">
                        <div class="card-body">
                            <h5 class="card-title"><?=$d->post_title?></h5>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>
<?php get_footer(); ?>