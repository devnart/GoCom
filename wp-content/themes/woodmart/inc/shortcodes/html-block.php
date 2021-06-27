<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* ------------------------------------------------------------------------------------------------
* HTML block shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_html_block_shortcode' ) ) {
	function woodmart_html_block_shortcode($atts) {
		extract(shortcode_atts(array(
			'id' => 0
		), $atts));

		return woodmart_get_html_block($id);
	}
}
