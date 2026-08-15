<?php

/**
 * The template for displaying all pages
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

get_header();
aranda_de_duero_the_header_image(esc_attr(get_the_title() . ' - Cabecera'));
?>`

<div class="container">
	<div class="row">
		<div class="col-lg-4 mt-4">
			<?php get_sidebar(); ?>
		</div>
		<div class="col-lg-8 mt-4">
			<main id="primary" class="site-main">

				<?php
				while (have_posts()) :
					the_post();

					get_template_part('template-parts/content', 'page');

					// If comments are open or we have at least one comment, load up the comment template.
					if (comments_open() || get_comments_number()) :
						comments_template();
					endif;

				endwhile; // End of the loop.
				?>

			</main><!-- #main -->
		</div>

	</div>
</div>

<?php

get_footer();
