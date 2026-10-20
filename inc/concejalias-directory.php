<?php
defined( 'ABSPATH' ) || exit;

/**
 * Links from the contact directory (/informacion-de-concejalias/) to the page of each concejalía.
 *
 * The directory is a TablePress table written by hand (id 2) whose first column names a department. Each department that belongs
 * to a concejalía becomes a link to /concejalia/<slug>/, so the directory leads to the news, the contact card and the rest of the
 * area. A department that has no concejalía of its own (street lighting, say) is left as plain text.
 *
 * @package Aranda_de_Duero
 */

/**
 * Id of the TablePress table that is the directory.
 */
const ARANDA_DE_DUERO_DIRECTORY_TABLE_ID = '2';

/**
 * Concejalía (slug of the term) of each department, by how its name starts in the directory.
 *
 * @return array<string,string> Start of the department name => slug of the concejalía.
 */
function aranda_de_duero_directory_concejalias() {
	return array(
		'Aguas'                    => 'aguas',
		'Bomberos'                 => 'seguridad-ciudadana',
		'CEAS'                     => 'servicios-sociales',
		'Cementerio'               => 'cementerio',
		'Cultura'                  => 'cultura',
		'Deportes'                 => 'deportes',
		'Educación'                => 'educacion',
		'Festejos'                 => 'festejos',
		'Gestión tributaria'       => 'hacienda',
		'Informática'              => 'nuevas-tecnologias',
		'Intervención'             => 'hacienda',
		'Juventud'                 => 'juventud',
		'Medio ambiente'           => 'medio-ambiente-aseo-urbano-parques-y-jardines',
		'Obras y urbanismo'        => 'urbanismo-y-vivienda',
		'OMIC'                     => 'comercio-y-consumo',
		'Policía'                  => 'seguridad-ciudadana',
		'Promoción'                => 'promocion',
		'Recinto ferial'           => 'promocion',
		'Secretaría'               => 'alcaldia',
		'Tesorería'                => 'hacienda',
		'Turismo'                  => 'turismo',
	);
}

/**
 * Slug of the concejalía of a department, or '' when it has none.
 *
 * @param string $department Name of the department as written in the table.
 * @return string
 */
function aranda_de_duero_directory_concejalia_slug( $department ) {
	$department = trim( wp_strip_all_tags( (string) $department ) );

	foreach ( aranda_de_duero_directory_concejalias() as $start => $slug ) {
		if ( 0 === strpos( $department, $start ) ) {
			return $slug;
		}
	}

	return '';
}

/**
 * Turn the name of a department of the directory into a link to its concejalía.
 *
 * @param string     $content  Content of the cell.
 * @param string|int $table_id Id of the TablePress table.
 * @return string
 */
function aranda_de_duero_directory_link_cell( $content, $table_id ) {
	if ( ARANDA_DE_DUERO_DIRECTORY_TABLE_ID !== (string) $table_id ) {
		return $content;
	}

	$slug = aranda_de_duero_directory_concejalia_slug( $content );
	if ( '' === $slug ) {
		return $content;
	}

	$term = get_term_by( 'slug', $slug, 'concejalia' );
	$link = $term ? get_term_link( $term ) : '';
	if ( ! is_string( $link ) || '' === $link ) {
		return $content;
	}

	return sprintf( '<a href="%s">%s</a>', esc_url( $link ), $content );
}
add_filter( 'tablepress_cell_content', 'aranda_de_duero_directory_link_cell', 10, 2 );
