<?php

/**
 * Template Name: Página de Subvenciones
 *
 * This is the template that displays subvenciones posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aranda_de_Duero
 */


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
						'heading' => __( 'Abre Aranda: Ayudas y oportunidades para tu negocio', 'aranda-de-duero' ),
						'lead'    => array(
							__( 'El Ayuntamiento de Aranda de Duero pone a disposición de autónomos, emprendedores, comercios y empresas este espacio de información sobre subvenciones, ayudas públicas y programas de apoyo al desarrollo económico.', 'aranda-de-duero' ),
							__( 'Nuestro objetivo es facilitar el acceso a las diferentes convocatorias disponibles, acercando la información de manera clara y sencilla para contribuir a la creación, consolidación y modernización de la actividad económica local.', 'aranda-de-duero' ),
						),
						'tips'    => array(
							array( __( 'Abre una convocatoria', 'aranda-de-duero' ), __( 'pulsando su tarjeta o el enlace «Ver subvención».', 'aranda-de-duero' ) ),
							array( __( 'Las más recientes, primero', 'aranda-de-duero' ), __( 'las subvenciones están ordenadas por su fecha de publicación.', 'aranda-de-duero' ) ),
							array( __( 'Busca las anteriores', 'aranda-de-duero' ), __( 'con los enlaces del final de la lista, que muestra 12 subvenciones por página.', 'aranda-de-duero' ) ),
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
								'terms'    => 'subvenciones',
							),
						),
					)
				);

				get_template_part(
					'template-parts/post-card-list',
					null,
					array(
						'query'       => $query,
						'read_label'  => __( 'Ver subvención', 'aranda-de-duero' ),
						'older_label' => __( 'Subvenciones anteriores', 'aranda-de-duero' ),
						'newer_label' => __( 'Subvenciones posteriores', 'aranda-de-duero' ),
						'empty'       => __( 'No hay subvenciones disponibles en este momento.', 'aranda-de-duero' ),
					)
				);
				?>

			</main><!-- #main -->
		</div>

	</div>
</div>

<?php

get_footer();
