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

    <!-- Security and SEO Headers -->
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="zoom-domain-verification" content="ZOOM_verify_KMhEiObFczwnG46wHdMKHq">

    <link rel="profile" href="https://gmpg.org/xfn/11">

    <!-- Font Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">

    <!-- Google Fonts with display=swap for better performance -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <?php wp_body_open(); ?>
    <div id="page" class="site">
        <div class="container">
            <div class="row">
                <div class="col-12 d-md-none text-right">
                    <div class="logo-mobile col-7 offset-3">
                        <?php the_custom_logo(); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'aranda-de-duero'); ?></a>

                    <header id="masthead" class="site-header justify-content-between mt-2">
                        <div class="site-branding order-2 order-lg-1">
                            <?php
                            the_custom_logo();
                            if (is_front_page()) :
                            ?>
                                <h1 class="site-title"><?php bloginfo('name'); ?></h1>
                            <?php
                            else :
                            ?>
                                <p class="site-title"><?php bloginfo('name'); ?></p>
                            <?php
                            endif;

                            $site_description = get_bloginfo('description', 'display');
                            if ($site_description || is_customize_preview()) :
                            ?>
                                <p class="site-description"><?php echo esc_html($site_description); ?></p>
                            <?php endif; ?>
                        </div><!-- .site-branding -->
                        <div class="header-left order-1 order-lg-2 align-self-stretch">
                            <div class="menu-top">
                                <?php
                                wp_nav_menu(
                                    array(
                                        'theme_location' => 'top',
                                        'menu_id'        => 'secondary-menu',
                                        'fallback_cb'    => false, // Don't show fallback menu
                                    )
                                );
                                ?>
                            </div>
                            <nav id="site-navigation" class="main-navigation mt-4" aria-label="<?php esc_attr_e('Primary Menu', 'aranda-de-duero'); ?>">
                                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation menu', 'aranda-de-duero'); ?>"><i class="fas fa-bars" aria-hidden="true"></i></button>
                                <?php
                                wp_nav_menu(
                                    array(
                                        'theme_location' => 'menu-1',
                                        'menu_id'        => 'primary-menu',
                                        'fallback_cb'    => false, // Don't show fallback menu
                                    )
                                );
                                ?>
                            </nav><!-- #site-navigation -->
                        </div>
                    </header><!-- #masthead -->
                </div>
            </div>
        </div>
        <div id="content" class="site-content">