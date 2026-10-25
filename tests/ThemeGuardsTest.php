<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/template-functions.php';

class ThemeGuardsTest extends TestCase {

	public function test_a_published_page_is_found_by_its_slug() {
		$page = new WP_Post();
		\WP_Mock::userFunction( 'get_page_by_path' )->with( 'aviso-legal' )->andReturn( $page );
		\WP_Mock::userFunction( 'get_permalink' )->andReturn( 'https://example.test/aviso-legal/' );

		$this->assertSame( 'https://example.test/aviso-legal/', aranda_de_duero_page_url( 'aviso-legal' ) );
	}

	public function test_a_missing_or_unpublished_page_gives_an_empty_link() {
		$draft              = new WP_Post();
		$draft->post_status = 'draft';
		\WP_Mock::userFunction( 'get_page_by_path' )->andReturnUsing(
			function ( $path ) use ( $draft ) {
				return 'borrador' === $path ? $draft : null;
			}
		);

		$this->assertSame( '', aranda_de_duero_page_url( 'borrador' ) );
		$this->assertSame( '', aranda_de_duero_page_url( 'no-existe' ) );
	}

	/**
	 * Own process: once any test defines get_field(), it exists for the rest of the run.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_without_acf_a_field_is_empty_instead_of_a_fatal_error() {
		$this->assertFalse( function_exists( 'get_field' ) );
		$this->assertSame( '', aranda_de_duero_field( 'cabecera_de_pagina' ) );
	}

	public function test_a_field_is_read_from_acf_when_it_is_active() {
		\WP_Mock::userFunction( 'get_field' )->with( 'cabecera_de_pagina', false )->andReturn( 'https://example.test/cabecera.jpg' );

		$this->assertSame( 'https://example.test/cabecera.jpg', aranda_de_duero_field( 'cabecera_de_pagina' ) );
	}
}
