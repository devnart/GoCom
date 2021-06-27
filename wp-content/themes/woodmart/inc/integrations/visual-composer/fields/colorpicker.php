<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* Colorpicker
*/
if ( ! function_exists( 'woodmart_get_colorpicker_param' ) ) {
	function woodmart_get_colorpicker_param( $settings, $value ) {
        $output = '<div class="woodmart-vc-colorpicker" id="' . esc_attr( uniqid() ) . '">';
            $output .= '<input name="color" class="woodmart-vc-colorpicker-input" type="text">';
            $output .= '<input type="hidden" class="woodmart-vc-colorpicker-value wpb_vc_param_value" name="' . esc_attr( $settings['param_name'] ) . '" data-css_args="' . esc_attr( json_encode( $settings['css_args'] ) ) . '"  value="' . esc_attr( $value ) . '">';
        $output .= '</div>';

        return $output;
    }
}
