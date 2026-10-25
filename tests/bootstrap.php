<?php
/**
 * PHPUnit bootstrap: WP_Mock stands in for WordPress, so no WP install is needed.
 */

define( 'ABSPATH', dirname( __DIR__ ) . '/' );

// WordPress core constants used by the theme.
define( 'DAY_IN_SECONDS', 86400 );
define( 'WEEK_IN_SECONDS', 604800 );
define( 'HOUR_IN_SECONDS', 3600 );
define( 'MINUTE_IN_SECONDS', 60 );
define( 'OBJECT', 'OBJECT' );

require_once dirname( __DIR__ ) . '/vendor/autoload.php';

WP_Mock::bootstrap();

/**
 * WordPress functions most tests need, stubbed the same way everywhere. Call it before a test's own stubs: with WP_Mock
 * the first matching stub wins.
 */
trait Aranda_WP_Stubs {

	protected function stub_wp_basics() {
		\WP_Mock::userFunction( 'apply_filters' )->andReturnUsing(
			function ( $tag, $value ) {
				return $value;
			}
		);
		\WP_Mock::userFunction( '__' )->andReturnUsing(
			function ( $text ) {
				return $text;
			}
		);
		\WP_Mock::userFunction( 'home_url' )->andReturnUsing(
			function ( $path = '' ) {
				return 'https://www.arandadeduero.es' . $path;
			}
		);
		\WP_Mock::userFunction( 'wp_parse_url' )->andReturnUsing(
			function ( $url, $component = -1 ) {
				return parse_url( $url, $component );
			}
		);
		// Same effect as WordPress' remove_accents() for the Spanish accents.
		\WP_Mock::userFunction( 'remove_accents' )->andReturnUsing(
			function ( $text ) {
				return strtr( $text, array( 'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U', 'ñ' => 'n' ) );
			}
		);
	}
}

// Stand-in for WordPress' WP_Post: the tests need `instanceof WP_Post` and these properties (declared, since PHP 8.2
// deprecates creating them on the fly).
class WP_Post {
	public $ID          = 0;
	public $post_title  = '';
	public $post_status = 'publish';
	public $post_name   = '';
}
