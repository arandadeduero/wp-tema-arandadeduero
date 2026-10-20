<?php

/**
 * Template Name: Página de Notas de Prensa
 *
 * This is the template that displays notas de prensa posts.
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
						'heading' => __( '¿Qué es una Nota de Prensa?', 'aranda-de-duero' ),
						'lead'    => array(
							__( 'Una nota de prensa es un comunicado oficial emitido por el ayuntamiento de Aranda de Duero para informar a los medios de comunicación y al público sobre eventos, decisiones o asuntos de interés general. Su objetivo es garantizar la transparencia y mantener a la ciudadanía informada.', 'aranda-de-duero' ),
						),
						'tips'    => array(
							array( __( 'Lee la nota completa', 'aranda-de-duero' ), __( 'pulsando su tarjeta o el enlace «Leer nota de prensa».', 'aranda-de-duero' ) ),
							array( __( 'Las más recientes, primero', 'aranda-de-duero' ), __( 'las notas están ordenadas por su fecha de publicación.', 'aranda-de-duero' ) ),
							array( __( 'Busca las anteriores', 'aranda-de-duero' ), __( 'con los enlaces del final de la lista, que muestra 12 notas por página.', 'aranda-de-duero' ) ),
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
								'terms'    => 'notas-de-prensa',
							),
						),
					)
				);

				get_template_part(
					'template-parts/post-card-list',
					null,
					array(
						'query'       => $query,
						'read_label'  => __( 'Leer nota de prensa', 'aranda-de-duero' ),
						'older_label' => __( 'Notas de prensa anteriores', 'aranda-de-duero' ),
						'newer_label' => __( 'Notas de prensa posteriores', 'aranda-de-duero' ),
						'empty'       => __( 'No hay notas de prensa disponibles en este momento.', 'aranda-de-duero' ),
					)
				);
				?>

			</main><!-- #main -->
		</div>

	</div>
</div>

<?php

get_footer();
