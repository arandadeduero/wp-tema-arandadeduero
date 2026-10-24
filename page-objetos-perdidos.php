<?php
/**
 * Template Name: Plantilla Objetos perdidos
 *
 * What to do if you lose something, or if you find it, in Aranda de Duero: the Policía Local keeps lost-and-found items.
 * Contact data in inc/everyday-services.php.
 *
 * Create a page with the slug `objetos-perdidos` and this template (the card of the home page and the search look for it).
 *
 * @package Aranda_de_Duero
 */

get_header();
aranda_de_duero_the_header_image( '' );

$aranda_lost = aranda_de_duero_lost_found_info();
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
								__( 'Qué hacer si pierdes algo en la calle, o si encuentras un objeto de otra persona, en Aranda de Duero.', 'aranda-de-duero' ),
							),
							'tips' => array(
								array( __( 'Llama antes de ir', 'aranda-de-duero' ), __( 'para saber si el objeto está depositado y acordar cuándo pasar a recogerlo.', 'aranda-de-duero' ) ),
								array( __( 'Acredita que es tuyo', 'aranda-de-duero' ), __( 'con una descripción detallada o cualquier prueba de que el objeto te pertenece.', 'aranda-de-duero' ) ),
								array( __( 'Si lo encuentras', 'aranda-de-duero' ), __( 'entrégalo en la Policía Local: así su dueño podrá recuperarlo.', 'aranda-de-duero' ) ),
							),
						)
					);
					?>

					<section class="lost-found-contact" aria-labelledby="lost-found-contact-title">
						<h2 id="lost-found-contact-title" class="h4"><?php esc_html_e( 'Policía Local', 'aranda-de-duero' ); ?></h2>
						<p><?php esc_html_e( 'Los objetos perdidos o hallados en la vía pública de Aranda de Duero se depositan en la Policía Local.', 'aranda-de-duero' ); ?></p>
						<ul>
							<li>
								<strong><?php esc_html_e( 'Teléfono:', 'aranda-de-duero' ); ?></strong>
								<a href="<?php echo esc_url( $aranda_lost['phone_href'] ); ?>"><?php echo esc_html( $aranda_lost['phone'] ); ?></a>
							</li>
							<li>
								<strong><?php esc_html_e( 'Correo electrónico:', 'aranda-de-duero' ); ?></strong>
								<a href="<?php echo esc_url( 'mailto:' . $aranda_lost['email'] ); ?>"><?php echo esc_html( $aranda_lost['email'] ); ?></a>
							</li>
							<li>
								<strong><?php esc_html_e( 'Dirección:', 'aranda-de-duero' ); ?></strong>
								<?php echo esc_html( $aranda_lost['address'] ); ?>
								<span class="text-muted"><?php esc_html_e( '(confírmala por teléfono antes de ir)', 'aranda-de-duero' ); ?></span>
							</li>
						</ul>
					</section>

					<section class="lost-found-how" aria-labelledby="lost-found-how-title">
						<h2 id="lost-found-how-title" class="h4"><?php esc_html_e( 'Cómo recuperar un objeto', 'aranda-de-duero' ); ?></h2>
						<ol>
							<li><?php esc_html_e( 'Llama a la Policía Local y describe el objeto: qué es, cuándo y dónde lo perdiste.', 'aranda-de-duero' ); ?></li>
							<li><?php esc_html_e( 'Si está depositado, acuerda una cita para pasar a recogerlo y acredita que es tuyo.', 'aranda-de-duero' ); ?></li>
							<li><?php esc_html_e( 'Los objetos no reclamados se conservan un tiempo, después se les da el destino que marca la ley.', 'aranda-de-duero' ); ?></li>
						</ol>
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

			<?php endwhile; ?>

		</main><!-- #primary -->
	</div>

<?php
get_footer();
