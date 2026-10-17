<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/performance.php';

if ( ! class_exists( 'WP_Post' ) ) {
	// Minimal stand-in: the code under test only checks the type and reads post_content.
	class WP_Post {
		public $post_content = '';
	}
}

class AjaxLoadMoreAssetsTest extends TestCase {

	private function post( $content ) {
		$post               = new WP_Post();
		$post->post_content = $content;
		return $post;
	}

	public function test_front_page_needs_the_assets() {
		\WP_Mock::userFunction( 'is_front_page' )->andReturn( true );
		\WP_Mock::userFunction( 'get_post' )->never();

		$this->assertTrue( aranda_de_duero_needs_ajax_load_more_assets() );
	}

	public function test_home_template_needs_the_assets() {
		\WP_Mock::userFunction( 'is_front_page' )->andReturn( false );
		\WP_Mock::userFunction( 'is_page_template' )->with( 'page-home.php' )->andReturn( true );
		\WP_Mock::userFunction( 'get_post' )->never();

		$this->assertTrue( aranda_de_duero_needs_ajax_load_more_assets() );
	}

	public function test_page_with_the_shortcode_needs_the_assets() {
		$content = 'Texto [ajax_load_more post_type="post"]';

		\WP_Mock::userFunction( 'is_front_page' )->andReturn( false );
		\WP_Mock::userFunction( 'is_page_template' )->andReturn( false );
		\WP_Mock::userFunction( 'get_post' )->andReturn( $this->post( $content ) );
		\WP_Mock::userFunction( 'has_shortcode' )->with( $content, 'ajax_load_more' )->andReturn( true );

		$this->assertTrue( aranda_de_duero_needs_ajax_load_more_assets() );
	}

	public function test_ordinary_page_does_not_need_the_assets() {
		\WP_Mock::userFunction( 'is_front_page' )->andReturn( false );
		\WP_Mock::userFunction( 'is_page_template' )->andReturn( false );
		\WP_Mock::userFunction( 'get_post' )->andReturn( $this->post( 'Contenido normal' ) );
		\WP_Mock::userFunction( 'has_shortcode' )->andReturn( false );

		$this->assertFalse( aranda_de_duero_needs_ajax_load_more_assets() );
	}

	public function test_request_without_a_post_does_not_need_the_assets() {
		\WP_Mock::userFunction( 'is_front_page' )->andReturn( false );
		\WP_Mock::userFunction( 'is_page_template' )->andReturn( false );
		\WP_Mock::userFunction( 'get_post' )->andReturn( null );
		\WP_Mock::userFunction( 'has_shortcode' )->never();

		$this->assertFalse( aranda_de_duero_needs_ajax_load_more_assets() );
	}
}
