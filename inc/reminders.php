<?php
defined( 'ABSPATH' ) || exit;

/**
 * E-mail reminders for information that goes out of date and lives outside the code (who is in charge of each concejalía...).
 *
 * A daily WP-Cron task sends each reminder once, on its date or in the week after it (the cron only runs with visits). A reminder
 * whose date passed long ago is never sent, so deploying the theme does not fire the old ones.
 *
 * @package Aranda_de_Duero
 */

/**
 * Who receives the reminders of the theme.
 *
 * @return string[]
 */
function aranda_de_duero_reminder_recipients() {
	return apply_filters(
		'aranda_de_duero_reminder_recipients',
		array( 'informatica@arandadeduero.es', 'glopez@arandadeduero.es' )
	);
}

/**
 * Days after its date during which a reminder is still sent.
 *
 * @return int
 */
function aranda_de_duero_reminder_grace_days() {
	return 7;
}

/**
 * Reminders whose date falls in a year.
 *
 * @param int $year Year.
 * @return array<int,array{id: string, date: string, subject: string, message: string}>
 */
function aranda_de_duero_periodic_reminders( $year ) {
	$year      = (int) $year;
	$reminders = array();

	// Twice a year: the delegations of the concejales change with the plenary sessions and the governing team.
	foreach ( array( '01', '07' ) as $month ) {
		$reminders[] = array(
			'id'      => 'delegaciones-' . $year . '-' . $month,
			'date'    => $year . '-' . $month . '-01',
			'subject' => __( 'Revisar posibles cambios en las delegaciones de las concejalías', 'aranda-de-duero' ),
			'message' => __(
				"Aviso semestral (1 de enero y 1 de julio): revisar si ha habido cambios en las delegaciones del equipo de gobierno.\n\nQué comprobar:\n\n1. La lista oficial en https://www.arandadeduero.es/corporacion-municipal/ frente a las concejalías de la web (Entradas → Concejalías): concejal o concejala, teléfono, correo y ubicación de cada una.\n2. Si cambia el reparto de áreas, que las concejalías, el menú «Áreas y servicios» y la tabla de /informacion-de-concejalias/ lo reflejen.\n3. Si se crea, une o elimina un área, actualizar docs/concejalias-vigentes-y-terminos.md en el repositorio del tema.\n\nSi no ha cambiado nada, no hay que hacer nada.",
				'aranda-de-duero'
			),
		);
	}

	if ( 2027 === $year ) {
		$reminders[] = array(
			'id'      => 'corporacion-2027-06-15',
			'date'    => '2027-06-15',
			'subject' => __( 'Revisar la web con la corporación municipal vigente', 'aranda-de-duero' ),
			'message' => __(
				"Aviso programado para el 15 de junio de 2027: comprobar que la web refleja la corporación municipal vigente.\n\nQué comprobar:\n\n1. Alcalde o alcaldesa y concejales en https://www.arandadeduero.es/corporacion-municipal/.\n2. Las delegaciones de cada concejalía (Entradas → Concejalías): concejal o concejala, teléfono, correo y ubicación.\n3. Textos del tema que citan al alcalde o alcaldesa por su nombre (por ejemplo, la página de propuestas de los barrios) y el menú «Áreas y servicios».\n4. Si cambia el reparto de áreas, actualizar docs/concejalias-vigentes-y-terminos.md.",
				'aranda-de-duero'
			),
		);
	}

	return $reminders;
}

/**
 * The reminders to send now: their date was reached in the last few days and they were not sent yet.
 *
 * @param string                                                                           $today     Today, «YYYY-MM-DD».
 * @param array<int,array{id: string, date: string, subject: string, message: string}>      $reminders Candidates.
 * @param string[]                                                                         $sent      Ids already sent.
 * @return array<int,array{id: string, date: string, subject: string, message: string}>
 */
function aranda_de_duero_reminders_due_today( $today, $reminders, $sent ) {
	$day = date_create_immutable( $today );
	if ( false === $day ) {
		return array();
	}

	$due = array();
	foreach ( $reminders as $reminder ) {
		$date = date_create_immutable( $reminder['date'] );
		if ( false === $date || in_array( $reminder['id'], $sent, true ) ) {
			continue;
		}

		$since = (int) $date->diff( $day )->format( '%r%a' );
		if ( $since >= 0 && $since <= aranda_de_duero_reminder_grace_days() ) {
			$due[] = $reminder;
		}
	}

	return $due;
}

/**
 * Daily check: send the reminders that are due. A reminder is marked as sent only when the e-mail went out.
 */
function aranda_de_duero_periodic_reminders_check() {
	$today = current_time( 'Y-m-d' );

	$sent = get_option( 'aranda_reminders_sent', array() );
	$sent = is_array( $sent ) ? $sent : array();

	$due = aranda_de_duero_reminders_due_today( $today, aranda_de_duero_periodic_reminders( (int) substr( $today, 0, 4 ) ), $sent );
	foreach ( $due as $reminder ) {
		if ( wp_mail( aranda_de_duero_reminder_recipients(), $reminder['subject'], $reminder['message'] ) ) {
			$sent[] = $reminder['id'];
			update_option( 'aranda_reminders_sent', $sent, false );
		}
	}
}
add_action( 'aranda_de_duero_periodic_reminders', 'aranda_de_duero_periodic_reminders_check' );

/**
 * Schedule the daily check.
 */
function aranda_de_duero_schedule_periodic_reminders() {
	if ( ! wp_next_scheduled( 'aranda_de_duero_periodic_reminders' ) ) {
		wp_schedule_event( time(), 'daily', 'aranda_de_duero_periodic_reminders' );
	}
}
add_action( 'init', 'aranda_de_duero_schedule_periodic_reminders' );
