<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
* Images gallery element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_vc_map_gallery' ) ) {
	function woodmart_vc_map_gallery() {
		if ( ! shortcode_exists( 'woodmart_gallery' ) ) {
			return;
		}

		vc_map( array(
			'name' => esc_html__( 'Images gallery', 'woodmart' ),
			'base' => 'woodmart_gallery',
			'class' => '',
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'description' => esc_html__( 'Images grid/carousel', 'woodmart' ),
        	'icon' => WOODMART_ASSETS . '/images/vc-icon/images-gallery.svg',
			'params' => array(
				/**
				* Images
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Images', 'woodmart' ),
					'param_name' => 'images_divider'
				),
				array(
					'type' => 'attach_images',
					'heading' => esc_html__( 'Images', 'woodmart' ),
					'param_name' => 'images',
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
					'heading' => esc_html__( 'View', 'woodmart' ),
					'param_name' => 'view',
					'save_always' => true,
					'value' => array(
						esc_html__( 'Default grid', 'woodmart' ) => 'grid',
						esc_html__( 'Masonry grid', 'woodmart' ) => 'masonry',
						esc_html__( 'Carousel', 'woodmart' ) => 'carousel',
						esc_html__( 'Justified gallery', 'woodmart' ) => 'justified',
					)
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Space between images', 'woodmart' ),
					'param_name' => 'spacing',
					'value' => array(
						0, 2, 6, 10, 20, 30
					),
					'dependency' => array(
						'element' => 'view',
						'value_not_equal_to' => array( 'justified' ),
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
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'dependency' => array(
						'element' => 'view',
						'value' => array( 'grid', 'masonry' ),
					),
					'hint' => esc_html__( 'Number of columns in the grid.', 'woodmart' )
				),
				array(
					'type' => 'woodmart_empty_space',
					'param_name' => 'woodmart_empty_space',
				),
				array(
					'type' => 'woodmart_image_select',
					'heading' => esc_html__( 'Horizontal  align', 'woodmart' ),
					'param_name' => 'horizontal_align',
				    'value' => array( 
						esc_html__( 'Left', 'woodmart' ) => 'left',
						esc_html__( 'Center', 'woodmart' ) => 'center',
						esc_html__( 'Right', 'woodmart' ) => 'right',
					),
					'images_value' => array(
						'left' => WOODMART_ASSETS_IMAGES . '/settings/content-align/horizontal/left.png',
						'center' => WOODMART_ASSETS_IMAGES . '/settings/content-align/horizontal/center.png',
						'right' => WOODMART_ASSETS_IMAGES . '/settings/content-align/horizontal/right.png',
					),
					'std' => 'center',
					'wood_tooltip' => true,
					'edit_field_class' => 'vc_col-sm-6 vc_column content-position',
					'dependency' => array(
						'element' => 'view',
						'value' => array( 'grid', 'masonry', 'carousel' ),
					),
				),
				array(
					'type' => 'woodmart_image_select',
					'heading' => esc_html__( 'Vertical align', 'woodmart' ),
					'param_name' => 'vertical_align',
				    'value' => array( 
						esc_html__( 'Top', 'woodmart' ) => 'top',
						esc_html__( 'Middle', 'woodmart' ) => 'middle',
						esc_html__( 'Bottom', 'woodmart' ) => 'bottom',
					),
					'images_value' => array(
						'top' => WOODMART_ASSETS_IMAGES . '/settings/content-align/vertical/top.png',
						'middle' => WOODMART_ASSETS_IMAGES . '/settings/content-align/vertical/middle.png',
						'bottom' => WOODMART_ASSETS_IMAGES . '/settings/content-align/vertical/bottom.png',
					),
					'std' => 'middle',
					'wood_tooltip' => true,
					'edit_field_class' => 'vc_col-sm-6 vc_column content-position',
					'dependency' => array(
						'element' => 'view',
						'value' => array( 'grid', 'masonry', 'carousel' ),
					),
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
						'element' => 'view',
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
					'dependency' => array(
						'element' => 'view',
						'value' => array( 'carousel' ),
					),
					'hint' => esc_html__( 'Set numbers of slides you want to display at the same time on slider\'s container for carousel mode.', 'woodmart' )
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Scroll per page', 'woodmart' ),
					'param_name' => 'scroll_per_page',
					'hint' => esc_html__( 'Scroll per page not per item. This affect next/prev buttons and mouse/touch dragging.', 'woodmart' ),
					'true_state' => 'yes',
					'false_state' => 'no',
					'default' => 'yes',
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
						'element' => 'view',
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
						'element' => 'view',
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
						'element' => 'view',
						'value' => array( 'carousel' ),
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
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'dependency' => array(
						'element' => 'view',
						'value' => array( 'carousel' ),
					),
				),
				/**
				* Click action
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Click action', 'woodmart' ),
					'param_name' => 'click_divider'
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'On click action', 'woodmart' ),
					'param_name' => 'on_click',
					'value' => array(
						esc_html__( 'Lightbox', 'woodmart' ) => 'lightbox',
						esc_html__( 'Custom link', 'woodmart' ) => 'links',
						esc_html__( 'None', 'woodmart' ) => 'none'
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Open in new tab', 'woodmart' ),
					'param_name' => 'target_blank',
					'true_state' => 1,
					'false_state' => 0,
					'default' => 0,
					'dependency' => array(
						'element' => 'on_click',
						'value' => array( 'links' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Images captions', 'woodmart' ),
					'hint' => esc_html__( 'Display images captions below the images when you open them in lightbox. Captions are based on titles of your photos and can be edited in Dashboard -> Media.', 'woodmart' ),
					'param_name' => 'caption',
					'true_state' => 1,
					'false_state' => 0,
					'default' => 0,
					'dependency' => array(
						'element' => 'on_click',
						'value' => array( 'lightbox' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'exploded_textarea_safe',
					'heading' => esc_html__( 'Custom links', 'woodmart' ),
					'param_name' => 'custom_links',
					'hint' => esc_html__( 'Enter links for each slide (Note: divide links with linebreaks (Enter)).', 'woodmart' ),
					'dependency' => array(
						'element' => 'on_click',
						'value' => array( 'links' ),
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
						'element' => 'view',
						'value' => array( 'carousel' ),
					),
				),
				( function_exists( 'vc_map_add_css_animation' ) ) ? vc_map_add_css_animation( true ) : '',
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'woodmart' ),
					'param_name' => 'el_class',
					'hint' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' )
				)
			)
		) );
	}
	add_action( 'vc_before_init', 'woodmart_vc_map_gallery' );
}
