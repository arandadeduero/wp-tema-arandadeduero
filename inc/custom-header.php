<?php
/**
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
	<?php the_header_image_tag(); ?>
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package Aranda_de_Duero
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses aranda_de_duero_header_style()
 */
function aranda_de_duero_custom_header_setup() {
	add_theme_support(
		'custom-header',
		apply_filters(
			'aranda_de_duero_custom_header_args',
			array(
				'default-image'      => '',
				'default-text-color' => '000000',
				'width'              => 1000,
				'height'             => 250,
				'flex-height'        => true,
				'wp-head-callback'   => 'aranda_de_duero_header_style',
			)
		)
	);
}
add_action( 'after_setup_theme', 'aranda_de_duero_custom_header_setup' );

if ( ! function_exists( 'aranda_de_duero_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see aranda_de_duero_custom_header_setup().
	 */
	function aranda_de_duero_header_style() {
		$header_text_color = get_header_textcolor();

		/*
		 * If no custom options for text are set, let's bail.
		 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
		 */
		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		// Hidden text: nothing to print, the `header-text-hidden` body class does it (css/aranda-de-duero.css).
		if ( ! display_header_text() ) {
			return;
		}

		// Custom colour: only the value is printed; the rule that uses it is in css/aranda-de-duero.css.
		?>
		<style>
			:root {
				--aranda-site-title-color: #<?php echo esc_attr( $header_text_color ); ?>;
			}
		</style>
		<?php
	}
endif;

/**
 * Tells the stylesheet whether the site title/description are hidden or have a custom colour.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function aranda_de_duero_header_text_body_classes( $classes ) {
	if ( get_theme_support( 'custom-header', 'default-text-color' ) === get_header_textcolor() ) {
		return $classes;
	}

	$classes[] = display_header_text() ? 'has-custom-header-text-color' : 'header-text-hidden';

	return $classes;
}
add_filter( 'body_class', 'aranda_de_duero_header_text_body_classes' );
