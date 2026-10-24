<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/template-functions.php';
require_once dirname( __DIR__ ) . '/inc/services.php';
require_once dirname( __DIR__ ) . '/inc/especiales.php';
require_once dirname( __DIR__ ) . '/inc/search.php';
require_once dirname( __DIR__ ) . '/inc/situations.php';
require_once dirname( __DIR__ ) . '/inc/audiences.php';

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
		\WP_Mock::userFunction( 'get_template_directory' )->andReturn( dirname( __DIR__ ) );
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

	public function test_the_catalogue_of_the_office_is_searchable_with_a_page_for_each_procedure() {
		$this->mocks();

		$entries = aranda_de_duero_sede_catalog_entries();
		$this->assertGreaterThan( 400, count( $entries ) );
		$this->assertCount( count( $entries ), array_unique( array_column( $entries, 'url' ) ) );
		foreach ( $entries as $entry ) {
			$this->assertStringContainsString( 'PAGE_CODE=CATALOGO&DETALLE=', $entry['url'] );
		}

		$found = $this->titles( aranda_de_duero_search_services( 'primera ocupación', 12 ) );
		$this->assertContains( 'Declaración responsable primera ocupación', $found );
	}

	public function test_the_validated_synonyms_find_catalogue_procedures() {
		$this->mocks();

		$basura = $this->titles( aranda_de_duero_search_services( 'basura', 12 ) );
		$this->assertContains( 'Devolucion de garantia o fianza de residuos de construccion y demolicion', $basura );

		foreach ( array( 'sepultura', 'sepulturas' ) as $query ) {
			$found = $this->titles( aranda_de_duero_search_services( $query, 12 ) );
			$this->assertContains( 'Solicitud de Inhumación en Nichos, Panteones, Columbarios...', $found, $query );
		}

		foreach ( array(
			'cochera'      => 'Licencias de Puerta Carretera,Vado permanente o Reserva de Espacio',
			'garaje'       => 'Licencias de Puerta Carretera,Vado permanente o Reserva de Espacio',
			'perro'        => 'Solicitud de inscripción en el Registro de Animales Potencialmente Peligrosos',
			'contribución' => 'Solicitud del recibo IBI por cotitularidad',
		) as $query => $title ) {
			$this->assertContains( $title, $this->titles( aranda_de_duero_search_services( $query, 50 ) ), $query );
		}

		$this->assertSame( '', aranda_de_duero_sede_catalog_keywords( 'Licencia de obras de demolición' ) );
	}

	public function test_the_curated_services_come_before_the_catalogue_and_the_suggestions_do_not_carry_it() {
		$this->mocks();

		$this->assertSame( 'Certificado de padrón', $this->titles( aranda_de_duero_search_services( 'padrón', 12 ) )[0] );
		$this->assertLessThan( 150, count( aranda_de_duero_search_index() ) );
	}

	public function test_a_missing_catalogue_file_gives_no_entries_and_the_rest_still_works() {
		\WP_Mock::userFunction( 'get_template_directory' )->andReturn( '/no/existe' );

		$this->assertSame( array(), aranda_de_duero_sede_catalog_entries() );
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

	public function test_the_photos_of_the_council_are_found_and_open_in_a_new_tab() {
		$this->mocks();

		foreach ( array( 'fotos', 'fotografias', 'flickr', 'album' ) as $query ) {
			$found = array_filter(
				aranda_de_duero_search_services( $query ),
				function ( $service ) {
					return 'Fotografías del Ayuntamiento' === $service['title'];
				}
			);
			$this->assertCount( 1, $found, $query );
			$this->assertSame( 'https://www.flickr.com/photos/ayuntamientoarandadeduero/', array_values( $found )[0]['url'], $query );
			$this->assertTrue( array_values( $found )[0]['new_tab'], $query );
		}
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

	public function test_the_index_for_the_browser_has_the_words_to_match_against() {
		$this->mocks();

		$index = aranda_de_duero_search_index();
		$entry = array_values( array_filter( $index, static fn( $e ) => 'Certificado de padrón' === $e['n'] ) )[0];

		$this->assertSame( array( 'n', 'd', 'u', 'b', 'w', 't' ), array_keys( $entry ) );
		$this->assertSame( 'certificado de padron', $entry['t'] );
		$this->assertContains( 'empadronamiento', explode( ' ', $entry['w'] ) );
		$this->assertSame( 1, $entry['b'] );
	}

	public function test_the_index_words_have_no_accents_or_repeats() {
		$this->mocks();

		foreach ( aranda_de_duero_search_index() as $entry ) {
			$words = explode( ' ', $entry['w'] );
			$this->assertSame( $words, array_values( array_unique( $words ) ), $entry['n'] );
			$this->assertSame( 1, preg_match( '/^[a-z0-9 ]+$/', $entry['w'] ), $entry['n'] );
		}
	}

	public function test_the_index_and_the_server_search_offer_the_same_services() {
		$this->mocks();

		$index   = array_column( aranda_de_duero_search_index(), 'u' );
		$catalog = array_column( aranda_de_duero_sede_catalog_entries(), 'url' );

		// The server also searches the catalogue of the office, which is too big to travel with every page.
		foreach ( array( 'padron', 'pagar', 'ayuda', 'firma' ) as $query ) {
			foreach ( aranda_de_duero_search_services( $query, 50 ) as $service ) {
				$this->assertContains( $service['url'], array_merge( $index, $catalog ), $query );
			}
		}
		$this->assertSame( $index, array_values( array_unique( $index ) ) );
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
