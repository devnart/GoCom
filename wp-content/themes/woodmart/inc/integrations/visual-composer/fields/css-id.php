<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* CSS id
*/
if ( ! function_exists( 'woodmart_get_css_id_param' ) ) {
	function woodmart_get_css_id_param( $settings, $value ) {
	    return '<input type="hidden" name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value woodmart-css-id" value="' . esc_attr( uniqid() ) . '">';
    }
}
