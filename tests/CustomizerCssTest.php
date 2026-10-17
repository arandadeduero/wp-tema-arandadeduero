<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/customizer-css.php';
require_once dirname( __DIR__ ) . '/inc/custom-header.php';

class CustomizerCssTest extends TestCase {

	public function test_exposes_every_customizer_colour_as_a_custom_property_with_its_default() {
		\WP_Mock::userFunction( 'get_theme_mod' )->andReturnUsing(
			function ( $name, $default = false ) {
				return $default;
			}
		);

		$this->assertSame(
			array(
				'--aranda-text-color'              => '#000000',
				'--aranda-background-color'        => '#ffffff',
				'--aranda-header-text-color'       => '#000000',
				'--aranda-link-color'              => '#000000',
				'--aranda-main-section-text-color' => '#000000',
			),
			aranda_de_duero_customizer_css_variables()
		);
	}

	public function test_uses_the_colour_saved_in_the_customizer() {
		\WP_Mock::userFunction( 'get_theme_mod' )->andReturnUsing(
			function ( $name, $default = false ) {
				return 'aranda_de_duero_link_color' === $name ? '#005b99' : $default;
			}
		);

		$variables = aranda_de_duero_customizer_css_variables();

		$this->assertSame( '#005b99', $variables['--aranda-link-color'] );
		$this->assertSame( '#000000', $variables['--aranda-text-color'] );
	}

	private function header( $text_color, $displayed ) {
		\WP_Mock::userFunction( 'get_theme_support' )->with( 'custom-header', 'default-text-color' )->andReturn( '000000' );
		\WP_Mock::userFunction( 'get_header_textcolor' )->andReturn( $text_color );
		\WP_Mock::userFunction( 'display_header_text' )->andReturn( $displayed );
	}

	public function test_header_text_keeps_the_body_classes_when_it_is_the_default() {
		$this->header( '000000', true );

		$this->assertSame( array( 'home' ), aranda_de_duero_header_text_body_classes( array( 'home' ) ) );
	}

	public function test_hidden_header_text_adds_the_hidden_class() {
		$this->header( 'blank', false );

		$this->assertSame( array( 'home', 'header-text-hidden' ), aranda_de_duero_header_text_body_classes( array( 'home' ) ) );
	}

	public function test_custom_header_text_colour_adds_its_class() {
		$this->header( 'ff0000', true );

		$this->assertSame( array( 'has-custom-header-text-color' ), aranda_de_duero_header_text_body_classes( array() ) );
	}
}
