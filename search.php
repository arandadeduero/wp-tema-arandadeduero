<?php
/**
 * The template for displaying search results pages
 *
 * Above the results of the site search (news, pages…) it lists the services of the electronic office that match what was
 * typed (inc/search.php), so «padrón» or «recibo» lead straight to the service, and it always offers the catalogue of
 * procedures.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Aranda_de_Duero
 */

get_header();

global $wp_query;

// The main query already counted every match; running the search a second time
// just to read the total doubled the (slow) LIKE scan over all posts.
$total_results   = (int) $wp_query->found_posts;
$aranda_search   = get_search_query( false );
$aranda_services = aranda_de_duero_search_services( $aranda_search, 12 );
?>

<div class="container mt-4">
	<div class="row">
		<div class="col-lg-3 pt-4">
			<?php dynamic_sidebar( 'sidebar-noticias' ); ?>
		</div>
		<div class="col-lg-9">
			<main id="primary" class="site-main">

				<header class="page-header">
					<h1 class="page-title">
						<?php
						if ( have_posts() ) {
							/* translators: %s: search query. */
							printf( esc_html__( 'Resultado de búsqueda: %s', 'aranda-de-duero' ), '<span>' . esc_html( $aranda_search ) . '</span>' );
						} else {
							/* translators: %s: search query. */
							printf( esc_html__( 'Sin resultados para: %s', 'aranda-de-duero' ), '<span>' . esc_html( $aranda_search ) . '</span>' );
						}
						?>
					</h1>
					<?php if ( have_posts() ) : ?>
						<p class="search-total-results">
							<?php
							/* translators: %s: number of search results. */
							printf( esc_html__( 'Encontrados %s resultados', 'aranda-de-duero' ), '<span>' . esc_html( (string) $total_results ) . '</span>' );
							?>
						</p>
					<?php endif; ?>
				</header><!-- .page-header -->

				<?php
				get_template_part(
					'template-parts/search-box',
					null,
					array(
						'id'    => 'results-search',
						'value' => $aranda_search,
					)
				);
				?>

				<?php if ( array() !== $aranda_services ) : ?>
					<section class="search-services" aria-labelledby="search-services-title">
						<h2 id="search-services-title" class="service-group-title"><?php esc_html_e( 'Servicios y trámites', 'aranda-de-duero' ); ?></h2>
						<?php get_template_part( 'template-parts/service-cards', null, array( 'items' => $aranda_services ) ); ?>
					</section>
				<?php endif; ?>

				<p class="search-catalog">
					<?php esc_html_e( '¿No encuentras el trámite?', 'aranda-de-duero' ); ?>
					<a href="<?php echo esc_url( aranda_de_duero_catalog_url() ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Busca en el catálogo de trámites de la sede electrónica', 'aranda-de-duero' ); ?><?php aranda_de_duero_the_new_tab_notice(); ?></a>
				</p>

				<?php if ( have_posts() ) : ?>

					<h2 class="service-group-title"><?php esc_html_e( 'Noticias y páginas', 'aranda-de-duero' ); ?></h2>

					<?php
					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

						/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
						 */
						get_template_part( 'template-parts/content', 'search' );

					endwhile;

					the_posts_navigation();

				else :
					?>

					<p class="search-empty"><?php esc_html_e( 'No hemos encontrado noticias ni páginas con esas palabras. Prueba con otras o consulta el catálogo de trámites.', 'aranda-de-duero' ); ?></p>

				<?php endif; ?>

			</main><!-- #main -->
		</div>
	</div>
</div>
<?php
get_footer();
