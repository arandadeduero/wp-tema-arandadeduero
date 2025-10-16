<?php
/**
 * Template part for displaying tramites
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aranda_de_Duero
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		
			the_title( '<h1 class="entry-title h4"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );

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

	<?php aranda_de_duero_post_thumbnail(); ?>

	<div class="entry-content">
		<!-- Taxonomias  -->
		<?php
			$concejalias = wp_get_post_terms( $post->ID, 'concejalia' );
			$categorias = wp_get_post_terms( $post->ID, 'categoria_tramite' );
		?>
		<table class="table table-responsive-md table-striped">
			<tbody>
				<tr>
					<td><strong><?php _e('Fecha');?></strong></td>
					<td> <?php echo get_the_date();?></td>
				</tr>
				<tr>
					<td><strong><?php _e('Concejalía')?>: </strong></td>
					<td><?php 
						foreach ( $concejalias as $concejalia ) {
								echo $concejalia->name;
							}
						?>
					</td>
				</tr>
				<tr>
					<td><strong><?php _e('Categoría')?></strong></td>
					<td>
						<?php 
						foreach ( $categorias as $categoria ) {
								echo $categoria->name;
							}
						?>
					</td>
				</tr>
				<tr>
					<td><strong><?php _e('Dirigido a');?> </strong></td>
					<td><?php echo get_field('tramite_dirigido');?></td>
				</tr>
				<tr>
					<td><strong><?php _e('Gestor');?></strong></td>
					<td><?php echo get_field('tramite_gestor');?></td>
				</tr>
				<tr>
					<td><strong><?php _e('Normativa');?></strong></td>
					<td><?php echo get_field('tramite_normativa');?></td>
				</tr>
				<tr>
					<td><strong><?php _e('Forma de pago');?> </strong></td>
					<td><?php echo get_field('tramite_pago');?></td>
				</tr>
				<tr>
					<td><strong><?php _e('Descripción');?> </strong></td>
					<td>
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
						?>
					</td>
				</tr>
				<tr>
					<td><strong><?php _e('Procedimiento');?> </strong></td>
					<td><?php echo get_field('tramite_procedimiento');?></td>
				</tr>
				<tr>
					<td><strong><?php _e('Fichero adjunto');?></strong></td>
					<td><a class="text-dark" href="<?php echo get_field('tramite_fichero');?>" target="_blank"><?php _e('Descargar');?></a></td>
				</tr>
			</tbody>
		</table>
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
