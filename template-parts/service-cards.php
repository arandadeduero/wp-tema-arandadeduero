<?php
/**
 * A list of service cards (title, one-line description, link): «Accesos frecuentes» and «Pagos y recibos».
 *
 * Passed with get_template_part() as the third argument:
 * - `items` (array<int, array{title: string, text: string, url: string, new_tab: bool}>) The cards.
 *
 * @package Aranda_de_Duero
 */

$aranda_cards = wp_parse_args(
	isset( $args ) ? $args : array(), // $args is set by get_template_part().
	array( 'items' => array() )
);

if ( array() === $aranda_cards['items'] ) {
	return;
}
?>
<ul class="service-cards">
	<?php foreach ( $aranda_cards['items'] as $aranda_card ) : ?>
		<li>
			<a class="service-card" href="<?php echo esc_url( $aranda_card['url'] ); ?>"
				<?php
				if ( $aranda_card['new_tab'] ) :
					?>
					target="_blank" rel="noopener"<?php endif; ?>>
				<span class="service-card-title"><?php echo esc_html( $aranda_card['title'] ); ?></span>
				<span class="service-card-text"><?php echo esc_html( $aranda_card['text'] ); ?></span>
				<?php
				if ( $aranda_card['new_tab'] ) {
					aranda_de_duero_the_new_tab_notice();
				}
				?>
			</a>
		</li>
	<?php endforeach; ?>
</ul>
