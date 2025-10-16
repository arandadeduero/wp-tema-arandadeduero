<?php
/**
 * The template for displaying all single tramites
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
		<?php dynamic_sidebar('Tramites');?>
			</div>
			<div class="col-lg-9 pt-4">
				<main id="primary" class="site-main">

					<?php
					while ( have_posts() ) :
						the_post();

						get_template_part( 'template-parts/content', get_post_type() );
						

					endwhile; // End of the loop.
					?>

				</main><!-- #main -->
			</div>
		</div>
	</div>

<?php
// get_sidebar();
get_footer();
