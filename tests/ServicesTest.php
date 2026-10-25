<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/template-functions.php';
require_once dirname( __DIR__ ) . '/inc/services.php';

class ServicesTest extends TestCase {

	use Aranda_WP_Stubs;

	private function mocks() {
		$this->stub_wp_basics();
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
		\WP_Mock::userFunction( 'get_page_by_path' )->with( 'pagos-y-recibos', OBJECT, 'page' )->andReturn( $page );
		\WP_Mock::userFunction( 'get_permalink' )->with( $page )->andReturn( 'https://www.arandadeduero.es/pagos-y-recibos/' );

		$this->assertSame( 'https://www.arandadeduero.es/pagos-y-recibos/', aranda_de_duero_payments_url() );
	}

	public function test_payments_shortcut_falls_back_to_the_gateway_without_a_page() {
		$this->mocks();
		\WP_Mock::userFunction( 'get_page_by_path' )->with( 'pagos-y-recibos', OBJECT, 'page' )->andReturn( null );

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

		// Farmacia de guardia, recogida de residuos, OMIC, objetos perdidos and the bus moved to «Servicios cotidianos»
		// (tests/EverydayServicesTest.php): this list is only the town-hall procedures.
		$this->assertCount( 8, $items );
		foreach ( $items as $item ) {
			$this->assertNotSame( '', $item['title'] );
			$this->assertNotSame( '', $item['text'] );
			$this->assertStringStartsWith( 'https://', $item['url'] );
			$this->assertIsBool( $item['new_tab'] );
		}
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
