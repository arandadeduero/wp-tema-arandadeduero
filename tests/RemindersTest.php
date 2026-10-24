<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/reminders.php';

class RemindersTest extends TestCase {

	private function mocks() {
		\WP_Mock::userFunction( '__' )->andReturnUsing(
			function ( $text ) {
				return $text;
			}
		);
		\WP_Mock::userFunction( 'apply_filters' )->andReturnUsing(
			function ( $tag, $value ) {
				return $value;
			}
		);
	}

	public function test_both_addresses_receive_the_reminders() {
		$this->mocks();

		$this->assertSame( array( 'informatica@arandadeduero.es', 'glopez@arandadeduero.es' ), aranda_de_duero_reminder_recipients() );
	}

	public function test_the_delegations_are_reviewed_every_six_months() {
		$this->mocks();

		$dates = array_column( aranda_de_duero_periodic_reminders( 2026 ), 'date' );

		$this->assertSame( array( '2026-01-01', '2026-07-01' ), $dates );
	}

	public function test_the_reminder_of_15_june_2027_exists_only_that_year() {
		$this->mocks();

		$this->assertContains( '2027-06-15', array_column( aranda_de_duero_periodic_reminders( 2027 ), 'date' ) );
		$this->assertNotContains( '2027-06-15', array_column( aranda_de_duero_periodic_reminders( 2028 ), 'date' ) );
	}

	public function test_ids_are_unique_across_years() {
		$this->mocks();

		$ids = array_merge( array_column( aranda_de_duero_periodic_reminders( 2026 ), 'id' ), array_column( aranda_de_duero_periodic_reminders( 2027 ), 'id' ) );

		$this->assertSame( $ids, array_values( array_unique( $ids ) ) );
	}

	public function test_a_reminder_is_due_on_its_date_and_the_week_after() {
		$list = array( array( 'id' => 'a', 'date' => '2027-06-15', 'subject' => 's', 'message' => 'm' ) );

		$this->assertSame( array(), aranda_de_duero_reminders_due_today( '2027-06-14', $list, array() ) );
		$this->assertCount( 1, aranda_de_duero_reminders_due_today( '2027-06-15', $list, array() ) );
		$this->assertCount( 1, aranda_de_duero_reminders_due_today( '2027-06-22', $list, array() ) );
		$this->assertSame( array(), aranda_de_duero_reminders_due_today( '2027-06-23', $list, array() ) );
	}

	public function test_an_old_reminder_is_not_sent_when_the_theme_is_deployed_later() {
		$list = array( array( 'id' => 'a', 'date' => '2026-07-01', 'subject' => 's', 'message' => 'm' ) );

		$this->assertSame( array(), aranda_de_duero_reminders_due_today( '2026-10-24', $list, array() ) );
	}

	public function test_a_reminder_already_sent_is_not_sent_again() {
		$list = array( array( 'id' => 'a', 'date' => '2027-01-01', 'subject' => 's', 'message' => 'm' ) );

		$this->assertSame( array(), aranda_de_duero_reminders_due_today( '2027-01-02', $list, array( 'a' ) ) );
	}

	public function test_the_check_sends_the_reminder_and_remembers_its_id() {
		$this->mocks();
		\WP_Mock::userFunction( 'current_time' )->andReturn( '2027-07-01' );
		\WP_Mock::userFunction( 'get_option' )->andReturn( array( 'delegaciones-2027-01' ) );
		\WP_Mock::userFunction( 'wp_mail' )->once()->with( array( 'informatica@arandadeduero.es', 'glopez@arandadeduero.es' ), \Mockery::type( 'string' ), \Mockery::type( 'string' ) )->andReturn( true );
		\WP_Mock::userFunction( 'update_option' )->once()->with( 'aranda_reminders_sent', array( 'delegaciones-2027-01', 'delegaciones-2027-07' ), false );

		aranda_de_duero_periodic_reminders_check();
		$this->assertTrue( true );
	}

	public function test_a_failed_mail_is_not_remembered() {
		$this->mocks();
		\WP_Mock::userFunction( 'current_time' )->andReturn( '2027-07-01' );
		\WP_Mock::userFunction( 'get_option' )->andReturn( array() );
		\WP_Mock::userFunction( 'wp_mail' )->once()->andReturn( false );
		\WP_Mock::userFunction( 'update_option' )->never();

		aranda_de_duero_periodic_reminders_check();
		$this->assertTrue( true );
	}

	public function test_nothing_is_sent_on_an_ordinary_day() {
		$this->mocks();
		\WP_Mock::userFunction( 'current_time' )->andReturn( '2027-03-10' );
		\WP_Mock::userFunction( 'get_option' )->andReturn( array() );
		\WP_Mock::userFunction( 'wp_mail' )->never();

		aranda_de_duero_periodic_reminders_check();
		$this->assertTrue( true );
	}
}
