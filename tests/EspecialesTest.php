<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/especiales.php';

class EspecialesTest extends TestCase {

	private function mocks() {
		\WP_Mock::userFunction( 'esc_url' )->andReturnUsing(
			function ( $text ) {
				return $text;
			}
		);
		\WP_Mock::userFunction( 'esc_html' )->andReturnUsing(
			function ( $text ) {
				return $text;
			}
		);
		\WP_Mock::userFunction( 'wp_get_attachment_image' )->andReturnUsing(
			function ( $id, $size, $icon, $attr ) {
				return '<img data-id="' . $id . '" loading="' . $attr['loading'] . '">';
			}
		);
	}

	private function item( $title, $image = 5 ) {
		return array(
			'title'    => $title,
			'url'      => 'https://example.test/' . $title,
			'image_id' => $image,
		);
	}

	public function test_a_javascript_link_is_never_kept() {
		$posts = array( new WP_Post(), new WP_Post() );
		$posts[0]->ID = 1;
		$posts[1]->ID = 2;
		$posts[0]->post_title = 'Uno';
		$posts[1]->post_title = 'Dos';
		$links = array( 1 => 'javascript:alert(1)', 2 => 'https://pmus.arandadeduero.es/' );

		\WP_Mock::userFunction( 'get_posts' )->andReturn( $posts );
		\WP_Mock::userFunction( 'get_post_meta' )->andReturnUsing(
			function ( $id ) use ( $links ) {
				return $links[ $id ];
			}
		);
		// Same effect as WordPress' esc_url_raw() for a scheme that is not allowed.
		\WP_Mock::userFunction( 'esc_url_raw' )->andReturnUsing(
			function ( $url ) {
				return 0 === strpos( $url, 'javascript:' ) ? '' : $url;
			}
		);
		\WP_Mock::userFunction( 'get_permalink' )->andReturn( 'https://www.arandadeduero.es/especial/' );
		\WP_Mock::userFunction( 'get_post_thumbnail_id' )->andReturn( 0 );

		$items = aranda_de_duero_home_especiales();

		$this->assertSame( 'https://www.arandadeduero.es/especial/', $items[0]['url'] );
		$this->assertSame( 'https://pmus.arandadeduero.es/', $items[1]['url'] );
	}

	public function test_every_special_is_printed_with_no_button_or_pagination() {
		$this->mocks();

		$html = aranda_de_duero_home_especiales_html( array( $this->item( 'a' ), $this->item( 'b' ), $this->item( 'c' ) ) );

		$this->assertStringContainsString( '>a</a>', $html );
		$this->assertStringContainsString( '>b</a>', $html );
		$this->assertStringContainsString( '>c</a>', $html );
		$this->assertStringNotContainsString( '<details', $html );
		$this->assertStringNotContainsString( '<button', $html );
	}

	public function test_each_card_has_a_single_link_and_a_decorative_image() {
		$this->mocks();

		$html = aranda_de_duero_especial_card_html( $this->item( 'a' ) );

		$this->assertSame( 1, substr_count( $html, '<a ' ) );
		$this->assertStringContainsString( 'class="bold stretched-link" href="https://example.test/a"', $html );
	}

	public function test_only_the_first_row_is_loaded_eagerly() {
		$this->mocks();

		$items = array();
		for ( $i = 0; $i < 8; $i++ ) {
			$items[] = $this->item( 'n' . $i, 10 + $i );
		}

		$html = aranda_de_duero_home_especiales_html( $items );

		$this->assertStringContainsString( 'data-id="10" loading="eager"', $html );
		$this->assertStringContainsString( 'data-id="15" loading="eager"', $html );
		$this->assertStringContainsString( 'data-id="16" loading="lazy"', $html );
	}

	public function test_a_card_without_image_still_renders() {
		$this->mocks();

		$html = aranda_de_duero_especial_card_html( $this->item( 'a', 0 ) );

		$this->assertStringNotContainsString( 'home-especial-image', $html );
		$this->assertStringContainsString( '>a</a>', $html );
	}
}
