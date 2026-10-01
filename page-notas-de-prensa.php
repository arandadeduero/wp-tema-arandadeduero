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
				<!-- Explicación sobre Notas de Prensa -->
				<div class="alert alert-info mb-4" role="alert">
					<div class="d-flex">
						<div class="mr-3" style="color: #0c5460;">
							<?php
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
							echo aranda_de_duero_icon( 'aranda-de-duero/circle-question', array( 'size' => 32 ) );
							?>
						</div>
						<div>
							<h5 class="alert-heading"><?php esc_html_e( '¿Qué es una Nota de Prensa?', 'aranda-de-duero' ); ?></h5>
							<p class="mb-0">Una nota de prensa es un comunicado oficial emitido por el ayuntamiento de Aranda de Duero para informar a los medios de comunicación y al público sobre eventos, decisiones o asuntos de interés general. Su objetivo es garantizar la transparencia y mantener a la ciudadanía informada.</p>
						</div>
					</div>
				</div>

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
								<div class="nota-de-prensa-card p-3 rounded h-100 border" style="border-color: <?php echo esc_attr( get_theme_mod( 'aranda_de_duero_main_section_text_color', '#007bff' ) ); ?>;">
									<div class="nota-de-prensa-card-content d-flex flex-column justify-content-between h-100">
										<div class="nota-de-prensa-card-image mb-3">
											<?php if ( has_post_thumbnail() ) : ?>
												<?php
												the_post_thumbnail(
													'medium',
													array(
														'class' => 'img-fluid rounded',
														'alt' => get_the_title(),
														'loading' => 'lazy',
														'decoding' => 'async',
													)
												);
												?>
											<?php else : ?>
												<img loading="lazy" decoding="async" class="img-fluid rounded" src="https://www.arandadeduero.es/wp-content/uploads/2025/11/Copia-de-Banner-ayuntamiento-5-300x107.png" alt="<?php the_title(); ?>">
											<?php endif; ?>
										</div>

										<div class="nota-de-prensa-card-description">
											<a href="<?php the_permalink( get_the_ID() ); ?>" style="color:<?php echo esc_attr( get_theme_mod( 'aranda_de_duero_main_section_text_color' ) ); ?>!important;">
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
