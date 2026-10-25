<?php
/**
 * Template part for a single service of a concejalía (Biblioteca, Escuela de música…).
 *
 * @package Aranda_de_Duero
 */

$aranda_concejalias = wp_get_post_terms( get_the_ID(), 'concejalia' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php foreach ( $aranda_concejalias as $aranda_concejalia ) : ?>
			<p class="entry-meta">
				<?php
				/* translators: %s: name of the concejalía. */
				echo esc_html( sprintf( __( 'Servicio de %s', 'aranda-de-duero' ), $aranda_concejalia->name ) );
				?>
				<a href="<?php echo esc_url( get_term_link( $aranda_concejalia ) ); ?>"><?php esc_html_e( 'Ver la concejalía', 'aranda-de-duero' ); ?></a>
			</p>
		<?php endforeach; ?>
	</header><!-- .entry-header -->

	<?php aranda_de_duero_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Sigue leyendo<span class="screen-reader-text"> "%s"</span>', 'aranda-de-duero' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			)
		);
		?>
		<?php
		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aranda-de-duero' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php aranda_de_duero_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
