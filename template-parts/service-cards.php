<?php
/**
 * A list of service cards (title, one-line description, link): «Accesos frecuentes» and «Pagos y recibos».
 *
 * Passed with get_template_part() as the third argument:
 * - `items` (array<int, array<string, mixed>>) The cards: title, text, url, new_tab; the pharmacy on duty also has `pharmacy` (name,
 *   address, phone, phone_href, map) and the waste card has `waste` (ban, bulky_phone, bulky_href, bulky_label).
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
		<?php if ( ! empty( $aranda_card['waste'] ) ) : ?>
			<?php $aranda_waste_card = $aranda_card['waste']; ?>
			<?php /* Waste: not one link, because it has the bulky-items phone inside. Light red on a day without deposit. */ ?>
			<li>
				<div class="service-card service-card-waste<?php echo $aranda_waste_card['ban'] ? ' is-banned' : ''; ?>">
					<a class="service-card-title" href="<?php echo esc_url( $aranda_card['url'] ); ?>"><?php echo esc_html( $aranda_card['title'] ); ?></a>
					<?php if ( $aranda_waste_card['ban'] ) : ?>
						<span class="service-card-waste-ban"><?php esc_html_e( 'HOY PROHIBIDO DEPOSITAR BASURA', 'aranda-de-duero' ); ?></span>
					<?php else : ?>
						<span class="service-card-text"><?php echo esc_html( $aranda_card['text'] ); ?></span>
					<?php endif; ?>
					<span class="service-card-waste-actions">
						<a href="<?php echo esc_url( $aranda_waste_card['bulky_href'] ); ?>"><?php echo esc_html( $aranda_waste_card['bulky_label'] . ': ' . $aranda_waste_card['bulky_phone'] ); ?></a>
					</span>
				</div>
			</li>
			<?php continue; ?>
		<?php endif; ?>
		<?php if ( ! empty( $aranda_card['pharmacy'] ) ) : ?>
			<?php $aranda_pharmacy = $aranda_card['pharmacy']; ?>
			<?php /* The pharmacy on duty now: not one link, because it has its own (phone, map) inside. */ ?>
			<li>
				<div class="service-card service-card-pharmacy">
					<a class="service-card-title" href="<?php echo esc_url( $aranda_card['url'] ); ?>"><?php echo esc_html( $aranda_card['title'] ); ?></a>
					<span class="service-card-pharmacy-name"><?php echo esc_html( $aranda_pharmacy['name'] ); ?></span>
					<span class="service-card-text"><?php echo esc_html( $aranda_pharmacy['address'] ); ?></span>
					<span class="service-card-pharmacy-actions">
						<?php if ( '' !== $aranda_pharmacy['phone_href'] ) : ?>
							<a href="<?php echo esc_url( $aranda_pharmacy['phone_href'] ); ?>"><?php echo esc_html( $aranda_pharmacy['phone'] ); ?></a>
						<?php endif; ?>
						<?php if ( ! empty( $aranda_pharmacy['map'] ) ) : ?>
							<?php // Only the drawing, small and on the right; the name of the app is for screen readers. ?>
							<a class="pharmacy-map pharmacy-map-icon" href="<?php echo esc_url( $aranda_pharmacy['map']['url'] ); ?>" target="_blank" rel="noopener">
								<?php
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
								echo aranda_de_duero_icon( $aranda_pharmacy['map']['icon'], array( 'size' => 16 ) );
								?>
								<span class="screen-reader-text">
									<?php
									/* translators: %s: map application, Google Maps or Apple Maps. */
									echo esc_html( sprintf( __( 'Cómo llegar con %s', 'aranda-de-duero' ), $aranda_pharmacy['map']['label'] ) );
									?>
								</span>
								<?php aranda_de_duero_the_new_tab_notice(); ?>
							</a>
						<?php endif; ?>
					</span>
				</div>
			</li>
			<?php continue; ?>
		<?php endif; ?>
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
