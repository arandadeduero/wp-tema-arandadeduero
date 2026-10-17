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

/**
 * Transparent 1x1 GIF used as a stand-in until a deferred slide image loads.
 */
const ARANDA_DE_DUERO_PLACEHOLDER_GIF = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';

/**
 * Defer the download of every home-slider image except the first.
 *
 * Smart Slider prints one <img> per slide and fetches all of them up front,
 * though only the first is visible. On the home page that is six full-size
 * animated GIFs (~3.5 MB) fighting the first one, which is the LCP element.
 *
 * The first image is made eager (the plugin prints it as lazy); the rest are
 * swapped for a 1x1 placeholder and restored once the page has loaded (or after
 * 5 s). It deliberately does NOT use fetchpriority="high": measured on a 4G
 * profile it delayed the first paint by ~650 ms because the 254 KB image then
 * competed with the render-blocking stylesheets. If the
 * plugin's markup doesn't match the expected pattern, the HTML is returned
 * untouched, so a plugin update can't break the slider through this filter.
 *
 * @param string $html Output of the [smartslider3] shortcode.
 * @return string
 */
function aranda_de_duero_defer_slider_images( $html ) {
	// A <picture> that holds only an <img> (no <source> to rewrite) inside a slide background.
	$pattern = '#(<div class="n2-ss-slide-background-image"[^>]*>\s*<picture[^>]*>\s*)<img\s([^>]*?)src="([^"]+)"([^>]*)>#';
	$index   = 0;

	$result = preg_replace_callback(
		$pattern,
		function ( $match ) use ( &$index ) {
			$before = $match[1];
			$attrs  = $match[2] . $match[4];

			if ( 0 === $index++ ) {
				$attrs = preg_replace( '#\sloading="[^"]*"#', '', ' ' . $attrs );
				return $before . '<img loading="eager" ' . trim( $attrs ) . ' src="' . $match[3] . '">';
			}

			return $before . '<img ' . $match[2] . 'src="' . ARANDA_DE_DUERO_PLACEHOLDER_GIF . '" data-aranda-src="' . $match[3] . '"' . $match[4] . '>';
		},
		$html
	);

	// Nothing matched, or only one slide: keep the original markup exactly as it was.
	if ( null === $result || $index < 2 ) {
		return $html;
	}

	$script = '<script>(function(){var d=!1;function r(){if(d)return;d=!0;document.querySelectorAll("img[data-aranda-src]").forEach(function(i){i.src=i.getAttribute("data-aranda-src");i.removeAttribute("data-aranda-src")})}if("complete"===document.readyState){r()}else{window.addEventListener("load",r);setTimeout(r,5000)}})();</script>';

	return $result . $script;
}
