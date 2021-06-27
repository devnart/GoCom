<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
*  AJAX search element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_vc_map_ajax_search' ) ) {
	function woodmart_vc_map_ajax_search() {
		if ( ! shortcode_exists( 'woodmart_ajax_search' ) ) {
			return;
		}

		vc_map( array(
			'name' => esc_html__( 'AJAX Search', 'woodmart' ),
			'description' => esc_html__( 'Shows AJAX search form', 'woodmart' ),
			'base' => 'woodmart_ajax_search',
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
        	'icon' => WOODMART_ASSETS . '/images/vc-icon/ajax-search.svg',
			'params' => array(
				/**
				* Search results
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Search results', 'woodmart' ),
					'param_name' => 'results_divider'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Number results to show', 'woodmart' ),
					'param_name' => 'number',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_button_set',
					'heading' => esc_html__('Search post type', 'woodmart'), 
					'param_name' => 'search_post_type',
					'value' => array(
						esc_html__( 'Product', 'woodmart' ) => 'product',
						esc_html__( 'Post', 'woodmart' ) => 'post',
						esc_html__( 'Portfolio', 'woodmart' ) => 'portfolio',
					),
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
				woodmart_get_color_scheme_param(),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Show price', 'woodmart' ),
					'param_name' => 'price',
					'true_state' => 1,
					'false_state' => 0,
					'default' => 1,
					'edit_field_class' => 'vc_col-sm-4 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Show thumbnail', 'woodmart' ),
					'param_name' => 'thumbnail',
					'true_state' => 1,
					'false_state' => 0,
					'default' => 1,
					'edit_field_class' => 'vc_col-sm-4 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Show category', 'woodmart' ),
					'param_name' => 'category',
					'true_state' => 1,
					'false_state' => 0,
					'default' => 1,
					'edit_field_class' => 'vc_col-sm-4 vc_column',
				),
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
			),
		) );
	}
	add_action( 'vc_before_init', 'woodmart_vc_map_ajax_search' );
}
