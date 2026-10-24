<?php
/**
 * Template Name: Plantilla Farmacias de guardia
 *
 * The pharmacy on duty today, the next days and the twelve pharmacies of the town. The data are in data/farmacias/ (see
 * inc/pharmacies.php); a new year is a new file there.
 *
 * Create a page with the slug `farmacia-de-guardia` (the card of the home page looks for it) and this template.
 *
 * @package Aranda_de_Duero
 */

get_header();
aranda_de_duero_the_header_image( '' );

$aranda_today    = aranda_de_duero_pharmacy_guard_day( current_time( 'Y-m-d H:i:s' ) );
$aranda_guard    = aranda_de_duero_pharmacy_guard_for_date( $aranda_today );
$aranda_after    = aranda_de_duero_pharmacy_guard_after( $aranda_today, 14 );
$aranda_catalog  = aranda_de_duero_pharmacy_catalog();
$aranda_colegio  = 'https://www.cofburgos.es';
$aranda_day_text = static function ( $date ) {
	return wp_date( 'l j \d\e F', (int) strtotime( $date . ' 12:00:00' ) );
};
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
							'lead' => array(
								__( 'Consulta qué farmacia de Aranda de Duero está de guardia hoy y en los próximos días.', 'aranda-de-duero' ),
							),
							'tips' => array(
								array( __( 'Mira la fecha', 'aranda-de-duero' ), __( 'que aparece junto a la farmacia: el calendario cambia cada día.', 'aranda-de-duero' ) ),
								array( __( 'Llama antes de ir', 'aranda-de-duero' ), __( 'si es una urgencia: el teléfono de cada farmacia está debajo de su nombre.', 'aranda-de-duero' ) ),
								array( __( 'Fíjate en los avisos', 'aranda-de-duero' ), __( 'de «por confirmar»: son días que conviene contrastar con el Colegio de Farmacéuticos.', 'aranda-de-duero' ) ),
							),
						)
					);
					?>

					<?php if ( null !== $aranda_guard ) : ?>
						<section class="pharmacy-today" aria-labelledby="pharmacy-today-title">
							<h2 id="pharmacy-today-title" class="h4">
								<?php
								/* translators: %s: day of the week and date, e.g. «jueves 22 de octubre». */
								echo esc_html( sprintf( __( 'De guardia: %s', 'aranda-de-duero' ), $aranda_day_text( $aranda_today ) ) );
								?>
							</h2>
							<div class="card pharmacy-guard mb-3">
								<div class="card-body">
									<p class="pharmacy-name mb-1"><?php echo esc_html( $aranda_guard['pharmacy']['name'] ); ?></p>
									<p class="pharmacy-address mb-2"><?php echo esc_html( $aranda_guard['pharmacy']['address'] ); ?></p>
									<?php if ( ! empty( $aranda_guard['pharmacy']['phone'] ) ) : ?>
										<p class="pharmacy-phone mb-3"><a href="<?php echo esc_url( 'tel:' . $aranda_guard['pharmacy']['phone'] ); ?>"><?php echo esc_html( aranda_de_duero_pharmacy_phone( $aranda_guard['pharmacy']['phone'] ) ); ?></a></p>
									<?php endif; ?>
									<?php $aranda_map = aranda_de_duero_pharmacy_map_link( $aranda_guard['pharmacy'] ); ?>
									<?php if ( null !== $aranda_map ) : ?>
										<p class="pharmacy-maps mb-0">
											<a class="pharmacy-map" href="<?php echo esc_url( $aranda_map['url'] ); ?>" target="_blank" rel="noopener">
												<?php
												// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
												echo aranda_de_duero_icon( $aranda_map['icon'], array( 'size' => 16 ) );
												echo esc_html( $aranda_map['label'] );
												aranda_de_duero_the_new_tab_notice();
												?>
											</a>
										</p>
									<?php endif; ?>
								</div>
							</div>
							<p class="small text-muted">
								<?php esc_html_e( 'La guardia suele cambiar a las 10 de la mañana: hasta esa hora se muestra la farmacia del día anterior. Si es una urgencia, llama antes de ir.', 'aranda-de-duero' ); ?>
							</p>
							<?php if ( $aranda_guard['low_confidence'] ) : ?>
								<p class="alert alert-warning" role="alert">
									<?php esc_html_e( 'Este día está por confirmar: antes de ir, contrasta la farmacia con el Colegio Oficial de Farmacéuticos de Burgos.', 'aranda-de-duero' ); ?>
									<a href="<?php echo esc_url( $aranda_colegio ); ?>" target="_blank" rel="noopener">cofburgos.es<?php aranda_de_duero_the_new_tab_notice(); ?></a>
								</p>
							<?php endif; ?>
						</section>
					<?php else : ?>
						<p class="alert alert-warning" role="alert">
							<?php
							/* translators: %s: current year. */
							echo esc_html( sprintf( __( 'No tenemos el calendario de guardias de %s. Consúltalo en el Colegio Oficial de Farmacéuticos de Burgos.', 'aranda-de-duero' ), wp_date( 'Y' ) ) );
							?>
							<a href="<?php echo esc_url( $aranda_colegio ); ?>" target="_blank" rel="noopener">cofburgos.es<?php aranda_de_duero_the_new_tab_notice(); ?></a>
						</p>
					<?php endif; ?>

					<?php if ( array() !== $aranda_after ) : ?>
						<section class="pharmacy-next" aria-labelledby="pharmacy-next-title">
							<h2 id="pharmacy-next-title" class="h4"><?php esc_html_e( 'Próximos días', 'aranda-de-duero' ); ?></h2>
							<table class="table">
								<thead>
									<tr>
										<th scope="col"><?php esc_html_e( 'Día', 'aranda-de-duero' ); ?></th>
										<th scope="col"><?php esc_html_e( 'Farmacia', 'aranda-de-duero' ); ?></th>
										<th scope="col"><?php esc_html_e( 'Dirección', 'aranda-de-duero' ); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ( $aranda_after as $aranda_entry ) : ?>
										<tr>
											<th scope="row"><?php echo esc_html( $aranda_day_text( $aranda_entry['date'] ) ); ?></th>
											<td>
												<?php echo esc_html( $aranda_entry['pharmacy']['name'] ); ?>
												<?php if ( $aranda_entry['low_confidence'] ) : ?>
													<small class="text-muted"><?php esc_html_e( '(por confirmar)', 'aranda-de-duero' ); ?></small>
												<?php endif; ?>
											</td>
											<td><?php echo esc_html( $aranda_entry['pharmacy']['address'] ); ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</section>
					<?php endif; ?>

					<?php if ( array() !== $aranda_catalog ) : ?>
						<section class="pharmacy-all" aria-labelledby="pharmacy-all-title">
							<h2 id="pharmacy-all-title" class="h4"><?php esc_html_e( 'Todas las farmacias', 'aranda-de-duero' ); ?></h2>
							<table class="table">
								<thead>
									<tr>
										<th scope="col"><?php esc_html_e( 'Farmacia', 'aranda-de-duero' ); ?></th>
										<th scope="col"><?php esc_html_e( 'Dirección', 'aranda-de-duero' ); ?></th>
										<th scope="col"><?php esc_html_e( 'Teléfono', 'aranda-de-duero' ); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ( $aranda_catalog as $aranda_pharmacy ) : ?>
										<tr>
											<th scope="row"><?php echo esc_html( $aranda_pharmacy['name'] ); ?></th>
											<td>
												<?php echo esc_html( $aranda_pharmacy['address'] ); ?>
												<?php if ( ! empty( $aranda_pharmacy['zone'] ) ) : ?>
													<small class="text-muted">(<?php echo esc_html( $aranda_pharmacy['zone'] ); ?>)</small>
												<?php endif; ?>
												<?php $aranda_map = aranda_de_duero_pharmacy_map_link( $aranda_pharmacy ); ?>
												<?php if ( null !== $aranda_map ) : ?>
													<small class="d-block">
														<a class="pharmacy-map" href="<?php echo esc_url( $aranda_map['url'] ); ?>" target="_blank" rel="noopener">
															<?php
															// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
															echo aranda_de_duero_icon( $aranda_map['icon'], array( 'size' => 14 ) );
															echo esc_html( $aranda_map['label'] );
															aranda_de_duero_the_new_tab_notice();
															?>
														</a>
													</small>
												<?php endif; ?>
											</td>
											<td>
												<?php if ( ! empty( $aranda_pharmacy['phone'] ) ) : ?>
													<a href="<?php echo esc_url( 'tel:' . $aranda_pharmacy['phone'] ); ?>"><?php echo esc_html( aranda_de_duero_pharmacy_phone( $aranda_pharmacy['phone'] ) ); ?></a>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</section>
					<?php endif; ?>

					<p class="small text-muted">
						<?php esc_html_e( 'Fuente: Colegio Oficial de Farmacéuticos de Burgos, servicios de urgencia de la zona farmacéutica de Aranda de Duero.', 'aranda-de-duero' ); ?>
					</p>

					<div class="entry-content">
						<?php the_content(); ?>
					</div><!-- .entry-content -->

					<?php if ( get_edit_post_link() ) : ?>
						<footer class="entry-footer">
							<?php edit_post_link( esc_html__( 'Edit', 'aranda-de-duero' ), '<span class="edit-link">', '</span>' ); ?>
						</footer><!-- .entry-footer -->
					<?php endif; ?>
				</article><!-- #post-<?php the_ID(); ?> -->

			<?php endwhile; ?>

		</main><!-- #primary -->
	</div>

<?php
get_footer();
