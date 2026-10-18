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
		\WP_Mock::userFunction( 'esc_html__' )->andReturnUsing(
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

	private function item( $title, $featured, $image = 5 ) {
		return array(
			'title'    => $title,
			'url'      => 'https://example.test/' . $title,
			'image_id' => $image,
			'featured' => $featured,
		);
	}

	public function test_featured_cards_are_shown_and_the_rest_wait_inside_details() {
		$this->mocks();

		$html = aranda_de_duero_home_especiales_html( array( $this->item( 'a', true ), $this->item( 'b', false ) ) );

		$this->assertStringContainsString( '<details class="especiales-mas', $html );
		$this->assertLessThan( strpos( $html, '<details' ), strpos( $html, '>a</a>' ) );
		$this->assertGreaterThan( strpos( $html, '<details' ), strpos( $html, '>b</a>' ) );
	}

	public function test_without_hidden_cards_there_is_no_button() {
		$this->mocks();

		$this->assertStringNotContainsString( '<details', aranda_de_duero_home_especiales_html( array( $this->item( 'a', true ) ) ) );
	}

	public function test_each_card_has_a_single_link_and_a_decorative_image() {
		$this->mocks();

		$html = aranda_de_duero_especial_card_html( $this->item( 'a', true ) );

		$this->assertSame( 1, substr_count( $html, '<a ' ) );
		$this->assertStringContainsString( 'class="bold stretched-link" href="https://example.test/a"', $html );
	}

	public function test_only_the_first_row_is_loaded_eagerly() {
		$this->mocks();

		$items = array();
		for ( $i = 0; $i < 8; $i++ ) {
			$items[] = $this->item( 'n' . $i, true, 10 + $i );
		}

		$html = aranda_de_duero_home_especiales_html( $items );

		$this->assertStringContainsString( 'data-id="10" loading="eager"', $html );
		$this->assertStringContainsString( 'data-id="15" loading="eager"', $html );
		$this->assertStringContainsString( 'data-id="16" loading="lazy"', $html );
	}

	public function test_a_card_without_image_still_renders() {
		$this->mocks();

		$html = aranda_de_duero_especial_card_html( $this->item( 'a', true, 0 ) );

		$this->assertStringNotContainsString( 'home-especial-image', $html );
		$this->assertStringContainsString( '>a</a>', $html );
	}
}
