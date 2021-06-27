<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* Empty space
*/
if ( ! function_exists( 'woodmart_get_empty_space_param' ) ) {
	function woodmart_get_empty_space_param( $settings, $value ) {
	    return '<div class="woodmart-vc-empty-space ' . esc_attr( $settings['param_name'] ) . '"></div>';
    }
}
