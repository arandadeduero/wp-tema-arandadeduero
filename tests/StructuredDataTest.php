<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/structured-data.php';

class StructuredDataTest extends TestCase {

	use Aranda_WP_Stubs;

	public function setUp(): void {
		parent::setUp();

		\WP_Mock::userFunction( 'home_url' )->andReturn( 'https://example.test/' );
		\WP_Mock::userFunction( 'get_bloginfo' )->andReturnUsing(
			function ( $show ) {
				return 'language' === $show ? 'es-ES' : 'Ayuntamiento';
			}
		);
		\WP_Mock::userFunction( 'get_theme_mod' )->andReturn( 0 );
		\WP_Mock::userFunction( 'wp_strip_all_tags' )->andReturnUsing( 'strip_tags' );
		$this->stub_wp_basics();
	}

	private function types( array $graph ) {
		return array_column( $graph, '@type' );
	}

	public function test_front_page_has_organization_and_website_only() {
		\WP_Mock::userFunction( 'is_singular' )->andReturn( false );
		\WP_Mock::userFunction( 'is_tax' )->andReturn( false );

		$graph = aranda_de_duero_structured_data_graph();

		$this->assertSame( array( 'GovernmentOrganization', 'WebSite' ), $this->types( $graph ) );
		$this->assertSame( 'https://example.test/#organization', $graph[0]['@id'] );
		$this->assertContains( 'https://www.facebook.com/aytoarandadeduero/', $graph[0]['sameAs'] );
		$this->assertContains( 'https://www.flickr.com/photos/ayuntamientoarandadeduero/', $graph[0]['sameAs'] );
	}

	public function test_a_term_archive_has_breadcrumbs_from_home_to_the_term() {
		\WP_Mock::userFunction( 'is_singular' )->andReturn( false );
		\WP_Mock::userFunction( 'is_tax' )->andReturn( true );
		$term = (object) array( 'name' => 'Juventud <b>y</b> Deportes' );
		\WP_Mock::userFunction( 'get_queried_object' )->andReturn( $term );
		\WP_Mock::userFunction( 'get_term_link' )->andReturn( 'https://example.test/concejalia/juventud/' );

		$graph = aranda_de_duero_structured_data_graph();

		$this->assertSame( array( 'GovernmentOrganization', 'WebSite', 'BreadcrumbList' ), $this->types( $graph ) );
		$items = $graph[2]['itemListElement'];
		$this->assertCount( 2, $items );
		$this->assertSame( 'https://example.test/', $items[0]['item'] );
		$this->assertSame( 2, $items[1]['position'] );
		$this->assertSame( 'Juventud y Deportes', $items[1]['name'] );
		$this->assertSame( 'https://example.test/concejalia/juventud/', $items[1]['item'] );
	}

	public function test_the_front_page_has_a_single_breadcrumb() {
		\WP_Mock::userFunction( 'is_singular' )->andReturnUsing(
			function ( $type = '' ) {
				return '' === $type;
			}
		);
		\WP_Mock::userFunction( 'is_page' )->andReturn( true );
		\WP_Mock::userFunction( 'is_front_page' )->andReturn( true );
		\WP_Mock::userFunction( 'get_the_ID' )->andReturn( 2 );
		\WP_Mock::userFunction( 'get_permalink' )->andReturn( 'https://example.test/' );
		\WP_Mock::userFunction( 'get_the_post_thumbnail_url' )->andReturn( '' );
		\WP_Mock::userFunction( 'get_the_title' )->andReturn( 'Inicio' );
		\WP_Mock::userFunction( 'get_post_ancestors' )->andReturn( array() );

		$graph = aranda_de_duero_structured_data_graph();

		$this->assertSame( array( 'GovernmentOrganization', 'WebSite', 'WebPage', 'BreadcrumbList' ), $this->types( $graph ) );
		$this->assertCount( 1, $graph[3]['itemListElement'] );
	}

	public function test_a_term_without_a_link_adds_no_breadcrumbs() {
		\WP_Mock::userFunction( 'is_singular' )->andReturn( false );
		\WP_Mock::userFunction( 'is_tax' )->andReturn( true );
		\WP_Mock::userFunction( 'get_queried_object' )->andReturn( (object) array( 'name' => 'X' ) );
		\WP_Mock::userFunction( 'get_term_link' )->andReturn( false );

		$this->assertSame( array( 'GovernmentOrganization', 'WebSite' ), $this->types( aranda_de_duero_structured_data_graph() ) );
	}

	public function test_post_adds_news_article_and_breadcrumbs() {
		\WP_Mock::userFunction( 'is_singular' )->andReturnUsing(
			function ( $type = '' ) {
				return '' === $type || 'post' === $type;
			}
		);
		\WP_Mock::userFunction( 'is_page' )->andReturn( false );
		\WP_Mock::userFunction( 'is_front_page' )->andReturn( false );
		\WP_Mock::userFunction( 'get_the_ID' )->andReturn( 5 );
		\WP_Mock::userFunction( 'get_permalink' )->andReturn( 'https://example.test/noticia/' );
		\WP_Mock::userFunction( 'get_the_post_thumbnail_url' )->andReturn( 'https://example.test/i.jpg' );
		\WP_Mock::userFunction( 'get_the_title' )->andReturn( 'Fiestas <b>2026</b>' );
		\WP_Mock::userFunction( 'get_the_date' )->andReturn( '2026-10-01T10:00:00+02:00' );
		\WP_Mock::userFunction( 'get_the_modified_date' )->andReturn( '2026-10-02T10:00:00+02:00' );
		\WP_Mock::userFunction( 'get_post_ancestors' )->andReturn( array() );

		$graph = aranda_de_duero_structured_data_graph();

		$this->assertSame(
			array( 'GovernmentOrganization', 'WebSite', 'NewsArticle', 'BreadcrumbList' ),
			$this->types( $graph )
		);
		$this->assertSame( 'Fiestas 2026', $graph[2]['headline'] );
		$this->assertSame( array( 'https://example.test/i.jpg' ), $graph[2]['image'] );
		$this->assertCount( 2, $graph[3]['itemListElement'] );
	}
}
