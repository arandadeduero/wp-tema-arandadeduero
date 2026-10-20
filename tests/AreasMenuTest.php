<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/areas-menu.php';

class AreasMenuTest extends TestCase {

	private function item( $id, $parent, $classes = array() ) {
		return (object) array(
			'ID'               => $id,
			'menu_item_parent' => $parent,
			'classes'          => $classes,
		);
	}

	private function args() {
		return (object) array( 'aranda_submenu_class' => ARANDA_DE_DUERO_AREAS_MENU_CLASS );
	}

	private function menu() {
		return array(
			$this->item( 1, 0 ),
			$this->item( 2, 1 ),
			$this->item( 10, 0, array( ARANDA_DE_DUERO_AREAS_MENU_CLASS ) ),
			$this->item( 11, 10 ),
			$this->item( 12, 10 ),
			$this->item( 13, 12 ),
			$this->item( 20, 0 ),
			$this->item( 21, 20 ),
		);
	}

	public function setUp(): void {
		parent::setUp();
		WP_Mock::userFunction( 'wp_list_pluck' )->andReturnUsing(
			function ( $list, $field ) {
				return array_map(
					function ( $o ) use ( $field ) {
						return $o->$field;
					},
					$list
				);
			}
		);
	}

	public function test_other_menus_are_not_touched() {
		$items = $this->menu();

		$this->assertSame( $items, aranda_de_duero_submenu_objects( $items, (object) array() ) );
	}

	public function test_only_the_descendants_of_the_marked_item_remain_in_menu_order() {
		$ids = array_map(
			function ( $item ) {
				return $item->ID;
			},
			aranda_de_duero_submenu_objects( $this->menu(), $this->args() )
		);

		$this->assertSame( array( 11, 12, 13 ), $ids );
	}

	public function test_the_children_become_the_top_level() {
		$result = aranda_de_duero_submenu_objects( $this->menu(), $this->args() );

		$this->assertSame( 0, $result[0]->menu_item_parent );
		$this->assertSame( 0, $result[1]->menu_item_parent );
		$this->assertSame( 12, $result[2]->menu_item_parent );
	}

	public function test_the_search_box_a_plugin_adds_is_removed() {
		$menu = '<ul><li class="a"><a href="/a/">A</a></li><li class="b"><a href="/b/">B</a></li>'
			. '<li class="is-search"><a href="#"><svg></svg></a><form role="search"><input type="search"></form><div class="search-close"></div></li></ul>';

		$this->assertSame(
			'<ul><li class="a"><a href="/a/">A</a></li><li class="b"><a href="/b/">B</a></li></ul>',
			aranda_de_duero_strip_menu_forms( $menu )
		);
	}

	public function test_the_submenu_is_empty_without_a_menu_in_the_location() {
		WP_Mock::userFunction( 'has_nav_menu' )->with( 'menu-1' )->andReturn( false );

		$this->assertSame( '', aranda_de_duero_get_submenu_html( ARANDA_DE_DUERO_AYUNTAMIENTO_MENU_CLASS ) );
	}

	public function test_the_submenu_is_wrapped_as_a_widget_and_has_no_search_box() {
		WP_Mock::userFunction( 'has_nav_menu' )->with( 'menu-1' )->andReturn( true );
		WP_Mock::userFunction( 'wp_nav_menu' )->andReturn( '<ul><li><a href="/a/">A</a></li><li class="s"><form role="search"></form></li></ul>' );

		$this->assertSame(
			'<section class="widget widget_nav_menu"><ul><li><a href="/a/">A</a></li></ul></section>',
			aranda_de_duero_get_submenu_html( ARANDA_DE_DUERO_AYUNTAMIENTO_MENU_CLASS )
		);
	}

	public function test_a_menu_without_items_gives_nothing() {
		WP_Mock::userFunction( 'has_nav_menu' )->andReturn( true );
		WP_Mock::userFunction( 'wp_nav_menu' )->andReturn( '<div class="menu"><ul></ul></div>' );

		$this->assertSame( '', aranda_de_duero_get_submenu_html( ARANDA_DE_DUERO_AREAS_MENU_CLASS ) );
	}

	public function test_without_the_marked_item_nothing_is_left() {
		$items = array( $this->item( 1, 0 ), $this->item( 2, 1 ) );

		$this->assertSame( array(), aranda_de_duero_submenu_objects( $items, $this->args() ) );
	}
}
