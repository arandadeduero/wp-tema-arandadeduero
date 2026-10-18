<?php
/**
 * Template Name: Plantilla Trámites por situaciones
 *
 * The procedures of the town hall grouped by what the visitor is going through («me empadrono», «voy a hacer obras»…). The
 * procedures are the ones listed in «Trámites» and the services of the electronic office; see inc/situations.php.
 *
 * Create a page with the slug `tramites-por-situaciones` and this template: the site search then offers it too.
 *
 * @package Aranda_de_Duero
 */

get_header();
aranda_de_duero_the_header_image( '' );
?>

	<div class="container mt-4">
		<main id="primary" class="site-main">

			<?php
			while ( have_posts() ) :
				the_post();
				?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php
					get_template_part(
						'template-parts/page-intro',
						null,
						array(
							'lead' => array(
								__( 'Elige qué estás haciendo y te mostramos los trámites y servicios que te pueden hacer falta.', 'aranda-de-duero' ),
							),
							'tips' => array(
								array( __( 'Elige tu situación', 'aranda-de-duero' ), __( 'entre las de abajo: cada una reúne lo que suele hacer falta.', 'aranda-de-duero' ) ),
								array( __( 'Abre el trámite', 'aranda-de-duero' ), __( 'los del portal se abren aquí y los de la sede electrónica, en una pestaña nueva.', 'aranda-de-duero' ) ),
								array( __( '¿No está tu caso?', 'aranda-de-duero' ), __( 'usa el buscador de la portada o el catálogo completo de trámites de la sede.', 'aranda-de-duero' ) ),
							),
						)
					);
					?>

					<?php foreach ( aranda_de_duero_situations() as $aranda_group ) : ?>
						<?php if ( array() !== $aranda_group['items'] ) : ?>
							<section class="service-group">
								<h2 class="service-group-title"><?php echo esc_html( $aranda_group['title'] ); ?></h2>
								<?php get_template_part( 'template-parts/service-cards', null, array( 'items' => $aranda_group['items'] ) ); ?>
							</section>
						<?php endif; ?>
					<?php endforeach; ?>

					<section class="service-group">
						<h2 class="service-group-title"><?php esc_html_e( 'Más trámites', 'aranda-de-duero' ); ?></h2>
						<?php get_template_part( 'template-parts/service-cards', null, array( 'items' => aranda_de_duero_more_procedures() ) ); ?>
					</section>

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
			endwhile;
			?>

		</main><!-- #main -->
	</div>

<?php

get_footer();
