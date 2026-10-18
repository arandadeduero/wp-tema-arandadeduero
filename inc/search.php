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
		aranda_de_duero_directory_entry( __( 'Enlaces de interés', 'aranda-de-duero' ), __( 'Redes sociales y perfiles del Ayuntamiento.', 'aranda-de-duero' ), home_url( '/enlaces-de-interes/' ), 'enlaces redes sociales instagram facebook twitter tiktok youtube', false ),
	);

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
 * Everything the finder can offer, without repeated addresses: the shortcuts of the home page, the payment services, the
 * other pages of the office and of this site, and the «Especiales» of the home page.
 *
 * @return array<int, array{title: string, text: string, url: string, new_tab: bool, keywords?: string}>
 */
function aranda_de_duero_search_pool() {
	$pool = aranda_de_duero_quick_access();
	foreach ( aranda_de_duero_payment_services() as $group ) {
		$pool = array_merge( $pool, $group['items'] );
	}
	$pool = array_merge( $pool, aranda_de_duero_service_directory(), aranda_de_duero_search_especiales() );

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
	$index = array();
	foreach ( aranda_de_duero_search_pool() as $service ) {
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
			'items' => aranda_de_duero_search_index(),
			'i18n'  => array(
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
		if ( 0 === strpos( $word, $term ) ) {
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

	$pool = aranda_de_duero_search_pool();

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
