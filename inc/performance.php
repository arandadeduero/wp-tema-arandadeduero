<?php
defined( 'ABSPATH' ) || exit;

/**
 * Front-end performance and privacy tweaks: drop third-party requests the
 * site doesn't need.
 *
 * @package Aranda_de_Duero
 */

/**
 * Stop WordPress from loading its emoji script and styles.
 *
 * Besides two extra requests, the script swaps every emoji in the page for an
 * <img> fetched from s.w.org (a third party), even though browsers render
 * emoji natively. The agenda, byline and press-note pages all use emoji.
 */
function aranda_de_duero_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'embed_head', 'print_emoji_detection_script' );

	// Emoji styles: print_emoji_styles() was replaced by wp_enqueue_emoji_styles() in WP 6.4.
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_emoji_styles' );
	remove_action( 'admin_enqueue_scripts', 'wp_enqueue_emoji_styles' );

	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

	add_filter( 'emoji_svg_url', '__return_false' );
	add_filter( 'tiny_mce_plugins', 'aranda_de_duero_remove_tinymce_emoji' );
}
add_action( 'init', 'aranda_de_duero_disable_emojis' );

/**
 * Remove the emoji plugin from the classic editor.
 *
 * @param mixed $plugins TinyMCE plugin list.
 * @return array<int|string, string>
 */
function aranda_de_duero_remove_tinymce_emoji( $plugins ) {
	return is_array( $plugins ) ? array_diff( $plugins, array( 'wpemoji' ) ) : array();
}

/**
 * Handles of the stylesheets the Font Awesome plugin prints on the front end.
 *
 * The theme draws its icons as inline SVG (inc/icons.php) and none of the
 * pages checked use Font Awesome classes, yet the plugin loads its CSS from
 * use.fontawesome.com (a third party) on every page.
 *
 * @return string[]
 */
function aranda_de_duero_font_awesome_handles() {
	return array(
		'font-awesome-official',
		'font-awesome-official-v4shim',
		'font-awesome-svg-styles',
	);
}

/**
 * Dequeue the Font Awesome plugin styles on the front end.
 *
 * Return false from the `aranda_de_duero_dequeue_font_awesome` filter if
 * content starts using Font Awesome classes.
 */
function aranda_de_duero_dequeue_font_awesome() {
	if ( ! apply_filters( 'aranda_de_duero_dequeue_font_awesome', true ) ) {
		return;
	}

	foreach ( aranda_de_duero_font_awesome_handles() as $handle ) {
		wp_dequeue_style( $handle );
		wp_deregister_style( $handle );
	}
}
// After the plugin has enqueued its styles (default priority 10).
add_action( 'wp_enqueue_scripts', 'aranda_de_duero_dequeue_font_awesome', 100 );

/**
 * Drop the dns-prefetch hint for use.fontawesome.com when its CSS is gone.
 *
 * @param array<int, mixed> $urls          URLs to print for resource hints.
 * @param string            $relation_type The relation type the URLs are printed for.
 * @return array<int, mixed>
 */
function aranda_de_duero_drop_font_awesome_hint( $urls, $relation_type ) {
	if ( 'dns-prefetch' !== $relation_type || ! apply_filters( 'aranda_de_duero_dequeue_font_awesome', true ) ) {
		return $urls;
	}

	return array_values(
		array_filter(
			(array) $urls,
			function ( $url ) {
				$host = is_array( $url ) ? ( $url['href'] ?? '' ) : (string) $url;
				return false === strpos( $host, 'use.fontawesome.com' );
			}
		)
	);
}
add_filter( 'wp_resource_hints', 'aranda_de_duero_drop_font_awesome_hint', 10, 2 );
