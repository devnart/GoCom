<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
* Portfolio element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_vc_map_portfolio' ) ) {
	function woodmart_vc_map_portfolio() {
		if ( ! shortcode_exists( 'woodmart_portfolio' ) || woodmart_get_opt( 'disable_portfolio' ) ) {
			return;
		}

		vc_map( array(
			'name' => esc_html__( 'Portfolio', 'woodmart' ),
			'base' => 'woodmart_portfolio',
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'description' => esc_html__( 'Showcase your projects or gallery', 'woodmart' ),
        	'icon' => WOODMART_ASSETS . '/images/vc-icon/portfolio.svg',
			'params' => array(
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
					'type' => 'textfield',
					'heading' => esc_html__( 'Number of projects per page', 'woodmart' ),
					'param_name' => 'posts_per_page',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_slider',
					'heading' => esc_html__( 'Columns', 'woodmart' ),
					'param_name' => 'columns',
					'min' => '2',
					'max' => '6',
					'step' => '1',
					'default' => '3',
					'units' => '',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Space between projects', 'woodmart' ),
					'param_name' => 'spacing',
					'value' => array(
	                    0,2,6,10,20,30
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Show categories filters', 'woodmart' ),
					'param_name' => 'filters',
					'true_state' => 1,
					'false_state' => 0,
					'default' => 0,
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),

				array(
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Filters type', 'woodmart' ),
					'param_name' => 'filters_type',
					'value' => array(
						esc_html__( 'Links', 'woodmart' ) => 'links',
						esc_html__( 'Masonry', 'woodmart' ) => 'masonry',
					),
					'save_always' => true,
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'default' => 'masonry',
					'dependency'       => array(
						'element' => 'filters',
						'value' => '1',
					),
				),
				/**
				* Data settings
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Data settings', 'woodmart' ),
					'param_name' => 'data_divider'
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Categories', 'woodmart' ),
					'param_name' => 'categories',
					'value' => woodmart_get_projects_cats_array(),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Order by', 'woodmart' ),
					'param_name' => 'orderby',
					'value' => array(
						'',
						esc_html__( 'Date', 'woodmart' ) => 'date',
						esc_html__( 'ID', 'woodmart' ) => 'ID',
						esc_html__( 'Title', 'woodmart' ) => 'title',
						esc_html__( 'Modified', 'woodmart' ) => 'modified',
						esc_html__( 'Menu order', 'woodmart' ) => 'menu_order',
					),
					'save_always' => true,
					'hint' => sprintf( wp_kses(  __( 'Select how to sort retrieved projects. More at %s.', 'woodmart' ), array(
	                        'a' => array( 
	                            'href' => array(), 
	                            'target' => array()
	                        )
                    	)), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Sort order', 'woodmart' ),
					'param_name' => 'order',
					'value' => array(
						esc_html__( 'Inherit', 'woodmart' ) => '',
						esc_html__( 'Descending', 'woodmart' ) => 'DESC',
						esc_html__( 'Ascending', 'woodmart' ) => 'ASC',
					),
					'save_always' => true,
					'hint' => sprintf( wp_kses(  __( 'Designates the ascending or descending order. More at %s.', 'woodmart' ), array(
	                        'a' => array( 
	                            'href' => array(), 
	                            'target' => array()
	                        )
                    	)), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Pagination', 'woodmart' ),
					'param_name' => 'pagination',
					'value' => array(
	                    '' => '',
	                    esc_html__( 'Pagination', 'woodmart' ) => 'pagination',
	                    wp_kses( __( 'Load more button', 'woodmart' ), 'entities' ) => 'load_more',
	                    esc_html__( 'Infinit', 'woodmart' ) => 'infinit',
	                    esc_html__( 'Disable', 'woodmart' ) => 'disable',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				/**
				* Extra
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Design', 'woodmart' ),
					'group' => esc_html__( 'Design', 'woodmart' ),
					'param_name' => 'extra_divider'
				),
				array(
					'type' => 'woodmart_image_select',
					'heading' => esc_html__( 'Style', 'woodmart' ),
					'param_name' => 'style',
					'value' => array( 
						esc_html__( 'Inherit from Theme Settings', 'woodmart' ) => 'inherit',
						esc_html__( 'Show text on mouse over', 'woodmart' ) => 'hover',
						esc_html__( 'Alternative', 'woodmart' ) => 'hover-inverse',
						esc_html__( 'Text under image', 'woodmart' ) => 'text-shown',
						esc_html__( 'Mouse move parallax', 'woodmart' ) => 'parallax',
					),
					'group' => esc_html__( 'Design', 'woodmart' ),
					'images_value' => array(
						'inherit' => WOODMART_ASSETS_IMAGES . '/settings/empty.jpg',
						'hover' => WOODMART_ASSETS_IMAGES . '/settings/portfolio/hover.jpg',
						'hover-inverse' => WOODMART_ASSETS_IMAGES . '/settings/portfolio/hover-inverse.jpg',
						'text-shown' => WOODMART_ASSETS_IMAGES . '/settings/portfolio/text-shown.jpg',
						'parallax' => WOODMART_ASSETS_IMAGES . '/settings/portfolio/hover.jpg',
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Images size', 'woodmart' ),
					'group' => esc_html__( 'Design', 'woodmart' ),
					'param_name' => 'image_size',
					'hint' => esc_html__( 'Enter image size. Example: \'thumbnail\', \'medium\', \'large\', \'full\' or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use \'thumbnail\' size.', 'woodmart' ),
					'description' => esc_html__( 'Example: \'thumbnail\', \'medium\', \'large\', \'full\' or enter image size in pixels: \'200x100\'.', 'woodmart' ),
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
					'heading' => esc_html__( 'Lazy loading for images', 'woodmart' ),
					'hint' => esc_html__( 'Enable lazy loading for images for this element.', 'woodmart' ),
					'param_name' => 'lazy_loading',
					'true_state' => 'yes',
					'false_state' => 'no',
					'default' => 'no',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
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
	add_action( 'vc_before_init', 'woodmart_vc_map_portfolio' );
}
