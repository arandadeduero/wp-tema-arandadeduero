<?php

/**
 * Template Name: Página de Actualidad
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
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
						'heading' => __( 'La actualidad del Ayuntamiento', 'aranda-de-duero' ),
						'lead'    => array(
							__( 'Las últimas noticias del Ayuntamiento de Aranda de Duero: actividades, servicios, obras, cultura, deportes y todo lo que ocurre en la ciudad.', 'aranda-de-duero' ),
						),
						'tips'    => array(
							array( __( 'Lee la noticia completa', 'aranda-de-duero' ), __( 'pulsando su tarjeta o el enlace «Leer noticia».', 'aranda-de-duero' ) ),
							array( __( 'Las más recientes, primero', 'aranda-de-duero' ), __( 'las noticias están ordenadas por su fecha de publicación.', 'aranda-de-duero' ) ),
							array( __( 'Busca las anteriores', 'aranda-de-duero' ), __( 'con los enlaces del final de la lista, que muestra 12 noticias por página.', 'aranda-de-duero' ) ),
						),
					)
				);

				get_template_part(
					'template-parts/post-card-list',
					null,
					array(
						'query'       => aranda_de_duero_content( 'post', 'publish_date', 'DESC', 12 ),
						'read_label'  => __( 'Leer noticia', 'aranda-de-duero' ),
						'older_label' => __( 'Noticias anteriores', 'aranda-de-duero' ),
						'newer_label' => __( 'Noticias posteriores', 'aranda-de-duero' ),
						'empty'       => __( 'No hay noticias disponibles en este momento.', 'aranda-de-duero' ),
					)
				);
				?>

			</main><!-- #main -->
		</div>

	</div>
</div>

<?php

get_footer();
