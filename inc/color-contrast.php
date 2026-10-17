<?php
defined( 'ABSPATH' ) || exit;

/**
 * WCAG colour contrast helpers and Customizer validation.
 *
 * Public-sector sites in Spain must meet WCAG 2.1 AA (RD 1112/2018), which
 * asks for a 4.5:1 contrast ratio on normal text. The colour pickers in the
 * Customizer accept any colour, so pairs that can't be read are rejected.
 *
 * @package Aranda_de_Duero
 */

/**
 * Minimum contrast ratio for normal-size text (WCAG 2.1 AA).
 */
const ARANDA_DE_DUERO_MIN_CONTRAST = 4.5;

/**
 * Relative luminance of a hex colour (WCAG 2.1 definition).
 *
 * @param string $hex Colour such as "#fff" or "#0083c1".
 * @return float|null Luminance from 0 to 1, or null if the colour is invalid.
 */
function aranda_de_duero_relative_luminance( $hex ) {
	$hex = ltrim( trim( (string) $hex ), '#' );

	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}

	if ( 1 !== preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
		return null;
	}

	$channels = array();
	foreach ( str_split( $hex, 2 ) as $pair ) {
		$value      = hexdec( $pair ) / 255;
		$channels[] = ( $value <= 0.03928 ) ? $value / 12.92 : pow( ( $value + 0.055 ) / 1.055, 2.4 );
	}

	return 0.2126 * $channels[0] + 0.7152 * $channels[1] + 0.0722 * $channels[2];
}

/**
 * WCAG contrast ratio between two hex colours.
 *
 * @param string $first  First colour.
 * @param string $second Second colour.
 * @return float|null Ratio from 1 to 21, or null if either colour is invalid.
 */
function aranda_de_duero_contrast_ratio( $first, $second ) {
	$a = aranda_de_duero_relative_luminance( $first );
	$b = aranda_de_duero_relative_luminance( $second );

	if ( null === $a || null === $b ) {
		return null;
	}

	return ( max( $a, $b ) + 0.05 ) / ( min( $a, $b ) + 0.05 );
}

/**
 * Foreground setting => background setting it is drawn on.
 *
 * @return array<string, array{background: string, label: string}>
 */
function aranda_de_duero_contrast_pairs() {
	return array(
		'aranda_de_duero_text_color'              => array(
			'background' => 'aranda_de_duero_background_color',
			'label'      => 'El color del texto',
		),
		'aranda_de_duero_header_text_color'       => array(
			'background' => 'aranda_de_duero_background_color',
			'label'      => 'El color de los encabezados',
		),
		'aranda_de_duero_link_color'              => array(
			'background' => 'aranda_de_duero_background_color',
			'label'      => 'El color de los enlaces',
		),
		'aranda_de_duero_main_section_text_color' => array(
			'background' => 'aranda_de_duero_main_section_background',
			'label'      => 'El color de texto de las secciones principales',
		),
	);
}

/**
 * Error message when two colours don't have enough contrast.
 *
 * @param string $label Description of the foreground colour.
 * @param float  $ratio Ratio found.
 * @return string
 */
function aranda_de_duero_contrast_message( $label, $ratio ) {
	return sprintf(
		/* translators: 1: colour description, 2: contrast ratio found, 3: minimum ratio. */
		__( '%1$s no tiene suficiente contraste con su color de fondo (%2$s:1; el mínimo para cumplir WCAG AA es %3$s:1).', 'aranda-de-duero' ),
		$label,
		number_format_i18n( $ratio, 2 ),
		number_format_i18n( ARANDA_DE_DUERO_MIN_CONTRAST, 1 )
	);
}

/**
 * Validate a colour setting, in either role of a foreground/background pair.
 *
 * Each colour is checked against its counterpart as it will be after this save:
 * the value posted in the same Customizer session, or else the stored one.
 *
 * @param WP_Error             $validity Validation result so far.
 * @param string               $value    Submitted colour.
 * @param WP_Customize_Setting $setting  The setting being saved.
 * @return WP_Error
 */
function aranda_de_duero_validate_color_contrast( $validity, $value, $setting ) {
	$pairs = aranda_de_duero_contrast_pairs();
	$id    = $setting->id;
	$check = array();

	if ( isset( $pairs[ $id ] ) ) {
		$check[] = array( $value, $pairs[ $id ]['background'], $pairs[ $id ]['label'], true );
	}

	foreach ( $pairs as $foreground => $pair ) {
		if ( $pair['background'] === $id ) {
			$check[] = array( $value, $foreground, $pair['label'], false );
		}
	}

	foreach ( $check as $item ) {
		list( $mine, $other_id, $label, $is_foreground ) = $item;

		$other = $setting->manager->get_setting( $other_id );
		if ( ! $other ) {
			continue;
		}

		$other_value = $other->post_value( $other->value() );
		$ratio       = $is_foreground
			? aranda_de_duero_contrast_ratio( $mine, $other_value )
			: aranda_de_duero_contrast_ratio( $other_value, $mine );

		if ( null !== $ratio && $ratio < ARANDA_DE_DUERO_MIN_CONTRAST ) {
			$validity->add( 'low_contrast', aranda_de_duero_contrast_message( $label, $ratio ) );
			break;
		}
	}

	return $validity;
}
