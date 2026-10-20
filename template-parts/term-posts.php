<?php
/**
 * Card list of a term archive (concejalía or tema), with its pagination or, when empty, a message.
 *
 * Passed with get_template_part() as the third argument:
 * - `query` (WP_Query) Posts to list.
 *
 * @package Aranda_de_Duero
 */

$aranda_args  = isset( $args ) ? $args : array(); // $args is set by get_template_part().
$aranda_query = $aranda_args['query'];

if ( ! $aranda_query->have_posts() ) :
	?>
	<p class="archive-empty"><?php esc_html_e( 'Todavía no hay contenido en este apartado.', 'aranda-de-duero' ); ?></p>
	<?php
	return;
endif;
?>
<div class="row">
	<?php
	while ( $aranda_query->have_posts() ) :
		$aranda_query->the_post();
		?>
		<div class="col-sm-12 col-lg-6 mt-4">
			<article class="home-main-news p-3 rounded h-100 d-flex flex-column">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="home-main-news-image mb-3">
						<?php
						the_post_thumbnail(
							'large',
							array(
								'class'    => 'img-fluid',
								'alt'      => '',
								'loading'  => 'lazy',
								'decoding' => 'async',
							)
						);
						?>
					</div>
				<?php endif; ?>
				<div class="home-main-news-content px-2 d-flex flex-column justify-content-between flex-grow-1">
					<div class="home-main-news-description">
						<time class="archive-card-date d-block" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
						<a href="<?php the_permalink(); ?>" class="main-section-text-link">
							<h2 class="h6 mb-0 font-weight-bold"><?php the_title(); ?></h2>
						</a>
						<p class="small mt-3 w-100 pr-2"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_excerpt(), true ), 30 ) ); ?></p>
					</div>
					<div class="home-main-news-button my-3">
						<a href="<?php the_permalink(); ?>" class="btn btn-primary" role="button"><?php esc_html_e( 'Ver más', 'aranda-de-duero' ); ?></a>
					</div>
				</div>
			</article>
		</div>
		<?php
	endwhile;
	?>
</div>
<?php
wp_reset_postdata();

aranda_de_duero_query_pagination( $aranda_query );
