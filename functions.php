<?php
// 加载资源
function load_bootstrap() {
    wp_enqueue_style('theme-style', get_stylesheet_uri());

    // 加载Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css');

    // 加载jQuery（WordPress自带）
    wp_enqueue_script('jquery');

    // 加载应用ajax
    wp_enqueue_script( 'doc-ajax-script', get_template_directory_uri() . '/js/doc.js', array( 'jquery' ), '1.0', true );
    wp_localize_script( 'doc-ajax-script', 'docAjaxObject', array(
        'url' => admin_url( 'admin-ajax.php' ),
        'nonce'  => wp_create_nonce( 'doc-ajax-nonce' )
    ) );

    // 加载Popper.js（Bootstrap依赖）
    wp_enqueue_script('popper-js', 'https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js', array('jquery'), null, true);

    // 加载Bootstrap JS
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js', array('popper-js'), null, true);
}
add_action('wp_enqueue_scripts', 'load_bootstrap');

// 启用特色图片/缩略图
add_theme_support( 'post-thumbnails', array( 'post' ) );
// 缩略图尺寸
// set_post_thumbnail_size( 50, 50, true );


function add_custom_query_var($vars) {
    $vars[] = "brand";
    $vars[] = "post_id";
    return $vars;
}
add_filter('query_vars', 'add_custom_query_var');

function custom_rewrite_rules() {
    add_rewrite_rule('^download/([^/]*)$', 'index.php?page_id=49&post_id=$matches[1]', 'top');
    flush_rewrite_rules();
}
add_action('init', 'custom_rewrite_rules');


// 登录成功跳转
function custom_login_redirect($redirect_to, $request, $user): string {
    return home_url('my-account');
}
add_filter('login_redirect', 'custom_login_redirect', 10, 3);

// 隐藏管理员导航栏
if (!current_user_can('administrator')) {
    add_filter('show_admin_bar', '__return_false');
}

// 注册ajax
// 添加收藏夹
function doc_favorite_add_handler() {
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'doc-ajax-nonce' ) ) {
        wp_send_json_error(array( 'message' => 'No naughty business, please!' ) );
    }

    $current_user = wp_get_current_user();
    
    // 获取用户收藏列表
    $user_id = $current_user->ID;
    $meta_key = 'favorite_doc';
    $meta_value = get_user_meta($user_id, $meta_key, true);
    if(!isset($meta_value) || empty($meta_value)){
        $meta_value = array();
    }
    $dir = $_POST['dir'] ?? '未分类';
    if(empty($dir)){
        $dir = '未分类';
    }

    $post_id = $_POST['post_id'] ?? -1;
    $title = $_POST['title'];
    if($post_id == -1){
        wp_send_json_error(array( 'message' => '收藏失败!' ) );
    }
    
    if(!isset($meta_value[$dir])){
        $meta_value[$dir] = array(
            'links' => array()
        );
    }
    
    if(array_key_exists($post_id, $meta_value[$dir]['links'])){
        wp_send_json_error(array( 'message' => '已收藏!' ) );
    };

    $meta_value[$dir]['links'][$post_id] = array(
        'title' => $title
    );

    update_user_meta($user_id, $meta_key, $meta_value);


    // Send response back to the frontend.
    wp_send_json_success( array( 'message' => '收藏成功!' ) );
}
function nopriv_doc_handler(){
    wp_send_json_error(array( 'message' => '未登录' ) );
}
add_action( 'wp_ajax_doc_favorite_add', 'doc_favorite_add_handler' );
add_action( 'wp_ajax_nopriv_doc_favorite_add', 'nopriv_doc_handler' );

// 添加目录
function doc_favorite_add_dir_handler(){
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'doc-ajax-nonce' ) ) {
        wp_send_json_error(array( 'message' => 'No naughty business, please!' ) );
    }

    $dir = $_POST['dir'] ?? '';
    if(empty($dir)){
        wp_send_json_error(array( 'message' => '文件夹名不能为空!' ) );
    }

    $current_user = wp_get_current_user();
    
    // 获取用户收藏列表
    $user_id = $current_user->ID;
    $meta_key = 'favorite_doc';
    $favorites = get_user_meta($user_id, $meta_key, true);
    if(!isset($favorites) || empty($favorites)){
        $favorites = array();
    }
    if(array_key_exists($dir, $favorites)){
        wp_send_json_error(array( 'message' => '文件夹已存在!' ) );
    }

    $favorites[$dir] = array(
        'links' => array()
    );

    update_user_meta($user_id, $meta_key, $favorites);

    // Send response back to the frontend.
    wp_send_json_success( array( 'message' => '文件夹创建成功!' ) );
}
add_action( 'wp_ajax_doc_favorite_add_dir', 'doc_favorite_add_dir_handler' );
add_action( 'wp_ajax_nopriv_doc_favorite_add_dir', 'nopriv_doc_handler' );


// 加载收藏夹
function doc_favorite_dir_list(){
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'doc-ajax-nonce' ) ) {
        wp_send_json_error(array( 'message' => 'No naughty business, please!' ) );
    }

    $current_user = wp_get_current_user();
    // 获取用户收藏列表
    $user_id = $current_user->ID;
    $meta_key = 'favorite_doc';
    $favorites = get_user_meta($user_id, $meta_key, true);
    wp_send_json_success( $favorites );
}
add_action('wp_ajax_doc_favorite_dir_list', 'doc_favorite_dir_list');
add_action('wp_ajax_nopriv_doc_favorite_dir_list', 'nopriv_doc_handler');


// 下载
function doc_download(){
    $option_value = get_option('doc_download_count_all');
    $post_id = $_POST['post_id'] ?? -1;

    if (!isset($option_value)) {
        $option_value = 0;
    }
    $option_value = $option_value + 1;

    update_option('doc_download_count_all', $option_value);

    // 修改文章
    $cnt = get_post_meta($post_id, 'doc_download_count', true);
    if(empty($cnt)){
        $cnt = 0;
    }
    update_post_meta($post_id, 'doc_download_count', $cnt + 1);
}
add_action('wp_ajax_doc_download', 'doc_download');
add_action('wp_ajax_nopriv_doc_download', 'doc_download');