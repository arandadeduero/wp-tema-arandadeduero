<?php
/**
 * Template Name: Página de mapa web
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aranda_de_Duero
 */


// Nuevo 
get_header();
?>

	<div class="container mt-4">
		<div class="row">
			<div class="col-lg-12 pt-4">
				<main id="primary" class="site-main">
               <?php 
                	wp_nav_menu(
						array(
							'theme_location' => 'menu-1',
							'menu_id'        => '',
						)
					);

					wp_nav_menu(
						array(
							'theme_location' => 'top',
							'menu_id'        => '',
						)
					);

					
               ?>
                    
				</main><!-- #main -->
			</div>
			
		</div>
	</div>

<?php

get_footer();
