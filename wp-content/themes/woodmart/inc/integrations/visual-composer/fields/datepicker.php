<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* Datepicker
*/
if ( ! function_exists( 'woodmart_get_datepicker_param' ) ) {
	function woodmart_get_datepicker_param( $settings, $value ) {
        $output = '<div class="woodmart-vc-datepicker">';
            $output .= '<input type="text" autocomplete="off" class="woodmart-vc-datepicker-value textfield wpb-textinput wpb_vc_param_value" name="' . esc_attr( $settings['param_name'] ) . '" value="' . esc_attr( $value ) . '">';
        $output .= '</div>';

        return $output;
    }
}
