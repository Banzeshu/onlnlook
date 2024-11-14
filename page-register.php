<?php
/*
Template Name: Register
*/

get_header();

$register_success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_user($_POST['user_login']);
    $email = sanitize_email($_POST['user_email']);
    $password = $_POST['password'];

    $errors = [];

    if (empty($username) || empty($email) || empty($password)) {
        $errors[] = '请填写所有字段。';
    } elseif (!is_email($email)) {
        $errors[] = '邮箱格式不正确。';
    } elseif (username_exists($username) || email_exists($email)) {
        $errors[] = '用户名或邮箱已存在。';
    }

    if (empty($errors)) {
        $user_id = wp_create_user($username, $password, $email);
        if (!is_wp_error($user_id)) {
            $register_success = true;
        } else {
            $errors[] = '注册失败。请重试。';
        }
    }
}


?>

<main>

    <div class="login-card">
        <div class="card" style="width: 70rem;">
            <div class="row">
                <div class="col-sm">
                    <img src="<?=get_template_directory_uri()?>/images/regist-left.png" width="100%"/>
                </div>
                <div class="col-sm" style="padding:2rem 2rem 2rem 2rem;">
                    <h1>注册账号</h1>
                    <?php if (!empty($errors)) : ?>
                        <?php foreach ($errors as $error) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo esc_html($error); ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if ($register_success) : ?>
                        <div class="alert alert-success" role="alert">
                            注册成功, <a href="<?=home_url('login')?>">点击登录账号</a>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post">
                        <div class="form-group">
                            <label for="user_login">用户名</label>
                            <input class="form-control form-control-lg" name="user_login" id="user_login" aria-describedby="用户名">
                        </div>
                        <div class="form-group">
                            <label for="user_email">邮箱</label>
                            <input type="email" class="form-control form-control-lg" name="user_email" id="user_email" aria-describedby="邮箱">
                        </div>
                        <div class="form-group">
                            <label for="password">密码</label>
                            <input type="password" class="form-control form-control-lg" id="password" name="password">
                        </div>
                        <div class="form-group">
                            <label for="password2">确认密码</label>
                            <input type="password" class="form-control form-control-lg" id="password2" name="password2">
                        </div>
                        <div class="form-group">
                            已有账号? <a href="<?=home_url('login')?>">点击登录账号</a>
                        </div>
                        <button type="submit" class="btn btn-primary">注册</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--
    <div class="row">
        <div class="col-md-4 login-left">
            <div class="container">
                <h1><?=get_bloginfo('name')?></h1>
                <p><?=get_bloginfo('description')?></p>
            </div>
        </div>
        <div class="col-md-8 login-form">
            <div class="container">
                <h3>注册账号</h3>
                <?php if (!empty($errors)) : ?>
                    <?php foreach ($errors as $error) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo esc_html($error); ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if ($register_success) : ?>
                    <div class="alert alert-success" role="alert">
                        注册成功, <a href="<?=home_url('login')?>">点击登录账号</a>
                    </div>
                <?php endif; ?>
                
                <form method="post">
                    <div class="form-group">
                        <label for="user_login">用户名</label>
                        <input class="form-control" name="user_login" id="user_login" aria-describedby="用户名">
                    </div>
                    <div class="form-group">
                        <label for="user_email">邮箱</label>
                        <input type="email" class="form-control" name="user_email" id="user_email" aria-describedby="邮箱">
                    </div>
                    <div class="form-group">
                        <label for="password">密码</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="form-group">
                        <label for="password2">确认密码</label>
                        <input type="password" class="form-control" id="password2" name="password2">
                    </div>
                    <div class="form-group">
                        已有账号? <a href="<?=home_url('login')?>">点击登录账号</a>
                    </div>
                    <button type="submit" class="btn btn-primary">注册</button>
                </form>
            </div>
        </div>
    </div>
    -->

</main>

<?php get_footer(); ?>