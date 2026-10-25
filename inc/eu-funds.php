<?php
defined( 'ABSPATH' ) || exit;

/**
 * European-funded projects of the Ayuntamiento («proyecto-europeo», registered in inc/content-types.php): one page each, with
 * its data (programme, granting body and call, EU grant, total investment, status) in a meta box and the rest (contractor,
 * dates, documents, logos) in the editor. The index is the shortcode [proyectos_europeos] on the «Fondos europeos» page.
 *
 * @package Aranda_de_Duero
 */

const ARANDA_DE_DUERO_EU_PROJECT_TYPE = 'proyecto-europeo';

/**
 * Slug of the page with the index of the projects and the anti-fraud policy.
 */
const ARANDA_DE_DUERO_EU_FUNDS_PAGE = 'fondos-nextgenerationeu-prtr';

/**
 * Mandatory wording of the PRTR (Orden HFP/1030/2021, art. 9.3.b), shown as text and not only inside the logos.
 */
const ARANDA_DE_DUERO_EU_FUNDS_WORDING = 'Plan de Recuperación, Transformación y Resiliencia – Financiado por la Unión Europea – NextGenerationEU';

/**
 * Fields of a project: meta key => label. The amounts are numbers in euros; the rest, text.
 *
 * @return array<string,string>
 */
function aranda_de_duero_eu_project_fields() {
	return array(
		'_aranda_eu_programme' => __( 'Programa', 'aranda-de-duero' ),
		'_aranda_eu_body'      => __( 'Organismo y convocatoria', 'aranda-de-duero' ),
		'_aranda_eu_grant'     => __( 'Ayuda de la Unión Europea', 'aranda-de-duero' ),
		'_aranda_eu_total'     => __( 'Inversión total', 'aranda-de-duero' ),
		'_aranda_eu_status'    => __( 'Estado', 'aranda-de-duero' ),
	);
}

/**
 * The statuses a project can be in.
 *
 * @return string[]
 */
function aranda_de_duero_eu_statuses() {
	return array( 'Concedido', 'En ejecución', 'Terminado' );
}

/**
 * An amount in euros as people read it in Spain: «1.672.367,45 €».
 *
 * @param float $amount Euros.
 * @return string
 */
function aranda_de_duero_eu_amount_text( $amount ) {
	return number_format( (float) $amount, 2, ',', '.' ) . ' €';
}

/**
 * The data of a project to show: label => text, only the fields that have a value.
 *
 * @param int $post_id Project.
 * @return array<string,string>
 */
function aranda_de_duero_eu_project_data( $post_id ) {
	$data = array();
	foreach ( aranda_de_duero_eu_project_fields() as $key => $label ) {
		$value = (string) get_post_meta( $post_id, $key, true );
		if ( '' === $value ) {
			continue;
		}
		$data[ $label ] = in_array( $key, array( '_aranda_eu_grant', '_aranda_eu_total' ), true ) ? aranda_de_duero_eu_amount_text( (float) $value ) : $value;
	}

	return $data;
}

/**
 * Meta box with the data of a project.
 */
function aranda_de_duero_eu_project_meta_box() {
	add_meta_box( 'aranda-eu-project', __( 'Datos del proyecto', 'aranda-de-duero' ), 'aranda_de_duero_eu_project_meta_box_html', ARANDA_DE_DUERO_EU_PROJECT_TYPE, 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'aranda_de_duero_eu_project_meta_box' );

/**
 * Print the fields.
 *
 * @param WP_Post $post Project being edited.
 */
function aranda_de_duero_eu_project_meta_box_html( $post ) {
	wp_nonce_field( 'aranda_eu_project', 'aranda_eu_project_nonce' );
	foreach ( aranda_de_duero_eu_project_fields() as $key => $label ) {
		$value = (string) get_post_meta( $post->ID, $key, true );
		$id    = 'aranda-eu-' . substr( $key, 11 );
		echo '<p><label for="' . esc_attr( $id ) . '"><strong>' . esc_html( $label ) . '</strong></label><br>';
		if ( '_aranda_eu_status' === $key ) {
			echo '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $key ) . '"><option value="">' . esc_html__( 'Sin indicar', 'aranda-de-duero' ) . '</option>';
			foreach ( aranda_de_duero_eu_statuses() as $status ) {
				echo '<option value="' . esc_attr( $status ) . '"' . selected( $status, $value, false ) . '>' . esc_html( $status ) . '</option>';
			}
			echo '</select>';
		} elseif ( in_array( $key, array( '_aranda_eu_grant', '_aranda_eu_total' ), true ) ) {
			echo '<input type="number" step="0.01" min="0" id="' . esc_attr( $id ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '"> €';
		} else {
			echo '<input type="text" class="widefat" id="' . esc_attr( $id ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '">';
		}
		echo '</p>';
	}
	echo '<p class="description">' . esc_html__( 'Los campos vacíos no se muestran. Importes en euros, con punto para los céntimos (1672367.45). La empresa, las fechas, los documentos y la barra de logos van en el contenido.', 'aranda-de-duero' ) . '</p>';
}

/**
 * Save the fields.
 *
 * @param int $post_id Project.
 */
function aranda_de_duero_eu_project_save( $post_id ) {
	if ( ! isset( $_POST['aranda_eu_project_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aranda_eu_project_nonce'] ) ), 'aranda_eu_project' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( array_keys( aranda_de_duero_eu_project_fields() ) as $key ) {
		$value = isset( $_POST[ $key ] ) ? trim( sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) ) : '';
		if ( in_array( $key, array( '_aranda_eu_grant', '_aranda_eu_total' ), true ) ) {
			$value = is_numeric( $value ) ? (string) (float) $value : '';
		} elseif ( '_aranda_eu_status' === $key && ! in_array( $value, aranda_de_duero_eu_statuses(), true ) ) {
			$value = '';
		}

		if ( '' === $value ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}
}
add_action( 'save_post_' . ARANDA_DE_DUERO_EU_PROJECT_TYPE, 'aranda_de_duero_eu_project_save' );

/**
 * Published projects, in title order.
 *
 * @return array<int,WP_Post>
 */
function aranda_de_duero_eu_projects() {
	return get_posts(
		array(
			'post_type'      => ARANDA_DE_DUERO_EU_PROJECT_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		)
	);
}

/**
 * The projects as cards for template-parts/service-cards.php: programme, status and EU grant under the title.
 *
 * @param array<int,WP_Post> $projects Projects.
 * @return array<int, array{title: string, text: string, url: string, new_tab: bool}>
 */
function aranda_de_duero_eu_project_cards( $projects ) {
	$cards = array();
	foreach ( $projects as $project ) {
		$data    = aranda_de_duero_eu_project_data( $project->ID );
		$grant   = __( 'Ayuda de la Unión Europea', 'aranda-de-duero' );
		$parts   = array_filter( array( $data[ __( 'Programa', 'aranda-de-duero' ) ] ?? '', $data[ __( 'Estado', 'aranda-de-duero' ) ] ?? '', isset( $data[ $grant ] ) ? $data[ $grant ] . ' ' . __( 'de la UE', 'aranda-de-duero' ) : '' ) );
		$cards[] = array(
			'title'   => get_the_title( $project ),
			'text'    => implode( ' · ', $parts ),
			'url'     => (string) get_permalink( $project ),
			'new_tab' => false,
		);
	}

	return $cards;
}

/**
 * Sum of the EU grants of the projects that have one.
 *
 * @param array<int,WP_Post> $projects Projects.
 * @return float
 */
function aranda_de_duero_eu_funds_total( $projects ) {
	$total = 0.0;
	foreach ( $projects as $project ) {
		$total += (float) get_post_meta( $project->ID, '_aranda_eu_grant', true );
	}

	return $total;
}

/**
 * [proyectos_europeos]: the total of EU grants and a card per published project.
 *
 * @return string
 */
function aranda_de_duero_eu_projects_shortcode() {
	$projects = aranda_de_duero_eu_projects();
	if ( array() === $projects ) {
		return '';
	}

	$total = aranda_de_duero_eu_funds_total( $projects );
	ob_start();
	if ( $total > 0 ) {
		/* translators: %s: amount in euros. */
		echo '<p class="eu-funds-total">' . esc_html( sprintf( __( 'Ayudas de la Unión Europea en los proyectos con importe publicado: %s', 'aranda-de-duero' ), aranda_de_duero_eu_amount_text( $total ) ) ) . '</p>';
	}
	get_template_part( 'template-parts/service-cards', null, array( 'items' => aranda_de_duero_eu_project_cards( $projects ) ) );

	return (string) ob_get_clean();
}

/**
 * Register [proyectos_europeos].
 */
function aranda_de_duero_eu_register_shortcode() {
	add_shortcode( 'proyectos_europeos', 'aranda_de_duero_eu_projects_shortcode' );
}
add_action( 'init', 'aranda_de_duero_eu_register_shortcode' );
