<?php
/**
 * Template Name: Plantilla de enlaces (sin cabecera)
 *
 * Página de enlaces al estilo «link en la bio»: logotipo, título y grupos de botones, sin la cabecera ni el menú
 * del sitio (pero con el pie y el aviso de cookies). Sustituye a la página hecha con Elementor «Enlaces de interés».
 *
 * El contenido se edita con bloques: por cada grupo, un bloque «Grupo» con la clase `enlaces-grupo`, un título
 * (Encabezado) y un bloque «Botones» con un botón por enlace; el icono se elige solo según la dirección
 * (Instagram, Facebook, TikTok, X, YouTube). Ver docs/pagina-enlaces.md.
 *
 * @package Aranda_de_Duero
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<?php wp_head(); ?>
</head>

<body <?php body_class( 'page-enlaces' ); ?>>
	<?php wp_body_open(); ?>

	<div id="page" class="site">
		<header class="enlaces-logo">
			<?php the_custom_logo(); ?>
		</header>

		<main id="primary" class="site-main enlaces-main">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<h1 class="enlaces-title"><?php the_title(); ?></h1>
				<div class="enlaces-content">
					<?php the_content(); ?>
				</div>
				<?php
			endwhile;
			?>
		</main>

<?php
// footer.php prints the site footer, closes #page and the document.
get_footer();
