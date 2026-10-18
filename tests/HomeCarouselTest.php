<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/home-carousel.php';

class HomeCarouselTest extends TestCase {

	private function mocks() {
		\WP_Mock::userFunction( 'esc_attr__' )->andReturnUsing(
			function ( $text ) {
				return $text;
			}
		);
		\WP_Mock::userFunction( '__' )->andReturnUsing(
			function ( $text ) {
				return $text;
			}
		);
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
}
