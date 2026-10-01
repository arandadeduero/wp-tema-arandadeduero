<?php

/**
 * Reusable block patterns for content editors.
 *
 * These give editors, from the block editor of any regular page, the same
 * visual components that would otherwise require asking a developer to
 * hardcode a new page-*.php template (info box, icon-link card).
 *
 * Runs after aranda_de_duero_register_icons() (inc/icons.php), which is
 * hooked at the default init priority (10), so the icon collection used
 * below is already registered.
 *
 * @package Aranda_de_Duero
 */

if ( ! function_exists( 'aranda_de_duero_register_block_pattern_category' ) ) :
	function aranda_de_duero_register_block_pattern_category() {
		if ( ! function_exists( 'register_block_pattern_category' ) ) {
			return;
		}

		register_block_pattern_category(
			'aranda-de-duero',
			array(
				'label' => __( 'Aranda de Duero', 'aranda-de-duero' ),
			)
		);
	}
endif;
add_action( 'init', 'aranda_de_duero_register_block_pattern_category' );

if ( ! function_exists( 'aranda_de_duero_register_block_patterns' ) ) :
	function aranda_de_duero_register_block_patterns() {
		if ( ! function_exists( 'register_block_pattern' ) ) {
			return;
		}

		$icon = aranda_de_duero_icon( 'aranda-de-duero/circle-question', array( 'size' => 32 ) );

		register_block_pattern(
			'aranda-de-duero/aviso-informativo',
			array(
				'title'       => __( 'Aviso informativo con icono', 'aranda-de-duero' ),
				'description' => __( 'Cuadro de aviso con icono, título y texto explicativo, como el usado en las páginas de Bandos, Notas de Prensa o Subvenciones.', 'aranda-de-duero' ),
				'categories'  => array( 'aranda-de-duero' ),
				'content'     => '<!-- wp:html -->
<div class="alert alert-info mb-4" role="alert">
    <div class="d-flex">
        <div class="mr-3" style="color: #0c5460;">' . $icon . '</div>
        <div>
            <h5 class="alert-heading">' . esc_html__( 'Título del aviso', 'aranda-de-duero' ) . '</h5>
            <p class="mb-0">' . esc_html__( 'Texto explicativo del aviso. Sustituye este párrafo por el contenido real.', 'aranda-de-duero' ) . '</p>
        </div>
    </div>
</div>
<!-- /wp:html -->',
			)
		);

		register_block_pattern(
			'aranda-de-duero/tarjeta-icono-enlace',
			array(
				'title'       => __( 'Tarjeta con icono y enlace', 'aranda-de-duero' ),
				'description' => __( 'Tarjeta destacada con icono, título-enlace y texto, como la usada para "Nota de Prensa" o "Informe Jurídico" en las páginas de expedientes.', 'aranda-de-duero' ),
				'categories'  => array( 'aranda-de-duero' ),
				'content'     => '<!-- wp:html -->
<div class="card shadow-sm border-primary position-relative">
    <div class="card-body text-center p-4">
        <h3 class="h5 card-title mb-2">
            <a href="#" class="stretched-link text-primary text-decoration-none">📄 ' . esc_html__( 'Título del enlace', 'aranda-de-duero' ) . '</a>
        </h3>
        <p class="card-text text-muted mb-0">' . esc_html__( 'Descripción breve del documento o enlace.', 'aranda-de-duero' ) . '</p>
    </div>
</div>
<!-- /wp:html -->',
			)
		);
	}
endif;
add_action( 'init', 'aranda_de_duero_register_block_patterns', 20 );
