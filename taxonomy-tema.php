<?php

/**
 * Taxonomy archive template for "tema" terms.
 *
 * A single generic template for every "tema" term, replacing one
 * hand-copied file per term (taxonomy-tema-salud.php,
 * taxonomy-tema-tributos.php, etc.) that differed only in the sidebar
 * name and which term slug was hardcoded.
 *
 * Two layouts were in use across those files: a card/list layout for
 * general topics, and a simple date/title table for terms that behave
 * like a bulletin (tributos, empleo, concursos...). $table_layout_terms
 * below lists which slugs use the table layout; everything else gets
 * the card layout. $sidebar_by_term lists the handful of terms whose
 * sidebar differs from the 'Servicios' default.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aranda_de_Duero
 */

get_header();
$current_term = get_queried_object();
aranda_de_duero_the_header_image( '', $current_term );
$query = aranda_de_duero_temas( $current_term->slug );

$table_layout_terms = array( 'tributos', 'concursos', 'oposiciones-y-empleo', 'ayudas-y-subvenciones', 'empleo' );
$is_table_layout    = in_array( $current_term->slug, $table_layout_terms, true );

$sidebar_by_term = array(
	'anuncios'                 => 'Actualidad',
	'oficina-desarrollo-local' => 'Tramites',
);
foreach ( $table_layout_terms as $table_term_slug ) {
	$sidebar_by_term[ $table_term_slug ] = 'Tramites';
}
$sidebar = isset( $sidebar_by_term[ $current_term->slug ] ) ? $sidebar_by_term[ $current_term->slug ] : 'Servicios';
?>

	<div class="container mt-4">
		<div class="row">
			<div class="col-lg-3 pt-4">
				<?php 'Servicios' === $sidebar ? aranda_de_duero_the_areas_menu() : dynamic_sidebar( $sidebar ); ?>
			</div>
			<div class="col-lg-9 pt-4">
				<main id="primary" class="site-main">
					<?php
					get_template_part(
						'template-parts/term-heading',
						null,
						array(
							'term'   => $current_term,
							'kicker' => __( 'Tema', 'aranda-de-duero' ),
						)
					);
					?>
					<?php if ( $is_table_layout && $query->have_posts() ) : ?>
						<table class="table table-responsive-md table-striped">
							<thead>
								<tr>
									<th><?php esc_html_e( 'Fecha', 'aranda-de-duero' ); ?></th>
									<th><?php esc_html_e( 'Descripción', 'aranda-de-duero' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								while ( $query->have_posts() ) :
									$query->the_post();
									?>
									<tr>
										<td><?php echo get_the_date(); ?></td>
										<td><a class="text-dark" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></td>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
						<?php
						wp_reset_postdata();
						aranda_de_duero_query_pagination( $query );
						?>
					<?php else : ?>
						<?php get_template_part( 'template-parts/term-posts', null, array( 'query' => $query ) ); ?>
					<?php endif; ?>
				</main><!-- #main -->
			</div>
		</div>
	</div>

<?php

get_footer();
