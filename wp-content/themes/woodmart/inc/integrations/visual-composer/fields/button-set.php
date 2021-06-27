<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* Button set
*/
if ( ! function_exists( 'woodmart_get_button_set_param' ) ) {
	function woodmart_get_button_set_param( $settings, $value ) {
		$value = ( $value == '' && isset( $settings['default'] ) ) ? $settings['default'] : $value;

        $output = '<div class="woodmart-vc-button-set">';
            $output .= '<input type="hidden" class="woodmart-vc-button-set-value wpb_vc_param_value" name="' . esc_attr( $settings['param_name'] ) . '" value="' . esc_attr( $value ) . '">';
            $output .= '<ul class="woodmart-vc-button-set-list">';
                foreach ( $settings['value'] as $title => $value ) {
                    $output .= '<li class="vc-button-set-item" data-value="' . esc_html( $value ) . '"><span>' . esc_html( $title ) . '</span></li>';
                }
            $output .= '</ul>';
        $output .= '</div>';

        return $output;
    }

}
