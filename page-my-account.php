<?php
/*
Template Name: My Account
*/

if (!is_user_logged_in()) {
    wp_redirect(home_url(('login')));
}

$current_user = wp_get_current_user();
$logout_url = wp_logout_url(home_url('/'));

// 获取用户收藏列表
$user_id = $current_user->ID;
$meta_key = 'favorite_doc';
$favorite_docs = get_user_meta($user_id, $meta_key, true);

get_header();
?>


<main>
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1 class="display-4">我的账号</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-2">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active" id="v-pills-home-tab" data-toggle="pill" data-target="#v-pills-home" name="my-account-tab" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">个人信息</button>
                <button class="nav-link" id="v-pills-favorites-tab" data-toggle="pill" data-target="#v-pills-favorites" name="my-account-tab" type="button" role="tab" aria-controls="v-pills-favorites" aria-selected="false">我的收藏</button>
            </div>
        </div>
        <div class="col-10">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                    <p>您好, <?php echo esc_html($current_user->display_name); ?>! <a href="<?=esc_url($logout_url)?>">退出</a></p>
                </div>
                <div class="tab-pane fade" id="v-pills-favorites" role="tabpanel" aria-labelledby="v-pills-favorites-tab">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">添加文件夹</button>
                    <div class="accordion account-favorite-box" id="favorites_dir">
                        <?php foreach($favorite_docs as $key => $value): ?>

                            <div class="card">
                                <div class="card-header" id="headingOne-<?=$key?>">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne-<?=$key?>" aria-expanded="true" aria-controls="collapseOne-<?=$key?>">
                                            <?=$key?>
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapseOne-<?=$key?>" class="collapse" aria-labelledby="headingOne-<?=$key?>" data-parent="#favorites_dir">
                                    <div class="card-body">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <?php foreach($value['links'] as $id => $doc):?>
                                                    <tr>
                                                        <td><a href="<?=get_permalink($id)?>" target="_blank"><?=$doc['title']?></a></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                        
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">输入文件夹名称</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input class="form-control form-control-lg" type="text" placeholder="输入文件夹名称" id="input_favorite_add_dir">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_favorite_add_dir">确定</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
            </div>
            </div>
        </div>
    </div>
</main>


<?php get_footer(); ?>