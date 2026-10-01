<?php
defined( 'ABSPATH' ) || exit;

/**
 * Open Graph / Twitter Card meta tags.
 *
 * @package Aranda_de_Duero
 */

/**
 * Whether an SEO plugin already outputs Open Graph tags, so ours don't duplicate them.
 *
 * @return bool
 */
function aranda_de_duero_has_external_og_tags() {
	return defined( 'WPSEO_VERSION' )
		|| defined( 'RANK_MATH_VERSION' )
		|| defined( 'AIOSEO_VERSION' )
		|| defined( 'SEOPRESS_VERSION' );
}

/**
 * Resolve the Open Graph / Twitter Card image for the current request.
 *
 * Falls back in order: featured image, ACF page header image, the
 * dedicated social-share Customizer image, the default header image.
 *
 * @return array{url: string, width: int, height: int}|null
 */
function aranda_de_duero_social_image() {
	if ( is_singular() && has_post_thumbnail() ) {
		$src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
		if ( $src ) {
			return array(
				'url'    => $src[0],
				'width'  => $src[1],
				'height' => $src[2],
			);
		}
	}

	if ( is_singular() ) {
		$header_image = get_field( 'cabecera_de_pagina' );
		if ( $header_image ) {
			return array(
				'url'    => $header_image,
				'width'  => 0,
				'height' => 0,
			);
		}
	}

	$social_share_image = get_theme_mod( 'aranda_de_duero_social_share_image' );
	if ( $social_share_image ) {
		$src = wp_get_attachment_image_src( $social_share_image, 'full' );
		if ( $src ) {
			return array(
				'url'    => $src[0],
				'width'  => $src[1],
				'height' => $src[2],
			);
		}
	}

	$default_header_image = get_theme_mod( 'aranda_de_duero_default_header_image' );
	if ( $default_header_image ) {
		$src = wp_get_attachment_image_src( $default_header_image, 'large' );
		if ( $src ) {
			return array(
				'url'    => $src[0],
				'width'  => $src[1],
				'height' => $src[2],
			);
		}
	}

	return null;
}

/**
 * Output Open Graph and Twitter Card meta tags in wp_head.
 */
function aranda_de_duero_social_meta_tags() {
	if ( aranda_de_duero_has_external_og_tags() ) {
		return;
	}

	if ( is_front_page() ) {
		$title       = get_bloginfo( 'name' );
		$description = get_bloginfo( 'description' );
		$url         = home_url( '/' );
		$type        = 'website';
	} elseif ( is_singular() ) {
		$title = get_the_title();
		$url   = get_permalink();
		$type  = 'article';

		$description = has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', get_the_ID() ) ) ), 55 );
	} else {
		global $wp;
		$title       = wp_get_document_title();
		$description = get_bloginfo( 'description' );
		$url         = home_url( user_trailingslashit( $wp->request ) );
		$type        = 'website';
	}

	if ( '' === trim( wp_strip_all_tags( (string) $description ) ) ) {
		$description = get_bloginfo( 'description' );
	}

	$image = aranda_de_duero_social_image();
	?>
	<meta property="og:locale" content="es_ES" />
	<meta property="og:type" content="<?php echo esc_attr( $type ); ?>" />
	<meta property="og:title" content="<?php echo esc_attr( wp_strip_all_tags( $title ) ); ?>" />
	<meta property="og:description" content="<?php echo esc_attr( wp_strip_all_tags( $description ) ); ?>" />
	<meta property="og:url" content="<?php echo esc_url( $url ); ?>" />
	<meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
	<?php if ( $image ) : ?>
		<meta property="og:image" content="<?php echo esc_url( $image['url'] ); ?>" />
		<?php if ( ! empty( $image['width'] ) ) : ?>
			<meta property="og:image:width" content="<?php echo esc_attr( (string) $image['width'] ); ?>" />
		<?php endif; ?>
		<?php if ( ! empty( $image['height'] ) ) : ?>
			<meta property="og:image:height" content="<?php echo esc_attr( (string) $image['height'] ); ?>" />
		<?php endif; ?>
	<?php endif; ?>
	<meta name="twitter:card" content="<?php echo esc_attr( $image ? 'summary_large_image' : 'summary' ); ?>" />
	<meta name="twitter:title" content="<?php echo esc_attr( wp_strip_all_tags( $title ) ); ?>" />
	<meta name="twitter:description" content="<?php echo esc_attr( wp_strip_all_tags( $description ) ); ?>" />
	<?php if ( $image ) : ?>
		<meta name="twitter:image" content="<?php echo esc_url( $image['url'] ); ?>" />
	<?php endif; ?>
	<?php
}
add_action( 'wp_head', 'aranda_de_duero_social_meta_tags', 1 );

// Avoid duplicate tags if Jetpack's own Open Graph module is active.
add_filter( 'jetpack_enable_open_graph', '__return_false' );
