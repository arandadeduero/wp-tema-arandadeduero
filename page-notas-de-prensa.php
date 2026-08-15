<?php

/**
 * Template Name: Página de Notas de Prensa
 *
 * This is the template that displays notas de prensa posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aranda_de_Duero
 */


// Nuevo
get_header();

$header_image = wp_get_attachment_url(get_theme_mod('aranda_de_duero_default_header_image'));
if (get_field('cabecera_de_pagina')) {
    $header_image = get_field('cabecera_de_pagina');
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
        <div class="col pt-4">
            <main id="primary" class="site-main">
                <!-- Explicación sobre Notas de Prensa -->
                <div class="alert alert-info mb-4" role="alert">
                    <div class="d-flex">
                        <div class="mr-3">
                            <i class="fas fa-question-circle" style="font-size: 2rem; color: #0c5460;"></i>
                        </div>
                        <div>
                            <h5 class="alert-heading"><?php _e('¿Qué es una Nota de Prensa?'); ?></h5>
                            <p class="mb-0">Una nota de prensa es un comunicado oficial emitido por el ayuntamiento de Aranda de Duero para informar a los medios de comunicación y al público sobre eventos, decisiones o asuntos de interés general. Su objetivo es garantizar la transparencia y mantener a la ciudadanía informada.</p>
                        </div>
                    </div>
                </div>

                <?php
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 12,
                    'paged' => $paged,
                    'orderby' => 'publish_date',
                    'order' => 'DESC',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'category',
                            'field' => 'slug',
                            'terms' => 'notas-de-prensa'
                        )
                    )
                );

                $query = new WP_Query($args);
                ?>

                <?php if ($query->have_posts()) : ?>

                    <div class="row notas-de-prensa-grid">
                        <?php $count = 0; ?>
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                                <div class="nota-de-prensa-card p-3 rounded h-100 border" style="border-color: <?php echo get_theme_mod('aranda_de_duero_main_section_text_color', '#007bff'); ?>;">
                                    <div class="nota-de-prensa-card-content d-flex flex-column justify-content-between h-100">
                                        <?php
                                        $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                                        if (!$thumbnail_url) {
                                            $thumbnail_url = 'https://www.arandadeduero.es/wp-content/uploads/2025/11/Copia-de-Banner-ayuntamiento-5-300x107.png';
                                        }
                                        ?>
                                        <div class="nota-de-prensa-card-image mb-3">
                                            <img class="img-fluid rounded" src="<?php echo $thumbnail_url; ?>" alt="<?php the_title(); ?>">
                                        </div>

                                        <div class="nota-de-prensa-card-description">
                                            <a href="<?php echo the_permalink(get_the_ID()); ?>" style="color:<?php echo get_theme_mod('aranda_de_duero_main_section_text_color'); ?>!important;">
                                                <h3 class="h6 mb-2 font-weight-bold"><?php the_title(); ?></h3>
                                            </a>
                                            <small class="text-muted d-block mb-2"><?php echo get_the_date(); ?></small>
                                        </div>

                                        <div class="nota-de-prensa-card-button mt-3">
                                            <a href="<?php echo the_permalink(get_the_ID()); ?>" class="text-blue"><span class="arrow">➔</span> <?php _e('Leer nota de prensa'); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php $count++; ?>
                        <?php endwhile; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($query->max_num_pages > 1) { ?>
                        <nav class="notas-de-prensa-pagination d-flex justify-content-between my-5">
                            <div class="prev-notas-de-prensa-link ">
                                <?php echo get_next_posts_link('← Notas de prensa anteriores', $query->max_num_pages); ?>
                            </div>
                            <div class="next-notas-de-prensa-link">
                                <?php echo get_previous_posts_link('Notas de prensa posteriores →'); ?>
                            </div>
                        </nav>
                    <?php } ?>

                    <?php wp_reset_postdata(); ?>
                <?php else: ?>
                    <div class="alert alert-warning" role="alert">
                        <p><?php _e('No hay notas de prensa disponibles en este momento.'); ?></p>
                    </div>
                <?php endif; ?>

            </main><!-- #main -->
        </div>

    </div>
</div>

<?php

get_footer();
