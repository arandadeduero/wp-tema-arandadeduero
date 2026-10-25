<?php
/**
 * Template part for a project with European funds: its data, its content and, always, the mandatory PRTR wording as text.
 *
 * @package Aranda_de_Duero
 */

$aranda_funds_url = aranda_de_duero_page_url( ARANDA_DE_DUERO_EU_FUNDS_PAGE );
$aranda_data      = aranda_de_duero_eu_project_data( get_the_ID() );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php if ( '' !== $aranda_funds_url ) : ?>
			<p class="service-kicker">
				<a class="service-back-link" href="<?php echo esc_url( $aranda_funds_url ); ?>"><?php esc_html_e( 'Volver a Fondos europeos', 'aranda-de-duero' ); ?></a>
			</p>
		<?php endif; ?>
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php if ( array() !== $aranda_data ) : ?>
		<dl class="eu-project-data">
			<?php foreach ( $aranda_data as $aranda_label => $aranda_value ) : ?>
				<dt><?php echo esc_html( $aranda_label ); ?></dt>
				<dd><?php echo esc_html( $aranda_value ); ?></dd>
			<?php endforeach; ?>
		</dl>
	<?php endif; ?>

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<p class="eu-funds-wording"><?php echo esc_html( ARANDA_DE_DUERO_EU_FUNDS_WORDING ); ?></p>

	<footer class="entry-footer">
		<?php aranda_de_duero_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
