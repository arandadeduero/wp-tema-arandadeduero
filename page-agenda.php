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
					<header class="agenda-intro">
						<h1 class="entry-title"><?php the_title(); ?></h1>
						<p class="agenda-lead">
							<?php
							/**
							 * Filters the introduction shown above the agenda calendar.
							 *
							 * @param string $text Plain text.
							 */
							echo esc_html(
								apply_filters(
									'aranda_de_duero_agenda_intro',
									__( 'Los actos y actividades que se celebran en Aranda de Duero: culturales, deportivos, festivos y municipales. Consulta qué hay hoy, esta semana o este mes.', 'aranda-de-duero' )
								)
							);
							?>
						</p>
						<ul class="agenda-tips">
							<li><strong><?php esc_html_e( 'Cambia la vista', 'aranda-de-duero' ); ?></strong> <?php esc_html_e( 'a mes, semana o día, o salta a otra fecha con los selectores.', 'aranda-de-duero' ); ?></li>
							<li><strong><?php esc_html_e( 'Pulsa un evento', 'aranda-de-duero' ); ?></strong> <?php esc_html_e( 'para ver su cartel, la hora y los detalles.', 'aranda-de-duero' ); ?></li>
							<li><strong><?php esc_html_e( 'Añádelo a tu calendario', 'aranda-de-duero' ); ?></strong> <?php esc_html_e( 'con los botones iCal y Google de cada evento.', 'aranda-de-duero' ); ?></li>
						</ul>
					</header><!-- .agenda-intro -->

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
