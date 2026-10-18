<?php
/**
 * Title, introduction and three short tips shown above a page's content (Agenda, Bandos, Subvenciones, Notas de prensa).
 *
 * Passed with get_template_part() as the third argument:
 * - `lead`    (string[]) Introduction paragraphs.
 * - `tips`    (array<int, array{0: string, 1: string}>) Bold lead-in and text of each tip.
 * - `heading` (string, optional) Short line in bold above the introduction.
 *
 * @package Aranda_de_Duero
 */

$aranda_intro = wp_parse_args(
	isset( $args ) ? $args : array(), // $args is set by get_template_part().
	array(
		'lead'    => array(),
		'tips'    => array(),
		'heading' => '',
	)
);
?>
<header class="page-intro">
	<h1 class="entry-title"><?php the_title(); ?></h1>
	<?php if ( '' !== $aranda_intro['heading'] ) : ?>
		<p class="page-intro-heading"><?php echo esc_html( $aranda_intro['heading'] ); ?></p>
	<?php endif; ?>
	<?php foreach ( $aranda_intro['lead'] as $aranda_paragraph ) : ?>
		<p class="page-intro-lead"><?php echo esc_html( $aranda_paragraph ); ?></p>
	<?php endforeach; ?>
	<ul class="page-intro-tips">
		<?php foreach ( $aranda_intro['tips'] as $aranda_tip ) : ?>
			<li><strong><?php echo esc_html( $aranda_tip[0] ); ?></strong> <?php echo esc_html( $aranda_tip[1] ); ?></li>
		<?php endforeach; ?>
	</ul>
</header><!-- .page-intro -->
