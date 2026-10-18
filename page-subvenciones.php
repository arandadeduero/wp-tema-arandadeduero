<?php

/**
 * Template Name: Página de Subvenciones
 *
 * This is the template that displays subvenciones posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aranda_de_Duero
 */


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
						'heading' => __( 'Abre Aranda: Ayudas y oportunidades para tu negocio', 'aranda-de-duero' ),
						'lead'    => array(
							__( 'El Ayuntamiento de Aranda de Duero pone a disposición de autónomos, emprendedores, comercios y empresas este espacio de información sobre subvenciones, ayudas públicas y programas de apoyo al desarrollo económico.', 'aranda-de-duero' ),
							__( 'Nuestro objetivo es facilitar el acceso a las diferentes convocatorias disponibles, acercando la información de manera clara y sencilla para contribuir a la creación, consolidación y modernización de la actividad económica local.', 'aranda-de-duero' ),
						),
						'tips'    => array(
							array( __( 'Abre una convocatoria', 'aranda-de-duero' ), __( 'pulsando su tarjeta o el enlace «Ver subvención».', 'aranda-de-duero' ) ),
							array( __( 'Las más recientes, primero', 'aranda-de-duero' ), __( 'las subvenciones están ordenadas por su fecha de publicación.', 'aranda-de-duero' ) ),
							array( __( 'Busca las anteriores', 'aranda-de-duero' ), __( 'con los enlaces del final de la lista, que muestra 12 subvenciones por página.', 'aranda-de-duero' ) ),
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
							'terms'    => 'subvenciones',
						),
					),
				);

				$query = new WP_Query( $args );
				?>

				<?php if ( $query->have_posts() ) : ?>

					<div class="row subvenciones-grid">
						<?php $count = 0; ?>
						<?php
						while ( $query->have_posts() ) :
							$query->the_post();
							?>
							<div class="col-lg-3 col-md-6 col-sm-12 mb-4">
								<div class="subvencion-card p-3 rounded h-100 border">
									<div class="subvencion-card-content d-flex flex-column justify-content-between h-100">
										<div class="subvencion-card-image mb-3">
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

										<div class="subvencion-card-description">
											<a href="<?php the_permalink( get_the_ID() ); ?>" class="main-section-text-link">
												<h3 class="h6 mb-2 font-weight-bold"><?php the_title(); ?></h3>
											</a>
											<small class="text-muted d-block mb-2"><?php echo get_the_date(); ?></small>
										</div>

										<div class="subvencion-card-button mt-3">
											<a href="<?php the_permalink( get_the_ID() ); ?>" class="text-blue"><span class="arrow">&#10132;</span> <?php esc_html_e( 'Ver subvención', 'aranda-de-duero' ); ?></a>
										</div>
									</div>
								</div>
							</div>
							<?php ++$count; ?>
						<?php endwhile; ?>
					</div>

					<!-- Pagination -->
					<?php if ( $query->max_num_pages > 1 ) { ?>
						<nav class="subvenciones-pagination d-flex justify-content-between my-5">
							<div class="prev-subvenciones-link ">
								<?php
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_next_posts_link() returns WP core's own safely-built HTML.
								echo get_next_posts_link( '&larr; Subvenciones anteriores', $query->max_num_pages );
								?>
							</div>
							<div class="next-subvenciones-link">
								<?php
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_previous_posts_link() returns WP core's own safely-built HTML.
								echo get_previous_posts_link( 'Subvenciones posteriores &rarr;' );
								?>
							</div>
						</nav>
					<?php } ?>

					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<div class="alert alert-warning" role="alert">
						<p><?php esc_html_e( 'No hay subvenciones disponibles en este momento.', 'aranda-de-duero' ); ?></p>
					</div>
				<?php endif; ?>

			</main><!-- #main -->
		</div>

	</div>
</div>

<?php

get_footer();
