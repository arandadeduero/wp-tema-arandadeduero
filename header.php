<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Aranda_de_Duero
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="zoom-domain-verification" content="ZOOM_verify_KMhEiObFczwnG46wHdMKHq">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>
<div id="page" class="site">
<div class="container">
	<div class="row">
		<div class="col-12 d-md-none text-right">
			<div class="logo-mobile col-7 offset-3">
				<?php
				the_custom_logo();
				?>
			</div>
		</div>
		<!-- <div class="col-12 mt-2">
			
		</div> -->
	</div>
	<div class="row">
		<div class="col-12">
				<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'aranda-de-duero' ); ?></a>

				<header id="masthead" class="site-header justify-content-between mt-2">
					<div class="site-branding order-2 order-lg-1">
						<?php
						the_custom_logo();
						if ( is_front_page()) :
							?>
							<h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
							<?php
						else :
							?>
							<p class="site-title"><?php bloginfo( 'name' ); ?></p>
							<?php
						endif;
						$aranda_de_duero_description = get_bloginfo( 'description', 'display' );
						if ( $aranda_de_duero_description || is_customize_preview() ) :
							?>
							<p class="site-description"><?php echo $aranda_de_duero_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
						<?php endif; ?>
					</div><!-- .site-branding -->
					<div class="header-left order-1 order-lg-2 align-self-stretch">
						<div class="menu-top">
							<?php
								wp_nav_menu(
									array(
										'theme_location' => 'top',
										'menu_id'        => 'secondary-menu',
									)
								);
							?>
						</div>
						<nav id="site-navigation" class="main-navigation mt-4">
							<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><i class="fas fa-bars"></i></button>
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'menu-1',
									'menu_id'        => 'primary-menu',
								)
							);
							?>
						</nav><!-- #site-navigation -->
					</div>
				</header><!-- #masthead -->
				<!-- Social bar  -->
				<div class="social-bar">
					<p><a aria-label="Perfil de Facebook de Aranda de Duero" href="https://www.facebook.com/ArandaMeGusta/" rel="external" target="_blank"><span class="d-none">Facebook</span><i aria-hidden="true" class="fab fa-facebook-square fa-2x" title="Perfil de Facebook de Aranda de Duero"></i></a></p>
					<p><a aria-label="Perfil de Twitter de Aranda de Duero" href="https://twitter.com/aranda_megusta" rel="external" target="_blank"><span class="d-none">Twitter</span><i aria-hidden="true" class="fab fa-twitter-square fa-2x" title="Perfil de Twitter de Aranda de Duero"></i></a></p>
					<p><a aria-label="Perfil de Instagram de Aranda de Duero" href="http://www.instagram.com/arandamegusta/" rel="external" target="_blank"><span class="d-none">Instagram</span><i aria-hidden="true" class="fab fa-instagram-square fa-2x" aria-label="Perfil de Instagram de Aranda de Duero"></i></a></p>
					<p><a aria-label="Blog de Aranda de Duero" href="http://blog.arandadeduero.es/" rel="external" target="_blank"><span class="d-none">Blog</span><i aria-hidden="true" class="fab fa-blogger fa-2x" title="Blog de Aranda de Duero"></i></a></p>
				</div>
    			<!-- Fin social bar  -->
		</div>
	</div>
</div>
