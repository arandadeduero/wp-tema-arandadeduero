<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/social-meta.php';

class SocialMetaTest extends TestCase {

	public function test_no_external_og_tags_by_default() {
		$this->assertFalse( aranda_de_duero_has_external_og_tags() );
	}

	public function test_social_image_uses_featured_image_with_alt() {
		\WP_Mock::userFunction( 'is_singular' )->andReturn( true );
		\WP_Mock::userFunction( 'has_post_thumbnail' )->andReturn( true );
		\WP_Mock::userFunction( 'get_post_thumbnail_id' )->andReturn( 7 );
		\WP_Mock::userFunction( 'wp_get_attachment_image_src' )
			->with( 7, 'large' )
			->andReturn( array( 'https://example.test/a.jpg', 1200, 630 ) );
		\WP_Mock::userFunction( 'get_post_meta' )
			->with( 7, '_wp_attachment_image_alt', true )
			->andReturn( ' Plaza Mayor ' );

		$this->assertSame(
			array(
				'url'    => 'https://example.test/a.jpg',
				'width'  => 1200,
				'height' => 630,
				'alt'    => 'Plaza Mayor',
			),
			aranda_de_duero_social_image()
		);
	}

	public function test_social_image_falls_back_to_customizer_image() {
		\WP_Mock::userFunction( 'is_singular' )->andReturn( false );
		\WP_Mock::userFunction( 'get_theme_mod' )
			->with( 'aranda_de_duero_social_share_image' )
			->andReturn( 9 );
		\WP_Mock::userFunction( 'wp_get_attachment_image_src' )
			->with( 9, 'full' )
			->andReturn( array( 'https://example.test/share.jpg', 1200, 630 ) );

		$image = aranda_de_duero_social_image();

		$this->assertSame( 'https://example.test/share.jpg', $image['url'] );
	}

	public function test_social_image_is_null_without_any_source() {
		\WP_Mock::userFunction( 'is_singular' )->andReturn( false );
		\WP_Mock::userFunction( 'get_theme_mod' )->andReturn( '' );

		$this->assertNull( aranda_de_duero_social_image() );
	}

	public function test_detects_seo_plugin(): void {
		// Constants can't be undefined, so this must stay the last test in the file.
		define( 'WPSEO_VERSION', '1.0' );
		$this->assertTrue( aranda_de_duero_has_external_og_tags() );
	}
}
