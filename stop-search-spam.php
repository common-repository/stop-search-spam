<?php
/*
	Plugin Name:				Stop Search Spam
	Plugin URI:					https://profiles.wordpress.org/rockstarlab/
	Description:				This simple (but useful) plugin blocks internal site search spam. It can limit search query size, disallow emojis, chinese and cyrillic (russian) characters in search query.
	Version:						1.0.0
	Requires at least: 	4.3
	Author:							Dmytro Kudleichuk, RockStarLab
	Author URI:					https://profiles.wordpress.org/rockstarlab/profile/
	Text Domain:				stop-search-spam
	Domain Path:				/language
  License:						GPL v2 or later
  License URI:				https://www.gnu.org/licenses/gpl-2.0.html
*/

// If this file is called directly, abort.
defined( 'ABSPATH') or exit;

define( 'RSL_SSP_FILE', __FILE__);
define( 'RSL_SSP_PATH', trailingslashit( plugin_dir_path( RSL_SSP_FILE )));
define( 'RSL_SSP_URL', plugins_url( '/', RSL_SSP_FILE));

// Autoloader
spl_autoload_register( function( $class) {

	$prefix = 'RSL_SSP\\';
	$base_dir = RSL_SSP_PATH . 'App/';

	$len = strlen( $prefix);

	if( strncmp( $prefix, $class, $len) !== 0) {
		return;
	}

	$relative_class = substr( $class, $len);
	$file = $base_dir . str_replace( '\\', '/', $relative_class) . '.php';

	if( file_exists( $file)) {
		require $file;
	}

});

// Global point of enter
if( ! function_exists( 'RSL_SSP')) {

	function RSL_SSP() {
		return \RSL_SSP\App::getInstance();
	}

}

// Run the plugin
RSL_SSP()->run();

register_deactivation_hook( RSL_SSP_FILE, [ '\RSL_SSP\App', 'deactivate_cleanup' ] );