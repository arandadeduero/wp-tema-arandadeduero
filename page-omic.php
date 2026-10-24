<?php
/**
 * Template Name: Plantilla OMIC
 *
 * What the OMIC is, its legal basis and its contact data. Contact data in inc/everyday-services.php.
 *
 * Create a page with the slug `omic` and this template (the card of the home page, the search and the redirect of the old
 * article look for it).
 *
 * @package Aranda_de_Duero
 */

get_header();
aranda_de_duero_the_header_image( '' );

$aranda_omic = aranda_de_duero_omic_info();
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
								__( 'La OMIC es la oficina municipal que informa, media y recibe tus reclamaciones como consumidor o consumidora.', 'aranda-de-duero' ),
							),
							'tips' => array(
								array( __( 'Es gratuita', 'aranda-de-duero' ), __( 'para cualquier vecino o vecina, sin necesidad de pertenecer a ninguna asociación.', 'aranda-de-duero' ) ),
								array( __( 'Antes de ir', 'aranda-de-duero' ), __( 'reúne el contrato, la factura o el tique y cualquier comunicación con la empresa.', 'aranda-de-duero' ) ),
								array( __( 'Si no es competencia municipal', 'aranda-de-duero' ), __( 'la OMIC te orienta hacia el organismo que corresponda.', 'aranda-de-duero' ) ),
							),
						)
					);
					?>

					<section class="omic-what" aria-labelledby="omic-what-title">
						<h2 id="omic-what-title" class="h4"><?php esc_html_e( 'Qué es', 'aranda-de-duero' ); ?></h2>
						<p><?php esc_html_e( 'La OMIC (Oficina Municipal de Información al Consumidor) es un servicio público y gratuito del Ayuntamiento, dentro de la Concejalía de Consumo, para informar, orientar y defender a los consumidores y usuarios de Aranda de Duero.', 'aranda-de-duero' ); ?></p>
						<p>
							<?php
							echo wp_kses(
								__( 'Las oficinas de información y protección al consumidor están previstas en el artículo 25 de la <strong>Ley 2/2015, de 4 de marzo, por la que se aprueba el Estatuto del Consumidor de Castilla y León</strong>, que encarga a la Administración autonómica promover su creación.', 'aranda-de-duero' ),
								array( 'strong' => array() )
							);
							?>
							<a href="https://www.boe.es/buscar/act.php?id=BOE-A-2015-3280" target="_blank" rel="noopener"><?php esc_html_e( 'Ver la ley en el BOE', 'aranda-de-duero' ); ?><?php aranda_de_duero_the_new_tab_notice(); ?></a>
						</p>
					</section>

					<section class="omic-functions" aria-labelledby="omic-functions-title">
						<h2 id="omic-functions-title" class="h4"><?php esc_html_e( 'Qué puede hacer por ti', 'aranda-de-duero' ); ?></h2>
						<ul>
							<li><?php esc_html_e( 'Informarte sobre tus derechos como consumidor o consumidora, con especial atención a los colectivos más desfavorecidos.', 'aranda-de-duero' ); ?></li>
							<li><?php esc_html_e( 'Recibir, registrar y tramitar tu reclamación o denuncia, resolverla o remitirla al organismo competente.', 'aranda-de-duero' ); ?></li>
							<li><?php esc_html_e( 'Mediar, de forma voluntaria, entre tú y la empresa o profesional con quien tengas el conflicto.', 'aranda-de-duero' ); ?></li>
							<li><?php esc_html_e( 'Fomentar y difundir el sistema arbitral de consumo, una vía gratuita y rápida para resolver conflictos.', 'aranda-de-duero' ); ?></li>
							<li><?php esc_html_e( 'Formar e informar a los consumidores con campañas, publicaciones y jornadas.', 'aranda-de-duero' ); ?></li>
						</ul>
						<p class="small text-muted"><?php esc_html_e( 'La OMIC no puede hacer publicidad de empresas ni de sus bienes o servicios.', 'aranda-de-duero' ); ?></p>
					</section>

					<section class="omic-contact" aria-labelledby="omic-contact-title">
						<h2 id="omic-contact-title" class="h4"><?php esc_html_e( 'Cómo contactar', 'aranda-de-duero' ); ?></h2>
						<ul>
							<li>
								<strong><?php esc_html_e( 'Teléfonos:', 'aranda-de-duero' ); ?></strong>
								<a href="<?php echo esc_url( $aranda_omic['phone_1_href'] ); ?>"><?php echo esc_html( $aranda_omic['phone_1'] ); ?></a>
								<?php esc_html_e( 'y', 'aranda-de-duero' ); ?>
								<a href="<?php echo esc_url( $aranda_omic['phone_2_href'] ); ?>"><?php echo esc_html( $aranda_omic['phone_2'] ); ?></a>
							</li>
							<li>
								<strong><?php esc_html_e( 'Dirección:', 'aranda-de-duero' ); ?></strong>
								<?php echo esc_html( $aranda_omic['address'] ); ?>
							</li>
						</ul>
					</section>

					<p class="small text-muted">
						<?php esc_html_e( 'Fuente: directorio de Oficinas Municipales de Información al Consumidor de la Junta de Castilla y León.', 'aranda-de-duero' ); ?>
						<a href="https://consumo.jcyl.es/web/es/informacion-consumidor/oficinas-informacion-consumidor-omics.html" target="_blank" rel="noopener">consumo.jcyl.es<?php aranda_de_duero_the_new_tab_notice(); ?></a>
					</p>

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
