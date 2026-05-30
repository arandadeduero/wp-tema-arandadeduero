<?php
defined( 'ABSPATH' ) || exit;

/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Aranda_de_Duero
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function aranda_de_duero_body_classes( $classes ) {
    // Adds a class of hfeed to non-singular pages.
    if ( ! is_singular() ) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when no sidebar is active for the current context.
    $sidebar_ids = array(
        'sidebar-1',
        'sidebar-ayuntamiento',
        'sidebar-villa',
        'sidebar-noticias',
        'sidebar-tramites',
        'sidebar-actualidad',
        'sidebar-servicios',
    );

    $any_active = false;
    foreach ( $sidebar_ids as $sidebar_id ) {
        if ( is_active_sidebar( $sidebar_id ) ) {
            $any_active = true;
            break;
        }
    }

    if ( ! $any_active ) {
        $classes[] = 'no-sidebar';
    }

    return $classes;
}
add_filter( 'body_class', 'aranda_de_duero_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function aranda_de_duero_pingback_header() {
    if ( is_singular() && pings_open() ) {
        printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
    }
}
add_action( 'wp_head', 'aranda_de_duero_pingback_header' );
