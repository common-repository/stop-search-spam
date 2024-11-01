<?php

namespace RSL_SSP\Controller;
defined( 'ABSPATH') or exit;

/**
 * Search Controller
 **/
class Search {

	/**
	 * Constructor
	 **/
	function __construct() {

		// stop search spam
		add_action( 'pre_get_posts', [ $this, 'modify_search_query']);

	}

	/**
	 * Modify search query to stop spam in google search console
	 */
	function modify_search_query( $query ) {

		if ( ! is_admin() && $query->is_main_query() && $query->is_search ) {

			$options = get_option( 'rls_stop_search_spam' );
			$search_string = get_search_query();

			if(
				( $options[ 'limitSearchLength'] && mb_strlen( $search_string ) > absint( $options[ 'searchLengthSize'] ) ) 
				|| ( $options[ 'disallowEmojis'] && \RSL_SSP\Helper\Utils::string_has_emojis( $search_string ) )
				|| ( $options[ 'disallowChineseCharacters'] && \RSL_SSP\Helper\Utils::string_has_chinese_chars( $search_string ) )
				|| ( $options[ 'disallowCyrillicCharacters'] && \RSL_SSP\Helper\Utils::string_has_cyrillic_chars( $search_string ) ) 
				|| ( $options[ 'disallowJapaneseCharacters'] && \RSL_SSP\Helper\Utils::string_has_japanese_chars( $search_string ) ) 
			) {

				$query->set_404();

			}

		}

	}

}