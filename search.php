<?php
/*
Template Name: Search
*/
    get_header();

    
?>

<main>
    <?php if(have_posts()):?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">文档</th>
                    <th scope="col">品牌 / 类别 / 型号</th>
                </tr>
            </thead>
            <tbody>
                <?php while ( have_posts() ) : the_post(); ?>
                    <tr>
                        <td><?php  print_r(the_title()); ?></td>
                        <td><?php print_r(get_the_category_list(', ', 'multiple')); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else:?>
        没有查询到相关文档。
    <?php endif; ?>


</main>


<?php 

//global $wpdb;
//print_r($wpdb->queries);

?>
<?php get_footer(); ?>