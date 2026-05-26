<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Aranda_de_Duero
 */

?>

<!-- Featured Specials Section -->
<section class="home-especial-section py-5" aria-label="<?php esc_attr_e('Contenidos especiales', 'aranda-de-duero'); ?>">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title">Especiales</h2>
                <p class="text-muted mt-4">Contenidos destacados de nuestro municipio</p>
            </div>
        </div>

        <div class="row">
            <?php
            echo do_shortcode('[ajax_load_more id="9318154416" container_type="div" css_classes="row" post_type="especial" posts_per_page="18" orderby="meta_value_num" custom_args="limit:24" scroll_distance="-50" button_label="Cargar más especiales"]');
            ?>
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <button id="moreEspecial" class="btn btn-outline-primary btn-lg px-5">
                    Más especiales
                    <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<footer id="colophon" class="site-footer">
    <!-- Main Footer -->
    <div class="footer-main">
        <div class="container">
            <div class="row">
                <!-- Column 1: About Municipality -->
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-about">
                        <h4 class="text-white mb-3">Ilustre Ayuntamiento de Aranda de Duero</h4>
                        <p class="text-white-50 mb-3">
                            Trabajando cada día por y para la ciudadanía de Aranda de Duero.
                            Gobierno municipal comprometido con la transparencia y la participación ciudadana.
                        </p>
                        <div class="footer-seal mt-4">
                            <span>🏛️</span>
                        </div>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <h5 class="text-white mb-3">Servicios</h5>
                    <ul class="list-unstyled footer-links">
                        <li class="mb-2">
                            <a href="https://sede.arandadeduero.es/sta/CarpetaPublic/doEvent?APP_CODE=STA&PAGE_CODE=CATALOGO">
                                → Trámites online
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo esc_url(get_permalink(get_page_by_title('Agenda'))); ?>">
                                → Agenda municipal
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo esc_url(get_permalink(get_page_by_title('Ayudas'))); ?>">
                                → Ayudas y subvenciones
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="https://sede.arandadeduero.es/sta/CarpetaPublic/doEvent?APP_CODE=STA&PAGE_CODE=PTS2_TABLON">
                                → Ofertas de empleo
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="https://sede.arandadeduero.es/">
                                → Sede electrónica
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Column 3: Contact -->
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <h5 class="text-white mb-3">Contacto</h5>
                    <ul class="list-unstyled text-white-50 footer-contact-item">
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt footer-contact-icon"></i>
                            Plaza Mayor, 1<br>
                            <span class="ms-4">09400 Aranda de Duero, Burgos</span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone-alt footer-contact-icon"></i>
                            <a href="tel:947500100">947 500 100</a>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-envelope footer-contact-icon"></i>
                            <a href="mailto:atencionpublico@arandadeduero.es">atencionpublico@arandadeduero.es</a>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-clock footer-contact-icon"></i>
                            Lun - Vie: 8:30 - 14:30
                        </li>
                    </ul>
                </div>

                <!-- Column 4: Social Media -->
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <h5 class="text-white mb-3">Síguenos</h5>
                    <p class="text-white-50 mb-3">Mantente conectado con nosotros en las redes sociales</p>
                    <div class="footer-social d-flex gap-2 mb-4">
                        <a href="https://www.facebook.com/aytoarandadeduero/"
                            rel="external noopener"
                            target="_blank"
                            class="social-icon"
                            aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/aytoaranda"
                            rel="external noopener"
                            target="_blank"
                            class="social-icon"
                            aria-label="Twitter/X">
                            <i class="fab fa-x-twitter"></i>
                        </a>
                        <a href="https://www.instagram.com/ayuntamientoarandadeduero/"
                            rel="external noopener"
                            target="_blank"
                            class="social-icon"
                            aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.youtube.com/@SMArandaDeDuero"
                            rel="external noopener"
                            target="_blank"
                            class="social-icon"
                            aria-label="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom Bar -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-3 mb-lg-0">
                    <p class="mb-0 text-white-50 small">
                        &copy; <?php echo esc_html(wp_date('Y')); ?> Ilustre Ayuntamiento de Aranda de Duero. Todos los derechos reservados.
                    </p>
                </div>
                <div class="col-lg-6 text-lg-end">
                    <ul class="list-inline mb-0 footer-legal-links">
                        <li class="list-inline-item">
                            <a href="<?php echo esc_url(get_permalink(get_page_by_title('Aviso legal'))); ?>">
                                Aviso Legal
                            </a>
                        </li>
                        <li class="list-inline-item mx-2 text-white-50">•</li>
                        <li class="list-inline-item">
                            <a href="<?php echo esc_url(get_permalink(get_page_by_title('Política de Privacidad'))); ?>">
                                Privacidad
                            </a>
                        </li>
                        <li class="list-inline-item mx-2 text-white-50">•</li>
                        <li class="list-inline-item">
                            <a href="<?php echo esc_url(get_permalink(get_page_by_title('Política de Accesibilidad'))); ?>">
                                Accesibilidad
                            </a>
                        </li>
                        <li class="list-inline-item mx-2 text-white-50">•</li>
                        <li class="list-inline-item">
                            <a href="<?php echo esc_url(get_permalink(get_page_by_title('Web Map'))); ?>">
                                Mapa Web
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>