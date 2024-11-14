<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title('|', true, 'right'); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?=home_url('/')?>" style="display:inline-flex;">
                    <img src="<?=get_site_icon_url()?>" width="30" height="30" alt="<?=get_bloginfo('name')?>">
                    <span class="header-nav-title"><?=get_bloginfo('name')?></span>
                </a>

                
                <div class="header-search-sm">
                    <form action="<?=home_url('/')?>">
                        <input class="form-control mr-sm-2" type="search" style="border-radius:0.5rem;width:10rem;" name="s" placeholder="搜索" aria-label="Search">
                    </form>
                </div>
                
                <div class="header-login-logo justify-content-end">
                    <a type="button" class="btn btn-primary" name="header-search-logo">
                        <img src="<?=get_template_directory_uri()?>/images/search.png" alt="Icon" width="24" height="24">
                    </a>
                    <a type="button" href="<?=esc_url(site_url('login'))?>" class="btn btn-primary">
                        <img src="<?=get_template_directory_uri()?>/images/me.png" alt="Icon" width="24" height="24">
                    </a>
                    <button class="navbar-toggler justify-content-end" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <ul class="navbar-nav" style="margin-right:2rem;">
                        <li class="nav-item">
                            <a class="nav-link" style="color:white;" href="<?=home_url('/')?>">首页</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" style="color:white;" href="<?=home_url('/brand/');?>">品牌</a>
                        </li>
                    </ul>
                </div>
                
                <div class="header-login-btn">
                    <?php if(!is_front_page()): ?>
                        <form class="form-inline my-2 my-lg-0" style="margin-right:2rem;" action="<?=home_url('/')?>">
                            <input class="form-control mr-sm-2" type="search" style="border-radius:0.5rem;width:20rem;" name="s" placeholder="搜索" aria-label="Search">
                        </form>
                    <?php endif; ?>
                </div>
                <div class="header-login-btn">
                    <a type="button" href="<?=esc_url(site_url('login'))?>" class="btn btn-primary" style="border: 1px solid white;padding:0.2rem 2rem 0.2rem 2rem;">登录</a>
                </div>
            </div>
            
        </nav>

        <div class="header-alert-box">
        </div>
    </header>