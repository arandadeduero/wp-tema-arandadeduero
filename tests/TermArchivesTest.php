<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/term-archives.php';

class TermArchivesTest extends TestCase {

	private const NOW = 1792000000; // 2026-10-14.

	public function test_a_term_with_recent_content_is_not_historic() {
		$this->assertFalse( aranda_de_duero_is_historic( self::NOW - 30 * DAY_IN_SECONDS, self::NOW ) );
	}

	public function test_a_term_is_historic_after_three_years_without_content() {
		$this->assertFalse( aranda_de_duero_is_historic( self::NOW - 2.9 * 365.25 * DAY_IN_SECONDS, self::NOW ) );
		$this->assertTrue( aranda_de_duero_is_historic( self::NOW - 3.1 * 365.25 * DAY_IN_SECONDS, self::NOW ) );
	}

	public function test_an_empty_term_is_not_historic_it_is_empty() {
		$this->assertFalse( aranda_de_duero_is_historic( 0, self::NOW ) );
	}

	public function test_the_newest_post_of_the_term_decides() {
		$term = (object) array(
			'taxonomy' => 'concejalia',
			'term_id'  => 7,
		);
		\WP_Mock::userFunction( 'get_posts' )->once()->andReturn( array( 123 ) );
		\WP_Mock::userFunction( 'get_post_timestamp' )->with( 123 )->andReturn( 1500000000 );

		$this->assertSame( 1500000000, aranda_de_duero_term_newest_timestamp( $term ) );
	}

	public function test_a_term_without_posts_has_no_newest_date() {
		$term = (object) array(
			'taxonomy' => 'tema',
			'term_id'  => 8,
		);
		\WP_Mock::userFunction( 'get_posts' )->andReturn( array() );

		$this->assertSame( 0, aranda_de_duero_term_newest_timestamp( $term ) );
	}
}
