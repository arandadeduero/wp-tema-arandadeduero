<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/template-functions.php';
require_once dirname( __DIR__ ) . '/inc/waste.php';

class WasteTest extends TestCase {

	private $dir = '';

	public function setUp(): void {
		parent::setUp();
		$this->dir = sys_get_temp_dir() . '/aranda-waste-' . uniqid();
		mkdir( $this->dir . '/data/residuos', 0777, true );
		\WP_Mock::userFunction( 'get_template_directory' )->andReturn( $this->dir );
	}

	public function tearDown(): void {
		$file = $this->dir . '/data/residuos/dias-sin-deposito.json';
		if ( file_exists( $file ) ) {
			unlink( $file );
		}
		rmdir( $this->dir . '/data/residuos' );
		rmdir( $this->dir . '/data' );
		rmdir( $this->dir );
		parent::tearDown();
	}

	private function days( $json ) {
		file_put_contents( $this->dir . '/data/residuos/dias-sin-deposito.json', $json );
	}

	public function test_the_data_file_of_the_theme_is_valid_and_starts_empty() {
		$real = json_decode( (string) file_get_contents( dirname( __DIR__ ) . '/data/residuos/dias-sin-deposito.json' ), true );

		$this->assertIsArray( $real );
		$this->assertArrayHasKey( 'days', $real );
	}

	public function test_a_listed_day_is_a_day_without_deposit_with_its_reason() {
		$this->days( '{"days":[{"date":"2026-12-25","reason":"Navidad"},{"date":"2026-12-01"}]}' );

		$this->assertSame(
			array(
				'date'   => '2026-12-25',
				'reason' => 'Navidad',
			),
			aranda_de_duero_waste_ban( '2026-12-25' )
		);
		$this->assertSame( '', aranda_de_duero_waste_ban( '2026-12-01' )['reason'] );
		$this->assertNull( aranda_de_duero_waste_ban( '2026-12-02' ) );
	}

	public function test_a_missing_or_broken_file_means_every_day_is_allowed() {
		$this->assertNull( aranda_de_duero_waste_ban( '2026-12-25' ) );

		$this->days( 'esto no es json' );
		$this->assertNull( aranda_de_duero_waste_ban( '2026-12-25' ) );

		$this->days( '{"days":[{"date":"mañana"},{"reason":"sin fecha"}]}' );
		$this->assertSame( array(), aranda_de_duero_waste_ban_days() );
	}

	public function test_the_next_bans_are_the_later_days_in_order_and_limited() {
		$this->days( '{"days":[{"date":"2026-12-25"},{"date":"2026-11-01","reason":"Todos los Santos"},{"date":"2026-10-01"},{"date":"2027-01-01"}]}' );

		$next = aranda_de_duero_waste_next_bans( '2026-10-25', 2 );

		$this->assertSame( array( '2026-11-01', '2026-12-25' ), array_column( $next, 'date' ) );
		$this->assertSame( array(), aranda_de_duero_waste_next_bans( '2026-10-25', 0 ) );
	}

	public function test_the_phone_of_bulky_items_is_the_one_of_the_leaflet() {
		$info = aranda_de_duero_waste_info();

		$this->assertSame( '947 50 60 50', $info['bulky_phone'] );
		$this->assertSame( 'tel:947506050', $info['bulky_href'] );
	}
}
