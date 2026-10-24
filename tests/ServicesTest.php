<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/template-functions.php';
require_once dirname( __DIR__ ) . '/inc/services.php';
require_once dirname( __DIR__ ) . '/inc/pharmacies.php';

// Minimal stand-in: the tests only need `instanceof WP_Post` and `post_status`.
if ( ! class_exists( 'WP_Post' ) ) {
	class WP_Post {
		public $post_status = 'publish';
	}
}

class ServicesTest extends TestCase {

	private function mocks() {
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
	}

	public function test_sede_url_is_built_on_the_configured_office() {
		$this->mocks();

		$this->assertSame(
			'https://sede.arandadeduero.es/sta/CarpetaPublic/doEvent?APP_CODE=STA&PAGE_CODE=CATALOGO',
			aranda_de_duero_sede_url( '/sta/CarpetaPublic/doEvent?APP_CODE=STA&PAGE_CODE=CATALOGO' )
		);
	}

	public function test_payments_shortcut_points_to_the_page_when_it_is_published() {
		$this->mocks();
		$page = new WP_Post();
		\WP_Mock::userFunction( 'get_page_by_path' )->with( 'pagos-y-recibos' )->andReturn( $page );
		\WP_Mock::userFunction( 'get_permalink' )->with( $page )->andReturn( 'https://www.arandadeduero.es/pagos-y-recibos/' );

		$this->assertSame( 'https://www.arandadeduero.es/pagos-y-recibos/', aranda_de_duero_payments_url() );
	}

	public function test_payments_shortcut_falls_back_to_the_gateway_without_a_page() {
		$this->mocks();
		\WP_Mock::userFunction( 'get_page_by_path' )->with( 'pagos-y-recibos' )->andReturn( null );

		$this->assertStringEndsWith( 'PAGE_CODE=FRONTAL_SPT', aranda_de_duero_payments_url() );
	}

	public function test_payments_shortcut_ignores_a_draft_page() {
		$this->mocks();
		$page              = new WP_Post();
		$page->post_status = 'draft';
		\WP_Mock::userFunction( 'get_page_by_path' )->andReturn( $page );

		$this->assertStringEndsWith( 'PAGE_CODE=FRONTAL_SPT', aranda_de_duero_payments_url() );
	}

	public function test_every_quick_access_has_title_text_and_a_secure_url() {
		$this->mocks();
		\WP_Mock::userFunction( 'get_page_by_path' )->andReturn( null );

		$items = aranda_de_duero_quick_access();

		$this->assertCount( 8, $items );
		foreach ( $items as $item ) {
			$this->assertNotSame( '', $item['title'] );
			$this->assertNotSame( '', $item['text'] );
			$this->assertStringStartsWith( 'https://', $item['url'] );
			$this->assertIsBool( $item['new_tab'] );
		}
	}

	public function test_the_pharmacy_card_only_appears_when_its_page_is_published() {
		$this->mocks();
		$page              = new WP_Post();
		$page->post_status = 'publish';
		\WP_Mock::userFunction( 'get_page_by_path' )->andReturnUsing(
			function ( $path ) use ( $page ) {
				return 'farmacia-de-guardia' === $path ? $page : null;
			}
		);
		\WP_Mock::userFunction( 'get_permalink' )->andReturn( 'https://www.arandadeduero.es/farmacia-de-guardia/' );
		\WP_Mock::userFunction( 'get_template_directory' )->andReturn( dirname( __DIR__ ) );
		\WP_Mock::userFunction( 'sanitize_text_field' )->andReturnArg( 0 );
		\WP_Mock::userFunction( 'wp_unslash' )->andReturnArg( 0 );
		// Before ten in the morning it is still the guard of the day before.
		\WP_Mock::userFunction( 'current_time' )->andReturn( '2026-10-25 09:30:00' );

		$items = aranda_de_duero_quick_access();

		$this->assertCount( 9, $items );
		$this->assertSame( 'Farmacia de guardia', $items[2]['title'] );
		$this->assertSame( 'https://www.arandadeduero.es/farmacia-de-guardia/', $items[2]['url'] );
		$this->assertSame( 'INÉS BRIME BERDUGO', $items[2]['pharmacy']['name'] );
		$this->assertSame( 'Avenida Castilla, 58', $items[2]['pharmacy']['address'] );
		$this->assertSame( '947 501 237', $items[2]['pharmacy']['phone'] );
		$this->assertSame( 'tel:947501237', $items[2]['pharmacy']['phone_href'] );
		$this->assertSame( 'Google Maps', $items[2]['pharmacy']['map']['label'] );
	}

	public function test_without_a_calendar_the_pharmacy_card_is_a_plain_link() {
		$this->mocks();
		$page              = new WP_Post();
		$page->post_status = 'publish';
		\WP_Mock::userFunction( 'get_page_by_path' )->andReturnUsing(
			function ( $path ) use ( $page ) {
				return 'farmacia-de-guardia' === $path ? $page : null;
			}
		);
		\WP_Mock::userFunction( 'get_permalink' )->andReturn( 'https://www.arandadeduero.es/farmacia-de-guardia/' );
		\WP_Mock::userFunction( 'get_template_directory' )->andReturn( dirname( __DIR__ ) );
		\WP_Mock::userFunction( 'current_time' )->andReturn( '2098-06-01 12:00:00' );

		$items = aranda_de_duero_quick_access();

		$this->assertSame( 'Farmacia de guardia', $items[2]['title'] );
		$this->assertArrayNotHasKey( 'pharmacy', $items[2] );
	}

	public function test_every_payment_service_has_title_text_and_a_secure_url() {
		$this->mocks();

		$groups = aranda_de_duero_payment_services();

		$this->assertNotEmpty( $groups );
		foreach ( $groups as $group ) {
			$this->assertNotSame( '', $group['title'] );
			$this->assertNotEmpty( $group['items'] );
			foreach ( $group['items'] as $item ) {
				$this->assertNotSame( '', $item['title'] );
				$this->assertNotSame( '', $item['text'] );
				$this->assertStringStartsWith( 'https://', $item['url'] );
			}
		}
	}
}
