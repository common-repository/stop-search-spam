<?php

namespace RSL_SSP\View;
defined( 'ABSPATH') or exit;

/**
 * View
 **/
class View {

	/**
	 * Load view. Used on back-end side
	 *
	 * @throws \Exception
	 **/
	function load( $path = '', $data = [], $return = false, $base = null ) {

		if( is_null( $base ) ) {
			$base = RSL_SSP_PATH . '/App/View/';
		} else {
			$base = wp_normalize_path( RSL_SSP_PATH. '/' . $base );
		}

		$full_path = $base . $path . '.php';

		if( $return ) {
			ob_start();
		}

		if( file_exists( $full_path ) ) {
			require $full_path;
		} else {
			throw new \Exception( 'The view path ' . $full_path . ' can not be found.' );
		}

		if( $return ) {
			return ob_get_clean();
		}

	}

}