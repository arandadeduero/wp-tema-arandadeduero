<?php
/**
 * Aranda de Duero functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Aranda_de_Duero
 */
 
 		// Desactivamos el editor Gutenberg.
 add_filter('use_block_editor_for_post', '__return_false', 10);

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

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
				'top' => esc_html__( 'Secondary', 'aranda-de-duero' ),
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

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
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
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'aranda-de-duero' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'aranda-de-duero' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	// Sidebar para Ayuntamiento 
	register_sidebar(
		array(
			'name'          => esc_html__( 'Ayuntamiento', 'aranda-de-duero' ),
			'id'            => 'sidebar-ayuntamiento',
			'description'   => esc_html__( 'Add widgets here.', 'aranda-de-duero' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	// Sidebar para Villa de Aranda 
	register_sidebar(
		array(
			'name'          => esc_html__( 'Villa', 'aranda-de-duero' ),
			'id'            => 'sidebar-villa',
			'description'   => esc_html__( 'Add widgets here.', 'aranda-de-duero' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	// Sidebar para Noticias
	register_sidebar(
		array(
			'name'          => esc_html__( 'Noticias', 'aranda-de-duero' ),
			'id'            => 'sidebar-noticias',
			'description'   => esc_html__( 'Add widgets here.', 'aranda-de-duero' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	// Sidebar para Tramites
	register_sidebar(
		array(
			'name'          => esc_html__( 'Tramites', 'aranda-de-duero' ),
			'id'            => 'sidebar-tramites',
			'description'   => esc_html__( 'Add widgets here.', 'aranda-de-duero' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	// Sidebar para Tramites
	register_sidebar(
		array(
			'name'          => esc_html__( 'tramite', 'aranda-de-duero' ),
			'id'            => 'sidebar-tramite',
			'description'   => esc_html__( 'Add widgets here.', 'aranda-de-duero' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);	

	register_sidebar(
		array(
			'name'          => esc_html__( 'Actualidad', 'aranda-de-duero' ),
			'id'            => 'sidebar-actualidad',
			'description'   => esc_html__( 'Add widgets here.', 'aranda-de-duero' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Servicios', 'aranda-de-duero' ),
			'id'            => 'sidebar-servicios',
			'description'   => esc_html__( 'Add widgets here.', 'aranda-de-duero' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);


}
add_action( 'widgets_init', 'aranda_de_duero_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function aranda_de_duero_scripts() {
	wp_enqueue_style( 'aranda-de-duero-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_enqueue_style( 'aranda-de-duero-custom-style', get_template_directory_uri().'/css/aranda-de-duero.css', array(), _S_VERSION );
	wp_style_add_data( 'aranda-de-duero-style', 'rtl', 'replace' );

	wp_enqueue_script( 'aranda-de-duero-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'aranda-de-duero-script', get_template_directory_uri() . '/js/aranda-de-duero.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'aranda_de_duero_scripts' );

function bootstrap_css() {
	wp_enqueue_style( 'bootstrap_css', 
  					'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css', 
  					array(), 
  					'4.1.3'
  					); 
}
add_action( 'wp_enqueue_scripts', 'bootstrap_css');

function bootstrap_js() {
	wp_enqueue_script( 'bootstrap_js', 
  					'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js', 
  					array('jquery','popper_js'), 
  					'4.1.3', 
					  true); 
	wp_enqueue_script( 'popper_js', 
  					'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js', 
  					array(), 
  					'1.14.3', 
  					true); 
}
add_action( 'wp_enqueue_scripts', 'bootstrap_js');


// Carga de Font Awesome
function font_awesome_script() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css', array());
}
add_action('wp_enqueue_scripts','font_awesome_script');


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

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
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}

/**
 * Customizer options
 */

function aranda_de_duero_customizer_settings($wp_customize) {
	
		$wp_customize->add_section('aranda_de_duero_options', array(
			'title' => 'Aranda de Duero',
			'description' => 'Opciones específicas para el tema Aranda de Duero',
			'priority' => 120,
		));
		// add a setting for background color
		$wp_customize->add_setting('aranda_de_duero_background_color', array(
			'default'     => '#ffffff',
			'transport'   => 'refresh',
		) );
		// Add a control select background color
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aranda_de_duero_background_color',
		array(
		'label' => 'Color de fondo',
		'section' => 'aranda_de_duero_options',
		'settings' => 'aranda_de_duero_background_color',
		) ) );

		// add a setting for the header text color
		$wp_customize->add_setting('aranda_de_duero_header_text_color', array(
			'default'     => '#000000',
			'transport'   => 'refresh',
		) );
		// Add a control select header text color
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aranda_de_duero_header_text_color',
		array(
		'label' => 'Color de los encabezados',
		'section' => 'aranda_de_duero_options',
		'settings' => 'aranda_de_duero_header_text_color',
		) ) );

		// add a setting for text color
		$wp_customize->add_setting('aranda_de_duero_text_color', array(
			'default'     => '#000000',
			'transport'   => 'refresh',
		) );
		// Add a control select text color
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aranda_de_duero_text_color',
		array(
		'label' => 'Color del texto',
		'section' => 'aranda_de_duero_options',
		'settings' => 'aranda_de_duero_text_color',
		) ) );

		// Add a control select header text color
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aranda_de_duero_header_text_color',
		array(
		'label' => 'Color de los encabezados',
		'section' => 'aranda_de_duero_options',
		'settings' => 'aranda_de_duero_header_text_color',
		) ) );

		// add a setting for link color
		$wp_customize->add_setting('aranda_de_duero_link_color', array(
			'default'     => '#000000',
			'transport'   => 'refresh',
		) );
		// Add a control select link color
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aranda_de_duero_link_color',
		array(
		'label' => 'Color de los enlaces',
		'section' => 'aranda_de_duero_options',
		'settings' => 'aranda_de_duero_link_color',
		) ) );

		$wp_customize->add_setting( 'aranda_de_duero_custom_script' );
    	$wp_customize->add_control( 'aranda_de_duero_custom_script', array(
		'type' => 'textarea',	
        'id'=> 'aranda_de_duero_custom_script',
        'label' => 'Javascript personalizado',
        'section' => 'aranda_de_duero_options'
    	) );
		// add a setting for front page video
		$wp_customize->add_setting('aranda_de_duero_front_page_video');
		//Add a control for front page vídeo
		$wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aranda_de_duero_front_page_video', array(
			'section' => 'aranda_de_duero_options',
			'label' => 'Vídeo para la página de inicio',
			'mime_type' => 'video'
		)));

		// add a setting for main sections background color
		$wp_customize->add_setting('aranda_de_duero_main_section_background', array(
			'default'     => '#000000',
			'transport'   => 'refresh',
		) );
		// Add a control select main sections background color
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aranda_de_duero_main_section_background',
		array(
		'label' => 'Color de fondo de las secciones principales en el home',
		'section' => 'aranda_de_duero_options',
		'settings' => 'aranda_de_duero_main_section_background',
		) ) );

		// add a setting for main sections text color
		$wp_customize->add_setting('aranda_de_duero_main_section_text_color', array(
			'default'     => '#000000',
			'transport'   => 'refresh',
		) );
		// Add a control select main sections text color
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aranda_de_duero_main_section_text_color',
		array(
		'label' => 'Color de texto de las secciones principales en el home',
		'section' => 'aranda_de_duero_options',
		'settings' => 'aranda_de_duero_main_section_text_color',
		) ) );

		// add a setting for Default list image
		$wp_customize->add_setting('aranda_de_duero_default_list_image');
		//Add a control for Default list image
		$wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aranda_de_duero_default_list_image', array(
			'section' => 'aranda_de_duero_options',
			'label' => 'Imagen por defecto para los listados de noticias',
			'mime_type' => 'image'
		)));

		// add a setting for Default header image
		$wp_customize->add_setting('aranda_de_duero_default_header_image');
		//Add a control for Default header image
		$wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aranda_de_duero_default_header_image', array(
			'section' => 'aranda_de_duero_options',
			'label' => 'Imagen por defecto para las cabeceras de página',
			'mime_type' => 'image'
		)));

		//CheckBox Banner Plenos
		$wp_customize->add_setting( 'aranda_de_duero_show_banner', array(
			'default' => '',
		));

		//add control
		$wp_customize->add_control( 'aranda_de_duero_show_banner_checkbox', array(
					'label' => 'Mostrar banner de plenos en la home page',
					'type'  => 'checkbox', // this indicates the type of control
					'section' => 'aranda_de_duero_options',
					'settings' => 'aranda_de_duero_show_banner'
		));

		//Texto Banner Plenos
		$wp_customize->add_setting( 'aranda_de_duero_show_banner_text', array(
			'default' => '',
		));

		//add control
		$wp_customize->add_control( 'aranda_de_duero_show_banner_text', array(
					'label' => 'Texto a mostrar en el banner de plenos en la home page',
					'type'  => 'text', // this indicates the type of control
					'section' => 'aranda_de_duero_options',
					'settings' => 'aranda_de_duero_show_banner_text'
		));
	}
add_action('customize_register', 'aranda_de_duero_customizer_settings');

/**
 * Replies on frontend customizer options
 */
function aranda_de_duero_customize_css()
{
    ?>
         <style>
		 	body {
				 color: <?php echo get_theme_mod('aranda_de_duero_text_color', '#000000'); ?> !important;
				 background-color: <?php echo get_theme_mod('aranda_de_duero_background_color', '#ffffff'); ?> !important;
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
				 color: <?php echo get_theme_mod('aranda_de_duero_header_text_color', '#000000'); ?>!important;
				}
			.post a:not(h1 a,h2 a, h3 a, h4 a, h5 a, h6 a) {
				 color: <?php echo get_theme_mod('aranda_de_duero_link_color', '#000000'); ?> !important;
			 }
         </style>
    <?php
}
add_action( 'wp_head', 'aranda_de_duero_customize_css');

function aranda_de_duero_customize_js()
{
    ?>
       <script>
	   		jQuery( document ).ready(function() {
				<?php echo get_theme_mod('aranda_de_duero_custom_script'); ?>
				jQuery('video').on("loadeddata", function() {
				jQuery('video').bind('contextmenu',function() { return false; });
				
				}); 
			});
	   		
	   </script>
    <?php
}
add_action( 'wp_footer', 'aranda_de_duero_customize_js');

function aranda_de_duero_content($type, $order = null, $order_way = null, $limit = null)
{
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$args = array(
        'post_type' => $type,
        'order' => $order_way,
        'orderby' => $order,
        'posts_per_page' => $limit,
		'paged' => $paged,
        );
	return new WP_Query( $args );
}
function aranda_de_duero_temas($slug)
{
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$args = array(
        'post_type' => 'post',
        'order' => 'desc',
        'orderby' => 'date',
        'posts_per_page' => 25,
		'paged' => $paged,
		'tax_query' => array(
			array(
			'taxonomy' => 'tema',
			'field'    => 'slug',
			'terms' => $slug,
			 )
		  )
        );
	return new WP_Query( $args );
}

function aranda_de_duero_concejalias($slug)
{
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$args = array(
        'post_type' => 'post',
        'order' => 'desc',
        'orderby' => 'date',
        'posts_per_page' => 25,
		'paged' => $paged,
		'tax_query' => array(
			array(
			'taxonomy' => 'concejalia',
			'field'    => 'slug',
			'terms' => $slug,
			 )
		  )
        );
	return new WP_Query( $args );
}

/**
 * Registers an editor stylesheet for the theme.
 */
 function register_editor_stylesheet() {
    add_editor_style( 'css/aranda-de-duero.css' );
    add_editor_style( 'css/ajax-load-more.css' );
    add_editor_style( 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css' );
}
add_action( 'admin_init', 'register_editor_stylesheet' );

/** 
 * Cambiar entradas por noticias
*/
function revcon_change_post_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'Noticias';
    $submenu['edit.php'][5][0] = 'Noticias';
    $submenu['edit.php'][10][0] = 'Añadir Noticia';
    $submenu['edit.php'][16][0] = 'Etiquetas';
}
function revcon_change_post_object() {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'Noticias';
    $labels->singular_name = 'Noticia';
    $labels->add_new = 'Añadir Noticia';
    $labels->add_new_item = 'Añadir Noticia';
    $labels->edit_item = 'Editar Noticia';
    $labels->new_item = 'Noticia';
    $labels->view_item = 'Ver Noticias';
    $labels->search_items = 'Buscar Noticias';
    $labels->not_found = 'No se han encontrado noticias';
    $labels->not_found_in_trash = 'No hay Noticias en la Papelera';
    $labels->all_items = 'Todas las Noticias';
    $labels->menu_name = 'Noticias';
    $labels->name_admin_bar = 'Noticias';
}
 
add_action( 'admin_menu', 'revcon_change_post_label' );
add_action( 'init', 'revcon_change_post_object' );
