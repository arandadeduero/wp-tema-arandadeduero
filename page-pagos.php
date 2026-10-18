<?php
/**
 * Template Name: Plantilla Pagos y recibos
 *
 * Groups the payment services of the electronic office (receipts, fines, settlements, fiscal calendar) in one place, with
 * direct links. The services themselves live in sede.arandadeduero.es; see inc/services.php.
 *
 * Create a page with the slug `pagos-y-recibos` and this template: «Pagos y recibos» in the home page's «Accesos
 * frecuentes» then points to it.
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
								__( 'Consulta y paga online tus recibos, multas y liquidaciones. Estos servicios los presta la sede electrónica del Ayuntamiento.', 'aranda-de-duero' ),
							),
							'tips' => array(
								array( __( 'Elige el servicio', 'aranda-de-duero' ), __( 'que necesitas: pagar ahora, o consultar tus recibos, multas y liquidaciones.', 'aranda-de-duero' ) ),
								array( __( 'Identifícate en la sede', 'aranda-de-duero' ), __( 'cuando el servicio muestre tus datos: se abre en una pestaña nueva.', 'aranda-de-duero' ) ),
								array( __( 'Guarda el justificante', 'aranda-de-duero' ), __( 'o la carta de pago: «Recibos» y «Mis liquidaciones» permiten obtenerlos.', 'aranda-de-duero' ) ),
							),
						)
					);
					?>

					<?php foreach ( aranda_de_duero_payment_services() as $aranda_group ) : ?>
						<section class="service-group">
							<h2 class="service-group-title"><?php echo esc_html( $aranda_group['title'] ); ?></h2>
							<?php get_template_part( 'template-parts/service-cards', null, array( 'items' => $aranda_group['items'] ) ); ?>
						</section>
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
