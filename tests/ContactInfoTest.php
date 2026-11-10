<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/template-functions.php';

class ContactInfoTest extends TestCase {

	use Aranda_WP_Stubs;

	public function test_returns_the_town_hall_contact_data() {
		$this->stub_wp_basics();

		$info = aranda_de_duero_contact_info();

		$this->assertSame( '947 500 100', $info['phone'] );
		$this->assertSame( 'tel:947500100', $info['phone_href'] );
		$this->assertSame( 'https://citaprevia.arandadeduero.es/', $info['appointment_url'] );
		$this->assertSame( 'https://transparencia.arandadeduero.es/', $info['transparency_url'] );
		$this->assertSame( 'https://sede.arandadeduero.es/', $info['sede_url'] );
		$this->assertNotSame( '', $info['hours'] );
	}

	public function test_transparency_addresses_of_this_site_lead_to_the_portal() {
		$this->stub_wp_basics();

		foreach ( array( '/transparencia/', 'transparencia', '/portal-de-transparencia', '/transparencia' ) as $path ) {
			$this->assertSame( 'https://transparencia.arandadeduero.es/', aranda_de_duero_transparency_redirect_target( $path ), $path );
		}
		foreach ( array( '/', '/agenda/', '/transparencia-fiscal/', '/otra/transparencia/' ) as $path ) {
			$this->assertSame( '', aranda_de_duero_transparency_redirect_target( $path ), $path );
		}
	}

	public function test_the_second_ordinances_post_leads_to_the_ordinances_page() {
		$this->stub_wp_basics();
		$page              = new WP_Post();
		$page->post_status = 'publish';
		\WP_Mock::userFunction( 'get_page_by_path' )->with( 'listado-completo-ordenanzas-fiscales-vigentes', OBJECT, 'page' )->andReturn( $page );
		\WP_Mock::userFunction( 'get_permalink' )->andReturn( 'https://www.arandadeduero.es/listado-completo-ordenanzas-fiscales-vigentes/' );

		foreach ( array( '/listado-completo-ordenanzas-fiscales-vigentes-3/', 'listado-completo-ordenanzas-fiscales-vigentes-3' ) as $path ) {
			$this->assertSame( 'https://www.arandadeduero.es/listado-completo-ordenanzas-fiscales-vigentes/', aranda_de_duero_replaced_post_redirect_target( $path ), $path );
		}
		foreach ( array( '/', '/listado-completo-ordenanzas-fiscales-vigentes/', '/listado-completo-ordenanzas-fiscales-vigentes-2/' ) as $path ) {
			$this->assertSame( '', aranda_de_duero_replaced_post_redirect_target( $path ), $path );
		}
	}

	public function test_the_pmus_post_leads_to_the_pmus_site() {
		$this->stub_wp_basics();

		foreach ( array( '/plan-de-movilidad-urbana-sostenible-de-aranda-de-duero/', 'plan-de-movilidad-urbana-sostenible-de-aranda-de-duero' ) as $path ) {
			$this->assertSame( 'https://pmus.arandadeduero.es/', aranda_de_duero_replaced_post_redirect_target( $path ), $path );
		}
	}

	public function test_phone_link_matches_the_visible_number() {
		$this->stub_wp_basics();

		$info = aranda_de_duero_contact_info();

		$this->assertSame( $info['phone_href'], 'tel:' . preg_replace( '/\D/', '', $info['phone'] ) );
	}
}
