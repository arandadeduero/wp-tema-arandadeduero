<?php
/**
 * The template for displaying archive pages (categories, tags, dates, authors and custom post types).
 *
 * Uses the main query, so the category/tag/date being viewed is the one that is listed, and shows what the
 * archive is at the top. Cards work with or without a featured image.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aranda_de_Duero
 */

get_header();

if ( is_category() ) {
	$aranda_kicker = __( 'Categoría', 'aranda-de-duero' );
} elseif ( is_tag() ) {
	$aranda_kicker = __( 'Etiqueta', 'aranda-de-duero' );
} elseif ( is_author() ) {
	$aranda_kicker = __( 'Autor/a', 'aranda-de-duero' );
} elseif ( is_date() ) {
	$aranda_kicker = __( 'Archivo', 'aranda-de-duero' );
} else {
	$aranda_kicker = __( 'Listado', 'aranda-de-duero' );
}

// «Categoría: Educación» → «Educación»: the kicker already says what it is.
add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );
$aranda_title = get_the_archive_title();
remove_filter( 'get_the_archive_title_prefix', '__return_empty_string' );

global $wp_query;
$aranda_total = (int) $wp_query->found_posts;
?>

<div class="container mt-4">
	<main id="primary" class="site-main">

		<header class="archive-heading">
			<p class="archive-kicker"><?php echo esc_html( $aranda_kicker ); ?></p>
			<h1 class="entry-title"><?php echo wp_kses_post( $aranda_title ); ?></h1>
			<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
			<?php if ( $aranda_total > 0 ) : ?>
				<p class="archive-count">
					<?php
					/* translators: %s: number of entries in the archive. */
					echo esc_html( sprintf( _n( '%s entrada', '%s entradas', $aranda_total, 'aranda-de-duero' ), number_format_i18n( $aranda_total ) ) );
					?>
				</p>
			<?php endif; ?>
		</header><!-- .archive-heading -->

		<?php if ( have_posts() ) : ?>

			<div class="row archive-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<div class="col-lg-4 col-md-6 col-12 mb-4">
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'archive-card h-100' ); ?>>
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="archive-card-image">
									<?php
									the_post_thumbnail(
										'medium_large',
										array(
											'alt'      => '',
											'loading'  => 'lazy',
											'decoding' => 'async',
										)
									);
									?>
								</div>
							<?php endif; ?>

							<div class="archive-card-body">
								<time class="archive-card-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
								<h2 class="archive-card-title h5">
									<a class="stretched-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h2>
								<p class="archive-card-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p>
								<span class="archive-card-more" aria-hidden="true">➔ <?php esc_html_e( 'Leer más', 'aranda-de-duero' ); ?></span>
							</div>
						</article>
					</div>
				<?php endwhile; ?>
			</div>

			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 1,
					'prev_text' => esc_html__( '« Anterior', 'aranda-de-duero' ),
					'next_text' => esc_html__( 'Siguiente »', 'aranda-de-duero' ),
				)
			);
			?>

		<?php else : ?>

			<p class="archive-empty"><?php esc_html_e( 'Todavía no hay contenido en este apartado.', 'aranda-de-duero' ); ?></p>

		<?php endif; ?>

	</main><!-- #main -->
</div>

<?php
get_footer();
