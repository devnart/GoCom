<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
* Image hotspot
* ------------------------------------------------------------------------------------------------
*/

if ( ! function_exists( 'woodmart_vc_map_image_hotspot' ) ) {
	function woodmart_vc_map_image_hotspot() {
		if ( ! shortcode_exists( 'woodmart_image_hotspot' ) ) {
			return;
		}

		vc_map( array(
			'name' => esc_html__( 'Image Hotspot', 'woodmart' ),
			'base' => 'woodmart_image_hotspot',
			'class' => '',
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'description' => esc_html__( 'Add hotspots with products to the image', 'woodmart' ),
            'icon' => WOODMART_ASSETS . '/images/vc-icon/image-map.svg',
            'as_parent' => array( 'only' => 'woodmart_hotspot' ),
			'content_element' => true,
			'show_settings_on_create' => true,
			'params' => array(
				/**
				* Image
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Image', 'woodmart' ),
					'param_name' => 'image_divider'
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__( 'Image', 'woodmart' ),
					'param_name' => 'img',
					'holder' => 'img',
					'value' => '',
					'hint' => esc_html__( 'Select images from media library.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Image size', 'woodmart' ),
					'param_name' => 'img_size',
					'hint' => esc_html__( 'Enter image size. Example: \'thumbnail\', \'medium\', \'large\', \'full\' or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use \'thumbnail\' size.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'description' => esc_html__( 'Example: \'thumbnail\', \'medium\', \'large\', \'full\' or enter image size in pixels: \'200x100\'.', 'woodmart' ),
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
					'type' => 'woodmart_image_select',
					'heading' => esc_html__( 'Hotspot icon', 'woodmart' ),
					'param_name' => 'icon',
					'value' => array( 
						esc_html__( 'Default', 'woodmart' ) => 'default',
						esc_html__( 'Alternative', 'woodmart' ) => 'alt',
					),
					'images_value' => array(
						'default' => WOODMART_ASSETS_IMAGES . '/settings/image-hotspot/default.jpg',
						'alt' => WOODMART_ASSETS_IMAGES . '/settings/image-hotspot/alt.jpg',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Hotspot action', 'woodmart' ),
					'param_name' => 'action',
					'value' =>  array(
						esc_html__( 'Hover', 'woodmart' ) => 'hover',
						esc_html__( 'Click', 'woodmart' ) => 'click',
					),
					'hint' => esc_html__( 'Open hotspot content on click or hover', 'woodmart' ),
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
				woodmart_get_color_scheme_param(),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'woodmart' ),
					'param_name' => 'el_class',
					'hint' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' )
				),
				/**
				* Design Options
				*/
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'woodmart' ),
					'param_name' => 'css',
					'group' => esc_html__( 'Design Options', 'woodmart' )
				),
            ),
			'js_view' => 'VcColumnView'
        ) );
        
        vc_map( array(
			'name' => esc_html__( 'Hotspot', 'woodmart'),
			'base' => 'woodmart_hotspot',
			'as_child' => array( 'only' => 'woodmart_image_hotspot' ),
			'content_element' => true,
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'icon' => WOODMART_ASSETS . '/images/vc-icon/image-map-hotspot.svg',
			'params' => array(
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Position', 'woodmart' ),
					'param_name' => 'title_divider'
				),	
				array(
					'type' => 'woodmart_image_hotspot',
					'heading' => esc_html__( 'Hotspot position', 'woodmart' ),
					'param_name' => 'hotspot',
				),
				/**
				* Content
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Content', 'woodmart' ),
					'param_name' => 'content_divider'
				),	
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Hotspot content', 'woodmart' ),
					'param_name' => 'hotspot_type',
					'value' =>  array(
						esc_html__( 'Product', 'woodmart' ) => 'product',
						esc_html__( 'Text', 'woodmart' ) => 'text'
					),
					'hint' => esc_html__( 'You can display any product or custom text in the hotspot content.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Hotspot dropdown side', 'woodmart' ),
					'param_name' => 'hotspot_dropdown_side',
					'value' =>  array(
						esc_html__( 'Left', 'woodmart' ) => 'left',
						esc_html__( 'Right', 'woodmart' ) => 'right',
						esc_html__( 'Top', 'woodmart' ) => 'top',
						esc_html__( 'Bottom', 'woodmart' ) => 'bottom',
					),
					'hint' => esc_html__( 'Show the content on left or right side, top or bottom.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				/**
				* Product
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Product', 'woodmart' ),
					'param_name' => 'product_divider',
					'dependency' => array(
						'element' => 'hotspot_type',
						'value' => array( 'product' )
					)
				),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Select product', 'woodmart' ),
					'param_name' => 'product_id',
					'hint' => esc_html__( 'Add products by title.', 'woodmart' ),
					'settings' => array(
						'multiple' => true,
						'sortable' => true,
						'groups' => true
					),
					'dependency' => array(
						'element' => 'hotspot_type',
						'value' => array( 'product' )
					)
				),
				/**
				* Text
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Text', 'woodmart' ),
					'param_name' => 'text_divider',
					'dependency' => array(
						'element' => 'hotspot_type',
						'value' => array( 'text' )
					)
				),
				array(
					'type' => 'textfield',
					'holder' => 'div',
					'heading' => esc_html__( 'Title', 'woodmart' ),
					'param_name' => 'title',
					'dependency' => array(
						'element' => 'hotspot_type',
						'value' => array( 'text' )
					)
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__( 'Image', 'woodmart' ),
					'param_name' => 'img',
					'value' => '',
					'hint' => esc_html__( 'Select images from media library.', 'woodmart' ),
					'dependency' => array(
						'element' => 'hotspot_type',
						'value' => array( 'text' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Image size', 'woodmart' ),
					'param_name' => 'img_size',
					'hint' => esc_html__( 'Enter image size. Example: \'thumbnail\', \'medium\', \'large\', \'full\' or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use \'thumbnail\' size.', 'woodmart' ),
					'dependency' => array(
						'element' => 'hotspot_type',
						'value' => array( 'text' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'description' => esc_html__( 'Example: \'thumbnail\', \'medium\', \'large\', \'full\' or enter image size in pixels: \'200x100\'.', 'woodmart' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Link text', 'woodmart'),
					'param_name' => 'link_text',
					'dependency' => array(
						'element' => 'hotspot_type',
						'value' => array( 'text' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'vc_link',
					'heading' => esc_html__( 'Link', 'woodmart'),
					'param_name' => 'link',
					'dependency' => array(
						'element' => 'hotspot_type',
						'value' => array( 'text' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textarea_html',
					'holder' => 'div',
					'heading' => esc_html__( 'Content', 'woodmart' ),
					'param_name' => 'content',
					'dependency' => array(
						'element' => 'hotspot_type',
						'value' => array( 'text' )
					)
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
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'woodmart' ),
					'param_name' => 'el_class',
					'hint' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' )
				),
            ),
        ) );

        // A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
		if( class_exists( 'WPBakeryShortCodesContainer' ) ){
			class WPBakeryShortCode_woodmart_image_hotspot extends WPBakeryShortCodesContainer {}
		}

		// Replace Wbc_Inner_Item with your base name from mapping for nested element
		if( class_exists( 'WPBakeryShortCode' ) ){
			class WPBakeryShortCode_woodmart_hotspot extends WPBakeryShortCode {}
		}

		//WC 3.6.0
		if ( function_exists( 'WC' ) && version_compare( WC()->version, '3.6.0', '<' ) ) {
			add_filter( 'vc_autocomplete_woodmart_hotspot_product_id_callback',	'woodmart_productIdAutocompleteSuggester', 10, 1 ); 
			add_filter( 'vc_autocomplete_woodmart_hotspot_product_id_render','woodmart_productIdAutocompleteRender', 10, 1 );
		} else {
			add_filter( 'vc_autocomplete_woodmart_hotspot_product_id_callback',	'woodmart_productIdAutocompleteSuggester_new', 10, 1 ); 
			add_filter( 'vc_autocomplete_woodmart_hotspot_product_id_render','woodmart_productIdAutocompleteRender', 10, 1 );
		}

		if ( ! function_exists( 'woodmart_productIdAutocompleteSuggester' ) ) {
			function woodmart_productIdAutocompleteSuggester( $query ) {
				global $wpdb;
				$product_id = (int) $query;
				$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.ID AS id, a.post_title AS title, b.meta_value AS sku
							FROM {$wpdb->posts} AS a
							LEFT JOIN ( SELECT meta_value, post_id  FROM {$wpdb->postmeta} WHERE `meta_key` = '_sku' ) AS b ON b.post_id = a.ID
							WHERE a.post_type = 'product' AND ( a.ID = '%d' OR b.meta_value LIKE '%%%s%%' OR a.post_title LIKE '%%%s%%' )", $product_id > 0 ? $product_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );

				$results = array();
				if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
					foreach ( $post_meta_infos as $value ) {
						$data = array();
						$data['value'] = $value['id'];
						$data['label'] = __( 'Id', 'woodmart' ) . ': ' . $value['id'] . ( ( strlen( $value['title'] ) > 0 ) ? ' - ' . __( 'Title', 'woodmart' ) . ': ' . $value['title'] : '' ) . ( ( strlen( $value['sku'] ) > 0 ) ? ' - ' . __( 'Sku', 'woodmart' ) . ': ' . $value['sku'] : '' );
						$results[] = $data;
					}
				}

				return $results;
			}
		}

		if ( ! function_exists( 'woodmart_productIdAutocompleteSuggester_new' ) ) {
			function woodmart_productIdAutocompleteSuggester_new( $query ) {
				global $wpdb;
				$product_id = (int) $query;
				$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.ID AS id, a.post_title AS title
							FROM {$wpdb->posts} AS a
							LEFT JOIN ( SELECT product_id, sku FROM {$wpdb->wc_product_meta_lookup} ) AS b ON b.product_id = a.ID
							WHERE a.post_type = 'product' AND ( a.ID = '%d' OR b.sku LIKE '%%%s%%' OR a.post_title LIKE '%%%s%%' )", $product_id > 0 ? $product_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );

				$results = array();
				if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
					foreach ( $post_meta_infos as $value ) {
						$data = array();
						$data['value'] = $value['id'];
						$data['label'] = esc_html__( 'Id', 'woodmart' ) . ': ' . $value['id'] . ( ( strlen( $value['title'] ) > 0 ) ? ' - ' . esc_html__( 'Title', 'woodmart' ) . ': ' . $value['title'] : '' ) . ( ( strlen( $value['sku'] ) > 0 ) ? ' - ' . esc_html__( 'Sku', 'woodmart' ) . ': ' . $value['sku'] : '' );
						$results[] = $data;
					}
				}

				return $results;
			}
		}

		if ( ! function_exists( 'woodmart_productIdAutocompleteRender' ) ) {
			function woodmart_productIdAutocompleteRender( $query ) {
				$query = trim( $query['value'] ); // get value from requested
				if ( ! empty( $query ) ) {
					// get product
					$product_object = wc_get_product( (int) $query );
					if ( is_object( $product_object ) ) {
						$product_sku = $product_object->get_sku();
						$product_title = $product_object->get_title();
						$product_id = $product_object->get_id();

						$product_sku_display = '';
						if ( ! empty( $product_sku ) ) {
							$product_sku_display = ' - ' . esc_html__( 'Sku', 'woodmart' ) . ': ' . $product_sku;
						}

						$product_title_display = '';
						if ( ! empty( $product_title ) ) {
							$product_title_display = ' - ' . esc_html__( 'Title', 'woodmart' ) . ': ' . $product_title;
						}

						$product_id_display = esc_html__( 'Id', 'woodmart' ) . ': ' . $product_id;

						$data = array();
						$data['value'] = $product_id;
						$data['label'] = $product_id_display . $product_title_display . $product_sku_display;

						return ! empty( $data ) ? $data : false;
					}

					return false;
				}

				return false;
			}
		}
	}

	add_action( 'vc_before_init', 'woodmart_vc_map_image_hotspot' );
}
