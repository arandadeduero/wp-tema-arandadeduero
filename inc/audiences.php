<?php
defined( 'ABSPATH' ) || exit;

/**
 * «Para ti»: the services of the town hall grouped by who is asking (families, older people, young people, businesses, new
 * residents, associations), as a second way to navigate besides the departments.
 *
 * Nothing new is built: every card is a page that already exists, either a section of this site (the concejalías and topics of
 * the menu) or a service or procedure from inc/services.php, inc/search.php and inc/situations.php.
 *
 * @package Aranda_de_Duero
 */

/**
 * The sections of this site (topics and concejalías of the menu) that «Para ti» points to.
 *
 * The descriptions say no more than the title does.
 *
 * @return array<int, array{title: string, text: string, url: string, new_tab: bool}>
 */
function aranda_de_duero_site_sections() {
	$rows = array(
		array( __( 'Servicios sociales', 'aranda-de-duero' ), __( 'Información y servicios de servicios sociales.', 'aranda-de-duero' ), '/tema/servicios-sociales/' ),
		array( __( 'Salud', 'aranda-de-duero' ), __( 'Información y servicios de salud.', 'aranda-de-duero' ), '/tema/salud/' ),
		array( __( 'Cultura', 'aranda-de-duero' ), __( 'Información y servicios de cultura.', 'aranda-de-duero' ), '/tema/cultura/' ),
		array( __( 'Movilidad y transportes', 'aranda-de-duero' ), __( 'Información y servicios de movilidad y transportes.', 'aranda-de-duero' ), '/tema/movilidad-y-transportes/' ),
		array( __( 'Consumo y comercio', 'aranda-de-duero' ), __( 'Información y servicios de consumo y comercio.', 'aranda-de-duero' ), '/tema/consumo-y-comercio/' ),
		array( __( 'Juventud', 'aranda-de-duero' ), __( 'Información y servicios de juventud.', 'aranda-de-duero' ), '/concejalia/juventud/' ),
		array( __( 'Urbanismo y vivienda', 'aranda-de-duero' ), __( 'Información y servicios de urbanismo y vivienda.', 'aranda-de-duero' ), '/concejalia/urbanismo-y-vivienda/' ),
	);

	$sections = array();
	foreach ( $rows as $row ) {
		$sections[] = array(
			'title'   => $row[0],
			'text'    => $row[1],
			'url'     => home_url( $row[2] ),
			'new_tab' => false,
		);
	}

	$sections[] = array(
		'title'   => __( 'Deportes', 'aranda-de-duero' ),
		'text'    => __( 'Información y servicios de deportes.', 'aranda-de-duero' ),
		'url'     => 'https://www.arandactiva.com/',
		'new_tab' => true,
	);

	return $sections;
}

/**
 * An entry by its exact title: first the sections of the site, then the services and procedures.
 *
 * @param string $title Title.
 * @return array{title: string, text: string, url: string, new_tab: bool}|null
 */
function aranda_de_duero_audience_entry( $title ) {
	foreach ( aranda_de_duero_site_sections() as $section ) {
		if ( $section['title'] === $title ) {
			return $section;
		}
	}

	return aranda_de_duero_service_by_title( $title );
}

/**
 * Where «Para ti» is: the page with that slug when it exists and is published, '' until then.
 *
 * @return string
 */
function aranda_de_duero_audiences_url() {
	return aranda_de_duero_page_url( 'para-ti' );
}

/**
 * The groups of the page, each with the cards that help that group.
 *
 * @return array<int, array{title: string, items: array<int, array{title: string, text: string, url: string, new_tab: bool}>}>
 */
function aranda_de_duero_audiences() {
	$procedures = aranda_de_duero_portal_procedures();

	$groups = array(
		array(
			'title' => __( 'Familias', 'aranda-de-duero' ),
			'items' => array( 'Servicios sociales', 'Salud', 'Cultura', 'Deportes', 'Agenda de eventos', 'Certificado de padrón', 'Ayudas y subvenciones' ),
		),
		array(
			'title' => __( 'Personas mayores', 'aranda-de-duero' ),
			'items' => array( 'Servicios sociales', 'Salud', 'Teléfonos de interés', 'Movilidad y transportes', 'Cultura', 'Deportes', 'Cita previa' ),
		),
		array(
			'title' => __( 'Jóvenes', 'aranda-de-duero' ),
			'items' => array( 'Juventud', 'Deportes', 'Cultura', 'Agenda de eventos', 'Empleo público', 'Ayudas y subvenciones' ),
		),
		array(
			'title' => __( 'Empresas y comercios', 'aranda-de-duero' ),
			'items' => array( 'Consumo y comercio', $procedures['ambiental'], $procedures['licencia-amb'], $procedures['veladores'], $procedures['via-publica'], 'Ayudas y subvenciones', 'Perfil de contratante', 'Factura electrónica' ),
		),
		array(
			'title' => __( 'Nuevos vecinos', 'aranda-de-duero' ),
			'items' => array( $procedures['padron'], 'Certificado de padrón', 'Cita previa', 'Movilidad y transportes', 'Agenda de eventos', 'Turismo', 'Teléfonos de interés', 'Preguntas frecuentes' ),
		),
		array(
			'title' => __( 'Asociaciones', 'aranda-de-duero' ),
			'items' => array( 'Participación ciudadana', 'Ayudas y subvenciones', 'Agenda de eventos', 'Tablón de anuncios' ),
		),
	);

	// Entries given by title are looked up; one that no longer exists is left out instead of breaking the page.
	foreach ( $groups as $index => $group ) {
		$items = array();
		foreach ( $group['items'] as $item ) {
			$resolved = is_array( $item ) ? $item : aranda_de_duero_audience_entry( $item );
			if ( null !== $resolved ) {
				$items[] = array(
					'title'   => $resolved['title'],
					'text'    => $resolved['text'],
					'url'     => $resolved['url'],
					'new_tab' => $resolved['new_tab'],
				);
			}
		}
		$groups[ $index ]['items'] = $items;
	}

	/**
	 * Filters the groups shown in «Para ti».
	 *
	 * @param array $groups Groups, each with a `title` and its `items`.
	 */
	return apply_filters( 'aranda_de_duero_audiences', $groups );
}

/**
 * The ways to explore the services besides searching, linked from the home page: only the pages that exist and are published.
 *
 * @return array<int, array{title: string, url: string}>
 */
function aranda_de_duero_explore_links() {
	$candidates = array(
		array( __( 'Para ti', 'aranda-de-duero' ), aranda_de_duero_audiences_url() ),
		array( __( 'Trámites por situaciones', 'aranda-de-duero' ), aranda_de_duero_situations_url() ),
	);

	$links = array();
	foreach ( $candidates as $candidate ) {
		if ( '' !== $candidate[1] ) {
			$links[] = array(
				'title' => $candidate[0],
				'url'   => $candidate[1],
			);
		}
	}

	return $links;
}
