<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/template-functions.php';

class MyCalendarStringsTest extends TestCase {

	public function test_untranslated_clear_filters_is_put_in_spanish() {
		$this->assertSame( 'Quitar filtros', aranda_de_duero_my_calendar_strings( 'Clear Filters', 'Clear Filters' ) );
	}

	public function test_a_translation_from_the_plugin_wins() {
		$this->assertSame( 'Borrar filtros', aranda_de_duero_my_calendar_strings( 'Borrar filtros', 'Clear Filters' ) );
	}

	public function test_other_strings_are_untouched() {
		$this->assertSame( 'Next', aranda_de_duero_my_calendar_strings( 'Next', 'Next' ) );
	}
}
