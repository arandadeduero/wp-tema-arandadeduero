<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/term-contact.php';

class TermContactTest extends TestCase {

	public function setUp(): void {
		parent::setUp();
		WP_Mock::userFunction( 'wp_strip_all_tags' )->andReturnUsing(
			function ( $text ) {
				return strip_tags( $text );
			}
		);
		WP_Mock::userFunction( 'is_email' )->andReturnUsing(
			function ( $email ) {
				return false !== filter_var( $email, FILTER_VALIDATE_EMAIL ) ? $email : false;
			}
		);
	}

	public function test_the_four_fields_are_registered() {
		$this->assertSame(
			array( 'concejalia_concejal', 'concejalia_telefono', 'concejalia_email', 'concejalia_ubicacion' ),
			array_keys( aranda_de_duero_term_contact_fields() )
		);
	}

	public function test_text_is_stripped_of_tags_and_extra_spaces() {
		$this->assertSame( 'Plaza Mayor, 13', aranda_de_duero_sanitize_contact_value( 'concejalia_ubicacion', "  <b>Plaza   Mayor,</b>\n13 " ) );
	}

	public function test_an_invalid_email_is_discarded() {
		$this->assertSame( '', aranda_de_duero_sanitize_contact_value( 'concejalia_email', 'no es un correo' ) );
		$this->assertSame( 'cultura@arandadeduero.es', aranda_de_duero_sanitize_contact_value( 'concejalia_email', ' cultura@arandadeduero.es ' ) );
	}

	public function test_a_phone_keeps_only_digits_and_separators() {
		$this->assertSame( '947 511458 / 947 512296', aranda_de_duero_sanitize_contact_value( 'concejalia_telefono', '947 511458 / 947 512296<script>' ) );
	}

	public function test_several_phones_become_several_links() {
		$this->assertSame(
			array(
				array(
					'label' => '092',
					'href'  => 'tel:092',
				),
				array(
					'label' => '947 512646',
					'href'  => 'tel:947512646',
				),
			),
			aranda_de_duero_phone_numbers( '092 / 947 512646' )
		);
	}

	public function test_text_without_digits_gives_no_phone() {
		$this->assertSame( array(), aranda_de_duero_phone_numbers( ' / ' ) );
		$this->assertSame( array(), aranda_de_duero_phone_numbers( '' ) );
	}

	public function test_only_filled_fields_are_returned() {
		WP_Mock::userFunction( 'get_term_meta' )->andReturnUsing(
			function ( $term_id, $key ) {
				$meta = array(
					'concejalia_concejal'  => 'Guillermo López Leal',
					'concejalia_email'     => 'invalido',
					'concejalia_ubicacion' => '',
				);
				return isset( $meta[ $key ] ) ? $meta[ $key ] : '';
			}
		);

		$this->assertSame( array( 'concejalia_concejal' => 'Guillermo López Leal' ), aranda_de_duero_term_contact( 7 ) );
	}
}
