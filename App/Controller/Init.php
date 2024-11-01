<?php

namespace RSL_SSP\Controller;
defined( 'ABSPATH') or exit;

/**
 * Init Controller
 **/
class Init {

	/**
	 * Constructor
	 **/
	function __construct() {

		// load plugin translations
		add_action( 'init', [ $this, 'load_translations'] );

	}

	/**
	 * Load plugin translations
	 */
	function load_translations() {

		load_plugin_textdomain( 'stop-search-spam', false, dirname( plugin_basename( RSL_SSP_FILE ) ) . '/language' );

	}

}