<?php
/**
 * General information of a concejalía (who leads it, phone, e-mail, where it is). Prints nothing when none is filled in.
 *
 * Passed with get_template_part() as the third argument:
 * - `term` (WP_Term) Concejalía.
 *
 * @package Aranda_de_Duero
 */

$aranda_args    = isset( $args ) ? $args : array();
$aranda_term    = $aranda_args['term'];
$aranda_contact = ARANDA_DE_DUERO_CONTACT_TAXONOMY === $aranda_term->taxonomy ? aranda_de_duero_term_contact( $aranda_term->term_id ) : array();

if ( empty( $aranda_contact ) ) {
	return;
}

$aranda_labels = aranda_de_duero_term_contact_fields();
?>
<dl class="term-contact">
	<?php foreach ( $aranda_contact as $aranda_key => $aranda_value ) : ?>
		<div class="term-contact-item">
			<dt><?php echo esc_html( $aranda_labels[ $aranda_key ] ); ?></dt>
			<dd>
				<?php
				if ( 'concejalia_email' === $aranda_key ) {
					printf( '<a href="%s">%s</a>', esc_url( 'mailto:' . $aranda_value ), esc_html( $aranda_value ) );
				} elseif ( 'concejalia_telefono' === $aranda_key ) {
					$aranda_links = array();
					foreach ( aranda_de_duero_phone_numbers( $aranda_value ) as $aranda_number ) {
						$aranda_links[] = sprintf( '<a href="%s">%s</a>', esc_url( $aranda_number['href'], array( 'tel' ) ), esc_html( $aranda_number['label'] ) );
					}
					echo wp_kses_post( implode( ' · ', $aranda_links ) );
				} else {
					echo esc_html( $aranda_value );
				}
				?>
			</dd>
		</div>
	<?php endforeach; ?>
</dl>
