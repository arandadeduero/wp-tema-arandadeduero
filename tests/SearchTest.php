<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/template-functions.php';
require_once dirname( __DIR__ ) . '/inc/services.php';
require_once dirname( __DIR__ ) . '/inc/especiales.php';
require_once dirname( __DIR__ ) . '/inc/search.php';

class SearchTest extends TestCase {

	private function mocks( array $especiales = array() ) {
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
		\WP_Mock::userFunction( 'get_page_by_path' )->andReturn( null );
		\WP_Mock::userFunction( 'wp_parse_url' )->andReturnUsing(
			function ( $url, $component = -1 ) {
				return parse_url( $url, $component );
			}
		);
		\WP_Mock::userFunction( 'get_posts' )->andReturn( $especiales );
		\WP_Mock::userFunction( 'get_post_meta' )->andReturnUsing(
			function ( $id ) {
				return 'https://pmus.arandadeduero.es/';
			}
		);
		\WP_Mock::userFunction( 'get_permalink' )->andReturn( 'https://www.arandadeduero.es/especial/' );
		\WP_Mock::userFunction( 'get_post_thumbnail_id' )->andReturn( 0 );
		// Same effect as WordPress' remove_accents() for the Spanish accents.
		\WP_Mock::userFunction( 'remove_accents' )->andReturnUsing(
			function ( $text ) {
				return strtr( $text, array( 'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U', 'ñ' => 'n' ) );
			}
		);
	}

	private function titles( array $services ) {
		return array_column( $services, 'title' );
	}

	public function test_a_word_finds_the_service_with_or_without_accents() {
		$this->mocks();

		foreach ( array( 'padrón', 'padron', 'PADRÓN' ) as $query ) {
			$this->assertContains( 'Certificado de padrón', $this->titles( aranda_de_duero_search_services( $query ) ), $query );
		}
	}

	public function test_a_word_only_matches_at_the_start_of_a_word() {
		$this->mocks();

		$titles = $this->titles( aranda_de_duero_search_services( 'cita' ) );

		$this->assertContains( 'Cita previa', $titles );
		$this->assertNotContains( 'Documentos habitualmente solicitados', $titles );
		$this->assertNotContains( 'Perfil de contratante', $titles );
	}

	public function test_a_word_started_but_not_finished_still_finds_the_service() {
		$this->mocks();

		$this->assertContains( 'Certificado de padrón', $this->titles( aranda_de_duero_search_services( 'padr' ) ) );
	}

	public function test_matches_in_the_title_come_before_matches_in_the_description() {
		$this->mocks();

		$titles = $this->titles( aranda_de_duero_search_services( 'ayuda' ) );

		$this->assertSame( 'Ayuda de la sede', $titles[0] );
	}

	public function test_a_synonym_in_the_keywords_finds_the_service() {
		$this->mocks();

		$this->assertContains( 'Empleo público', $this->titles( aranda_de_duero_search_services( 'oposiciones' ) ) );
		$this->assertContains( 'Quejas y sugerencias', $this->titles( aranda_de_duero_search_services( 'reclamar' ) ) );
	}

	public function test_every_word_typed_must_match() {
		$this->mocks();

		$titles = $this->titles( aranda_de_duero_search_services( 'pagar multa' ) );

		$this->assertContains( 'Multas', $titles );
		$this->assertNotContains( 'Empleo público', $titles );
	}

	public function test_it_also_searches_other_pages_of_the_electronic_office() {
		$this->mocks();

		$this->assertContains( 'Sesiones de pleno', $this->titles( aranda_de_duero_search_services( 'pleno' ) ) );
		$this->assertContains( 'Perfil de contratante', $this->titles( aranda_de_duero_search_services( 'licitaciones' ) ) );
	}

	public function test_it_finds_more_pages_of_the_electronic_office() {
		$this->mocks();

		$expected = array(
			'expediente'  => 'Expedientes',
			'firmar'      => 'Portafirmas',
			'plazos'      => 'Calendario y cómputo de plazos',
			'facturas'    => 'Factura electrónica',
			'ayuda'       => 'Ayuda de la sede',
			'caida'       => 'Interrupciones del servicio',
			'suscripcion' => 'Suscripción o baja de notificaciones electrónicas',
		);
		foreach ( $expected as $query => $title ) {
			$this->assertContains( $title, $this->titles( aranda_de_duero_search_services( $query ) ), $query );
		}
	}

	public function test_it_finds_the_sections_and_portals_of_the_site() {
		$this->mocks();

		$this->assertContains( 'Turismo', $this->titles( aranda_de_duero_search_services( 'turismo' ) ) );
		$this->assertContains( 'Bandos', $this->titles( aranda_de_duero_search_services( 'bando' ) ) );
		$this->assertContains( 'Teléfonos de interés', $this->titles( aranda_de_duero_search_services( 'telefono' ) ) );
	}

	public function test_internal_sections_open_in_the_same_tab_and_other_sites_in_a_new_one() {
		$this->mocks();

		$bandos  = aranda_de_duero_search_services( 'bando' );
		$turismo = aranda_de_duero_search_services( 'turismo' );

		$this->assertFalse( $bandos[0]['new_tab'] );
		$this->assertTrue( $turismo[0]['new_tab'] );
	}

	public function test_the_specials_of_the_home_page_are_searchable() {
		$special             = new stdClass();
		$special->ID         = 7;
		$special->post_title = 'PMUS Aranda de Duero';
		$this->mocks( array( $special ) );

		$found = aranda_de_duero_search_services( 'pmus' );

		$this->assertSame( array( 'PMUS Aranda de Duero' ), $this->titles( $found ) );
		$this->assertSame( 'https://pmus.arandadeduero.es/', $found[0]['url'] );
		$this->assertTrue( $found[0]['new_tab'] );
	}

	public function test_every_office_address_follows_the_pattern_the_office_uses() {
		$this->mocks();

		foreach ( aranda_de_duero_service_directory() as $entry ) {
			if ( false !== strpos( $entry['url'], 'sede.arandadeduero.es' ) ) {
				$this->assertStringContainsString( '/sta/Carpeta', $entry['url'] );
				$this->assertStringContainsString( '?APP_CODE=STA&PAGE_CODE=', $entry['url'] );
			}
			$this->assertStringStartsWith( 'https://', $entry['url'] );
		}
	}

	public function test_the_directory_has_no_repeated_addresses() {
		$this->mocks();

		$urls = array_column( aranda_de_duero_service_directory(), 'url' );

		$this->assertSame( $urls, array_values( array_unique( $urls ) ) );
	}

	public function test_no_match_and_empty_searches_give_nothing() {
		$this->mocks();

		$this->assertSame( array(), aranda_de_duero_search_services( 'zzzzqx' ) );
		$this->assertSame( array(), aranda_de_duero_search_services( '' ) );
		$this->assertSame( array(), aranda_de_duero_search_services( '  ?! ' ) );
	}

	public function test_results_have_no_repeated_links_and_respect_the_limit() {
		$this->mocks();

		$urls = array_column( aranda_de_duero_search_services( 'pagar', 20 ), 'url' );

		$this->assertSame( $urls, array_values( array_unique( $urls ) ) );
		$this->assertCount( 2, aranda_de_duero_search_services( 'pagar', 2 ) );
	}

	public function test_results_have_the_shape_the_cards_need() {
		$this->mocks();

		foreach ( aranda_de_duero_search_services( 'servicio' ) as $service ) {
			$this->assertSame( array( 'title', 'text', 'url', 'new_tab' ), array_keys( $service ) );
		}
	}

	public function test_the_catalogue_link_points_to_the_electronic_office() {
		$this->mocks();

		$this->assertStringEndsWith( 'PAGE_CODE=CATALOGO', aranda_de_duero_catalog_url() );
	}

	public function test_suggestions_are_plain_words() {
		$this->mocks();

		$this->assertNotEmpty( aranda_de_duero_search_suggestions() );
	}
}
