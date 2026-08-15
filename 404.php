<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Aranda_de_Duero
 */

get_header();

$header_image = wp_get_attachment_url(get_theme_mod('aranda_de_duero_default_header_image'));

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 p-0">
            <img src="<?php echo esc_url($header_image); ?>" class="img-fluid w-100 cabecera_pagina" alt="" />
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col pt-4">
            <main id="primary" class="site-main">

                <section class="error-404 not-found" aria-label="<?php esc_attr_e('Página no encontrada', 'aranda-de-duero'); ?>">
                    <!-- Hero 404 Section -->
                    <div class="error-hero text-center mb-5">
                        <div class="error-hero-number">404</div>
                        <h1 class="mt-4">¡Te has perdido!</h1>
                        <p class="lead mt-3">
                            Parece que no encontramos lo que estabas buscando, pero <strong>en Aranda podrás perderte
                                y disfrutar las horas por nuestra villa</strong>. 🍷
                        </p>
                    </div>

                    <!-- Suggestions Cards -->
                    <div class="row mb-5">
                        <div class="col-md-4 mb-4">
                            <div class="suggestion-card h-100">
                                <div class="suggestion-card-icon">🏛️</div>
                                <h3>Descubre Aranda</h3>
                                <p>
                                    Te invitamos a conocer nuestra histórica villa del Duero, sus bodegas subterráneas y su rica gastronomía.
                                </p>
                                <a href="https://turismo.arandadeduero.es/" target="_blank" rel="noopener noreferrer"
                                    class="btn btn-outline-primary btn-sm">
                                    Ver más
                                </a>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="suggestion-card h-100">
                                <div class="suggestion-card-icon">📅</div>
                                <h3>Agenda Cultural</h3>
                                <p>
                                    Consulta los eventos, conciertos, exposiciones y actividades que tenemos preparadas.
                                </p>
                                <a href="<?php echo esc_url(get_permalink(get_page_by_path('agenda'))); ?>"
                                    class="btn btn-outline-primary btn-sm">
                                    Ver agenda
                                </a>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="suggestion-card h-100">
                                <div class="suggestion-card-icon">📰</div>
                                <h3>Últimas Noticias</h3>
                                <p>
                                    Mantente informado sobre las novedades y actualidad de nuestro municipio.
                                </p>
                                <a href="<?php echo esc_url(get_permalink(get_page_by_path('noticias'))); ?>"
                                    class="btn btn-outline-primary btn-sm">
                                    Leer noticias
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Search Section -->
                    <div class="search-section">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center mb-4 mb-md-0">
                                <div class="search-section-icon">🔍</div>
                                <h3>Busca lo que necesites</h3>
                            </div>
                            <div class="col-md-8">
                                <p class="mb-3">
                                    Utiliza nuestro buscador para encontrar información sobre trámites, servicios, ayudas o cualquier contenido del portal.
                                </p>
                                <?php get_search_form(); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Fun Facts about Aranda -->
                    <div class="aranda-facts mb-5">
                        <h3>💡 ¿Sabías que...?</h3>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p>
                                    <strong>🍇</strong> Aranda de Duero se encuentra en el corazón de la Ribera del Duero,
                                    una de las zonas vitivinícolas más prestigiosas de España.
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p>
                                    <strong>🏰</strong> Bajo nuestras calles se esconden más de 7 km de bodegas subterráneas
                                    medievales, un auténtico laberinto enológico.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Posts Widget -->
                    <div class="recent-content mb-4">
                        <h3 class="mb-4">Contenidos recientes que podrían interesarte</h3>
                        <div>
                            <?php the_widget('WP_Widget_Recent_Posts', 'number=5'); ?>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="quick-links">
                        <h3 class="mb-4">Accesos rápidos</h3>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="quick-link-card">
                                    <span class="quick-link-card-icon">🏠</span>
                                    <span class="quick-link-card-text">Volver al inicio</span>
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="<?php echo esc_url(get_permalink(get_page_by_path('web-map'))); ?>" class="quick-link-card">
                                    <span class="quick-link-card-icon">🗺️</span>
                                    <span class="quick-link-card-text">Mapa del sitio web</span>
                                </a>
                            </div>
                        </div>
                    </div>

                </section><!-- .error-404 -->

            </main><!-- #main -->
        </div>
    </div>
</div>
<?php
get_footer();
