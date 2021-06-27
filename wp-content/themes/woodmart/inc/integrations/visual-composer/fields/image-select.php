<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* Add image select
*/
if( ! function_exists( 'woodmart_add_image_select_type' ) ) {
	function woodmart_add_image_select_type( $settings, $value ) {
		$settings_value = array_flip( $settings['value'] );
		$value = ( ! $value && isset( $settings['std'] ) ) ? $settings['std'] : $value;
		$tooltip = ( isset( $settings['wood_tooltip'] ) ) ? $settings['wood_tooltip'] : false;
		$title = ( isset( $settings['title'] ) ) ? $settings['title'] : true;
		$classes = $tooltip ? 'woodmart-css-tooltip' : '';
		$classes .= ! $tooltip && $title ? ' with-title' : '';

		$output = '<ul class="woodmart-vc-image-select">';
			$output .= '<input type="hidden" class="woodmart-vc-image-select-input wpb_vc_param_value" name="' . esc_attr( $settings['param_name'] ) . '" value="' . esc_attr( $value ) . '">';
			foreach ( $settings['value'] as $key => $value ) {
				$output .= '<li data-value="' . esc_attr( $value ) . '" class="' . esc_attr( $classes ) . '" data-text="' . esc_html( $settings_value[$value] ) . '">';
				$output .= '<img src="' . esc_url( $settings['images_value'][$value] ) . '">';
				if ( ! $tooltip && $title ) {
					$output .= '<h4>' . esc_html( $settings_value[$value] ) . '</h4>';
				}
				$output .= '</li>';
			}
		$output .= '</ul>';

		return $output;
	}
}
