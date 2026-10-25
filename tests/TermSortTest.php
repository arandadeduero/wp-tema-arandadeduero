<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/term-archives.php';
require_once dirname( __DIR__ ) . '/inc/term-index.php';

class TermSortTest extends TestCase {

	use Aranda_WP_Stubs;

	public function test_terms_are_sorted_by_name_ignoring_case_and_accents() {
		$this->stub_wp_basics();

		$names = array( 'Hacienda', 'Festejos', 'Educación', 'Alcaldía', 'Deportes', 'urbanismo', 'Ámbito' );
		$terms = array_map(
			function ( $name ) {
				return (object) array( 'name' => $name );
			},
			$names
		);

		$sorted = array_map(
			function ( $term ) {
				return $term->name;
			},
			aranda_de_duero_sort_terms_by_name( $terms )
		);

		$this->assertSame( array( 'Alcaldía', 'Ámbito', 'Deportes', 'Educación', 'Festejos', 'Hacienda', 'urbanismo' ), $sorted );
	}
}
