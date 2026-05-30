<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Aranda_de_Duero
 */

get_header();

$allsearch = new WP_Query(array('s' => get_search_query(), 'posts_per_page' => 0));
$total_results = $allsearch->found_posts;
?>

<div class="container mt-4">
	<div class="row">
		<div class="col-lg-3 pt-4">
			<?php dynamic_sidebar('sidebar-noticias'); ?>
		</div>
		<div class="col-lg-9">
			<main id="primary" class="site-main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Resultado de búsqueda: %s', 'aranda-de-duero' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
					?>
				</h1>
				<p class="search-total-results"><?php printf( esc_html__( 'Encontrados %s resultados', 'aranda-de-duero' ), '<span>' . esc_html( (string) $total_results ) . '</span>' ); ?></p>
			</header><!-- .page-header -->

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

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>
		</div>

	</main><!-- #main -->
</div>
</div>
<?php
get_footer();
