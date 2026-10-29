<?php
defined( 'ABSPATH' ) || exit;

/**
 * Helpers of the archives of the "concejalia" and "tema" terms (taxonomy-concejalia.php, taxonomy-tema.php).
 *
 * @package Aranda_de_Duero
 */

/**
 * Years without new content after which the archive of a term is shown as historic.
 */
const ARANDA_DE_DUERO_HISTORIC_YEARS = 3;

/**
 * Timestamp of the newest post of a term, or 0 when it has none.
 *
 * The archives only list posts, so only posts count here.
 *
 * @param WP_Term $term Term of the archive.
 * @return int
 */
function aranda_de_duero_term_newest_timestamp( $term ) {
	$ids = get_posts(
		array(
			'post_type'              => 'post',
			'post_status'            => 'publish',
			'posts_per_page'         => 1,
			'orderby'                => 'date',
			'order'                  => 'DESC',
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'tax_query'              => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- one cheap lookup of the newest post of the term.
				array(
					'taxonomy' => $term->taxonomy,
					'field'    => 'term_id',
					'terms'    => $term->term_id,
				),
			),
		)
	);

	if ( empty( $ids ) ) {
		return 0;
	}
	return (int) get_post_timestamp( $ids[0] );
}

/**
 * Whether the newest post of a term is older than ARANDA_DE_DUERO_HISTORIC_YEARS years.
 *
 * A term with no posts is not historic: it is empty, and the archive says so.
 *
 * @param int $newest Timestamp from aranda_de_duero_term_newest_timestamp().
 * @param int $now    Current timestamp.
 * @return bool
 */
function aranda_de_duero_is_historic( $newest, $now ) {
	if ( $newest <= 0 ) {
		return false;
	}
	return ( $now - $newest ) > ARANDA_DE_DUERO_HISTORIC_YEARS * 365.25 * DAY_IN_SECONDS;
}

/**
 * Page links of a custom query, in the markup the archives already style (.navigation.pagination).
 *
 * The main query of a term archive is not the one that is listed (it has its own, 25 per page), so
 * the_posts_pagination() would count the wrong pages.
 *
 * @param WP_Query $query Query being listed.
 * @return void
 */
function aranda_de_duero_query_pagination( $query ) {
	if ( $query->max_num_pages < 2 ) {
		return;
	}

	$links = paginate_links(
		array(
			'total'     => (int) $query->max_num_pages,
			'current'   => max( 1, (int) get_query_var( 'paged' ) ),
			'mid_size'  => 1,
			'prev_text' => esc_html__( '« Anterior', 'aranda-de-duero' ),
			'next_text' => esc_html__( 'Siguiente »', 'aranda-de-duero' ),
		)
	);
	if ( ! $links ) {
		return;
	}
	?>
	<nav class="navigation pagination" aria-label="<?php esc_attr_e( 'Paginación', 'aranda-de-duero' ); ?>">
		<div class="nav-links"><?php echo $links; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- paginate_links() returns WP core's own escaped HTML. ?></div>
	</nav>
	<?php
}

/**
 * Let the description of a concejalía or tema keep the `class` of its links (the «link-pill» button): WordPress' filter for
 * term descriptions only allows href, title, rel… on <a>, so the class was dropped when saving.
 *
 * @param array<string, array<string, bool>> $tags    Allowed tags and attributes.
 * @param string                             $context Context of the filter (`pre_term_description` when saving).
 * @return array<string, array<string, bool>>
 */
function aranda_de_duero_term_description_link_class( $tags, $context ) {
	if ( 'pre_term_description' === $context && isset( $tags['a'] ) ) {
		$tags['a']['class'] = true;
	}
	return $tags;
}
add_filter( 'wp_kses_allowed_html', 'aranda_de_duero_term_description_link_class', 10, 2 );
