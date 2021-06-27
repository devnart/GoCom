<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
* Categories element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_vc_shortcode_categories' ) ) {
	function woodmart_vc_shortcode_categories() {
		if ( ! shortcode_exists( 'woodmart_categories' ) ) {
			return;
		}

		$order_by_values = array(
			'',
			esc_html__( 'Date', 'woodmart' ) => 'date',
			esc_html__( 'ID', 'woodmart' ) => 'ID',
			esc_html__( 'Title', 'woodmart' ) => 'title',
			esc_html__( 'Modified', 'woodmart' ) => 'modified',
			esc_html__( 'Menu order', 'woodmart' ) => 'menu_order',
			esc_html__( 'As IDs or slugs provided order', 'woodmart' ) => 'include',
		);

		$order_way_values = array(
			esc_html__( 'Inherit', 'woodmart' ) => '',
			esc_html__( 'Descending', 'woodmart' ) => 'DESC',
			esc_html__( 'Ascending', 'woodmart' ) => 'ASC',
		);

		vc_map( array(
			'name' => esc_html__( 'Product categories', 'woodmart' ),
			'base' => 'woodmart_categories',
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'description' => esc_html__( 'Product categories grid', 'woodmart' ),
        	'icon' => WOODMART_ASSETS . '/images/vc-icon/product-categories.svg',
			'params' => array(
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
					'type' => 'textfield',
					'heading' => esc_html__( 'Number', 'woodmart' ),
					'param_name' => 'number',
					'hint' => esc_html__( 'Enter the number of categories to display for this element.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Order by', 'woodmart' ),
					'param_name' => 'orderby',
					'value' => $order_by_values,
					'save_always' => true,
					'hint' => sprintf( wp_kses(  __( 'Select how to sort retrieved categories. More at %s.', 'woodmart' ), array(
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
					'value' => $order_way_values,
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
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Categories', 'woodmart' ),
					'param_name' => 'ids',
					'settings' => array(
						'multiple' => true,
						'sortable' => true,
					),
					'save_always' => true,
					'hint' => esc_html__( 'List of product categories', 'woodmart' ),
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
					'type' => 'dropdown',
					'heading' => esc_html__( 'Layout', 'woodmart' ),
					'value' => 4,
					'param_name' => 'style',
					'save_always' => true,
					'hint' => esc_html__( 'Try out our creative styles for categories block', 'woodmart' ),
					'value' => array(
						'Default' => 'default',
						'Masonry' => 'masonry',
						'Masonry (with first wide)' => 'masonry-first',
						'Carousel' => 'carousel',
					),
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
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'hint' => esc_html__( 'Number of columns in the grid.', 'woodmart' ),
					'dependency' => array(
						'element' => 'style',
						'value' => array( 'masonry', 'default' ),
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Space between categories', 'woodmart' ),
					'param_name' => 'spacing',
					'value' => array(
						30,20,10,6,2,0
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Hide empty', 'woodmart' ),
					'hint' => esc_html__( 'Don’t display categories that don’t have any products assigned.', 'woodmart' ),
					'param_name' => 'hide_empty',
					'true_state' => 'yes',
					'false_state' => 'no',
					'default' => 'yes',
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
						'element' => 'style',
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
					'default' => '3',
					'units' => '',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'hint' => esc_html__( 'Set numbers of slides you want to display at the same time on slider\'s container for carousel mode.', 'woodmart' ),
					'dependency' => array(
						'element' => 'style',
						'value' => array( 'carousel' ),
					),
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
						'element' => 'style',
						'value' => array( 'carousel' ),
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
						'element' => 'style',
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
						'element' => 'style',
						'value' => array( 'carousel' ),
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
						'element' => 'view',
						'value' => array( 'carousel' ),
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
					'param_name' => 'design_divider',
				),
				array(
					'type' => 'woodmart_image_select',
					'heading' => esc_html__( 'Categories design', 'woodmart' ),
					'hint' => esc_html__( 'Overrides option from Theme Settings -> Shop', 'woodmart' ),
					'param_name' => 'categories_design',
					'value' => array( 
						esc_html__( 'Inherit from Theme Settings', 'woodmart' ) => 'inherit',
						esc_html__( 'Default', 'woodmart' ) => 'default',
						esc_html__( 'Alternative', 'woodmart' ) => 'alt',
						esc_html__( 'Center title', 'woodmart' ) => 'center',
						esc_html__( 'Replace title', 'woodmart' ) => 'replace-title',
					),
					'group' => esc_html__( 'Design', 'woodmart' ),
					'images_value' => array(
						'inherit' => WOODMART_ASSETS_IMAGES . '/settings/empty.jpg',
						'default' => WOODMART_ASSETS_IMAGES . '/settings/categories/default.jpg',
						'alt' => WOODMART_ASSETS_IMAGES . '/settings/categories/alt.jpg',
						'center' => WOODMART_ASSETS_IMAGES . '/settings/categories/center.jpg',
						'replace-title' => WOODMART_ASSETS_IMAGES . '/settings/categories/replace-title.jpg',
					),
				),
				array(
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Categories with shadow', 'woodmart' ),
					'param_name' => 'categories_with_shadow',
					'group' => esc_html__( 'Design', 'woodmart' ),
					'value' => array(
						esc_html__( 'Inherit', 'woodmart' ) => '',
						esc_html__( 'Enable', 'woodmart' ) => 'enable',
						esc_html__( 'Disable', 'woodmart' ) => 'disable'
					),
					'dependency' => array(
						'element' => 'categories_design',
						'value' => array( 'alt', 'default' ),
					),
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
						'element' => 'style',
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
		) );
	}
	add_action( 'vc_before_init', 'woodmart_vc_shortcode_categories' );
}

//Filters For autocomplete param:
//For suggestion: vc_autocomplete_[shortcode_name]_[param_name]_callback
add_filter( 'vc_autocomplete_woodmart_categories_ids_callback', 'woodmart_productCategoryCategoryAutocompleteSuggester', 10, 1 ); // Get suggestion(find). Must return an array
add_filter( 'vc_autocomplete_woodmart_categories_ids_render', 'woodmart_productCategoryCategoryRenderByIdExact', 10, 1 );

if( ! function_exists( 'woodmart_productCategoryCategoryAutocompleteSuggester' ) ) {
	function woodmart_productCategoryCategoryAutocompleteSuggester( $query, $slug = false ) {
		global $wpdb;
		$cat_id = (int) $query;
		$query = trim( $query );
		$post_meta_infos = $wpdb->get_results(
			$wpdb->prepare( "SELECT a.term_id AS id, b.name as name, b.slug AS slug
						FROM {$wpdb->term_taxonomy} AS a
						INNER JOIN {$wpdb->terms} AS b ON b.term_id = a.term_id
						WHERE a.taxonomy = 'product_cat' AND (a.term_id = '%d' OR b.slug LIKE '%%%s%%' OR b.name LIKE '%%%s%%' )",
				$cat_id > 0 ? $cat_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );

		$result = array();
		if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
			foreach ( $post_meta_infos as $value ) {
				$data = array();
				$data['value'] = $slug ? $value['slug'] : $value['id'];
				$data['label'] = esc_html__( 'Id', 'woodmart' ) . ': ' .
				                 $value['id'] .
				                 ( ( strlen( $value['name'] ) > 0 ) ? ' - ' . esc_html__( 'Name', 'woodmart' ) . ': ' .
				                                                      $value['name'] : '' ) .
				                 ( ( strlen( $value['slug'] ) > 0 ) ? ' - ' . esc_html__( 'Slug', 'woodmart' ) . ': ' .
				                                                      $value['slug'] : '' );
				$result[] = $data;
			}
		}

		return $result;
	}
}
if( ! function_exists( 'woodmart_productCategoryCategoryRenderByIdExact' ) ) {
	function woodmart_productCategoryCategoryRenderByIdExact( $query ) {
		global $wpdb;
		$query = $query['value'];
		$cat_id = (int) $query;
		$term = get_term( $cat_id, 'product_cat' );

		return woodmart_productCategoryTermOutput( $term );
	}
}

if( ! function_exists( 'woodmart_productCategoryTermOutput' ) ) {
	function woodmart_productCategoryTermOutput( $term ) {
		if ( !$term || !is_object( $term ) ) {
			return false;
		}

		$term_slug = $term->slug;
		$term_title = $term->name;
		$term_id = $term->term_id;

		$term_slug_display = '';
		if ( ! empty( $term_slug ) ) {
			$term_slug_display = ' - ' . esc_html__( 'Slug', 'woodmart' ) . ': ' . $term_slug;
		}

		$term_title_display = '';
		if ( ! empty( $term_title ) ) {
			$term_title_display = ' - ' . esc_html__( 'Name', 'woodmart' ) . ': ' . $term_title;
		}

		$term_id_display = esc_html__( 'Id', 'woodmart' ) . ': ' . $term_id;

		$data = array();
		$data['value'] = $term_id;
		$data['label'] = $term_id_display . $term_title_display . $term_slug_display;

		return ! empty( $data ) ? $data : false;
	}
}
