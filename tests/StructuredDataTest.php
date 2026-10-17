<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/structured-data.php';

class StructuredDataTest extends TestCase {

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
		\WP_Mock::userFunction( 'apply_filters' )->andReturnUsing(
			function ( $tag, $value ) {
				return $value;
			}
		);
	}

	private function types( array $graph ) {
		return array_column( $graph, '@type' );
	}

	public function test_front_page_has_organization_and_website_only() {
		\WP_Mock::userFunction( 'is_singular' )->andReturn( false );

		$graph = aranda_de_duero_structured_data_graph();

		$this->assertSame( array( 'GovernmentOrganization', 'WebSite' ), $this->types( $graph ) );
		$this->assertSame( 'https://example.test/#organization', $graph[0]['@id'] );
		$this->assertContains( 'https://www.facebook.com/aytoarandadeduero/', $graph[0]['sameAs'] );
	}

	public function test_post_adds_news_article_and_breadcrumbs() {
		\WP_Mock::userFunction( 'is_singular' )->andReturnUsing(
			function ( $type = '' ) {
				return '' === $type || 'post' === $type;
			}
		);
		\WP_Mock::userFunction( 'is_page' )->andReturn( false );
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
