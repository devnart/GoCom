<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* Woodmart title divider
*/
if ( ! function_exists( 'woodmart_get_title_divider_param' ) ) {
	function woodmart_get_title_divider_param( $settings, $value ) {
        $input = '<input id="' . esc_attr( $settings[ 'param_name' ] ) . '" class="wpb_vc_param_value" name="' . esc_attr( $settings[ 'param_name' ] ) . '" value="" type="hidden">';
        $title = isset( $settings[ 'title' ] ) ? '<div class="woodmart-td-title">' . $settings[ 'title' ] . '</div>' : '';
        $subtitle = isset( $settings[ 'subtitle' ] ) ? '<span class="woodmart-td-subtitle">' . $settings[ 'subtitle' ] . '</span>' : '';

        return $input . $title . $subtitle;
    }
    
}
