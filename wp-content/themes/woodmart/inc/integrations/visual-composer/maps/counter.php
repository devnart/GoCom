<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
* Animated counter element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_vc_map_animated_counter' ) ) {
	function woodmart_vc_map_animated_counter() {
		if ( ! shortcode_exists( 'woodmart_counter' ) ) {
			return;
		}

		vc_map( array(
			'name' => esc_html__( 'Animated Counter', 'woodmart' ),
			'description' => esc_html__( 'Shows animated counter with label', 'woodmart' ),
			'base' => 'woodmart_counter',
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
        	'icon' => WOODMART_ASSETS . '/images/vc-icon/animated-counter.svg',
			'params' => array(
				array(
					'type' => 'woodmart_css_id',
					'param_name' => 'woodmart_css_id'
				),
				/**
				* Text
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Text', 'woodmart' ),
					'param_name' => 'text_divider'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Label', 'woodmart' ),
					'param_name' => 'label',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Actual value', 'woodmart' ),
					'param_name' => 'value',
					'hint' => esc_html__('Our final point. For ex.: 95', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
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
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Size', 'woodmart' ),
					'param_name' => 'size',
					'value' => array(
						esc_html__( 'Default', 'woodmart' ) => '',
						esc_html__( 'Small', 'woodmart' ) => 'small',
						esc_html__( 'Large', 'woodmart' ) => 'large',
						esc_html__( 'Extra large', 'woodmart' ) => 'extra-large'
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Countdown number font weight', 'woodmart' ),
					'param_name' => 'font_weight',
					'value' => array(
						'' => '',
						esc_html__( 'Ultra-Light 100', 'woodmart' ) => 100,
						esc_html__( 'Light 200', 'woodmart' ) => 200,
						esc_html__( 'Book 300', 'woodmart' ) => 300,
						esc_html__( 'Normal 400', 'woodmart' ) => 400,
						esc_html__( 'Medium 500', 'woodmart' ) => 500,
						esc_html__( 'Semi-Bold 600', 'woodmart' ) => 600,
						esc_html__( 'Bold 700', 'woodmart' ) => 700,
						esc_html__( 'Extra-Bold 800', 'woodmart' ) => 800,
						esc_html__( 'Ultra-Bold 900', 'woodmart' ) => 900,
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
					'edit_field_class' => 'vc_col-sm-6 vc_column title-align',
				),
				array(
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Color scheme', 'woodmart' ),
					'param_name' => 'color_scheme',
					'value' => array(
						esc_html__( 'Inherit', 'woodmart' ) => '',
						esc_html__( 'Light', 'woodmart' ) => 'light',
						esc_html__( 'Dark', 'woodmart' ) => 'dark',
						esc_html__( 'Custom', 'woodmart' ) => 'custom'
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_colorpicker',
					'heading' => esc_html__( 'Custom color', 'woodmart' ),
					'param_name' => 'color',
					'css_args' => array(
						'color' => array(
							'.woodmart-counter',
						),
					),
					'dependency' => array(
						'element' => 'color_scheme',
						'value' => array( 'custom' )
					) 
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
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'woodmart' ),
					'param_name' => 'css',
					'group' => esc_html__( 'Design Options', 'woodmart' )
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'woodmart' ),
					'param_name' => 'el_class',
					'hint' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' )
				)
			)
		) );
	}
	add_action( 'vc_before_init', 'woodmart_vc_map_animated_counter' );
}
