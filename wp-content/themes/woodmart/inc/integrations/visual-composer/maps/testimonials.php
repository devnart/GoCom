<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
* Testimonials element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_vc_map_testimonials' ) ) {
	function woodmart_vc_map_testimonials() {
		if ( ! shortcode_exists( 'testimonials' ) ) {
			return;
		}
		
		vc_map( array(
			'name' => esc_html__( 'Testimonials', 'woodmart' ),
			'base' => 'testimonials',
			'as_parent' => array( 'only' => 'testimonial' ),
			'content_element' => true,
			'show_settings_on_create' => false,
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'description' => esc_html__( 'User testimonials slider or grid', 'woodmart' ),
        	'icon' => WOODMART_ASSETS . '/images/vc-icon/testimonials.svg',
			'params' => array(
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Title', 'woodmart' ),
					'param_name' => 'title_divider'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Element title', 'woodmart' ),
					'param_name' => 'title',
					'value' => '',
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
					'heading' => esc_html__( 'Layout', 'woodmart' ),
					'param_name' => 'layout',
					'value' => array(
						esc_html__( 'Slider', 'woodmart' ) => 'slider',
						esc_html__( 'Grid', 'woodmart' ) => 'grid'
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
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'grid' )
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Space between testimonial', 'woodmart' ),
					'param_name' => 'spacing',
					'value' => array(
						30,20,10,6,2,0
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				/**
				* Slider
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Slider', 'woodmart' ),
					'param_name' => 'slider_divider',
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'slider' ),
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
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'slider' )
					),
					'hint' => esc_html__( 'Set numbers of slides you want to display at the same time on slider\'s container for carousel mode.', 'woodmart' ),
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
						'value' => array( 'slider' )
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
						'value' => array( 'slider' )
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
						'value' => array( 'slider' )
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
						'value' => array( 'slider' )
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
						'value' => array( 'slider' ),
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
						'value' => array( 'slider' )
					),
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
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Style', 'woodmart' ),
					'param_name' => 'style',
					'value' => array(
						esc_html__( 'Standard', 'woodmart' ) => 'standard',
						esc_html__( 'Boxed', 'woodmart' ) => 'boxed'
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Text size', 'woodmart' ),
					'param_name' => 'text_size',
					'value' => array(
						esc_html__( 'Default', 'woodmart' ) => '',
						esc_html__( 'Small (14px)', 'woodmart' ) => 'small',
						esc_html__( 'Medium (16px)', 'woodmart' ) => 'medium',
						esc_html__( 'Large (18px)', 'woodmart' ) => 'large'
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_image_select',
					'heading' => esc_html__( 'Align', 'woodmart' ),
					'param_name' => 'align',
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
					'std' => 'center',
					'wood_tooltip' => true,
					'edit_field_class' => 'vc_col-sm-6 vc_column title-align',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Display stars rating', 'woodmart' ),
					'param_name' => 'stars_rating',
					'true_state' => 'yes',
					'false_state' => 'no',
					'default' => 'yes',
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
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'woodmart' ),
					'param_name' => 'el_class',
					'hint' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' )
				)
			),
		    'js_view' => 'VcColumnView'
		) );

		vc_map( array(
			'name' => esc_html__( 'Testimonial', 'woodmart' ),
			'base' => 'testimonial',
			'class' => '',
			'as_child' => array( 'only' => 'testimonials' ),
			'content_element' => true,
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'description' => esc_html__( 'User testimonial', 'woodmart' ),
        	'icon' => WOODMART_ASSETS . '/images/vc-icon/testimonials.svg',
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
					'heading' => esc_html__( 'User Avatar', 'woodmart' ),
					'param_name' => 'image',
					'value' => '',
					'hint' => esc_html__( 'Enter the person’s title or job position. For example: CEO or Senior Developer.', 'woodmart' ),
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
				* Content
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Content', 'woodmart' ),
					'param_name' => 'content_divider'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Name', 'woodmart' ),
					'param_name' => 'name',
					'value' => '',
					'hint' => esc_html__( 'Enter the person’s name.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title', 'woodmart' ),
					'param_name' => 'title',
					'value' => '',
					'hint' => esc_html__( 'User title', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textarea_html',
					'holder' => 'div',
					'heading' => esc_html__( 'Text', 'woodmart' ),
					'param_name' => 'content'
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
				)
			)
		) );
	}
	add_action( 'vc_before_init', 'woodmart_vc_map_testimonials' );
}

if( class_exists( 'WPBakeryShortCodesContainer' ) ){
    class WPBakeryShortCode_testimonials extends WPBakeryShortCodesContainer {

    }
}

// Replace Wbc_Inner_Item with your base name from mapping for nested element
if( class_exists( 'WPBakeryShortCode' ) ){
    class WPBakeryShortCode_testimonial extends WPBakeryShortCode {

    }
}
