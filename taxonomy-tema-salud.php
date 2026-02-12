<?php
/**
 * Template Name: Página de Salud
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
$term = get_queried_object();
$header_image = wp_get_attachment_url( get_theme_mod('aranda_de_duero_default_header_image'));
if(get_field('cabecera_de_pagina', $term)) {
    $header_image = get_field('cabecera_de_pagina', $term);
}
?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 p-0">
                <img src="<?php echo $header_image; ?>" class="img-fluid w-100 cabecera_pagina" alt="<?php echo $header_image; ?>"/>
            </div>
        </div>
    </div>


    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-3 pt-4">
                <?php dynamic_sidebar('Servicios');?>
            </div>
            <div class="col-lg-9 pt-4">
                <main id="primary" class="site-main">
                <?php 
                    $query = aranda_de_duero_temas('salud');
        
                ?>
                    
                    <?php if ( $query->have_posts() ) : ?> 
                
                    
                <!-- the loop -->
                <?php $count = 0; ?>
                    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                      <div class="col-12">
                        <div class="noticia-listada d-flex mb-4">
                                <?php 
                                    if(get_the_post_thumbnail_url(get_the_ID(),'medium')!= "") :
                                ?>
                                    <div class="noticia-listada-imagen mr-3">
                                        <img class="img-thumbnail" title="<?php the_title();?>" alt="<?php the_title();?>" src="<?php echo get_the_post_thumbnail_url(get_the_ID(),'medium');?>">
                                     </div>   
                                <?php
                                    endif;
                                ?>
                            <div class="noticia-listada-texto">
                                <h2 class="h4"><a class="text-blue" href="<?php echo the_permalink(get_the_ID());?>"><?php the_title();?></a></h2>
                                <p><?php echo wp_strip_all_tags( get_the_excerpt(), true ); ?></p>
                            </div>
                        </div>
                      </div>
                    <?php endwhile; ?>
                   
                <!-- end of the loop -->
               
        
        <?php wp_reset_postdata(); ?>
    <?php endif;?>
    <?php if ($query->max_num_pages  > 1) { // check if the max number of pages is greater than 1  ?>
                    <nav class="prev-next-posts d-flex justify-content-between my-3">
                        <div class="prev-posts-link ">
                            <?php echo get_next_posts_link( 'Noticias anteriores', $query->max_num_pages ); // display older posts link ?>
                        </div>
                        <div class="next-posts-link">
                            <?php echo get_previous_posts_link( 'Noticias posteriores' ); // display newer posts link ?>
                        </div>
                    </nav>
                <?php } ?>
                    
                </main><!-- #main -->
            </div>
            
        </div>
    </div>

<?php

get_footer();
