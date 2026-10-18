<?php

/**
 * Template Name: Página de Notas de Prensa
 *
 * This is the template that displays notas de prensa posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aranda_de_Duero
 */


// Nuevo
get_header();

aranda_de_duero_the_header_image( '' );
?>
<div class="container mt-4">
	<div class="row">
		<div class="col pt-4">
			<main id="primary" class="site-main">
				<?php
				get_template_part(
					'template-parts/page-intro',
					null,
					array(
						'heading' => __( '¿Qué es una Nota de Prensa?', 'aranda-de-duero' ),
						'lead'    => array(
							__( 'Una nota de prensa es un comunicado oficial emitido por el ayuntamiento de Aranda de Duero para informar a los medios de comunicación y al público sobre eventos, decisiones o asuntos de interés general. Su objetivo es garantizar la transparencia y mantener a la ciudadanía informada.', 'aranda-de-duero' ),
						),
						'tips'    => array(
							array( __( 'Lee la nota completa', 'aranda-de-duero' ), __( 'pulsando su tarjeta o el enlace «Leer nota de prensa».', 'aranda-de-duero' ) ),
							array( __( 'Las más recientes, primero', 'aranda-de-duero' ), __( 'las notas están ordenadas por su fecha de publicación.', 'aranda-de-duero' ) ),
							array( __( 'Busca las anteriores', 'aranda-de-duero' ), __( 'con los enlaces del final de la lista, que muestra 12 notas por página.', 'aranda-de-duero' ) ),
						),
					)
				);
				?>

				<?php
				$current_page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				$args         = array(
					'post_type'      => 'post',
					'posts_per_page' => 12,
					'paged'          => $current_page,
					'orderby'        => 'publish_date',
					'order'          => 'DESC',
					'tax_query'      => array(
						array(
							'taxonomy' => 'category',
							'field'    => 'slug',
							'terms'    => 'notas-de-prensa',
						),
					),
				);

				$query = new WP_Query( $args );
				?>

				<?php if ( $query->have_posts() ) : ?>

					<div class="row notas-de-prensa-grid">
						<?php $count = 0; ?>
						<?php
						while ( $query->have_posts() ) :
							$query->the_post();
							?>
							<div class="col-lg-3 col-md-6 col-sm-12 mb-4">
								<div class="nota-de-prensa-card p-3 rounded h-100 border">
									<div class="nota-de-prensa-card-content d-flex flex-column justify-content-between h-100">
										<div class="nota-de-prensa-card-image mb-3">
											<?php if ( has_post_thumbnail() ) : ?>
												<?php
												the_post_thumbnail(
													'medium',
													array(
														'class' => 'img-fluid rounded',
														'alt' => '',
														'loading' => 'lazy',
														'decoding' => 'async',
													)
												);
												?>
											<?php else : ?>
												<img loading="lazy" decoding="async" class="img-fluid rounded" src="<?php echo esc_url( content_url( '/uploads/2025/11/Copia-de-Banner-ayuntamiento-5-300x107.png' ) ); ?>" alt="">
											<?php endif; ?>
										</div>

										<div class="nota-de-prensa-card-description">
											<a href="<?php the_permalink( get_the_ID() ); ?>" class="main-section-text-link">
												<h3 class="h6 mb-2 font-weight-bold"><?php the_title(); ?></h3>
											</a>
											<small class="text-muted d-block mb-2"><?php echo get_the_date(); ?></small>
										</div>

										<div class="nota-de-prensa-card-button mt-3">
											<a href="<?php the_permalink( get_the_ID() ); ?>" class="text-blue"><span class="arrow">➔</span> <?php esc_html_e( 'Leer nota de prensa', 'aranda-de-duero' ); ?></a>
										</div>
									</div>
								</div>
							</div>
							<?php ++$count; ?>
						<?php endwhile; ?>
					</div>

					<!-- Pagination -->
					<?php if ( $query->max_num_pages > 1 ) { ?>
						<nav class="notas-de-prensa-pagination d-flex justify-content-between my-5">
							<div class="prev-notas-de-prensa-link ">
								<?php
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_next_posts_link() returns WP core's own safely-built HTML.
								echo get_next_posts_link( '← Notas de prensa anteriores', $query->max_num_pages );
								?>
							</div>
							<div class="next-notas-de-prensa-link">
								<?php
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_previous_posts_link() returns WP core's own safely-built HTML.
								echo get_previous_posts_link( 'Notas de prensa posteriores →' );
								?>
							</div>
						</nav>
					<?php } ?>

					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<div class="alert alert-warning" role="alert">
						<p><?php esc_html_e( 'No hay notas de prensa disponibles en este momento.', 'aranda-de-duero' ); ?></p>
					</div>
				<?php endif; ?>

			</main><!-- #main -->
		</div>

	</div>
</div>

<?php

get_footer();
