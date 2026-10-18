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

<div class="container py-4">
	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );

			the_post_navigation(
				array(
					'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Noticia anterior', 'aranda-de-duero' ) . '</span> <span class="nav-title">%title</span>',
					'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Noticia posterior', 'aranda-de-duero' ) . '</span> <span class="nav-title">%title</span>',
				)
			);

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->
</div>

<?php
get_footer();
