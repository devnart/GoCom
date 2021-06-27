<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
* Promo Banner element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_vc_map_responsive_text_block' ) ) {
	function woodmart_vc_map_responsive_text_block() {
		if ( ! shortcode_exists( 'woodmart_responsive_text_block' ) ) {
			return;
		}

		$secondary_font = woodmart_get_opt( 'secondary-font' );
		$primary_font = woodmart_get_opt( 'primary-font' );
		$text_font = woodmart_get_opt( 'text-font' );

		$secondary_font_title = isset( $secondary_font[0] ) ? esc_html__( 'Secondary', 'woodmart' ) . ' (' . $secondary_font[0]['font-family'] . ')' : esc_html__( 'Secondary', 'woodmart' );
		$text_font_title = isset( $text_font[0] ) ? esc_html__( 'Text', 'woodmart' ) . ' (' . $text_font[0]['font-family'] . ')' : esc_html__( 'Text', 'woodmart' );
		$primary_font_title = isset( $primary_font[0] ) ? esc_html__( 'Primary', 'woodmart' ) . ' (' . $primary_font[0]['font-family'] . ')' : esc_html__( 'Primary', 'woodmart' );

		vc_map( array(
			'name' => esc_html__( 'Responsive text block', 'woodmart' ),
			'base' => 'woodmart_responsive_text_block',
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'description' => esc_html__( 'A block of text with responsive text sizes', 'woodmart' ),
        	'icon' => WOODMART_ASSETS . '/images/vc-icon/text-blox-res.svg',
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
					'type' => 'textarea_html',
					'holder' => 'div',
					'heading' => esc_html__( 'Text', 'woodmart' ),
					'param_name' => 'content'
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Text font', 'woodmart' ),
					'param_name' => 'font',
					'value' => array(
						$primary_font_title => 'primary',
						$text_font_title => 'text',
						$secondary_font_title => 'alt'
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Font size', 'woodmart' ),
					'param_name' => 'size',
					'value' => array(
						esc_html__( 'Default (22px)', 'woodmart' ) => 'default',
						esc_html__( 'Small (18px)', 'woodmart' ) => 'small',
						esc_html__( 'Medium (26px)', 'woodmart' ) => 'medium',
						esc_html__( 'Large (36px)', 'woodmart' ) => 'large',
						esc_html__( 'Extra Large (46px)', 'woodmart' ) => 'extra-large',
						esc_html__( 'Custom', 'woodmart' ) => 'custom'
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_responsive_size',
					'heading' => esc_html__( 'Size', 'woodmart' ),
					'param_name' => 'text_font_size',
					'css_args' => array(
						'font-size' => array(
							' .woodmart-text-block',
						),
					),
					'dependency' => array(
						'element' => 'size',
						'value' => array( 'custom' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column', 
				),
				array(
					'type' => 'woodmart_responsive_size',
					'heading' => esc_html__( 'Line height', 'woodmart' ),
					'param_name' => 'text_line_height',
					'css_args' => array(
						'line-height' => array(
							' .woodmart-text-block',
						),
					),
					'dependency' => array(
						'element' => 'size',
						'value' => array( 'custom' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column', 
				),
				array(
					'type' => 'woodmart_empty_space',
					'param_name' => 'woodmart_empty_space',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Font weight', 'woodmart' ),
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
					'type' => 'woodmart_dropdown',
					'heading' => esc_html__( 'Color scheme', 'woodmart' ),
					'param_name' => 'color_scheme',
					'value' => array(
						'' => '',
						esc_html__( 'Light', 'woodmart' ) => 'light',
						esc_html__( 'Dark', 'woodmart' ) => 'dark',
						esc_html__( 'Custom', 'woodmart' ) => 'custom'
					),
					'style' => array(
						'dark' => '#2d2a2a',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_colorpicker',
					'heading' => esc_html__( 'Custom Color', 'woodmart' ),
					'param_name' => 'color',
					'css_args' => array(
						'color' => array(
							' .woodmart-text-block',
						),
					),
					'dependency' => array(
						'element' => 'color_scheme',
						'value' => array( 'custom' )
					),
				),
				/**
				* Layout
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Layout', 'woodmart' ),
					'param_name' => 'layout_divider'
				),
				array(
					'type' => 'woodmart_image_select',
					'heading' => esc_html__( 'Text align', 'woodmart' ),
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
					'type' => 'woodmart_slider',
					'heading' => esc_html__( 'Content width', 'woodmart' ),
					'param_name' => 'content_width',
					'min' => '10',
					'max' => '100',
					'step' => '10',
					'default' => '100',
					'units' => '%',
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
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Display inline', 'woodmart' ),
					'param_name' => 'inline',
					'true_state' => 'yes',
					'false_state' => 'no',
					'default' => 'no',
				),
				( function_exists( 'vc_map_add_css_animation' ) ) ? vc_map_add_css_animation( true ) : '',
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'woodmart' ),
					'param_name' => 'el_class',
					'hint' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' )
				),
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'woodmart' ),
					'param_name' => 'css',
					'group' => esc_html__( 'Design Options', 'woodmart' )
				),
				/**
				* Custom sizes
				*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Desktop text size ( > 1024px )', 'woodmart' ),
					'param_name' => 'desktop_text_size',
					'hint' => esc_html__( 'Only number without px.', 'woodmart' ),
					'group' => esc_html__( 'Custom size', 'woodmart' ),
					'dependency' => array(
						'element' => 'size',
						'value' => array( 'custom' )
					) 
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Tablet text size ( < 1024px )', 'woodmart' ),
					'param_name' => 'tablet_text_size',
					'hint' => esc_html__( 'Only number without px.', 'woodmart' ),
					'group' => esc_html__( 'Custom size', 'woodmart' ),
					'dependency' => array(
						'element' => 'size',
						'value' => array( 'custom' )
					) 	
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Mobile text size ( < 767px )', 'woodmart' ),
					'param_name' => 'mobile_text_size',
					'hint' => esc_html__( 'Only number without px.', 'woodmart' ),
					'group' => esc_html__( 'Custom size', 'woodmart' ),
					'dependency' => array(
						'element' => 'size',
						'value' => array( 'custom' )
					) 
				),
			),
		) );
	}
	add_action( 'vc_before_init', 'woodmart_vc_map_responsive_text_block' );
}
