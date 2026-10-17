<?php
defined( 'ABSPATH' ) || exit;

/**
 * Speculation Rules.
 *
 * WordPress core already prints a speculation-rules script for logged-out
 * visitors (prefetch, conservative). The council site is mostly static
 * content, so we upgrade it to prerender with moderate eagerness: the page
 * is rendered when the visitor hovers a link, making navigation feel instant.
 *
 * @package Aranda_de_Duero
 */

/**
 * Switch core's speculation rules to prerender / moderate.
 *
 * @param array<string, string>|null $config Core configuration.
 * @return array<string, string>|null
 */
function aranda_de_duero_speculation_config( $config ) {
	if ( ! is_array( $config ) ) {
		return $config;
	}

	$config['mode']      = 'prerender';
	$config['eagerness'] = 'moderate';

	return $config;
}
add_filter( 'wp_speculation_rules_configuration', 'aranda_de_duero_speculation_config' );
