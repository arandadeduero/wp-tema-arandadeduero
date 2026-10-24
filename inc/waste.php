<?php
defined( 'ABSPATH' ) || exit;

/**
 * Waste collection: the data the «Recogida de residuos» page, its card in «Accesos frecuentes» and the search share.
 *
 * Phones and contact data are here, once. The days on which waste cannot be left in the containers are a plain file,
 * data/residuos/dias-sin-deposito.json: when today is in it, the page and the card turn light red and say so.
 *
 * @package Aranda_de_Duero
 */

/**
 * Slug of the page that uses page-residuos.php (the card of the home page and the search look for it).
 */
const ARANDA_DE_DUERO_WASTE_PAGE_SLUG = 'recogida-de-residuos';

/**
 * Contact data of the waste services (source: the leaflet «La separación de residuos empieza desde casa», Concejalía de Medio Ambiente).
 *
 * @return array<string,string>
 */
function aranda_de_duero_waste_info() {
	return array(
		'bulky_phone'    => '947 50 60 50',
		'bulky_href'     => 'tel:947506050',
		'bulky_company'  => 'Valoriza Servicios Medioambientales, S.A.',
		'office_phone'   => '947 54 63 53',
		'office_href'    => 'tel:947546353',
		'office_email'   => 'medioambiente@arandadeduero.es',
		'office_address' => 'Plaza Mayor, 13, 2.ª planta',
		'company_office' => 'C/ Santander, 2',
	);
}

/**
 * Permalink of the waste page when it is published, or '' (so nothing points to a page that does not exist yet).
 *
 * @return string
 */
function aranda_de_duero_waste_page_url() {
	return aranda_de_duero_page_url( ARANDA_DE_DUERO_WASTE_PAGE_SLUG );
}

/**
 * The days without deposit, from data/residuos/dias-sin-deposito.json, by date.
 *
 * @return array<string,string> Date «YYYY-MM-DD» => reason ('' when none is given).
 */
function aranda_de_duero_waste_ban_days() {
	$path = get_template_directory() . '/data/residuos/dias-sin-deposito.json';
	if ( ! is_readable( $path ) ) {
		return array();
	}

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- a file of the theme, not a URL.
	$raw  = file_get_contents( $path );
	$data = false === $raw ? null : json_decode( $raw, true );
	if ( ! is_array( $data ) || empty( $data['days'] ) || ! is_array( $data['days'] ) ) {
		return array();
	}

	$days = array();
	foreach ( $data['days'] as $day ) {
		if ( isset( $day['date'] ) && 1 === preg_match( '/^\d{4}-\d{2}-\d{2}$/', (string) $day['date'] ) ) {
			$days[ (string) $day['date'] ] = isset( $day['reason'] ) ? (string) $day['reason'] : '';
		}
	}
	ksort( $days );

	return $days;
}

/**
 * The reason why waste cannot be deposited on a day, or null when it can.
 *
 * @param string $date Day, «YYYY-MM-DD».
 * @return array{date: string, reason: string}|null
 */
function aranda_de_duero_waste_ban( $date ) {
	$days = aranda_de_duero_waste_ban_days();

	return isset( $days[ $date ] ) ? array(
		'date'   => $date,
		'reason' => $days[ $date ],
	) : null;
}

/**
 * The next days without deposit after a date.
 *
 * @param string $date  Day «YYYY-MM-DD»; only later days are returned.
 * @param int    $limit How many.
 * @return array<int,array{date: string, reason: string}>
 */
function aranda_de_duero_waste_next_bans( $date, $limit = 5 ) {
	$next = array();
	foreach ( aranda_de_duero_waste_ban_days() as $day => $reason ) {
		if ( $day > $date ) {
			$next[] = array(
				'date'   => $day,
				'reason' => $reason,
			);
		}
	}

	return array_slice( $next, 0, max( 0, (int) $limit ) );
}
