<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
* AJAX Products tabs element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_vc_map_products_tabs' ) ) {
	function woodmart_vc_map_products_tabs() {
		if ( ! shortcode_exists( 'products_tabs' ) ) {
			return;
		}
		
		vc_map( array(
			'name' => esc_html__( 'AJAX Products tabs', 'woodmart' ),
			'base' => 'products_tabs',
			'as_parent' => array( 'only' => 'products_tab' ),
			'content_element' => true,
			'show_settings_on_create' => true,
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'description' => esc_html__( 'Product tabs for your marketplace', 'woodmart' ),
        	'icon' => WOODMART_ASSETS . '/images/vc-icon/ajax-products-tabs.svg',
			'params' => array(
				array(
					'type' => 'woodmart_css_id',
					'param_name' => 'woodmart_css_id'
				),
				/**
				* Title
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Title', 'woodmart' ),
					'param_name' => 'title_divider'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Tabs title', 'woodmart' ),
					'param_name' => 'title',
				),
				/**
				* Image
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Image settings', 'woodmart' ),
					'param_name' => 'image_divider'
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__( 'Icon image', 'woodmart' ),
					'param_name' => 'image',
					'value' => '',
					'hint' => esc_html__( 'Select image from media library.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Images size', 'woodmart' ),
					'param_name' => 'img_size',
					'hint' => esc_html__( 'Enter image size. Example: \'thumbnail\', \'medium\', \'large\', \'full\' or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use \'thumbnail\' size.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'description' => esc_html__( 'Example: \'thumbnail\', \'medium\', \'large\', \'full\' or enter image size in pixels: \'200x100\'.', 'woodmart' ),
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
					'type' => 'woodmart_colorpicker',
					'heading' => esc_html__( 'Tabs color', 'woodmart' ),
					'param_name' => 'color',
					'css_args' => array(
						'color' => array(
							'.tabs-design-simple .products-tabs-title li.active-tab-title',
							'.tabs-design-simple .owl-nav > div:hover',
							'.tabs-design-simple .wrap-loading-arrow > div:not(.disabled):hover',
						),
						'border-color' => array(
							'.tabs-design-simple .tabs-name'
						),
						'background-color' => array(
							'.tabs-design-default .products-tabs-title .tab-label:after',
							'.tabs-design-alt .products-tabs-title .tab-label:after',
						),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_image_select',
					'heading' => esc_html__( 'Design', 'woodmart' ),
					'param_name' => 'design',
					'value' => array( 
						esc_html__( 'Default', 'woodmart' ) => 'default',
						esc_html__( 'Simple', 'woodmart' ) => 'simple',
						esc_html__( 'Alternative', 'woodmart' ) => 'alt',
					),
					'images_value' => array(
						'default' => WOODMART_ASSETS_IMAGES . '/settings/ajax-tabs/default.png',
						'simple' => WOODMART_ASSETS_IMAGES . '/settings/ajax-tabs/simple.png',
						'alt' => WOODMART_ASSETS_IMAGES . '/settings/ajax-tabs/alternative.png',
					),
					'std' => 'default',
					'wood_tooltip' => true,
					'edit_field_class' => 'vc_col-xs-12 vc_column tab-design',
				),
				array(
					'type' => 'woodmart_image_select',
					'heading' => esc_html__( 'Alignment', 'woodmart' ),
					'group' => esc_html__( 'Layouts', 'woodmart' ),
					'param_name' => 'alignment',
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
					'dependency' => array(
						'element' => 'design',
						'value' => array( 'default' )
					),
					'std' => 'center',
					'wood_tooltip' => true,
					'edit_field_class' => 'vc_col-sm-6 vc_column title-align',
			),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'woodmart' ),
					'param_name' => 'el_class',
					'hint' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' )
				)
			),
		    'js_view' => 'VcColumnView'
		) );

		$woodmart_prdoucts_params = vc_map_integrate_shortcode( woodmart_get_products_shortcode_map_params(), '', '', array(
			'exclude' => array(
				'highlighted_products',
				'element_title',
				'source_divider',
				'shop_tools'
			),
		) );

		vc_map( array(
			'name' => esc_html__( 'Products tab', 'woodmart' ),
			'base' => 'products_tab',
			'as_child' => array( 'only' => 'products_tabs' ),
			'content_element' => true,
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'description' => esc_html__( 'Products block', 'woodmart' ),
        	'icon' => WOODMART_ASSETS . '/images/vc-icon/product-categories.svg',
			'params' => array_merge( array(
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Title', 'woodmart' ),
					'param_name' => 'image_divider'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title for the tab', 'woodmart' ),
					'param_name' => 'title'
				),
				/**
				* Icon
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Icon', 'woodmart' ),
					'param_name' => 'icon_divider'
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__( 'Icon for the tab', 'woodmart' ),
					'param_name' => 'icon',
					'hint' => esc_html__( 'Select icon from media library.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Icon size', 'woodmart' ),
					'param_name' => 'icon_size',
					'hint' => esc_html__( 'Enter image size. Example: \'thumbnail\', \'medium\', \'large\', \'full\' or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use \'thumbnail\' size.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'description' => esc_html__( 'Example: \'thumbnail\', \'medium\', \'large\', \'full\' or enter image size in pixels: \'200x100\'.', 'woodmart' ),
				)
			), $woodmart_prdoucts_params )
		) );

		// Necessary hooks for blog autocomplete fields
		add_filter( 'vc_autocomplete_products_tab_include_callback',	'vc_include_field_search', 10, 1 ); // Get suggestion(find). Must return an array
		add_filter( 'vc_autocomplete_products_tab_include_render',
			'vc_include_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)

		// Narrow data taxonomies
		add_filter( 'vc_autocomplete_products_tab_taxonomies_callback', 'woodmart_vc_autocomplete_taxonomies_field_search', 10, 1 );
		add_filter( 'vc_autocomplete_products_tab_taxonomies_render', 'woodmart_vc_autocomplete_taxonomies_field_render', 10, 1 );

		// Narrow data taxonomies for exclude_filter
		add_filter( 'vc_autocomplete_products_tab_exclude_filter_callback', 'vc_autocomplete_taxonomies_field_search', 10, 1 );
		add_filter( 'vc_autocomplete_products_tab_exclude_filter_render', 'vc_autocomplete_taxonomies_field_render', 10, 1 );

		add_filter( 'vc_autocomplete_products_tab_exclude_callback',	'vc_exclude_field_search', 10, 1 ); // Get suggestion(find). Must return an array
		add_filter( 'vc_autocomplete_products_tab_exclude_render', 'vc_exclude_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)
	}
	add_action( 'vc_before_init', 'woodmart_vc_map_products_tabs' );
}

// A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
if( class_exists( 'WPBakeryShortCodesContainer' ) ){
    class WPBakeryShortCode_products_tabs extends WPBakeryShortCodesContainer {

    }
}

// Replace Wbc_Inner_Item with your base name from mapping for nested element
if( class_exists( 'WPBakeryShortCode' ) ){
    class WPBakeryShortCode_products_tab extends WPBakeryShortCode {

    }
}
