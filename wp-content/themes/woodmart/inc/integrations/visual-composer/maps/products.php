<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
* AJAX Products tabs element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_vc_map_products' ) ) {
	function woodmart_vc_map_products() {
		if ( ! shortcode_exists( 'woodmart_products' ) ) {
			return;
		}

		vc_map( woodmart_get_products_shortcode_map_params() );
	}
	add_action( 'vc_before_init', 'woodmart_vc_map_products' );
}

if( ! function_exists( 'woodmart_get_products_shortcode_params' ) ) {
	function woodmart_get_products_shortcode_map_params() {
		return array(
			'name' => esc_html__( 'Products (grid or carousel)', 'woodmart' ),
			'base' => 'woodmart_products',
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'description' => esc_html__( 'Animated carousel with posts', 'woodmart' ),
        	'icon' => WOODMART_ASSETS . '/images/vc-icon/products-grid-or-carousel.svg',
			'params' => woodmart_get_products_shortcode_params()
		);
	}
}

if( ! function_exists( 'woodmart_get_products_shortcode_params' ) ) {
	function woodmart_get_products_shortcode_params() {
		return apply_filters( 'woodmart_get_products_shortcode_params', array(
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Title', 'woodmart' ),
					'param_name' => 'title_divider'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Element title', 'woodmart' ),
					'param_name' => 'element_title',
				),
				/**
				* Product source
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Product source', 'woodmart' ),
					'param_name' => 'source_divider'
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Data source', 'woodmart' ),
					'param_name' => 'post_type',
					'value' =>  array(
						array( 'product', esc_html__( 'All Products', 'woodmart' ) ),
						array( 'featured', esc_html__( 'Featured Products', 'woodmart' ) ),
						array( 'sale', esc_html__( 'Sale Products', 'woodmart' ) ),
						array( 'new', esc_html__( 'Products with NEW label', 'woodmart' ) ),
						array( 'bestselling', esc_html__( 'Bestsellers', 'woodmart' ) ),
						array( 'ids', esc_html__( 'List of IDs', 'woodmart' ) ),
						array( 'top_rated_products', esc_html__( 'Top Rated Products', 'woodmart' ) )
					),
					'hint' => esc_html__( 'Select content type for your grid.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Include only', 'woodmart' ),
					'param_name' => 'include',
					'hint' => esc_html__( 'Add products by title.', 'woodmart' ),
					'settings' => array(
						'multiple' => true,
						'sortable' => true,
						'groups' => true
					),
					'dependency' => array(
						'element' => 'post_type',
						'value' => array( 'ids' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				// Custom query tab
				array(
					'type' => 'textarea_safe',
					'heading' => esc_html__( 'Custom query', 'woodmart' ),
					'param_name' => 'custom_query',
					'hint' => wp_kses(  __( 'Build custom query according to <a href="http://codex.wordpress.org/Function_Reference/query_posts">WordPress Codex</a>.', 'woodmart' ), array(
	                        'a' => array( 
	                            'href' => array(), 
	                            'target' => array()
	                        )
                    	) ),
					'dependency' => array(
						'element' => 'post_type',
						'value' => array( 'custom' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Categories or tags', 'woodmart' ),
					'param_name' => 'taxonomies',
					'settings' => array(
						'multiple' => true,
						// is multiple values allowed? default false
						// 'sortable' => true, // is values are sortable? default false
						'min_length' => 1,
						// min length to start search -> default 2
						// 'no_hide' => true, // In UI after select doesn't hide an select list, default false
						'groups' => true,
						// In UI show results grouped by groups, default false
						'unique_values' => true,
						// In UI show results except selected. NB! You should manually check values in backend, default false
						'display_inline' => true,
						// In UI show results inline view, default false (each value in own line)
						'delay' => 500,
						// delay for search. default 500
						'auto_focus' => true,
						// auto focus input, default true
					),
					'param_holder_class' => 'vc_not-for-custom',
					'hint' => esc_html__( 'Enter categories, tags or custom taxonomies.', 'woodmart' ),
					'dependency' => array(
						'element' => 'post_type',
						'value_not_equal_to' => array( 'ids', 'custom' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
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
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Grid or carousel', 'woodmart' ),
					'param_name' => 'layout',
					'value' =>  array(
						esc_html__( 'Grid', 'woodmart' ) => 'grid',
	                    esc_html__( 'List', 'woodmart' ) => 'list',
	                    esc_html__( 'Carousel', 'woodmart' ) => 'carousel',
					),
					'hint' => esc_html__( 'Show products in standard grid or via slider carousel', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_slider',
					'heading' => esc_html__( 'Columns', 'woodmart' ),
					'param_name' => 'columns',
					'min' => '1',
					'max' => '6',
					'step' => '1',
					'default' => '4',
					'units' => '',
					'hint' => esc_html__( 'Number of columns in the grid.', 'woodmart' ),
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'grid' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Masonry grid', 'woodmart' ), 
					'param_name' => 'products_masonry',
					'hint' => esc_html__( 'Products may have different sizes', 'woodmart' ),
					'value' => array(
	                    esc_html__( 'Inherit', 'woodmart' ) => '',
	                    esc_html__( 'Enable', 'woodmart' ) => 'enable',
	                    esc_html__( 'Disable', 'woodmart' ) => 'disable'
					),
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'grid' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Products grid with different sizes', 'woodmart' ), 
					'hint' => esc_html__( 'In this situation, some of the products will be twice bigger in width than others. Recommended to use with 6 columns grid only.', 'woodmart' ), 
					'param_name' => 'products_different_sizes',
					'value' => array(
	                    esc_html__( 'Inherit', 'woodmart' ) => '',
	                    esc_html__( 'Enable', 'woodmart' ) => 'enable',
	                    esc_html__( 'Disable', 'woodmart' ) => 'disable'
					),
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'grid' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Space between products', 'woodmart' ),
					'param_name' => 'spacing',
					'value' => array(
						'', 30,20,10,6,2,0
					),
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'grid', 'carousel' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				/**
				* Carousel
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Carousel', 'woodmart' ),
					'param_name' => 'carousel_divider',
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'carousel' ),
					),
				),
				array(
					'type' => 'woodmart_slider',
					'heading' => esc_html__( 'Slides per view', 'woodmart' ),
					'param_name' => 'slides_per_view',
					'min' => '1',
					'max' => '8',
					'step' => '1',
					'default' => '4',
					'units' => '',
					'hint' => esc_html__( 'Set numbers of slides you want to display at the same time on slider\'s container for carousel mode. Also supports for "auto" value, in this case it will fit slides depending on container\'s width. "auto" mode doesn\'t compatible with loop mode.', 'woodmart' ),
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'carousel' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Scroll per page', 'woodmart' ),
					'param_name' => 'scroll_per_page',
					'hint' => esc_html__( 'Scroll per page not per item. This affect next/prev buttons and mouse/touch dragging.', 'woodmart' ),
					'true_state' => 'yes',
					'false_state' => 'no',
					'default' => 'no',
					'std' => 'yes',
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'carousel' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Slider autoplay', 'woodmart' ),
					'param_name' => 'autoplay',
					'hint' => esc_html__( 'Enables autoplay mode.', 'woodmart' ),
					'true_state' => 'yes',
					'false_state' => 'no',
					'default' => 'no',
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'carousel' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Slider speed', 'woodmart' ),
					'param_name' => 'speed',
					'value' => '5000',
					'hint' => esc_html__( 'Duration of animation between slides (in ms)', 'woodmart' ),
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'carousel' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Hide pagination control', 'woodmart' ),
					'param_name' => 'hide_pagination_control',
					'hint' => esc_html__( 'If "YES" pagination control will be removed', 'woodmart' ),
					'true_state' => 'yes',
					'false_state' => 'no',
					'default' => 'no',
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'carousel' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Hide prev/next buttons', 'woodmart' ),
					'param_name' => 'hide_prev_next_buttons',
					'hint' => esc_html__( 'If "YES" prev/next control will be removed', 'woodmart' ),
					'true_state' => 'yes',
					'false_state' => 'no',
					'default' => 'no',
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'carousel' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Center mode', 'woodmart' ),
					'param_name' => 'center_mode',
					'true_state' => 'yes',
					'false_state' => 'no',
					'default' => 'no',
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'carousel' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Slider loop', 'woodmart' ),
					'param_name' => 'wrap',
					'hint' => esc_html__( 'Enables loop mode.', 'woodmart' ),
					'true_state' => 'yes',
					'false_state' => 'no',
					'default' => 'no',
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'carousel' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				/**
				* Pagination
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Pagination', 'woodmart' ),
					'param_name' => 'pagination_divider'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Items per page', 'woodmart' ),
					'param_name' => 'items_per_page',
					'hint' => esc_html__( 'Number of items to show per page.', 'woodmart' ),
					'value' => '12',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Pagination', 'woodmart' ),
					'param_name' => 'pagination',
					'value' => array(
	                    esc_html__( 'Inherit', 'woodmart' ) => '',
	                    wp_kses( __( 'Load more button', 'woodmart' ), 'entities' ) => 'more-btn',
	                    esc_html__( 'Infinit scrolling', 'woodmart' ) => 'infinit',
	                    esc_html__( 'Arrows', 'woodmart' ) => 'arrows',
	                    esc_html__( 'Links', 'woodmart' ) => 'links'
					),
					'dependency' => array(
						'element' => 'layout',
						'value_not_equal_to' => array( 'carousel' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Shop tools', 'woodmart' ),
					'hint' => esc_html__( 'Per page, Sorting, Columns', 'woodmart' ),
					'param_name' => 'shop_tools',
					'true_state' => 'yes',
					'false_state' => 'no',
					'default' => 'no',
					'dependency' => array(
						'element' => 'pagination',
						'value' => array( 'links' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				/**
				* Design
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Design', 'woodmart' ),
					'group' => esc_html__( 'Design', 'woodmart' ),
					'param_name' => 'design_divider'
				),
				array(
				    'type' => 'woodmart_image_select',
				    'heading' => esc_html__( 'Products hover', 'woodmart' ),
				    'param_name' => 'product_hover',
				    'value' => array( 
						esc_html__( 'Inherit from Theme Settings', 'woodmart' ) => 'inherit',
						esc_html__( 'Full info on hover', 'woodmart' ) => 'info-alt',
						esc_html__( 'Full info on image', 'woodmart' ) => 'info',
						esc_html__( 'Icons and "add to cart" on hover', 'woodmart' ) => 'alt',
						esc_html__( 'Icons on hover', 'woodmart' ) => 'icons',
						esc_html__( 'Quick', 'woodmart' ) => 'quick',
						esc_html__( 'Show button on hover on image', 'woodmart' ) => 'button',
						esc_html__( 'Show summary on hover', 'woodmart' ) => 'base',
						esc_html__( 'Standard button', 'woodmart' ) => 'standard',
						esc_html__( 'Tiled', 'woodmart' ) => 'tiled',
					),
					'group' => esc_html__( 'Design', 'woodmart' ),
					'images_value' => array(
						'inherit' => WOODMART_ASSETS_IMAGES . '/settings/empty.jpg',
						'info-alt' => WOODMART_ASSETS_IMAGES . '/settings/hover/info-alt.jpg',
						'info' => WOODMART_ASSETS_IMAGES . '/settings/hover/info.jpg',
						'alt' => WOODMART_ASSETS_IMAGES . '/settings/hover/alt.jpg',
						'icons' => WOODMART_ASSETS_IMAGES . '/settings/hover/icons.jpg',
						'quick' => WOODMART_ASSETS_IMAGES . '/settings/hover/quick.jpg',
						'button' => WOODMART_ASSETS_IMAGES . '/settings/hover/button.jpg',
						'base' => WOODMART_ASSETS_IMAGES . '/settings/hover/base.jpg',
						'standard' => WOODMART_ASSETS_IMAGES . '/settings/hover/standard.jpg',
						'tiled' => WOODMART_ASSETS_IMAGES . '/settings/hover/tiled.jpg',
					),
				    'dependency' => array(
				        'element' => 'layout',
				        'value_not_equal_to' => array( 'list' )
				    )
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Images size', 'woodmart' ),
					'group' => esc_html__( 'Design', 'woodmart' ),
					'param_name' => 'img_size',
					'hint' => esc_html__( 'Enter image size. Example: \'thumbnail\', \'medium\', \'large\', \'full\' or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use \'thumbnail\' size.', 'woodmart' ),
					'description' => esc_html__( 'Example: \'thumbnail\', \'medium\', \'large\', \'full\' or enter image size in pixels: \'200x100\'.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Sale countdown', 'woodmart' ),
					'hint' => esc_html__( 'Countdown to the end sale date will be shown. Be sure you have set final date of the product sale price.', 'woodmart' ),
					'param_name' => 'sale_countdown',
					'true_state' => 1,
					'false_state' => 0,
					'default' => 0,
					'group' => esc_html__( 'Design', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Stock progress bar', 'woodmart' ),
					'hint' => esc_html__( 'Display a number of sold and in stock products as a progress bar.', 'woodmart' ),
					'param_name' => 'stock_progress_bar',
					'true_state' => 1,
					'false_state' => 0,
					'default' => 0,
					'group' => esc_html__( 'Design', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Highlighted products', 'woodmart' ),
					'hint' => esc_html__( 'Create an eye-catching section of special products to promote them on your store.', 'woodmart' ),
					'param_name' => 'highlighted_products',
					'true_state' => 1,
					'false_state' => 0,
					'default' => 0,
					'group' => esc_html__( 'Design', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Bordered grid', 'woodmart' ),
					'hint' => esc_html__( 'Add borders between the products in your grid', 'woodmart' ),
					'param_name' => 'products_bordered_grid',
					'true_state' => 1,
					'false_state' => 0,
					'default' => 0,
					'group' => esc_html__( 'Design', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Quantity input on product', 'woodmart' ),
					'param_name' => 'product_quantity',
					'value' => array(
						esc_html__( 'Inherit', 'woodmart' ) => '',
						esc_html__( 'Enable', 'woodmart' ) => 'enable',
						esc_html__( 'Disable', 'woodmart' ) => 'disable'
					),
					'group' => esc_html__( 'Design', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'dependency' => array(
						'element' => 'product_hover',
						'value' => array( 'standard', 'quick' ),
					),
				),
				/**
				* Data settings
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Data settings', 'woodmart' ),
					'group' => esc_html__( 'Data Settings', 'woodmart' ),
					'param_name' => 'data_tab_divider',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Order by', 'woodmart' ),
					'param_name' => 'orderby',
					'value' => array(
						'',
						esc_html__( 'Date', 'woodmart' ) => 'date',
						esc_html__( 'Order by post ID', 'woodmart' ) => 'ID',
						esc_html__( 'Author', 'woodmart' ) => 'author',
						esc_html__( 'Title', 'woodmart' ) => 'title',
						esc_html__( 'Last modified date', 'woodmart' ) => 'modified',
						esc_html__( 'Number of comments', 'woodmart' ) => 'comment_count',
						esc_html__( 'Menu order/Page Order', 'woodmart' ) => 'menu_order',
						esc_html__( 'Meta value', 'woodmart' ) => 'meta_value',
						esc_html__( 'Meta value number', 'woodmart' ) => 'meta_value_num',
						esc_html__( 'Matches same order you passed in via the include parameter.', 'woodmart') => 'post__in',
						esc_html__( 'Random order', 'woodmart' ) => 'rand',
						esc_html__( 'Price', 'woodmart' ) => 'price'
					),
					'hint' => esc_html__( 'Select order type. If "Meta value" or "Meta value Number" is chosen then meta key is required.', 'woodmart' ),
					'group' => esc_html__( 'Data Settings', 'woodmart' ),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'dependency' => array(
						'element' => 'post_type',
						'value_not_equal_to' => array( 'custom' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Offset', 'woodmart' ),
					'param_name' => 'offset',
					'hint' => esc_html__( 'Number of grid elements to displace or pass over.', 'woodmart' ),
					'group' => esc_html__( 'Data Settings', 'woodmart' ),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'dependency' => array(
						'element' => 'post_type',
						'value_not_equal_to' => array( 'ids', 'custom' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Query type', 'woodmart' ),
					'param_name' => 'query_type',
					'group' => esc_html__( 'Data Settings', 'woodmart' ),
					'value' => array(
						esc_html__( 'OR', 'woodmart' ) => 'OR',
						esc_html__( 'AND', 'woodmart' ) => 'AND'
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Sorting', 'woodmart' ),
					'param_name' => 'order',
					'group' => esc_html__( 'Data Settings', 'woodmart' ),
					'value' => array(
						esc_html__( 'Inherit', 'woodmart' ) => '',
						esc_html__( 'Descending', 'woodmart' ) => 'DESC',
						esc_html__( 'Ascending', 'woodmart' ) => 'ASC'
					),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'hint' => esc_html__( 'Select sorting order.', 'woodmart' ),
					'dependency' => array(
						'element' => 'post_type',
						'value_not_equal_to' => array( 'ids', 'custom' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Meta key', 'woodmart' ),
					'param_name' => 'meta_key',
					'hint' => esc_html__( 'Input meta key for grid ordering.', 'woodmart' ),
					'group' => esc_html__( 'Data Settings', 'woodmart' ),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'dependency' => array(
						'element' => 'orderby',
						'value' => array( 'meta_value', 'meta_value_num' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Exclude', 'woodmart' ),
					'param_name' => 'exclude',
					'hint' => esc_html__( 'Exclude posts, pages, etc. by title.', 'woodmart' ),
					'group' => esc_html__( 'Data Settings', 'woodmart' ),
					'settings' => array(
						'multiple' => true,
					),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'dependency' => array(
						'element' => 'post_type',
						'value_not_equal_to' => array( 'ids', 'custom' ),
						'callback' => 'vc_grid_exclude_dependency_callback',
					),
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
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Init carousel on scroll', 'woodmart' ),
					'hint' => esc_html__( 'This option allows you to init carousel script only when visitor scroll the page to the slider. Useful for performance optimization.', 'woodmart' ),
					'param_name' => 'scroll_carousel_init',
					'true_state' => 'yes',
					'false_state' => 'no',
					'default' => 'no',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'carousel' ),
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'woodmart' ),
					'param_name' => 'el_class',
					'hint' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' )
				)
			)
		);
	}
}

// Necessary hooks for blog autocomplete fields
add_filter( 'vc_autocomplete_woodmart_products_include_callback',	'woodmart_productIdAutocompleteSuggester_new', 10, 1 ); // Get suggestion(find). Must return an array
add_filter( 'vc_autocomplete_woodmart_products_include_render',
	'woodmart_productIdAutocompleteRender', 10, 1 ); // Render exact product. Must return an array (label,value)

// Narrow data taxonomies
add_filter( 'vc_autocomplete_woodmart_products_taxonomies_callback', 'woodmart_vc_autocomplete_taxonomies_field_search', 10, 1 );
add_filter( 'vc_autocomplete_woodmart_products_taxonomies_render', 'woodmart_vc_autocomplete_taxonomies_field_render', 10, 1 );

// Narrow data taxonomies for exclude_filter
add_filter( 'vc_autocomplete_woodmart_products_exclude_filter_callback', 'vc_autocomplete_taxonomies_field_search', 10, 1 );
add_filter( 'vc_autocomplete_woodmart_products_exclude_filter_render', 'vc_autocomplete_taxonomies_field_render', 10, 1 );

add_filter( 'vc_autocomplete_woodmart_products_exclude_callback',	'vc_exclude_field_search', 10, 1 ); // Get suggestion(find). Must return an array
add_filter( 'vc_autocomplete_woodmart_products_exclude_render', 'vc_exclude_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)

if( ! function_exists( 'woodmart_vc_autocomplete_taxonomies_field_render' ) ) {
	function woodmart_vc_autocomplete_taxonomies_field_render( $term ) {
		$vc_taxonomies_types = vc_taxonomies_types();

		$brands_attribute = woodmart_get_opt( 'brands_attribute' );

		if( !empty( $brands_attribute ) && taxonomy_exists( $brands_attribute ) ) {
			$vc_taxonomies_types[ $brands_attribute ] = $brands_attribute;
		}

		$terms = get_terms( array_keys( $vc_taxonomies_types ), array(
			'include' => array( $term['value'] ),
			'hide_empty' => false,
		) );
		$data = false;
		if ( is_array( $terms ) && 1 === count( $terms ) ) {
			$term = $terms[0];
			$data = vc_get_term_object( $term );
		}

		return $data;
	}
}

// Add other product attributes
if( ! function_exists( 'woodmart_vc_autocomplete_taxonomies_field_search' ) ) {
	function woodmart_vc_autocomplete_taxonomies_field_search( $search_string ) {
		$data = array();
		$vc_filter_by = vc_post_param( 'vc_filter_by', '' );
		$vc_taxonomies_types = strlen( $vc_filter_by ) > 0 ? array( $vc_filter_by ) : array_keys( vc_taxonomies_types() );

		$brands_attribute = woodmart_get_opt( 'brands_attribute' );

		if( !empty( $brands_attribute ) && taxonomy_exists( $brands_attribute ) ) {
			array_push($vc_taxonomies_types, $brands_attribute);
		}

		$vc_taxonomies = get_terms( $vc_taxonomies_types, array(
			'hide_empty' => false,
			'search' => $search_string,
		) );
		if ( is_array( $vc_taxonomies ) && ! empty( $vc_taxonomies ) ) {
			foreach ( $vc_taxonomies as $t ) {
				if ( is_object( $t ) ) {
					$data[] = vc_get_term_object( $t );
				}
			}
		}

		return $data;
	}
}
