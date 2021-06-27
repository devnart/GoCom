<?php
if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}
/**
* ------------------------------------------------------------------------------------------------
* Instagram element map
* ------------------------------------------------------------------------------------------------
*/

if ( ! function_exists( 'woodmart_vc_map_instagram' ) ) {
	function woodmart_vc_map_instagram() {
		if ( ! shortcode_exists( 'woodmart_instagram' ) ) {
			return;
		}

		vc_map(
			array(
				'name'        => esc_html__( 'Instagram', 'woodmart' ),
				'base'        => 'woodmart_instagram',
				'category'    => esc_html__( 'Theme elements', 'woodmart' ),
				'description' => esc_html__( 'Instagram photos', 'woodmart' ),
				'icon'        => WOODMART_ASSETS . '/images/vc-icon/instagram.svg',
				'params'      => woodmart_get_instagram_params(),
			)
		);
	}
	add_action( 'vc_before_init', 'woodmart_vc_map_instagram' );
}

if ( ! function_exists( 'woodmart_get_instagram_params' ) ) {
	function woodmart_get_instagram_params() {
		return apply_filters(
			'woodmart_get_instagram_params',
			array(
				/**
				 * Data
				 */
				array(
					'type'       => 'woodmart_title_divider',
					'holder'     => 'div',
					'title'      => esc_html__( 'Data', 'woodmart' ),
					'param_name' => 'data_divider',
				),
				array(
					'type'       => 'woodmart_button_set',
					'heading'    => esc_html__( 'Source type', 'woodmart' ),
					'param_name' => 'data_source',
					'value'      => array(
						esc_html__( 'Scrape (deprecated)', 'woodmart' ) => 'scrape',
						esc_html__( 'AJAX (deprecated)', 'woodmart' ) => 'ajax',
						esc_html__( 'API', 'woodmart' )    => 'api',
						esc_html__( 'Images', 'woodmart' ) => 'images',
					),
					'hint'       => 'API request type<br>
Scrape - parse Instagram page and take photos by username. Now deprecated and may be blocked by Instagram.<br>
AJAX - send AJAX request to the Instagram page on frontend. Works more stable then simple scrape.<br>
API - the best safe and legal option to obtain Instagram photos. Requires Instagram APP configuration. <br>
Follow our documentation <a href="https://xtemos.com/docs/woodmart/faq-guides/setup-instagram-api/" target="_blank">here</a>',
				),
				/**
				 * Images
				 */
				array(
					'type'       => 'woodmart_title_divider',
					'holder'     => 'div',
					'title'      => esc_html__( 'Images', 'woodmart' ),
					'param_name' => 'images_divider',
					'dependency' => array(
						'element' => 'data_source',
						'value'   => array( 'images' ),
					),
				),
				array(
					'type'             => 'attach_images',
					'heading'          => esc_html__( 'Images', 'woodmart' ),
					'param_name'       => 'images',
					'value'            => '',
					'hint'             => esc_html__( 'Select images from media library.', 'woodmart' ),
					'dependency'       => array(
						'element' => 'data_source',
						'value'   => array( 'images' ),
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
				),
				array(
					'type'             => 'textfield',
					'heading'          => esc_html__( 'Image size', 'woodmart' ),
					'param_name'       => 'images_size',
					'hint'             => esc_html__( 'Enter image size. Example: \'thumbnail\', \'medium\', \'large\', \'full\'. Leave empty to use \'thumbnail\' size.', 'woodmart' ),
					'dependency'       => array(
						'element' => 'data_source',
						'value'   => array( 'images' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'description'      => esc_html__( 'Example: \'thumbnail\', \'medium\', \'large\', \'full\' or enter image size in pixels: \'200x100\'.', 'woodmart' ),
				),
				array(
					'type'             => 'textfield',
					'heading'          => esc_html__( 'Images link', 'woodmart' ),
					'param_name'       => 'images_link',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'textfield',
					'heading'          => esc_html__( 'Likes limit', 'woodmart' ),
					'param_name'       => 'images_likes',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'default'          => '1000-10000',
					'description'      => 'Example: 1000-10000',
				),
				array(
					'type'             => 'textfield',
					'heading'          => esc_html__( 'Comments limit', 'woodmart' ),
					'param_name'       => 'images_comments',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'default'          => '0-1000',
					'description'      => 'Example: 0-1000',
				),
				/**
				* Content
				*/
				array(
					'type'       => 'woodmart_title_divider',
					'holder'     => 'div',
					'title'      => esc_html__( 'Content', 'woodmart' ),
					'param_name' => 'content_divider',
				),
				array(
					'type'             => 'textfield',
					'heading'          => esc_html__( 'Title', 'woodmart' ),
					'param_name'       => 'title',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'textfield',
					'heading'          => esc_html__( 'Username', 'woodmart' ),
					'hint'             => esc_html__( 'Enter your Instagram username. For example: asos', 'woodmart' ),
					'param_name'       => 'username',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'woodmart_slider',
					'heading'          => esc_html__( 'Number of photos', 'woodmart' ),
					'param_name'       => 'number',
					'value'            => array(
						'9'  => '9',
						'12' => '12',
						'11' => '11',
						'10' => '10',
						'8'  => '8',
						'7'  => '7',
						'6'  => '6',
						'5'  => '5',
						'4'  => '4',
						'3'  => '3',
						'2'  => '2',
						'1'  => '1',
					),
					'min'              => '1',
					'max'              => '30',
					'step'             => '1',
					'default'          => '9',
					'units'            => '',
					'dependency'       => array(
						'element'            => 'data_source',
						'value_not_equal_to' => array( 'images' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'woodmart_button_set',
					'heading'          => esc_html__( 'Photo size', 'woodmart' ),
					'param_name'       => 'size',
					'value'            => array(
						esc_html__( 'Medium', 'woodmart' ) => 'medium',
						esc_html__( 'Thumbnail', 'woodmart' ) => 'thumbnail',
						esc_html__( 'Large', 'woodmart' )  => 'large',
					),
					'dependency'       => array(
						'element'            => 'data_source',
						'value_not_equal_to' => array( 'images' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'       => 'textarea_html',
					'holder'     => 'div',
					'heading'    => esc_html__( 'Instagram text', 'woodmart' ),
					'param_name' => 'content',
					'skip_in'    => 'widget',
					'hint'       => esc_html__( 'Add here few words about your instagram profile.', 'woodmart' ),
				),
				/**
				* Link
				*/
				array(
					'type'       => 'woodmart_title_divider',
					'holder'     => 'div',
					'title'      => esc_html__( 'Link', 'woodmart' ),
					'param_name' => 'link_divider',
				),
				array(
					'type'             => 'dropdown',
					'heading'          => esc_html__( 'Open link in', 'woodmart' ),
					'param_name'       => 'target',
					'value'            => array(
						esc_html__( 'Current window (_self)', 'woodmart' ) => '_self',
						esc_html__( 'New window (_blank)', 'woodmart' ) => '_blank',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'textfield',
					'heading'          => esc_html__( 'Link text', 'woodmart' ),
					'param_name'       => 'link',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				/**
				* Layout
				*/
				array(
					'type'       => 'woodmart_title_divider',
					'holder'     => 'div',
					'title'      => esc_html__( 'Layout', 'woodmart' ),
					'param_name' => 'layout_divider',
				),
				array(
					'type'             => 'woodmart_button_set',
					'heading'          => esc_html__( 'Layout', 'woodmart' ),
					'param_name'       => 'design',
					'skip_in'          => 'widget',
					'value'            => array(
						esc_html__( 'Grid', 'woodmart' )   => 'grid',
						esc_html__( 'Slider', 'woodmart' ) => 'slider',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'woodmart_slider',
					'heading'          => esc_html__( 'Photos per row', 'woodmart' ),
					'param_name'       => 'per_row',
					'skip_in'          => 'widget',
					'min'              => '1',
					'max'              => '12',
					'step'             => '1',
					'default'          => '3',
					'units'            => '',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'hint'             => esc_html__( 'Number of photos per row for grid design or items in slider per view.', 'woodmart' ),
				),
				array(
					'type'             => 'woodmart_switch',
					'heading'          => esc_html__( 'Add spaces between photos', 'woodmart' ),
					'skip_in'          => 'widget',
					'param_name'       => 'spacing',
					'true_state'       => 1,
					'false_state'      => 0,
					'default'          => 0,
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'dropdown',
					'heading'          => esc_html__( 'Space between images', 'woodmart' ),
					'param_name'       => 'spacing_custom',
					'skip_in'          => 'widget',
					'value'            => array(
						'',
						0,
						2,
						6,
						10,
						20,
						30,
					),
					'dependency'       => array(
						'element' => 'spacing',
						'value'   => array( '1' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'woodmart_switch',
					'heading'          => esc_html__( 'Rounded corners for images', 'woodmart' ),
					'skip_in'          => 'widget',
					'param_name'       => 'rounded',
					'true_state'       => 1,
					'false_state'      => 0,
					'default'          => 0,
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'woodmart_switch',
					'heading'          => esc_html__( 'Hide likes and comments', 'woodmart' ),
					'skip_in'          => 'widget',
					'param_name'       => 'hide_mask',
					'true_state'       => 1,
					'false_state'      => 0,
					'default'          => 0,
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'woodmart_switch',
					'heading'          => esc_html__( 'Hide pagination control', 'woodmart' ),
					'param_name'       => 'hide_pagination_control',
					'hint'             => esc_html__( 'If "YES" pagination control will be removed', 'woodmart' ),
					'true_state'       => 'yes',
					'false_state'      => 'no',
					'default'          => 'no',
					'skip_in'          => 'widget',
					'dependency'       => array(
						'element' => 'design',
						'value'   => array( 'slider' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'woodmart_switch',
					'heading'          => esc_html__( 'Hide prev/next buttons', 'woodmart' ),
					'param_name'       => 'hide_prev_next_buttons',
					'hint'             => esc_html__( 'If "YES" prev/next control will be removed', 'woodmart' ),
					'true_state'       => 'yes',
					'false_state'      => 'no',
					'default'          => 'no',
					'skip_in'          => 'widget',
					'dependency'       => array(
						'element' => 'design',
						'value'   => array( 'slider' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				/**
				* Extra
				*/
				array(
					'type'       => 'woodmart_title_divider',
					'holder'     => 'div',
					'title'      => esc_html__( 'Extra options', 'woodmart' ),
					'param_name' => 'extra_divider',
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Extra class name', 'woodmart' ),
					'skip_in'    => 'widget',
					'param_name' => 'el_class',
					'hint'       => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' ),
				),
			)
		);
	}
}
