<?php
/*
Template Name: Category
*/

get_header();

//$category = get_query_var('c');
$category = get_query_var('cat');
$brand_flag = false;
$cate_flag = false;


$cate = get_category($category);

global $wpdb;
// 判断是否为品牌
$sql = $wpdb->prepare("select parent from {$wpdb->prefix}term_taxonomy where term_id = %d", $category);
$parent = $wpdb->get_var($sql);
// 当前为品牌，展示品牌下所有分类和型号
if($parent == 0){
    $brand_flag = true;
    
    // 按分类分组，每个分类查询8条数据
    /*
    $sql = $wpdb->prepare("
        select p.post_title, p.post_name, t2.term_id cat_id, t2.name cat_name, t.term_id mode_id, t.name model_name from {$wpdb->prefix}posts p 
        inner join {$wpdb->prefix}term_relationships tr on p.ID = tr.object_id 
        inner join {$wpdb->prefix}term_taxonomy tt on tt.term_taxonomy_id = tr.term_taxonomy_id 
        inner join {$wpdb->prefix}terms t on tt.term_id = t.term_id 
        inner join {$wpdb->prefix}term_taxonomy tt2 on tt.parent = tt2.term_taxonomy_id 
        inner join {$wpdb->prefix}terms t2 on t2.term_id = tt2.term_id 
        where tt2.parent = %d
        order by t2.name
    ", $category);
    */
    $sql = $wpdb->prepare("
        with post_datas as (        
        select p.post_title, p.post_name, t2.term_id cat_id, t2.name cat_name, t.term_id mode_id, t.name model_name,
                row_number() over (PARTITION BY t2.term_id ORDER BY t.name) as row_number
                from {$wpdb->prefix}posts p 
                inner join {$wpdb->prefix}term_relationships tr on p.ID = tr.object_id 
                inner join {$wpdb->prefix}term_taxonomy tt on tt.term_taxonomy_id = tr.term_taxonomy_id 
                inner join {$wpdb->prefix}terms t on tt.term_id = t.term_id 
                inner join {$wpdb->prefix}term_taxonomy tt2 on tt.parent = tt2.term_taxonomy_id 
                inner join {$wpdb->prefix}terms t2 on t2.term_id = tt2.term_id 
                where tt2.parent = %d
        )
        select * from post_datas
        where row_number <= 8
    ", $category);

    $sql_post_cnt = $wpdb->prepare("
        select count(1) from {$wpdb->prefix}posts p 
        inner join {$wpdb->prefix}term_relationships tr on p.ID = tr.object_id 
        inner join {$wpdb->prefix}term_taxonomy tt on tt.term_taxonomy_id = tr.term_taxonomy_id 
        inner join {$wpdb->prefix}terms t on tt.term_id = t.term_id 
        inner join {$wpdb->prefix}term_taxonomy tt2 on tt.parent = tt2.term_taxonomy_id 
        inner join {$wpdb->prefix}terms t2 on t2.term_id = tt2.term_id 
        where tt2.parent = %d
        ", $category);

    $lasted_post_sql = $wpdb->prepare("
        select p.post_title, p.post_name, t2.term_id cat_id, t2.name cat_name, t.term_id mode_id, t.name model_name from {$wpdb->prefix}posts p 
        inner join {$wpdb->prefix}term_relationships tr on p.ID = tr.object_id 
        inner join {$wpdb->prefix}term_taxonomy tt on tt.term_taxonomy_id = tr.term_taxonomy_id 
        inner join {$wpdb->prefix}terms t on tt.term_id = t.term_id 
        inner join {$wpdb->prefix}term_taxonomy tt2 on tt.parent = tt2.term_taxonomy_id 
        inner join {$wpdb->prefix}terms t2 on t2.term_id = tt2.term_id 
        where tt2.parent = %d
        order by p.post_date desc
        limit 0, 8
        ", $category);
}
// 当前分类，展示所有型号
else{
    $brand_flag = false;

    // 判断是否为型号
    $sql = $wpdb->prepare("select count(1) from {$wpdb->prefix}term_taxonomy tt where tt.parent = %d", $category);
    $child = $wpdb->get_var($sql);
    // 当前为型号
    if($child == 0){
        $sql = $wpdb->prepare("
            select p.post_title, p.post_name, t.term_id mode_id, t.name model_name from {$wpdb->prefix}posts p 
            inner join {$wpdb->prefix}term_relationships tr on p.ID = tr.object_id 
            inner join {$wpdb->prefix}term_taxonomy tt on tt.term_taxonomy_id = tr.term_taxonomy_id 
            inner join {$wpdb->prefix}terms t on tt.term_id = t.term_id 
            where tt.term_id = %d
            order by t.name
        ", $category);

        $sql_post_cnt = $wpdb->prepare("
            select count(1) from {$wpdb->prefix}posts p 
            inner join {$wpdb->prefix}term_relationships tr on p.ID = tr.object_id 
            inner join {$wpdb->prefix}term_taxonomy tt on tt.term_taxonomy_id = tr.term_taxonomy_id 
            inner join {$wpdb->prefix}terms t on tt.term_id = t.term_id 
            where tt.term_id = %d
        ", $category);

        $lasted_post_sql = $wpdb->prepare("
            select p.post_title, p.post_name, t.term_id mode_id, t.name model_name from {$wpdb->prefix}posts p 
            inner join {$wpdb->prefix}term_relationships tr on p.ID = tr.object_id 
            inner join {$wpdb->prefix}term_taxonomy tt on tt.term_taxonomy_id = tr.term_taxonomy_id 
            inner join {$wpdb->prefix}terms t on tt.term_id = t.term_id 
            where tt.term_id = %d
            order by p.post_date desc
        ", $category);
    }
    // 当前为类别，按字母分类
    else{
        $cate_flag = true;

        $sql = $wpdb->prepare("
            select p.post_title, p.post_name, t.term_id mode_id, t.name model_name, substring(t.slug, 1, 1) letter from {$wpdb->prefix}posts p 
            inner join {$wpdb->prefix}term_relationships tr on p.ID = tr.object_id 
            inner join {$wpdb->prefix}term_taxonomy tt on tt.term_taxonomy_id = tr.term_taxonomy_id 
            inner join {$wpdb->prefix}terms t on tt.term_id = t.term_id 
            where tt.parent = %d
            order by t.slug
        ", $category);

        // 导航
        $sql_nav = $wpdb->prepare("
            select distinct substring(t.slug, 1, 1) letter from oil_posts p 
            inner join oil_term_relationships tr on p.ID = tr.object_id 
            inner join oil_term_taxonomy tt on tt.term_taxonomy_id = tr.term_taxonomy_id 
            inner join oil_terms t on tt.term_id = t.term_id 
            where tt.parent = %d
            order by t.slug
        ", $category);

        $sql_post_cnt = $wpdb->prepare("
            select count(1) model_name from {$wpdb->prefix}posts p 
            inner join {$wpdb->prefix}term_relationships tr on p.ID = tr.object_id 
            inner join {$wpdb->prefix}term_taxonomy tt on tt.term_taxonomy_id = tr.term_taxonomy_id 
            inner join {$wpdb->prefix}terms t on tt.term_id = t.term_id 
            where tt.parent = %d
        ", $category);

        $lasted_post_sql = $wpdb->prepare("
            select p.post_title, p.post_name, t.term_id mode_id, t.name model_name from {$wpdb->prefix}posts p 
            inner join {$wpdb->prefix}term_relationships tr on p.ID = tr.object_id 
            inner join {$wpdb->prefix}term_taxonomy tt on tt.term_taxonomy_id = tr.term_taxonomy_id 
            inner join {$wpdb->prefix}terms t on tt.term_id = t.term_id 
            where tt.parent = %d
            order by p.post_date desc
        ", $category);
    }

    
}

$results = $wpdb->get_results($sql);

$post_cnt = $wpdb->get_var($sql_post_cnt);

$lasted_posts = $wpdb->get_results($lasted_post_sql);

if($cate_flag){
    $cate_navs = $wpdb->get_results($sql_nav);
}

$last_brand = -1;
$last_cate_slug = '';

?>
<main>
    <div class="jumbotron" style="margin-bottom:0rem;">
        <h1 class="display-4"><?=$cate->name?> 文档</h1>
        <p>拥有超过 <?=$post_cnt?> 本 <?=$cate->name?> 文档</p>
    </div>
    <?php if($cate_flag): ?>
    <div class="brand-letter-box">
        <p><b>点击下面的字母，快速跳转到对应型号。</b></p>
        <div class="brand-letter-box-links">
            <?php 
                foreach($cate_navs as $l){
                    echo '<a href="#' . strtoupper($l->letter) . '">' . strtoupper($l->letter) . '</a>';
                } 
            ?>
        </div>
    </div>
    <?php endif; ?>
    <div style="padding:0rem 1rem 0rem 1rem;">
        <?php if($brand_flag): ?>
            <h1>最新文档</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" class="col-3" style="width:25%">型号</th>
                        <th scope="col" class="col-9" style="width:75%">文档</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($lasted_posts as $p): ?>
                        <tr>
                            <td><?=$p->model_name?></td>
                            <td><a href="<?=home_url('/' . $p->post_name)?>" target="_blank"><?=$p->post_title?></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php foreach($results as $idx => $r): ?>
                <?php if($r->cat_id != $last_brand): ?>
                    <?php $last_brand = $r->cat_id; ?>
                    <h1><?=$r->cat_name?></h1>

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" class="col-3" style="width:25%">型号</th>
                                <th scope="col" class="col-9" style="width:75%">文档</th>
                            </tr>
                        </thead>
                        <tbody>

                <?php endif; ?>
                    
                            <tr>
                                <td><?=$r->model_name?></td>
                                <td><a href="<?=home_url('/' . $r->post_name)?>" target="_blank"><?=$r->post_title?></a></td>
                            </tr>
                <?php if(count($results) - 1 === $idx || ($brand_flag && $results[$idx + 1]->cat_id != $last_brand)): ?>
                        </tbody>
                    </table>
                    <a href="<?=get_category_link($r->cat_id)?>" target="_blank" style="margin-left:5rem;">
                        查看所有<?=$r->cat_name?>文档
                    </a>
                <?php endif; ?>

            <?php endforeach; ?>

        <?php else: ?>
            <?php foreach($results as $idx => $r): ?>
                <?php if($r->letter !== $last_cate_slug): ?>
                    <?php $last_cate_slug = $r->letter; ?>
                        <a name="<?=strtoupper($r->letter)?>"><h1><?=$r->model_name?></h1></a>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" class="col-3" style="width:25%">型号</th>
                                    <th scope="col" class="col-9" style="width:75%">文档</th>
                                </tr>
                            </thead>
                            <tbody>
                <?php endif; ?>
                    <tr>
                        <td><?=$r->model_name?></td>
                        <td><a href="<?=home_url('/' . $r->post_name)?>" target="_blank"><?=$r->post_title?></a></td>
                    </tr>
                <?php if(count($results) - 1 === $idx || ($cate_flag && $results[$idx + 1]->letter != $last_cate_slug)): ?>
                    </tbody>
                </table>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</main>


<?php get_footer(); ?>