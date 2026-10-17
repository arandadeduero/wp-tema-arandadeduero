<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/template-functions.php';

class QuickLinksTest extends TestCase {

	private function item( $title, $url, $parent = '0', $target = '' ) {
		return (object) array(
			'title'            => $title,
			'url'              => $url,
			'menu_item_parent' => $parent,
			'target'           => $target,
		);
	}

	private function menu( array $items ) {
		\WP_Mock::userFunction( 'get_nav_menu_locations' )->andReturn( array( 'top' => 7 ) );
		\WP_Mock::userFunction( 'wp_get_nav_menu_items' )->with( 7 )->andReturn( $items );
		\WP_Mock::userFunction( 'wp_strip_all_tags' )->andReturnUsing( 'strip_tags' );
		\WP_Mock::userFunction( 'apply_filters' )->andReturnUsing(
			function ( $tag, $value ) {
				return $value;
			}
		);
	}

	public function test_returns_the_top_level_links_with_their_data() {
		$this->menu(
			array(
				$this->item( 'Cita previa', 'https://citaprevia.example.test/' ),
				$this->item( 'Portal de transparencia', 'https://transparencia.example.test/', '0', '_blank' ),
			)
		);

		$this->assertSame(
			array(
				array(
					'title'   => 'Cita previa',
					'url'     => 'https://citaprevia.example.test/',
					'new_tab' => false,
				),
				array(
					'title'   => 'Portal de transparencia',
					'url'     => 'https://transparencia.example.test/',
					'new_tab' => true,
				),
			),
			aranda_de_duero_quick_links()
		);
	}

	public function test_skips_submenu_items_and_items_without_a_real_url() {
		$this->menu(
			array(
				$this->item( 'Con hijos', 'https://example.test/a/' ),
				$this->item( 'Hijo', 'https://example.test/hijo/', '12' ),
				$this->item( 'Sin enlace', '#' ),
				$this->item( 'Vacío', '' ),
			)
		);

		$links = aranda_de_duero_quick_links();

		$this->assertCount( 1, $links );
		$this->assertSame( 'Con hijos', $links[0]['title'] );
	}

	public function test_strips_html_from_titles() {
		$this->menu( array( $this->item( '<span>Sede</span> electrónica', 'https://sede.example.test/' ) ) );

		$this->assertSame( 'Sede electrónica', aranda_de_duero_quick_links()[0]['title'] );
	}

	public function test_returns_nothing_without_a_menu_in_the_location() {
		\WP_Mock::userFunction( 'get_nav_menu_locations' )->andReturn( array() );
		\WP_Mock::userFunction( 'wp_get_nav_menu_items' )->never();

		$this->assertSame( array(), aranda_de_duero_quick_links() );
	}

	public function test_returns_nothing_when_wordpress_gives_no_items() {
		\WP_Mock::userFunction( 'get_nav_menu_locations' )->andReturn( array( 'top' => 7 ) );
		\WP_Mock::userFunction( 'wp_get_nav_menu_items' )->andReturn( false );

		$this->assertSame( array(), aranda_de_duero_quick_links() );
	}
}
