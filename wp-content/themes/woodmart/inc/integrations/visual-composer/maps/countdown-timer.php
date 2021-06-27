<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
* Countdown timer element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_vc_map_countdown_timer' ) ) {
	function woodmart_vc_map_countdown_timer() {
		if ( ! shortcode_exists( 'woodmart_countdown_timer' ) ) {
			return;
		}

		vc_map( array(
			'name' => esc_html__( 'Countdown timer', 'woodmart' ),
			'base' => 'woodmart_countdown_timer',
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'description' => esc_html__( 'Shows countdown timer', 'woodmart' ),
        	'icon' => WOODMART_ASSETS . '/images/vc-icon/countdown-timer.svg',
			'params' => array(
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Date', 'woodmart' ),
					'param_name' => 'date_divider'
				),
				array(
					'type' => 'woodmart_datepicker',
					'heading' => esc_html__( 'Date', 'woodmart' ),
					'param_name' => 'date',
					'hint' => esc_html__( 'Final date in the format Y/m/d. For example 2020/12/12 13:00', 'woodmart' ),
				),
				/**
				* Style
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Style', 'woodmart' ),
					'param_name' => 'style_divider'
				),
				array(
					'type' => 'woodmart_dropdown',
					'heading' => esc_html__( 'Style', 'woodmart' ),
					'param_name' => 'style',
					'value' => array(
						esc_html__( 'Standard', 'woodmart' ) => 'standard',
						esc_html__( 'Transparent', 'woodmart' ) => 'transparent',
						esc_html__( 'Primary color', 'woodmart' ) => 'active',
					),
					'style' => array(
						'active' => woodmart_get_color_value( 'primary-color', '#7eb934' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Color Scheme', 'woodmart' ),
					'param_name' => 'woodmart_color_scheme',
					'value' => array(
						esc_html__( 'Inherit', 'woodmart' ) => '',
						esc_html__( 'Light', 'woodmart' ) => 'light',
						esc_html__( 'Dark', 'woodmart' ) => 'dark',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_image_select',
					'heading' => esc_html__( 'Align', 'woodmart' ),
					'param_name' => 'align',
					'value' => array( 
						esc_html__( 'Left', 'woodmart' ) => 'left',
						esc_html__( 'Center', 'woodmart' ) => 'center',
						esc_html__( 'Right', 'woodmart' ) => 'right',
					),
					'images_value' => array(
						'center' => WOODMART_ASSETS_IMAGES . '/settings/align/center.jpg',
						'left' => WOODMART_ASSETS_IMAGES . '/settings/align/left.jpg',
						'right' => WOODMART_ASSETS_IMAGES . '/settings/align/right.jpg',
					),
					'std' => 'center',
					'wood_tooltip' => true,
					'hint' => esc_html__( 'Select image alignment.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column title-align',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Size', 'woodmart' ),
					'param_name' => 'size',
					'value' => array(
						esc_html__( 'Medium (24px)', 'woodmart' ) => 'medium',
						esc_html__( 'Small (20px)', 'woodmart' ) => 'small',
						esc_html__( 'Large (28px)', 'woodmart' ) => 'large',
						esc_html__( 'Extra Large (42px)', 'woodmart' ) => 'xlarge',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				/**
				* Extra
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Extra options', 'woodmart' ),
					'param_name' => 'extra_divider'
				),
				( function_exists( 'vc_map_add_css_animation' ) ) ? vc_map_add_css_animation( true ) : '',
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'woodmart' ),
					'param_name' => 'el_class',
					'hint' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' )
				)
			)
		) );
	}
	add_action( 'vc_before_init', 'woodmart_vc_map_countdown_timer' );
}
