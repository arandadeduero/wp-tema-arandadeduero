<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/template-functions.php';

if ( ! class_exists( 'WP_Widget' ) ) {
	// Minimal stand-in: the code under test only checks the type and reads id_base.
	class WP_Widget {
		public $id_base = '';

		public function __construct( $id_base = '' ) {
			$this->id_base = $id_base;
		}
	}
}

class EventsWidgetsTest extends TestCase {

	public function test_upcoming_events_widget_is_hidden() {
		\WP_Mock::onFilter( 'aranda_de_duero_show_events_widgets' )->with( false )->reply( false );

		$this->assertFalse( aranda_de_duero_hide_events_widgets( array( 'title' => 'Próximos' ), new WP_Widget( 'my_calendar_upcoming_widget' ) ) );
	}

	public function test_todays_events_widget_is_hidden() {
		\WP_Mock::onFilter( 'aranda_de_duero_show_events_widgets' )->with( false )->reply( false );

		$this->assertFalse( aranda_de_duero_hide_events_widgets( array(), new WP_Widget( 'my_calendar_today_widget' ) ) );
	}

	public function test_navigation_menu_widget_is_untouched() {
		$instance = array( 'nav_menu' => 3 );

		$this->assertSame( $instance, aranda_de_duero_hide_events_widgets( $instance, new WP_Widget( 'nav_menu' ) ) );
	}

	public function test_calendar_widget_itself_is_untouched() {
		$instance = array( 'title' => 'Calendario' );

		$this->assertSame( $instance, aranda_de_duero_hide_events_widgets( $instance, new WP_Widget( 'my_calendar_mini_widget' ) ) );
	}

	public function test_widget_can_be_shown_again_with_the_filter() {
		$instance = array( 'title' => 'Próximos' );
		\WP_Mock::onFilter( 'aranda_de_duero_show_events_widgets' )->with( false )->reply( true );

		$this->assertSame( $instance, aranda_de_duero_hide_events_widgets( $instance, new WP_Widget( 'my_calendar_upcoming_widget' ) ) );
	}

	public function test_a_widget_already_cancelled_by_another_filter_stays_cancelled() {
		$this->assertFalse( aranda_de_duero_hide_events_widgets( false, new WP_Widget( 'nav_menu' ) ) );
	}
}
