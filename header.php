<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Aranda_de_Duero
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="description" content="<?php echo esc_attr(get_bloginfo('description')); ?>">
    <meta name="theme-color" content="#0083c1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="zoom-domain-verification" content="ZOOM_verify_KMhEiObFczwnG46wHdMKHq">

    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'aranda-de-duero'); ?></a>

    <div id="page" class="site">
        <div class="container">
            <!-- Mobile Logo -->
            <div class="row d-md-none">
                <div class="col-12 text-center py-3">
                    <?php the_custom_logo(); ?>
                </div>
            </div>

            <!-- Main Header -->
            <div class="row">
                <div class="col-12">
                    <header id="masthead" class="site-header d-flex justify-content-between mt-2">
                        <!-- Site Branding -->
                        <div class="site-branding order-2 order-lg-1 d-none d-md-block">
                            <?php
                            the_custom_logo();
                            $site_name = get_bloginfo('name');
                            $site_description = get_bloginfo('description', 'display');

                            if (is_front_page() && is_home()) : ?>
                                <h1 class="site-title"><?php echo esc_html($site_name); ?></h1>
                            <?php else : ?>
                                <p class="site-title">
                                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                        <?php echo esc_html($site_name); ?>
                                    </a>
                                </p>
                            <?php endif;

                            if ($site_description || is_customize_preview()) : ?>
                                <p class="site-description"><?php echo esc_html($site_description); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Navigation -->
                        <div class="header-nav order-1 order-lg-2 align-self-stretch">
                            <?php if (has_nav_menu('top')) : ?>
                                <nav class="menu-top" aria-label="<?php esc_attr_e('Secondary Menu', 'aranda-de-duero'); ?>">
                                    <?php
                                    wp_nav_menu(array(
                                        'theme_location' => 'top',
                                        'menu_id'        => 'secondary-menu',
                                        'container'      => false,
                                        'fallback_cb'    => false,
                                    ));
                                    ?>
                                </nav>
                            <?php endif; ?>

                            <nav id="site-navigation" class="main-navigation mt-4" aria-label="<?php esc_attr_e('Primary Menu', 'aranda-de-duero'); ?>">
                                <button class="menu-toggle"
                                    aria-controls="primary-menu"
                                    aria-expanded="false"
                                    aria-label="<?php esc_attr_e('Toggle navigation menu', 'aranda-de-duero'); ?>">
                                    <i class="fas fa-bars" aria-hidden="true"></i>
                                    <span class="screen-reader-text"><?php esc_html_e('Menu', 'aranda-de-duero'); ?></span>
                                </button>
                                <?php
                                wp_nav_menu(array(
                                    'theme_location' => 'menu-1',
                                    'menu_id'        => 'primary-menu',
                                    'container'      => false,
                                    'fallback_cb'    => false,
                                ));
                                ?>
                            </nav>
                        </div>
                    </header>
                </div>
            </div>
        </div>

        <div id="content" class="site-content">