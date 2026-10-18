<?php
defined( 'ABSPATH' ) || exit;

/**
 * "Especiales" section of the home page, rendered by the theme (replaces Ajax Load More).
 *
 * The cards come straight from the `especial` post type: featured image, title and the ACF field
 * `especiales_enlace` (falls back to the entry's own URL). All of them are printed: no pagination, no "load more".
 *
 * @package Aranda_de_Duero
 */

/**
 * Specials in the order of the home page: newest first, at most 24.
 *
 * @return array<int, array{title: string, url: string, image_id: int}>
 */
function aranda_de_duero_home_especiales() {
	$posts = get_posts(
		array(
			'post_type'      => 'especial',
			'post_status'    => 'publish',
			'posts_per_page' => 24,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
		)
	);

	$items = array();
	foreach ( (array) $posts as $post ) {
		$link = trim( (string) get_post_meta( $post->ID, 'especiales_enlace', true ) );

		$items[] = array(
			'title'    => (string) $post->post_title,
			'url'      => '' !== $link ? $link : (string) get_permalink( $post->ID ),
			'image_id' => (int) get_post_thumbnail_id( $post->ID ),
		);
	}

	return $items;
}

/**
 * Markup of one card. The whole card is a link (one accessible link: the title).
 *
 * @param array{title: string, url: string, image_id: int} $item Special.
 * @param bool                                              $lazy Lazy-load the image.
 * @return string
 */
function aranda_de_duero_especial_card_html( array $item, $lazy = true ) {
	$image = $item['image_id'] > 0
		? wp_get_attachment_image(
			$item['image_id'],
			'medium',
			false,
			array(
				'class'    => 'img-fluid',
				'alt'      => '',
				'loading'  => $lazy ? 'lazy' : 'eager',
				'decoding' => 'async',
			)
		)
		: '';

	return '<div class="col-lg-2 col-md-4 col-6 p-0 mb-3"><div class="home-especial-wrapper"><div class="home-especial">'
		. ( '' !== $image ? '<div class="home-especial-image">' . $image . '</div>' : '' )
		. '<div class="home-especial-title"><a class="bold stretched-link" href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['title'] ) . '</a></div>'
		. '</div></div></div>';
}

/**
 * The whole section content: every card in one grid.
 *
 * @param array<int, array{title: string, url: string, image_id: int}> $items Specials.
 * @return string
 */
function aranda_de_duero_home_especiales_html( array $items ) {
	$html = '';

	foreach ( array_values( $items ) as $index => $item ) {
		// The first row is above the fold on large screens; the rest wait for scrolling.
		$html .= aranda_de_duero_especial_card_html( $item, $index >= 6 );
	}

	return '<div class="row">' . $html . '</div>';
}
