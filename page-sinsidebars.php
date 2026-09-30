<?php
/**
 * Template Name: Página sin sidebars
 *
 * Plantilla de página sin sidebars
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aranda_de_Duero
 */

get_header();
aranda_de_duero_the_header_image('');
?>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <main id="primary" class="site-main">

                    <?php
                    while ( have_posts() ) :
                        the_post();

                        get_template_part( 'template-parts/content', 'page-notitle' );

                        // If comments are open or we have at least one comment, load up the comment template.
                        if ( comments_open() || get_comments_number() ) :
                            comments_template();
                        endif;

                    endwhile; // End of the loop.
                    ?>

                </main><!-- #main -->
            </div>
        </div>
    </div>

<?php

get_footer();
