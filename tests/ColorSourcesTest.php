<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/customizer-css.php';

/**
 * The colours live as CSS variables in the `:root` of css/aranda-de-duero.css. The few places that cannot read a variable
 * (PHP defaults, the `theme-color` meta tag) must agree with them, and no variable may be used without being defined.
 */
class ColorSourcesTest extends TestCase {

	private function css( $file ) {
		return (string) file_get_contents( dirname( __DIR__ ) . '/' . $file );
	}

	/**
	 * Custom properties declared in the `:root` block.
	 *
	 * @return array<string, string>
	 */
	private function root_variables() {
		preg_match( '/:root\s*\{(.*?)\n\}/s', $this->css( 'css/aranda-de-duero.css' ), $block );
		preg_match_all( '/(--[\w-]+)\s*:\s*([^;]+);/', $block[1], $pairs, PREG_SET_ORDER );

		$variables = array();
		foreach ( $pairs as $pair ) {
			$variables[ $pair[1] ] = trim( $pair[2] );
		}

		return $variables;
	}

	/**
	 * `#fff` and `#ffffff` are the same colour.
	 *
	 * @param string $hex Hexadecimal colour.
	 * @return string
	 */
	private function full_hex( $hex ) {
		$hex = strtolower( $hex );

		return 4 === strlen( $hex ) ? '#' . $hex[1] . $hex[1] . $hex[2] . $hex[2] . $hex[3] . $hex[3] : $hex;
	}

	public function test_the_theme_color_meta_is_the_brand_colour_of_the_css() {
		$root = $this->root_variables();

		$this->assertSame( $this->full_hex( $root['--color-primary'] ), $this->full_hex( aranda_de_duero_brand_color() ) );
	}

	public function test_the_customizer_defaults_match_the_text_and_background_of_the_css() {
		$root     = $this->root_variables();
		$defaults = aranda_de_duero_customizer_color_defaults();

		$this->assertSame( $this->full_hex( $root['--color-bg'] ), $defaults['aranda_de_duero_background_color'] );
		foreach ( array( 'aranda_de_duero_text_color', 'aranda_de_duero_header_text_color', 'aranda_de_duero_link_color', 'aranda_de_duero_main_section_text_color' ) as $setting ) {
			$this->assertSame( $this->full_hex( $root['--color-text'] ), $defaults[ $setting ], $setting );
		}
	}

	public function test_every_default_is_a_full_hexadecimal_colour() {
		foreach ( aranda_de_duero_customizer_color_defaults() as $setting => $color ) {
			$this->assertSame( 1, preg_match( '/^#[0-9a-f]{6}$/', $color ), $setting );
		}
	}

	public function test_no_colour_variable_is_used_without_being_defined() {
		$defined = array_keys( $this->root_variables() );

		foreach ( array( 'css/aranda-de-duero.css', 'style.css', 'style-rtl.css' ) as $file ) {
			preg_match_all( '/var\(\s*(--(?:color|gray)-[\w-]+)/', $this->css( $file ), $used );
			foreach ( array_unique( $used[1] ) as $name ) {
				$this->assertContains( $name, $defined, $file . ' usa ' . $name . ' sin definirla en :root' );
			}
		}
	}

	public function test_the_palette_names_are_unique() {
		preg_match( '/:root\s*\{(.*?)\n\}/s', $this->css( 'css/aranda-de-duero.css' ), $block );
		preg_match_all( '/^\s*(--[\w-]+)\s*:/m', $block[1], $names );

		$this->assertSame( $names[1], array_values( array_unique( $names[1] ) ) );
	}
}
