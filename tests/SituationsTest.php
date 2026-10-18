<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/template-functions.php';
require_once dirname( __DIR__ ) . '/inc/services.php';
require_once dirname( __DIR__ ) . '/inc/search.php';
require_once dirname( __DIR__ ) . '/inc/situations.php';
require_once dirname( __DIR__ ) . '/inc/audiences.php';

if ( ! class_exists( 'WP_Post' ) ) {
	class WP_Post {
		public $post_status = 'publish';
	}
}

class SituationsTest extends TestCase {

	private function mocks( $page = null ) {
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
		\WP_Mock::userFunction( 'get_page_by_path' )->andReturnUsing(
			function ( $path ) use ( $page ) {
				return 'tramites-por-situaciones' === $path ? $page : null;
			}
		);
		\WP_Mock::userFunction( 'get_permalink' )->andReturn( 'https://www.arandadeduero.es/tramites-por-situaciones/' );
		\WP_Mock::userFunction( 'wp_parse_url' )->andReturnUsing(
			function ( $url, $component = -1 ) {
				return parse_url( $url, $component );
			}
		);
		\WP_Mock::userFunction( 'get_posts' )->andReturn( array() );
		\WP_Mock::userFunction( 'remove_accents' )->andReturnUsing(
			function ( $text ) {
				return strtr( $text, array( 'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ñ' => 'n' ) );
			}
		);
	}

	public function test_every_procedure_points_to_its_page_in_this_site() {
		$this->mocks();

		$procedures = aranda_de_duero_portal_procedures();

		$this->assertCount( 17, $procedures );
		foreach ( $procedures as $key => $procedure ) {
			$this->assertStringStartsWith( 'https://www.arandadeduero.es/tramite/', $procedure['url'], $key );
			$this->assertStringEndsWith( '/', $procedure['url'], $key );
			$this->assertNotSame( '', $procedure['title'], $key );
			$this->assertNotSame( '', $procedure['text'], $key );
			$this->assertFalse( $procedure['new_tab'], $key );
		}
		$urls = array_column( $procedures, 'url' );
		$this->assertSame( $urls, array_values( array_unique( $urls ) ) );
	}

	public function test_every_entry_of_every_situation_is_found() {
		$this->mocks();

		$groups = aranda_de_duero_situations();

		$this->assertCount( 7, $groups );
		foreach ( $groups as $group ) {
			$this->assertNotSame( '', $group['title'] );
			$this->assertNotEmpty( $group['items'], $group['title'] );
			foreach ( $group['items'] as $item ) {
				$this->assertSame( array( 'title', 'text', 'url', 'new_tab' ), array_keys( $item ), $group['title'] );
				$this->assertStringStartsWith( 'https://', $item['url'] );
			}
		}
	}

	public function test_a_title_that_no_longer_exists_is_not_resolved() {
		$this->mocks();

		$this->assertNull( aranda_de_duero_service_by_title( 'Un servicio que no existe' ) );
		$this->assertSame( 'Cita previa', aranda_de_duero_service_by_title( 'Cita previa' )['title'] );
	}

	public function test_no_situation_repeats_an_address() {
		$this->mocks();

		foreach ( aranda_de_duero_situations() as $group ) {
			$urls = array_column( $group['items'], 'url' );
			$this->assertSame( $urls, array_values( array_unique( $urls ) ), $group['title'] );
		}
	}

	public function test_the_page_is_only_offered_to_the_search_once_it_is_published() {
		$this->mocks();
		$this->assertSame( '', aranda_de_duero_situations_url() );
		$this->assertNotContains( 'Trámites por situaciones', array_column( aranda_de_duero_service_directory(), 'title' ) );
	}

	public function test_a_published_page_is_found_by_the_search() {
		$this->mocks( new WP_Post() );

		$this->assertSame( 'https://www.arandadeduero.es/tramites-por-situaciones/', aranda_de_duero_situations_url() );
		$this->assertContains( 'Trámites por situaciones', array_column( aranda_de_duero_service_directory(), 'title' ) );
	}

	public function test_a_draft_page_is_not_offered() {
		$page              = new WP_Post();
		$page->post_status = 'draft';
		$this->mocks( $page );

		$this->assertSame( '', aranda_de_duero_situations_url() );
	}

	public function test_the_search_finds_the_procedures_by_what_people_say() {
		$this->mocks();

		$expected = array(
			'boda'          => 'Matrimonio civil',
			'ibi'           => 'Impuesto sobre bienes inmuebles (IBI)',
			'obra'          => 'Obra mayor',
			'terraza'       => 'Veladores y terrazas',
			'plusvalia'     => 'Impuesto de plusvalías',
			'empadronarme'  => 'Altas, cambios de domicilio y renovaciones del padrón',
		);
		foreach ( $expected as $query => $title ) {
			$this->assertContains( $title, array_column( aranda_de_duero_search_services( $query ), 'title' ), $query );
		}
	}

	public function test_the_two_full_lists_are_offered_at_the_end() {
		$this->mocks();

		$more = aranda_de_duero_more_procedures();

		$this->assertCount( 2, $more );
		$this->assertStringEndsWith( '/tramites/', $more[0]['url'] );
		$this->assertStringEndsWith( 'PAGE_CODE=CATALOGO', $more[1]['url'] );
	}
}
