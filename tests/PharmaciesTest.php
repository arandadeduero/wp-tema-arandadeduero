<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/reminders.php';
require_once dirname( __DIR__ ) . '/inc/pharmacies.php';

class PharmaciesTest extends TestCase {

	private function mocks() {
		\WP_Mock::userFunction( 'get_template_directory' )->andReturn( dirname( __DIR__ ) );
		\WP_Mock::userFunction( '__' )->andReturnUsing(
			function ( $text ) {
				return $text;
			}
		);
		\WP_Mock::userFunction( '_n' )->andReturnUsing(
			function ( $single, $plural, $number ) {
				return 1 === $number ? $single : $plural;
			}
		);
		\WP_Mock::userFunction( 'apply_filters' )->andReturnUsing(
			function ( $tag, $value ) {
				return $value;
			}
		);
	}

	public function test_the_calendar_of_2026_has_every_day_and_every_pharmacy_is_in_the_catalog() {
		$this->mocks();

		$year    = aranda_de_duero_pharmacy_guard_year( 2026 );
		$catalog = aranda_de_duero_pharmacy_catalog();

		$this->assertCount( 365, $year['schedule'] );
		$this->assertCount( 12, $catalog );
		foreach ( $year['schedule'] as $date => $id ) {
			$this->assertArrayHasKey( $id, $catalog, $date );
		}
	}

	public function test_the_guard_changes_at_ten_in_the_morning() {
		$this->assertSame( '2026-10-23', aranda_de_duero_pharmacy_guard_day( '2026-10-24 00:30:00' ) );
		$this->assertSame( '2026-10-23', aranda_de_duero_pharmacy_guard_day( '2026-10-24 09:59:59' ) );
		$this->assertSame( '2026-10-24', aranda_de_duero_pharmacy_guard_day( '2026-10-24 10:00:00' ) );
		$this->assertSame( '2026-10-24', aranda_de_duero_pharmacy_guard_day( '2026-10-24 23:59:59' ) );
		$this->assertSame( '2025-12-31', aranda_de_duero_pharmacy_guard_day( '2026-01-01 03:00:00' ) );
	}

	public function test_the_map_links_search_by_address_in_both_apps() {
		$links = aranda_de_duero_pharmacy_map_links(
			array(
				'address'    => 'Avenida Castilla, 58',
				'postalCode' => '09400',
				'city'       => 'Aranda de Duero',
			)
		);

		$this->assertSame( array( 'Google Maps', 'Apple Maps' ), array_column( $links, 'label' ) );
		$this->assertSame( 'https://www.google.com/maps/search/?api=1&query=Avenida%20Castilla%2C%2058%2C%2009400%2C%20Aranda%20de%20Duero', $links[0]['url'] );
		$this->assertSame( 'https://maps.apple.com/?q=Avenida%20Castilla%2C%2058%2C%2009400%2C%20Aranda%20de%20Duero', $links[1]['url'] );
		$this->assertSame( array(), aranda_de_duero_pharmacy_map_links( array() ) );
	}

	public function test_a_day_gives_its_pharmacy() {
		$this->mocks();

		$entry = aranda_de_duero_pharmacy_guard_for_date( '2026-01-01' );

		$this->assertSame( 'A. SANZ HERNÁN', $entry['pharmacy']['name'] );
		$this->assertFalse( $entry['low_confidence'] );
	}

	public function test_a_day_the_source_is_not_sure_about_is_flagged() {
		$this->mocks();

		$this->assertTrue( aranda_de_duero_pharmacy_guard_for_date( '2026-12-24' )['low_confidence'] );
	}

	public function test_a_year_without_a_file_or_a_bad_date_gives_nothing() {
		$this->mocks();

		$this->assertNull( aranda_de_duero_pharmacy_guard_for_date( '2099-01-01' ) );
		$this->assertNull( aranda_de_duero_pharmacy_guard_for_date( 'mañana' ) );
		$this->assertNull( aranda_de_duero_pharmacy_guard_year( 2099 ) );
	}

	public function test_the_next_days_start_the_day_after() {
		$this->mocks();

		$dates = array_column( aranda_de_duero_pharmacy_guard_after( '2026-01-01', 3 ), 'date' );

		$this->assertSame( array( '2026-01-02', '2026-01-03', '2026-01-04' ), $dates );
		$this->assertSame( array(), aranda_de_duero_pharmacy_guard_after( '2099-12-30', 3 ) );
	}

	public function test_phone_is_grouped_in_threes() {
		$this->assertSame( '947 500 269', aranda_de_duero_pharmacy_phone( '947500269' ) );
		$this->assertSame( '112', aranda_de_duero_pharmacy_phone( '112' ) );
	}

	public function test_days_to_the_end_of_the_year() {
		$this->assertSame( 15, aranda_de_duero_pharmacy_days_to_year_end( '2026-12-16' ) );
		$this->assertSame( 0, aranda_de_duero_pharmacy_days_to_year_end( '2026-12-31' ) );
	}

	public function test_no_reminder_before_fifteen_days() {
		$this->assertSame( array(), aranda_de_duero_pharmacy_reminders_due( '2026-12-15', false, array() ) );
	}

	public function test_each_reminder_is_due_once_the_days_are_reached() {
		$this->assertSame( array( 15 ), aranda_de_duero_pharmacy_reminders_due( '2026-12-16', false, array() ) );
		$this->assertSame( array(), aranda_de_duero_pharmacy_reminders_due( '2026-12-20', false, array( 15 ) ) );
		$this->assertSame( array( 10 ), aranda_de_duero_pharmacy_reminders_due( '2026-12-21', false, array( 15 ) ) );
		$this->assertSame( array( 5 ), aranda_de_duero_pharmacy_reminders_due( '2026-12-26', false, array( 15, 10 ) ) );
	}

	public function test_missed_days_are_sent_together_not_one_by_one() {
		$this->assertSame( array( 15, 10 ), aranda_de_duero_pharmacy_reminders_due( '2026-12-23', false, array() ) );
	}

	public function test_no_reminder_when_next_year_is_loaded() {
		$this->assertSame( array(), aranda_de_duero_pharmacy_reminders_due( '2026-12-28', true, array() ) );
	}

	public function test_the_mail_says_the_days_and_the_year() {
		$this->mocks();

		$mail = aranda_de_duero_pharmacy_reminder_mail( 2026, 5 );

		$this->assertSame( 'Farmacias de guardia: faltan 5 días para cargar el calendario de 2027', $mail['subject'] );
		$this->assertStringContainsString( 'data/farmacias/2027.json', $mail['message'] );
		$this->assertSame( 'Farmacias de guardia: falta 1 día para cargar el calendario de 2027', aranda_de_duero_pharmacy_reminder_mail( 2026, 1 )['subject'] );
	}

	public function test_the_check_sends_one_mail_and_remembers_it() {
		$this->mocks();
		\WP_Mock::userFunction( 'current_time' )->andReturn( '2098-12-20' );
		\WP_Mock::userFunction( 'get_option' )->andReturn( array() );
		\WP_Mock::userFunction( 'wp_mail' )->once()->with( array( 'informatica@arandadeduero.es', 'glopez@arandadeduero.es' ), \Mockery::type( 'string' ), \Mockery::type( 'string' ) )->andReturn( true );
		\WP_Mock::userFunction( 'update_option' )->once()->with( 'aranda_pharmacy_reminders_sent', array( 2098 => array( 15 ) ), false );

		aranda_de_duero_pharmacy_reminder_check();
		$this->assertTrue( true );
	}

	public function test_a_failed_mail_is_not_remembered_so_it_is_tried_again() {
		$this->mocks();
		\WP_Mock::userFunction( 'current_time' )->andReturn( '2098-12-20' );
		\WP_Mock::userFunction( 'get_option' )->andReturn( array() );
		\WP_Mock::userFunction( 'wp_mail' )->once()->andReturn( false );
		\WP_Mock::userFunction( 'update_option' )->never();

		aranda_de_duero_pharmacy_reminder_check();
		$this->assertTrue( true );
	}

	public function test_the_check_sends_nothing_when_already_sent() {
		$this->mocks();
		\WP_Mock::userFunction( 'current_time' )->andReturn( '2098-12-20' );
		\WP_Mock::userFunction( 'get_option' )->andReturn( array( 2098 => array( 15 ) ) );
		\WP_Mock::userFunction( 'wp_mail' )->never();

		aranda_de_duero_pharmacy_reminder_check();
		$this->assertTrue( true );
	}
}
