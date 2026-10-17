<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/template-functions.php';

class ComplianceTest extends TestCase {

	public function test_menu_link_without_url_gets_a_hash_href() {
		$this->assertSame( '#', aranda_de_duero_menu_link_href( array( 'title' => 'Mi ciudad' ) )['href'] );
		$this->assertSame( '#', aranda_de_duero_menu_link_href( array( 'href' => '' ) )['href'] );
	}

	public function test_menu_link_with_url_is_untouched() {
		$atts = array( 'href' => 'https://example.test/pagina/' );

		$this->assertSame( $atts, aranda_de_duero_menu_link_href( $atts ) );
	}

	public function test_whistleblower_link_is_empty_until_configured() {
		\WP_Mock::userFunction( 'get_theme_mod' )->with( 'aranda_de_duero_whistleblower_url', '' )->andReturn( '' );
		\WP_Mock::userFunction( 'esc_url_raw' )->andReturnUsing(
			function ( $url ) {
				return $url;
			}
		);

		$this->assertSame( '', aranda_de_duero_whistleblower_url() );
	}

	public function test_whistleblower_link_returns_the_configured_url() {
		\WP_Mock::userFunction( 'get_theme_mod' )->andReturn( 'https://sede.example.test/canal-informante' );
		\WP_Mock::userFunction( 'esc_url_raw' )->andReturnUsing(
			function ( $url ) {
				return $url;
			}
		);

		$this->assertSame( 'https://sede.example.test/canal-informante', aranda_de_duero_whistleblower_url() );
	}
}
