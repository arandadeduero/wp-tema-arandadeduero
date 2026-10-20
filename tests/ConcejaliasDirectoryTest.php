<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/concejalias-directory.php';

class ConcejaliasDirectoryTest extends TestCase {

	public function setUp(): void {
		parent::setUp();
		WP_Mock::userFunction( 'wp_strip_all_tags' )->andReturnUsing(
			function ( $text ) {
				return strip_tags( $text );
			}
		);
		WP_Mock::userFunction( 'esc_url' )->andReturnUsing(
			function ( $url ) {
				return $url;
			}
		);
	}

	public function test_a_department_is_matched_by_the_start_of_its_name() {
		$this->assertSame( 'hacienda', aranda_de_duero_directory_concejalia_slug( 'Gestión tributaria (liquidaciones, catastro…)' ) );
		$this->assertSame( 'seguridad-ciudadana', aranda_de_duero_directory_concejalia_slug( 'Policía / gestión de multas' ) );
		$this->assertSame( 'servicios-sociales', aranda_de_duero_directory_concejalia_slug( ' CEAS / Servicios sociales (Francesas) ' ) );
		$this->assertSame( 'cultura', aranda_de_duero_directory_concejalia_slug( '<strong>Cultura</strong>' ) );
	}

	public function test_a_department_without_concejalia_has_no_slug() {
		$this->assertSame( '', aranda_de_duero_directory_concejalia_slug( 'Electricidad / alumbrado' ) );
		$this->assertSame( '', aranda_de_duero_directory_concejalia_slug( 'Departamento' ) );
		$this->assertSame( '', aranda_de_duero_directory_concejalia_slug( '' ) );
	}

	public function test_the_department_becomes_a_link() {
		$term = (object) array( 'slug' => 'turismo' );
		WP_Mock::userFunction( 'get_term_by' )->with( 'slug', 'turismo', 'concejalia' )->andReturn( $term );
		WP_Mock::userFunction( 'get_term_link' )->with( $term )->andReturn( 'https://example.org/concejalia/turismo/' );

		$this->assertSame( '<a href="https://example.org/concejalia/turismo/">Turismo</a>', aranda_de_duero_directory_link_cell( 'Turismo', 2 ) );
	}

	public function test_a_cell_that_already_has_a_link_is_not_wrapped_again() {
		$cell = '<a href="https://example.org/">Turismo</a>';

		$this->assertSame( $cell, aranda_de_duero_directory_link_cell( $cell, 2 ) );
	}

	public function test_other_tables_are_not_touched() {
		$this->assertSame( 'Turismo', aranda_de_duero_directory_link_cell( 'Turismo', 5 ) );
	}

	public function test_a_missing_term_leaves_the_text_as_it_is() {
		WP_Mock::userFunction( 'get_term_by' )->andReturn( false );

		$this->assertSame( 'Turismo', aranda_de_duero_directory_link_cell( 'Turismo', '2' ) );
	}
}
