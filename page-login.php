<?php
/*
Template Name: Login
*/
if (is_user_logged_in()) {
    wp_redirect(home_url(('my-account')));
} 

get_header(); ?>


<main>

    <div class="login-card">
        <div class="card" style="width: 70rem;">
            <div class="row">
                <div class="col-sm">
                    <img src="<?=get_template_directory_uri()?>/images/login-left.png" width="100%"/>
                </div>
                <div class="col-sm" style="padding:4rem 2rem 2rem 2rem;">
                    <h1>登录账号</h1>
                    <form method="post" action="<?php echo esc_url(site_url('wp-login.php', 'login_post')); ?>">
                        <div class="form-group">
                            <label for="exampleInputEmail1">用户名或邮箱</label>
                            <input class="form-control form-control-lg" name="log" id="exampleInputEmail1" aria-describedby="用户名或邮箱">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">密码</label>
                            <input type="password" class="form-control form-control-lg" id="exampleInputPassword1" name="pwd">
                        </div>
                        <div class="form-group">
                            没有账号? <a href="<?=home_url('register')?>">点击注册账号</a>
                        </div>
                        <button type="submit" class="btn btn-primary">登录</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- 
    <div class="row">
        <div class="col-md-6 login-left">
            <div class="container">
                <h1><?=get_bloginfo('name')?></h1>
                <p><?=get_bloginfo('description')?></p>
            </div>
        </div>
        
        <div class="col-md-6 login-form">
            <div class="container">
                <h3>登录账号</h3>
                <form method="post" action="<?php echo esc_url(site_url('wp-login.php', 'login_post')); ?>">
                    <div class="form-group">
                        <label for="exampleInputEmail1">用户名或邮箱</label>
                        <input class="form-control" name="log" id="exampleInputEmail1" aria-describedby="用户名或邮箱">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">密码</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" name="pwd">
                    </div>
                    <div class="form-group">
                        没有账号? <a href="<?=home_url('register')?>">点击注册账号</a>
                    </div>
                    <button type="submit" class="btn btn-primary">登录</button>
                </form>
            </div>
        </div>
    </div>
-->

</main>


<?php get_footer(); ?>