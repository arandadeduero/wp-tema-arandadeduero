<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/template-functions.php';
require_once dirname( __DIR__ ) . '/inc/pharmacies.php';
require_once dirname( __DIR__ ) . '/inc/waste.php';
require_once dirname( __DIR__ ) . '/inc/everyday-services.php';

class EverydayServicesTest extends TestCase {

	use Aranda_WP_Stubs;

	private function mocks( array $published = array() ) {
		$this->stub_wp_basics();
		\WP_Mock::userFunction( 'get_page_by_path' )->andReturnUsing(
			function ( $path ) use ( $published ) {
				if ( ! in_array( $path, $published, true ) ) {
					return null;
				}
				$page              = new WP_Post();
				$page->post_status = 'publish';
				$page->post_name   = $path;

				return $page;
			}
		);
		\WP_Mock::userFunction( 'get_permalink' )->andReturnUsing(
			function ( $post ) {
				return 'https://www.arandadeduero.es/' . $post->post_name . '/';
			}
		);
	}

	public function test_the_bus_is_the_only_one_without_a_page_and_always_appears() {
		$this->mocks();

		$items = aranda_de_duero_everyday_services();

		$this->assertCount( 1, $items );
		$this->assertSame( 'Transporte: autobús urbano', $items[0]['title'] );
		$this->assertSame( 'https://busurbanoaranda.com/', $items[0]['url'] );
		$this->assertTrue( $items[0]['new_tab'] );
	}

	public function test_the_bus_leads_to_its_service_once_published() {
		$this->mocks( array( 'transporte-urbano' ) );

		$items = aranda_de_duero_everyday_services();

		$this->assertSame( 'https://www.arandadeduero.es/transporte-urbano/', $items[0]['url'] );
		$this->assertFalse( $items[0]['new_tab'] );
	}

	public function test_every_page_appears_once_published_in_order_with_the_bus_last() {
		$this->mocks( array( 'farmacia-de-guardia', 'recogida-de-residuos', 'omic', 'objetos-perdidos' ) );
		\WP_Mock::userFunction( 'get_template_directory' )->andReturn( dirname( __DIR__ ) );
		\WP_Mock::userFunction( 'sanitize_text_field' )->andReturnArg( 0 );
		\WP_Mock::userFunction( 'wp_unslash' )->andReturnArg( 0 );
		\WP_Mock::userFunction( 'current_time' )->andReturn( '2098-06-01 12:00:00' );

		$items = aranda_de_duero_everyday_services();

		$this->assertSame(
			array( 'Farmacia de guardia', 'Recogida de residuos', 'OMIC: información al consumidor', 'Objetos perdidos', 'Transporte: autobús urbano' ),
			array_column( $items, 'title' )
		);
	}

	public function test_the_pharmacy_card_names_the_pharmacy_of_the_moment() {
		$this->mocks( array( 'farmacia-de-guardia' ) );
		\WP_Mock::userFunction( 'get_template_directory' )->andReturn( dirname( __DIR__ ) );
		\WP_Mock::userFunction( 'sanitize_text_field' )->andReturnArg( 0 );
		\WP_Mock::userFunction( 'wp_unslash' )->andReturnArg( 0 );
		\WP_Mock::userFunction( 'current_time' )->andReturn( '2026-10-25 09:30:00' );

		$items = aranda_de_duero_everyday_services();

		$this->assertCount( 2, $items );
		$this->assertSame( 'INÉS BRIME BERDUGO', $items[0]['pharmacy']['name'] );
	}

	public function test_the_waste_card_carries_the_bulky_phone_and_the_ban() {
		$this->mocks( array( 'recogida-de-residuos' ) );
		\WP_Mock::userFunction( 'get_template_directory' )->andReturn( dirname( __DIR__ ) );
		\WP_Mock::userFunction( 'current_time' )->andReturn( '2026-10-25' );

		$items = aranda_de_duero_everyday_services();

		$this->assertCount( 2, $items );
		$this->assertSame( '947 50 60 50', $items[0]['waste']['bulky_phone'] );
		$this->assertFalse( $items[0]['waste']['ban'] );
	}

	public function test_omic_and_lost_found_are_plain_links_with_their_keywords() {
		$this->mocks( array( 'omic', 'objetos-perdidos' ) );

		$items = aranda_de_duero_everyday_services();

		$this->assertCount( 3, $items );
		$this->assertSame( 'https://www.arandadeduero.es/omic/', $items[0]['url'] );
		$this->assertStringContainsString( 'reclamacion', $items[0]['keywords'] );
		$this->assertSame( 'https://www.arandadeduero.es/objetos-perdidos/', $items[1]['url'] );
		$this->assertStringContainsString( 'llaves', $items[1]['keywords'] );
	}

	public function test_the_omic_link_is_the_service_not_a_page() {
		\WP_Mock::userFunction( 'get_page_by_path' )->once()->with( 'omic', OBJECT, 'servicio-municipal' )->andReturnUsing(
			function () {
				$service            = new WP_Post();
				$service->post_name = 'omic';

				return $service;
			}
		);
		\WP_Mock::userFunction( 'get_permalink' )->andReturn( 'https://www.arandadeduero.es/servicios/omic/' );

		$this->assertSame( 'https://www.arandadeduero.es/servicios/omic/', aranda_de_duero_omic_page_url() );
	}

	public function test_lost_found_contact_data() {
		$lost = aranda_de_duero_lost_found_info();
		$this->assertSame( '947 51 26 46', $lost['phone'] );
		$this->assertSame( 'policialocalad@arandadeduero.es', $lost['email'] );
		$this->assertSame( 'Calle Valladolid, 4', $lost['address'] );
	}

	public function test_the_old_omic_article_redirects_to_the_new_page_once_it_exists() {
		$this->mocks( array( 'omic' ) );

		$this->assertSame( 'https://www.arandadeduero.es/omic/', aranda_de_duero_omic_article_redirect_target( 'la-omic-que-es-y-que-funciones-tiene' ) );
		$this->assertSame( '', aranda_de_duero_omic_article_redirect_target( 'otra-noticia' ) );
	}

	public function test_the_old_omic_article_does_not_redirect_without_the_new_page() {
		$this->mocks();

		$this->assertSame( '', aranda_de_duero_omic_article_redirect_target( 'la-omic-que-es-y-que-funciones-tiene' ) );
	}

	public function test_the_old_omic_page_redirects_to_the_new_service_once_it_exists() {
		$this->mocks( array( 'omic' ) );

		foreach ( array( '/omic/', 'omic', '/omic' ) as $path ) {
			$this->assertSame( 'https://www.arandadeduero.es/omic/', aranda_de_duero_omic_page_redirect_target( $path ), $path );
		}
		foreach ( array( '/', '/omic-que-es/', '/servicios/omic/' ) as $path ) {
			$this->assertSame( '', aranda_de_duero_omic_page_redirect_target( $path ), $path );
		}
	}

	public function test_the_old_omic_page_does_not_redirect_without_the_new_service() {
		$this->mocks();

		$this->assertSame( '', aranda_de_duero_omic_page_redirect_target( 'omic' ) );
	}
}
