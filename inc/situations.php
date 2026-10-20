<?php
defined( 'ABSPATH' ) || exit;

/**
 * «Trámites por situaciones»: the procedures of the town hall grouped by what the visitor is going through («me empadrono»,
 * «voy a hacer obras»…), instead of by department.
 *
 * Nothing new is built: it brings together the procedures that already have a page in this site (the `tramite` entries listed in
 * «Trámites») and the services of the electronic office from inc/services.php and inc/search.php. Only permanent procedures are
 * listed: the yearly calls for grants change their address every year and stay in the full list.
 *
 * @package Aranda_de_Duero
 */

/**
 * Permanent procedures that have their own page in this site (`/tramite/<slug>/`).
 *
 * The descriptions say no more than the title of the procedure does.
 *
 * @return array<string, array{title: string, text: string, url: string, new_tab: bool, keywords: string}>
 */
function aranda_de_duero_portal_procedures() {
	$rows = array(
		'padron'       => array( 'informacion-de-documentacion-requerida-para-altas-cambio-de-domicilio-renovacion-confirmacion-en-padron-de-habitantes', __( 'Altas, cambios de domicilio y renovaciones del padrón', 'aranda-de-duero' ), __( 'Documentación que necesitas para darte de alta, cambiar de domicilio o renovar tu inscripción en el padrón.', 'aranda-de-duero' ), 'padron empadronamiento empadronarme alta baja cambio domicilio renovacion confirmacion mudanza' ),
		'veladores'    => array( 'solicitud-de-autorizacion-renovacion-para-la-instalacion-de-veladores-en-la-via-publica-y-o-licencia-de-obra-de-cerramiento-de-terraza-con-construccion-ligera', __( 'Veladores y terrazas', 'aranda-de-duero' ), __( 'Solicita o renueva la autorización de veladores en la vía pública o la licencia de cerramiento de terraza.', 'aranda-de-duero' ), 'velador veladores terraza terrazas bar hosteleria cerramiento licencia obra' ),
		'ite'          => array( 'solicitud-para-el-acceso-al-registro-municipal-de-i-t-e', __( 'Registro municipal de ITE', 'aranda-de-duero' ), __( 'Solicitud para el acceso al registro municipal de ITE.', 'aranda-de-duero' ), 'ite inspeccion tecnica edificios registro municipal' ),
		'via-publica'  => array( 'solicitud-de-ocupacion-de-via-publica', __( 'Ocupación de la vía pública', 'aranda-de-duero' ), __( 'Solicita la ocupación de la vía pública.', 'aranda-de-duero' ), 'ocupacion via publica calle vallas contenedor andamio' ),
		'ambiental'    => array( 'modelo-comunicacion-ambiental-antes-inicio-actividad-inocua', __( 'Comunicación ambiental', 'aranda-de-duero' ), __( 'Modelo de comunicación ambiental (antes, inicio de actividad inocua).', 'aranda-de-duero' ), 'ambiental actividad inocua negocio local abrir apertura comunicacion' ),
		'inicio-lic'   => array( 'comunicacion-de-inicio-de-actividad-sometida-a-licencia-ambiental', __( 'Inicio de actividad con licencia ambiental', 'aranda-de-duero' ), __( 'Comunicación de inicio de una actividad sometida a licencia ambiental.', 'aranda-de-duero' ), 'ambiental actividad licencia inicio abrir apertura negocio local' ),
		'licencia-amb' => array( 'solicitud-de-licencia-ambiental', __( 'Licencia ambiental', 'aranda-de-duero' ), __( 'Solicita una licencia ambiental.', 'aranda-de-duero' ), 'licencia ambiental actividad negocio local abrir apertura' ),
		'parcelacion'  => array( 'solicitud-de-parcelacion-o-segregacion', __( 'Parcelación o segregación', 'aranda-de-duero' ), __( 'Solicita una parcelación o una segregación.', 'aranda-de-duero' ), 'parcelacion segregacion parcela finca terreno' ),
		'derribo'      => array( 'solicitud-de-derribo', __( 'Derribo', 'aranda-de-duero' ), __( 'Solicita un derribo.', 'aranda-de-duero' ), 'derribo demolicion demoler edificio' ),
		'primera-ocup' => array( 'solicitud-de-primera-ocupacion', __( 'Primera ocupación', 'aranda-de-duero' ), __( 'Solicita la primera ocupación.', 'aranda-de-duero' ), 'primera ocupacion vivienda habitabilidad obra nueva' ),
		'obra-mayor'   => array( 'solicitud-de-obra-mayor', __( 'Obra mayor', 'aranda-de-duero' ), __( 'Solicita una obra mayor.', 'aranda-de-duero' ), 'obra obras mayor licencia construir reforma edificar construccion' ),
		'matrimonio'   => array( 'solicitud-para-contraer-matrimonio-civil', __( 'Matrimonio civil', 'aranda-de-duero' ), __( 'Solicita contraer matrimonio civil.', 'aranda-de-duero' ), 'matrimonio civil boda bodas contraer' ),
		'catastro'     => array( 'punto-de-informacion-catastral', __( 'Punto de información catastral', 'aranda-de-duero' ), __( 'Consulta información catastral.', 'aranda-de-duero' ), 'catastro catastral informacion punto parcela referencia' ),
		'tasa-terreno' => array( 'tasa-ocupacion-de-terrenos-con-materiales-construccion', __( 'Tasa por ocupar terrenos con materiales de construcción', 'aranda-de-duero' ), __( 'Tasa por ocupar terrenos con materiales de construcción.', 'aranda-de-duero' ), 'tasa ocupacion terrenos materiales construccion obra' ),
		'plusvalia'    => array( 'impuesto-plus-valias', __( 'Impuesto de plusvalías', 'aranda-de-duero' ), __( 'Información del impuesto de plusvalías.', 'aranda-de-duero' ), 'plusvalia plusvalias incremento valor terrenos impuesto venta herencia' ),
		'ibi'          => array( 'impuesto-sobre-bienes-inmuebles-i-b-i', __( 'Impuesto sobre bienes inmuebles (IBI)', 'aranda-de-duero' ), __( 'Información del impuesto sobre bienes inmuebles.', 'aranda-de-duero' ), 'ibi bienes inmuebles impuesto vivienda contribucion recibo' ),
		'ivtm'         => array( 'impuesto-sobre-vehiculos-de-traccion-mecanica', __( 'Impuesto sobre vehículos de tracción mecánica', 'aranda-de-duero' ), __( 'Información del impuesto sobre vehículos.', 'aranda-de-duero' ), 'ivtm vehiculos traccion mecanica impuesto coche circulacion' ),
	);

	$procedures = array();
	foreach ( $rows as $key => $row ) {
		$procedures[ $key ] = array(
			'title'    => $row[1],
			'text'     => $row[2],
			'url'      => home_url( '/tramite/' . $row[0] . '/' ),
			'new_tab'  => false,
			'keywords' => $row[3],
		);
	}

	return $procedures;
}

/**
 * Where «Trámites por situaciones» is: the page with that slug when it exists and is published, '' until then.
 *
 * @return string
 */
function aranda_de_duero_situations_url() {
	return aranda_de_duero_page_url( 'tramites-por-situaciones' );
}

/**
 * A service or procedure by its exact title, searching the shortcuts, the payment services, the office pages and the procedures.
 *
 * @param string $title Title.
 * @return array{title: string, text: string, url: string, new_tab: bool}|null
 */
function aranda_de_duero_service_by_title( $title ) {
	$candidates = array_merge( aranda_de_duero_quick_access(), aranda_de_duero_service_directory(), array_values( aranda_de_duero_portal_procedures() ) );
	foreach ( aranda_de_duero_payment_services() as $group ) {
		$candidates = array_merge( $candidates, $group['items'] );
	}

	foreach ( $candidates as $candidate ) {
		if ( $candidate['title'] === $title ) {
			return array(
				'title'   => $candidate['title'],
				'text'    => $candidate['text'],
				'url'     => $candidate['url'],
				'new_tab' => $candidate['new_tab'],
			);
		}
	}

	return null;
}

/**
 * The situations of the page, each with the services and procedures that help in it.
 *
 * @return array<int, array{title: string, items: array<int, array{title: string, text: string, url: string, new_tab: bool}>}>
 */
function aranda_de_duero_situations() {
	$procedures = aranda_de_duero_portal_procedures();

	$groups = array(
		array(
			'title' => __( 'Me empadrono o cambio de domicilio', 'aranda-de-duero' ),
			'items' => array( $procedures['padron'], 'Certificado de padrón', 'Cita previa', 'Documentos habitualmente solicitados' ),
		),
		array(
			'title' => __( 'Me caso', 'aranda-de-duero' ),
			'items' => array( $procedures['matrimonio'], 'Cita previa' ),
		),
		array(
			'title' => __( 'Quiero abrir o tengo un negocio', 'aranda-de-duero' ),
			'items' => array( $procedures['ambiental'], $procedures['licencia-amb'], $procedures['inicio-lic'], $procedures['veladores'], $procedures['via-publica'], 'Ayudas y subvenciones' ),
		),
		array(
			'title' => __( 'Voy a hacer obras o construir', 'aranda-de-duero' ),
			'items' => array( $procedures['obra-mayor'], $procedures['derribo'], $procedures['parcelacion'], $procedures['primera-ocup'], $procedures['tasa-terreno'], $procedures['catastro'], $procedures['ite'] ),
		),
		array(
			'title' => __( 'Pago mis impuestos y tasas', 'aranda-de-duero' ),
			'items' => array( $procedures['ibi'], $procedures['ivtm'], $procedures['plusvalia'], 'Pagos y recibos', 'Calendario fiscal', 'Ordenanzas fiscales' ),
		),
		array(
			'title' => __( 'Busco empleo o ayudas', 'aranda-de-duero' ),
			'items' => array(
				'Empleo público',
				'Ayudas y subvenciones',
				array(
					'title'   => __( 'Convocatorias de ayudas', 'aranda-de-duero' ),
					'text'    => __( 'Ayudas a asociaciones, a estudiantes y a asociaciones de padres: están entre los trámites del portal.', 'aranda-de-duero' ),
					'url'     => home_url( '/tramites/' ),
					'new_tab' => false,
				),
			),
		),
		array(
			'title' => __( 'Quiero presentar un documento o una queja', 'aranda-de-duero' ),
			'items' => array( 'Documentación para un expediente en trámite', 'Oficinas de asistencia en materia de registro', 'Validación de documentos', 'Quejas y sugerencias', 'Notificaciones electrónicas' ),
		),
	);

	// Entries given by title are looked up; one that no longer exists is left out instead of breaking the page.
	foreach ( $groups as $index => $group ) {
		$items = array();
		foreach ( $group['items'] as $item ) {
			$resolved = is_array( $item ) ? $item : aranda_de_duero_service_by_title( $item );
			if ( null !== $resolved ) {
				$items[] = array(
					'title'   => $resolved['title'],
					'text'    => $resolved['text'],
					'url'     => $resolved['url'],
					'new_tab' => $resolved['new_tab'],
				);
			}
		}
		$groups[ $index ]['items'] = $items;
	}

	/**
	 * Filters the situations shown in «Trámites por situaciones».
	 *
	 * @param array $groups Groups, each with a `title` and its `items`.
	 */
	return apply_filters( 'aranda_de_duero_situations', $groups );
}

/**
 * The two full lists, shown at the end of the page for whoever does not find their situation.
 *
 * @return array<int, array{title: string, text: string, url: string, new_tab: bool}>
 */
function aranda_de_duero_more_procedures() {
	return array(
		array(
			'title'   => __( 'Trámites del portal', 'aranda-de-duero' ),
			'text'    => __( 'Todos los trámites con página propia en este portal.', 'aranda-de-duero' ),
			'url'     => home_url( '/tramites/' ),
			'new_tab' => false,
		),
		array(
			'title'   => __( 'Catálogo de trámites de la sede', 'aranda-de-duero' ),
			'text'    => __( 'El catálogo completo, organizado por materias, en la sede electrónica.', 'aranda-de-duero' ),
			'url'     => aranda_de_duero_catalog_url(),
			'new_tab' => true,
		),
	);
}
