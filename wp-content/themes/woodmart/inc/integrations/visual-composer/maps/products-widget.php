<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
*  WC products widget element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_vc_map_products_widget' ) ) {
	function woodmart_vc_map_products_widget() {
		if ( ! shortcode_exists( 'woodmart_shortcode_products_widget' ) ) {
			return;
		}

		vc_map( array(
			'name' => esc_html__( 'WC products widget', 'woodmart' ),
			'base' => 'woodmart_shortcode_products_widget',
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'description' => esc_html__( 'Small products list widget', 'woodmart' ),
        	'icon'            => WOODMART_ASSETS . '/images/vc-icon/wc-product-widget.svg',
			'params' => array(
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Title', 'woodmart' ),
					'param_name' => 'title_divider',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Widget title', 'woodmart' ),
					'param_name' => 'title',
				),
				/**
				* Data settings
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Data settings', 'woodmart' ),
					'param_name' => 'data_divider',
				),
				array(
					'type' => 'woodmart_slider',
					'heading' => esc_html__( 'Number of products to show', 'woodmart' ),
					'param_name' => 'number',
					'min' => '1',
					'max' => '7',
					'step' => '1',
					'default' => '3',
					'units' => '',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'dependency' => array(
						'element' => 'show',
						'value_not_equal_to' => array( 'product_ids' ),
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Show', 'woodmart' ),
					'param_name' => 'show',
					'value' => array(
						esc_html__( 'All Products', 'woodmart' ) => '',
						esc_html__( 'Featured Products', 'woodmart' ) => 'featured',
						esc_html__( 'On-sale Products', 'woodmart' ) => 'onsale',
						esc_html__( 'List of IDs', 'woodmart' ) => 'product_ids'

					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Include only', 'woodmart' ),
					'param_name' => 'include_products',
					'hint' => esc_html__( 'Add products by title.', 'woodmart' ),
					'settings' => array(
						'multiple' => true,
						'sortable' => true,
						'unique_values' => true,
						// In UI show results except selected. NB! You should manually check values in backend
					),
					'save_always' => true,
					'dependency' => array(
						'element' => 'show',
						'value' => array( 'product_ids' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Order by', 'woodmart' ),
					'param_name' => 'orderby',
					'value' => array(
						esc_html__( 'Date', 'woodmart' ) => 'date',
						esc_html__( 'Price', 'woodmart' ) => 'price',
						esc_html__( 'Random', 'woodmart' ) => 'rand',
						esc_html__( 'Sales', 'woodmart' ) => 'sales'
					),
					'dependency' => array(
						'element' => 'show',
						'value_not_equal_to' => array( 'product_ids' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Order', 'woodmart' ),
					'param_name' => 'order',
					'value' => array(
						esc_html__( 'ASC', 'woodmart' ) => 'asc',
						esc_html__( 'DESC', 'woodmart' ) => 'desc'
					),
					'dependency' => array(
						'element' => 'show',
						'value_not_equal_to' => array( 'product_ids' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Categories', 'woodmart' ),
					'param_name' => 'ids',
					'settings' => array(
						'multiple' => true,
						'sortable' => true
					),
					'save_always' => true,
					'hint' => esc_html__( 'List of product categories', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'dependency' => array(
						'element' => 'show',
						'value_not_equal_to' => array( 'product_ids' ),
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Images size', 'woodmart' ),
					'param_name' => 'images_size',
					'hint' => esc_html__( 'Enter image size. Example: \'thumbnail\', \'medium\', \'large\', \'full\' or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use \'thumbnail\' size.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'description' => esc_html__( 'Example: \'thumbnail\', \'medium\', \'large\', \'full\' or enter image size in pixels: \'200x100\'.', 'woodmart' ),
				),
				/**
				* Extra
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Extra options', 'woodmart' ),
					'param_name' => 'extra_divider',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Hide free products', 'woodmart' ),
					'param_name' => 'hide_free',
					'true_state' => 1,
					'false_state' => 0,
					'default' => 0,
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Show hidden products', 'woodmart' ),
					'param_name' => 'show_hidden',
					'true_state' => 1,
					'false_state' => 0,
					'default' => 0,
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'woodmart' ),
					'param_name' => 'el_class',
					'hint' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' )
				),
			),
		));

		//Filters For autocomplete param:
		//For suggestion: vc_autocomplete_[shortcode_name]_[param_name]_callback
		add_filter( 'vc_autocomplete_woodmart_shortcode_products_widget_ids_callback', 'woodmart_productCategoryCategoryAutocompleteSuggester', 10, 1 ); // Get suggestion(find). Must return an array
		add_filter( 'vc_autocomplete_woodmart_shortcode_products_widget_ids_render', 'woodmart_productCategoryCategoryRenderByIdExact', 10, 1 );

		add_filter( 'vc_autocomplete_woodmart_shortcode_products_widget_include_products_callback',	'woodmart_productIdAutocompleteSuggester', 10, 1 ); 
		add_filter( 'vc_autocomplete_woodmart_shortcode_products_widget_include_products_render',	'woodmart_productIdAutocompleteRender', 10, 1 ); 
	}
	add_action( 'vc_before_init', 'woodmart_vc_map_products_widget' );
}
