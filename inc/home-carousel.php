<?php
defined( 'ABSPATH' ) || exit;

/**
 * Home-page carousel owned by the theme (replaces Smart Slider 3).
 *
 * Slides are a small private post type, "Portada: diapositivas": featured image, title (used as the image's
 * alternative text when it has none), optional link and the order of the "Orden" attribute. The markup is a
 * scroll-snap strip (css/aranda-de-duero.css) with a few lines of JS for the arrows and the autoplay
 * (js/home-carousel.js). While there are no slides, page-home.php still prints the Smart Slider.
 *
 * @package Aranda_de_Duero
 */

const ARANDA_DE_DUERO_SLIDE_POST_TYPE = 'portada_slide';
const ARANDA_DE_DUERO_SLIDE_URL_META  = '_aranda_slide_url';

/**
 * Register the slides post type (admin only: no public URLs).
 */
function aranda_de_duero_register_slides() {
	register_post_type(
		ARANDA_DE_DUERO_SLIDE_POST_TYPE,
		array(
			'labels'              => array(
				'name'          => __( 'Portada: diapositivas', 'aranda-de-duero' ),
				'singular_name' => __( 'Diapositiva', 'aranda-de-duero' ),
				'add_new_item'  => __( 'Añadir diapositiva', 'aranda-de-duero' ),
				'edit_item'     => __( 'Editar diapositiva', 'aranda-de-duero' ),
				'menu_name'     => __( 'Portada', 'aranda-de-duero' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_rest'        => false,
			'exclude_from_search' => true,
			'menu_icon'           => 'dashicons-images-alt2',
			'menu_position'       => 21,
			'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
		)
	);
}
add_action( 'init', 'aranda_de_duero_register_slides' );

/**
 * Meta box with the optional link of a slide.
 */
function aranda_de_duero_slide_meta_box() {
	add_meta_box(
		'aranda-slide-link',
		__( 'Enlace de la diapositiva', 'aranda-de-duero' ),
		'aranda_de_duero_slide_meta_box_html',
		ARANDA_DE_DUERO_SLIDE_POST_TYPE,
		'normal'
	);
}
add_action( 'add_meta_boxes', 'aranda_de_duero_slide_meta_box' );

/**
 * Print the link field.
 *
 * @param WP_Post $post Slide being edited.
 */
function aranda_de_duero_slide_meta_box_html( $post ) {
	wp_nonce_field( 'aranda_slide_link', 'aranda_slide_link_nonce' );
	$url = (string) get_post_meta( $post->ID, ARANDA_DE_DUERO_SLIDE_URL_META, true );
	?>
	<p>
		<label for="aranda-slide-url"><?php esc_html_e( 'Dirección a la que lleva al pulsar (opcional):', 'aranda-de-duero' ); ?></label>
		<input type="url" id="aranda-slide-url" name="aranda_slide_url" value="<?php echo esc_attr( $url ); ?>" class="widefat" placeholder="https://">
	</p>
	<p class="description"><?php esc_html_e( 'La imagen de la diapositiva es la «Imagen destacada». El orden se cambia con «Orden» en Atributos de página (número más bajo, antes).', 'aranda-de-duero' ); ?></p>
	<?php
}

/**
 * Save the link.
 *
 * @param int $post_id Slide ID.
 */
function aranda_de_duero_slide_save( $post_id ) {
	if ( ! isset( $_POST['aranda_slide_link_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aranda_slide_link_nonce'] ) ), 'aranda_slide_link' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$url = isset( $_POST['aranda_slide_url'] ) ? esc_url_raw( wp_unslash( $_POST['aranda_slide_url'] ) ) : '';
	if ( '' === $url ) {
		delete_post_meta( $post_id, ARANDA_DE_DUERO_SLIDE_URL_META );
	} else {
		update_post_meta( $post_id, ARANDA_DE_DUERO_SLIDE_URL_META, $url );
	}
}
add_action( 'save_post_' . ARANDA_DE_DUERO_SLIDE_POST_TYPE, 'aranda_de_duero_slide_save' );

/**
 * Published slides that have an image, in order.
 *
 * @return array<int, array{image_id: int, title: string, url: string}>
 */
function aranda_de_duero_home_slides() {
	$posts = get_posts(
		array(
			'post_type'      => ARANDA_DE_DUERO_SLIDE_POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => 12,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
			'no_found_rows'  => true,
		)
	);

	$slides = array();
	foreach ( (array) $posts as $post ) {
		$image_id = (int) get_post_thumbnail_id( $post->ID );
		if ( $image_id <= 0 ) {
			continue;
		}

		$slides[] = array(
			'image_id' => $image_id,
			'title'    => (string) $post->post_title,
			'url'      => (string) get_post_meta( $post->ID, ARANDA_DE_DUERO_SLIDE_URL_META, true ),
		);
	}

	return $slides;
}

/**
 * Markup of the carousel for a list of slides ('' when there are none).
 *
 * The first image loads eagerly and with a high fetch priority (it is the LCP element on wide screens); the rest use
 * native lazy loading.
 *
 * @param array<int, array{image_id: int, title: string, url: string}> $slides Slides.
 * @return string
 */
function aranda_de_duero_home_carousel_html( array $slides ) {
	if ( array() === $slides ) {
		return '';
	}

	$total = count( $slides );
	$html  = '<section class="home-carousel" aria-roledescription="carousel" aria-label="' . esc_attr__( 'Destacados', 'aranda-de-duero' ) . '">';
	$html .= '<div class="home-carousel-track" tabindex="0">';

	foreach ( array_values( $slides ) as $index => $slide ) {
		$attributes = array(
			'class'    => 'home-carousel-image',
			'sizes'    => '100vw',
			'loading'  => 0 === $index ? 'eager' : 'lazy',
			'decoding' => 'async',
			'alt'      => $slide['title'],
		);
		if ( 0 === $index ) {
			$attributes['fetchpriority'] = 'high';
		}

		$image = wp_get_attachment_image( $slide['image_id'], 'full', false, $attributes );
		if ( '' === $image ) {
			continue;
		}

		if ( '' !== $slide['url'] ) {
			$image = '<a href="' . esc_url( $slide['url'] ) . '">' . $image . '</a>';
		}

		/* translators: 1: slide number, 2: total slides. */
		$label = sprintf( __( '%1$d de %2$d', 'aranda-de-duero' ), $index + 1, $total );

		$html .= '<div class="home-carousel-slide" role="group" aria-roledescription="slide" aria-label="' . esc_attr( $label ) . '">' . $image . '</div>';
	}

	$html .= '</div>';
	$html .= '<button type="button" class="home-carousel-button home-carousel-prev" aria-label="' . esc_attr__( 'Diapositiva anterior', 'aranda-de-duero' ) . '"></button>';
	$html .= '<button type="button" class="home-carousel-button home-carousel-next" aria-label="' . esc_attr__( 'Diapositiva siguiente', 'aranda-de-duero' ) . '"></button>';
	$html .= '<button type="button" class="home-carousel-button home-carousel-pause" aria-pressed="false" aria-label="' . esc_attr__( 'Pausar el cambio automático', 'aranda-de-duero' ) . '"></button>';
	$html .= '</section>';

	return $html;
}

/**
 * Load the carousel script (only where the carousel is printed).
 */
function aranda_de_duero_enqueue_home_carousel() {
	wp_enqueue_script(
		'aranda-de-duero-home-carousel',
		get_template_directory_uri() . '/js/home-carousel.js',
		array(),
		aranda_de_duero_asset_version( '/js/home-carousel.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
	wp_localize_script(
		'aranda-de-duero-home-carousel',
		'arandaCarousel',
		array(
			'pause'  => __( 'Pausar el cambio automático', 'aranda-de-duero' ),
			'resume' => __( 'Reanudar el cambio automático', 'aranda-de-duero' ),
		)
	);
}
