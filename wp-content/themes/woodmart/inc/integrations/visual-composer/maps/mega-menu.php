<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
* Mega Menu widget element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_vc_map_mega_menu' ) ) {
	function woodmart_vc_map_mega_menu() {
		if ( ! shortcode_exists( 'woodmart_mega_menu' ) ) {
			return;
		}
		
		vc_map( array(
			'name' => esc_html__( 'Mega Menu widget', 'woodmart' ),
			'base' => 'woodmart_mega_menu',
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'description' => esc_html__( 'Categories mega menu widget', 'woodmart' ),
        	'icon' => WOODMART_ASSETS . '/images/vc-icon/mega-menu-widget.svg',
			'params' => array(
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Gereral options', 'woodmart' ),
					'param_name' => 'general_divider',
				),
				array(
					'type' => 'woodmart_css_id',
					'param_name' => 'woodmart_css_id'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title', 'woodmart' ),
					'param_name' => 'title',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Choose Menu', 'woodmart' ),
					'param_name' => 'nav_menu',
					'value' => woodmart_get_menus_array(),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				woodmart_get_color_scheme_param( 6 ),
				array(
					'type' => 'woodmart_colorpicker',
					'heading' => esc_html__( 'Title background color', 'woodmart' ),
					'param_name' => 'color',
					'css_args' => array(
						'background-color' => array(
							' .widget-title',
						),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Extra options', 'woodmart' ),
					'param_name' => 'extra_divider',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'woodmart' ),
					'param_name' => 'el_class',
					'hint' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' )
				)
			),
		) );
	}
	add_action( 'vc_before_init', 'woodmart_vc_map_mega_menu' );
}
