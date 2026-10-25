<?php
/**
 * The template for displaying a single «servicio-municipal» (a service of a concejalía: Biblioteca, Escuela de música…).
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Aranda_de_Duero
 */

get_header();
aranda_de_duero_the_header_image( '' );
?>
	<div class="container mt-4">
		<main id="primary" class="site-main">

			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', get_post_type() );

			endwhile;
			?>

		</main><!-- #primary -->
	</div>

<?php
get_footer();
