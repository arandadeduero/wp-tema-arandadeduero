<?php
/**
 * Body of the index pages /tema/, /concejalia/ and /tramite/ (see inc/term-index.php and index-terminos.php).
 *
 * Everybody sees the terms (or procedures) that have content, each linking to its page. Administrators see all of them, with
 * their id, slug, numbers and an edit link.
 *
 * @package Aranda_de_Duero
 */

$aranda_kind  = aranda_de_duero_current_term_index();
$aranda_admin = current_user_can( 'manage_options' );
$aranda_now   = time();

$aranda_titles = array(
	'tema'       => __( 'Temas', 'aranda-de-duero' ),
	'concejalia' => __( 'Concejalías', 'aranda-de-duero' ),
	'tramite'    => __( 'Trámites', 'aranda-de-duero' ),
);

$aranda_rows = array();
if ( 'tramite' === $aranda_kind ) {
	$aranda_rows = get_posts(
		array(
			'post_type'              => 'tramite',
			'post_status'            => 'publish',
			'posts_per_page'         => -1,
			'orderby'                => 'title',
			'order'                  => 'ASC',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
		)
	);
} else {
	$aranda_rows = get_terms(
		array(
			'taxonomy'   => $aranda_kind,
			'hide_empty' => ! $aranda_admin,
			'orderby'    => 'name',
		)
	);
	$aranda_rows = is_wp_error( $aranda_rows ) ? array() : $aranda_rows;
	if ( ! $aranda_admin ) {
		$aranda_rows = array_filter(
			$aranda_rows,
			function ( $term ) {
				return ! aranda_de_duero_is_numeric_term_name( $term->name );
			}
		);
	}
}
?>

	<div class="container mt-4">
		<main id="primary" class="site-main">

			<header class="archive-heading">
				<p class="archive-kicker"><?php echo esc_html( $aranda_admin ? __( 'Índice (administración)', 'aranda-de-duero' ) : __( 'Índice', 'aranda-de-duero' ) ); ?></p>
				<h1 class="entry-title"><?php echo esc_html( $aranda_titles[ $aranda_kind ] ); ?></h1>
				<?php if ( $aranda_admin ) : ?>
					<p class="archive-count"><?php echo esc_html( sprintf( /* translators: %d: number of rows. */ __( '%d elementos. Solo lo ves tú y quien administre la web: el resto ve los que tienen contenido.', 'aranda-de-duero' ), count( $aranda_rows ) ) ); ?></p>
				<?php endif; ?>
			</header>

			<?php if ( empty( $aranda_rows ) ) : ?>
				<p class="archive-empty"><?php esc_html_e( 'Todavía no hay nada en este apartado.', 'aranda-de-duero' ); ?></p>
			<?php elseif ( ! $aranda_admin ) : ?>
				<ul class="term-index">
					<?php foreach ( $aranda_rows as $aranda_row ) : ?>
						<li>
							<a href="<?php echo esc_url( 'tramite' === $aranda_kind ? get_permalink( $aranda_row ) : get_term_link( $aranda_row ) ); ?>"><?php echo esc_html( 'tramite' === $aranda_kind ? get_the_title( $aranda_row ) : $aranda_row->name ); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php elseif ( 'tramite' === $aranda_kind ) : ?>
				<div class="tramites-table-scroll">
					<table class="table table-striped term-index-table">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Trámite', 'aranda-de-duero' ); ?></th>
								<th><?php esc_html_e( 'ID', 'aranda-de-duero' ); ?></th>
								<th><?php esc_html_e( 'Dirección', 'aranda-de-duero' ); ?></th>
								<th><?php esc_html_e( 'Fecha', 'aranda-de-duero' ); ?></th>
								<th><?php esc_html_e( 'Editar', 'aranda-de-duero' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $aranda_rows as $aranda_row ) : ?>
								<tr>
									<td><a href="<?php echo esc_url( get_permalink( $aranda_row ) ); ?>"><?php echo esc_html( get_the_title( $aranda_row ) ); ?></a></td>
									<td><?php echo esc_html( (string) $aranda_row->ID ); ?></td>
									<td><code>/tramite/<?php echo esc_html( $aranda_row->post_name ); ?>/</code></td>
									<td><?php echo esc_html( get_the_date( '', $aranda_row ) ); ?></td>
									<td><a href="<?php echo esc_url( (string) get_edit_post_link( $aranda_row ) ); ?>"><?php esc_html_e( 'Editar', 'aranda-de-duero' ); ?></a></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php else : ?>
				<div class="tramites-table-scroll">
					<table class="table table-striped term-index-table">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Nombre', 'aranda-de-duero' ); ?></th>
								<th><?php esc_html_e( 'ID', 'aranda-de-duero' ); ?></th>
								<th><?php esc_html_e( 'Dirección', 'aranda-de-duero' ); ?></th>
								<th><?php esc_html_e( 'Elementos', 'aranda-de-duero' ); ?></th>
								<th><?php esc_html_e( 'Última entrada', 'aranda-de-duero' ); ?></th>
								<th><?php esc_html_e( 'Estado', 'aranda-de-duero' ); ?></th>
								<th><?php esc_html_e( 'Editar', 'aranda-de-duero' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ( $aranda_rows as $aranda_row ) :
								$aranda_status = aranda_de_duero_term_status( $aranda_row, $aranda_now );
								?>
								<tr>
									<td><a href="<?php echo esc_url( get_term_link( $aranda_row ) ); ?>"><?php echo esc_html( $aranda_row->name ); ?></a></td>
									<td><?php echo esc_html( (string) $aranda_row->term_id ); ?></td>
									<td><code>/<?php echo esc_html( $aranda_kind . '/' . $aranda_row->slug ); ?>/</code></td>
									<td><?php echo esc_html( (string) $aranda_row->count ); ?></td>
									<td><?php echo esc_html( $aranda_status['newest'] > 0 ? wp_date( get_option( 'date_format' ), $aranda_status['newest'] ) : '–' ); ?></td>
									<td><?php echo esc_html( $aranda_status['label'] ); ?></td>
									<td><a href="<?php echo esc_url( (string) get_edit_term_link( $aranda_row ) ); ?>"><?php esc_html_e( 'Editar', 'aranda-de-duero' ); ?></a></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<p class="archive-count"><?php esc_html_e( '«Elementos» es lo que cuenta WordPress (todos los tipos de contenido); «Última entrada» y «Estado» miran solo las entradas, que es lo que lista el archivo. «Histórico»: más de 3 años sin entradas.', 'aranda-de-duero' ); ?></p>
			<?php endif; ?>

		</main><!-- #main -->
	</div>
