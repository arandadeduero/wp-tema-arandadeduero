<?php
/**
 * Template Name: Pagina de Trámites
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aranda_de_Duero
 */

get_header();
$header_image = wp_get_attachment_url( get_theme_mod('aranda_de_duero_default_header_image'));
if(get_field('cabecera_de_pagina')) {
    $header_image = get_field('cabecera_de_pagina');
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 p-0">
            <img src="<?php echo $header_image; ?>" class="img-fluid w-100 cabecera_pagina" alt="<?php echo $header_image; ?>"/>
        </div>
    </div>
</div>

    <!-- Secciones principales -->
    
    
    <!-- Fin secciones principales  -->
    <!-- Secciones principales -->
    <?php 
        $query = aranda_de_duero_content(
            'tramite',
            'ID',
            'DESC',
            '24'
        );
    ?>
    
    <!-- Fin noticias  -->
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-3 pt-4">
                <?php dynamic_sidebar('Tramites');?>
            </div>
            <div class="col-lg-9 pt-4">
                <main id="primary" class="site-main">
                    <?php if ( $query->have_posts() ) : ?> 
                        <div style="overflow-x:auto;">
                                <table id="tableTramites" class="table table-responsive-md table-striped">
                                    <thead>
                                        <transliterator_create>
                                            <th><?php _e('Fecha');?></th>
                                            <th><?php _e('Gestor');?></th>
                                            <th><?php _e('Trámite');?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                                            <tr>
                                                    <td><?php echo get_the_date();?></td>
                                                    <td><?php echo get_field('tramite_gestor');?></td>
                                                    <td><a class="text-dark" href="<?php the_permalink();?>"><?php the_title();?></a></td>
                                            </tr>
                                        <?php endwhile; ?>
                                 </tbody>
                                </table>         
                        </div>              
                    <?php endif;?>
                </main><!-- #main -->
            </div>
        </div>
    </div>
<?php

get_footer();
