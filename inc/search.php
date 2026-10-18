<?php
defined( 'ABSPATH' ) || exit;

/**
 * «¿Qué necesitas?»: the service finder behind the search box of the home page and of the search results.
 *
 * The site search only looks at the WordPress content (news, pages…); the services people actually look for (certificado de
 * padrón, pagar un recibo, empleo público…) live in the electronic office. This matches the query against the curated service
 * lists of inc/services.php plus a few more office pages, so those appear first and the visitor lands on the service itself.
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
 * Other pages of the electronic office and the transparency portal that people look for, only searchable (not shown as shortcuts).
 *
 * @return array<int, array{title: string, text: string, url: string, new_tab: bool, keywords: string}>
 */
function aranda_de_duero_service_directory() {
	$info = aranda_de_duero_contact_info();

	return array(
		array(
			'title'    => __( 'Sesiones de pleno', 'aranda-de-duero' ),
			'text'     => __( 'Sesiones del pleno del Ayuntamiento.', 'aranda-de-duero' ),
			'url'      => aranda_de_duero_sede_url( '/sta/CarpetaPublic/doEvent?APP_CODE=STA&PAGE_CODE=PTS2_PLENO' ),
			'new_tab'  => true,
			'keywords' => 'pleno plenos sesion sesiones acta actas',
		),
		array(
			'title'    => __( 'Sesiones de junta de gobierno local', 'aranda-de-duero' ),
			'text'     => __( 'Sesiones de la junta de gobierno local.', 'aranda-de-duero' ),
			'url'      => aranda_de_duero_sede_url( '/sta/CarpetaPublic/doEvent?APP_CODE=STA&PAGE_CODE=PTS2_JUNTA' ),
			'new_tab'  => true,
			'keywords' => 'junta gobierno sesion sesiones acta actas',
		),
		array(
			'title'    => __( 'Perfil de contratante', 'aranda-de-duero' ),
			'text'     => __( 'Contratos y licitaciones del Ayuntamiento.', 'aranda-de-duero' ),
			'url'      => aranda_de_duero_sede_url( '/sta/CarpetaPublic/doEvent?APP_CODE=STA&PAGE_CODE=PTS2_PERFIL_CONTRATANTE' ),
			'new_tab'  => true,
			'keywords' => 'contrato contratos contratacion licitacion licitaciones proveedor',
		),
		array(
			'title'    => __( 'Validación de documentos', 'aranda-de-duero' ),
			'text'     => __( 'Valida y comprueba documentos emitidos por el Ayuntamiento.', 'aranda-de-duero' ),
			'url'      => aranda_de_duero_sede_url( '/sta/CarpetaPublic/doEvent?APP_CODE=STA&PAGE_CODE=VALDOCS' ),
			'new_tab'  => true,
			'keywords' => 'validar comprobar verificar documento documentos csv',
		),
		array(
			'title'    => __( 'Oficinas de asistencia en materia de registro', 'aranda-de-duero' ),
			'text'     => __( 'Dónde presentar documentos en persona.', 'aranda-de-duero' ),
			'url'      => aranda_de_duero_sede_url( '/sta/CarpetaPublic/doEvent?APP_CODE=STA&PAGE_CODE=PTS2_OFIASIREG' ),
			'new_tab'  => true,
			'keywords' => 'registro oficina oficinas presentar documentos entrada',
		),
		array(
			'title'    => __( 'Documentación para un expediente en trámite', 'aranda-de-duero' ),
			'text'     => __( 'Presenta documentación a expedientes en trámite.', 'aranda-de-duero' ),
			'url'      => aranda_de_duero_sede_url( '/sta/CarpetaPublic/doEvent?APP_CODE=STA&PAGE_CODE=CATALOGO&DETALLE=6269004131670985405225' ),
			'new_tab'  => true,
			'keywords' => 'expediente expedientes documentacion presentar aportar',
		),
		array(
			'title'    => __( 'Cartas de servicios', 'aranda-de-duero' ),
			'text'     => __( 'Compromisos de calidad de los servicios municipales.', 'aranda-de-duero' ),
			'url'      => aranda_de_duero_sede_url( '/sta/CarpetaPublic/doEvent?APP_CODE=STA&PAGE_CODE=PTS2_CARTASERVICIOS' ),
			'new_tab'  => true,
			'keywords' => 'carta cartas servicios calidad',
		),
		array(
			'title'    => __( 'Portal de transparencia', 'aranda-de-duero' ),
			'text'     => __( 'Información pública del Ayuntamiento.', 'aranda-de-duero' ),
			'url'      => $info['transparency_url'],
			'new_tab'  => true,
			'keywords' => 'transparencia retribuciones presupuesto organigrama subvenciones informacion publica',
		),
	);
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
 * Services that match a search: every word typed must appear (as part of a word) in the title, the description or the
 * keywords of the service, so «padrón» finds «Certificado de padrón» and «pagar multa» finds «Multas».
 *
 * @param string $query Search typed by the visitor.
 * @param int    $limit Maximum number of results.
 * @return array<int, array{title: string, text: string, url: string, new_tab: bool}>
 */
function aranda_de_duero_search_services( $query, $limit = 6 ) {
	$terms = preg_split( '/[^a-z0-9]+/', aranda_de_duero_search_normalize( (string) $query ), -1, PREG_SPLIT_NO_EMPTY );
	if ( false === $terms || array() === $terms ) {
		return array();
	}

	$pool = aranda_de_duero_quick_access();
	foreach ( aranda_de_duero_payment_services() as $group ) {
		$pool = array_merge( $pool, $group['items'] );
	}
	$pool = array_merge( $pool, aranda_de_duero_service_directory() );

	$found = array();
	$seen  = array();
	foreach ( $pool as $service ) {
		if ( isset( $seen[ $service['url'] ] ) ) {
			continue;
		}

		$haystack = aranda_de_duero_search_normalize( $service['title'] . ' ' . $service['text'] . ' ' . ( isset( $service['keywords'] ) ? $service['keywords'] : '' ) );
		foreach ( $terms as $term ) {
			if ( false === strpos( $haystack, $term ) ) {
				continue 2;
			}
		}

		$seen[ $service['url'] ] = true;
		$found[]                 = array(
			'title'   => $service['title'],
			'text'    => $service['text'],
			'url'     => $service['url'],
			'new_tab' => $service['new_tab'],
		);

		if ( count( $found ) >= $limit ) {
			break;
		}
	}

	return $found;
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
