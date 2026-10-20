<?php
defined( 'ABSPATH' ) || exit;

/**
 * Home-page carousel owned by the theme (replaces Smart Slider 3).
 *
 * Slides are a small private post type, "Portada: diapositivas": featured image, title (used as the image's
 * alternative text when it has none), optional link, optional MP4 video chosen from the media library and the order
 * of the "Orden" attribute. The markup is a scroll-snap strip (css/aranda-de-duero.css) with some JS for the arrows,
 * the autoplay and the videos (js/home-carousel.js). While there are no slides, page-home.php still prints the Smart
 * Slider.
 *
 * A video goes on top of the slide's image, which is always there: it is what the page shows first (and the LCP
 * element), what visitors see without JavaScript, with reduced motion, on a data saver or when a browser refuses to
 * autoplay. The video has no `src` in the HTML; the script loads the first one after the page has loaded and the next
 * one only when the current one starts to play, so five videos are never fetched at once.
 *
 * @package Aranda_de_Duero
 */

const ARANDA_DE_DUERO_SLIDE_POST_TYPE  = 'portada_slide';
const ARANDA_DE_DUERO_SLIDE_URL_META   = '_aranda_slide_url';
const ARANDA_DE_DUERO_SLIDE_VIDEO_META = '_aranda_slide_video';

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
		__( 'Enlace y vídeo de la diapositiva', 'aranda-de-duero' ),
		'aranda_de_duero_slide_meta_box_html',
		ARANDA_DE_DUERO_SLIDE_POST_TYPE,
		'normal'
	);
}
add_action( 'add_meta_boxes', 'aranda_de_duero_slide_meta_box' );

/**
 * Print the link and video fields.
 *
 * @param WP_Post $post Slide being edited.
 */
function aranda_de_duero_slide_meta_box_html( $post ) {
	wp_nonce_field( 'aranda_slide_link', 'aranda_slide_link_nonce' );
	$url   = (string) get_post_meta( $post->ID, ARANDA_DE_DUERO_SLIDE_URL_META, true );
	$video = (int) get_post_meta( $post->ID, ARANDA_DE_DUERO_SLIDE_VIDEO_META, true );
	?>
	<p>
		<label for="aranda-slide-url"><?php esc_html_e( 'Dirección a la que lleva al pulsar (opcional):', 'aranda-de-duero' ); ?></label>
		<input type="url" id="aranda-slide-url" name="aranda_slide_url" value="<?php echo esc_attr( $url ); ?>" class="widefat" placeholder="https://">
	</p>
	<p class="description"><?php esc_html_e( 'La imagen de la diapositiva es la «Imagen destacada». El orden se cambia con «Orden» en Atributos de página (número más bajo, antes).', 'aranda-de-duero' ); ?></p>
	<p>
		<label for="aranda-slide-video"><?php esc_html_e( 'Vídeo de la diapositiva (opcional):', 'aranda-de-duero' ); ?></label>
		<select id="aranda-slide-video" name="aranda_slide_video" class="widefat">
			<option value="0"><?php esc_html_e( 'Sin vídeo (solo la imagen)', 'aranda-de-duero' ); ?></option>
			<?php foreach ( aranda_de_duero_slide_video_choices() as $video_id => $video_title ) : ?>
				<option value="<?php echo esc_attr( (string) $video_id ); ?>" <?php selected( $video_id, $video ); ?>><?php echo esc_html( $video_title ); ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<p class="description"><?php esc_html_e( 'Sube antes el vídeo MP4 en Medios → Añadir nuevo y aparecerá aquí. Se reproduce solo, sin sonido, sobre la imagen destacada, que se sigue viendo mientras carga y a quien no pueda reproducirlo; el carrusel pasa a la siguiente diapositiva cuando acaba. Conviene que pese pocos MB.', 'aranda-de-duero' ); ?></p>
	<?php
}

/**
 * MP4 videos of the media library that a slide can use, newest first.
 *
 * @return array<int, string> Attachment ID => title.
 */
function aranda_de_duero_slide_video_choices() {
	$videos  = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_mime_type' => 'video/mp4',
			'post_status'    => 'inherit',
			'posts_per_page' => 100,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
		)
	);
	$choices = array();
	foreach ( (array) $videos as $video ) {
		$choices[ (int) $video->ID ] = '' !== $video->post_title ? (string) $video->post_title : basename( (string) get_attached_file( $video->ID ) );
	}

	return $choices;
}

/**
 * Address of the MP4 video chosen for a slide, '' when there is none or it is no longer an MP4 of the media library.
 *
 * @param int $post_id Slide ID.
 * @return string
 */
function aranda_de_duero_slide_video_url( $post_id ) {
	$video_id = (int) get_post_meta( $post_id, ARANDA_DE_DUERO_SLIDE_VIDEO_META, true );
	if ( $video_id <= 0 || 'video/mp4' !== get_post_mime_type( $video_id ) ) {
		return '';
	}

	return (string) wp_get_attachment_url( $video_id );
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

	$video_id = isset( $_POST['aranda_slide_video'] ) ? absint( wp_unslash( $_POST['aranda_slide_video'] ) ) : 0;
	if ( $video_id > 0 && 'video/mp4' === get_post_mime_type( $video_id ) ) {
		update_post_meta( $post_id, ARANDA_DE_DUERO_SLIDE_VIDEO_META, $video_id );
	} else {
		delete_post_meta( $post_id, ARANDA_DE_DUERO_SLIDE_VIDEO_META );
	}
}
add_action( 'save_post_' . ARANDA_DE_DUERO_SLIDE_POST_TYPE, 'aranda_de_duero_slide_save' );

/**
 * Published slides that have an image, in order.
 *
 * @return array<int, array{image_id: int, title: string, url: string, video_url: string}>
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
			'image_id'  => $image_id,
			'title'     => (string) $post->post_title,
			'url'       => (string) get_post_meta( $post->ID, ARANDA_DE_DUERO_SLIDE_URL_META, true ),
			'video_url' => aranda_de_duero_slide_video_url( $post->ID ),
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
 * @param array<int, array{image_id: int, title: string, url: string, video_url?: string}> $slides Slides.
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

		// The video is decorative (the image says the same) and has no src: js/home-carousel.js loads and plays it.
		if ( ! empty( $slide['video_url'] ) ) {
			$image .= '<video class="home-carousel-video" muted playsinline preload="none" aria-hidden="true" tabindex="-1" data-src="' . esc_url( $slide['video_url'] ) . '"></video>';
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
