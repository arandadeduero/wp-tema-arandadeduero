<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Aranda_de_Duero
 */

get_header();

$header_image = wp_get_attachment_url( get_theme_mod('aranda_de_duero_default_header_image'));

?>
<div class="container-fluid">
        <div class="row">
            <div class="col-12 p-0">
                <img src="<?php echo $header_image; ?>" class="img-fluid w-100 cabecera_pagina" alt="<?php echo $header_image; ?>"/>
            </div>
        </div>
</div>

<div class="container mt-4">
		<div class="row">
			<div class="col-lg-3 pt-4">
				<?php dynamic_sidebar('Servicios');?>
			</div>
			<div class="col-lg-9 pt-4">
				<main id="primary" class="site-main">

					<section class="error-404 not-found">
						<header class="page-header">
							<h1 class="page-title"><?php esc_html_e( 'La página que buscas no ha sido encontrada', 'aranda-de-duero' ); ?></h1>
						</header><!-- .page-header -->

						<div class="page-content">
							<p><?php esc_html_e( 'Introduce la URL correcta o utiliza nuestro buscador', 'aranda-de-duero' ); ?></p>

								<?php
								get_search_form();

								the_widget( 'WP_Widget_Recent_Posts' );
								?>

								<div class="widget widget_categories">
									<h2 class="widget-title"><?php esc_html_e( 'Categorías más utilizadas', 'aranda-de-duero' ); ?></h2>
									<ul>
										<?php
										wp_list_categories(
											array(
												'orderby'    => 'count',
												'order'      => 'DESC',
												'show_count' => 1,
												'title_li'   => '',
												'number'     => 10,
											)
										);
										?>
									</ul>
								</div><!-- .widget -->

								<?php
								/* translators: %1$s: smiley */
								$aranda_de_duero_archive_content = '<p>' . sprintf( esc_html__( 'Trata de buscar en los archivos mensuales', 'aranda-de-duero' )) . '</p>';
								the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$aranda_de_duero_archive_content" );

								the_widget( 'WP_Widget_Tag_Cloud' );
								?>

						</div><!-- .page-content -->
					</section><!-- .error-404 -->

				</main><!-- #main -->
			</div>
		</div>
</div>
<?php
get_footer();
