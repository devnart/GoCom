<?php
/*
Plugin Name: Woodmart Core
Description: Woodmart Core needed for Woodmart theme
Version: 1.0.28
Text Domain: woodmart-core
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

define( 'WOODMART_CORE_PLUGIN_VERSION', '1.0.27' );

require_once 'vendor/opauth/twitteroauth/twitteroauth.php';
require_once 'inc/auth.php';
require_once 'post-types.php';
require_once 'inc/shortcodes.php';
