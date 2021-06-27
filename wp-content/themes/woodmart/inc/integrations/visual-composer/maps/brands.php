<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
*  Brands element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_vc_map_brands' ) ) {
	function woodmart_vc_map_brands() {
		if ( ! shortcode_exists( 'woodmart_brands' ) ) {
			return;
		}

		$order_by_values = array(
			'',
			esc_html__( 'Name', 'woodmart' ) => 'name',
			esc_html__( 'Slug', 'woodmart' ) => 'slug',
			esc_html__( 'Term ID', 'woodmart' ) => 'term_id',
			esc_html__( 'ID', 'woodmart' ) => 'id',
			esc_html__( 'Random', 'woodmart' ) => 'random',
			esc_html__( 'As IDs or slugs provided order', 'woodmart' ) => 'include',
		);

		$order_way_values = array(
			'',
			esc_html__( 'Descending', 'woodmart' ) => 'DESC',
			esc_html__( 'Ascending', 'woodmart' ) => 'ASC',
		);


		vc_map( array(
			'name' => esc_html__( 'Brands', 'woodmart' ),
			'base' => 'woodmart_brands',
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'description' => esc_html__( 'Brands carousel/grid', 'woodmart' ),
        	'icon' => WOODMART_ASSETS . '/images/vc-icon/brands.svg',
			'params' => array(
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Title', 'woodmart' ),
					'param_name' => 'title_divider'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Brands title', 'woodmart' ),
					'param_name' => 'title'
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
					'type' => 'textfield',
					'heading' => esc_html__( 'Number', 'woodmart' ),
					'param_name' => 'number',
					'hint' => esc_html__( 'Enter the number of brands to display for this element.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Order by', 'woodmart' ),
					'param_name' => 'orderby',
					'value' => $order_by_values,
					'save_always' => true,
					'hint' => sprintf( wp_kses(  __( 'Select how to sort retrieved brands. More at %s.', 'woodmart' ), array( 'a' => array( 'href' => array(), 'target' => array() ) )), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Sort order', 'woodmart' ),
					'param_name' => 'order',
					'value' => $order_way_values,
					'save_always' => true,
					'hint' => sprintf( wp_kses(  __( 'Designates the ascending or descending order. More at %s.', 'woodmart' ), array( 'a' => array( 'href' => array(), 'target' => array() ) )), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>'  ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Brands', 'woodmart' ),
					'param_name' => 'ids',
					'settings' => array(
						'multiple' => true,
						'sortable' => true,
					),
					'save_always' => true,
					'hint' => esc_html__( 'List of product brands to show. Leave empty to show all', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Hide empty', 'woodmart' ),
					'param_name' => 'hide_empty',
					'true_state' => 'yes',
					'false_state' => 'no',
					'default' => 'no',
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
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Brand images hover', 'woodmart' ),
					'param_name' => 'hover',
					'save_always' => true,
					'value' => array(
						'Default' => 'default',
						'Simple' => 'simple',
						'Alternate' => 'alt',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Style', 'woodmart' ),
					'param_name' => 'brand_style',
					'save_always' => true,
					'value' => array(
						'Default' => 'default',
						'Bordered' => 'bordered',
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
					'type' => 'dropdown',
					'heading' => esc_html__( 'Layout', 'woodmart' ),
					'value' => 4,
					'param_name' => 'style',
					'save_always' => true,
					'value' => array(
						'Carousel' => 'carousel',
						'Grid' => 'grid',
						'Links List' => 'list',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_slider',
					'heading' => esc_html__( 'Slides per view', 'woodmart' ),
					'param_name' => 'per_row',
					'min' => '1',
					'max' => '8',
					'step' => '1',
					'default' => '3',
					'units' => '',
					'hint' => esc_html__( 'Set numbers of slides you want to display at the same time on slider\'s container for carousel mode.', 'woodmart' ),
					'dependency' => array(
						'element' => 'style',
						'value' => array( 'carousel' ),
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
					'default' => '3',
					'units' => '',
					'hint' => esc_html__( 'Number of columns in the grid.', 'woodmart' ),
					'dependency' => array(
						'element' => 'style',
						'value' => array( 'grid', 'list' ),
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
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Slider speed', 'woodmart' ),
					'param_name' => 'speed',
					'value' => '5000',
					'hint' => esc_html__( 'Duration of animation between slides (in ms)', 'woodmart' ),
					'dependency' => array(
						'element' => 'view',
						'value' => array( 'carousel' ),
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
			)
		) );

	}
	add_action( 'vc_before_init', 'woodmart_vc_map_brands' );
}

//Filters For autocomplete param:
//For suggestion: vc_autocomplete_[shortcode_name]_[param_name]_callback
add_filter( 'vc_autocomplete_woodmart_brands_ids_callback', 'woodmart_productBrandsAutocompleteSuggester', 10, 1 ); // Get suggestion(find). Must return an array
add_filter( 'vc_autocomplete_woodmart_brands_ids_render', 'woodmart_productBrandsRenderByIdExact', 10, 1 );

if( ! function_exists( 'woodmart_productBrandsAutocompleteSuggester' ) ) {
	function woodmart_productBrandsAutocompleteSuggester( $query, $slug = false ) {
		global $wpdb;
		$cat_id = (int) $query;
		$query = trim( $query );

		$attribute = woodmart_get_opt( 'brands_attribute' );

		$post_meta_infos = $wpdb->get_results(
			$wpdb->prepare( "SELECT a.term_id AS id, b.name as name, b.slug AS slug
						FROM {$wpdb->term_taxonomy} AS a
						INNER JOIN {$wpdb->terms} AS b ON b.term_id = a.term_id
						WHERE a.taxonomy = '%s' AND (a.term_id = '%d' OR b.slug LIKE '%%%s%%' OR b.name LIKE '%%%s%%' )",
						$attribute,
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

if( ! function_exists( 'woodmart_productBrandsRenderByIdExact' ) ) {
	function woodmart_productBrandsRenderByIdExact( $query ) {
		global $wpdb;
		$query = $query['value'];
		$cat_id = (int) $query;
		$attribute = woodmart_get_opt( 'brands_attribute' );
		$term = get_term( $cat_id, $attribute );

		return woodmart_productCategoryTermOutput( $term );
	}
}
