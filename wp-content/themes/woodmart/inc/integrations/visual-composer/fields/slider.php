<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* Woodmart slider param
*/
if ( ! function_exists( 'woodmart_get_slider_param' ) ) {
	function woodmart_get_slider_param( $settings, $value ) {
        $value = ! $value ? $settings['default'] : $value;
        $output = '<div class="woodmart-vc-slider">';
		    $output .= '<div class="woodmart-slider-field"></div>';
            $output .= '<input type="hidden" class="woodmart-slider-field-value wpb_vc_param_value" name="' . esc_attr( $settings['param_name'] ) . '" id="' . esc_attr( $settings['param_name'] ) . '" value="' . esc_attr( $value ) . '" data-start="' . esc_attr( $value ) . '" data-min="' . esc_attr( $settings['min'] ) . '" data-max="' . esc_attr( $settings['max'] ) . '" data-step="' . esc_attr( $settings['step'] ) . '">';
            $output .= '<span class="woodmart-slider-field-value-display"><span class="woodmart-slider-field-value-text"></span>' . esc_attr( $settings['units'] ) . '</span>';
        $output .= '</div>';

        return $output;
    }
    
}
