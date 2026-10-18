<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/template-functions.php';
require_once dirname( __DIR__ ) . '/inc/services.php';
require_once dirname( __DIR__ ) . '/inc/search.php';

class SearchTest extends TestCase {

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
		\WP_Mock::userFunction( 'get_page_by_path' )->andReturn( null );
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
