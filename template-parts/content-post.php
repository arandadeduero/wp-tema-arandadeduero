<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aranda_de_Duero
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('mb-5'); ?>>
	
	<header class="entry-header ">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="h3 entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="h3 entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				aranda_de_duero_posted_on();
				aranda_de_duero_posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php
	// Check if post is older than 3 years and display warning
	$post_date = get_the_date('Y-m-d');

	if (strtotime($post_date) < strtotime('-3 years') && is_singular()) : ?>
		<div class="outdated-post-warning alert alert-warning mb-3" role="alert">
			<div class="d-flex align-items-center">
				<i class="fas fa-exclamation-triangle me-2" aria-hidden="true"></i>
				<div>
					<strong><?php esc_html_e('Contenido desactualizado', 'aranda-de-duero'); ?></strong><br>
					<small><?php
						printf(
							esc_html__('Esta noticia fue publicada el %1$s y puede contener información desactualizada. Por favor, consulte fuentes más recientes para obtener información actualizada.', 'aranda-de-duero'),
							get_the_date()
						);
					?></small>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php aranda_de_duero_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'aranda-de-duero' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aranda-de-duero' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php aranda_de_duero_entry_topics_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
