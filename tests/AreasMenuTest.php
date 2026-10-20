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

	public function test_without_the_marked_item_nothing_is_left() {
		$items = array( $this->item( 1, 0 ), $this->item( 2, 1 ) );

		$this->assertSame( array(), aranda_de_duero_submenu_objects( $items, $this->args() ) );
	}
}
