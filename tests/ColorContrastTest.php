<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/color-contrast.php';

class ColorContrastTest extends TestCase {

	public function test_black_on_white_is_maximum_contrast() {
		$this->assertEqualsWithDelta( 21.0, aranda_de_duero_contrast_ratio( '#000000', '#ffffff' ), 0.01 );
	}

	public function test_same_colour_has_ratio_one() {
		$this->assertEqualsWithDelta( 1.0, aranda_de_duero_contrast_ratio( '#0083c1', '#0083c1' ), 0.001 );
	}

	public function test_ratio_is_symmetric() {
		$this->assertEqualsWithDelta(
			aranda_de_duero_contrast_ratio( '#0083c1', '#ffffff' ),
			aranda_de_duero_contrast_ratio( '#ffffff', '#0083c1' ),
			0.0001
		);
	}

	public function test_shorthand_hex_is_expanded() {
		$this->assertEqualsWithDelta( 21.0, aranda_de_duero_contrast_ratio( '#000', '#fff' ), 0.01 );
	}

	public function test_invalid_colour_returns_null() {
		$this->assertNull( aranda_de_duero_contrast_ratio( 'rojo', '#ffffff' ) );
		$this->assertNull( aranda_de_duero_contrast_ratio( '', '#ffffff' ) );
	}

	public function test_theme_blue_on_white_passes_aa_but_light_grey_does_not() {
		$this->assertGreaterThanOrEqual( ARANDA_DE_DUERO_MIN_CONTRAST, aranda_de_duero_contrast_ratio( '#005B99', '#ffffff' ) );
		$this->assertLessThan( ARANDA_DE_DUERO_MIN_CONTRAST, aranda_de_duero_contrast_ratio( '#999999', '#ffffff' ) );
	}

	/**
	 * Build a fake setting whose manager knows one counterpart colour.
	 *
	 * @param string $id          Setting being validated.
	 * @param string $other_id    Counterpart setting id.
	 * @param string $other_value Counterpart colour.
	 * @return object
	 */
	private function setting( $id, $other_id, $other_value ) {
		$other = new class( $other_value ) {
			private $value;

			public function __construct( $value ) {
				$this->value = $value;
			}

			public function value() {
				return $this->value;
			}

			public function post_value( $default = null ) {
				return $default;
			}
		};

		$manager = new class( $other_id, $other ) {
			private $id;
			private $setting;

			public function __construct( $id, $setting ) {
				$this->id      = $id;
				$this->setting = $setting;
			}

			public function get_setting( $id ) {
				return $id === $this->id ? $this->setting : null;
			}
		};

		return (object) array(
			'id'      => $id,
			'manager' => $manager,
		);
	}

	public function test_validation_rejects_unreadable_text_colour() {
		\WP_Mock::passthruFunction( '__' );
		\WP_Mock::userFunction( 'number_format_i18n' )->andReturnUsing(
			function ( $n, $d = 0 ) {
				return number_format( $n, $d );
			}
		);

		$errors  = new class() {
			public $messages = array();

			public function add( $code, $message ) {
				$this->messages[ $code ] = $message;
			}
		};
		$setting = $this->setting( 'aranda_de_duero_text_color', 'aranda_de_duero_background_color', '#ffffff' );

		$result = aranda_de_duero_validate_color_contrast( $errors, '#cccccc', $setting );

		$this->assertArrayHasKey( 'low_contrast', $result->messages );
	}

	public function test_validation_accepts_readable_text_colour() {
		$errors  = new class() {
			public $messages = array();

			public function add( $code, $message ) {
				$this->messages[ $code ] = $message;
			}
		};
		$setting = $this->setting( 'aranda_de_duero_text_color', 'aranda_de_duero_background_color', '#ffffff' );

		$result = aranda_de_duero_validate_color_contrast( $errors, '#222222', $setting );

		$this->assertSame( array(), $result->messages );
	}

	public function test_validation_checks_background_against_foregrounds() {
		\WP_Mock::passthruFunction( '__' );
		\WP_Mock::userFunction( 'number_format_i18n' )->andReturnUsing(
			function ( $n, $d = 0 ) {
				return number_format( $n, $d );
			}
		);

		$errors = new class() {
			public $messages = array();

			public function add( $code, $message ) {
				$this->messages[ $code ] = $message;
			}
		};
		// Black text on a dark background being saved as the page background.
		$setting = $this->setting( 'aranda_de_duero_background_color', 'aranda_de_duero_text_color', '#000000' );

		$result = aranda_de_duero_validate_color_contrast( $errors, '#111111', $setting );

		$this->assertArrayHasKey( 'low_contrast', $result->messages );
	}
}
