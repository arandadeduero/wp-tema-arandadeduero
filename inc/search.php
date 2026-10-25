<?php
defined( 'ABSPATH' ) || exit;

/**
 * «¿Qué necesitas?»: the service finder behind the search box of the home page and of the search results.
 *
 * The site search only looks at the WordPress content (news, pages…); the services people actually look for (certificado de
 * padrón, pagar un recibo, empleo público…) live in the electronic office. This matches the query against the curated service
 * lists of inc/services.php, more pages of the office and of this site, and the «Especiales» of the home page, so those appear
 * first and the visitor lands on the service itself.
 *
 * @package Aranda_de_Duero
 */

/**
 * Catalogue of procedures of the electronic office.
 *
 * @return string
 */
function aranda_de_duero_catalog_url() {
	return aranda_de_duero_sede_url( '/sta/CarpetaPublic/doEvent?APP_CODE=STA&PAGE_CODE=CATALOGO' );
}

/**
 * One searchable entry.
 *
 * @param string $title    Title.
 * @param string $text     One-line description.
 * @param string $url      Address.
 * @param string $keywords Synonyms, without accents.
 * @param bool   $new_tab  Open in a new tab (pages outside this site).
 * @return array{title: string, text: string, url: string, new_tab: bool, keywords: string}
 */
function aranda_de_duero_directory_entry( $title, $text, $url, $keywords, $new_tab = true ) {
	return array(
		'title'    => $title,
		'text'     => $text,
		'url'      => $url,
		'new_tab'  => $new_tab,
		'keywords' => $keywords,
	);
}

/**
 * Other pages of the electronic office, the transparency portal and the sections of this site that people look for. They are only
 * searchable: the shortcuts of the home page live in inc/services.php.
 *
 * The titles are the ones the electronic office itself uses; the descriptions say no more than the title does.
 *
 * @return array<int, array{title: string, text: string, url: string, new_tab: bool, keywords: string}>
 */
function aranda_de_duero_service_directory() {
	$info = aranda_de_duero_contact_info();
	$e    = static function ( $page, $code ) {
		return aranda_de_duero_sede_url( '/sta/' . $page . '?APP_CODE=STA&PAGE_CODE=' . $code );
	};
	$pub  = 'CarpetaPublic/doEvent';
	$priv = 'CarpetaPrivate/Login';

	$entries = array(
		// Your own data in the electronic office (they ask you to identify yourself there).
		aranda_de_duero_directory_entry( __( 'Expedientes', 'aranda-de-duero' ), __( 'Consulta tus expedientes.', 'aranda-de-duero' ), $e( $priv, 'EXPEDIENTES_FULL' ), 'expediente expedientes tramitacion seguimiento estado' ),
		aranda_de_duero_directory_entry( __( 'Datos personales', 'aranda-de-duero' ), __( 'Consulta y modifica tus datos personales.', 'aranda-de-duero' ), $e( $priv, 'DATOS_PERSONALES' ), 'datos personales perfil direccion correo modificar' ),
		aranda_de_duero_directory_entry( __( 'Anotaciones en el registro', 'aranda-de-duero' ), __( 'Consulta tus anotaciones de registro.', 'aranda-de-duero' ), $e( $priv, 'ANOTACIONES' ), 'registro anotaciones entrada salida presentado' ),
		aranda_de_duero_directory_entry( __( 'Documentos', 'aranda-de-duero' ), __( 'Consulta los documentos que hayas generado.', 'aranda-de-duero' ), $e( $priv, 'BUZONDOC' ), 'documento documentos buzon' ),
		aranda_de_duero_directory_entry( __( 'Descarga de documentos', 'aranda-de-duero' ), __( 'Descarga documentos desde la sede electrónica.', 'aranda-de-duero' ), $e( $priv, 'SOL_DOC' ), 'descargar descarga documentos' ),
		aranda_de_duero_directory_entry( __( 'Documentos habitualmente solicitados', 'aranda-de-duero' ), __( 'Presenta los documentos que se piden con más frecuencia.', 'aranda-de-duero' ), $e( $priv, 'SINGLE_REUSE' ), 'documentos presentar solicitados habituales dni' ),
		aranda_de_duero_directory_entry( __( 'Suscripción o baja de notificaciones electrónicas', 'aranda-de-duero' ), __( 'Suscríbete o date de baja de las notificaciones electrónicas.', 'aranda-de-duero' ), $e( 'CarpetaPrivate/doEvent', 'SINGLE_SUSCR' ), 'notificaciones notificacion suscripcion suscribirse baja alta' ),
		aranda_de_duero_directory_entry( __( 'Portafirmas', 'aranda-de-duero' ), __( 'Firma de documentos.', 'aranda-de-duero' ), $e( 'CarpetaPrivate/Certificate', 'PORTAFIRMAS' ), 'firma firmar documentos certificado' ),

		// Public pages of the electronic office.
		aranda_de_duero_directory_entry( __( 'Trámites destacados', 'aranda-de-duero' ), __( 'Los trámites destacados de la sede electrónica.', 'aranda-de-duero' ), $e( $pub, 'PTS2_TRAMDESTACADOS' ), 'tramites destacados habituales frecuentes' ),
		aranda_de_duero_directory_entry( __( 'Sesiones de pleno', 'aranda-de-duero' ), __( 'Sesiones del pleno del Ayuntamiento.', 'aranda-de-duero' ), $e( $pub, 'PTS2_PLENO' ), 'pleno plenos sesion sesiones acta actas' ),
		aranda_de_duero_directory_entry( __( 'Sesiones de junta de gobierno local', 'aranda-de-duero' ), __( 'Sesiones de la junta de gobierno local.', 'aranda-de-duero' ), $e( $pub, 'PTS2_JUNTA' ), 'junta gobierno sesion sesiones acta actas' ),
		aranda_de_duero_directory_entry( __( 'Perfil de contratante', 'aranda-de-duero' ), __( 'Contratos y licitaciones del Ayuntamiento.', 'aranda-de-duero' ), $e( $pub, 'PTS2_PERFIL_CONTRATANTE' ), 'contrato contratos contratacion licitacion licitaciones proveedor' ),
		aranda_de_duero_directory_entry( __( 'Factura electrónica', 'aranda-de-duero' ), __( 'Presentación de facturas electrónicas al Ayuntamiento.', 'aranda-de-duero' ), 'https://face.gob.es', 'factura facturas facturacion proveedor proveedores face' ),
		aranda_de_duero_directory_entry( __( 'Validación de documentos', 'aranda-de-duero' ), __( 'Valida y comprueba documentos emitidos por el Ayuntamiento.', 'aranda-de-duero' ), $e( $pub, 'VALDOCS' ), 'validar comprobar verificar documento documentos csv' ),
		aranda_de_duero_directory_entry( __( 'Oficinas de asistencia en materia de registro', 'aranda-de-duero' ), __( 'Dónde presentar documentos en persona.', 'aranda-de-duero' ), $e( $pub, 'PTS2_OFIASIREG' ), 'registro oficina oficinas presentar documentos entrada' ),
		aranda_de_duero_directory_entry( __( 'Documentación para un expediente en trámite', 'aranda-de-duero' ), __( 'Presenta documentación a expedientes en trámite.', 'aranda-de-duero' ), $e( $pub, 'CATALOGO&DETALLE=6269004131670985405225' ), 'expediente expedientes documentacion presentar aportar' ),
		aranda_de_duero_directory_entry( __( 'Calendario y cómputo de plazos', 'aranda-de-duero' ), __( 'Días hábiles y cómputo de plazos en la sede electrónica.', 'aranda-de-duero' ), $e( $pub, 'PTS2_CALENHABTXT' ), 'plazo plazos dias habiles inhabiles festivos computo' ),
		aranda_de_duero_directory_entry( __( 'Fecha y hora oficial', 'aranda-de-duero' ), __( 'Fecha y hora oficial de la sede electrónica.', 'aranda-de-duero' ), $e( $pub, 'PTS2_FECHAHORA' ), 'fecha hora oficial reloj' ),
		aranda_de_duero_directory_entry( __( 'Certificados electrónicos', 'aranda-de-duero' ), __( 'Información sobre certificados electrónicos.', 'aranda-de-duero' ), $e( $pub, 'PTS2_CERTIFICADOS' ), 'certificado certificados electronico electronicos firma identificacion' ),
		aranda_de_duero_directory_entry( __( 'Sistemas de identificación', 'aranda-de-duero' ), __( 'Cómo identificarte en la sede electrónica.', 'aranda-de-duero' ), $e( $pub, 'PTS2_FIRMASELEC' ), 'identificacion identificarse acceso clave certificado' ),
		aranda_de_duero_directory_entry( __( 'Sistemas de firma', 'aranda-de-duero' ), __( 'Sistemas de firma admitidos en la sede electrónica.', 'aranda-de-duero' ), $e( $pub, 'PTS2_FIRMA' ), 'firma firmar sistemas' ),
		aranda_de_duero_directory_entry( __( 'Relación de servicios electrónicos', 'aranda-de-duero' ), __( 'Los servicios electrónicos del Ayuntamiento.', 'aranda-de-duero' ), $e( $pub, 'PTS2_CARTA' ), 'servicios electronicos relacion lista' ),
		aranda_de_duero_directory_entry( __( 'Cartas de servicios', 'aranda-de-duero' ), __( 'Compromisos de calidad de los servicios municipales.', 'aranda-de-duero' ), $e( $pub, 'PTS2_CARTASERVICIOS' ), 'carta cartas servicios calidad' ),
		aranda_de_duero_directory_entry( __( 'Normativa de administración electrónica', 'aranda-de-duero' ), __( 'Normativa y ordenanza de administración electrónica.', 'aranda-de-duero' ), $e( $pub, 'PTS2_NORMATIVA' ), 'normativa ordenanza administracion electronica' ),
		aranda_de_duero_directory_entry( __( 'Interrupciones del servicio', 'aranda-de-duero' ), __( 'Avisos de interrupciones del servicio de la sede.', 'aranda-de-duero' ), $e( $pub, 'PTS2_INTERRSERV' ), 'interrupcion interrupciones caida mantenimiento no funciona' ),
		aranda_de_duero_directory_entry( __( 'Preguntas frecuentes de la sede', 'aranda-de-duero' ), __( 'Preguntas frecuentes sobre la sede electrónica.', 'aranda-de-duero' ), $e( $pub, 'PTS2_FAQS' ), 'preguntas frecuentes faq dudas ayuda' ),
		aranda_de_duero_directory_entry( __( 'Ayuda de la sede', 'aranda-de-duero' ), __( 'Ayuda para usar la sede electrónica.', 'aranda-de-duero' ), $e( $pub, 'PTS2_AYUDA' ), 'ayuda soporte problema dudas' ),

		// Other portals and sections of this site.
		aranda_de_duero_directory_entry( __( 'Portal de transparencia', 'aranda-de-duero' ), __( 'Información pública del Ayuntamiento.', 'aranda-de-duero' ), $info['transparency_url'], 'transparencia retribuciones presupuesto organigrama subvenciones informacion publica' ),
		aranda_de_duero_directory_entry( __( 'Turismo', 'aranda-de-duero' ), __( 'Web de turismo de Aranda de Duero.', 'aranda-de-duero' ), 'https://turismo.arandadeduero.es/', 'turismo visitar visita monumentos bodegas ruta rutas' ),
		aranda_de_duero_directory_entry( __( 'Participación ciudadana', 'aranda-de-duero' ), __( 'Web de las asociaciones y la participación ciudadana.', 'aranda-de-duero' ), 'https://asociaciones.arandadeduero.es/', 'participacion ciudadana asociaciones asociacion', true ),
		aranda_de_duero_directory_entry( __( 'Agenda de eventos', 'aranda-de-duero' ), __( 'Los actos y actividades que se celebran en Aranda de Duero.', 'aranda-de-duero' ), home_url( '/agenda/' ), 'agenda eventos actos actividades fiestas conciertos cultura', false ),
		aranda_de_duero_directory_entry( __( 'Bandos', 'aranda-de-duero' ), __( 'Bandos municipales.', 'aranda-de-duero' ), home_url( '/bandos/' ), 'bando bandos aviso avisos', false ),
		aranda_de_duero_directory_entry( __( 'Notas de prensa', 'aranda-de-duero' ), __( 'Comunicados oficiales del Ayuntamiento.', 'aranda-de-duero' ), home_url( '/notas-de-prensa/' ), 'nota notas prensa comunicado comunicados', false ),
		aranda_de_duero_directory_entry( __( 'Ayudas y subvenciones', 'aranda-de-duero' ), __( 'Ayudas para autónomos, emprendedores, comercios y empresas.', 'aranda-de-duero' ), home_url( '/subvenciones/' ), 'ayuda ayudas subvencion subvenciones empresa empresas autonomo autonomos comercio emprendedor abre aranda', false ),
		aranda_de_duero_directory_entry( __( 'Teléfonos de interés', 'aranda-de-duero' ), __( 'Teléfonos de interés de Aranda de Duero.', 'aranda-de-duero' ), home_url( '/telefonos-de-interes/' ), 'telefono telefonos llamar emergencias contacto', false ),
		aranda_de_duero_directory_entry( __( 'Preguntas frecuentes', 'aranda-de-duero' ), __( 'Preguntas frecuentes sobre el Ayuntamiento.', 'aranda-de-duero' ), home_url( '/preguntas-mas-frecuentes/' ), 'preguntas frecuentes faq dudas', false ),
		aranda_de_duero_directory_entry( __( 'Fotografías del Ayuntamiento', 'aranda-de-duero' ), __( 'Álbumes de fotos de los actos y actividades del Ayuntamiento, en Flickr.', 'aranda-de-duero' ), 'https://www.flickr.com/photos/ayuntamientoarandadeduero/', 'fotos fotografias foto fotografia imagenes album albumes galeria flickr' ),
		aranda_de_duero_directory_entry( __( 'Enlaces de interés', 'aranda-de-duero' ), __( 'Redes sociales y perfiles del Ayuntamiento.', 'aranda-de-duero' ), home_url( '/enlaces-de-interes/' ), 'enlaces redes sociales instagram facebook twitter tiktok youtube flickr', false ),
	);

	// Bulky items (muebles, colchones…): the phone is in the text, and the link goes to its place in the waste page.
	$waste_url = aranda_de_duero_waste_page_url();
	if ( '' !== $waste_url ) {
		$waste     = aranda_de_duero_waste_info();
		$entries[] = aranda_de_duero_directory_entry(
			__( 'Recogida de enseres', 'aranda-de-duero' ),
			/* translators: %s: phone number. */
			sprintf( __( 'Muebles, colchones y electrodomésticos: llama al %s, previa solicitud.', 'aranda-de-duero' ), $waste['bulky_phone'] ),
			$waste_url . '#enseres',
			'enseres enser mueble muebles colchon colchones sofa sofas somier armario armarios trasto trastos electrodomestico electrodomesticos nevera frigorifico lavadora voluminoso voluminosos recoger recogida retirar retirada tirar telefono valoriza viejo viejos vieja viejas',
			false
		);
	}

	// OMIC, objetos perdidos and the bus come from aranda_de_duero_everyday_services(), already in the pool.
	$entries[] = aranda_de_duero_directory_entry( __( 'Trámites del portal', 'aranda-de-duero' ), __( 'Todos los trámites con página propia en este portal.', 'aranda-de-duero' ), home_url( '/tramites/' ), 'tramites tramite solicitudes modelos documentacion', false );

	$situations = aranda_de_duero_situations_url();
	if ( '' !== $situations ) {
		$entries[] = aranda_de_duero_directory_entry( __( 'Trámites por situaciones', 'aranda-de-duero' ), __( 'Los trámites agrupados según lo que necesitas hacer.', 'aranda-de-duero' ), $situations, 'tramites situaciones empadronarme casarme negocio obras impuestos empleo', false );
	}

	$audiences = aranda_de_duero_audiences_url();
	if ( '' !== $audiences ) {
		$entries[] = aranda_de_duero_directory_entry( __( 'Para ti', 'aranda-de-duero' ), __( 'Los servicios agrupados para familias, mayores, jóvenes, empresas, nuevos vecinos y asociaciones.', 'aranda-de-duero' ), $audiences, 'para ti familias familia mayores jovenes juventud empresas comercios autonomos nuevos vecinos asociaciones colectivos', false );
	}

	return array_merge( $entries, array_values( aranda_de_duero_portal_procedures() ) );
}

/**
 * The «Especiales» of the home page as searchable entries: whatever the Ayuntamiento highlights there (PGOU, catastro, calendario
 * fiscal, PMUS…) is found by its title without being copied anywhere.
 *
 * @return array<int, array{title: string, text: string, url: string, new_tab: bool, keywords: string}>
 */
function aranda_de_duero_search_especiales() {
	if ( ! function_exists( 'aranda_de_duero_home_especiales' ) ) {
		return array();
	}

	$site_host = wp_parse_url( home_url(), PHP_URL_HOST );
	$entries   = array();

	foreach ( aranda_de_duero_home_especiales() as $item ) {
		$host      = wp_parse_url( $item['url'], PHP_URL_HOST );
		$entries[] = aranda_de_duero_directory_entry(
			$item['title'],
			__( 'Destacado en la portada.', 'aranda-de-duero' ),
			$item['url'],
			'',
			is_string( $host ) && $host !== $site_host
		);
	}

	return $entries;
}

/**
 * Every concejalía with content and every published servicio-municipal, as searchable entries: «cultura» finds the
 * concejalía's own page, «biblioteca» finds its service.
 *
 * @return array<int, array{title: string, text: string, url: string, new_tab: bool, keywords?: string}>
 */
function aranda_de_duero_search_concejalias_and_services() {
	$entries = array();

	$terms = get_terms(
		array(
			'taxonomy'   => 'concejalia',
			'hide_empty' => true,
		)
	);
	if ( ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			$link = get_term_link( $term );
			if ( is_wp_error( $link ) ) {
				continue;
			}
			$entries[] = aranda_de_duero_directory_entry(
				/* translators: %s: name of the concejalía. */
				sprintf( __( 'Concejalía de %s', 'aranda-de-duero' ), $term->name ),
				__( 'Noticias, trámites y servicios de esta concejalía.', 'aranda-de-duero' ),
				$link,
				'',
				false
			);
		}
	}

	$services = get_posts(
		array(
			'post_type'      => 'servicio-municipal',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		)
	);
	foreach ( (array) $services as $service ) {
		$entries[] = aranda_de_duero_directory_entry(
			get_the_title( $service ),
			wp_strip_all_tags( (string) get_the_excerpt( $service ) ),
			(string) get_permalink( $service ),
			'',
			false
		);
	}

	return $entries;
}

/**
 * Lower case, no accents.
 *
 * @param string $text Text.
 * @return string
 */
function aranda_de_duero_search_normalize( $text ) {
	return strtolower( remove_accents( $text ) );
}

/**
 * Words of a text: lower case, no accents, letters and digits only.
 *
 * @param string $text Text.
 * @return string[]
 */
function aranda_de_duero_search_words( $text ) {
	$words = preg_split( '/[^a-z0-9]+/', aranda_de_duero_search_normalize( (string) $text ), -1, PREG_SPLIT_NO_EMPTY );

	return false === $words ? array() : $words;
}

/**
 * Words people use that the catalogue does not: a procedure whose title has a word starting with one of the stems of a rule is
 * also found by the keywords of that rule. Validated by the Ayuntamiento (2026-10-24); add a rule only with their ok.
 *
 * @return array<int, array{stems: string[], keywords: string}>
 */
function aranda_de_duero_sede_catalog_synonyms() {
	return array(
		array(
			'stems'    => array( 'residuo' ),
			'keywords' => 'basura basuras',
		),
		array(
			'stems'    => array( 'funerari', 'nicho', 'panteon', 'columbario', 'exhumacion', 'inhumacion', 'cementerio' ),
			'keywords' => 'sepultura sepulturas',
		),
		array(
			'stems'    => array( 'vado' ),
			'keywords' => 'cochera cocheras garaje garajes',
		),
		array(
			'stems'    => array( 'peligroso' ),
			'keywords' => 'perro perros',
		),
		array(
			'stems'    => array( 'ibi' ),
			'keywords' => 'contribucion',
		),
		array(
			'stems'    => array( 'infantil' ),
			'keywords' => 'guarderia guarderias',
		),
		array(
			'stems'    => array( 'matrimonio' ),
			'keywords' => 'boda bodas casarse casar',
		),
		array(
			'stems'    => array( 'pirotecnicos' ),
			'keywords' => 'fuegos artificiales petardos',
		),
		array(
			'stems'    => array( 'casco' ),
			'keywords' => 'peatonal peatonales bolardo bolardos coche coches',
		),
		array(
			'stems'    => array( 'ivtm' ),
			'keywords' => 'impuesto circulacion coche coches',
		),
		array(
			'stems'    => array( 'aplazamiento' ),
			'keywords' => 'aplazar plazos',
		),
		array(
			'stems'    => array( 'selectivas' ),
			'keywords' => 'oposiciones oposicion',
		),
		array(
			'stems'    => array( 'obras' ),
			'keywords' => 'reforma reformas',
		),
		array(
			'stems'    => array( 'subvencion' ),
			'keywords' => 'ayuda ayudas',
		),
		array(
			'stems'    => array( 'solar' ),
			'keywords' => 'placas paneles',
		),
		array(
			'stems'    => array( 'animales' ),
			'keywords' => 'mascota mascotas',
		),
		array(
			'stems'    => array( 'ora' ),
			'keywords' => 'aparcar aparcamiento estacionamiento zona azul',
		),
		array(
			'stems'    => array( 'recurso' ),
			'keywords' => 'recurrir recurrido',
		),
	);
}

/**
 * The synonyms that apply to a procedure of the catalogue.
 *
 * @param string $title Title of the procedure.
 * @return string Keywords, without accents ('' when no rule applies).
 */
function aranda_de_duero_sede_catalog_keywords( $title ) {
	$words    = aranda_de_duero_search_words( $title );
	$keywords = array();
	foreach ( aranda_de_duero_sede_catalog_synonyms() as $rule ) {
		foreach ( $rule['stems'] as $stem ) {
			if ( aranda_de_duero_search_has_word( $words, $stem ) ) {
				$keywords[] = $rule['keywords'];
				break;
			}
		}
	}

	return implode( ' ', $keywords );
}

/**
 * The procedures of the catalogue of the electronic office as searchable entries, each pointing to its own page in the catalogue.
 *
 * The list is data/sede/tramites.json (node scripts/update-sede-catalog.js refreshes it from the office). It is only searched on the
 * results page: its ~400 entries would weigh down the suggestions that travel with every page.
 *
 * @return array<int, array{title: string, text: string, url: string, new_tab: bool, keywords: string}>
 */
function aranda_de_duero_sede_catalog_entries() {
	$file = get_template_directory() . '/data/sede/tramites.json';
	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- a file of the theme, not a URL.
	$raw  = is_readable( $file ) ? file_get_contents( $file ) : false;
	$data = false === $raw ? null : json_decode( $raw, true );
	if ( ! is_array( $data ) || empty( $data['procedures'] ) || ! is_array( $data['procedures'] ) ) {
		return array();
	}

	$entries = array();
	foreach ( $data['procedures'] as $procedure ) {
		if ( empty( $procedure['id'] ) || empty( $procedure['name'] ) ) {
			continue;
		}
		$entries[] = aranda_de_duero_directory_entry(
			(string) $procedure['name'],
			empty( $procedure['area'] ) ? __( 'Trámite de la sede electrónica.', 'aranda-de-duero' ) : (string) $procedure['area'],
			aranda_de_duero_sede_url( '/sta/CarpetaPublic/doEvent?APP_CODE=STA&PAGE_CODE=CATALOGO&DETALLE=' . rawurlencode( (string) $procedure['id'] ) ),
			aranda_de_duero_sede_catalog_keywords( (string) $procedure['name'] )
		);
	}

	return $entries;
}

/**
 * Everything the finder can offer, without repeated addresses: the shortcuts of the home page, the payment services, the
 * other pages of the office and of this site, and the «Especiales» of the home page; with the catalogue of procedures when asked.
 *
 * @param bool $with_catalog Add the procedures of the catalogue of the office (after the rest, so the curated services come first).
 * @return array<int, array{title: string, text: string, url: string, new_tab: bool, keywords?: string}>
 */
function aranda_de_duero_search_pool( $with_catalog = false ) {
	$pool = array_merge( aranda_de_duero_quick_access(), aranda_de_duero_everyday_services() );
	foreach ( aranda_de_duero_payment_services() as $group ) {
		$pool = array_merge( $pool, $group['items'] );
	}
	$pool = array_merge( $pool, aranda_de_duero_service_directory(), aranda_de_duero_search_especiales(), aranda_de_duero_search_concejalias_and_services() );
	if ( $with_catalog ) {
		$pool = array_merge( $pool, aranda_de_duero_sede_catalog_entries() );
	}

	$unique = array();
	foreach ( $pool as $service ) {
		if ( ! isset( $unique[ $service['url'] ] ) ) {
			$unique[ $service['url'] ] = $service;
		}
	}

	return array_values( $unique );
}

/**
 * The same pool prepared for the browser (js/search-suggest.js), which suggests services while the visitor types without
 * asking the server anything: `w` are the words it matches against (title, description and synonyms) and `t` those of the title.
 *
 * @return array<int, array{n: string, d: string, u: string, b: int, w: string, t: string}>
 */
function aranda_de_duero_search_index() {
	return aranda_de_duero_search_index_for( aranda_de_duero_search_pool() );
}

/**
 * Services prepared for the browser (see aranda_de_duero_search_index()).
 *
 * @param array<int, array{title: string, text: string, url: string, new_tab: bool, keywords?: string}> $services Services.
 * @return array<int, array{n: string, d: string, u: string, b: int, w: string, t: string}>
 */
function aranda_de_duero_search_index_for( array $services ) {
	$index = array();
	foreach ( $services as $service ) {
		$text    = $service['title'] . ' ' . $service['text'] . ' ' . ( isset( $service['keywords'] ) ? $service['keywords'] : '' );
		$index[] = array(
			'n' => $service['title'],
			'd' => $service['text'],
			'u' => $service['url'],
			'b' => $service['new_tab'] ? 1 : 0,
			'w' => implode( ' ', array_unique( aranda_de_duero_search_words( $text ) ) ),
			't' => implode( ' ', aranda_de_duero_search_words( $service['title'] ) ),
		);
	}

	return $index;
}

/**
 * The procedures of the catalogue of the office prepared for the browser. The script asks for them the first time somebody types in
 * the box (about 400 entries: too many to travel with every page).
 *
 * @return array<int, array{n: string, d: string, u: string, b: int, w: string, t: string}>
 */
function aranda_de_duero_search_catalog_index() {
	return aranda_de_duero_search_index_for( aranda_de_duero_sede_catalog_entries() );
}

/**
 * REST route with the catalogue index; public data, and the browser keeps it for an hour.
 */
function aranda_de_duero_search_register_catalog_route() {
	register_rest_route(
		'aranda-de-duero/v1',
		'/tramites',
		array(
			'methods'             => 'GET',
			'callback'            => static function () {
				$response = new WP_REST_Response( aranda_de_duero_search_catalog_index() );
				$response->header( 'Cache-Control', 'public, max-age=3600' );

				return $response;
			},
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'aranda_de_duero_search_register_catalog_route' );

/**
 * Load the script that suggests services while typing, with its data (once per page).
 */
function aranda_de_duero_enqueue_search_suggest() {
	if ( wp_script_is( 'aranda-de-duero-search-suggest', 'enqueued' ) ) {
		return;
	}

	wp_enqueue_script(
		'aranda-de-duero-search-suggest',
		get_template_directory_uri() . '/js/search-suggest.js',
		array(),
		aranda_de_duero_asset_version( '/js/search-suggest.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
	wp_localize_script(
		'aranda-de-duero-search-suggest',
		'arandaSearch',
		array(
			'items'      => aranda_de_duero_search_index(),
			'catalogUrl' => rest_url( 'aranda-de-duero/v1/tramites' ),
			'i18n'       => array(
				/* translators: %s: what the visitor typed. */
				'all'     => __( 'Buscar «%s» en noticias y páginas', 'aranda-de-duero' ),
				'one'     => __( '1 sugerencia disponible', 'aranda-de-duero' ),
				/* translators: %d: number of suggestions. */
				'many'    => __( '%d sugerencias disponibles', 'aranda-de-duero' ),
				'newTab'  => aranda_de_duero_new_tab_text(),
				'listing' => __( 'Servicios y trámites sugeridos', 'aranda-de-duero' ),
			),
		)
	);
}

/**
 * Whether some word starts with the term («cita» matches «cita previa» but not «solicitados»).
 *
 * @param string[] $words Normalized words.
 * @param string   $term  Normalized term.
 * @return bool
 */
function aranda_de_duero_search_has_word( array $words, $term ) {
	foreach ( $words as $word ) {
		if ( str_starts_with( $word, $term ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Services that match a search: every word typed must start a word in the title, the description or the
 * keywords of the service, so «padrón» finds «Certificado de padrón» and «pagar multa» finds «Multas».
 *
 * @param string $query Search typed by the visitor.
 * @param int    $limit Maximum number of results.
 * @return array<int, array{title: string, text: string, url: string, new_tab: bool}>
 */
function aranda_de_duero_search_services( $query, $limit = 6 ) {
	$terms = aranda_de_duero_search_words( $query );
	if ( array() === $terms ) {
		return array();
	}

	$pool = aranda_de_duero_search_pool( true );

	$found = array();
	foreach ( $pool as $index => $service ) {
		$words = aranda_de_duero_search_words( $service['title'] . ' ' . $service['text'] . ' ' . ( isset( $service['keywords'] ) ? $service['keywords'] : '' ) );
		foreach ( $terms as $term ) {
			if ( ! aranda_de_duero_search_has_word( $words, $term ) ) {
				continue 2;
			}
		}

		// Matches in the title come first; the rest keep the order of the lists.
		$title_words = aranda_de_duero_search_words( $service['title'] );
		$in_title    = 0;
		foreach ( $terms as $term ) {
			$in_title += aranda_de_duero_search_has_word( $title_words, $term ) ? 0 : 1;
		}

		$found[] = array(
			'rank'    => array( $in_title, $index ),
			'service' => array(
				'title'   => $service['title'],
				'text'    => $service['text'],
				'url'     => $service['url'],
				'new_tab' => $service['new_tab'],
			),
		);
	}

	usort(
		$found,
		static function ( $a, $b ) {
			return $a['rank'] <=> $b['rank'];
		}
	);

	return array_slice( array_column( $found, 'service' ), 0, $limit );
}

/**
 * Words suggested under the search box of the home page: «Prueba con…».
 *
 * @return string[] Search terms.
 */
function aranda_de_duero_search_suggestions() {
	/**
	 * Filters the suggested searches shown under the search box of the home page.
	 *
	 * @param string[] $terms Search terms.
	 */
	return apply_filters( 'aranda_de_duero_search_suggestions', array( 'padrón', 'recibos', 'multas', 'empleo', 'cita previa' ) );
}
