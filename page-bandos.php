<?php

/**
 * Template Name: Página de Bandos
 *
 * This is the template that displays bandos posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aranda_de_Duero
 */


// Nuevo
get_header();

aranda_de_duero_the_header_image( '' );
?>
<div class="container mt-4">
	<div class="row">
		<div class="col pt-4">
			<main id="primary" class="site-main">
				<?php
				get_template_part(
					'template-parts/page-intro',
					null,
					array(
						'heading' => __( '¿Qué es un Bando Municipal?', 'aranda-de-duero' ),
						'lead'    => array(
							__( 'Un bando municipal es una disposición administrativa solemne emitida por el alcalde o alcaldesa de un ayuntamiento en España, destinada a regular la convivencia, recordar obligaciones o anunciar asuntos de interés general. Es de obligado cumplimiento para los vecinos y regula aspectos como seguridad, limpieza o eventos locales.', 'aranda-de-duero' ),
						),
						'tips'    => array(
							array( __( 'Lee el bando completo', 'aranda-de-duero' ), __( 'pulsando su tarjeta o el enlace «Leer bando».', 'aranda-de-duero' ) ),
							array( __( 'Los más recientes, primero', 'aranda-de-duero' ), __( 'los bandos están ordenados por su fecha de publicación.', 'aranda-de-duero' ) ),
							array( __( 'Busca los anteriores', 'aranda-de-duero' ), __( 'con los enlaces del final de la lista, que muestra 12 bandos por página.', 'aranda-de-duero' ) ),
						),
					)
				);

				$current_page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				$query        = new WP_Query(
					array(
						'post_type'      => 'post',
						'posts_per_page' => 12,
						'paged'          => $current_page,
						'orderby'        => 'publish_date',
						'order'          => 'DESC',
						'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- the posts of one category.
							array(
								'taxonomy' => 'category',
								'field'    => 'slug',
								'terms'    => 'bandos',
							),
						),
					)
				);

				get_template_part(
					'template-parts/post-card-list',
					null,
					array(
						'query'       => $query,
						'read_label'  => __( 'Leer bando', 'aranda-de-duero' ),
						'older_label' => __( 'Bandos anteriores', 'aranda-de-duero' ),
						'newer_label' => __( 'Bandos posteriores', 'aranda-de-duero' ),
						'empty'       => __( 'No hay bandos disponibles en este momento.', 'aranda-de-duero' ),
					)
				);
				?>

			</main><!-- #main -->
		</div>

	</div>
</div>

<?php

get_footer();
