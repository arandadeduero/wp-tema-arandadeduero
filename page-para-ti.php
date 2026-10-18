<?php
/**
 * Template Name: Plantilla Para ti
 *
 * The services of the town hall grouped by who is asking: families, older people, young people, businesses, new residents and
 * associations. Every card is a page that already exists; see inc/audiences.php.
 *
 * Create a page with the slug `para-ti` and this template: the site search then offers it too.
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
								__( 'Elige quién eres y te mostramos los servicios, trámites y actividades que más te pueden interesar.', 'aranda-de-duero' ),
							),
							'tips' => array(
								array( __( 'Elige tu grupo', 'aranda-de-duero' ), __( 'entre los de abajo: cada uno reúne lo que suele hacer falta.', 'aranda-de-duero' ) ),
								array( __( 'Abre el servicio', 'aranda-de-duero' ), __( 'los de este portal se abren aquí y los de otras webs municipales, en una pestaña nueva.', 'aranda-de-duero' ) ),
								array( __( '¿No encuentras algo?', 'aranda-de-duero' ), __( 'usa el buscador «¿Qué necesitas?» de la portada o los trámites por situaciones.', 'aranda-de-duero' ) ),
							),
						)
					);
					?>

					<?php foreach ( aranda_de_duero_audiences() as $aranda_group ) : ?>
						<?php if ( array() !== $aranda_group['items'] ) : ?>
							<section class="service-group">
								<h2 class="service-group-title"><?php echo esc_html( $aranda_group['title'] ); ?></h2>
								<?php get_template_part( 'template-parts/service-cards', null, array( 'items' => $aranda_group['items'] ) ); ?>
							</section>
						<?php endif; ?>
					<?php endforeach; ?>

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
