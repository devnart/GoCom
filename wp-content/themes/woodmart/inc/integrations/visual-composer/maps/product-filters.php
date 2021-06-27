<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
* Product filters
* ------------------------------------------------------------------------------------------------
*/

if ( ! function_exists( 'woodmart_vc_map_product_filters' ) ) {
	function woodmart_vc_map_product_filters() {
		if ( ! shortcode_exists( 'woodmart_product_filters' ) ) {
			return;
		}

		$attribute_array = array( '' => '' );

		if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			if ( $attribute_taxonomies ) {
				foreach ( $attribute_taxonomies as $tax ) {
					$attribute_array[ $tax->attribute_name ] = $tax->attribute_name;
				}
			}
		}

        //Product filter parent element
		vc_map( array(
			'name' => esc_html__( 'Product filters', 'woodmart' ),
			'base' => 'woodmart_product_filters',
			'class' => '',
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'description' => esc_html__( 'Add filters by category, attribute or price', 'woodmart' ),
            'icon' => WOODMART_ASSETS . '/images/vc-icon/product-filter.svg',
            'as_parent' => array( 'only' => 'woodmart_filter_categories, woodmart_filters_attribute, woodmart_filters_price_slider, woodmart_stock_status, woodmart_filters_orderby' ),
			'content_element' => true,
			'show_settings_on_create' => true,
			'params' => array(
				woodmart_get_color_scheme_param(),
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
            ),
			'js_view' => 'VcColumnView'
        ) );

        //Product filter categories
        vc_map( array(
			'name' => esc_html__( 'Filter categories', 'woodmart'),
			'base' => 'woodmart_filter_categories',
			'as_child' => array( 'only' => 'woodmart_product_filters' ),
			'content_element' => true,
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'icon' => WOODMART_ASSETS . '/images/vc-icon/product-filter-categories.svg',
			'params' => array(
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'General options', 'woodmart' ),
					'param_name' => 'general_divider'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title', 'woodmart' ),
					'param_name' => 'title',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Order by', 'woodmart' ),
					'param_name' => 'order_by',
					'value' => array(
						esc_html__( 'Name', 'woodmart' ) => 'name',
						esc_html__( 'ID', 'woodmart' ) => 'ID',
						esc_html__( 'Slug', 'woodmart' ) => 'slug',
						esc_html__( 'Count', 'woodmart' ) => 'count',
						esc_html__( 'Category order', 'woodmart' ) => 'order',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Show hierarchy', 'woodmart' ),
					'param_name' => 'hierarchical',
					'true_state' => 1,
					'false_state' => 0,
					'default' => 1,
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Hide empty categories', 'woodmart' ),
					'param_name' => 'hide_empty',
					'true_state' => 1,
					'false_state' => 0,
					'default' => 0,
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Show current category ancestors', 'woodmart' ),
					'param_name' => 'show_categories_ancestors',
					'hint' => esc_html__( 'If you visit category Man, for example, only man\'s subcategories will be shown in the page title like T-shirts, Coats, Shoes etc.', 'woodmart' ),
					'true_state' => 1,
					'false_state' => 0,
					'default' => 0,
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
				)
			),
        ) );
        
        //Product filter attribute
        vc_map( array(
			'name' => esc_html__( 'Filter attribute', 'woodmart'),
			'base' => 'woodmart_filters_attribute',
			'as_child' => array( 'only' => 'woodmart_product_filters' ),
			'content_element' => true,
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'icon' => WOODMART_ASSETS . '/images/vc-icon/product-filter-atribute.svg',
			'params' => array(
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'General options', 'woodmart' ),
					'param_name' => 'general_divider'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title', 'woodmart' ),
					'param_name' => 'title',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Attribute', 'woodmart' ),
					'param_name' => 'attribute',
					'value' => $attribute_array,
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Show in categories', 'woodmart' ),
					'param_name' => 'categories',
					'settings' => array(
						'multiple' => true,
						'sortable' => true,
					),
					'save_always' => true,
					'hint' => esc_html__( 'Choose on which categories pages you want to display this filter. Or leave empty to show on all pages.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Query type', 'woodmart' ),
					'param_name' => 'query_type',
					'hint' => esc_html__( 'If you select “AND”, you will be allowed to select only one attribute. In case of “OR”, you will be able to select multiple values.', 'woodmart' ),
					'value' => array(
						esc_html__( 'AND', 'woodmart' ) => 'and',
						esc_html__( 'OR', 'woodmart' ) => 'or',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Swatches size', 'woodmart' ),
					'param_name' => 'size',
					'value' => array(
						esc_html__( 'Normal (25px)', 'woodmart' ) => 'normal',
						esc_html__( 'Small (15px)', 'woodmart' ) => 'small',
						esc_html__( 'Large (35px)', 'woodmart' ) => 'large',
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
				)
			),
        ) );

		vc_map( array(
			'name' => esc_html__( 'Stock status', 'woodmart'),
			'base' => 'woodmart_stock_status',
			'as_child' => array( 'only' => 'woodmart_product_filters' ),
			'content_element' => true,
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'icon' => WOODMART_ASSETS . '/images/vc-icon/product-filter-atribute.svg',
			'params' => array(
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'General options', 'woodmart' ),
					'param_name' => 'general_divider'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title', 'woodmart' ),
					'param_name' => 'title',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'On Sale filter', 'woodmart' ),
					'param_name' => 'onsale',
					'true_state' => 1,
					'false_state' => 0,
					'default' => 1,
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'In Stock filter', 'woodmart' ),
					'param_name' => 'instock',
					'true_state' => 1,
					'false_state' => 0,
					'default' => 1,
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Show labels', 'woodmart' ),
					'param_name' => 'labels',
					'true_state' => 1,
					'false_state' => 0,
					'default' => 1,
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
				)
			),
		) );
        
        //Product filter price
        vc_map( array(
			'name' => esc_html__( 'Filter price', 'woodmart'),
			'base' => 'woodmart_filters_price_slider',
			'as_child' => array( 'only' => 'woodmart_product_filters' ),
			'content_element' => true,
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'icon' => WOODMART_ASSETS . '/images/vc-icon/product-filter-price.svg',
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title', 'woodmart' ),
					'param_name' => 'title',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'woodmart' ),
					'param_name' => 'el_class',
					'hint' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' )
				)
			),
		) );

        // Order by
		vc_map( array(
			'name' => esc_html__( 'Order by', 'woodmart'),
			'base' => 'woodmart_filters_orderby',
			'as_child' => array( 'only' => 'woodmart_product_filters' ),
			'content_element' => true,
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'icon' => WOODMART_ASSETS . '/images/vc-icon/product-filter-atribute.svg',
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'woodmart' ),
					'param_name' => 'el_class',
					'hint' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' )
				)
			),
		) );
        
        // A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
		if ( class_exists( 'WPBakeryShortCodesContainer' ) ){
			class WPBakeryShortCode_woodmart_product_filters extends WPBakeryShortCodesContainer {}
		}

		// Replace Wbc_Inner_Item with your base name from mapping for nested element
		if ( class_exists( 'WPBakeryShortCode' ) ){
			class WPBakeryShortCode_woodmart_filter_categories extends WPBakeryShortCode {}
		}

		// Replace Wbc_Inner_Item with your base name from mapping for nested element
		if ( class_exists( 'WPBakeryShortCode' ) ){
			class WPBakeryShortCode_woodmart_filters_attribute extends WPBakeryShortCode {}
		}

		// Replace Wbc_Inner_Item with your base name from mapping for nested element
		if ( class_exists( 'WPBakeryShortCode' ) ){
			class WPBakeryShortCode_woodmart_filters_price_slider extends WPBakeryShortCode {}
		}

		if ( class_exists( 'WPBakeryShortCode' ) ){
			class WPBakeryShortCode_woodmart_filters_orderby extends WPBakeryShortCode {}
		}
		
		if ( class_exists( 'WPBakeryShortCode' ) ){
			class WPBakeryShortCode_woodmart_stock_status extends WPBakeryShortCode {}
		}

		add_filter( 'vc_autocomplete_woodmart_filters_attribute_categories_callback', 'woodmart_productCategoryCategoryAutocompleteSuggester', 10, 1 ); 
		
		add_filter( 'vc_autocomplete_woodmart_filters_attribute_categories_render', 'woodmart_productCategoryCategoryRenderByIdExact', 10, 1 ); 

    }

	add_action( 'vc_before_init', 'woodmart_vc_map_product_filters' );
}
