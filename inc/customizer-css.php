<?php
defined( 'ABSPATH' ) || exit;

/**
 * Brand colour, for the places that cannot read a CSS variable (the `theme-color` meta tag of header.php).
 *
 * It must be the same as `--color-primary` in css/aranda-de-duero.css; tests/ColorSourcesTest.php checks it.
 *
 * @return string
 */
function aranda_de_duero_brand_color() {
	return '#0083c1';
}

/**
 * Default value of each colour of the Customizer, by setting id: the only place they are written (functions.php and
 * aranda_de_duero_customizer_css_variables() read them from here). They must match `--color-text` and `--color-bg` in
 * css/aranda-de-duero.css; tests/ColorSourcesTest.php checks it.
 *
 * @return array<string, string>
 */
function aranda_de_duero_customizer_color_defaults() {
	return array(
		'aranda_de_duero_background_color'        => '#ffffff',
		'aranda_de_duero_header_text_color'       => '#000000',
		'aranda_de_duero_text_color'              => '#000000',
		'aranda_de_duero_link_color'              => '#000000',
		'aranda_de_duero_main_section_background' => '#000000',
		'aranda_de_duero_main_section_text_color' => '#000000',
	);
}

/**
 * Customizer colours as CSS custom properties.
 *
 * The rules that use them live in css/aranda-de-duero.css (`var(--aranda-…)`); the only thing that
 * has to be printed in the page is these values, because they come from get_theme_mod().
 *
 * @return array<string, string> Custom property name => colour.
 */
function aranda_de_duero_customizer_css_variables() {
	$defaults = aranda_de_duero_customizer_color_defaults();
	$colors   = array(
		'--aranda-text-color'              => 'aranda_de_duero_text_color',
		'--aranda-background-color'        => 'aranda_de_duero_background_color',
		'--aranda-header-text-color'       => 'aranda_de_duero_header_text_color',
		'--aranda-link-color'              => 'aranda_de_duero_link_color',
		'--aranda-main-section-text-color' => 'aranda_de_duero_main_section_text_color',
	);

	$variables = array();
	foreach ( $colors as $property => $mod ) {
		$variables[ $property ] = (string) get_theme_mod( $mod, $defaults[ $mod ] );
	}

	return $variables;
}
