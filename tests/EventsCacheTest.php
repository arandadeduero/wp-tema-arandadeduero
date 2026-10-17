<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/events.php';

class EventsCacheTest extends TestCase {

	public function test_cached_events_are_returned_without_querying() {
		$events = array( array( 'id' => 1, 'title' => 'Concierto' ) );

		\WP_Mock::userFunction( 'get_transient' )
			->once()
			->with( 'aranda_upcoming_events_50' )
			->andReturn( $events );
		// Anything past the cache hit would need these and would fail the test.
		\WP_Mock::userFunction( 'set_transient' )->never();
		\WP_Mock::userFunction( 'aranda_de_duero_content' )->never();

		$this->assertSame( $events, aranda_de_duero_get_upcoming_events( 50 ) );
	}

	public function test_empty_cached_list_is_still_a_cache_hit() {
		\WP_Mock::userFunction( 'get_transient' )->once()->andReturn( array() );
		\WP_Mock::userFunction( 'aranda_de_duero_content' )->never();

		$this->assertSame( array(), aranda_de_duero_get_upcoming_events( 50 ) );
	}

	public function test_miss_stores_result_and_remembers_limit() {
		$query = new class() {
			public function have_posts() {
				return false;
			}
		};

		\WP_Mock::userFunction( 'get_transient' )->once()->andReturn( false );
		\WP_Mock::userFunction( 'aranda_de_duero_content' )->once()->andReturn( $query );
		\WP_Mock::userFunction( 'set_transient' )->once()->with( 'aranda_upcoming_events_10', array(), 600 );
		\WP_Mock::userFunction( 'get_option' )->andReturn( array() );
		\WP_Mock::userFunction( 'update_option' )->once()->with( 'aranda_upcoming_events_limits', array( 10 ), false );
		if ( ! defined( 'MINUTE_IN_SECONDS' ) ) {
			define( 'MINUTE_IN_SECONDS', 60 );
		}

		$this->assertSame( array(), aranda_de_duero_get_upcoming_events( 10 ) );
	}

	public function test_flush_deletes_every_remembered_limit() {
		\WP_Mock::userFunction( 'get_option' )->andReturn( array( 50, 10 ) );
		\WP_Mock::userFunction( 'delete_transient' )->once()->with( 'aranda_upcoming_events_50' );
		\WP_Mock::userFunction( 'delete_transient' )->once()->with( 'aranda_upcoming_events_10' );

		aranda_de_duero_flush_events_cache();
		$this->assertTrue( true );
	}

	public function test_only_event_posts_flush_the_cache() {
		\WP_Mock::userFunction( 'get_post_type' )->with( 5 )->andReturn( 'post' );
		\WP_Mock::userFunction( 'get_option' )->never();

		aranda_de_duero_maybe_flush_events_cache( 5 );
		$this->assertTrue( true );
	}
}
