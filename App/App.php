<?php

namespace RSL_SSP;
defined( 'ABSPATH') or exit;

/**
 * Main Stop Search Spam class
 */
class App {

	private static $instance = null;
	public $view;
	public $controller;

	/**
	 * @return static
	 **/
	public static function getInstance() {
		if( is_null( static::$instance )) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	private function __construct() {

	}

	private function __clone() {
	
	}

	/**
	 * Run the core
	 **/
	public function run() {

		// Load core classes
		$this->_dispatch();

	}

	/**
	 * Load and instantiate all application
	 * classes neccessary for this theme
	 **/
	private function _dispatch() {

		$this->view = new \stdClass();
		$this->controller = new \stdClass();

		// Init view
		$this->view = new \RSL_SSP\View\View();

		// Load controllers manually
		$controllers = [
			'Init',
			'Settings',
			'Search'
		];

		$this->_load_controllers( $controllers );
	}

	/**
	 * Autoload core modules in a specific directory
	 *
	 * @param string
	 * @param string
	 * @param bool
	 **/
	private function _load_modules( $layer, $dir = '/') {

		$directory	= RSL_SSP_PATH . '/App/' . $layer . $dir;
		$handle			= opendir( $directory );

		if( count( glob( "$directory/*")) === 0) {
			return false;
		}

		while( false !== ( $file = readdir( $handle))) {

			if( is_file( $directory . $file)) {

				// Figure out class name from file name
				$class = str_replace( '.php', '', $file);

				// Avoid recursion
				if( $class !== get_class($this)) {
					$classPath = "\\RSL_SSP\\{$layer}\\{$class}";
					$this->$layer->$class = new $classPath();
				}
			}
		}
	}

	/**
	 * Autoload controllers in specific order
	 */
	private function _load_controllers( $list) {

		$directory 	= RSL_SSP_PATH . '/App/Controller/';

		foreach( $list as $controller_name) {

			if( is_file( $directory . $controller_name . '.php')) {
				$class = $controller_name;

				// Avoid recursion
				if ($class !== get_class($this)) {
					$classPath = "\\RSL_SSP\\Controller\\{$class}";
					$this->controller->$controller_name = new $classPath();
				}

			}

		}

	}

	/**
	 * Deactivate plugin hook
	 */
	public static function deactivate_cleanup() {

		delete_option( 'rls_stop_search_spam' );

	}

}