<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* Woodmart switch param
*/
if ( ! function_exists( 'woodmart_get_switch_param' ) ) {
	function woodmart_get_switch_param( $settings, $value ) {
		$value = ( $value == '' && isset( $settings['default'] ) ) ? $settings['default'] : $value;

        $output = '<div class="woodmart-vc-switch">';
            $output .= '<input type="hidden" class="switch-field-value wpb_vc_param_value" name="' . esc_attr( $settings['param_name'] ) . '" value="' . esc_attr( $value ) . '">';
            $output .= '<div class="woodmart-vc-switch-inner">';
                $output .= '<div class="switch-controls switch-active" data-value="' . esc_attr( $settings['true_state'] ) . '"><span>' . esc_html( 'Yes', 'woodmart' ) . '</span></div>';
                $output .= '<div class="switch-controls switch-inactive" data-value="' . esc_attr( $settings['false_state'] ) . '"><span>' . esc_html( 'No', 'woodmart' ) . '</span></div>';
            $output .= '</div>';
        $output .= '</div>';

        return $output;
    }
    
}
