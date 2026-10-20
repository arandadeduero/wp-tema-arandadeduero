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

while ( $aranda_query->have_posts() ) :
	$aranda_query->the_post();
	?>
	<div class="col-12">
		<div class="noticia-listada d-flex mb-4">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="noticia-listada-imagen mr-3">
					<?php
					the_post_thumbnail(
						'medium',
						array(
							'class'    => 'img-thumbnail',
							'alt'      => '',
							'loading'  => 'lazy',
							'decoding' => 'async',
						)
					);
					?>
				</div>
			<?php endif; ?>
			<div class="noticia-listada-texto">
				<h2 class="h4"><a class="text-blue" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<p><?php echo esc_html( wp_strip_all_tags( get_the_excerpt(), true ) ); ?></p>
			</div>
		</div>
	</div>
	<?php
endwhile;
wp_reset_postdata();

aranda_de_duero_query_pagination( $aranda_query );
