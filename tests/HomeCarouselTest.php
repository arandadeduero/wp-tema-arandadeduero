<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/home-carousel.php';

class HomeCarouselTest extends TestCase {

	use Aranda_WP_Stubs;

	private function mocks() {
		\WP_Mock::userFunction( 'esc_attr__' )->andReturnUsing(
			function ( $text ) {
				return $text;
			}
		);
		$this->stub_wp_basics();
		\WP_Mock::userFunction( 'esc_attr' )->andReturnUsing(
			function ( $text ) {
				return $text;
			}
		);
		\WP_Mock::userFunction( 'esc_url' )->andReturnUsing(
			function ( $text ) {
				return $text;
			}
		);
		\WP_Mock::userFunction( 'wp_get_attachment_image' )->andReturnUsing(
			function ( $id, $size, $icon, $attr ) {
				$priority = isset( $attr['fetchpriority'] ) ? ' fetchpriority="' . $attr['fetchpriority'] . '"' : '';
				return '<img data-id="' . $id . '" loading="' . $attr['loading'] . '"' . $priority . ' alt="' . $attr['alt'] . '">';
			}
		);
	}

	public function test_no_slides_means_no_markup() {
		$this->assertSame( '', aranda_de_duero_home_carousel_html( array() ) );
	}

	public function test_only_the_first_image_is_eager() {
		$this->mocks();

		$html = aranda_de_duero_home_carousel_html(
			array(
				array( 'image_id' => 1, 'title' => 'Uno', 'url' => '' ),
				array( 'image_id' => 2, 'title' => 'Dos', 'url' => '' ),
			)
		);

		$this->assertStringContainsString( 'data-id="1" loading="eager"', $html );
		$this->assertStringContainsString( 'data-id="2" loading="lazy"', $html );
		$this->assertSame( 2, substr_count( $html, 'class="home-carousel-slide"' ) );
	}

	public function test_only_the_first_image_has_high_fetch_priority() {
		$this->mocks();

		$html = aranda_de_duero_home_carousel_html(
			array(
				array( 'image_id' => 1, 'title' => 'Uno', 'url' => '' ),
				array( 'image_id' => 2, 'title' => 'Dos', 'url' => '' ),
			)
		);

		$this->assertStringContainsString( 'data-id="1" loading="eager" fetchpriority="high"', $html );
		$this->assertSame( 1, substr_count( $html, 'fetchpriority' ) );
	}

	public function test_a_slide_with_url_wraps_the_image_in_a_link() {
		$this->mocks();

		$html = aranda_de_duero_home_carousel_html( array( array( 'image_id' => 1, 'title' => 'Uno', 'url' => 'https://example.test/a' ) ) );

		$this->assertStringContainsString( '<a href="https://example.test/a"><img', $html );
	}

	public function test_slides_are_numbered_for_screen_readers() {
		$this->mocks();
		\WP_Mock::userFunction( 'wp_get_attachment_image' )->andReturn( '<img>' );

		$html = aranda_de_duero_home_carousel_html(
			array(
				array( 'image_id' => 1, 'title' => 'A', 'url' => '' ),
				array( 'image_id' => 2, 'title' => 'B', 'url' => '' ),
			)
		);

		$this->assertStringContainsString( 'aria-label="1 de 2"', $html );
		$this->assertStringContainsString( 'aria-label="2 de 2"', $html );
	}

	public function test_a_slide_with_a_video_gets_a_muted_video_without_src_over_its_image() {
		$this->mocks();

		$html = aranda_de_duero_home_carousel_html(
			array(
				array( 'image_id' => 1, 'title' => 'Uno', 'url' => '', 'video_url' => 'https://example.test/uno.mp4' ),
				array( 'image_id' => 2, 'title' => 'Dos', 'url' => '' ),
			)
		);

		$this->assertSame( 1, substr_count( $html, '<video' ) );
		$this->assertStringContainsString( '<video class="home-carousel-video" muted playsinline preload="none" aria-hidden="true" tabindex="-1" data-src="https://example.test/uno.mp4"></video>', $html );
		// Nothing is downloaded until the script asks for it.
		$this->assertStringNotContainsString( '<video class="home-carousel-video" muted playsinline preload="none" aria-hidden="true" tabindex="-1" src=', $html );
		$this->assertStringNotContainsString( 'autoplay', $html );
		// The image stays: it is the LCP element and what a browser without autoplay shows.
		$this->assertStringContainsString( 'data-id="1" loading="eager" fetchpriority="high"', $html );
	}

	public function test_the_video_goes_inside_the_link_of_the_slide() {
		$this->mocks();

		$html = aranda_de_duero_home_carousel_html( array( array( 'image_id' => 1, 'title' => 'Uno', 'url' => 'https://example.test/a', 'video_url' => 'https://example.test/uno.mp4' ) ) );

		$this->assertMatchesRegularExpression( '#<a href="https://example.test/a"><img[^>]*><video[^>]*></video></a>#', $html );
	}

	public function test_a_slide_without_a_video_has_none() {
		$this->mocks();

		$html = aranda_de_duero_home_carousel_html( array( array( 'image_id' => 1, 'title' => 'Uno', 'url' => '', 'video_url' => '' ) ) );

		$this->assertStringNotContainsString( '<video', $html );
	}

	public function test_the_video_of_a_slide_must_be_an_mp4_of_the_media_library() {
		\WP_Mock::userFunction( 'get_post_meta' )->andReturnUsing(
			function ( $id ) {
				return array( 10 => '55', 11 => '56', 12 => '' )[ $id ];
			}
		);
		\WP_Mock::userFunction( 'get_post_mime_type' )->andReturnUsing(
			function ( $id ) {
				return array( 55 => 'video/mp4', 56 => 'image/gif' )[ $id ];
			}
		);
		\WP_Mock::userFunction( 'wp_get_attachment_url' )->andReturn( 'https://example.test/video.mp4' );

		$this->assertSame( 'https://example.test/video.mp4', aranda_de_duero_slide_video_url( 10 ) );
		$this->assertSame( '', aranda_de_duero_slide_video_url( 11 ), 'a file that is not an MP4 is ignored' );
		$this->assertSame( '', aranda_de_duero_slide_video_url( 12 ), 'a slide with no video chosen' );
	}
}
