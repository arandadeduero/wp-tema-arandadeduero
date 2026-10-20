<?php
/**
 * Title of a term archive (concejalía or tema): what it is, its name, its description and, when nothing new has been
 * published for years, a notice that the information may be out of date.
 *
 * Passed with get_template_part() as the third argument:
 * - `term`  (WP_Term) Term being listed.
 * - `kicker` (string) What kind of archive it is («Concejalía», «Tema»).
 *
 * @package Aranda_de_Duero
 */

$aranda_args   = isset( $args ) ? $args : array(); // $args is set by get_template_part().
$aranda_term   = $aranda_args['term'];
$aranda_newest = aranda_de_duero_term_newest_timestamp( $aranda_term );
?>
<header class="archive-heading">
	<p class="archive-kicker"><?php echo esc_html( $aranda_args['kicker'] ); ?></p>
	<h1 class="entry-title"><?php echo esc_html( $aranda_term->name ); ?></h1>
	<?php if ( '' !== trim( $aranda_term->description ) ) : ?>
		<div class="archive-description"><?php echo wp_kses_post( wpautop( $aranda_term->description ) ); ?></div>
	<?php endif; ?>
	<?php if ( aranda_de_duero_is_historic( $aranda_newest, time() ) ) : ?>
		<div class="old-post-disclaimer alert alert-warning d-flex align-items-center mt-3 mb-0" role="note">
			<span class="mr-3 old-post-disclaimer-icon">
				<?php
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
				echo aranda_de_duero_icon( 'aranda-de-duero/triangle-exclamation', array( 'size' => 21 ) );
				?>
			</span>
			<div>
				<strong><?php esc_html_e( 'Archivo histórico', 'aranda-de-duero' ); ?>:</strong>
				<?php
				/* translators: %s: year of the newest entry. */
				echo esc_html( sprintf( __( 'aquí no se publica nada desde %s. La información puede estar desactualizada.', 'aranda-de-duero' ), wp_date( 'Y', $aranda_newest ) ) );
				?>
			</div>
		</div>
	<?php endif; ?>
</header><!-- .archive-heading -->
