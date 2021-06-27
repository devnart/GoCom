<?php
if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Size guide element map
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_vc_map_size_guide' ) ) {
	function woodmart_vc_map_size_guide() {
		if ( ! shortcode_exists( 'woodmart_size_guide' ) ) {
			return;
		}

		vc_map(
			array(
				'name'        => esc_html__( 'Size guide', 'woodmart' ),
				'base'        => 'woodmart_size_guide',
				'class'       => '',
				'category'    => esc_html__( 'Theme elements', 'woodmart' ),
				'description' => esc_html__( 'Display size guide table anywhere', 'woodmart' ),
				'icon'        => WOODMART_ASSETS . '/images/vc-icon/size-guide.svg',
				'params'      => array(
					/**
					* Content
					*/
				   array(
					   'type'       => 'woodmart_title_divider',
					   'holder'     => 'div',
					   'title'      => esc_html__( 'Content', 'woodmart' ),
					   'param_name' => 'content_divider',
				   ),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Select size guide', 'woodmart' ),
						'param_name' => 'id',
						'value'      => woodmart_get_size_guides_array(),
					),
					array(
						'type' => 'woodmart_switch',
						'heading' => esc_html__( 'Show title', 'woodmart' ),
						'param_name' => 'title',
						'true_state' => 1,
						'false_state' => 0,
						'default' => 1,
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'type' => 'woodmart_switch',
						'heading' => esc_html__( 'Show description', 'woodmart' ),
						'param_name' => 'description',
						'true_state' => 1,
						'false_state' => 0,
						'default' => 1,
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					/**
					 * Extra
					 */
					array(
						'type'       => 'woodmart_title_divider',
						'holder'     => 'div',
						'title'      => esc_html__( 'Extra options', 'woodmart' ),
						'param_name' => 'extra_divider',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Extra class name', 'woodmart' ),
						'param_name' => 'el_class',
						'hint'       => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' ),
					),
					array(
						'type' => 'css_editor',
						'heading' => esc_html__( 'CSS box', 'woodmart' ),
						'param_name' => 'css',
						'group' => esc_html__( 'Design Options', 'woodmart' )
					),
				),
			)
		);
	}
	add_action( 'vc_before_init', 'woodmart_vc_map_size_guide' );
}
