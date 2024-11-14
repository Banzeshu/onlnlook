<?php
/*
    Template Name: Brand Page Template
*/
get_header(); 

$brand = '';
$page = 1;
$page_size = 10;
$search_brand = get_query_var('brand');
if(isset($search_brand)){
    $searchs = explode('_', string: $search_brand);
    $brand = $searchs[0];
    if(count($searchs) === 2){
        $page = $searchs[1];
    }
}

global $wpdb;

$sql = $wpdb->prepare("
    select t.name, t.term_id, t.slug, group_concat(st.name order by st.term_id) s_name, group_concat(st.term_id order by st.term_id) s_id, 
    group_concat(st.slug order by st.term_id) s_slug
     from (
    select t.name, t.term_id, t.slug from {$wpdb->prefix}terms t 
    inner join {$wpdb->prefix}term_taxonomy a on t.term_id = a.term_taxonomy_id
    where a.parent = 0 and a.taxonomy = 'category' and t.slug like '%s'
    order by t.name
    limit %d, %d
    ) t 
    left join {$wpdb->prefix}term_taxonomy ca on ca.parent = t.term_id 
    left join {$wpdb->prefix}terms st on st.term_id = ca.term_id 
    group by t.term_id
", $brand . '%', ($page - 1) * $page_size, $page_size);


$results = $wpdb->get_results($sql);

$brand_cnt = $wpdb->get_var("select count(1) from {$wpdb->prefix}term_taxonomy tt where tt.parent = 0 and tt.taxonomy = 'category'");

$file_cnt = $wpdb->get_var("select count(1) from {$wpdb->prefix}posts p where p.post_type = 'attachment' and p.post_mime_type = 'application/pdf'");

?>

<main>
    <div class="jumbotron" style="margin-bottom:0rem;">
        <h1 class="display-4">受欢迎的品牌</h1>
        <p>拥有超过<?=$file_cnt?>本手册, 涵盖<?=$brand_cnt?>个品牌。</p>
    </div>

    <div class="brand-letter-box">
        <p><b>点击下面的字母，探索以每个字母开头的完整型号列表。</b></p>
        <div class="brand-letter-box-links">
            <?php 
                $letter = array('#', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
                foreach($letter as $l){
                    echo '<a href="' . home_url('/' . 'brand?brand=' . strtolower(string: $l)) . '">' . $l . '</a>';
                } 
            ?>
        </div>
    </div>
    <div style="padding:0rem 1rem 0rem 1rem;">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" class="col-3" style="width:25%">品牌</th>
                    <th scope="col" class="col-9" style="width:75%">类别</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach($results as $r){
                ?>
                    <tr>
                        <td><a href="<?=get_category_link($r->term_id)?>" target="_blank"><?=$r->name?></a></td>
                        <td>
                            <?php 
                                if(isset($r->s_name)){
                                    $sname_arr = explode(',', $r->s_name);
                                    $sid_arr = explode(',', string: $r->s_id);
                                    foreach($sname_arr as $s_index => $sname){
                                        ?>
                                            <a href="<?=get_category_link($sid_arr[$s_index])?>" target="_blank"><?=$sname?></a>
                                        <?php
                                        if(count($sname_arr) - 1 > $s_index){
                                            echo ', ';
                                        }
                                    }
                                }
                            ?>
                        </td>
                    </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
</main>


<?php get_footer(); ?>