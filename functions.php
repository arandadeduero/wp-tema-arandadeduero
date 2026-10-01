<?php

/**
 * Aranda de Duero functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Aranda_de_Duero
 */


if ( ! defined( 'ARANDA_DE_DUERO_VERSION' ) ) {
	// Fallback version, only used if an asset file can't be found on disk.
	define( 'ARANDA_DE_DUERO_VERSION', '1.0.0' );
}

if ( ! function_exists( 'aranda_de_duero_asset_version' ) ) :
	/**
	 * Cache-busting version string for a theme asset, based on the file's
	 * own last-modified time instead of a single hardcoded version number
	 * that's easy to forget to bump after editing a file — browsers and
	 * CDNs would otherwise keep serving a stale cached copy after a change.
	 *
	 * @param string      $relative_path Path relative to $base_dir, e.g. '/css/aranda-de-duero.css'.
	 * @param string|null $base_dir Defaults to get_template_directory().
	 * @return string
	 */
	function aranda_de_duero_asset_version( $relative_path, $base_dir = null ) {
		$base_dir = $base_dir ? $base_dir : get_template_directory();
		$file     = $base_dir . $relative_path;

		return file_exists( $file ) ? (string) filemtime( $file ) : ARANDA_DE_DUERO_VERSION;
	}
endif;

if ( ! function_exists( 'aranda_de_duero_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function aranda_de_duero_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Aranda de Duero, use a find and replace
		 * to change 'aranda-de-duero' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'aranda-de-duero', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'top'    => esc_html__( 'Secondary', 'aranda-de-duero' ),
				'menu-1' => esc_html__( 'Primary', 'aranda-de-duero' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'aranda_de_duero_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Load the theme's own stylesheets inside the block editor (see add_editor_style() calls below).
		add_theme_support( 'editor-styles' );

		// Add default styling for core blocks (buttons, quotes, etc.) when used in page content.
		add_theme_support( 'wp-block-styles' );

		// Allow wide and full alignment for blocks used in page content.
		add_theme_support( 'align-wide' );

		// Make embedded content (YouTube, Twitter, etc.) responsive by default.
		add_theme_support( 'responsive-embeds' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 600,
				'width'       => 200,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'aranda_de_duero_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function aranda_de_duero_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'aranda_de_duero_content_width', 640 );
}
add_action( 'after_setup_theme', 'aranda_de_duero_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function aranda_de_duero_widgets_init() {
	// All sidebars share the same shape; only the name/id differ.
	$sidebars = array(
		'sidebar-1'            => 'Sidebar',
		'sidebar-ayuntamiento' => 'Ayuntamiento',
		'sidebar-villa'        => 'Villa',
		'sidebar-noticias'     => 'Noticias',
		'sidebar-tramites'     => 'Tramites',
		'sidebar-actualidad'   => 'Actualidad',
		'sidebar-servicios'    => 'Servicios',
	);

	foreach ( $sidebars as $sidebar_id => $sidebar_name ) {
		register_sidebar(
			array(
				// Not run through __() on purpose: these are proper-noun area names (not UI copy), and __() requires a literal string argument for translation-string extraction.
				'name'          => esc_html( $sidebar_name ),
				'id'            => $sidebar_id,
				'description'   => esc_html__( 'Add widgets here.', 'aranda-de-duero' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'aranda_de_duero_widgets_init' );

/**
 * Enqueue theme scripts and styles.
 *
 * Loads Bootstrap 5, Font Awesome, and custom theme assets with proper
 * dependencies and caching headers.
 *
 * @since 1.0.0
 * @uses wp_enqueue_style()
 * @uses wp_enqueue_script()
 *
 * @return void
 */
function aranda_de_duero_scripts() {
	// Core theme styles
	wp_enqueue_style(
		'aranda-de-duero-style',
		get_stylesheet_uri(),
		array(),
		aranda_de_duero_asset_version( '/style.css', get_stylesheet_directory() ),
		'all'
	);

	// Custom theme styles with CSS variables
	wp_enqueue_style(
		'aranda-de-duero-custom-style',
		get_template_directory_uri() . '/css/aranda-de-duero.css',
		array(),
		aranda_de_duero_asset_version( '/css/aranda-de-duero.css' ),
		'all'
	);

	// AJAX Load More specific styles
	wp_enqueue_style(
		'aranda-de-duero-ajax-load-more',
		get_template_directory_uri() . '/css/ajax-load-more.css',
		array(),
		aranda_de_duero_asset_version( '/css/ajax-load-more.css' ),
		'all'
	);

	// RTL support
	wp_style_add_data( 'aranda-de-duero-style', 'rtl', 'replace' );

	// Navigation - deferred loading for better performance
	wp_enqueue_script(
		'aranda-de-duero-navigation',
		get_template_directory_uri() . '/js/navigation.js',
		array(),
		aranda_de_duero_asset_version( '/js/navigation.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	// Main theme script - deferred for non-critical path
	wp_enqueue_script(
		'aranda-de-duero-script',
		get_template_directory_uri() . '/js/aranda-de-duero.js',
		array(),
		aranda_de_duero_asset_version( '/js/aranda-de-duero.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	// Comment reply for threaded comments
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'aranda_de_duero_scripts' );

/**
 * Enqueue Bootstrap 5 CSS.
 *
 * Loads Bootstrap 5 framework CSS from the theme's local css/ directory.
 * Self-hosted to avoid sending visitor IP addresses to third-party CDNs (GDPR).
 *
 * @since 1.0.0
 * @return void
 */
function aranda_de_duero_bootstrap_css() {
	wp_enqueue_style(
		'bootstrap_css',
		get_template_directory_uri() . '/css/bootstrap.min.css',
		array(),
		'5.3.2',
		'all'
	);
}
add_action( 'wp_enqueue_scripts', 'aranda_de_duero_bootstrap_css' );

/**
 * Enqueue Bootstrap 5 JavaScript Bundle.
 *
 * Loads Bootstrap 5 with bundled Popper.js (no jQuery required).
 * Self-hosted to avoid sending visitor IP addresses to third-party CDNs (GDPR).
 *
 * @since 1.0.0
 * @return void
 */
function aranda_de_duero_bootstrap_js() {
	wp_enqueue_script(
		'bootstrap_js',
		get_template_directory_uri() . '/js/bootstrap.bundle.min.js',
		array(),
		'5.3.2',
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'aranda_de_duero_bootstrap_js' );


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * SVG icon set (replaces Font Awesome).
 */
require get_template_directory() . '/inc/icons.php';

/**
 * Reusable block patterns for content editors.
 */
require get_template_directory() . '/inc/block-patterns.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}



/**
 * Customizer options
 */
function aranda_de_duero_customizer_settings( $wp_customize ) {

	$wp_customize->add_section(
		'aranda_de_duero_options',
		array(
			'title'       => 'Aranda de Duero',
			'description' => 'Opciones específicas para el tema Aranda de Duero',
			'priority'    => 120,
		)
	);
	// Color controls: all share the same setting/control shape, differing
	// only in slug, label and default value.
	$color_settings = array(
		'aranda_de_duero_background_color' => array(
			'label'   => 'Color de fondo',
			'default' => '#ffffff',
		),
		'aranda_de_duero_header_text_color' => array(
			'label'   => 'Color de los encabezados',
			'default' => '#000000',
		),
		'aranda_de_duero_text_color' => array(
			'label'   => 'Color del texto',
			'default' => '#000000',
		),
		'aranda_de_duero_link_color' => array(
			'label'   => 'Color de los enlaces',
			'default' => '#000000',
		),
		'aranda_de_duero_main_section_background' => array(
			'label'   => 'Color de fondo de las secciones principales en el home',
			'default' => '#000000',
		),
		'aranda_de_duero_main_section_text_color' => array(
			'label'   => 'Color de texto de las secciones principales en el home',
			'default' => '#000000',
		),
	);

	foreach ( $color_settings as $setting_id => $setting_args ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'   => $setting_args['default'],
				'transport' => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => $setting_args['label'],
					'section'  => 'aranda_de_duero_options',
					'settings' => $setting_id,
				)
			)
		);
	}

	$wp_customize->add_setting( 'aranda_de_duero_custom_script' );
	$wp_customize->add_control(
		'aranda_de_duero_custom_script',
		array(
			'type'    => 'textarea',
			'id'      => 'aranda_de_duero_custom_script',
			'label'   => 'Javascript personalizado',
			'section' => 'aranda_de_duero_options',
		)
	);
	// add a setting for front page video
	$wp_customize->add_setting( 'aranda_de_duero_front_page_video' );
	// Add a control for front page vídeo
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'aranda_de_duero_front_page_video',
			array(
				'section'   => 'aranda_de_duero_options',
				'label'     => 'Vídeo para la página de inicio',
				'mime_type' => 'video',
			)
		)
	);

	// add a setting for Default list image
	$wp_customize->add_setting( 'aranda_de_duero_default_list_image' );
	// Add a control for Default list image
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'aranda_de_duero_default_list_image',
			array(
				'section'   => 'aranda_de_duero_options',
				'label'     => 'Imagen por defecto para los listados de noticias',
				'mime_type' => 'image',
			)
		)
	);

	// add a setting for Default header image
	$wp_customize->add_setting( 'aranda_de_duero_default_header_image' );
	// Add a control for Default header image
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'aranda_de_duero_default_header_image',
			array(
				'section'   => 'aranda_de_duero_options',
				'label'     => 'Imagen por defecto para las cabeceras de página',
				'mime_type' => 'image',
			)
		)
	);

	// CheckBox Banner Plenos
	$wp_customize->add_setting(
		'aranda_de_duero_show_banner',
		array(
			'default' => '',
		)
	);

	// add control
	$wp_customize->add_control(
		'aranda_de_duero_show_banner_checkbox',
		array(
			'label'                               => 'Mostrar banner de plenos en la home page',
			'type'                                => 'checkbox',
			// this indicates the type of control
										'section' => 'aranda_de_duero_options',
			'settings'                            => 'aranda_de_duero_show_banner',
		)
	);

	// Texto Banner Plenos
	$wp_customize->add_setting(
		'aranda_de_duero_show_banner_text',
		array(
			'default' => '',
		)
	);

	// add control
	$wp_customize->add_control(
		'aranda_de_duero_show_banner_text',
		array(
			'label'                               => 'Texto a mostrar en el banner de plenos en la home page',
			'type'                                => 'text',
			// this indicates the type of control
										'section' => 'aranda_de_duero_options',
			'settings'                            => 'aranda_de_duero_show_banner_text',
		)
	);
}
add_action( 'customize_register', 'aranda_de_duero_customizer_settings' );

/**
 * Replies on frontend customizer options
 */
function aranda_de_duero_customize_css() {
	?>
	<style>
		body {
			color: <?php echo esc_attr( get_theme_mod( 'aranda_de_duero_text_color', '#000000' ) ); ?> !important;
			background-color: <?php echo esc_attr( get_theme_mod( 'aranda_de_duero_background_color', '#ffffff' ) ); ?> !important;
		}

		h1,
		h2,
		h3,
		h4,
		h5,
		h6,
		h1 a,
		h2 a,
		h3 a,
		h4 a,
		h5 a,
		h6 a {
			color: <?php echo esc_attr( get_theme_mod( 'aranda_de_duero_header_text_color', '#000000' ) ); ?> !important;
		}

		.post a:not(h1 a, h2 a, h3 a, h4 a, h5 a, h6 a) {
			color: <?php echo esc_attr( get_theme_mod( 'aranda_de_duero_link_color', '#000000' ) ); ?> !important;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'aranda_de_duero_customize_css' );

/**
 * Output custom inline JavaScript for theme features.
 *
 * Includes:
 * - Custom user scripts from theme customizer
 * - Video context menu protection
 *
 * @since 1.0.0
 * @return void
 */
function aranda_de_duero_customize_js() {
	?>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			'use strict';

			// User custom script from theme customizer
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- intentionally raw: an admin-authored JS snippet meant to run verbatim, not text content.
			echo get_theme_mod( 'aranda_de_duero_custom_script' );
			?>

			// Prevent right-click context menu on video elements
			document.querySelectorAll('video').forEach(video => {
				video.addEventListener('loadeddata', () => {
					video.addEventListener('contextmenu', (e) => {
						e.preventDefault();
						return false;
					});
				});
			});
		});
	</script>
	<?php
}
add_action( 'wp_footer', 'aranda_de_duero_customize_js' );

function aranda_de_duero_content( $type, $order = null, $order_way = null, $limit = null ) {
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$args  = array(
		'post_type'      => $type,
		'order'          => $order_way,
		'orderby'        => $order,
		'posts_per_page' => $limit,
		'paged'          => $paged,
	);
	return new WP_Query( $args );
}

/**
 * Get the permalink of a published page by its exact title.
 *
 * Replaces the deprecated get_page_by_title() with an equivalent WP_Query lookup.
 *
 * @param string $title Exact page title to search for.
 * @return string Permalink, or an empty string if no matching page is found.
 */
function aranda_de_duero_get_page_permalink_by_title( $title ) {
	$query = new WP_Query(
		array(
			'post_type'      => 'page',
			'title'          => $title,
			'post_status'    => 'publish',
			'posts_per_page' => 1,
		)
	);

	return $query->have_posts() ? get_permalink( $query->posts[0] ) : '';
}

function aranda_de_duero_temas( $slug ) {
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$args  = array(
		'post_type'      => 'post',
		'order'          => 'desc',
		'orderby'        => 'date',
		'posts_per_page' => 25,
		'paged'          => $paged,
		'tax_query'      => array(
			array(
				'taxonomy' => 'tema',
				'field'    => 'slug',
				'terms'    => $slug,
			),
		),
	);
	return new WP_Query( $args );
}

function aranda_de_duero_concejalias( $slug ) {
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$args  = array(
		'post_type'      => 'post',
		'order'          => 'desc',
		'orderby'        => 'date',
		'posts_per_page' => 25,
		'paged'          => $paged,
		'tax_query'      => array(
			array(
				'taxonomy' => 'concejalia',
				'field'    => 'slug',
				'terms'    => $slug,
			),
		),
	);
	return new WP_Query( $args );
}

/**
 * Registers an editor stylesheet for the theme.
 */
function aranda_de_duero_register_editor_stylesheet() {
	add_editor_style( 'css/aranda-de-duero.css' );
	add_editor_style( 'css/ajax-load-more.css' );
	add_editor_style( 'css/bootstrap.min.css' );
}
add_action( 'admin_init', 'aranda_de_duero_register_editor_stylesheet' );


/**
 * Get upcoming events from My Calendar plugin
 *
 * @param int $limit Maximum number of events to return (default: 50)
 * @return array Array of upcoming events sorted by date
 */
function aranda_de_duero_get_upcoming_events( $limit = 50 ) {
	global $wpdb;

	// Query events from My Calendar
	$query = aranda_de_duero_content(
		'mc-events',
		'',
		'',
		$limit
	);

	if ( ! $query->have_posts() ) {
		return array();
	}

	$upcoming_events = array();
	$current_year    = (int) current_time( 'Y' );
	$next_year       = $current_year + 1;
	$today           = strtotime( current_time( 'Y-m-d' ) );

	// Loop through all events and filter/extract data
	while ( $query->have_posts() ) {
		$query->the_post();

		// Get event metadata
		$event_data = get_post_meta( get_the_ID(), '_mc_event_data', false );

		// Skip if event data is empty
		if ( empty( $event_data ) || empty( $event_data[0] ) ) {
			continue;
		}

		$date_start = $event_data[0]['event_begin'];
		$date_end   = $event_data[0]['event_end'];

		$date_start_timestamp = strtotime( $date_start );
		$date_end_timestamp   = strtotime( $date_end );

		$event_year = (int) wp_date( 'Y', $date_start_timestamp );

		// Filter: Only show active upcoming events from current/next year
		if ( ( $event_year === $current_year || $event_year === $next_year ) &&
			$today <= $date_end_timestamp
		) {

			// Get event occurrence ID for proper linking
			$event_id = get_post_meta( get_the_ID(), '_mc_event_id', false );
			$occur_id = '';

			if ( ! empty( $event_id ) ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- My Calendar doesn't expose an API for this lookup; results are only used once per request within the loop above, so there's nothing worth caching.
				$result   = $wpdb->get_col(
					$wpdb->prepare(
						"SELECT occur_id FROM {$wpdb->prefix}my_calendar_events WHERE occur_event_id = %d",
						$event_id[0]
					)
				);
				$occur_id = ! empty( $result ) ? $result[0] : '';
			}

			// Build event array
			$event = array(
				'id'         => get_the_ID(),
				'date_start' => $date_start,
				'date_end'   => $date_end,
				'title'      => get_the_title(),
				'thumbnail'  => wp_get_attachment_url( get_post_thumbnail_id( get_the_ID(), 'thumbnail' ) ),
				'link'       => $occur_id ? get_permalink( get_the_ID() ) . '?mc_id=' . $occur_id : get_permalink( get_the_ID() ),
			);

			$upcoming_events[] = $event;
		}//end if
	}//end while

	// Reset post data
	wp_reset_postdata();

	// Sort events by start date (earliest first)
	if ( ! empty( $upcoming_events ) ) {
		$dates = array_column( $upcoming_events, 'date_start' );
		array_multisort( $dates, SORT_ASC, $upcoming_events );
	}

	return $upcoming_events;
}

/**
 * Check if a post is older than 2 years
 *
 * @return bool True if the post is more than 2 years old, false otherwise
 */
if ( ! function_exists( 'aranda_de_duero_is_post_old' ) ) {
	function aranda_de_duero_is_post_old() {
		$post_date = get_the_date( 'U' );
		// phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested -- intentional: get_the_date('U') is likewise a site-local-time value read as a timestamp, so the two stay consistent with each other; swapping to time()/gmdate() here would introduce a timezone-offset mismatch instead of fixing one.
		$current_date         = current_time( 'U' );
		$two_years_in_seconds = 2 * 365.25 * 24 * 60 * 60;

		return ( $current_date - $post_date ) > $two_years_in_seconds;
	}
}

/**
 * Display old post disclaimer
 *
 * @return void
 */
if ( ! function_exists( 'aranda_de_duero_old_post_disclaimer' ) ) {
	function aranda_de_duero_old_post_disclaimer() {
		if ( aranda_de_duero_is_post_old() ) {
			?>
			<div class="old-post-disclaimer alert alert-warning d-flex align-items-center mt-3 mb-3" role="alert">
				<span class="mr-3" style="color: #856404;">
					<?php
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
					echo aranda_de_duero_icon( 'aranda-de-duero/triangle-exclamation', array( 'size' => 21 ) );
					?>
				</span>
				<div>
					<strong><?php esc_html_e( 'Contenido antiguo', 'aranda-de-duero' ); ?>:</strong>
					<?php esc_html_e( 'Este contenido tiene más de 2 años. La información puede estar desactualizada.', 'aranda-de-duero' ); ?>
				</div>
			</div>
			<?php
		}
	}
}
