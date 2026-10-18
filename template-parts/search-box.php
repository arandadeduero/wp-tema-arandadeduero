<?php
/**
 * Search box «¿Qué necesitas?», used in the home page and in the search results.
 *
 * Passed with get_template_part() as the third argument:
 * - `id`    (string) Unique id of the field (several boxes can share a page).
 * - `value` (string, optional) Current search.
 *
 * @package Aranda_de_Duero
 */

$aranda_box = wp_parse_args(
	isset( $args ) ? $args : array(), // $args is set by get_template_part().
	array(
		'id'    => 'search-box',
		'value' => '',
	)
);

aranda_de_duero_enqueue_search_suggest();
?>
<form role="search" method="get" class="search-box" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $aranda_box['id'] ); ?>"><?php esc_html_e( 'Busca un trámite, un servicio o una noticia', 'aranda-de-duero' ); ?></label>
	<input type="search" class="search-box-field" id="<?php echo esc_attr( $aranda_box['id'] ); ?>" name="s" value="<?php echo esc_attr( $aranda_box['value'] ); ?>" placeholder="<?php esc_attr_e( 'Trámite, servicio…', 'aranda-de-duero' ); ?>" autocomplete="off">
	<button type="submit" class="search-box-button"><?php esc_html_e( 'Buscar', 'aranda-de-duero' ); ?></button>
</form>
