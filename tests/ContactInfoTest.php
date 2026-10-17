<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/template-functions.php';

class ContactInfoTest extends TestCase {

	public function test_returns_the_town_hall_contact_data() {
		\WP_Mock::userFunction( 'apply_filters' )->andReturnUsing(
			function ( $tag, $value ) {
				return $value;
			}
		);

		$info = aranda_de_duero_contact_info();

		$this->assertSame( '947 500 100', $info['phone'] );
		$this->assertSame( 'tel:947500100', $info['phone_href'] );
		$this->assertSame( 'https://citaprevia.arandadeduero.es/', $info['appointment_url'] );
		$this->assertNotSame( '', $info['hours'] );
	}

	public function test_phone_link_matches_the_visible_number() {
		\WP_Mock::userFunction( 'apply_filters' )->andReturnUsing(
			function ( $tag, $value ) {
				return $value;
			}
		);

		$info = aranda_de_duero_contact_info();

		$this->assertSame( $info['phone_href'], 'tel:' . preg_replace( '/\D/', '', $info['phone'] ) );
	}
}
