<?php
/**
 * «Bando» label above the title of a news card of the front page, so that it is not only the colour that says it is important.
 *
 * @package Aranda_de_Duero
 */

?>
<p class="home-news-kicker">
	<span class="home-news-kicker-icon" aria-hidden="true">
		<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
		echo aranda_de_duero_icon( 'aranda-de-duero/triangle-exclamation', array( 'size' => 16 ) );
		?>
	</span>
	<?php esc_html_e( 'Bando', 'aranda-de-duero' ); ?>
</p>
