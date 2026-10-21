<?php
defined( 'ABSPATH' ) || exit;

/**
 * Upcoming events from the My Calendar plugin.
 *
 * Building the list needs one query for the events plus metadata and an
 * occurrence lookup per event, and the home page asks for it on every
 * request, so the result is cached in a short-lived transient.
 *
 * @package Aranda_de_Duero
 */

/**
 * Transient key for the upcoming-events list.
 *
 * @param int $limit Maximum number of events.
 * @return string
 */
function aranda_de_duero_events_cache_key( $limit ) {
	return 'aranda_upcoming_events_' . (int) $limit;
}

/**
 * Remember the limits used so the cache can be flushed without guessing them.
 *
 * @param int $limit Maximum number of events.
 */
function aranda_de_duero_events_remember_limit( $limit ) {
	$limits = (array) get_option( 'aranda_upcoming_events_limits', array() );
	if ( ! in_array( (int) $limit, $limits, true ) ) {
		$limits[] = (int) $limit;
		update_option( 'aranda_upcoming_events_limits', $limits, false );
	}
}

/**
 * Get upcoming events from My Calendar, served from a transient when possible.
 *
 * Events only change when an editor saves them (the cache is flushed then), and
 * the list also depends on today's date, hence the short expiry.
 *
 * @param int $limit Maximum number of events to return (default: 50).
 * @return array<int, array<string, mixed>> Upcoming events sorted by start date.
 */
function aranda_de_duero_get_upcoming_events( $limit = 50 ) {
	$key    = aranda_de_duero_events_cache_key( $limit );
	$cached = get_transient( $key );

	if ( is_array( $cached ) ) {
		return $cached;
	}

	$events = aranda_de_duero_query_upcoming_events( $limit );

	set_transient( $key, $events, 10 * MINUTE_IN_SECONDS );
	aranda_de_duero_events_remember_limit( $limit );

	return $events;
}

/**
 * Drop every cached events list; hooked to the saves that can change it.
 */
function aranda_de_duero_flush_events_cache() {
	foreach ( (array) get_option( 'aranda_upcoming_events_limits', array() ) as $limit ) {
		delete_transient( aranda_de_duero_events_cache_key( $limit ) );
	}
}

/**
 * Flush the events cache when an event post changes.
 *
 * @param int $post_id Post ID.
 */
function aranda_de_duero_maybe_flush_events_cache( $post_id ) {
	if ( 'mc-events' === get_post_type( $post_id ) ) {
		aranda_de_duero_flush_events_cache();
	}
}
add_action( 'save_post', 'aranda_de_duero_maybe_flush_events_cache' );
add_action( 'deleted_post', 'aranda_de_duero_maybe_flush_events_cache' );
add_action( 'trashed_post', 'aranda_de_duero_maybe_flush_events_cache' );

/**
 * Query My Calendar events and keep the ones that are still upcoming.
 *
 * @param int $limit Maximum number of events to read.
 * @return array<int, array<string, mixed>>
 */
function aranda_de_duero_query_upcoming_events( $limit ) {
	global $wpdb;

	// Only published events: the result is cached for everyone, so it must not depend on who triggered the cache fill
	// (WP_Query adds private posts for users who can read them).
	$query = aranda_de_duero_content( 'mc-events', '', '', $limit, false, array( 'post_status' => 'publish' ) );

	if ( ! $query->have_posts() ) {
		return array();
	}

	$upcoming_events = array();
	$current_year    = (int) current_time( 'Y' );
	$next_year       = $current_year + 1;
	$today           = strtotime( current_time( 'Y-m-d' ) );
	$series_ids      = array();

	while ( $query->have_posts() ) {
		$query->the_post();

		$event_data = get_post_meta( get_the_ID(), '_mc_event_data', false );

		if ( empty( $event_data ) || empty( $event_data[0] ) ) {
			continue;
		}

		$date_start = $event_data[0]['event_begin'];
		$date_end   = $event_data[0]['event_end'];

		$date_end_timestamp = strtotime( $date_end );
		$event_year         = (int) wp_date( 'Y', strtotime( $date_start ) );

		// Only show active upcoming events from the current/next year.
		if ( ( $event_year !== $current_year && $event_year !== $next_year ) || $today > $date_end_timestamp ) {
			continue;
		}

		$event_id = get_post_meta( get_the_ID(), '_mc_event_id', false );
		$series   = ! empty( $event_id ) ? (int) $event_id[0] : 0;

		if ( $series ) {
			$series_ids[] = $series;
		}

		$upcoming_events[] = array(
			'id'           => get_the_ID(),
			'date_start'   => $date_start,
			'date_end'     => $date_end,
			'title'        => get_the_title(),
			'thumbnail'    => wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ),
			'thumbnail_id' => (int) get_post_thumbnail_id( get_the_ID() ),
			'link'         => get_permalink( get_the_ID() ),
			'series'       => $series,
		);
	}

	wp_reset_postdata();

	// One query for every occurrence id, instead of one per event.
	$occurrences = array();
	$series_ids  = array_values( array_unique( $series_ids ) );
	if ( $series_ids ) {
		$placeholders = implode( ',', array_fill( 0, count( $series_ids ), '%d' ) );
		// phpcs:disable WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare -- My Calendar has no API for this lookup, the caller caches the result, and the placeholder list is built from %d only.
		$rows = $wpdb->get_results(
			$wpdb->prepare( "SELECT occur_event_id, MIN(occur_id) AS occur_id FROM {$wpdb->prefix}my_calendar_events WHERE occur_event_id IN ($placeholders) GROUP BY occur_event_id", $series_ids ),
			OBJECT_K
		);
		// phpcs:enable
		foreach ( (array) $rows as $event_series => $row ) {
			$occurrences[ (int) $event_series ] = $row->occur_id;
		}
	}

	foreach ( $upcoming_events as $i => $event ) {
		if ( ! empty( $event['series'] ) && ! empty( $occurrences[ $event['series'] ] ) ) {
			$upcoming_events[ $i ]['link'] .= '?mc_id=' . $occurrences[ $event['series'] ];
		}
		unset( $upcoming_events[ $i ]['series'] );
	}

	if ( ! empty( $upcoming_events ) ) {
		$dates = array_column( $upcoming_events, 'date_start' );
		array_multisort( $dates, SORT_ASC, $upcoming_events );
	}

	return $upcoming_events;
}
