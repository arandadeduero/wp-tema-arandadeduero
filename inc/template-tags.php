<?php
defined( 'ABSPATH' ) || exit;

/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Aranda_de_Duero
 */

if ( ! function_exists( 'aranda_de_duero_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function aranda_de_duero_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = '📅 <a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
endif;

if ( ! function_exists( 'aranda_de_duero_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function aranda_de_duero_posted_by() {
		$byline = '👤 ' . sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'por %s', 'post author', 'aranda-de-duero' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
endif;
if ( ! function_exists( 'aranda_de_duero_posted_categories' ) ) :
	/**
	 * Prints HTML with the categories for the current post.
	 */
	function aranda_de_duero_posted_categories() {
		if ( 'post' === get_post_type() ) {
			$categories_list = get_the_category_list( esc_html__( ', ', 'aranda-de-duero' ) );
			if ( $categories_list ) {
				printf(
					'<span class="categories-link">📂 %s</span>',
					$categories_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}
		}
	}
endif;
if ( ! function_exists( 'aranda_de_duero_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function aranda_de_duero_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'aranda-de-duero' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Publicado en %1$s', 'aranda-de-duero' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aranda-de-duero' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'aranda-de-duero' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Editar <span class="screen-reader-text">%s</span>', 'aranda-de-duero' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'aranda_de_duero_entry_search_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function aranda_de_duero_entry_search_footer() {

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Editar <span class="screen-reader-text">%s</span>', 'aranda-de-duero' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'aranda_de_duero_entry_topics_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function aranda_de_duero_entry_topics_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			global $post;
			$topics = get_the_terms( $post->ID, 'tema' );

			if ( ! empty( $topics ) && ! is_wp_error( $topics ) ) {
				foreach ( $topics as $topic ) {
					$term_link = get_term_link( $topic );
					if ( is_wp_error( $term_link ) ) {
						continue;
					}
					printf(
						/* translators: %1$s: topic/term link. */
						'<span class="cat-links">' . esc_html__( 'Publicado en %1$s', 'aranda-de-duero' ) . '</span>',
						'<span class="tags-links"><a href="' . esc_url( $term_link ) . '">' . esc_html( $topic->name ) . '</a></span>'
					); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			}
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( ' Editar <span class="screen-reader-text">%s</span>', 'aranda-de-duero' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'aranda_de_duero_attachment_id_from_url' ) ) :
	/**
	 * Id of the attachment a media address belongs to, or 0 when it is not one (kept a week: the lookup is a query and the header image is on every page).
	 *
	 * @param string $url Address of the file.
	 * @return int
	 */
	function aranda_de_duero_attachment_id_from_url( $url ) {
		$key = 'aranda_att_' . md5( $url );
		$id  = get_transient( $key );
		if ( false === $id ) {
			$id = (int) attachment_url_to_postid( $url );
			set_transient( $key, (string) $id, WEEK_IN_SECONDS );
		}

		return (int) $id;
	}
endif;

if ( ! function_exists( 'aranda_de_duero_header_image_html' ) ) :
	/**
	 * The <img> of the header image. When the address is a file of the media library the tag gets its width, height and srcset (no layout
	 * shift, and a smaller file on a phone); otherwise it is the plain tag it used to be.
	 *
	 * @param string $url Address of the image.
	 * @param string $alt Alt text («» for a decorative image).
	 * @return string
	 */
	function aranda_de_duero_header_image_html( $url, $alt ) {
		$id    = aranda_de_duero_attachment_id_from_url( $url );
		$image = $id ? wp_get_attachment_image(
			$id,
			'full',
			false,
			array(
				'class'         => 'img-fluid w-100 cabecera_pagina',
				'alt'           => $alt,
				'loading'       => 'eager',
				'fetchpriority' => 'high',
			)
		) : '';

		if ( '' !== $image ) {
			return $image;
		}

		return '<img src="' . esc_url( $url ) . '" class="img-fluid w-100 cabecera_pagina" alt="' . esc_attr( $alt ) . '" />';
	}
endif;

if ( ! function_exists( 'aranda_de_duero_the_header_image' ) ) :
	/**
	 * Prints the full-width header image used at the top of page and
	 * taxonomy-archive templates.
	 *
	 * Uses the ACF "cabecera_de_pagina" field when set on the current
	 * object (or on $term_object, e.g. the WP_Term of a taxonomy archive),
	 * falling back to the site-wide default header image set in the
	 * Customizer.
	 *
	 * @param string           $alt         Accessible alt text for the image.
	 *                                      Empty by default (the header image
	 *                                      is decorative in most templates).
	 * @param int|WP_Term|null $term_object Optional. Pass the current term on a
	 *                                      taxonomy archive template so its own
	 *                                      ACF field is used instead of the
	 *                                      current post's.
	 */
	function aranda_de_duero_the_header_image( $alt = '', $term_object = null ) {
		$header_image = wp_get_attachment_url( get_theme_mod( 'aranda_de_duero_default_header_image' ) );

		$field = aranda_de_duero_field( 'cabecera_de_pagina', $term_object ? $term_object : false );
		if ( $field ) {
			$header_image = $field;
		}
		?>
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 p-0">
					<?php
					echo aranda_de_duero_header_image_html( (string) $header_image, $alt ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the tag is built with esc_url()/esc_attr() or by wp_get_attachment_image().
					?>
				</div>
			</div>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'aranda_de_duero_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function aranda_de_duero_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail( 'medium' ); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
				the_post_thumbnail(
					'post-thumbnail',
					array(
						// Decorative: the anchor is aria-hidden and the title is already in the heading link.
						'alt' => '',
					)
				);
				?>
			</a>

			<?php
		endif;
		// End is_singular().
	}
endif;

