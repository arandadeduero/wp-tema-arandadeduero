<?php
/**
 * The template for displaying a single My Calendar event.
 *
 * Full width (no sidebar); the event card itself comes from My Calendar through the_content and is laid
 * out by the `.single-mc-events` rules in css/aranda-de-duero.css.
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
						'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Evento anterior', 'aranda-de-duero' ) . '</span> <span class="nav-title">%title</span>',
						'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Evento siguiente', 'aranda-de-duero' ) . '</span> <span class="nav-title">%title</span>',
					)
				);

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div>

<?php
get_footer();
