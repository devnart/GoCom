<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
* Timeline element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_vc_map_timeline_shortcode' ) ) {
	function woodmart_vc_map_timeline_shortcode() {
		if ( ! shortcode_exists( 'woodmart_timeline' ) ) {
			return;
		}

		vc_map( array(
			'name' => esc_html__( 'Timeline', 'woodmart' ),
			'base' => 'woodmart_timeline',
			'as_parent' => array( 'only' => 'woodmart_timeline_item, woodmart_timeline_breakpoint' ),
			'content_element' => true,
			'show_settings_on_create' => true,
			'description' => esc_html__( 'Timeline for the history of your product', 'woodmart' ),
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'icon' => WOODMART_ASSETS . '/images/vc-icon/timeline.svg',
			'params' => array(
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Genaral options', 'woodmart' ),
					'param_name' => 'general_divider'
				),
				array(
					'type' => 'woodmart_css_id',
					'param_name' => 'woodmart_css_id'
				),
				array(
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Line style', 'woodmart' ),
					'param_name' => 'line_style',
					'value' => array(
						esc_html__( 'Default', 'woodmart' ) => 'default',
						esc_html__( 'Dashed', 'woodmart' ) => 'dashed'
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Item style', 'woodmart' ),
					'param_name' => 'item_style',
					'value' => array(
						esc_html__( 'Default', 'woodmart' ) => 'default',
						esc_html__( 'With shadow', 'woodmart' ) => 'shadow'
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_colorpicker',
					'heading' => esc_html__( 'Color of line', 'woodmart' ),
					'param_name' => 'line_color',
					'css_args' => array(
						'background-color' => array(
							' .dot-start',
							' .dot-end',
						),
						'border-color' => array(
							' .woodmart-timeline-line',
						),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_colorpicker',
					'heading' => esc_html__( 'Color of dots', 'woodmart' ),
					'param_name' => 'dots_color',
					'css_args' => array(
						'background-color' => array(
							' .woodmart-timeline-dot',
						),
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
				),
			),
			'js_view' => 'VcColumnView'
		) );

		vc_map( array(
			'name' => esc_html__( 'Timeline item', 'woodmart'),
			'base' => 'woodmart_timeline_item',
			'as_child' => array( 'only' => 'woodmart_timeline' ),
			'content_element' => true,
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'icon' => WOODMART_ASSETS . '/images/vc-icon/timeline-item.svg',
			'params' => array(
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Genaral options', 'woodmart' ),
					'param_name' => 'general_divider'
				),
				array(
					'type' => 'woodmart_css_id',
					'param_name' => 'woodmart_css_id'
				),
				array(
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Position', 'woodmart' ),
					'param_name' => 'position',
					'value' => array(
						esc_html__( 'Left', 'woodmart' ) => 'left',
						esc_html__( 'Right', 'woodmart' ) => 'right',
						esc_html__( 'Full Width', 'woodmart' ) => 'full-width'
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_colorpicker',
					'heading' => esc_html__( 'Background color ', 'woodmart' ),
					'css_args' => array(
						'background-color' => array(
							'.wd-timeline-item',
							' .timeline-col-primary',
							' .timeline-col-secondary',
						),
						'color' => array(
							' .timeline-arrow',
						),
					),
					'param_name' => 'color_bg',
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
				),
				/**
				* Image
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Image', 'woodmart' ),
					'group' => esc_html__( 'Primary section', 'woodmart' ),
					'param_name' => 'primary_image_divider'
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__( 'Image Primary', 'woodmart' ),
					'group' => esc_html__( 'Primary section', 'woodmart' ),
					'param_name' => 'image_primary',
					'value' => '',
					'hint' => esc_html__( 'Select image from media library.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Image size', 'woodmart' ),
					'group' => esc_html__( 'Primary section', 'woodmart' ),
					'param_name' => 'img_size_primary',
					'hint' => esc_html__( 'Enter image size. Example: \'thumbnail\', \'medium\', \'large\', \'full\' or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use \'thumbnail\' size.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'description' => esc_html__( 'Example: \'thumbnail\', \'medium\', \'large\', \'full\' or enter image size in pixels: \'200x100\'.', 'woodmart' ),
				),
				/**
				* Content
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Content', 'woodmart' ),
					'group' => esc_html__( 'Primary section', 'woodmart' ),
					'param_name' => 'primary_content_divider'
				),
				array(
					'type' => 'textarea',
					'holder' => 'div',
					'heading' => esc_html__( 'Title Primary', 'woodmart' ),
					'param_name' => 'title_primary',
					'group' => esc_html__( 'Primary section', 'woodmart' ),
					'hint' => esc_html__( 'Provide the title for primary timeline item.', 'woodmart' )
				),
				array(
					'type' => 'textarea_html',
					'heading' => esc_html__( 'Content Primary', 'woodmart' ),
					'group' => esc_html__( 'Primary section', 'woodmart' ),
					'param_name' => 'content',
					'hint' => esc_html__( 'Provide the description for primary timeline item.', 'woodmart' )
				),
				/**
				* Image
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Image', 'woodmart' ),
					'group' => esc_html__( 'Secondary section', 'woodmart' ),
					'param_name' => 'secondary_image_divider'
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__( 'Image Secondary', 'woodmart' ),
					'group' => esc_html__( 'Secondary section', 'woodmart' ),
					'param_name' => 'image_secondary',
					'value' => '',
					'hint' => esc_html__( 'Select image from media library.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Image size', 'woodmart' ),
					'group' => esc_html__( 'Secondary section', 'woodmart' ),
					'param_name' => 'img_size_secondary',
					'hint' => esc_html__( 'Enter image size. Example: \'thumbnail\', \'medium\', \'large\', \'full\' or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use \'thumbnail\' size.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'description' => esc_html__( 'Example: \'thumbnail\', \'medium\', \'large\', \'full\' or enter image size in pixels: \'200x100\'.', 'woodmart' ),
				),
				/**
				* Content
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Content', 'woodmart' ),
					'group' => esc_html__( 'Secondary section', 'woodmart' ),
					'param_name' => 'secondary_content_divider'
				),
				array(
					'type' => 'textarea',
					'holder' => 'div',
					'heading' => esc_html__( 'Title Secondary', 'woodmart' ),
					'group' => esc_html__( 'Secondary section', 'woodmart' ),
					'param_name' => 'title_secondary',
					'hint' => esc_html__( 'Provide the title for secondary timeline item.', 'woodmart' ),
				),
				array(
					'type' => 'textarea',
					'heading' => esc_html__( 'Content Secondary', 'woodmart' ),
					'group' => esc_html__( 'Secondary section', 'woodmart' ),
					'param_name' => 'content_secondary',
					'hint' => esc_html__( 'Provide the description for secondary timeline item.', 'woodmart' )
				)
			),
		) );

		vc_map( array(
			'name' => esc_html__( 'Timeline breakpoint', 'woodmart'),
			'base' => 'woodmart_timeline_breakpoint',
			'as_child' => array( 'only' => 'woodmart_timeline' ),
			'content_element' => true,
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'icon' => WOODMART_ASSETS . '/images/vc-icon/timeline-breakpoint.svg',
			'params' => array(
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Genaral options', 'woodmart' ),
					'param_name' => 'general_divider'
				),
				array(
					'type' => 'woodmart_css_id',
					'param_name' => 'woodmart_css_id'
				),
				array(
					'type' => 'textfield',
					'holder' => 'div',
					'heading' => esc_html__( 'Title', 'woodmart' ),
					'param_name' => 'title',
					'hint' => esc_html__( 'Provide the title for this timeline item.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_colorpicker',
					'heading' => esc_html__( 'Background color ', 'woodmart' ),
					'param_name' => 'color_bg',
					'css_args' => array(
						'background-color' => array(
							' .woodmart-timeline-breakpoint-title',
						),
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

		// A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
		if( class_exists( 'WPBakeryShortCodesContainer' ) ){
			class WPBakeryShortCode_woodmart_timeline extends WPBakeryShortCodesContainer {}
		}

		// Replace Wbc_Inner_Item with your base name from mapping for nested element
		if( class_exists( 'WPBakeryShortCode' ) ){
			class WPBakeryShortCode_woodmart_timeline_item extends WPBakeryShortCode {}
		}
	}
	add_action( 'vc_before_init', 'woodmart_vc_map_timeline_shortcode' );
}
