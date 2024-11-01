<?php

namespace RSL_SSP\Controller;
defined( 'ABSPATH') or exit;

/**
 * Settings Controller
 **/
class Settings {

	/**
	 * Constructor
	 **/
	function __construct() {

		// add settings in admin page
		add_action( 'admin_menu', [ $this, 'add_settings_page'] );

		// load admin scripts and styles
		add_action( 'admin_enqueue_scripts', [ $this, 'load_admin_assets'] );

		// register plugin settings
		add_action( 'init', [ $this, 'register_settings'] );

		// add "Settings" link in plugins table
		add_filter( 'plugin_action_links_' . plugin_basename( RSL_SSP_FILE ), [ $this, 'add_plugin_settings_link'] );

	}

	/**
	 * Add settings page in WP admin panel 
	 */
	function add_settings_page() {

		add_options_page(
			__( 'Stop Search Spam', 'stop-search-spam' ),
			__( 'Stop Search Spam', 'stop-search-spam' ),
			'manage_options',
			'stop-search-spam',
			[ $this, 'load_settings_page']
		);

	}

	/**
	 * Load the view for Settings page
	 */
	function load_settings_page() {

		RSL_SSP()->view->load( 'settings/general');

	}

	/**
	 * Load admin scripts
	 */
	function load_admin_assets( $admin_page ) {

		if( 'settings_page_stop-search-spam' !== $admin_page ) {
			return;
		}

		$asset_file = RSL_SSP_PATH . 'build/index.asset.php';

		if( ! file_exists( $asset_file ) ) {
			return;
		}

		$asset = include $asset_file;

		wp_enqueue_script(
			'rsl-stop-search-spam-script',
			plugins_url( 'build/index.js', RSL_SSP_FILE ),
			$asset['dependencies'],
			$asset['version'],
			[
				'in_footer' => true,
			]
		);

		wp_enqueue_style( 'wp-components' );

    wp_enqueue_style(
			'rsl-stop-search-spam-style',
			plugins_url( 'build/index.css', RSL_SSP_FILE ),
			array_filter(
				$asset['dependencies'],
				function ( $style ) {
					return wp_style_is( $style, 'registered' );
				}
			),
			$asset['version'],
		);

	}

	/**
	 * Save plugin settings
	 */
	function register_settings() {

		$default = [
			'limitSearchLength' => false,
			'searchLengthSize' => 100,
			'disallowEmojis' => false,
			'disallowChineseCharacters' => false,
			'disallowCyrillicCharacters' => false,
		];

		$schema = [
			'type' => 'object',
			'properties' => [
				'limitSearchLength' => [
					'type' => 'boolean',
				],
				'searchLengthSize' => [
					'type' => 'number',
				],
				'disallowEmojis' => [
					'type' => 'boolean',
				],
				'disallowChineseCharacters' => [
					'type' => 'boolean',
				],
				'disallowCyrillicCharacters' => [
					'type' => 'boolean',
				],
				'disallowJapaneseCharacters' => [
					'type' => 'boolean',
				],
			],
		];

		register_setting(
			'options',
			'rls_stop_search_spam',
			[
				'type' => 'object',
				'default' => $default,
				'show_in_rest' => [
					'schema' => $schema,
				],
			]
		);

	}

	/**
	 * Add Settings link in Plugins table
	 */
	function add_plugin_settings_link( $links ) {

		array_push( $links, '<a href="' . admin_url( '/options-general.php?page=stop-search-spam') . '">' . __( 'Settings', 'stop-search-spam') . '</a>' );

		return $links;
	}

}