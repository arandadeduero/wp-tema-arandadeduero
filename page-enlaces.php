<?php
/**
 * Template Name: Plantilla de enlaces (sin cabecera)
 *
 * Página de enlaces al estilo «link en la bio»: logotipo, redes del Ayuntamiento y de «Aranda ME GUSTA» en botones
 * blancos sobre fondo azul claro y, debajo, una tarjeta de color por servicio (Policía Local, Bomberos, CEAS,
 * Deportes, Biblioteca y Protección Civil). No usa la cabecera del sitio; sí su pie (get_footer()), que carga el aviso
 * de cookies y la analítica por wp_footer().
 *
 * Todo el contenido está aquí, en `$aranda_enlaces`: para añadir, quitar o corregir un enlace basta con editar esa lista.
 * Mismo contenido y mismas medidas que la página antigua de Elementor (incluidos dos enlaces marcados «REVISAR»).
 * El icono de cada botón se elige solo por la dirección (css/aranda-de-duero.css, «Página de enlaces»).
 *
 * Sustituye a la página hecha con Elementor «Enlaces de interés».
 *
 * @package Aranda_de_Duero
 */

$aranda_enlaces = array(
	'destacados' => array(
		array(
			'titulo'  => 'Ayuntamiento Aranda de Duero:',
			'enlaces' => array(
				array( 'Página Web', home_url( '/' ), 'enlaces-boton--web' ),
				array( 'Instagram', 'https://www.instagram.com/ayuntamientoarandadeduero/' ),
				array( 'Facebook', 'https://www.facebook.com/aytoarandadeduero' ),
				array( 'Tiktok', 'https://www.tiktok.com/@aytoarandadeduero' ),
				array( 'Twitter', 'https://x.com/aytoaranda' ),
			),
		),
		array(
			'titulo'  => 'Aranda ME GUSTA:',
			'enlaces' => array(
				array( 'Instagram', 'https://www.instagram.com/arandamegusta' ),
				// REVISAR: este Facebook apunta a una búsqueda (facebook.com/search/top), no a un perfil. Así estaba en la página antigua.
				array( 'Facebook', 'https://www.facebook.com/search/top' ),
				array( 'Twitter', 'https://x.com/ArandaMeGusta' ),
				array( 'Youtube', 'https://www.youtube.com/@SMArandaDeDuero' ),
			),
		),
	),
	'servicios'  => array(
		array(
			'clave'   => 'policia',
			'titulo'  => 'POLICÍA LOCAL',
			'enlaces' => array(
				array( 'Instagram', 'https://www.instagram.com/policialocalad/' ),
				array( 'Twitter', 'https://x.com/PoliciaLocalAD' ),
			),
		),
		array(
			'clave'   => 'bomberos',
			'titulo'  => 'BOMBEROS',
			'enlaces' => array(
				array( 'Instagram', 'https://www.instagram.com/bomberosaranda/' ),
			),
		),
		array(
			'clave'   => 'ceas',
			'titulo'  => 'CEAS',
			'enlaces' => array(
				array( 'Instagram', 'https://www.instagram.com/ceas.aranda/' ),
			),
		),
		array(
			'clave'   => 'deportes',
			'titulo'  => 'DEPORTES',
			'enlaces' => array(
				array( 'Instagram', 'https://www.instagram.com/concejaliadeportes_aranda/' ),
				array( 'Facebook', 'https://www.facebook.com/p/Arandactiva-100071367133529/' ),
				array( 'Tiktok', 'https://www.tiktok.com/@concejalia.deportes' ),
			),
		),
		array(
			'clave'   => 'biblioteca',
			'titulo'  => 'BIBLIOTECA',
			'enlaces' => array(
				array( 'Instagram', 'https://www.instagram.com/biblio.aranda/' ),
				array( 'Facebook', 'https://www.facebook.com/BiblioAranda' ),
				array( 'Biblioaranda', 'http://www.linktr.ee/biblioaranda' ),
			),
		),
		array(
			'clave'   => 'proteccion-civil',
			'titulo'  => 'PROTECCIÓN CIVIL',
			'enlaces' => array(
				// REVISAR: en la página antigua este «Instagram» enlazaba a la Facebook de la Biblioteca. Poner el Instagram de Protección Civil.
				array( 'Instagram', 'https://www.facebook.com/BiblioAranda' ),
			),
		),
	),
);

/**
 * Prints one link as a button.
 *
 * @param array{0: string, 1: string, 2?: string} $enlace Label, URL and, optionally, an extra CSS class (icon).
 */
$aranda_boton = static function ( array $enlace ) {
	?>
	<a class="enlaces-boton<?php echo isset( $enlace[2] ) ? ' ' . esc_attr( $enlace[2] ) : ''; ?>" href="<?php echo esc_url( $enlace[1] ); ?>" target="_blank" rel="noopener">
		<?php echo esc_html( $enlace[0] ); ?>
		<?php aranda_de_duero_the_new_tab_notice(); ?>
	</a>
	<?php
};

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
			<a class="enlaces-marca" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img src="<?php echo esc_url( content_url( '/uploads/2025/11/08-escudo-Ayto-Aranda-de-Duero-color-300x300.png' ) ); ?>" width="110" height="110" alt="" decoding="async">
				<span class="enlaces-marca-linea">Ayuntamiento de</span>
				<span class="enlaces-marca-nombre">Aranda de Duero</span>
				<span class="screen-reader-text"><?php esc_html_e( 'Ir a la página de inicio', 'aranda-de-duero' ); ?></span>
			</a>
		</header>

		<main id="primary" class="site-main enlaces-main">
			<section class="enlaces-destacados">
				<?php foreach ( $aranda_enlaces['destacados'] as $aranda_indice => $aranda_grupo ) : ?>
					<div class="enlaces-grupo">
						<?php $aranda_etiqueta = 0 === $aranda_indice ? 'h1' : 'h2'; ?>
						<<?php echo tag_escape( $aranda_etiqueta ); ?> class="enlaces-titulo">
							<strong><?php echo esc_html( get_the_title( get_queried_object_id() ) ); ?></strong>
							<span><?php echo esc_html( $aranda_grupo['titulo'] ); ?></span>
						</<?php echo tag_escape( $aranda_etiqueta ); ?>>
						<?php foreach ( $aranda_grupo['enlaces'] as $aranda_enlace ) : ?>
							<?php $aranda_boton( $aranda_enlace ); ?>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</section>

			<section class="enlaces-servicios">
				<?php foreach ( $aranda_enlaces['servicios'] as $aranda_servicio ) : ?>
					<div class="enlaces-tarjeta enlaces-tarjeta--<?php echo esc_attr( $aranda_servicio['clave'] ); ?>">
						<img class="enlaces-tarjeta-icono" src="<?php echo esc_url( get_template_directory_uri() . '/images/enlaces/' . ( 'proteccion-civil' === $aranda_servicio['clave'] ? 'policia' : $aranda_servicio['clave'] ) . '.svg' ); ?>" width="126" height="126" alt="" decoding="async">
						<h2><?php echo esc_html( $aranda_servicio['titulo'] ); ?></h2>
						<?php foreach ( $aranda_servicio['enlaces'] as $aranda_enlace ) : ?>
							<?php $aranda_boton( $aranda_enlace ); ?>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</section>
		</main>

<?php
// footer.php prints the site footer (with the cookie banner and analytics through wp_footer), closes #page and the document.
get_footer();
