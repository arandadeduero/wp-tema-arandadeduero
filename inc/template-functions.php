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

/**
 * Text announced to screen readers for links that open in a new tab.
 *
 * @return string
 */
function aranda_de_duero_new_tab_text() {
	return __( '(se abre en una nueva pestaña)', 'aranda-de-duero' );
}

/**
 * Print a visually hidden notice for links that open in a new tab (WCAG 3.2.5 / G201).
 */
function aranda_de_duero_the_new_tab_notice() {
	echo '<span class="screen-reader-text"> ' . esc_html( aranda_de_duero_new_tab_text() ) . '</span>';
}

/**
 * My Calendar widgets that list events (not the calendar itself).
 *
 * @return string[] Widget id bases.
 */
function aranda_de_duero_events_list_widgets() {
	return array(
		'my_calendar_upcoming_widget',
		'my_calendar_today_widget',
	);
}

/**
 * Don't print event-list widgets in sidebars.
 *
 * Upcoming events are shown only on the home page (its agenda section, see
 * page-home.php) and the calendar lives on the Agenda page content. Editors had
 * the "Upcoming events" widget in the Ayuntamiento sidebar, so it appeared on
 * every page using that template. Hiding it at render time leaves the saved
 * widget settings untouched and doesn't affect the Widgets admin screen.
 *
 * Return true from the `aranda_de_duero_show_events_widgets` filter to show them.
 *
 * @param array<string, mixed>|false $instance Widget settings.
 * @param WP_Widget                  $widget   Widget being displayed.
 * @return array<string, mixed>|false False stops the widget from being printed.
 */
function aranda_de_duero_hide_events_widgets( $instance, $widget ) {
	if ( ! $widget instanceof WP_Widget || ! in_array( $widget->id_base, aranda_de_duero_events_list_widgets(), true ) ) {
		return $instance;
	}

	return apply_filters( 'aranda_de_duero_show_events_widgets', false ) ? $instance : false;
}
add_filter( 'widget_display_callback', 'aranda_de_duero_hide_events_widgets', 10, 2 );
