<?php
/**
 * PHPUnit bootstrap: WP_Mock stands in for WordPress, so no WP install is needed.
 */

define( 'ABSPATH', dirname( __DIR__ ) . '/' );

// WordPress core constants used by the theme.
define( 'DAY_IN_SECONDS', 86400 );
define( 'WEEK_IN_SECONDS', 604800 );

require_once dirname( __DIR__ ) . '/vendor/autoload.php';

WP_Mock::bootstrap();
