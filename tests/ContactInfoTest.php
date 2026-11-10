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

	public function test_the_ite_and_out_of_date_posts_lead_to_their_replacements() {
		$this->stub_wp_basics();
		\WP_Mock::userFunction( 'get_page_by_path' )->andReturnUsing(
			static function ( $path ) {
				$page              = new WP_Post();
				$page->post_status = 'publish';
				$page->post_name   = $path;

				return $page;
			}
		);
		\WP_Mock::userFunction( 'get_permalink' )->andReturnUsing(
			static function ( $post ) {
				return 'https://www.arandadeduero.es/' . $post->post_name . '/';
			}
		);

		$this->assertSame( 'https://www.arandadeduero.es/inspeccion-tecnica-de-edificios/', aranda_de_duero_replaced_post_redirect_target( '/i-t-e-inspeccion-tecnica-edificios/' ) );
		$this->assertSame( 'https://www.arandadeduero.es/inspeccion-tecnica-de-edificios/', aranda_de_duero_replaced_post_redirect_target( '/i-t-e-inspeccion-tecnica-edificios-2/' ) );
		$this->assertSame( 'https://www.arandadeduero.es/servicios/transporte-urbano/', aranda_de_duero_replaced_post_redirect_target( '/autobuses-urbanos-destinos-horarios-y-tarifas/' ) );
		$this->assertSame( 'https://www.arandadeduero.es/informacion-de-concejalias/', aranda_de_duero_replaced_post_redirect_target( '/ayuntamiento-telefonos-de-contacto/' ) );
		$this->assertSame( 'https://contratos.arandadeduero.es/', aranda_de_duero_replaced_post_redirect_target( '/perfil-del-contratante/' ) );
		$this->assertSame( '', aranda_de_duero_replaced_post_redirect_target( '/trenes-destinos-horarios-y-tarifas/' ) );
	}

	public function test_phone_link_matches_the_visible_number() {
		$this->stub_wp_basics();

		$info = aranda_de_duero_contact_info();

		$this->assertSame( $info['phone_href'], 'tel:' . preg_replace( '/\D/', '', $info['phone'] ) );
	}
}
