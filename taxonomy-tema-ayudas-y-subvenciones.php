<?php
/**
 * 
 *
 * Página para mostrar las Ayudas y subvenciones
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aranda_de_Duero
 */

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
				<?php dynamic_sidebar('Tramites');?>
			</div>
			<div class="col-lg-9 pt-4">
            <?php
                $query = aranda_de_duero_temas('ayudas-y-subvenciones');
            ?>
				<main id="primary" class="site-main">

	
						<table class="table table-responsive-md table-striped">
                                    <thead>
                                        <transliterator_create>
                                            <th><?php _e('Fecha');?></th>
                                            <th><?php _e('Descripción');?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                                            <tr>
                                                    <td><?php echo get_the_date()?></td>
                                                    <td><a class="text-dark" href="<?php the_permalink();?>"><?php the_title();?></a></td>
                                            </tr>
                                        <?php endwhile; ?>
                                 </tbody>
                                </table>    
                                <?php the_posts_navigation();?>


				</main><!-- #main -->
			</div>
			
		</div>
	</div>

<?php

get_footer();
