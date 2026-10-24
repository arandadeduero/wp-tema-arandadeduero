<?php
defined( 'ABSPATH' ) || exit;

/**
 * Duty pharmacies of Aranda de Duero.
 *
 * The data are plain files in data/farmacias/ (source: Colegio Oficial de Farmacéuticos de Burgos): `catalogo.json` has the
 * twelve pharmacies and `<year>.json` has the pharmacy on duty each day of that year. A new year is a new file; no PHP changes.
 * Copied from the city API data (api-ciudad) while that API is not public.
 *
 * @package Aranda_de_Duero
 */

/**
 * Days before 31 December on which the reminder to load next year's calendar is sent, while the file is still missing.
 *
 * @return int[]
 */
function aranda_de_duero_pharmacy_reminder_days() {
	return array( 15, 10, 5 );
}

/**
 * Decoded content of a file of data/farmacias/, or null when it is missing or is not valid JSON.
 *
 * @param string $file File name, e.g. «2026.json».
 * @return array<string,mixed>|null
 */
function aranda_de_duero_pharmacy_read_json( $file ) {
	$path = get_template_directory() . '/data/farmacias/' . $file;
	if ( ! is_readable( $path ) ) {
		return null;
	}

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- a file of the theme, not a URL.
	$raw = file_get_contents( $path );
	if ( false === $raw ) {
		return null;
	}

	$data = json_decode( $raw, true );

	return is_array( $data ) ? $data : null;
}

/**
 * The pharmacies by id.
 *
 * @return array<int,array<string,mixed>>
 */
function aranda_de_duero_pharmacy_catalog() {
	$data = aranda_de_duero_pharmacy_read_json( 'catalogo.json' );
	if ( null === $data || empty( $data['pharmacies'] ) || ! is_array( $data['pharmacies'] ) ) {
		return array();
	}

	$catalog = array();
	foreach ( $data['pharmacies'] as $pharmacy ) {
		if ( isset( $pharmacy['id'] ) ) {
			$catalog[ (int) $pharmacy['id'] ] = $pharmacy;
		}
	}

	return $catalog;
}

/**
 * The duty calendar of a year, or null when that year's file is missing or empty.
 *
 * @param int $year Year.
 * @return array{schedule: array<string,int>, low_confidence: array<string,int>}|null Pharmacy id by date, and the dates the source
 *                                                                                    is not sure about (as keys).
 */
function aranda_de_duero_pharmacy_guard_year( $year ) {
	$data = aranda_de_duero_pharmacy_read_json( (int) $year . '.json' );
	if ( null === $data || empty( $data['schedule'] ) || ! is_array( $data['schedule'] ) ) {
		return null;
	}

	$schedule = array();
	foreach ( $data['schedule'] as $entry ) {
		if ( isset( $entry['date'], $entry['pharmacyId'] ) ) {
			$schedule[ (string) $entry['date'] ] = (int) $entry['pharmacyId'];
		}
	}

	$low = array();
	if ( isset( $data['meta']['lowConfidenceDates'] ) && is_array( $data['meta']['lowConfidenceDates'] ) ) {
		$low = array_flip( array_map( 'strval', $data['meta']['lowConfidenceDates'] ) );
	}

	return array(
		'schedule'       => $schedule,
		'low_confidence' => $low,
	);
}

/**
 * The pharmacy on duty on a day.
 *
 * @param string $date Day, «YYYY-MM-DD».
 * @return array{date: string, pharmacy: array<string,mixed>, low_confidence: bool}|null Null when there is no calendar for that
 *                                                                                      year or the day is not in it.
 */
function aranda_de_duero_pharmacy_guard_for_date( $date ) {
	if ( 1 !== preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) {
		return null;
	}

	$year = aranda_de_duero_pharmacy_guard_year( (int) substr( $date, 0, 4 ) );
	if ( null === $year || ! isset( $year['schedule'][ $date ] ) ) {
		return null;
	}

	$catalog = aranda_de_duero_pharmacy_catalog();
	$id      = $year['schedule'][ $date ];
	if ( ! isset( $catalog[ $id ] ) ) {
		return null;
	}

	return array(
		'date'           => $date,
		'pharmacy'       => $catalog[ $id ],
		'low_confidence' => isset( $year['low_confidence'][ $date ] ),
	);
}

/**
 * The pharmacies on duty on the days after a date (the days without data are left out).
 *
 * @param string $from Day before the first one wanted, «YYYY-MM-DD».
 * @param int    $days How many days to look at.
 * @return array<int,array{date: string, pharmacy: array<string,mixed>, low_confidence: bool}>
 */
function aranda_de_duero_pharmacy_guard_after( $from, $days ) {
	$start = date_create_immutable( $from );
	if ( false === $start ) {
		return array();
	}

	$list = array();
	for ( $i = 1; $i <= $days; $i++ ) {
		$entry = aranda_de_duero_pharmacy_guard_for_date( $start->modify( '+' . $i . ' day' )->format( 'Y-m-d' ) );
		if ( null !== $entry ) {
			$list[] = $entry;
		}
	}

	return $list;
}

/**
 * A Spanish phone number in groups of three digits («947 500 269»); anything else is returned as it is.
 *
 * @param string $phone Phone number.
 * @return string
 */
function aranda_de_duero_pharmacy_phone( $phone ) {
	$phone = (string) $phone;

	return 1 === preg_match( '/^\d{9}$/', $phone ) ? trim( chunk_split( $phone, 3, ' ' ) ) : $phone;
}

/**
 * Days from a date to the 31 December of its year (0 on 31 December itself).
 *
 * @param string $today Day, «YYYY-MM-DD».
 * @return int
 */
function aranda_de_duero_pharmacy_days_to_year_end( $today ) {
	$day = date_create_immutable( $today );
	if ( false === $day ) {
		return 0;
	}

	$end = date_create_immutable( $day->format( 'Y' ) . '-12-31' );

	return false === $end ? 0 : (int) $day->diff( $end )->days;
}

/**
 * The reminder days that are due: those not sent yet, when the next year's calendar is still missing.
 *
 * Several can be due at once if the site had no visit for a few days (the cron only runs with visits); they are returned together
 * so that one e-mail goes out, not one per day.
 *
 * @param string $today         Today, «YYYY-MM-DD».
 * @param bool   $next_year_has Whether next year's file already exists.
 * @param int[]  $sent          Reminder days already sent this year.
 * @return int[]
 */
function aranda_de_duero_pharmacy_reminders_due( $today, $next_year_has, $sent ) {
	if ( $next_year_has ) {
		return array();
	}

	$days_left = aranda_de_duero_pharmacy_days_to_year_end( $today );

	$due = array();
	foreach ( aranda_de_duero_pharmacy_reminder_days() as $threshold ) {
		if ( $days_left <= $threshold && ! in_array( $threshold, $sent, true ) ) {
			$due[] = $threshold;
		}
	}

	return $due;
}

/**
 * E-mail asking to load next year's calendar, sent from the daily check.
 *
 * @param int $year      Year being closed.
 * @param int $days_left Days until 31 December.
 * @return array{subject: string, message: string}
 */
function aranda_de_duero_pharmacy_reminder_mail( $year, $days_left ) {
	$next = $year + 1;

	return array(
		'subject' => sprintf(
			/* translators: 1: days left, 2: next year. */
			_n(
				'Farmacias de guardia: falta %1$d día para cargar el calendario de %2$d',
				'Farmacias de guardia: faltan %1$d días para cargar el calendario de %2$d',
				$days_left,
				'aranda-de-duero'
			),
			$days_left,
			$next
		),
		'message' => sprintf(
			/* translators: 1: next year, 2: current year. */
			__(
				"La web del Ayuntamiento no tiene todavía el calendario de farmacias de guardia de %1\$d, y el de %2\$d termina el 31 de diciembre.\n\nPara que la página «Farmacias de guardia» siga funcionando:\n\n1. Conseguir el calendario %1\$d del Colegio Oficial de Farmacéuticos de Burgos (https://www.cofburgos.es), zona farmacéutica de Aranda de Duero.\n2. Crear en el repositorio del tema el fichero data/farmacias/%1\$d.json con el mismo formato que data/farmacias/%2\$d.json (una entrada por día, con su fecha y el número de la farmacia).\n3. Subir el tema completo a WordPress (npm run release) y vaciar la caché de Cache Enabler.\n\nSi la farmacia de un día no está clara en el documento, anotar esa fecha en meta.lowConfidenceDates y la web avisará de que se confirme con el Colegio.\n\nEste aviso se repite a los 15, 10 y 5 días del 31 de diciembre mientras no exista el fichero.",
				'aranda-de-duero'
			),
			$next,
			$year
		),
	);
}

/**
 * Daily check: e-mail the reminder when next year's calendar is missing close to 31 December.
 *
 * Marked as sent only when the e-mail went out, so a failed delivery is tried again on the next run.
 */
function aranda_de_duero_pharmacy_reminder_check() {
	$today = current_time( 'Y-m-d' );
	$year  = (int) substr( $today, 0, 4 );

	$sent_all = get_option( 'aranda_pharmacy_reminders_sent', array() );
	$sent_all = is_array( $sent_all ) ? $sent_all : array();
	$sent     = isset( $sent_all[ $year ] ) && is_array( $sent_all[ $year ] ) ? $sent_all[ $year ] : array();

	$next_year = aranda_de_duero_pharmacy_guard_year( $year + 1 );
	$due       = aranda_de_duero_pharmacy_reminders_due( $today, null !== $next_year, $sent );
	if ( array() === $due ) {
		return;
	}

	$mail = aranda_de_duero_pharmacy_reminder_mail( $year, aranda_de_duero_pharmacy_days_to_year_end( $today ) );

	if ( wp_mail( aranda_de_duero_reminder_recipients(), $mail['subject'], $mail['message'] ) ) {
		$sent_all[ $year ] = array_values( array_unique( array_merge( $sent, $due ) ) );
		update_option( 'aranda_pharmacy_reminders_sent', $sent_all, false );
	} else {
		error_log( 'aranda-de-duero: wp_mail() no pudo enviar el aviso del calendario de farmacias; se reintenta mañana.' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- the only trace of a failed reminder.
	}
}
add_action( 'aranda_de_duero_pharmacy_reminder', 'aranda_de_duero_pharmacy_reminder_check' );

/**
 * Schedule the daily check (WP-Cron runs it with the first visit after it is due).
 */
function aranda_de_duero_pharmacy_schedule_reminder() {
	if ( ! wp_next_scheduled( 'aranda_de_duero_pharmacy_reminder' ) ) {
		wp_schedule_event( time(), 'daily', 'aranda_de_duero_pharmacy_reminder' );
	}
}
add_action( 'init', 'aranda_de_duero_pharmacy_schedule_reminder' );
