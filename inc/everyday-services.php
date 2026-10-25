<?php
defined( 'ABSPATH' ) || exit;

/**
 * «Servicios cotidianos»: the home-page section that groups farmacia de guardia, recogida de residuos, OMIC, objetos perdidos
 * and the urban bus, apart from the town-hall procedures of «Accesos frecuentes» (inc/services.php).
 *
 * @package Aranda_de_Duero
 */

/**
 * Permalink of the OMIC service when it is published, or '' (so nothing points to a service that does not exist yet).
 *
 * OMIC is a «servicio-municipal» (Comercio y Consumo), not a static page: see docs/servicios.md.
 *
 * @return string
 */
function aranda_de_duero_omic_page_url() {
	return aranda_de_duero_service_url( 'omic' );
}

/**
 * Permalink of the lost-and-found page when it is published, or ''.
 *
 * @return string
 */
function aranda_de_duero_lost_found_page_url() {
	return aranda_de_duero_page_url( 'objetos-perdidos' );
}

/**
 * Address of the urban bus of Aranda de Duero (a site run by the operator, not by the Ayuntamiento).
 *
 * @return string
 */
function aranda_de_duero_transport_url() {
	return 'https://busurbanoaranda.com/';
}

/**
 * Contact data of the Policía Local for lost-and-found items.
 *
 * Source: the Ayuntamiento's own page of concejalías and departments (/informacion-de-concejalias/), 2026-10-25. The Policía
 * Local moved to this address in 2021, from its previous one at Plaza Jardines de Don Diego.
 *
 * @return array<string,string>
 */
function aranda_de_duero_lost_found_info() {
	return array(
		'phone'      => '947 51 26 46',
		'phone_href' => 'tel:947512646',
		'email'      => 'policialocalad@arandadeduero.es',
		'address'    => 'Calle Valladolid, 4',
	);
}

/**
 * «Servicios cotidianos» of the home page. Each page only appears once it is published, so a card never leads to a 404; the bus
 * is a direct link to the operator's site and always appears.
 *
 * @return array<int, array<string, mixed>>
 */
function aranda_de_duero_everyday_services() {
	$items = array();

	$pharmacy_url = aranda_de_duero_page_url( 'farmacia-de-guardia' );
	if ( '' !== $pharmacy_url ) {
		$card = array(
			'title'    => __( 'Farmacia de guardia', 'aranda-de-duero' ),
			'text'     => __( 'Consulta qué farmacia está de guardia hoy.', 'aranda-de-duero' ),
			'url'      => $pharmacy_url,
			'new_tab'  => false,
			'keywords' => 'farmacia farmacias guardia urgencia medicamentos',
		);

		// The card names the pharmacy on duty now (name, address, phone, map); without calendar it is a plain link to the page.
		$guard = aranda_de_duero_pharmacy_guard_for_date( aranda_de_duero_pharmacy_guard_day( current_time( 'Y-m-d H:i:s' ) ) );
		if ( null !== $guard ) {
			$pharmacy         = $guard['pharmacy'];
			$card['pharmacy'] = array(
				'name'       => (string) $pharmacy['name'],
				'address'    => (string) $pharmacy['address'],
				'phone'      => aranda_de_duero_pharmacy_phone( isset( $pharmacy['phone'] ) ? $pharmacy['phone'] : '' ),
				'phone_href' => empty( $pharmacy['phone'] ) ? '' : 'tel:' . $pharmacy['phone'],
				'map'        => aranda_de_duero_pharmacy_map_link( $pharmacy ),
			);
		}

		$items[] = $card;
	}

	$waste_url = aranda_de_duero_waste_page_url();
	if ( '' !== $waste_url ) {
		$waste = aranda_de_duero_waste_info();
		$ban   = aranda_de_duero_waste_ban( current_time( 'Y-m-d' ) );

		$items[] = array(
			'title'    => __( 'Recogida de residuos', 'aranda-de-duero' ),
			'text'     => __( 'Cómo separar la basura, punto limpio y horarios.', 'aranda-de-duero' ),
			'url'      => $waste_url,
			'new_tab'  => false,
			'keywords' => 'residuos basura basuras reciclaje reciclar contenedor contenedores vidrio papel carton envases organica organico resto punto limpio aceite ropa pilas medicamentos compost',
			'waste'    => array(
				'ban'         => null !== $ban,
				'bulky_phone' => $waste['bulky_phone'],
				'bulky_href'  => $waste['bulky_href'],
				'bulky_label' => __( 'Enseres', 'aranda-de-duero' ),
			),
		);
	}

	$omic_url = aranda_de_duero_omic_page_url();
	if ( '' !== $omic_url ) {
		$items[] = array(
			'title'    => __( 'OMIC: información al consumidor', 'aranda-de-duero' ),
			'text'     => __( 'Reclamaciones, mediación e información como consumidor.', 'aranda-de-duero' ),
			'url'      => $omic_url,
			'new_tab'  => false,
			'keywords' => 'omic consumo consumidor consumidores usuario usuarios reclamacion reclamaciones queja quejas denuncia denuncias mediacion arbitraje defensa',
		);
	}

	$lost_found_url = aranda_de_duero_lost_found_page_url();
	if ( '' !== $lost_found_url ) {
		$items[] = array(
			'title'    => __( 'Objetos perdidos', 'aranda-de-duero' ),
			'text'     => __( 'Qué hacer si pierdes algo o lo encuentras en la calle.', 'aranda-de-duero' ),
			'url'      => $lost_found_url,
			'new_tab'  => false,
			'keywords' => 'objeto objetos perdido perdidos perdida perdidas encontrado encontrados hallado hallados extraviado extraviados llaves cartera movil telefono mochila documentacion dni',
		);
	}

	$items[] = array(
		'title'    => __( 'Transporte: autobús urbano', 'aranda-de-duero' ),
		'text'     => __( 'Líneas, horarios y paradas del autobús urbano.', 'aranda-de-duero' ),
		'url'      => aranda_de_duero_transport_url(),
		'new_tab'  => true,
		'keywords' => 'autobus autobuses bus transporte urbano linea lineas horario horarios parada paradas billete billetes',
	);

	/**
	 * Filters the everyday services shown in the home page.
	 *
	 * @param array<int, array<string, mixed>> $items Services.
	 */
	return apply_filters( 'aranda_de_duero_everyday_services', $items );
}

/**
 * Where the old news article about the OMIC must redirect, now that there is a standing page: its address, or '' when the given
 * slug is not that article or the OMIC page does not exist yet.
 *
 * @param string $post_name Slug of the post being viewed.
 * @return string
 */
function aranda_de_duero_omic_article_redirect_target( $post_name ) {
	if ( 'la-omic-que-es-y-que-funciones-tiene' !== $post_name ) {
		return '';
	}

	return aranda_de_duero_omic_page_url();
}

/**
 * Send the old news article about the OMIC to the new page (301), once it exists.
 */
function aranda_de_duero_redirect_omic_article() {
	if ( ! is_singular() ) {
		return;
	}

	$queried = get_queried_object();
	if ( ! ( $queried instanceof WP_Post ) ) {
		return;
	}

	$target = aranda_de_duero_omic_article_redirect_target( $queried->post_name );
	if ( '' !== $target ) {
		wp_redirect( $target, 301 ); // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect -- a fixed address of the theme, not user input.
		exit;
	}
}
add_action( 'template_redirect', 'aranda_de_duero_redirect_omic_article' );

/**
 * Where the old static /omic/ page (a page with the page-omic.php template, replaced 2026-10-25 by the «OMIC» servicio-municipal)
 * must redirect, now that the page itself is gone: its address, or '' when the given path is not it or the new service does not
 * exist yet.
 *
 * @param string $path Path of the request, with or without slashes.
 * @return string
 */
function aranda_de_duero_omic_page_redirect_target( $path ) {
	if ( 'omic' !== trim( (string) $path, '/' ) ) {
		return '';
	}

	return aranda_de_duero_omic_page_url();
}

/**
 * Send the old /omic/ page to the new service (301), now that the page has been deleted.
 */
function aranda_de_duero_redirect_omic_page() {
	if ( ! is_404() || ! isset( $_SERVER['REQUEST_URI'] ) ) {
		return;
	}

	$target = aranda_de_duero_omic_page_redirect_target( (string) wp_parse_url( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ) );
	if ( '' !== $target ) {
		wp_redirect( $target, 301 ); // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect -- a fixed address of the theme, not user input.
		exit;
	}
}
add_action( 'template_redirect', 'aranda_de_duero_redirect_omic_page' );
