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

class AudiencesTest extends TestCase {

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
				return 'para-ti' === $path ? $page : null;
			}
		);
		\WP_Mock::userFunction( 'get_permalink' )->andReturn( 'https://www.arandadeduero.es/para-ti/' );
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

	public function test_every_group_resolves_all_its_entries() {
		$this->mocks();

		$groups = aranda_de_duero_audiences();

		$this->assertCount( 6, $groups );
		foreach ( $groups as $group ) {
			$this->assertNotSame( '', $group['title'] );
			$this->assertGreaterThanOrEqual( 4, count( $group['items'] ), $group['title'] );
			foreach ( $group['items'] as $item ) {
				$this->assertSame( array( 'title', 'text', 'url', 'new_tab' ), array_keys( $item ), $group['title'] );
				$this->assertStringStartsWith( 'https://', $item['url'] );
				$this->assertNotSame( '', $item['text'] );
			}
		}
	}

	public function test_no_entry_is_lost_on_the_way() {
		$this->mocks();

		// Entries that cannot be found are dropped silently, so the number of cards of each group is fixed here.
		$counts = array();
		foreach ( aranda_de_duero_audiences() as $group ) {
			$counts[ $group['title'] ] = count( $group['items'] );
		}
		$this->assertSame(
			array(
				'Familias'             => 7,
				'Personas mayores'     => 7,
				'Jóvenes'              => 6,
				'Empresas y comercios' => 8,
				'Nuevos vecinos'       => 8,
				'Asociaciones'         => 4,
			),
			$counts
		);
	}

	public function test_no_group_repeats_a_title() {
		$this->mocks();

		foreach ( aranda_de_duero_audiences() as $group ) {
			$titles = array_column( $group['items'], 'title' );
			$this->assertSame( $titles, array_values( array_unique( $titles ) ), $group['title'] );
		}
	}

	public function test_no_group_repeats_an_address() {
		$this->mocks();

		foreach ( aranda_de_duero_audiences() as $group ) {
			$urls = array_column( $group['items'], 'url' );
			$this->assertSame( $urls, array_values( array_unique( $urls ) ), $group['title'] );
		}
	}

	public function test_the_sections_of_the_site_point_to_this_site_except_sports() {
		$this->mocks();

		foreach ( aranda_de_duero_site_sections() as $section ) {
			if ( 'Deportes' === $section['title'] ) {
				$this->assertTrue( $section['new_tab'] );
				continue;
			}
			$this->assertMatchesRegularExpression( '#^https://www\.arandadeduero\.es/(tema|concejalia)/[a-z-]+/$#', $section['url'], $section['title'] );
			$this->assertFalse( $section['new_tab'], $section['title'] );
		}
	}

	public function test_a_title_that_does_not_exist_is_not_resolved() {
		$this->mocks();

		$this->assertNull( aranda_de_duero_audience_entry( 'Un servicio que no existe' ) );
		$this->assertSame( 'Salud', aranda_de_duero_audience_entry( 'Salud' )['title'] );
		$this->assertSame( 'Cita previa', aranda_de_duero_audience_entry( 'Cita previa' )['title'] );
	}

	public function test_the_page_is_only_offered_to_the_search_once_it_is_published() {
		$this->mocks();
		$this->assertSame( '', aranda_de_duero_audiences_url() );
		$this->assertNotContains( 'Para ti', array_column( aranda_de_duero_service_directory(), 'title' ) );
	}

	public function test_a_published_page_is_found_by_the_search() {
		$this->mocks( new WP_Post() );

		$this->assertSame( 'https://www.arandadeduero.es/para-ti/', aranda_de_duero_audiences_url() );
		$this->assertContains( 'Para ti', array_column( aranda_de_duero_service_directory(), 'title' ) );
		$this->assertContains( 'Para ti', array_column( aranda_de_duero_search_services( 'jovenes' ), 'title' ) );
	}

	public function test_a_draft_page_is_not_offered() {
		$page              = new WP_Post();
		$page->post_status = 'draft';
		$this->mocks( $page );

		$this->assertSame( '', aranda_de_duero_audiences_url() );
	}

	public function test_the_home_links_to_nothing_while_no_page_is_published() {
		$this->mocks();

		$this->assertSame( array(), aranda_de_duero_explore_links() );
	}

	public function test_the_home_links_to_the_published_page() {
		$this->mocks( new WP_Post() );

		$titles = array_column( aranda_de_duero_explore_links(), 'title' );
		$this->assertContains( 'Para ti', $titles );
	}
}
