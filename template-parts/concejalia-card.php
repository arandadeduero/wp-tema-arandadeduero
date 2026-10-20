<?php
/**
 * Card of a concejalía in the public index /concejalia/: name, who leads it and, when filled in, phone, e-mail and location.
 *
 * Passed with get_template_part() as the third argument:
 * - `term` (WP_Term) Concejalía.
 *
 * @package Aranda_de_Duero
 */

$aranda_args    = isset( $args ) ? $args : array();
$aranda_term    = $aranda_args['term'];
$aranda_contact = aranda_de_duero_term_contact( $aranda_term->term_id );
$aranda_link    = get_term_link( $aranda_term );
?>
<li class="concejalia-card">
	<h2 class="concejalia-card-title">
		<a href="<?php echo esc_url( is_string( $aranda_link ) ? $aranda_link : '' ); ?>"><?php echo esc_html( $aranda_term->name ); ?></a>
	</h2>
	<?php if ( isset( $aranda_contact['concejalia_concejal'] ) ) : ?>
		<p class="concejalia-card-concejal"><?php echo esc_html( $aranda_contact['concejalia_concejal'] ); ?></p>
	<?php endif; ?>
	<?php if ( isset( $aranda_contact['concejalia_telefono'] ) || isset( $aranda_contact['concejalia_email'] ) || isset( $aranda_contact['concejalia_ubicacion'] ) ) : ?>
		<ul class="concejalia-card-contact">
			<?php if ( isset( $aranda_contact['concejalia_telefono'] ) ) : ?>
				<li>
					<span class="concejalia-card-icon" aria-hidden="true">
						<?php
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
						echo aranda_de_duero_icon( 'aranda-de-duero/phone-flip', array( 'size' => 16 ) );
						?>
					</span>
					<span class="screen-reader-text"><?php esc_html_e( 'Teléfono', 'aranda-de-duero' ); ?>:</span>
					<?php
					$aranda_links = array();
					foreach ( aranda_de_duero_phone_numbers( $aranda_contact['concejalia_telefono'] ) as $aranda_number ) {
						$aranda_links[] = sprintf( '<a href="%s">%s</a>', esc_url( $aranda_number['href'], array( 'tel' ) ), esc_html( $aranda_number['label'] ) );
					}
					echo wp_kses_post( implode( ' · ', $aranda_links ) );
					?>
				</li>
			<?php endif; ?>
			<?php if ( isset( $aranda_contact['concejalia_email'] ) ) : ?>
				<li>
					<span class="concejalia-card-icon" aria-hidden="true">
						<?php
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
						echo aranda_de_duero_icon( 'aranda-de-duero/envelope', array( 'size' => 16 ) );
						?>
					</span>
					<span class="screen-reader-text"><?php esc_html_e( 'Correo electrónico', 'aranda-de-duero' ); ?>:</span>
					<a href="<?php echo esc_url( 'mailto:' . $aranda_contact['concejalia_email'] ); ?>"><?php echo esc_html( $aranda_contact['concejalia_email'] ); ?></a>
				</li>
			<?php endif; ?>
			<?php if ( isset( $aranda_contact['concejalia_ubicacion'] ) ) : ?>
				<li>
					<span class="concejalia-card-icon" aria-hidden="true">
						<?php
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
						echo aranda_de_duero_icon( 'aranda-de-duero/location-dot', array( 'size' => 16 ) );
						?>
					</span>
					<span class="screen-reader-text"><?php esc_html_e( 'Ubicación', 'aranda-de-duero' ); ?>:</span>
					<?php echo esc_html( $aranda_contact['concejalia_ubicacion'] ); ?>
				</li>
			<?php endif; ?>
		</ul>
	<?php endif; ?>
</li>
