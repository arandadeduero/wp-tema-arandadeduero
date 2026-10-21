<?php
/**
 * Grid of cards (image, title, date and a link) with the pagination below it: Noticias, Bandos, Notas de prensa and Subvenciones.
 *
 * Passed with get_template_part() as the third argument:
 * - `query`       (WP_Query) What to list; the page has to be paginated with `paged`.
 * - `read_label`  (string) Text of the link of each card («Leer bando»).
 * - `older_label` (string) Link to the next page of older posts («Bandos anteriores»).
 * - `newer_label` (string) Link back to the newer ones («Bandos posteriores»).
 * - `empty`       (string) Message when there is nothing to list.
 *
 * @package Aranda_de_Duero
 */

$aranda_list  = isset( $args ) ? $args : array();
$aranda_query = $aranda_list['query'];

if ( ! $aranda_query->have_posts() ) :
	?>
	<div class="alert alert-warning" role="alert">
		<p><?php echo esc_html( $aranda_list['empty'] ); ?></p>
	</div>
	<?php
	return;
endif;
?>
<div class="row post-card-grid">
	<?php
	while ( $aranda_query->have_posts() ) :
			$aranda_query->the_post();
		?>
			<div class="col-lg-3 col-md-6 col-sm-12 mb-4">
				<div class="post-card p-3 rounded h-100 border">
					<div class="post-card-content d-flex flex-column justify-content-between h-100">
						<div class="post-card-image mb-3">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php
								the_post_thumbnail(
									'medium',
									array(
										'class'    => 'img-fluid rounded',
										'alt'      => '',
										'loading'  => 'lazy',
										'decoding' => 'async',
									)
								);
								?>
							<?php else : ?>
								<img loading="lazy" decoding="async" class="img-fluid rounded" width="300" height="107" src="<?php echo esc_url( content_url( '/uploads/2025/11/Copia-de-Banner-ayuntamiento-5-300x107.png' ) ); ?>" alt="">
							<?php endif; ?>
						</div>

						<div class="post-card-description">
							<a href="<?php the_permalink(); ?>" class="main-section-text-link">
								<h2 class="h6 mb-2 font-weight-bold"><?php the_title(); ?></h2>
							</a>
							<small class="text-muted d-block mb-2"><?php echo esc_html( get_the_date() ); ?></small>
						</div>

						<div class="post-card-button mt-3">
							<a href="<?php the_permalink(); ?>" class="text-blue"><span class="arrow">➔</span> <?php echo esc_html( $aranda_list['read_label'] ); ?></a>
						</div>
					</div>
				</div>
			</div>
		<?php endwhile; ?>
	</div>

	<?php if ( $aranda_query->max_num_pages > 1 ) : ?>
		<nav class="post-card-pagination d-flex justify-content-between my-5">
			<div class="post-card-older">
				<?php
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_next_posts_link() returns WP core's own safely-built HTML.
				echo get_next_posts_link( '← ' . esc_html( $aranda_list['older_label'] ), $aranda_query->max_num_pages );
				?>
			</div>
			<div class="post-card-newer">
				<?php
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_previous_posts_link() returns WP core's own safely-built HTML.
				echo get_previous_posts_link( esc_html( $aranda_list['newer_label'] ) . ' →' );
				?>
			</div>
		</nav>
	<?php endif; ?>

<?php wp_reset_postdata(); ?>
