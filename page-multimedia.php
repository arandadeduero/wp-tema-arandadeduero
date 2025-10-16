<?php
/**
 * Template Name: Página de multimedia
 *
 * Página para mostrar la sección de multimedia
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

	<div class="container">
		<div class="row">
			<div class="col-lg-3 pt-4">
				<?php dynamic_sidebar('Actualidad');?>
			</div>
			<div class="col-lg-9">
				<main id="primary" class="site-main">

					<?php
					while ( have_posts() ) :
						the_post();

						get_template_part( 'template-parts/content', 'page-notitle' );

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile; // End of the loop.
					?>

				</main><!-- #main -->
			</div>
			<!-- <div class="col-lg-3">
				<div class="video-sidebar">
					<?php 
						// echo get_field('videos-multimedia');
						//dynamic_sidebar('Multimedia');?>
				</div>
			</div> -->
		</div>
	</div>

<?php

get_footer();
