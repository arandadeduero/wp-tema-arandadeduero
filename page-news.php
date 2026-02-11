<?php

/**
 * Template Name: Página de Actualidad
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aranda_de_Duero
 */


// Nuevo
get_header();

$header_image = wp_get_attachment_url(get_theme_mod('aranda_de_duero_default_header_image'));
if (get_field('cabecera_de_pagina', $term)) {
    $header_image = get_field('cabecera_de_pagina', $term);
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 p-0">
            <img src="<?php echo $header_image; ?>" class="img-fluid w-100 cabecera_pagina" alt="<?php echo $header_image; ?>" />
        </div>
    </div>
</div>
<div class="container mt-4">
    <div class="row">
        <div class="col-lg-9 pt-4">
            <main id="primary" class="site-main">
                <?php
                $query = aranda_de_duero_content(
                    'post',
                    'publish_date',
                    'DESC',
                    '9'
                );

                ?>

                <?php if ($query->have_posts()) : ?>

                    <!-- the loop -->
                    <?php $count = 0; ?>
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <?php
                        if ($count % 2 == 0) {
                            echo '<div class="row">';
                        }
                        ?>
                        <div class="col-sm-12 col-lg-6 mt-4">
                            <div class="home-main-news p-3 rounded h-100">
                                <div class="row">
                                    <?php
                                    if (get_the_post_thumbnail_url(get_the_ID(), 'large')) {
                                    ?>
                                        <div class="col-12">
                                            <div class="home-main-news-image mb-3">
                                                <img class="img-fluid" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>" alt="<?php the_title(); ?>">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="home-main-news-content px-2 d-flex flex-column justify-content-between">
                                                <div class="home-main-news-description">
                                                    <a href="<?php echo the_permalink(get_the_ID()); ?>" style="color:<?php echo get_theme_mod('aranda_de_duero_main_section_text_color'); ?>!important;">
                                                        <h2 class="h6 mb-0 font-weight-bold"><?php the_title(); ?></h2>
                                                    </a>
                                                    <p class="small mt-3 w-100 pr-2"><?php echo wp_strip_all_tags(get_the_excerpt(), true); ?></p>
                                                </div>
                                                <div class="home-main-news-button my-3">
                                                    <a href="<?php echo the_permalink(get_the_ID()); ?>" class="btn btn-primary" role="button" style="color:white !important;"><?php _e('Ver más'); ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    } else {
                                    ?>
                                        <div class="col-12">
                                            <div class="home-main-news-content px-2 d-flex flex-column justify-content-between">
                                                <div class="home-main-news-description">
                                                    <a href="<?php echo the_permalink(get_the_ID()); ?>" style="color:<?php echo get_theme_mod('aranda_de_duero_main_section_text_color'); ?>!important;">
                                                        <h2 class="h5 mb-0 font-weight-bold"><?php the_title(); ?></h2>
                                                    </a>
                                                </div>
                                                <div class="home-main-news-button my-3">
                                                    <a href="<?php echo the_permalink(get_the_ID()); ?>" class="btn btn-primary" role="button" style="color:white !important;"><?php _e('Ver más'); ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php if ($count % 2 == 1) {
                            echo '</div>';
                        } ?>
                        <?php $count++; ?>
                    <?php endwhile; ?>
                    <?php if ($count % 2 != 0) {
                        echo '</div>';
                    } ?>

                    <!-- end of the loop -->


                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>
                <?php if ($query->max_num_pages  > 1) { // check if the max number of pages is greater than 1  
                ?>
                    <nav class="prev-next-posts d-flex justify-content-between my-5">
                        <div class="prev-posts-link ">
                            <?php echo get_next_posts_link('Noticias anteriores', $query->max_num_pages); // display older posts link 
                            ?>
                        </div>
                        <div class="next-posts-link">
                            <?php echo get_previous_posts_link('Noticias posteriores'); // display newer posts link 
                            ?>
                        </div>
                    </nav>
                <?php } ?>

            </main><!-- #main -->
        </div>

    </div>
</div>

<?php

get_footer();
