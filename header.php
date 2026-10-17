<?php

/**
 * The header for our theme
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
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<meta name="description" content="<?php echo esc_attr( get_bloginfo( 'description' ) ); ?>">
	<meta name="theme-color" content="#0083c1">
	<meta name="format-detection" content="telephone=no">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'aranda-de-duero' ); ?></a>

	<div id="page" class="site">
		<?php $aranda_contact = aranda_de_duero_contact_info(); ?>
		<div class="top-bar">
			<div class="top-bar-inner">
				<ul class="top-bar-info">
					<li class="top-bar-item">
						<?php
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
						echo aranda_de_duero_icon( 'aranda-de-duero/phone-flip', array( 'size' => 14 ) );
						?>
						<a href="<?php echo esc_url( $aranda_contact['phone_href'] ); ?>"><?php echo esc_html( $aranda_contact['phone'] ); ?></a>
					</li>
					<li class="top-bar-item">
						<?php
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
						echo aranda_de_duero_icon( 'aranda-de-duero/clock', array( 'size' => 14 ) );
						?>
						<span><?php echo esc_html( $aranda_contact['hours'] ); ?></span>
					</li>
				</ul>
				<a class="top-bar-appointment" href="<?php echo esc_url( $aranda_contact['appointment_url'] ); ?>"><?php esc_html_e( 'Cita previa', 'aranda-de-duero' ); ?></a>
			</div>
		</div><!-- .top-bar -->

		<div class="site-header-wrapper">

			<!-- Main Header -->
			<header id="masthead" class="site-header">

				<!-- Site Branding (desktop) -->
				<div class="site-branding">
					<?php the_custom_logo(); ?>
				</div>

				<!-- Navigation -->
				<div class="header-nav">
					<?php if ( has_nav_menu( 'top' ) ) : ?>
						<nav class="menu-top" aria-label="<?php esc_attr_e( 'Secondary Menu', 'aranda-de-duero' ); ?>">
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'top',
									'menu_id'        => 'secondary-menu',
									'container'      => false,
									'fallback_cb'    => false,
								)
							);
							?>
						</nav>
					<?php endif; ?>

					<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'aranda-de-duero' ); ?>">
						<button class="menu-toggle"
							aria-controls="primary-menu"
							aria-expanded="false"
							aria-label="<?php esc_attr_e( 'Toggle navigation menu', 'aranda-de-duero' ); ?>"
							type="button">
							<?php
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
							echo aranda_de_duero_icon( 'aranda-de-duero/bars' );
							?>
						</button>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'menu-1',
								'menu_id'        => 'primary-menu',
								'container'      => false,
								'fallback_cb'    => false,
							)
						);
						?>
					</nav>
				</div><!-- .header-nav -->

			</header><!-- #masthead -->
		</div><!-- .site-header-wrapper -->

		<?php $aranda_quick_links = aranda_de_duero_quick_links(); ?>
		<?php if ( $aranda_quick_links ) : ?>
			<!-- Secondary menu again for phones, where Mobile Menu hides the header above (see css/aranda-de-duero.css) -->
			<nav class="mobile-quick-links" aria-label="<?php esc_attr_e( 'Accesos rápidos', 'aranda-de-duero' ); ?>">
				<ul>
					<?php foreach ( $aranda_quick_links as $aranda_link ) : ?>
						<li>
							<a href="<?php echo esc_url( $aranda_link['url'] ); ?>"
							<?php
							if ( $aranda_link['new_tab'] ) :
								?>
								target="_blank" rel="noopener"<?php endif; ?>>
								<?php echo esc_html( $aranda_link['title'] ); ?>
								<?php
								if ( $aranda_link['new_tab'] ) {
									aranda_de_duero_the_new_tab_notice();
								}
								?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>
		<?php endif; ?>

		<div id="content" class="site-content">