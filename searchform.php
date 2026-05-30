<?php
/**
 * Search form template
 *
 * @package Aranda_de_Duero
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="s">
		<span class="screen-reader-text"><?php esc_html_e( 'Buscar', 'aranda-de-duero' ); ?></span>
	</label>
	<input type="search" class="search-field" placeholder="<?php esc_attr_e( 'Buscar', 'aranda-de-duero' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" id="s">
	<input type="submit" class="search-submit" value="<?php esc_attr_e( 'Buscar', 'aranda-de-duero' ); ?>">
</form>
