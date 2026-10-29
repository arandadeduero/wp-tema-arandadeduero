<?php

/**
 * Template Name: Pagina de Inicio
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aranda_de_Duero
 */

get_header();
?>
<main id="primary" class="site-main">
<h1 class="screen-reader-text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
<div class="container-fluid">
	<div class="row">
		<div class="col-12 px-0">
			<div class="home-video-wrapper">
				<div class="home-video ">
					<?php
					$aranda_carousel = aranda_de_duero_home_carousel_html( aranda_de_duero_home_slides() );
					if ( '' !== $aranda_carousel ) {
						aranda_de_duero_enqueue_home_carousel();
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built by aranda_de_duero_home_carousel_html(), which escapes every value.
						echo $aranda_carousel;
					} elseif ( shortcode_exists( 'smartslider3' ) ) {
						// Until the first slide exists in «Portada», keep the Smart Slider (if the plugin is active).
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- shortcode output from Smart Slider, post-processed by a string filter that only rewrites <img> attributes.
						echo aranda_de_duero_defer_slider_images( do_shortcode( '[smartslider3 slider="2"]' ) );
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Banner de plenos  -->
<?php
if ( get_theme_mod( 'aranda_de_duero_show_banner' ) && '' !== get_theme_mod( 'aranda_de_duero_show_banner_text' ) ) :
	?>
	<div class="container">
		<div class="row my-5">
			<div class="col-12">
				<!-- Inicio banner plenos  -->
				<div id="banner_streaming" class="w-100">
					<div id="banner_left" class="d-flex">
						<div><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/webcamIcon.png" alt="" width="45" height="55"></div>
						<div><small class="banner_streaming_color font-weight-bold">En directo - <?php echo esc_html( get_theme_mod( 'aranda_de_duero_show_banner_text' ) ); ?></small><br><span class="font-weight-bold">PLENO DEL AYUNTAMIENTO DE ARANDA DE DUERO</span></div>
					</div>
					<div id="banner_right" class="d-flex align-items-center">
						<div><span class="banner-streaming-text">Síguelo a través de Youtube </span> </div>
						<div><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/playIcon.png" alt="" width="42" height="42"></div>
					</div>
				</div>
				<!-- Fin banner plenos  -->
			</div>
		</div>
	</div>
<?php endif; ?>
<!-- ¿Qué necesitas? -->
<div class="home-buscador-sentinel" aria-hidden="true"></div>
<section class="home-buscador" aria-labelledby="home-buscador-title">
	<div class="container">
		<h2 id="home-buscador-title" class="home-buscador-title"><?php esc_html_e( '¿Qué necesitas?', 'aranda-de-duero' ); ?></h2>
		<?php get_template_part( 'template-parts/search-box', null, array( 'id' => 'home-search' ) ); ?>
		<p class="search-suggestions">
			<?php esc_html_e( 'Prueba con:', 'aranda-de-duero' ); ?>
			<?php foreach ( aranda_de_duero_search_suggestions() as $aranda_term ) : ?>
				<a href="<?php echo esc_url( home_url( '/?s=' . rawurlencode( $aranda_term ) ) ); ?>"><?php echo esc_html( $aranda_term ); ?></a>
			<?php endforeach; ?>
		</p>
		<?php $aranda_explore = aranda_de_duero_explore_links(); ?>
		<?php if ( array() !== $aranda_explore ) : ?>
			<p class="search-suggestions search-explore">
				<?php esc_html_e( 'O explora:', 'aranda-de-duero' ); ?>
				<?php foreach ( $aranda_explore as $aranda_link ) : ?>
					<a href="<?php echo esc_url( $aranda_link['url'] ); ?>"><?php echo esc_html( $aranda_link['title'] ); ?></a>
				<?php endforeach; ?>
			</p>
		<?php endif; ?>
	</div>
</section>
<!-- Accesos frecuentes -->
<section class="home-accesos" aria-labelledby="home-accesos-title">
	<div class="container mt-4">
		<h2 id="home-accesos-title" class="h4 text-blue font-weight-normal text-uppercase"><?php esc_html_e( 'Accesos frecuentes', 'aranda-de-duero' ); ?></h2>
		<?php get_template_part( 'template-parts/service-cards', null, array( 'items' => aranda_de_duero_quick_access() ) ); ?>
	</div>
</section>
<!-- Servicios cotidianos -->
<section class="home-accesos home-everyday-services" aria-labelledby="home-everyday-services-title">
	<div class="container mt-4">
		<h2 id="home-everyday-services-title" class="h4 text-blue font-weight-normal text-uppercase"><?php esc_html_e( 'Servicios cotidianos', 'aranda-de-duero' ); ?></h2>
		<?php get_template_part( 'template-parts/service-cards', null, array( 'items' => aranda_de_duero_everyday_services() ) ); ?>
	</div>
</section>
<!-- Secciones principales -->
<?php
$query = aranda_de_duero_content(
	'post',
	'publish_date',
	'DESC',
	'9',
	false
);

?>
<div class="container mt-4">
	<div class="row">
		<div class="col mt-4">
			<!-- the loop -->
			<?php
			$aranda_hoy = (int) current_time( 'Ymd' );
			if ( $aranda_hoy >= 20260911 && $aranda_hoy <= 20260920 ) :
				?>
				<div class="row mb-4">
					<div class="col-12">
						<a href="https://fiestas.arandadeduero.dev/" class="d-block p-3 text-center text-white font-weight-bold text-uppercase fiestas-banner-link">
							Visita aquí el programa de las Fiestas Patronales
						</a>
					</div>
				</div>
			<?php endif; ?>
			<h2 class="h4 text-blue font-weight-normal text-uppercase">Últimas noticias</h2>
			<?php $count = 0; ?>
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				?>
				<?php
				// The first news is wide (large); the rest are cards of a quarter of the row from desktop width, half from tablet, full below.
				$image_type  = ( 0 === $count ) ? 'large' : 'aranda-tarjeta';
				$image_sizes = ( 0 === $count ) ? '(min-width: 768px) 500px, calc(100vw - 140px)' : '(min-width: 992px) 200px, (min-width: 768px) 260px, calc(100vw - 140px)';

				// Open row for first item or every 4 items after first
				if ( 0 === $count || ( $count > 0 && 0 === ( $count - 1 ) % 4 ) ) {
					echo '<div class="row">';
				}

				// Set column width
				if ( 0 === $count ) {
					$col_class = 'col-lg-12';
				} else {
					$col_class = 'col-lg-3 col-md-6 mt-4';
				}

				echo '<div class="' . esc_attr( $col_class ) . '">';
				?>
				<?php
				// The latest news goes first and wide: image on the left and text on the right from tablet width up, stacked below.
				$aranda_first    = ( 0 === $count );
				$aranda_is_bando = has_category( 'bandos' ); // A bando is something the neighbours must know: it gets its own colour and label.
				?>
				<div class="events-agenda-card p-3 h-100<?php echo $aranda_is_bando ? ' events-agenda-card--bando' : ''; ?>">
					<div class="row p-2<?php echo $aranda_first ? ' align-items-center' : ''; ?>">
						<?php
						if ( has_post_thumbnail() ) {
							?>
							<div class="<?php echo $aranda_first ? 'col-12 col-md-6' : 'col-12'; ?>">
								<div class="home-main-news-image p-1 mb-3 <?php echo ( 0 === $count ) ? 'first-news' : ''; ?>">
									<?php
									the_post_thumbnail(
										$image_type,
										array(
											'class'    => 'd-block mx-auto',
											'alt'      => '',
											'loading'  => ( 0 === $count ) ? 'eager' : 'lazy',
											'fetchpriority' => ( 0 === $count ) ? 'high' : 'auto',
											'decoding' => 'async',
											'sizes'    => $image_sizes,
										)
									);
									?>
								</div>
							</div>
							<div class="<?php echo $aranda_first ? 'col-12 col-md-6 p-3' : 'col-12 p-3'; ?>">
								<?php
								if ( $aranda_is_bando ) {
									get_template_part( 'template-parts/bando-kicker' );
								}
								?>
								<div class="home-main-news-description">
									<a href="<?php the_permalink( get_the_ID() ); ?>" class="main-section-text-link">
										<h3 class="<?php echo $aranda_first ? 'h4 mb-0' : 'h6 mb-0 font-weight-normal'; ?>"><?php the_title(); ?></h3>
									</a>
								</div>
								<?php
								if ( 0 === $count ) {
									?>
									<div class="mt-3">
										<p class="small"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt(), true ) ); ?></p>
									</div>
									<?php
								}
								?>
								<div class="home-main-news-button my-3">
									<a href="<?php the_permalink( get_the_ID() ); ?>" class="text-blue"><span class="arrow">➔</span> <?php esc_html_e( 'Leer noticia', 'aranda-de-duero' ); ?></a>
								</div>

							</div>
							<?php
						} else {
							?>
							<div class="col-12">
								<?php
								if ( $aranda_is_bando ) {
									get_template_part( 'template-parts/bando-kicker' );
								}
								?>
								<div class="home-main-news-description">
									<a href="<?php the_permalink( get_the_ID() ); ?>" class="main-section-text-link">
										<h3 class="h6 mb-0 font-weight-normal"><?php the_title(); ?></h3>
									</a>
								</div>
								<div class="home-main-news-button my-3">
									<a href="<?php the_permalink( get_the_ID() ); ?>" class="text-blue"><span class="arrow">➔</span> <?php esc_html_e( 'Leer noticia', 'aranda-de-duero' ); ?></a>
								</div>
							</div>

						<?php } ?>
					</div>
				</div>
				<?php
				echo '</div>'; // close col
				?>
				<?php
				// Close row after first item or every 4 items after first, or at end
				if ( 0 === $count || ( $count > 0 && 3 === ( $count - 1 ) % 4 ) || $query->current_post === $query->post_count - 1 ) {
					echo '</div>'; // close row
				}
				?>
				<?php ++$count; ?>
			<?php endwhile; ?>

			<!-- end of the loop -->
			<?php wp_reset_postdata(); ?>
		</div>

	</div>
	<?php $aranda_news_url = aranda_de_duero_page_url( 'noticias' ); ?>
	<?php if ( '' !== $aranda_news_url ) : ?>
	<div class="container mt-4">
		<div class="row justify-content-center">
			<div class="p-0 col-6 text-center">
				<div class="home-more-news col-8 p-0 mx-auto">
					<a href="<?php echo esc_url( trailingslashit( $aranda_news_url ) . 'page/2/' ); ?>" class="home-more-news-button">
						<p class="mb-0 py-2"><?php esc_html_e( 'Más actualidad', 'aranda-de-duero' ); ?></p>
					</a>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<!-- Fin noticias  -->

	<!-- Agenda de eventos  -->
	<?php
	// Get upcoming events for the main agenda section
	$agenda_events = aranda_de_duero_get_upcoming_events( 50 );

	if ( ! empty( $agenda_events ) ) :
		$first_event = $agenda_events[0]; // Get the first event for the date display
		$event_time  = strtotime( $first_event['date_start'] );
		?>
		<div class="container py-5">
			<div class="events-agenda-card p-4">
				<h2 class="h4 agenda-title text-blue mb-4">AGENDA DE EVENTOS</h2>

				<div class="row no-gutters bg-white shadow-sm">
					<!-- Event Poster (First Event) -->
					<div class="col-md-2 border-right p-3">
						<?php if ( ! empty( $first_event['thumbnail'] ) ) : ?>
							<a href="<?php echo esc_url( $first_event['link'] ); ?>" title="<?php echo esc_attr( $first_event['title'] ); ?>">
								<?php
								$aranda_poster_attrs = array(
									'class'    => 'img-fluid',
									'alt'      => $first_event['title'],
									'loading'  => 'lazy',
									'decoding' => 'async',
									'sizes'    => '(min-width: 768px) 190px, 100vw', // The poster is one sixth of the row from tablet width up.
								);
								// The attachment gives the tag its width, height and srcset (the poster used to be the full-size file with no dimensions: layout shift).
								$aranda_poster = ! empty( $first_event['thumbnail_id'] ) ? wp_get_attachment_image( (int) $first_event['thumbnail_id'], 'medium', false, $aranda_poster_attrs ) : '';
								if ( '' !== $aranda_poster ) {
									echo $aranda_poster; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image() escapes its attributes.
								} else {
									?>
									<img loading="lazy" decoding="async" src="<?php echo esc_url( $first_event['thumbnail'] ); ?>" class="img-fluid" alt="<?php echo esc_attr( $first_event['title'] ); ?>">
									<?php
								}
								?>
							</a>
						<?php else : ?>
							<a href="<?php echo esc_url( $first_event['link'] ); ?>" title="<?php echo esc_attr( $first_event['title'] ); ?>" class="text-decoration-none">
								<div class="bg-light d-flex align-items-center justify-content-center event-poster-placeholder">
									<span class="text-muted"><?php esc_html_e( 'Sin imagen', 'aranda-de-duero' ); ?></span>
								</div>
							</a>
						<?php endif; ?>
					</div>

					<!-- Current Date Display -->
					<div class="col-md-2 border-right p-3 d-flex flex-column align-items-center justify-content-center text-center">
						<span class="text-muted text-uppercase small"><?php echo esc_html( date_i18n( 'l' ) ); ?></span>
						<div class="display-3 font-weight-bold mb-0"><?php echo esc_html( date_i18n( 'd' ) ); ?></div>
						<span class="h5 text-uppercase"><?php echo esc_html( date_i18n( 'F' ) ); ?></span>
					</div>

					<!-- Events Grid (2 columns x 3 rows) -->
					<?php
					// Display events in a 2-column grid
					$event_index    = 0;
					$events_to_show = array_slice( $agenda_events, 0, 6 ); // Show up to 6 events

					for ( $col = 0; $col < 2; $col++ ) : // 2 columns
						?>
						<div class="col-md-4 <?php echo ( $col < 1 ) ? 'border-right' : ''; ?>">
							<?php
							// Display 3 events per column
							for ( $row = 0; $row < 3; $row++ ) :
								$event_idx = ( $col * 3 ) + $row;

								if ( ! empty( $events_to_show[ $event_idx ] ) ) :
									$event    = $events_to_show[ $event_idx ];
									$event_dt = strtotime( $event['date_start'] );
									$has_next = $row < 2 && ! empty( $events_to_show[ $event_idx + 1 ] ); // Another event below it in the same column.
									?>
									<div class="event-item p-3 <?php echo $has_next ? 'border-bottom' : ''; ?>">
										<h3 class="h6 event-name">
											<a href="<?php echo esc_url( $event['link'] ); ?>" class="text-dark text-decoration-none">
												<?php echo esc_html( $event['title'] ); ?>
											</a>
										</h3>
										<div class="event-meta small text-muted">
											<span>📅 <?php echo esc_html( date_i18n( 'd-m-Y', $event_dt ) ); ?></span>
										</div>
									</div>
									<?php
								endif;
							endfor;
							?>
						</div>
						<?php
					endfor;
					?>
				</div>
			</div>
		</div>
</div>
<?php endif; ?>
<!-- Fin agenda de eventos  -->

<!-- Featured Specials Section -->
<section class="home-especial-section py-5" aria-label="<?php esc_attr_e( 'Contenidos especiales', 'aranda-de-duero' ); ?>">
	<div class="container">
		<div class="row mb-4">
			<div class="col-12 text-center">
				<h2 class="section-title">Especiales</h2>
				<p class="text-muted mt-4">Contenidos destacados de nuestro municipio</p>
			</div>
		</div>

		<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built by aranda_de_duero_home_especiales_html(), which escapes every value.
		echo aranda_de_duero_home_especiales_html( aranda_de_duero_home_especiales() );
		?>
	</div>
</section>
</main><!-- #primary -->

<?php

get_footer();
