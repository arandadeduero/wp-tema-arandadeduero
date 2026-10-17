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

<footer id="colophon" class="site-footer">
	<!-- Main Footer -->
	<div class="footer-main">
		<div class="container">
			<div class="row">
				<!-- Column 1: About Municipality -->
				<div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
					<div class="footer-about">
						<h2 class="h4 text-white mb-3">Ilustre Ayuntamiento de Aranda de Duero</h2>
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
					<h3 class="h5 text-white mb-3">Servicios</h3>
					<ul class="list-unstyled footer-links">
						<li class="mb-2">
							<a href="https://sede.arandadeduero.es/sta/CarpetaPublic/doEvent?APP_CODE=STA&PAGE_CODE=CATALOGO">
								Trámites online
							</a>
						</li>
						<li class="mb-2">
							<a href="<?php echo esc_url( aranda_de_duero_get_page_permalink_by_title( 'Agenda' ) ); ?>">
								Agenda municipal
							</a>
						</li>
						<li class="mb-2">
							<a href="<?php echo esc_url( aranda_de_duero_get_page_permalink_by_title( 'Ayudas' ) ); ?>">
								Ayudas y subvenciones
							</a>
						</li>
						<li class="mb-2">
							<a href="https://sede.arandadeduero.es/sta/CarpetaPublic/doEvent?APP_CODE=STA&PAGE_CODE=PTS2_TABLON">
								Ofertas de empleo
							</a>
						</li>
						<li class="mb-2">
							<a href="https://sede.arandadeduero.es/">
								Sede electrónica
							</a>
						</li>
					</ul>
				</div>

				<!-- Column 3: Contact -->
				<div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
					<h3 class="h5 text-white mb-3">Contacto</h3>
					<ul class="list-unstyled text-white-50 footer-contact-item">
						<li class="footer-contact-row">
							<?php
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
							echo aranda_de_duero_icon(
								'aranda-de-duero/location-dot',
								array(
									'size'  => 16,
									'class' => 'footer-contact-icon',
								)
							);
							?>
							<span>Plaza Mayor, 1<br>09400 Aranda de Duero, Burgos</span>
						</li>
						<li class="footer-contact-row">
							<?php
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
							echo aranda_de_duero_icon(
								'aranda-de-duero/phone-flip',
								array(
									'size'  => 16,
									'class' => 'footer-contact-icon',
								)
							);
							?>
							<span><a href="tel:947500100">947 500 100</a></span>
						</li>
						<li class="footer-contact-row">
							<?php
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
							echo aranda_de_duero_icon(
								'aranda-de-duero/envelope',
								array(
									'size'  => 16,
									'class' => 'footer-contact-icon',
								)
							);
							?>
							<span><a href="mailto:atencionpublico@arandadeduero.es">atencionpublico@arandadeduero.es</a></span>
						</li>
						<li class="footer-contact-row">
							<?php
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
							echo aranda_de_duero_icon(
								'aranda-de-duero/clock',
								array(
									'size'  => 16,
									'class' => 'footer-contact-icon',
								)
							);
							?>
							<span>Lun - Vie: 8:30 - 14:30</span>
						</li>
					</ul>
				</div>

				<!-- Column 4: Social Media -->
				<div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
					<h3 class="h5 text-white mb-3">Síguenos</h3>
					<p class="text-white-50 mb-3">Mantente conectado con nosotros en las redes sociales</p>
					<div class="footer-social d-flex gap-2 mb-4">
						<a href="https://www.facebook.com/aytoarandadeduero/"
							rel="external noopener"
							target="_blank"
							class="social-icon"
							aria-label="Facebook">
							<?php
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
							echo aranda_de_duero_icon( 'aranda-de-duero/facebook-f', array( 'size' => 18 ) );
							?>
						</a>
						<a href="https://twitter.com/aytoaranda"
							rel="external noopener"
							target="_blank"
							class="social-icon"
							aria-label="Twitter/X">
							<?php
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
							echo aranda_de_duero_icon( 'aranda-de-duero/x-twitter', array( 'size' => 18 ) );
							?>
						</a>
						<a href="https://www.instagram.com/ayuntamientoarandadeduero/"
							rel="external noopener"
							target="_blank"
							class="social-icon"
							aria-label="Instagram">
							<?php
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
							echo aranda_de_duero_icon( 'aranda-de-duero/instagram', array( 'size' => 18 ) );
							?>
						</a>
						<a href="https://www.youtube.com/@SMArandaDeDuero"
							rel="external noopener"
							target="_blank"
							class="social-icon"
							aria-label="YouTube">
							<?php
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
							echo aranda_de_duero_icon( 'aranda-de-duero/youtube', array( 'size' => 18 ) );
							?>
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
						&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> Ilustre Ayuntamiento de Aranda de Duero. Todos los derechos reservados.
					</p>
				</div>
				<div class="col-lg-6 text-lg-end">
					<ul class="list-inline mb-0 footer-legal-links">
						<li class="list-inline-item">
							<a href="<?php echo esc_url( aranda_de_duero_get_page_permalink_by_title( 'Aviso legal' ) ); ?>">
								Aviso Legal
							</a>
						</li>
						<li class="list-inline-item mx-2 text-white-50">•</li>
						<li class="list-inline-item">
							<a href="<?php echo esc_url( aranda_de_duero_get_page_permalink_by_title( 'Política de Privacidad' ) ); ?>">
								Privacidad
							</a>
						</li>
						<li class="list-inline-item mx-2 text-white-50">•</li>
						<li class="list-inline-item">
							<a href="<?php echo esc_url( aranda_de_duero_get_page_permalink_by_title( 'Política de Accesibilidad' ) ); ?>">
								Accesibilidad
							</a>
						</li>
						<?php $aranda_whistleblower_url = aranda_de_duero_whistleblower_url(); ?>
						<?php if ( '' !== $aranda_whistleblower_url ) : ?>
							<li class="list-inline-item mx-2 text-white-50">•</li>
							<li class="list-inline-item">
								<a href="<?php echo esc_url( $aranda_whistleblower_url ); ?>">
									Canal de información
								</a>
							</li>
						<?php endif; ?>
						<li class="list-inline-item mx-2 text-white-50">•</li>
						<li class="list-inline-item">
							<a href="<?php echo esc_url( aranda_de_duero_get_page_permalink_by_title( 'Web Map' ) ); ?>">
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