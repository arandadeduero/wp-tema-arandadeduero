<?php

/**
 * Taxonomy archive template for "concejalia" terms.
 *
 * A single generic template for every "concejalia" term, replacing one
 * hand-copied file per term (taxonomy-concejalia-juventud.php, etc.)
 * that differed only in which term slug was hardcoded.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aranda_de_Duero
 */

get_header();
$current_term = get_queried_object();
aranda_de_duero_the_header_image( '', $current_term );
$query = aranda_de_duero_concejalias( $current_term->slug );
?>

	<div class="container mt-4">
		<div class="row">
			<div class="col-lg-3 pt-4">
				<?php aranda_de_duero_the_areas_menu(); ?>
			</div>
			<div class="col-lg-9 pt-4">
				<main id="primary" class="site-main">
					<?php
					get_template_part(
						'template-parts/term-heading',
						null,
						array(
							'term'   => $current_term,
							'kicker' => __( 'Concejalía', 'aranda-de-duero' ),
						)
					);
					get_template_part( 'template-parts/term-posts', null, array( 'query' => $query ) );
					?>
				</main><!-- #main -->
			</div>
		</div>
	</div>

<?php

get_footer();
