<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/term-archives.php';
require_once dirname( __DIR__ ) . '/inc/term-index.php';

class TermIndexTest extends TestCase {

	public function test_the_three_index_addresses_are_recognised() {
		$this->assertSame( 'tema', aranda_de_duero_term_index_kind( 'tema' ) );
		$this->assertSame( 'concejalia', aranda_de_duero_term_index_kind( '/concejalia/' ) );
		$this->assertSame( 'tramite', aranda_de_duero_term_index_kind( 'Tramite/' ) );
	}

	public function test_any_other_address_is_not_an_index() {
		$this->assertSame( '', aranda_de_duero_term_index_kind( 'tema/salud' ) );
		$this->assertSame( '', aranda_de_duero_term_index_kind( 'tramites' ) );
		$this->assertSame( '', aranda_de_duero_term_index_kind( '' ) );
	}

	public function test_a_name_that_is_only_a_number_is_numeric() {
		$this->assertTrue( aranda_de_duero_is_numeric_term_name( '29' ) );
		$this->assertTrue( aranda_de_duero_is_numeric_term_name( ' 57 ' ) );
		$this->assertFalse( aranda_de_duero_is_numeric_term_name( 'Juventud' ) );
		$this->assertFalse( aranda_de_duero_is_numeric_term_name( 'Plan 2030' ) );
		$this->assertFalse( aranda_de_duero_is_numeric_term_name( '' ) );
	}
}
