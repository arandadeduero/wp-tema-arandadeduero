<?php
defined( 'ABSPATH' ) || exit;

/**
 * Content types and taxonomies of the site. Most were registered by the Custom Post Type UI plugin.
 *
 * The arguments of `seccion-principal`, `actividad`, `especial`, `tramite` and the six original taxonomies are the ones that
 * plugin exported («Obtener el código», 2026-10-20), so nothing changes for their content, addresses or editors. `servicio-municipal`
 * (2026-10-25) is new, added by the theme itself: the «Biblioteca», «Escuela de música»… services of each concejalía, as their own
 * pages (`concejalia` is the taxonomy that links a service to its concejalía; the old `servicio` taxonomy is unrelated classification
 * on news posts, kept as it was to avoid a slug clash — see docs/servicios.md).
 *
 * A type or taxonomy that something else has already registered (the plugin, while it is still active) is left as it is, so the
 * plugin can be deactivated at any time without registering anything twice.
 *
 * @package Aranda_de_Duero
 */

/**
 * Arguments shared by the four post types.
 *
 * @param string $slug      Slug and name of the type.
 * @param string $singular  Name in singular.
 * @param string $plural    Name in plural.
 * @param array  $overrides Arguments that differ from the common ones.
 * @return array<string,mixed>
 */
function aranda_de_duero_post_type_args( $slug, $singular, $plural, $overrides = array() ) {
	return array_merge(
		array(
			'label'                 => $plural,
			'labels'                => array(
				'name'          => $plural,
				'singular_name' => $singular,
			),
			'description'           => '',
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_rest'          => true,
			'rest_base'             => '',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'rest_namespace'        => 'wp/v2',
			'has_archive'           => false,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'delete_with_user'      => false,
			'exclude_from_search'   => false,
			'capability_type'       => 'post',
			'map_meta_cap'          => true,
			'hierarchical'          => false,
			'can_export'            => true,
			'rewrite'               => array(
				'slug'       => $slug,
				'with_front' => true,
			),
			'query_var'             => true,
			'menu_icon'             => 'dashicons-category',
			'supports'              => array( 'title', 'editor', 'thumbnail' ),
		),
		$overrides
	);
}

/**
 * The post types: slug => arguments.
 *
 * @return array<string,array<string,mixed>>
 */
function aranda_de_duero_post_types() {
	return array(
		'seccion-principal' => aranda_de_duero_post_type_args(
			'seccion-principal',
			'Sección Principal',
			'Secciones Principales',
			array(
				'show_in_rest'        => false,
				'exclude_from_search' => true,
				'hierarchical'        => true,
			)
		),
		'actividad' => aranda_de_duero_post_type_args(
			'actividad',
			'Actividad',
			'Actividades',
			array( 'taxonomies' => array( 'seccion', 'concejalia', 'tema', 'servicio' ) )
		),
		'especial' => aranda_de_duero_post_type_args(
			'especial',
			'Especial',
			'Especiales',
			array( 'taxonomies' => array( 'concejalia' ) )
		),
		'tramite' => aranda_de_duero_post_type_args(
			'tramite',
			'Trámite',
			'Trámites',
			array( 'taxonomies' => array( 'concejalia' ) )
		),
		'servicio-municipal' => aranda_de_duero_post_type_args(
			'servicio-municipal',
			'Servicio',
			'Servicios',
			array(
				'taxonomies' => array( 'concejalia' ),
				'rewrite'    => array(
					'slug'       => 'servicios',
					'with_front' => true,
				),
				'menu_icon'  => 'dashicons-building',
			)
		),
	);
}

/**
 * Arguments shared by the six taxonomies.
 *
 * @param string $slug      Slug and name of the taxonomy.
 * @param string $singular  Name in singular.
 * @param string $plural    Name in plural.
 * @param bool   $hierarchy Whether the terms have parents.
 * @return array<string,mixed>
 */
function aranda_de_duero_taxonomy_args( $slug, $singular, $plural, $hierarchy = false ) {
	$rewrite = array(
		'slug'       => $slug,
		'with_front' => true,
	);
	if ( $hierarchy ) {
		$rewrite['hierarchical'] = true;
	}

	return array(
		'label'                 => $plural,
		'labels'                => array(
			'name'          => $plural,
			'singular_name' => $singular,
		),
		'public'                => true,
		'publicly_queryable'    => true,
		'hierarchical'          => $hierarchy,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'query_var'             => true,
		'rewrite'               => $rewrite,
		'show_admin_column'     => false,
		'show_in_rest'          => true,
		'show_tagcloud'         => true,
		'rest_base'             => $slug,
		'rest_controller_class' => 'WP_REST_Terms_Controller',
		'rest_namespace'        => 'wp/v2',
		'show_in_quick_edit'    => false,
		'sort'                  => false,
	);
}

/**
 * The taxonomies: slug => array( object types, arguments ).
 *
 * The types that also list a taxonomy in their own `taxonomies` argument (actividad, especial, tramite) get it from there.
 *
 * @return array<string,array{0:string[],1:array<string,mixed>}>
 */
function aranda_de_duero_taxonomies() {
	return array(
		'seccion'               => array( array( 'post' ), aranda_de_duero_taxonomy_args( 'seccion', 'Sección', 'Secciones' ) ),
		'concejalia'            => array( array( 'post' ), aranda_de_duero_taxonomy_args( 'concejalia', 'Concejalía', 'Concejalías' ) ),
		'tema'                  => array( array( 'post' ), aranda_de_duero_taxonomy_args( 'tema', 'Tema', 'Temas' ) ),
		'servicio'              => array( array( 'post' ), aranda_de_duero_taxonomy_args( 'servicio', 'Servicio', 'Servicios' ) ),
		'categoria_tramite'     => array( array( 'tramite' ), aranda_de_duero_taxonomy_args( 'categoria_tramite', 'Categoría de Trámite', 'Categorías de Trámite' ) ),
		'home_section_category' => array( array( 'seccion-principal' ), aranda_de_duero_taxonomy_args( 'home_section_category', 'Categoría de sección principal', 'Categorias de Sección Principal', true ) ),
	);
}

/**
 * Register the taxonomies. They go before the post types (priority 9), so that the types can list them.
 */
function aranda_de_duero_register_taxonomies() {
	foreach ( aranda_de_duero_taxonomies() as $slug => $definition ) {
		if ( ! taxonomy_exists( $slug ) ) {
			register_taxonomy( $slug, $definition[0], $definition[1] );
		}
	}
}
add_action( 'init', 'aranda_de_duero_register_taxonomies', 9 );

/**
 * Register the post types.
 */
function aranda_de_duero_register_post_types() {
	foreach ( aranda_de_duero_post_types() as $slug => $args ) {
		if ( ! post_type_exists( $slug ) ) {
			register_post_type( $slug, $args );
		}
	}
}
add_action( 'init', 'aranda_de_duero_register_post_types' );

/**
 * Published «servicio-municipal» posts of a concejalía (Biblioteca, Escuela de música… under Cultura), in title order.
 *
 * @param string $slug Slug of the concejalía.
 * @return array<int,WP_Post>
 */
function aranda_de_duero_concejalia_services( $slug ) {
	return get_posts(
		array(
			'post_type'      => 'servicio-municipal',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'no_found_rows'  => true,
			'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- a handful of services per concejalía.
				array(
					'taxonomy' => 'concejalia',
					'field'    => 'slug',
					'terms'    => $slug,
				),
			),
		)
	);
}

/**
 * Services of a concejalía (aranda_de_duero_concejalia_services()), as cards for template-parts/service-cards.php.
 *
 * @param string $slug Slug of the concejalía.
 * @return array<int, array{title: string, text: string, url: string, new_tab: bool}>
 */
function aranda_de_duero_concejalia_service_cards( $slug ) {
	$cards = array();

	foreach ( aranda_de_duero_concejalia_services( $slug ) as $service ) {
		$cards[] = array(
			'title'   => get_the_title( $service ),
			'text'    => wp_strip_all_tags( (string) get_the_excerpt( $service ) ),
			'url'     => (string) get_permalink( $service ),
			'new_tab' => false,
		);
	}

	return $cards;
}
