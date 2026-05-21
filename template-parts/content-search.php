<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aranda_de_Duero
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php 
		the_title( sprintf( '<h4 class="entry-title text-blue"><a style="color: #1A7ED6!important;?>" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' ); 
		?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="row">
		<div class="col-12">
			<div class="entry-summary">
				<?php 
					the_excerpt();
				 ?>
			</div>
		</div><!-- .entry-summary -->
	</div>

	<footer class="search-entry-footer">
		<?php aranda_de_duero_entry_search_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
