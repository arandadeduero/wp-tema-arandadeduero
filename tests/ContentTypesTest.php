<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/content-types.php';

class ContentTypesTest extends TestCase {

	public function test_the_six_post_types_and_six_taxonomies_are_defined() {
		$this->assertSame( array( 'seccion-principal', 'actividad', 'especial', 'tramite', 'servicio-municipal', 'proyecto-europeo' ), array_keys( aranda_de_duero_post_types() ) );
		$this->assertSame(
			array( 'seccion', 'concejalia', 'tema', 'servicio', 'categoria_tramite', 'home_section_category' ),
			array_keys( aranda_de_duero_taxonomies() )
		);
	}

	public function test_the_addresses_of_the_four_old_types_do_not_change() {
		$types = aranda_de_duero_post_types();
		unset( $types['servicio-municipal'], $types['proyecto-europeo'] );

		foreach ( $types as $slug => $args ) {
			$this->assertSame( $slug, $args['rewrite']['slug'] );
			$this->assertFalse( $args['has_archive'] );
		}
		foreach ( aranda_de_duero_taxonomies() as $slug => $definition ) {
			$this->assertSame( $slug, $definition[1]['rewrite']['slug'] );
			$this->assertSame( $slug, $definition[1]['rest_base'] );
		}
	}

	public function test_servicio_municipal_has_its_own_address_so_it_does_not_clash_with_the_old_servicio_taxonomy() {
		$types = aranda_de_duero_post_types();

		$this->assertSame( 'servicios', $types['servicio-municipal']['rewrite']['slug'] );
		$this->assertSame( 'fondos-europeos', $types['proyecto-europeo']['rewrite']['slug'] );
		$this->assertSame( array( 'concejalia' ), $types['servicio-municipal']['taxonomies'] );
	}

	public function test_servicio_municipal_is_edited_with_the_full_block_editor_and_an_excerpt_field() {
		$args = aranda_de_duero_post_types()['servicio-municipal'];

		// show_in_rest is what turns on the block editor (Gutenberg), not just a plain text box.
		$this->assertTrue( $args['show_in_rest'] );
		$this->assertSame( array( 'title', 'editor', 'thumbnail', 'excerpt' ), $args['supports'] );
	}

	public function test_the_home_sections_are_the_only_ones_out_of_the_rest_api_and_the_search() {
		$types = aranda_de_duero_post_types();

		$this->assertFalse( $types['seccion-principal']['show_in_rest'] );
		$this->assertTrue( $types['seccion-principal']['exclude_from_search'] );
		$this->assertTrue( $types['seccion-principal']['hierarchical'] );
		foreach ( array( 'actividad', 'especial', 'tramite', 'servicio-municipal' ) as $slug ) {
			$this->assertTrue( $types[ $slug ]['show_in_rest'] );
			$this->assertFalse( $types[ $slug ]['exclude_from_search'] );
		}
	}

	public function test_each_type_lists_the_taxonomies_it_had() {
		$types = aranda_de_duero_post_types();

		$this->assertSame( array( 'seccion', 'concejalia', 'tema', 'servicio' ), $types['actividad']['taxonomies'] );
		$this->assertSame( array( 'concejalia' ), $types['especial']['taxonomies'] );
		$this->assertSame( array( 'concejalia' ), $types['tramite']['taxonomies'] );
		$this->assertSame( array( 'concejalia' ), $types['servicio-municipal']['taxonomies'] );
		$this->assertArrayNotHasKey( 'taxonomies', $types['seccion-principal'] );
	}

	public function test_only_the_home_section_categories_are_hierarchical() {
		foreach ( aranda_de_duero_taxonomies() as $slug => $definition ) {
			$this->assertSame( 'home_section_category' === $slug, $definition[1]['hierarchical'], $slug );
		}
	}

	public function test_only_the_taxonomies_that_are_missing_get_registered() {
		WP_Mock::userFunction( 'taxonomy_exists' )->andReturnUsing(
			function ( $slug ) {
				return 'tema' === $slug;
			}
		);
		WP_Mock::userFunction( 'register_taxonomy' )->times( 5 );

		aranda_de_duero_register_taxonomies();

		$this->assertConditionsMet();
	}
}
