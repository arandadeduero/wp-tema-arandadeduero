<?php

/**
 * Minimal stubs for the WordPress 7.1 SVG Icon API.
 * The pinned php-stubs/wordpress-stubs release predates WP 7.1, so PHPStan
 * has no definition for these functions without this stub.
 */

/**
 * @param string               $slug
 * @param array<string, mixed> $args
 * @return bool
 */
function wp_register_icon_collection( $slug, $args = array() ) {
}

/**
 * @param string               $name
 * @param array<string, mixed> $args
 * @return bool
 */
function wp_register_icon( $name, $args = array() ) {
}

/**
 * @param string               $name
 * @param array<string, mixed> $args
 * @return string
 */
function wp_get_icon( $name, $args = array() ) {
}
