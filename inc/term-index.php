<?php
defined( 'ABSPATH' ) || exit;

/**
 * Index pages /tema/, /concejalia/ and /tramite/.
 *
 * WordPress has no page there (the taxonomies and the post type are registered by the Custom Post Type UI plugin without an
 * archive), so those addresses were a 404. This answers them with a list of what exists: the terms that have content for
 * everybody, and every term with its numbers and an edit link for administrators, to see at a glance what is in use.
 * They are not for search engines (noindex).
 *
 * No rewrite rule is added (that would need flushing the rules on deploy): a request that WordPress answered with a 404 is
 * recognised by its path and answered with 200 instead.
 *
 * @package Aranda_de_Duero
 */

/**
 * Which index a request path is, or '' when it is none.
 *
 * @param string $path Path of the request without the site address, as in $wp->request («tema», «concejalia/»…).
 * @return string `tema`, `concejalia`, `tramite` or ''.
 */
function aranda_de_duero_term_index_kind( $path ) {
	$path = strtolower( trim( $path, '/' ) );

	return in_array( $path, array( 'tema', 'concejalia', 'tramite' ), true ) ? $path : '';
}

/**
 * The index being requested, when the request ended in a 404.
 *
 * @return string
 */
function aranda_de_duero_current_term_index() {
	global $wp;

	if ( ! is_404() || ! isset( $wp->request ) ) {
		return '';
	}

	return aranda_de_duero_term_index_kind( $wp->request );
}

/**
 * Whether the name of a term is only a number (an old import that has no meaning for a visitor).
 *
 * @param string $name Name of the term.
 * @return bool
 */
function aranda_de_duero_is_numeric_term_name( $name ) {
	return '' !== trim( $name ) && ctype_digit( trim( $name ) );
}

/**
 * Sort terms by name, ignoring case and accents («Educación» between «Deportes» and «Festejos»).
 *
 * A term-order plugin may put them in a manual order that nobody sees in the admin; a public index reads better in alphabetical order.
 *
 * @param WP_Term[] $terms Terms.
 * @return WP_Term[]
 */
function aranda_de_duero_sort_terms_by_name( $terms ) {
	$terms = array_values( $terms );
	usort(
		$terms,
		function ( $a, $b ) {
			return strcmp( strtolower( remove_accents( $a->name ) ), strtolower( remove_accents( $b->name ) ) );
		}
	);

	return $terms;
}

/**
 * Answer an index with its own template and a 200.
 *
 * @param string $template Template WordPress chose.
 * @return string
 */
function aranda_de_duero_term_index_template( $template ) {
	if ( '' === aranda_de_duero_current_term_index() ) {
		return $template;
	}

	status_header( 200 );
	return get_template_directory() . '/index-terminos.php';
}
add_filter( 'template_include', 'aranda_de_duero_term_index_template', 99 );

/**
 * Do not let WordPress guess another address for an index.
 *
 * Before answering a 404, WordPress looks for a post whose slug starts with the requested one and redirects to it: /tramite/ went
 * to the first procedure whose slug begins with «tramite-…», so the index was never reached.
 *
 * @param string|null $pre Address WordPress guessed, or null to let it guess.
 * @return string|false|null
 */
function aranda_de_duero_term_index_no_guess( $pre ) {
	return '' !== aranda_de_duero_current_term_index() ? false : $pre;
}
add_filter( 'pre_redirect_guess_404_permalink', 'aranda_de_duero_term_index_no_guess' );

/**
 * Title of the browser tab.
 *
 * @param string $title Title WordPress built.
 * @return string
 */
function aranda_de_duero_term_index_title( $title ) {
	$labels = array(
		'tema'       => __( 'Temas', 'aranda-de-duero' ),
		'concejalia' => __( 'Concejalías', 'aranda-de-duero' ),
		'tramite'    => __( 'Trámites', 'aranda-de-duero' ),
	);
	$kind   = aranda_de_duero_current_term_index();

	return '' === $kind ? $title : $labels[ $kind ] . ' – ' . get_bloginfo( 'name' );
}
add_filter( 'pre_get_document_title', 'aranda_de_duero_term_index_title' );

/**
 * Keep the indexes out of search engines.
 *
 * @param array $robots Directives WordPress built.
 * @return array
 */
function aranda_de_duero_term_index_robots( $robots ) {
	if ( '' !== aranda_de_duero_current_term_index() ) {
		$robots['noindex'] = true;
		unset( $robots['max-image-preview'] );
	}

	return $robots;
}
add_filter( 'wp_robots', 'aranda_de_duero_term_index_robots' );

/**
 * Status of a term for the administrators' table.
 *
 * @param WP_Term $term Term.
 * @param int     $now  Current timestamp.
 * @return array{label: string, newest: int}
 */
function aranda_de_duero_term_status( $term, $now ) {
	$newest = aranda_de_duero_term_newest_timestamp( $term );

	if ( 0 === $newest ) {
		$label = __( 'Sin entradas', 'aranda-de-duero' );
	} elseif ( aranda_de_duero_is_historic( $newest, $now ) ) {
		$label = __( 'Histórico', 'aranda-de-duero' );
	} else {
		$label = __( 'Vigente', 'aranda-de-duero' );
	}
	if ( aranda_de_duero_is_numeric_term_name( $term->name ) ) {
		$label .= ' · ' . __( 'nombre numérico', 'aranda-de-duero' );
	}

	return array(
		'label'  => $label,
		'newest' => $newest,
	);
}
