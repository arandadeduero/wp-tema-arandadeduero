<?php
/**
 * Template Name: Plantilla Ayuntamiento
 *
 * Página para mostrar la sección Ayuntamiento
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aranda_de_Duero
 */

get_header();

aranda_de_duero_the_header_image( 'Cabecera página ayuntamiento' );
$aranda_side_menu = aranda_de_duero_get_submenu_html( ARANDA_DE_DUERO_AYUNTAMIENTO_MENU_CLASS );
?>

	<div class="container mt-4">
		<div class="row">
			<?php if ( '' !== $aranda_side_menu ) : ?>
				<div class="col-lg-3 pt-4">
					<?php
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup built by wp_nav_menu().
					echo $aranda_side_menu;
					?>
				</div>
			<?php endif; ?>
			<div class="<?php echo '' !== $aranda_side_menu ? 'col-lg-9 pt-4' : 'col-12'; ?>">
		<main id="primary" class="site-main">

			<?php
			while ( have_posts() ) :
				the_post();

				?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="page-heading">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header><!-- .page-heading -->

					<div class="entry-content">
						<?php the_content(); ?>
					</div><!-- .entry-content -->

					<?php if ( get_edit_post_link() ) : ?>
						<footer class="entry-footer">
							<?php edit_post_link( esc_html__( 'Edit', 'aranda-de-duero' ), '<span class="edit-link">', '</span>' ); ?>
						</footer><!-- .entry-footer -->
					<?php endif; ?>
				</article><!-- #post-<?php the_ID(); ?> -->

				<?php

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
			</div>
		</div>
	</div>

<?php

get_footer();
