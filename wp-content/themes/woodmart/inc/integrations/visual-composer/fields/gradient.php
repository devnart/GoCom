<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* Add gradient to VC 
*/
if( ! function_exists( 'woodmart_add_gradient_type' ) && apply_filters( 'woodmart_gradients_enabled', true ) ) {
	function woodmart_add_gradient_type( $settings, $value ) {
		return woodmart_get_gradient_field( $settings['param_name'], $value, true );
	}
}

if( ! function_exists( 'woodmart_get_gradient_field' ) ) {
	function woodmart_get_gradient_field( $param_name, $value, $is_VC = false ) {
		$classes = $param_name;
		$classes .= ( $is_VC ) ? ' wpb_vc_param_value' : '';
		$uniqid = uniqid();
		$output = '<div class="woodmart-grad-wrap">';
			$output .= '<div class="woodmart-grad-line" id="woodmart-grad-line' . $uniqid . '"></div>';
			$output .= '<div class="woodmart-grad-preview" id="woodmart-grad-preview' . $uniqid . '"></div>';
			$output .= '<input id="woodmart-grad-val' . $uniqid . '" class="' . $classes . '" name="' . $param_name . '"  style="display:none"  value="'.$value.'"/>';
		$output .= '</div>';

		$gradient_data = explode( '|', $value );
		$gradient_points_data = $gradient_data[0];
		$gradient_type_data = ( isset( $gradient_data[2] ) ) ? $gradient_data[2] : '';
		$gradient_direction_data = ( isset( $gradient_data[3] ) ) ? $gradient_data[3] : '';

		//Point result
		$result_point_value = '';
		if ( ! empty( $gradient_points_data ) ) {
			$points_value = explode( '/', $gradient_points_data );
			array_pop( $points_value );
			foreach ( $points_value as $key => $points_values ) {
				$points_values = explode( '-', $points_values );
				$result_point_value .= '{color:"' . esc_attr ( $points_values[0] ) . '",position:' . $points_values[1] . '},';
			}
		}else{
			$result_point_value = '{color:"rgb(60, 27, 59)",position:0},{color:"rgb(90, 55, 105)",position: 33},{color:"rgb(46, 76, 130)",position:66},{color:"rgb(29, 28, 44)",position:100}';
		}

		//Type result
		$result_type_value = ( ! empty( $gradient_type_data ) ) ? $gradient_type_data : 'linear' ;

		//Direction result
		$result_direction_value = ( ! empty( $gradient_direction_data ) ) ? $gradient_direction_data : 'left' ;


		$output .= "<script>
		jQuery( document ).ready( function() {
			var gradient_line = '#woodmart-grad-line" . $uniqid . "',
				gradient_preview = '#woodmart-grad-preview" . $uniqid . "',
				grad_val = '#woodmart-grad-val" . $uniqid . "';

			gradX(gradient_line, {
				targets: [gradient_preview],
				change: function( points, styles, type, direction ) {
				   for( i = 0; i < styles.length; ++i )  { 
				       jQuery( gradient_preview ).css( 'background-image', styles[i] );
						var points_value = '';
						jQuery( points ).each( function( index , value ){
							points_value +=  value[0] + '-' + value[1] + '/';
						})
						jQuery( grad_val ).attr( 'value', points_value + '|' + styles[i] + '|' + type + '|' + direction );
				   }
				 },
				type: \"" . $result_type_value . "\",
				direction: \"" .  $result_direction_value . "\",
				sliders: [" . $result_point_value . "]
			});
		})
		</script>";
		return $output;
	}
}
