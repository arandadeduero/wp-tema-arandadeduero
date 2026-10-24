<?php
defined( 'ABSPATH' ) || exit;

/**
 * Service shortcuts: the «Accesos frecuentes» block of the home page and the «Pagos y recibos» page.
 *
 * Almost everything they point to already exists in the electronic office (sede.arandadeduero.es); the point is to make
 * it findable from the portal. Each entry is plain data: to add, remove or fix a link, edit the lists below.
 *
 * @package Aranda_de_Duero
 */

/**
 * Absolute URL of a page of the electronic office.
 *
 * @param string $path Path and query string, starting with «/sta/».
 * @return string
 */
function aranda_de_duero_sede_url( $path ) {
	$info = aranda_de_duero_contact_info();

	return rtrim( $info['sede_url'], '/' ) . $path;
}

/**
 * Where «Pagos y recibos» points: the page of the same name when it exists and is published, and the payment gateway
 * of the electronic office until then, so the shortcut never leads to a 404.
 *
 * @return string
 */
function aranda_de_duero_payments_url() {
	$page = get_page_by_path( 'pagos-y-recibos' );

	if ( $page instanceof WP_Post && 'publish' === $page->post_status ) {
		return (string) get_permalink( $page );
	}

	return aranda_de_duero_sede_url( '/sta/CarpetaPublic/doEvent?APP_CODE=STA&PAGE_CODE=FRONTAL_SPT' );
}

/**
 * «Accesos frecuentes» of the home page.
 *
 * @return array<int, array{title: string, text: string, url: string, new_tab: bool}>
 */
function aranda_de_duero_quick_access() {
	$info = aranda_de_duero_contact_info();

	$items = array(
		array(
			'title'    => __( 'Cita previa', 'aranda-de-duero' ),
			'text'     => __( 'Pide hora para que te atiendan en el Ayuntamiento.', 'aranda-de-duero' ),
			'url'      => $info['appointment_url'],
			'new_tab'  => false,
			'keywords' => 'cita turno hora atencion',
		),
		array(
			'title'    => __( 'Pagos y recibos', 'aranda-de-duero' ),
			'text'     => __( 'Paga tus recibos, multas y liquidaciones.', 'aranda-de-duero' ),
			'url'      => aranda_de_duero_payments_url(),
			'new_tab'  => false,
			'keywords' => 'pagar pago tasa tasas impuesto impuestos tributo tributos',
		),
		array(
			'title'    => __( 'Certificado de padrón', 'aranda-de-duero' ),
			'text'     => __( 'Obtén en el momento tus certificados telemáticos.', 'aranda-de-duero' ),
			'url'      => aranda_de_duero_sede_url( '/sta/CarpetaPrivate/Login?APP_CODE=STA&PAGE_CODE=DATOS_HABITANTE' ),
			'new_tab'  => true,
			'keywords' => 'padron empadronamiento empadronado volante',
		),
		array(
			'title'    => __( 'Trámites', 'aranda-de-duero' ),
			'text'     => __( 'Busca el trámite que necesitas en el catálogo.', 'aranda-de-duero' ),
			'url'      => aranda_de_duero_sede_url( '/sta/CarpetaPublic/doEvent?APP_CODE=STA&PAGE_CODE=CATALOGO' ),
			'new_tab'  => true,
			'keywords' => 'tramite tramites catalogo solicitud solicitudes instancia',
		),
		array(
			'title'    => __( 'Empleo público', 'aranda-de-duero' ),
			'text'     => __( 'Consulta la oferta pública de empleo.', 'aranda-de-duero' ),
			'url'      => aranda_de_duero_sede_url( '/sta/CarpetaPublic/doEvent?APP_CODE=STA&PAGE_CODE=PTS2_EMPLEO' ),
			'new_tab'  => true,
			'keywords' => 'empleo oposicion oposiciones trabajo bolsa convocatoria plaza',
		),
		array(
			'title'    => __( 'Quejas y sugerencias', 'aranda-de-duero' ),
			'text'     => __( 'Cuéntanos una queja o una sugerencia.', 'aranda-de-duero' ),
			'url'      => aranda_de_duero_sede_url( '/sta/CarpetaPublic/doEvent?APP_CODE=STA&PAGE_CODE=CATALOGO&DETALLE=6269004114448790205225' ),
			'new_tab'  => true,
			'keywords' => 'queja sugerencia reclamacion reclamar',
		),
		array(
			'title'    => __( 'Notificaciones electrónicas', 'aranda-de-duero' ),
			'text'     => __( 'Consulta el buzón de tus notificaciones.', 'aranda-de-duero' ),
			'url'      => aranda_de_duero_sede_url( '/sta/CarpetaPrivate/doEvent?APP_CODE=STA&PAGE_CODE=SINGLE_NOTIF' ),
			'new_tab'  => true,
			'keywords' => 'notificacion notificaciones buzon',
		),
		array(
			'title'    => __( 'Tablón de anuncios', 'aranda-de-duero' ),
			'text'     => __( 'Anuncios y edictos electrónicos del Ayuntamiento.', 'aranda-de-duero' ),
			'url'      => aranda_de_duero_sede_url( '/sta/CarpetaPublic/doEvent?APP_CODE=STA&PAGE_CODE=PTS2_TABLON' ),
			'new_tab'  => true,
			'keywords' => 'tablon edicto edictos anuncio anuncios',
		),
	);

	// Only once the page exists, so the card never leads to a 404; second place, next to the payments.
	$pharmacy_url = aranda_de_duero_page_url( 'farmacia-de-guardia' );
	if ( '' !== $pharmacy_url ) {
		array_splice(
			$items,
			2,
			0,
			array(
				array(
					'title'    => __( 'Farmacia de guardia', 'aranda-de-duero' ),
					'text'     => __( 'Consulta qué farmacia está de guardia hoy.', 'aranda-de-duero' ),
					'url'      => $pharmacy_url,
					'new_tab'  => false,
					'keywords' => 'farmacia farmacias guardia urgencia medicamentos',
				),
			)
		);
	}

	/**
	 * Filters the shortcuts shown in «Accesos frecuentes».
	 *
	 * @param array<int, array{title: string, text: string, url: string, new_tab: bool}> $items Shortcuts.
	 */
	return apply_filters( 'aranda_de_duero_quick_access', $items );
}

/**
 * Services of the «Pagos y recibos» page, in groups.
 *
 * The descriptions follow what the electronic office itself says about each service.
 *
 * @return array<int, array{title: string, items: array<int, array{title: string, text: string, url: string, new_tab: bool}>}>
 */
function aranda_de_duero_payment_services() {
	$groups = array(
		array(
			'title' => __( 'Paga ahora', 'aranda-de-duero' ),
			'items' => array(
				array(
					'title'    => __( 'Pasarela de pagos', 'aranda-de-duero' ),
					'text'     => __( 'Paga online desde la sede electrónica.', 'aranda-de-duero' ),
					'url'      => aranda_de_duero_sede_url( '/sta/CarpetaPublic/doEvent?APP_CODE=STA&PAGE_CODE=FRONTAL_SPT' ),
					'new_tab'  => true,
					'keywords' => 'pagar pago online tarjeta',
				),
			),
		),
		array(
			'title' => __( 'Tus recibos, multas y liquidaciones', 'aranda-de-duero' ),
			'items' => array(
				array(
					'title'    => __( 'Recibos', 'aranda-de-duero' ),
					'text'     => __( 'Consulta el estado de tus recibos, paga online los pendientes u obtén la carta de pago y el justificante.', 'aranda-de-duero' ),
					'url'      => aranda_de_duero_sede_url( '/sta/CarpetaPrivate/Login?APP_CODE=STA&PAGE_CODE=RECIBOS' ),
					'new_tab'  => true,
					'keywords' => 'recibo recibos pagar pendiente carta justificante domiciliar domiciliacion',
				),
				array(
					'title'    => __( 'Multas', 'aranda-de-duero' ),
					'text'     => __( 'Consulta el estado de tus multas y paga online las pendientes.', 'aranda-de-duero' ),
					'url'      => aranda_de_duero_sede_url( '/sta/CarpetaPrivate/Login?APP_CODE=STA&PAGE_CODE=MULTAS' ),
					'new_tab'  => true,
					'keywords' => 'multa multas pagar pago sancion trafico denuncia',
				),
				array(
					'title'    => __( 'Mis liquidaciones', 'aranda-de-duero' ),
					'text'     => __( 'Consulta tus liquidaciones, paga online y genera la carta de pago.', 'aranda-de-duero' ),
					'url'      => aranda_de_duero_sede_url( '/sta/CarpetaPrivate/Login?APP_CODE=STA&PAGE_CODE=LISTALIQ' ),
					'new_tab'  => true,
					'keywords' => 'liquidacion liquidaciones pagar pago autoliquidacion',
				),
				array(
					'title'    => __( 'Alta de liquidaciones', 'aranda-de-duero' ),
					'text'     => __( 'Da de alta una liquidación desde la sede electrónica.', 'aranda-de-duero' ),
					'url'      => aranda_de_duero_sede_url( '/sta/CarpetaPrivate/Login?APP_CODE=STA&PAGE_CODE=CREARLIQ' ),
					'new_tab'  => true,
					'keywords' => 'liquidacion liquidaciones autoliquidacion alta',
				),
				array(
					'title'    => __( 'Objetos tributarios', 'aranda-de-duero' ),
					'text'     => __( 'Consulta tus tributos y domicilia los recibos.', 'aranda-de-duero' ),
					'url'      => aranda_de_duero_sede_url( '/sta/CarpetaPrivate/Login?APP_CODE=STA&PAGE_CODE=OTRIB' ),
					'new_tab'  => true,
					'keywords' => 'tributo tributos domiciliar domiciliacion',
				),
			),
		),
		array(
			'title' => __( 'Calendario y ordenanzas', 'aranda-de-duero' ),
			'items' => array(
				array(
					'title'    => __( 'Calendario fiscal', 'aranda-de-duero' ),
					'text'     => __( 'Los periodos de pago de los tributos municipales.', 'aranda-de-duero' ),
					'url'      => aranda_de_duero_sede_url( '/sta/CarpetaPublic/Public?APP_CODE=STA&PAGE_CODE=CALFISCALPUB' ),
					'new_tab'  => true,
					'keywords' => 'calendario contribuyente plazos periodo pago voluntario',
				),
				array(
					'title'    => __( 'Mi calendario fiscal', 'aranda-de-duero' ),
					'text'     => __( 'Consulta cuándo te cobraremos tus recibos o los periodos de pago de tus tributos.', 'aranda-de-duero' ),
					'url'      => aranda_de_duero_sede_url( '/sta/CarpetaPrivate/Login?APP_CODE=STA&PAGE_CODE=CALFISCAL' ),
					'new_tab'  => true,
					'keywords' => 'calendario contribuyente plazos periodo cobro recibos',
				),
				array(
					'title'    => __( 'Ordenanzas fiscales', 'aranda-de-duero' ),
					'text'     => __( 'Listado completo de las ordenanzas fiscales vigentes.', 'aranda-de-duero' ),
					'url'      => home_url( '/listado-completo-ordenanzas-fiscales-vigentes/' ),
					'new_tab'  => false,
					'keywords' => 'ordenanza ordenanzas tasas impuestos fiscal',
				),
			),
		),
	);

	/**
	 * Filters the groups of services shown in «Pagos y recibos».
	 *
	 * @param array $groups Groups, each with a `title` and its `items`.
	 */
	return apply_filters( 'aranda_de_duero_payment_services', $groups );
}
