<?php
defined( 'ABSPATH' ) || exit;

/**
 * JSON-LD structured data (schema.org).
 *
 * @package Aranda_de_Duero
 */

/**
 * Social profile URLs of the council, used for Organization "sameAs".
 *
 * @return string[]
 */
function aranda_de_duero_social_profiles() {
	return apply_filters(
		'aranda_de_duero_social_profiles',
		array(
			'https://www.facebook.com/aytoarandadeduero/',
			'https://twitter.com/aytoaranda',
			'https://www.instagram.com/ayuntamientoarandadeduero/',
			'https://www.youtube.com/@SMArandaDeDuero',
			'https://www.flickr.com/photos/ayuntamientoarandadeduero/',
		)
	);
}

/**
 * A schema.org BreadcrumbList.
 *
 * @param array<int, array{0: string, 1: string}> $crumbs Name and address of each step, from «Inicio» to the current page.
 * @return array<string, mixed>
 */
function aranda_de_duero_breadcrumb_list( $crumbs ) {
	$items = array();
	foreach ( $crumbs as $i => $crumb ) {
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $i + 1,
			'name'     => wp_strip_all_tags( $crumb[0] ),
			'item'     => $crumb[1],
		);
	}

	return array(
		'@type'           => 'BreadcrumbList',
		'itemListElement' => $items,
	);
}

/**
 * Build the schema.org graph for the current request.
 *
 * @return array<int, array<string, mixed>>
 */
function aranda_de_duero_structured_data_graph() {
	$home_url = home_url( '/' );
	$org_id   = $home_url . '#organization';
	$site_id  = $home_url . '#website';

	$organization = array(
		'@type'  => 'GovernmentOrganization',
		'@id'    => $org_id,
		'name'   => get_bloginfo( 'name' ),
		'url'    => $home_url,
		'sameAs' => aranda_de_duero_social_profiles(),
	);

	$logo_id = (int) get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$logo = wp_get_attachment_image_src( $logo_id, 'full' );
		if ( $logo ) {
			$organization['logo'] = array(
				'@type'  => 'ImageObject',
				'url'    => $logo[0],
				'width'  => $logo[1],
				'height' => $logo[2],
			);
		}
	}

	$graph = array(
		$organization,
		array(
			'@type'           => 'WebSite',
			'@id'             => $site_id,
			'url'             => $home_url,
			'name'            => get_bloginfo( 'name' ),
			'inLanguage'      => get_bloginfo( 'language' ),
			'publisher'       => array( '@id' => $org_id ),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => array(
					'@type'       => 'EntryPoint',
					'urlTemplate' => $home_url . '?s={search_term_string}',
				),
				'query-input' => 'required name=search_term_string',
			),
		),
	);

	if ( is_singular() ) {
		$post_id = get_the_ID();
		$url     = get_permalink( $post_id );
		$image   = get_the_post_thumbnail_url( $post_id, 'large' );

		if ( is_singular( 'post' ) ) {
			$article = array(
				'@type'            => 'NewsArticle',
				'@id'              => $url . '#article',
				'mainEntityOfPage' => $url,
				'headline'         => wp_strip_all_tags( get_the_title( $post_id ) ),
				'datePublished'    => get_the_date( 'c', $post_id ),
				'dateModified'     => get_the_modified_date( 'c', $post_id ),
				'inLanguage'       => get_bloginfo( 'language' ),
				'author'           => array( '@id' => $org_id ),
				'publisher'        => array( '@id' => $org_id ),
			);
			if ( $image ) {
				$article['image'] = array( $image );
			}
			$graph[] = $article;
		} elseif ( is_singular( 'mc-events' ) ) {
			$event_data = get_post_meta( $post_id, '_mc_event_data', true );
			$event      = array(
				'@type'     => 'Event',
				'@id'       => $url . '#event',
				'name'      => wp_strip_all_tags( get_the_title( $post_id ) ),
				'url'       => $url,
				'organizer' => array( '@id' => $org_id ),
			);
			if ( is_array( $event_data ) && ! empty( $event_data['event_begin'] ) ) {
				$begin              = ( ! empty( $event_data['event_time'] ) ) ? $event_data['event_begin'] . ' ' . $event_data['event_time'] : $event_data['event_begin'];
				$event['startDate'] = wp_date( 'c', strtotime( $begin ) );
				if ( ! empty( $event_data['event_end'] ) ) {
					$event['endDate'] = wp_date( 'c', strtotime( $event_data['event_end'] ) );
				}
			}
			if ( $image ) {
				$event['image'] = array( $image );
			}
			$graph[] = $event;
		} else {
			$graph[] = array(
				'@type'      => 'WebPage',
				'@id'        => $url . '#webpage',
				'url'        => $url,
				'name'       => wp_strip_all_tags( get_the_title( $post_id ) ),
				'isPartOf'   => array( '@id' => $site_id ),
				'inLanguage' => get_bloginfo( 'language' ),
			);
		}

		// Breadcrumbs: Inicio > ancestors > current page.
		if ( is_page() || is_singular( 'post' ) ) {
			$crumbs = array( array( __( 'Inicio', 'aranda-de-duero' ), $home_url ) );
			// The front page is «Inicio» itself: no second step with the same name and address.
			if ( ! is_front_page() ) {
				foreach ( array_reverse( get_post_ancestors( $post_id ) ) as $ancestor_id ) {
					$crumbs[] = array( get_the_title( $ancestor_id ), get_permalink( $ancestor_id ) );
				}
				$crumbs[] = array( get_the_title( $post_id ), $url );
			}

			$graph[] = aranda_de_duero_breadcrumb_list( $crumbs );
		}
	} elseif ( is_tax() ) {
		// Archive of a concejalía, tema, servicio, sección…: Inicio > the term.
		$term      = get_queried_object();
		$term_link = is_object( $term ) ? get_term_link( $term ) : '';
		if ( is_object( $term ) && ! empty( $term->name ) && is_string( $term_link ) && '' !== $term_link ) {
			$graph[] = aranda_de_duero_breadcrumb_list(
				array(
					array( __( 'Inicio', 'aranda-de-duero' ), $home_url ),
					array( $term->name, $term_link ),
				)
			);
		}
	}

	return apply_filters( 'aranda_de_duero_structured_data', $graph );
}

/**
 * Print the JSON-LD graph in wp_head.
 */
function aranda_de_duero_print_structured_data() {
	if ( is_404() || is_search() || aranda_de_duero_has_external_og_tags() ) {
		return;
	}

	$data = array(
		'@context' => 'https://schema.org',
		'@graph'   => aranda_de_duero_structured_data_graph(),
	);

	// JSON_HEX_TAG escapes "<" and ">" so content can never close the script tag.
	$json = wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP );
	if ( $json ) {
		echo '<script type="application/ld+json">' . $json . "</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON encoded with JSON_HEX_TAG.
	}
}
add_action( 'wp_head', 'aranda_de_duero_print_structured_data', 3 );
