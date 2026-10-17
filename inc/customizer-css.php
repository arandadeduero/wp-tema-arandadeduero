<?php
defined( 'ABSPATH' ) || exit;

/**
 * Customizer colours as CSS custom properties.
 *
 * The rules that use them live in css/aranda-de-duero.css (`var(--aranda-…)`); the only thing that
 * has to be printed in the page is these values, because they come from get_theme_mod().
 *
 * @return array<string, string> Custom property name => colour.
 */
function aranda_de_duero_customizer_css_variables() {
	$colors = array(
		'--aranda-text-color'              => array( 'aranda_de_duero_text_color', '#000000' ),
		'--aranda-background-color'        => array( 'aranda_de_duero_background_color', '#ffffff' ),
		'--aranda-header-text-color'       => array( 'aranda_de_duero_header_text_color', '#000000' ),
		'--aranda-link-color'              => array( 'aranda_de_duero_link_color', '#000000' ),
		'--aranda-main-section-text-color' => array( 'aranda_de_duero_main_section_text_color', '#000000' ),
	);

	$variables = array();
	foreach ( $colors as $property => $mod ) {
		$variables[ $property ] = (string) get_theme_mod( $mod[0], $mod[1] );
	}

	return $variables;
}
