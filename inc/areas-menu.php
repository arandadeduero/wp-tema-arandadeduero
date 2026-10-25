<?php
defined( 'ABSPATH' ) || exit;

/**
 * Left-hand menu of the concejalía and tema archives: the sub-menu «Áreas y servicios» of the main menu.
 *
 * The same list used to be written twice, once in the main menu and once in the «Servicios municipales» menu shown by a widget
 * of the «Servicios» sidebar, and the two drifted apart. Now the archives print the children of the main-menu item that has the
 * CSS class below, so the list is edited in one place (Apariencia → Menús). If no item has that class, the widgets of the
 * «Servicios» sidebar are shown as before.
 *
 * @package Aranda_de_Duero
 */

/**
 * CSS class (set in Apariencia → Menús, «Clases CSS») of the main-menu item whose children are the list.
 */
const ARANDA_DE_DUERO_AREAS_MENU_CLASS = 'menu-areas-y-servicios';

/**
 * Keep only the descendants of the menu item that has a given class (wp_nav_menu() argument `aranda_submenu_class`).
 *
 * @param object[] $items Menu items.
 * @param object   $args  Arguments of wp_nav_menu().
 * @return object[]
 */
function aranda_de_duero_submenu_objects( $items, $args ) {
	if ( empty( $args->aranda_submenu_class ) ) {
		return $items;
	}

	$root = 0;
	foreach ( $items as $item ) {
		if ( in_array( $args->aranda_submenu_class, (array) $item->classes, true ) ) {
			$root = (int) $item->ID;
			break;
		}
	}
	if ( 0 === $root ) {
		return array();
	}

	$kept   = array( $root => true );
	$result = array();
	do {
		$added = false;
		foreach ( $items as $item ) {
			$id = (int) $item->ID;
			if ( ! isset( $kept[ $id ] ) && isset( $kept[ (int) $item->menu_item_parent ] ) ) {
				$kept[ $id ] = true;
				$result[]    = $item;
				$added       = true;
			}
		}
	} while ( $added );

	// The children of the item become the top level of the list.
	foreach ( $result as $item ) {
		if ( (int) $item->menu_item_parent === $root ) {
			$item->menu_item_parent = 0;
		}
	}

	// Keep the order of the menu.
	$order = array_flip( array_map( 'intval', wp_list_pluck( $items, 'ID' ) ) );
	usort(
		$result,
		function ( $a, $b ) use ( $order ) {
			return $order[ (int) $a->ID ] - $order[ (int) $b->ID ];
		}
	);

	return $result;
}
add_filter( 'wp_nav_menu_objects', 'aranda_de_duero_submenu_objects', 10, 2 );

/**
 * Remove from a menu the items that hold a form.
 *
 * A search plugin adds its search box as one more item of the menu assigned to the `menu-1` location, and the left-hand list is
 * printed from that location: the box would show up under «Todas las concejalías».
 *
 * @param string $html Markup of the menu.
 * @return string
 */
function aranda_de_duero_strip_menu_forms( $html ) {
	$clean = preg_replace( '#<li\b[^>]*>(?:(?!<li\b).)*?<form\b.*?</form>.*?</li>#s', '', $html );

	return null === $clean ? $html : $clean;
}

/**
 * CSS class of the main-menu item «Ayuntamiento», whose children are the left-hand menu of the pages with the Ayuntamiento template.
 */
const ARANDA_DE_DUERO_AYUNTAMIENTO_MENU_CLASS = 'menu-ayuntamiento';

/**
 * Markup of the left-hand list made of the children of the main-menu item that has a given CSS class.
 *
 * @param string $css_class CSS class of the main-menu item.
 * @return string Empty when the menu is not assigned or no item has the class.
 */
function aranda_de_duero_get_submenu_html( $css_class ) {
	if ( ! has_nav_menu( 'menu-1' ) ) {
		return '';
	}

	$menu = wp_nav_menu(
		array(
			'theme_location'       => 'menu-1',
			'container_class'      => 'menu-servicios-municipales-container',
			'menu_id'              => 'menu-servicios-municipales',
			'fallback_cb'          => false,
			'echo'                 => false,
			'aranda_submenu_class' => $css_class,
		)
	);
	$menu = is_string( $menu ) ? aranda_de_duero_strip_menu_forms( $menu ) : '';

	return str_contains( $menu, '<li' ) ? '<section class="widget widget_nav_menu">' . $menu . '</section>' : '';
}

/**
 * Print the left-hand menu «Áreas y servicios» (or the «Servicios» sidebar when the main menu has no such item).
 */
function aranda_de_duero_the_areas_menu() {
	$menu = aranda_de_duero_get_submenu_html( ARANDA_DE_DUERO_AREAS_MENU_CLASS );

	if ( '' === $menu ) {
		dynamic_sidebar( 'Servicios' );
		return;
	}

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup built by wp_nav_menu().
	echo $menu;
}
