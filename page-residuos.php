<?php
/**
 * Template Name: Plantilla Recogida de residuos
 *
 * How to separate and leave the waste, the bulky-items phone, the recycling centre and who to call. The data (phones, contacts) are in
 * inc/waste.php; the days on which waste cannot be deposited are in data/residuos/dias-sin-deposito.json.
 *
 * Create a page with the slug `recogida-de-residuos` and this template (the card of the home page and the search look for it).
 *
 * @package Aranda_de_Duero
 */

get_header();
aranda_de_duero_the_header_image( '' );

$aranda_waste    = aranda_de_duero_waste_info();
$aranda_today    = current_time( 'Y-m-d' );
$aranda_ban      = aranda_de_duero_waste_ban( $aranda_today );
$aranda_next     = aranda_de_duero_waste_next_bans( $aranda_today, 5 );
$aranda_day_text = static function ( $date ) {
	return wp_date( 'l j \d\e F', (int) strtotime( $date . ' 12:00:00' ) );
};

// Containers: title, what goes in, and the hours when the leaflet gives them.
$aranda_containers = array(
	array(
		__( 'Contenedor azul · Papel y cartón', 'aranda-de-duero' ),
		__( 'Libretas, folios, revistas, papel de regalo y de envolver, sobres (sin ventanilla), folletos de publicidad, y cajas, piezas de cartón y envases de cereales, galletas, etc. Las cajas grandes se pliegan o trocean antes de depositarlas.', 'aranda-de-duero' ),
		'',
	),
	array(
		__( 'Contenedor amarillo · Envases ligeros', 'aranda-de-duero' ),
		__( 'Envases de plástico (botellas de agua, refrescos, leche…), bolsas y envolturas de plástico, envases de metal (latas de conserva y de bebida) y envases tipo brick (leche, zumos, vinos, batidos, caldos, gazpacho…).', 'aranda-de-duero' ),
		'',
	),
	array(
		__( 'Contenedor marrón · Orgánica', 'aranda-de-duero' ),
		__( 'Restos de la preparación de la comida y sobras, alimentos en mal estado (separados de su envase), cáscaras de huevo y de frutos secos, posos de café y bolsitas de infusiones, papel de cocina y servilletas de papel sucias, pequeñas ramas y restos de poda, hojas y flores, corchos de botella, virutas de madera y serrín.', 'aranda-de-duero' ),
		'',
	),
	array(
		__( 'Iglú verde · Vidrio', 'aranda-de-duero' ),
		__( 'Botellas, tarros y frascos de vidrio (de bebidas, alimentos, perfumes y cosméticos), sin tapón.', 'aranda-de-duero' ),
		__( 'De 8:00 a 23:00 h.', 'aranda-de-duero' ),
	),
	array(
		__( 'Contenedor de tapa verde · Resto', 'aranda-de-duero' ),
		__( 'Lo que no se puede depositar en los demás: pañales usados, juguetes, biberones, chupetes, utensilios de cocina, objetos cerámicos, arena para mascotas, pelo, polvo, colillas…', 'aranda-de-duero' ),
		__( 'De 21:00 a 23:00 h.', 'aranda-de-duero' ),
	),
);

// Special containers: what and where.
$aranda_special = array(
	array( __( 'Aceite vegetal usado', 'aranda-de-duero' ), __( 'Restos de aceite de cocina de origen doméstico, guardados antes en una botella de plástico cerrada.', 'aranda-de-duero' ) ),
	array( __( 'Ropa usada', 'aranda-de-duero' ), __( 'Ropa de vestir, calzado, complementos (bolsos, cinturones, bufandas) y textil del hogar (sábanas, toallas, cortinas), siempre en bolsas cerradas.', 'aranda-de-duero' ) ),
	array( __( 'Pilas', 'aranda-de-duero' ), __( 'Contenedor especial verde, en comercios, edificios municipales, etc.', 'aranda-de-duero' ) ),
	array( __( 'Medicamentos', 'aranda-de-duero' ), __( 'Contenedor especial blanco, en las farmacias.', 'aranda-de-duero' ) ),
);
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
								__( 'Cómo separar y depositar los residuos en Aranda de Duero, a quién llamar para retirar muebles y enseres, y dónde está el punto limpio.', 'aranda-de-duero' ),
							),
							'tips' => array(
								array( __( 'Separa en casa', 'aranda-de-duero' ), __( 'los residuos en cinco grupos: papel y cartón, envases, orgánica, vidrio y resto.', 'aranda-de-duero' ) ),
								array( __( 'Respeta el horario', 'aranda-de-duero' ), __( 'del vidrio (de 8:00 a 23:00 h) y del contenedor de resto (de 21:00 a 23:00 h).', 'aranda-de-duero' ) ),
								array( __( 'Muebles y enseres', 'aranda-de-duero' ), __( 'se recogen previa solicitud: llama al teléfono que verás más abajo.', 'aranda-de-duero' ) ),
							),
						)
					);
					?>

					<?php if ( null !== $aranda_ban ) : ?>
						<div class="waste-ban" role="status">
							<p class="waste-ban-title"><?php esc_html_e( 'HOY PROHIBIDO DEPOSITAR BASURA', 'aranda-de-duero' ); ?></p>
							<?php if ( '' !== $aranda_ban['reason'] ) : ?>
								<p class="mb-0"><?php echo esc_html( $aranda_ban['reason'] ); ?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( array() !== $aranda_next ) : ?>
						<section class="waste-next" aria-labelledby="waste-next-title">
							<h2 id="waste-next-title" class="h4"><?php esc_html_e( 'Próximos días sin depósito de basura', 'aranda-de-duero' ); ?></h2>
							<ul>
								<?php foreach ( $aranda_next as $aranda_day ) : ?>
									<li>
										<strong><?php echo esc_html( $aranda_day_text( $aranda_day['date'] ) ); ?></strong>
										<?php if ( '' !== $aranda_day['reason'] ) : ?>
											· <?php echo esc_html( $aranda_day['reason'] ); ?>
										<?php endif; ?>
									</li>
								<?php endforeach; ?>
							</ul>
						</section>
					<?php endif; ?>

					<section class="waste-containers" id="contenedores" aria-labelledby="waste-containers-title">
						<h2 id="waste-containers-title" class="h4"><?php esc_html_e( 'Cómo depositar los residuos domésticos', 'aranda-de-duero' ); ?></h2>
						<div class="row">
							<?php foreach ( $aranda_containers as $aranda_container ) : ?>
								<div class="col-md-6 mb-3">
									<div class="card h-100">
										<div class="card-body">
											<h3 class="h5"><?php echo esc_html( $aranda_container[0] ); ?></h3>
											<p class="mb-1"><?php echo esc_html( $aranda_container[1] ); ?></p>
											<?php if ( '' !== $aranda_container[2] ) : ?>
												<p class="mb-0"><strong><?php esc_html_e( 'Horario:', 'aranda-de-duero' ); ?></strong> <?php echo esc_html( $aranda_container[2] ); ?></p>
											<?php endif; ?>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>

						<h3 class="h5"><?php esc_html_e( 'Contenedores especiales', 'aranda-de-duero' ); ?></h3>
						<ul>
							<?php foreach ( $aranda_special as $aranda_item ) : ?>
								<li><strong><?php echo esc_html( $aranda_item[0] ); ?>:</strong> <?php echo esc_html( $aranda_item[1] ); ?></li>
							<?php endforeach; ?>
						</ul>
					</section>

					<section class="waste-bulky" id="enseres" aria-labelledby="waste-bulky-title">
						<h2 id="waste-bulky-title" class="h4"><?php esc_html_e( 'Recogida de enseres (muebles, colchones, electrodomésticos…)', 'aranda-de-duero' ); ?></h2>
						<p>
							<?php
							/* translators: %s: company that collects the items. */
							echo esc_html( sprintf( __( 'La recogida es previa solicitud, a través de %s. Llama a este teléfono:', 'aranda-de-duero' ), $aranda_waste['bulky_company'] ) );
							?>
						</p>
						<p class="waste-phone"><a href="<?php echo esc_url( $aranda_waste['bulky_href'] ); ?>"><?php echo esc_html( $aranda_waste['bulky_phone'] ); ?></a></p>
					</section>

					<section class="waste-recycling-center" id="punto-limpio" aria-labelledby="waste-center-title">
						<h2 id="waste-center-title" class="h4"><?php esc_html_e( 'Punto limpio', 'aranda-de-duero' ); ?></h2>
						<p><?php esc_html_e( 'El punto limpio del Consorcio Provincial de Residuos Urbanos de Burgos está a disposición de los vecinos (solo particulares) en la carretera de La Aguilera, km 2,4.', 'aranda-de-duero' ); ?></p>
						<ul>
							<li><strong><?php esc_html_e( 'De lunes a viernes (excepto festivos):', 'aranda-de-duero' ); ?></strong> <?php esc_html_e( 'mañanas de 10:00 a 14:00 h y tardes de 16:00 a 20:00 h.', 'aranda-de-duero' ); ?></li>
							<li><strong><?php esc_html_e( 'Sábados (excepto festivos):', 'aranda-de-duero' ); ?></strong> <?php esc_html_e( 'mañanas de 10:00 a 14:00 h.', 'aranda-de-duero' ); ?></li>
						</ul>
					</section>

					<section class="waste-contact" id="atencion" aria-labelledby="waste-contact-title">
						<h2 id="waste-contact-title" class="h4"><?php esc_html_e( 'Atención ciudadana de Medio Ambiente', 'aranda-de-duero' ); ?></h2>
						<ul>
							<li>
								<strong><?php esc_html_e( 'Información general:', 'aranda-de-duero' ); ?></strong>
								<?php esc_html_e( 'de 9:00 a 14:00 h, para cualquier consulta sobre los servicios de Medio Ambiente. Teléfono', 'aranda-de-duero' ); ?>
								<a href="<?php echo esc_url( $aranda_waste['office_href'] ); ?>"><?php echo esc_html( $aranda_waste['office_phone'] ); ?></a>.
							</li>
							<li>
								<strong><?php esc_html_e( 'Correo electrónico:', 'aranda-de-duero' ); ?></strong>
								<a href="<?php echo esc_url( 'mailto:' . $aranda_waste['office_email'] ); ?>"><?php echo esc_html( $aranda_waste['office_email'] ); ?></a>
							</li>
							<li>
								<strong><?php esc_html_e( 'Atención presencial:', 'aranda-de-duero' ); ?></strong>
								<?php
								/* translators: 1: Medio Ambiente office address, 2: company that collects the items, 3: its office address. */
								echo esc_html( sprintf( __( 'oficinas de Medio Ambiente (%1$s) y oficinas de %2$s, %3$s.', 'aranda-de-duero' ), $aranda_waste['office_address'], $aranda_waste['bulky_company'], $aranda_waste['company_office'] ) );
								?>
							</li>
						</ul>
					</section>

					<section class="waste-map" id="mapa" aria-labelledby="waste-map-title">
						<h2 id="waste-map-title" class="h4"><?php esc_html_e( 'Mapa de contenedores', 'aranda-de-duero' ); ?></h2>
						<p><?php esc_html_e( 'Próximamente: un mapa con la ubicación de los contenedores, integrado en esta misma página.', 'aranda-de-duero' ); ?></p>
					</section>

					<p class="small text-muted">
						<?php esc_html_e( 'Fuente: folleto «La separación de residuos empieza desde casa», de la Concejalía de Medio Ambiente.', 'aranda-de-duero' ); ?>
						<a href="<?php echo esc_url( home_url( '/reciclaje-la-separacion-de-residuos-empieza-en-casa/' ) ); ?>"><?php esc_html_e( 'Ver la noticia con el folleto', 'aranda-de-duero' ); ?></a>
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
