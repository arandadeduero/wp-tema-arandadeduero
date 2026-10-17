<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/performance.php';

class SliderDeferTest extends TestCase {

	/**
	 * Slide backgrounds as Smart Slider prints them.
	 *
	 * @param string[] $files Image file names, one per slide.
	 * @return string
	 */
	private function slider( array $files ) {
		$html = '<div class="n2-ss-slide-backgrounds n2-ow-all">';
		foreach ( $files as $i => $file ) {
			$html .= '<div class="n2-ss-slide-background" data-public-id="' . ( $i + 1 ) . '" data-mode="fill">'
				. '<div class="n2-ss-slide-background-image" data-blur="0" data-opacity="100" data-x="50" data-y="50" data-alt="" data-title="">'
				. '<picture class="skip-lazy jetpack-lazy-image" data-skip-lazy="1">'
				. '<img src="//www.arandadeduero.es/wp-content/uploads/' . $file . '" alt="" title="" loading="lazy" class="skip-lazy jetpack-lazy-image" data-skip-lazy="1">'
				. '</picture></div></div>';
		}
		return $html . '</div>';
	}

	public function test_first_image_is_made_eager_and_kept() {
		$out = aranda_de_duero_defer_slider_images( $this->slider( array( 'a.gif', 'b.gif', 'c.gif' ) ) );

		$this->assertStringContainsString( '<img loading="eager"', $out );
		$this->assertStringContainsString( 'src="//www.arandadeduero.es/wp-content/uploads/a.gif"', $out );
		// fetchpriority="high" made the first paint ~650 ms slower in testing; keep it out.
		$this->assertStringNotContainsString( 'fetchpriority', $out );
		$this->assertSame( 1, substr_count( $out, 'loading="eager"' ) );
		$this->assertSame( 0, substr_count( $out, 'loading="lazy"' ) - 2, 'first <img> loses lazy, the other two keep their attributes' );
	}

	public function test_other_images_are_swapped_for_the_placeholder() {
		$out = aranda_de_duero_defer_slider_images( $this->slider( array( 'a.gif', 'b.gif', 'c.gif' ) ) );

		$this->assertSame( 2, substr_count( $out, 'data-aranda-src=' ) );
		$this->assertStringContainsString( 'data-aranda-src="//www.arandadeduero.es/wp-content/uploads/b.gif"', $out );
		$this->assertStringContainsString( 'data-aranda-src="//www.arandadeduero.es/wp-content/uploads/c.gif"', $out );
		$this->assertSame( 2, substr_count( $out, 'src="' . ARANDA_DE_DUERO_PLACEHOLDER_GIF . '"' ) );
		// The real URLs of the deferred slides must not stay in a plain src attribute.
		$this->assertStringNotContainsString( ' src="//www.arandadeduero.es/wp-content/uploads/b.gif"', $out );
		$this->assertStringNotContainsString( ' src="//www.arandadeduero.es/wp-content/uploads/c.gif"', $out );
	}

	public function test_restore_script_is_appended() {
		$out = aranda_de_duero_defer_slider_images( $this->slider( array( 'a.gif', 'b.gif' ) ) );

		$this->assertStringContainsString( '<script>', $out );
		$this->assertStringContainsString( 'img[data-aranda-src]', $out );
		$this->assertStringContainsString( 'setTimeout(r,5000)', $out );
	}

	public function test_single_slide_is_left_untouched() {
		$html = $this->slider( array( 'a.gif' ) );

		$this->assertSame( $html, aranda_de_duero_defer_slider_images( $html ) );
	}

	public function test_unrecognised_markup_is_left_untouched() {
		$html = '<div class="other-slider"><img src="x.gif"><img src="y.gif"></div>';

		$this->assertSame( $html, aranda_de_duero_defer_slider_images( $html ) );
	}

	public function test_empty_output_is_left_untouched() {
		$this->assertSame( '', aranda_de_duero_defer_slider_images( '' ) );
	}

	public function test_picture_with_source_element_is_not_rewritten() {
		$html = '<div class="n2-ss-slide-background-image"><picture><source srcset="a.webp"><img src="a.gif"></picture></div>'
			. '<div class="n2-ss-slide-background-image"><picture><source srcset="b.webp"><img src="b.gif"></picture></div>';

		$this->assertSame( $html, aranda_de_duero_defer_slider_images( $html ) );
	}
}
