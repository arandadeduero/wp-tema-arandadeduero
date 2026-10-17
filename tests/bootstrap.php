<?php
/**
 * PHPUnit bootstrap: WP_Mock stands in for WordPress, so no WP install is needed.
 */

define( 'ABSPATH', dirname( __DIR__ ) . '/' );

require_once dirname( __DIR__ ) . '/vendor/autoload.php';

WP_Mock::bootstrap();
