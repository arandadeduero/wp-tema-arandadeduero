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

<div class="container mt-5 home-especial-container">
    <div class="row">
        <?php
        echo do_shortcode('[ajax_load_more id="9318154416" container_type="div" css_classes="container" post_type="especial" posts_per_page="18" orderby="meta_value_num" custom_args="limit:24" scroll_distance="-50" button_label="Cargar más"]');
        ?>
        <div class="container mt-1">
            <div class="row justify-content-center">
                <div class="p-0 col-6 text-center">
                    <button id="moreEspecial" class="more-especial col-8 p-0 mx-auto">
                        <p class="mb-0 py-2">Más especiales
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<footer id="colophon" class="site-footer py-4 mt-4">
    <div class="container">
        <div class="row">
            <div id="footer-text" class="col-12 text-center small">
                &copy;
                <?php
                /* translators: 1: Theme name, 2: Theme author. */
                printf(esc_html__('%1$s', 'aranda-de-duero'), 'Ilustre Ayuntamiento de Aranda de Duero');
                ?>
                <span class="sep"> | </span>
                <?php
                printf(esc_html__('%1$s, %2$s', 'aranda-de-duero'), 'Plaza Mayor', '1');
                ?>
                <span class="sep"> | </span>
                <?php
                printf(esc_html__('%1$s %2$s', 'aranda-de-duero'), '09400', 'Aranda de Duero');
                ?>
                <span class="sep"> | </span>
                <?php
                printf(esc_html__('%1$s', 'aranda-de-duero'), 'Burgos');
                ?>
                <span class="sep"> | </span>
                <?php
                printf(esc_html__('%1$s. %2$s', 'aranda-de-duero'), 'Tlfno', '947 500 100');
                ?>
                <span class="sep"> | </span>
                <a class="text-dark" href="<?php echo esc_url(__(get_permalink(get_page_by_title('Web Map')), 'aranda-de-duero')); ?>">
                    <?php
                    printf(esc_html__('%s', 'aranda-de-duero'), 'Mapa Web');
                    ?>
                </a>
                <span class="sep"> | </span>
                <a class="text-dark" href="<?php echo esc_url(__(get_permalink(get_page_by_title('Aviso legal')), 'aranda-de-duero')); ?>">
                    <?php
                    printf(esc_html__('%s', 'aranda-de-duero'), 'Aviso Legal');
                    ?>
                </a>
                <span class="sep"> | </span>
                <a class="text-dark" href="<?php echo esc_url(__(get_permalink(get_page_by_title('Política de Accesibilidad')), 'aranda-de-duero')); ?>">
                    <?php
                    printf(esc_html__('%s', 'aranda-de-duero'), 'Política de Accesibilidad');
                    ?>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="footer-links d-flex flex-lg-row flex-column justify-content-between mt-4">
                    <div class="footer-images ">
                        <img class="img img-fluid mr-4" src="/wp-content/uploads/2021/04/impulso.png" alt="Impulso">
                    </div>
                    <div id="footer-social" class="text-right d-flex flex-column justify-content-end col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <div class="footer-social-text">
                            <?php
                            printf(esc_html__('%s:', 'aranda-de-duero'), 'Síguenos en');
                            ?>
                        </div>
                        <div class="footer-social-icons">
                            <a href="https://www.facebook.com/ArandaMeGusta/" rel="external" target="_blank"><span class="d-none">Facebook</span><i class="fab fa-facebook-square ml-2 fa-2x"></i></a>
                            <a href="https://twitter.com/aranda_megusta" rel="external" target="_blank"><span class="d-none">Twitter</span><i class="fa-brands fa-square-x-twitter ml-2 fa-2x"></i></a>
                            <a href="http://www.instagram.com/arandamegusta/" rel="external" target="_blank"><span class="d-none">Instagram</span><i class="fab fa-instagram-square ml-2 fa-2x"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>