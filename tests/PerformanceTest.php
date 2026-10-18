<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/performance.php';

class PerformanceTest extends TestCase {

	public function test_tinymce_emoji_plugin_is_removed() {
		$this->assertSame(
			array( 0 => 'lists', 2 => 'link' ),
			aranda_de_duero_remove_tinymce_emoji( array( 'lists', 'wpemoji', 'link' ) )
		);
	}

	public function test_tinymce_filter_tolerates_non_array_input() {
		$this->assertSame( array(), aranda_de_duero_remove_tinymce_emoji( null ) );
	}

	public function test_font_awesome_styles_are_dequeued() {
		\WP_Mock::onFilter( 'aranda_de_duero_dequeue_font_awesome' )->with( true )->reply( true );

		foreach ( aranda_de_duero_font_awesome_handles() as $handle ) {
			\WP_Mock::userFunction( 'wp_dequeue_style' )->once()->with( $handle );
			\WP_Mock::userFunction( 'wp_deregister_style' )->once()->with( $handle );
		}

		aranda_de_duero_dequeue_font_awesome();
		$this->assertTrue( true );
	}

	public function test_font_awesome_is_kept_when_the_filter_says_so() {
		\WP_Mock::onFilter( 'aranda_de_duero_dequeue_font_awesome' )->with( true )->reply( false );
		\WP_Mock::userFunction( 'wp_dequeue_style' )->never();
		\WP_Mock::userFunction( 'wp_deregister_style' )->never();

		aranda_de_duero_dequeue_font_awesome();
		$this->assertTrue( true );
	}

	public function test_resource_hint_for_font_awesome_is_dropped() {
		\WP_Mock::onFilter( 'aranda_de_duero_dequeue_font_awesome' )->with( true )->reply( true );

		$urls = array( '//use.fontawesome.com', '//fonts.googleapis.com', array( 'href' => '//use.fontawesome.com' ) );

		$this->assertSame(
			array( '//fonts.googleapis.com' ),
			aranda_de_duero_drop_font_awesome_hint( $urls, 'dns-prefetch' )
		);
	}

	public function test_other_resource_hint_relations_are_untouched() {
		$urls = array( '//use.fontawesome.com' );

		$this->assertSame( $urls, aranda_de_duero_drop_font_awesome_hint( $urls, 'preconnect' ) );
	}

	public function test_the_pdf_flag_is_only_printed_on_a_single_page_with_a_file_block() {
		\WP_Mock::userFunction( 'is_singular' )->andReturn( true );
		\WP_Mock::userFunction( 'has_block' )->with( 'core/file' )->andReturn( true );
		\WP_Mock::userFunction( 'wp_print_inline_script_tag' )->once()->with( \Mockery::pattern( '/pdfViewerEnabled/' ) );

		aranda_de_duero_pdf_preview_flag();

		$this->assertTrue( aranda_de_duero_has_pdf_embed() );
	}

	public function test_the_pdf_flag_is_not_printed_without_a_file_block() {
		\WP_Mock::userFunction( 'is_singular' )->andReturn( true );
		\WP_Mock::userFunction( 'has_block' )->andReturn( false );
		\WP_Mock::userFunction( 'wp_print_inline_script_tag' )->never();

		aranda_de_duero_pdf_preview_flag();

		$this->assertFalse( aranda_de_duero_has_pdf_embed() );
	}

	public function test_the_pdf_flag_is_not_printed_outside_single_pages() {
		\WP_Mock::userFunction( 'is_singular' )->andReturn( false );
		\WP_Mock::userFunction( 'wp_print_inline_script_tag' )->never();

		aranda_de_duero_pdf_preview_flag();

		$this->assertFalse( aranda_de_duero_has_pdf_embed() );
	}
}
