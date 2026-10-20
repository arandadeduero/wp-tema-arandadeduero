<?php
defined( 'ABSPATH' ) || exit;

/**
 * General information of a concejalía: who leads it, phone, e-mail and where it is.
 *
 * The taxonomy is registered by the Custom Post Type UI plugin, so the fields are term meta registered here (no extra plugin):
 * they are edited in Entradas → Concejalías, shown under the description of the archive (template-parts/term-heading.php) and
 * in the index /concejalia/ (template-parts/term-index.php).
 *
 * @package Aranda_de_Duero
 */

/**
 * Taxonomy that has the information.
 */
const ARANDA_DE_DUERO_CONTACT_TAXONOMY = 'concejalia';

/**
 * The fields: term meta key => label.
 *
 * @return array<string,string>
 */
function aranda_de_duero_term_contact_fields() {
	return array(
		'concejalia_concejal'   => __( 'Concejal o concejala', 'aranda-de-duero' ),
		'concejalia_telefono'   => __( 'Teléfono', 'aranda-de-duero' ),
		'concejalia_email'      => __( 'Correo electrónico', 'aranda-de-duero' ),
		'concejalia_ubicacion'  => __( 'Ubicación', 'aranda-de-duero' ),
	);
}

/**
 * Clean the value of a field.
 *
 * @param string $key   Meta key.
 * @param mixed  $value Value as typed.
 * @return string Empty when it is not valid (an e-mail that is not one).
 */
function aranda_de_duero_sanitize_contact_value( $key, $value ) {
	$value = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( (string) $value ) ) );

	if ( 'concejalia_email' === $key ) {
		return is_email( $value ) ? $value : '';
	}
	if ( 'concejalia_telefono' === $key ) {
		return preg_replace( '/[^0-9+\/,;() .-]/', '', $value );
	}
	return $value;
}

/**
 * Split a phone field into its numbers («947 512296 / 947 511458»).
 *
 * @param string $phones Text of the field.
 * @return array<int,array{label:string,href:string}> Number as written and its `tel:` address.
 */
function aranda_de_duero_phone_numbers( $phones ) {
	$numbers = array();

	foreach ( preg_split( '/[\/,;]/', (string) $phones ) as $part ) {
		$label  = trim( $part );
		$digits = preg_replace( '/[^0-9+]/', '', $label );
		if ( '' === $digits ) {
			continue;
		}
		$numbers[] = array(
			'label' => $label,
			'href'  => 'tel:' . $digits,
		);
	}

	return $numbers;
}

/**
 * The information of a concejalía that has been filled in.
 *
 * @param int $term_id Term id.
 * @return array<string,string> Meta key => value, only the fields that are not empty.
 */
function aranda_de_duero_term_contact( $term_id ) {
	$contact = array();

	foreach ( array_keys( aranda_de_duero_term_contact_fields() ) as $key ) {
		$value = aranda_de_duero_sanitize_contact_value( $key, get_term_meta( $term_id, $key, true ) );
		if ( '' !== $value ) {
			$contact[ $key ] = $value;
		}
	}

	return $contact;
}

/**
 * Register the fields (also in the REST API, to fill them in bulk).
 */
function aranda_de_duero_register_contact_meta() {
	foreach ( array_keys( aranda_de_duero_term_contact_fields() ) as $key ) {
		register_term_meta(
			ARANDA_DE_DUERO_CONTACT_TAXONOMY,
			$key,
			array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => function ( $value ) use ( $key ) {
					return aranda_de_duero_sanitize_contact_value( $key, $value );
				},
				'auth_callback'     => function () {
					return current_user_can( 'manage_categories' );
				},
			)
		);
	}
}
add_action( 'init', 'aranda_de_duero_register_contact_meta' );

/**
 * Fields in the form to add a concejalía.
 */
function aranda_de_duero_contact_add_form_fields() {
	wp_nonce_field( 'aranda_de_duero_contact', 'aranda_de_duero_contact_nonce' );

	foreach ( aranda_de_duero_term_contact_fields() as $key => $label ) {
		?>
		<div class="form-field">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label>
			<input type="<?php echo esc_attr( 'concejalia_email' === $key ? 'email' : 'text' ); ?>" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" value="">
		</div>
		<?php
	}
}
add_action( ARANDA_DE_DUERO_CONTACT_TAXONOMY . '_add_form_fields', 'aranda_de_duero_contact_add_form_fields' );

/**
 * Fields in the form to edit a concejalía.
 *
 * @param WP_Term $term Term being edited.
 */
function aranda_de_duero_contact_edit_form_fields( $term ) {
	wp_nonce_field( 'aranda_de_duero_contact', 'aranda_de_duero_contact_nonce' );

	foreach ( aranda_de_duero_term_contact_fields() as $key => $label ) {
		?>
		<tr class="form-field">
			<th scope="row"><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
			<td>
				<input type="<?php echo esc_attr( 'concejalia_email' === $key ? 'email' : 'text' ); ?>" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( (string) get_term_meta( $term->term_id, $key, true ) ); ?>">
			</td>
		</tr>
		<?php
	}
}
add_action( ARANDA_DE_DUERO_CONTACT_TAXONOMY . '_edit_form_fields', 'aranda_de_duero_contact_edit_form_fields' );

/**
 * Save the fields when a concejalía is created or edited.
 *
 * @param int $term_id Term id.
 */
function aranda_de_duero_contact_save( $term_id ) {
	if ( ! isset( $_POST['aranda_de_duero_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aranda_de_duero_contact_nonce'] ) ), 'aranda_de_duero_contact' ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}

	foreach ( array_keys( aranda_de_duero_term_contact_fields() ) as $key ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}
		$value = aranda_de_duero_sanitize_contact_value( $key, wp_unslash( $_POST[ $key ] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized by the function.
		if ( '' === $value ) {
			delete_term_meta( $term_id, $key );
		} else {
			update_term_meta( $term_id, $key, $value );
		}
	}
}
add_action( 'created_' . ARANDA_DE_DUERO_CONTACT_TAXONOMY, 'aranda_de_duero_contact_save' );
add_action( 'edited_' . ARANDA_DE_DUERO_CONTACT_TAXONOMY, 'aranda_de_duero_contact_save' );
