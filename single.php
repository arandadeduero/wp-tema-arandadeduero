<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Aranda_de_Duero
 */

get_header();
?>

<div class="container">
    <div class="row">
        <div class="col-lg-3 pt-4">
            <?php dynamic_sidebar('sidebar-actualidad'); ?>
        </div>
        <div class="col-lg-9 pt-4">
            <main id="primary" class="site-main">

                <?php
                while (have_posts()) :
                    the_post();

                    get_template_part('template-parts/content', get_post_type());

                    the_post_navigation(
                        array(
                            'prev_text' => '<span class="nav-subtitle">' . esc_html__('', 'aranda-de-duero') . '</span> <span class="nav-title btn-news px-3 py-2">' . esc_html__('Noticia anterior', 'aranda-de-duero') . '</span>',
                            'next_text' => '<span class="nav-subtitle">' . esc_html__('', 'aranda-de-duero') . '</span> <span class="nav-title btn-news px-3 py-2">' . esc_html__('Noticia posterior', 'aranda-de-duero') . '</span>',
                        )
                    );



                endwhile; // End of the loop.
                ?>

            </main><!-- #main -->
        </div>
    </div>
</div>

<?php
get_footer();
