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
    <script async="" src="//www.google-analytics.com/analytics.js"></script>
	<?php wp_head(); ?>
	<style>
	/*
 * Ajax Load More
 * http://wordpress.org/plugins/ajax-load-more/
 *
 * Copyright 2015-2021 Connekt Media - https://connekthq.com
 * Free to use under the GPLv2 license.
 * http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Author: Darren Cooney
 * Twitter: @KaptonKaos
 * Twitter: @ajaxloadmore
 * Twitter: @connekthq
*/
.alm-btn-wrap {
  display: block;
  text-align: center;
  padding: 10px 0;
  margin: 0 0 15px;
  position: relative; }
  .alm-btn-wrap:after {
    display: table;
    clear: both;
    height: 0;
    width: 100%;
    content: ''; }
  .alm-btn-wrap .alm-load-more-btn {
    font-size: 15px;
    font-weight: 500;
    width: auto;
    height: 43px;
    line-height: 1;
    background: #ed7070;
    -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
    color: #fff;
    border: none;
    border-radius: 4px;
    margin: 0;
    padding: 0 20px;
    display: inline-block;
    position: relative;
    -webkit-transition: all 0.3s ease;
    transition: all 0.3s ease;
    text-align: center;
    text-decoration: none;
    -webkit-appearance: none;
       -moz-appearance: none;
            appearance: none;
    -webkit-user-select: none;
       -moz-user-select: none;
        -ms-user-select: none;
            user-select: none;
    cursor: pointer;
    /* Loading */
    /* Loaded / Done */
    /* Loading Icon */
    /* Loading :before */ }
    .alm-btn-wrap .alm-load-more-btn:hover, .alm-btn-wrap .alm-load-more-btn.loading {
      background-color: #e06161;
      -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.09);
              box-shadow: 0 1px 3px rgba(0, 0, 0, 0.09);
      color: #fff;
      text-decoration: none; }
    .alm-btn-wrap .alm-load-more-btn:active {
      -webkit-box-shadow: none;
              box-shadow: none;
      text-decoration: none; }
    .alm-btn-wrap .alm-load-more-btn.loading {
      cursor: default;
      outline: none;
      padding-left: 44px; }
    .alm-btn-wrap .alm-load-more-btn.done {
      cursor: default;
      opacity: 0.15;
      background-color: #ed7070;
      outline: none !important;
      -webkit-box-shadow: none !important;
              box-shadow: none !important; }
    .alm-btn-wrap .alm-load-more-btn:before, .alm-btn-wrap .alm-load-more-btn.done:before {
      background: none;
      width: 0; }
    .alm-btn-wrap .alm-load-more-btn.loading:before {
      background: #fff url("../../img/ajax-loader.gif") no-repeat center center;
      width: 30px;
      height: 31px;
      margin: 6px;
      border-radius: 3px;
      display: inline-block;
      z-index: 0;
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      overflow: hidden;
      -webkit-transition: width 0.5s ease-in-out;
      transition: width 0.5s ease-in-out; }
  .alm-btn-wrap .alm-elementor-link {
    display: block;
    font-size: 13px;
    margin: 0 0 15px; }
    @media screen and (min-width: 768px) {
      .alm-btn-wrap .alm-elementor-link {
        position: absolute;
        left: 0;
        top: 50%;
        -webkit-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
                transform: translateY(-50%);
        margin: 0; } }

/* white */
.ajax-load-more-wrap.white .alm-load-more-btn {
  background-color: #fff;
  color: #787878;
  border: 1px solid #e0e0e0;
  overflow: hidden;
  -webkit-transition: none;
  transition: none;
  outline: none; }
  .ajax-load-more-wrap.white .alm-load-more-btn:focus, .ajax-load-more-wrap.white .alm-load-more-btn:hover, .ajax-load-more-wrap.white .alm-load-more-btn.loading {
    background-color: #fff;
    color: #333;
    border-color: #aaaaaa; }
  .ajax-load-more-wrap.white .alm-load-more-btn.done {
    background-color: #fff;
    color: #444;
    border-color: #ccc; }
  .ajax-load-more-wrap.white .alm-load-more-btn.loading {
    color: rgba(255, 255, 255, 0) !important;
    outline: none !important;
    background-color: transparent;
    border-color: transparent !important;
    -webkit-box-shadow: none !important;
            box-shadow: none !important;
    padding-left: 20px; }
    .ajax-load-more-wrap.white .alm-load-more-btn.loading:before {
      margin: 0;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: transparent;
      background-image: url("../../img/ajax-loader-lg.gif");
      background-size: 25px 25px;
      background-position: center center; }

/* light grey */
.ajax-load-more-wrap.light-grey .alm-load-more-btn {
  background-color: #efefef;
  color: #787878;
  border: 1px solid #e0e0e0;
  overflow: hidden;
  -webkit-transition: all 0.075s ease;
  transition: all 0.075s ease;
  outline: none; }
  .ajax-load-more-wrap.light-grey .alm-load-more-btn:focus, .ajax-load-more-wrap.light-grey .alm-load-more-btn:hover, .ajax-load-more-wrap.light-grey .alm-load-more-btn.loading, .ajax-load-more-wrap.light-grey .alm-load-more-btn.done {
    background-color: #f1f1f1;
    color: #222;
    border-color: #aaaaaa; }
  .ajax-load-more-wrap.light-grey .alm-load-more-btn.loading {
    color: rgba(255, 255, 255, 0) !important;
    outline: none !important;
    background-color: transparent;
    border-color: transparent !important;
    -webkit-box-shadow: none !important;
            box-shadow: none !important;
    padding-left: 20px; }
    .ajax-load-more-wrap.light-grey .alm-load-more-btn.loading:before {
      margin: 0;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: transparent;
      background-image: url("../../img/ajax-loader-lg.gif");
      background-size: 25px 25px;
      background-position: center center; }

/* Blue */
.ajax-load-more-wrap.blue .alm-load-more-btn {
  background-color: #1b91ca; }
  .ajax-load-more-wrap.blue .alm-load-more-btn:hover, .ajax-load-more-wrap.blue .alm-load-more-btn.loading, .ajax-load-more-wrap.blue .alm-load-more-btn.done {
    background-color: #1b84b7; }

/* green */
.ajax-load-more-wrap.green .alm-load-more-btn {
  background-color: #80ca7a; }
  .ajax-load-more-wrap.green .alm-load-more-btn:hover, .ajax-load-more-wrap.green .alm-load-more-btn.loading, .ajax-load-more-wrap.green .alm-load-more-btn.done {
    background-color: #81c17b; }

/* purple */
.ajax-load-more-wrap.purple .alm-load-more-btn {
  background-color: #b97eca; }
  .ajax-load-more-wrap.purple .alm-load-more-btn:hover, .ajax-load-more-wrap.purple .alm-load-more-btn.loading, .ajax-load-more-wrap.purple .alm-load-more-btn.done {
    background-color: #a477b1; }

/* grey */
.ajax-load-more-wrap.grey .alm-load-more-btn {
  background-color: #a09e9e; }
  .ajax-load-more-wrap.grey .alm-load-more-btn:hover, .ajax-load-more-wrap.grey .alm-load-more-btn.loading, .ajax-load-more-wrap.grey .alm-load-more-btn.done {
    background-color: #888; }

/* Infinite */
.ajax-load-more-wrap.infinite > .alm-btn-wrap .alm-load-more-btn {
  width: 100%;
  background-color: transparent !important;
  background-position: center center;
  background-repeat: no-repeat;
  background-image: url("../../img/spinner.gif");
  border: none !important;
  opacity: 0;
  -webkit-transition: opacity 0.2s ease;
  transition: opacity 0.2s ease;
  -webkit-box-shadow: none !important;
          box-shadow: none !important;
  overflow: hidden;
  text-indent: -9999px;
  cursor: default !important;
  outline: none !important; }
  .ajax-load-more-wrap.infinite > .alm-btn-wrap .alm-load-more-btn:before {
    display: none !important; }
  .ajax-load-more-wrap.infinite > .alm-btn-wrap .alm-load-more-btn:focus, .ajax-load-more-wrap.infinite > .alm-btn-wrap .alm-load-more-btn:active {
    outline: none; }
  .ajax-load-more-wrap.infinite > .alm-btn-wrap .alm-load-more-btn.done {
    opacity: 0; }
  .ajax-load-more-wrap.infinite > .alm-btn-wrap .alm-load-more-btn.loading {
    opacity: 1; }

.ajax-load-more-wrap.infinite.skype > .alm-btn-wrap .alm-load-more-btn {
  background-image: url("../../img/spinner-skype.gif"); }

.ajax-load-more-wrap.infinite.ring > .alm-btn-wrap .alm-load-more-btn {
  background-image: url("../../img/spinner-ring.gif"); }

.ajax-load-more-wrap.infinite.fading-blocks > .alm-btn-wrap .alm-load-more-btn {
  background-image: url("../../img/loader-fading-blocks.gif"); }

.ajax-load-more-wrap.infinite.fading-circles > .alm-btn-wrap .alm-load-more-btn {
  background-image: url("../../img/loader-fading-circles.gif"); }

.ajax-load-more-wrap.infinite.chasing-arrows > .alm-btn-wrap .alm-load-more-btn {
  background-image: url("../../img/spinner-chasing-arrows.gif"); }

.ajax-load-more-wrap.alm-horizontal .alm-btn-wrap {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
  padding: 0;
  margin: 0; }
  .ajax-load-more-wrap.alm-horizontal .alm-btn-wrap button {
    margin: 0; }
    .ajax-load-more-wrap.alm-horizontal .alm-btn-wrap button.done {
      display: none; }

/**
 *  Generic alm-listing Styles
 *  @since 1.0.0
 */
.alm-listing .alm-reveal {
  outline: none; }
  .alm-listing .alm-reveal:after {
    display: table;
    clear: both;
    height: 0;
    content: ''; }

.alm-listing {
  margin: 0;
  padding: 0; }
  .alm-listing .alm-reveal > li,
  .alm-listing .alm-paging-content > li,
  .alm-listing > li {
    position: relative; }
    .alm-listing .alm-reveal > li.alm-item,
    .alm-listing .alm-paging-content > li.alm-item,
    .alm-listing > li.alm-item {
      background: none;
      margin: 0 0 30px;
      padding: 0 0 0 80px;
      position: relative;
      list-style: none; }
      @media screen and (min-width: 480px) {
        .alm-listing .alm-reveal > li.alm-item,
        .alm-listing .alm-paging-content > li.alm-item,
        .alm-listing > li.alm-item {
          padding: 0 0 0 100px; } }
      @media screen and (min-width: 768px) {
        .alm-listing .alm-reveal > li.alm-item,
        .alm-listing .alm-paging-content > li.alm-item,
        .alm-listing > li.alm-item {
          padding: 0 0 0 135px; } }
      @media screen and (min-width: 1024px) {
        .alm-listing .alm-reveal > li.alm-item,
        .alm-listing .alm-paging-content > li.alm-item,
        .alm-listing > li.alm-item {
          padding: 0 0 0 160px; } }
      .alm-listing .alm-reveal > li.alm-item h3,
      .alm-listing .alm-paging-content > li.alm-item h3,
      .alm-listing > li.alm-item h3 {
        margin: 0; }
      .alm-listing .alm-reveal > li.alm-item p,
      .alm-listing .alm-paging-content > li.alm-item p,
      .alm-listing > li.alm-item p {
        margin: 10px 0 0; }
        .alm-listing .alm-reveal > li.alm-item p.entry-meta,
        .alm-listing .alm-paging-content > li.alm-item p.entry-meta,
        .alm-listing > li.alm-item p.entry-meta {
          opacity: 0.75; }
      .alm-listing .alm-reveal > li.alm-item img,
      .alm-listing .alm-paging-content > li.alm-item img,
      .alm-listing > li.alm-item img {
        position: absolute;
        left: 0;
        top: 0;
        border-radius: 2px;
        max-width: 65px; }
        @media screen and (min-width: 480px) {
          .alm-listing .alm-reveal > li.alm-item img,
          .alm-listing .alm-paging-content > li.alm-item img,
          .alm-listing > li.alm-item img {
            max-width: 85px; } }
        @media screen and (min-width: 768px) {
          .alm-listing .alm-reveal > li.alm-item img,
          .alm-listing .alm-paging-content > li.alm-item img,
          .alm-listing > li.alm-item img {
            max-width: 115px; } }
        @media screen and (min-width: 1024px) {
          .alm-listing .alm-reveal > li.alm-item img,
          .alm-listing .alm-paging-content > li.alm-item img,
          .alm-listing > li.alm-item img {
            max-width: 140px; } }
    .alm-listing .alm-reveal > li.no-img,
    .alm-listing .alm-paging-content > li.no-img,
    .alm-listing > li.no-img {
      padding: 0; }
  .alm-listing.products li.product {
    padding-left: inherit; }
    .alm-listing.products li.product img {
      position: static;
      border-radius: inherit; }
  .alm-listing.stylefree .alm-reveal > li,
  .alm-listing.stylefree .alm-paging-content > li,
  .alm-listing.stylefree > li {
    padding: inherit;
    margin: inherit; }
    .alm-listing.stylefree .alm-reveal > li img,
    .alm-listing.stylefree .alm-paging-content > li img,
    .alm-listing.stylefree > li img {
      padding: inherit;
      margin: inherit;
      position: static;
      border-radius: inherit; }

.alm-listing.rtl .alm-reveal > li,
.alm-listing.rtl .alm-paging-content > li {
  padding: 0 170px 0 0;
  text-align: right; }
  .alm-listing.rtl .alm-reveal > li img,
  .alm-listing.rtl .alm-paging-content > li img {
    left: auto;
    right: 0; }

.alm-listing.rtl.products li.product {
  padding-right: inherit; }

.alm-masonry {
  display: block;
  overflow: hidden;
  clear: both; }

.alm-placeholder {
  opacity: 0;
  -webkit-transition: opacity 0.2s ease;
  transition: opacity 0.2s ease;
  display: none; }

.ajax-load-more-wrap.alm-horizontal {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: nowrap;
      flex-wrap: nowrap;
  width: 100%; }
  .ajax-load-more-wrap.alm-horizontal .alm-listing,
  .ajax-load-more-wrap.alm-horizontal .alm-listing .alm-reveal {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: nowrap;
        flex-wrap: nowrap;
    -webkit-box-orient: horizontal;
    -webkit-box-direction: normal;
        -ms-flex-direction: row;
            flex-direction: row; }
    .ajax-load-more-wrap.alm-horizontal .alm-listing > li.alm-item,
    .ajax-load-more-wrap.alm-horizontal .alm-listing .alm-reveal > li.alm-item {
      padding: 0;
      text-align: center;
      margin: 0 2px;
      padding: 20px 20px 30px;
      height: auto;
      background-color: #fff;
      border: 1px solid #efefef;
      border-radius: 4px;
      width: 300px; }
      .ajax-load-more-wrap.alm-horizontal .alm-listing > li.alm-item img,
      .ajax-load-more-wrap.alm-horizontal .alm-listing .alm-reveal > li.alm-item img {
        position: static;
        border-radius: 100%;
        max-width: 125px;
        margin: 0 auto 15px;
        border-radius: 4px;
        -webkit-box-shadow: 0 2px 10px rgba(0, 0, 0, 0.075);
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.075); }
  .ajax-load-more-wrap.alm-horizontal .alm-listing .alm-reveal:after {
    display: none; }

.alm-toc {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  width: auto;
  padding: 10px 0; }
  .alm-toc button {
    background: #f7f7f7;
    border-radius: 4px;
    -webkit-transition: all 0.15s ease;
    transition: all 0.15s ease;
    outline: none;
    border: 1px solid #efefef;
    -webkit-box-shadow: none;
            box-shadow: none;
    color: #454545;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    padding: 7px 10px;
    line-height: 1;
    margin: 0 5px 0 0;
    height: auto; }
    .alm-toc button:hover, .alm-toc button:focus {
      border-color: #ccc;
      color: #222; }
    .alm-toc button:hover {
      text-decoration: underline; }
    .alm-toc button:focus {
      -webkit-box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
              box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05); }

	</style>
</head>

<body <?php body_class(); ?>>
<script type="text/javascript">
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-48687607-1', 'arandadeduero.es');
  ga('send', 'pageview');

</script>


<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-BXMC22W46S"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-BXMC22W46S');
</script>


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
	
