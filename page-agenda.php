<?php
/**
 * Template Name: Plantilla Agenda
 *
 * Página para mostrar la sección Agenda
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
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
				?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php
					get_template_part(
						'template-parts/page-intro',
						null,
						array(
							/**
							 * 'lead' goes through the `aranda_de_duero_agenda_intro` filter (plain text).
							 */
							'lead' => array(
								apply_filters(
									'aranda_de_duero_agenda_intro',
									__( 'Los actos y actividades que se celebran en Aranda de Duero: culturales, deportivos, festivos y municipales. Consulta qué hay hoy, esta semana o este mes.', 'aranda-de-duero' )
								),
							),
							'tips' => array(
								array( __( 'Cambia la vista', 'aranda-de-duero' ), __( 'a mes, semana o día, o salta a otra fecha con los selectores.', 'aranda-de-duero' ) ),
								array( __( 'Pulsa un evento', 'aranda-de-duero' ), __( 'para ver su cartel, la hora y los detalles.', 'aranda-de-duero' ) ),
								array( __( 'Añádelo a tu calendario', 'aranda-de-duero' ), __( 'con los botones iCal y Google de cada evento.', 'aranda-de-duero' ) ),
							),
						)
					);
					?>

					<div class="entry-content">
						<?php the_content(); ?>
					</div><!-- .entry-content -->

					<?php if ( get_edit_post_link() ) : ?>
						<footer class="entry-footer">
							<?php edit_post_link( esc_html__( 'Edit', 'aranda-de-duero' ), '<span class="edit-link">', '</span>' ); ?>
						</footer><!-- .entry-footer -->
					<?php endif; ?>
				</article><!-- #post-<?php the_ID(); ?> -->

				<?php
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div>

<?php

get_footer();
