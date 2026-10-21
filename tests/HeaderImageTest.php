<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/template-tags.php';

class HeaderImageTest extends TestCase {

	public function setUp(): void {
		parent::setUp();
		WP_Mock::userFunction( 'esc_url' )->andReturnUsing(
			function ( $url ) {
				return $url;
			}
		);
		WP_Mock::userFunction( 'esc_attr' )->andReturnUsing(
			function ( $text ) {
				return $text;
			}
		);
	}

	public function test_the_attachment_id_is_looked_up_once_and_kept() {
		WP_Mock::userFunction( 'get_transient' )->once()->andReturn( false );
		WP_Mock::userFunction( 'attachment_url_to_postid' )->once()->with( 'https://example.test/cab.jpg' )->andReturn( 42 );
		WP_Mock::userFunction( 'set_transient' )->once()->with( 'aranda_att_' . md5( 'https://example.test/cab.jpg' ), '42', WEEK_IN_SECONDS );

		$this->assertSame( 42, aranda_de_duero_attachment_id_from_url( 'https://example.test/cab.jpg' ) );
	}

	public function test_a_known_lookup_does_not_query_again() {
		WP_Mock::userFunction( 'get_transient' )->andReturn( '7' );
		WP_Mock::userFunction( 'attachment_url_to_postid' )->never();

		$this->assertSame( 7, aranda_de_duero_attachment_id_from_url( 'https://example.test/otra.jpg' ) );
	}

	public function test_a_media_library_image_gets_its_dimensions_from_the_attachment() {
		WP_Mock::userFunction( 'get_transient' )->andReturn( '42' );
		WP_Mock::userFunction( 'wp_get_attachment_image' )->once()->with(
			42,
			'full',
			false,
			\Mockery::on(
				function ( $attr ) {
					return 'eager' === $attr['loading'] && 'high' === $attr['fetchpriority'] && '' === $attr['alt'];
				}
			)
		)->andReturn( '<img width="1920" height="300" src="x.jpg">' );

		$this->assertSame( '<img width="1920" height="300" src="x.jpg">', aranda_de_duero_header_image_html( 'https://example.test/cab.jpg', '' ) );
	}

	public function test_an_address_that_is_not_in_the_media_library_keeps_the_plain_tag() {
		WP_Mock::userFunction( 'get_transient' )->andReturn( '0' );
		WP_Mock::userFunction( 'wp_get_attachment_image' )->never();

		$this->assertSame(
			'<img src="https://example.org/a.jpg" class="img-fluid w-100 cabecera_pagina" alt="Cabecera" />',
			aranda_de_duero_header_image_html( 'https://example.org/a.jpg', 'Cabecera' )
		);
	}
}
