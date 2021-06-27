<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
* Section divider element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_vc_map_row_divider' ) ) {
	function woodmart_vc_map_row_divider() {
		if ( ! shortcode_exists( 'woodmart_row_divider' ) ) {
			return;
		}

		vc_map( array(
			'name' => esc_html__( 'Section divider', 'woodmart'),
			'base' => 'woodmart_row_divider',
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'description' => esc_html__( 'Divider for sections', 'woodmart' ),
        	'icon' => WOODMART_ASSETS . '/images/vc-icon/section-divider.svg',
			'params' => array(
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'General options', 'woodmart' ),
					'param_name' => 'general_divider'
				),
				array(
					'type' => 'woodmart_css_id',
					'param_name' => 'woodmart_css_id'
				),
				array(
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Position', 'woodmart' ),
					'param_name' => 'position',
					'value' => array(
						esc_html__( 'Top', 'woodmart' ) => 'top',
						esc_html__( 'Bottom', 'woodmart' ) => 'bottom'
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_image_select',
					'heading' => esc_html__( 'Button style', 'woodmart' ),
					'param_name' => 'style',
				    'value' => array( 
						esc_html__( 'Waves Small', 'woodmart' ) => 'waves-small',
						esc_html__( 'Waves Wide', 'woodmart' ) => 'waves-wide',
						esc_html__( 'Curved Line', 'woodmart' ) => 'curved-line',
						esc_html__( 'Triangle', 'woodmart' ) => 'triangle',
						esc_html__( 'Clouds', 'woodmart' ) => 'clouds',
						esc_html__( 'Diagonal Right', 'woodmart' ) => 'diagonal-right',
						esc_html__( 'Diagonal Left', 'woodmart' ) => 'diagonal-left',
						esc_html__( 'Half Circle', 'woodmart' ) => 'half-circle',
						esc_html__( 'Paint Stroke', 'woodmart' ) => 'paint-stroke',
						esc_html__( 'Sweet wave', 'woodmart' ) => 'sweet-wave'
					),
					'images_value' => array(
						'waves-small' => WOODMART_ASSETS_IMAGES . '/settings/dividers/waves-small.png',
						'waves-wide' => WOODMART_ASSETS_IMAGES . '/settings/dividers/waves-wide.png',
						'curved-line' => WOODMART_ASSETS_IMAGES . '/settings/dividers/curved-line.png',
						'triangle' => WOODMART_ASSETS_IMAGES . '/settings/dividers/triangle.png',
						'clouds' => WOODMART_ASSETS_IMAGES . '/settings/dividers/clouds.png',
						'diagonal-right' => WOODMART_ASSETS_IMAGES . '/settings/dividers/diagonal-right.png',
						'diagonal-left' => WOODMART_ASSETS_IMAGES . '/settings/dividers/diagonal-left.png',
						'half-circle' => WOODMART_ASSETS_IMAGES . '/settings/dividers/half-circle.png',
						'paint-stroke' => WOODMART_ASSETS_IMAGES . '/settings/dividers/paint-stroke.png',
						'sweet-wave' => WOODMART_ASSETS_IMAGES . '/settings/dividers/sweet-wave.png',
					),
					'wood_tooltip' => true,
					'std' => 'waves-small',
					'edit_field_class' => 'vc_col-xs-12 vc_column divider',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Custom height', 'woodmart' ),
					'param_name' => 'custom_height',
					'dependency' => array(
						'element' => 'style',
						'value' => array( 'curved-line', 'diagonal-right', 'half-circle', 'diagonal-left', 'sweet-wave' )
					),
					'hint' => esc_html__( 'Enter divider height (Note: CSS measurement units allowed).', 'woodmart' )
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Overlap', 'woodmart' ),
					'param_name' => 'content_overlap',
					'true_state' => 'enable',
					'false_state' => 'disable',
					'default' => 'disable',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_colorpicker',
					'heading' => esc_html__( 'Color', 'woodmart' ),
					'hint' => esc_html__( 'We recommend you to set the same color as your row’s background color for the best effect.', 'woodmart' ),
					'param_name' => 'color',
					'css_args' => array(
						'fill' => array(
							' svg',
						),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Extra options', 'woodmart' ),
					'param_name' => 'extra_divider'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'woodmart' ),
					'param_name' => 'el_class',
					'hint' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' )
				),
			),
		) );
	}
	add_action( 'vc_before_init', 'woodmart_vc_map_row_divider' );
}
