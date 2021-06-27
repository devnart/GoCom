<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
* Section title element map
* ------------------------------------------------------------------------------------------------
*/

if ( ! function_exists( 'woodmart_vc_map_title' ) ) {
	function woodmart_vc_map_title() {
		if ( ! shortcode_exists( 'woodmart_title' ) ) {
			return;
		}

		$secondary_font = woodmart_get_opt( 'secondary-font' );
		$default_font = woodmart_get_opt( 'text-font' );
		$alt_font_subtitle = isset( $secondary_font[0] ) ? esc_html__( 'Alternative', 'woodmart' ) . ' (' . $secondary_font[0]['font-family'] . ')' : esc_html__( 'Alternative', 'woodmart' );
		$default_font_subtitle = isset( $default_font[0] ) ? esc_html__( 'Default', 'woodmart' ) . ' (' . $default_font[0]['font-family'] . ')' : esc_html__( 'Default', 'woodmart' );

		vc_map( array(
			'name' => esc_html__( 'Section title', 'woodmart' ),
			'base' => 'woodmart_title',
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'description' => esc_html__( 'Styled title for sections', 'woodmart' ),
        	'icon' => WOODMART_ASSETS . '/images/vc-icon/section-title.svg',
			'params' => array(
				array(
					'type' => 'woodmart_css_id',
					'param_name' => 'woodmart_css_id'
				),
				/**
				* Title
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Title', 'woodmart' ),
					'param_name' => 'title_divider'
				),
				array(
					'type' => 'textarea',
					'holder' => 'div',
					'heading' => esc_html__( 'Section title', 'woodmart' ),
					'param_name' => 'title'
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Predefined title size', 'woodmart' ),
					'param_name' => 'size',
					'value' => array(
						esc_html__( 'Default (22px)', 'woodmart' ) => 'default',
						esc_html__( 'Small (18px)', 'woodmart' ) => 'small',
						esc_html__( 'Medium (26px)', 'woodmart' ) => 'medium',
						esc_html__( 'Large (36px)', 'woodmart' ) => 'large',
						esc_html__( 'Extra Large (46px)', 'woodmart' ) => 'extra-large',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Font weight', 'woodmart' ),
					'param_name' => 'font_weight',
					'value' => array(
						'' => '',
						esc_html__( 'Ultra-Light 100', 'woodmart' ) => 100,
						esc_html__( 'Light 200', 'woodmart' ) => 200,
						esc_html__( 'Book 300', 'woodmart' ) => 300,
						esc_html__( 'Normal 400', 'woodmart' ) => 400,
						esc_html__( 'Medium 500', 'woodmart' ) => 500,
						esc_html__( 'Semi-Bold 600', 'woodmart' ) => 600,
						esc_html__( 'Bold 700', 'woodmart' ) => 700,
						esc_html__( 'Extra-Bold 800', 'woodmart' ) => 800,
						esc_html__( 'Ultra-Bold 900', 'woodmart' ) => 900,
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_responsive_size',
					'heading' => esc_html__( 'Custom size', 'woodmart' ),
					'param_name' => 'title_font_size',
					'css_args' => array(
						'font-size' => array(
							' .woodmart-title-container',
						),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column', 
				),
				array(
					'type' => 'woodmart_responsive_size',
					'heading' => esc_html__( 'Custom line height', 'woodmart' ),
					'param_name' => 'title_line_height',
					'css_args' => array(
						'line-height' => array(
							' .woodmart-title-container',
						),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column', 
				),
				array(
					'type' => 'woodmart_empty_space',
					'param_name' => 'woodmart_empty_space',
				),
				array(
					'type' => 'woodmart_dropdown',
					'heading' => esc_html__( 'Predefined color scheme', 'woodmart' ),
					'param_name' => 'color',
					'value' => woodmart_section_title_color_variation(),
					'style' => array(
						'default' => '#989898',
						'primary' => woodmart_get_color_value( 'primary-color', '#7eb934' ),
						'alt' => woodmart_get_color_value( 'secondary-color', '#fbbc34' ),
						'black' => '#2d2a2a',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_colorpicker',
					'heading' => esc_html__( 'Custom title color', 'woodmart' ),
					'param_name' => 'title_custom_color',
					'css_args' => array(
						'color' => array(
							' .woodmart-title-container',
						),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				woodmart_title_gradient_picker(),
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
					'type' => 'woodmart_image_select',
					'heading' => esc_html__( 'Title align', 'woodmart' ),
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
					'type' => 'woodmart_slider',
					'heading' => esc_html__( 'Title width', 'woodmart' ),
					'param_name' => 'title_width',
					'min' => '10',
					'max' => '100',
					'step' => '10',
					'default' => '100',
					'units' => '%',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_image_select',
					'heading' => esc_html__( 'Title style', 'woodmart' ),
					'param_name' => 'style',
					'value' => array( 
						esc_html__( 'Default', 'woodmart' ) => 'default',
						esc_html__( 'Simple', 'woodmart' ) => 'simple',
						esc_html__( 'Bordered', 'woodmart' ) => 'bordered',
						esc_html__( 'Underline', 'woodmart' ) => 'underlined',
						esc_html__( 'Underline 2', 'woodmart' ) => 'underlined-2',
						esc_html__( 'Shadow', 'woodmart' ) => 'shadow',
						esc_html__( 'With image', 'woodmart' ) => 'image'
					),
					'images_value' => array(
						'default' => WOODMART_ASSETS_IMAGES . '/settings/title-style/default.png',
						'simple' => WOODMART_ASSETS_IMAGES . '/settings/title-style/simple.png',
						'bordered' => WOODMART_ASSETS_IMAGES . '/settings/title-style/bordered.png',
						'underlined' => WOODMART_ASSETS_IMAGES . '/settings/title-style/underlined.png',
						'underlined-2' => WOODMART_ASSETS_IMAGES . '/settings/title-style/underlined-2.png',
						'shadow' => WOODMART_ASSETS_IMAGES . '/settings/title-style/shadow.png',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/title-style/image.png',
					),
				),
				/**
				* Image
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Image', 'woodmart' ),
					'param_name' => 'image_divider',
					'dependency' => array(
						'element' => 'style',
						'value' => array( 'image' )
					)
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__( 'Image', 'woodmart' ),
					'param_name' => 'image',
					'value' => '',
					'hint' => esc_html__( 'Select image from media library.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'dependency' => array(
						'element' => 'style',
						'value' => array( 'image' )
					)
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Image size', 'woodmart' ),
					'param_name' => 'img_size',
					'hint' => esc_html__( 'Enter image size. Example: \'thumbnail\', \'medium\', \'large\', \'full\' or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use \'thumbnail\' size.', 'woodmart' ),
					'dependency' => array(
						'element' => 'style',
						'value' => array( 'image' )
					),
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
					'param_name' => 'extra_divider'
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Title tag', 'woodmart' ),
					'param_name' => 'tag',
					'value' => array(
						'h1' => 'h1',
						'h2' => 'h2',
						'h3' => 'h3',
						'h4' => 'h4',
						'h5' => 'h5',
						'h6' => 'h6',
						'p' => 'p',
						'div' => 'div',
						'span' => 'span',
					),
					'std' => 'h4',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				( function_exists( 'vc_map_add_css_animation' ) ) ? vc_map_add_css_animation( true ) : '',
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'woodmart' ),
					'param_name' => 'el_class',
					'hint' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' ),
					// 'edit_field_class' => 'vc_col-xs-12 vc_column woodmart-vc-no-wrap',
				),
				/**
				* Subtitle
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Subtitle', 'woodmart' ),
					'group' => esc_html__( 'Subtitle', 'woodmart' ),
					'param_name' => 'subtitle_divider'
				),
				array(
					'type' => 'textarea',
					'heading' => esc_html__( 'Subtitle', 'woodmart' ),
					'group' => esc_html__( 'Subtitle', 'woodmart' ),
					'param_name' => 'subtitle'
				),
				array(
					'type' => 'woodmart_responsive_size',
					'heading' => esc_html__( 'Font Size', 'woodmart' ),
					'group' => esc_html__( 'Subtitle', 'woodmart' ),
					'param_name' => 'subtitle_font_size',
					'css_args' => array(
						'font-size' => array(
							' .title-subtitle',
						),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_responsive_size',
					'heading' => esc_html__( 'Line height', 'woodmart' ),
					'group' => esc_html__( 'Subtitle', 'woodmart' ),
					'param_name' => 'subtitle_line_height_size',
					'css_args' => array(
						'line-height' => array(
							' .title-subtitle',
						),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_empty_space',
					'param_name' => 'woodmart_empty_space',
					'group' => esc_html__( 'Subtitle', 'woodmart' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Font', 'woodmart' ),
					'group' => esc_html__( 'Subtitle', 'woodmart' ),
					'param_name' => 'subtitle_font',
					'value' => array(
						$default_font_subtitle => 'default',
						$alt_font_subtitle => 'alt'
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Font weight', 'woodmart' ),
					'group' => esc_html__( 'Subtitle', 'woodmart' ),
					'param_name' => 'subtitle_font_weight',
					'value' => array(
						'' => '',
						esc_html__( 'Ultra-Light 100', 'woodmart' ) => 100,
						esc_html__( 'Light 200', 'woodmart' ) => 200,
						esc_html__( 'Book 300', 'woodmart' ) => 300,
						esc_html__( 'Normal 400', 'woodmart' ) => 400,
						esc_html__( 'Medium 500', 'woodmart' ) => 500,
						esc_html__( 'Semi-Bold 600', 'woodmart' ) => 600,
						esc_html__( 'Bold 700', 'woodmart' ) => 700,
						esc_html__( 'Extra-Bold 800', 'woodmart' ) => 800,
						esc_html__( 'Ultra-Bold 900', 'woodmart' ) => 900,
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_colorpicker',
					'heading' => esc_html__( 'Custom color', 'woodmart' ),
					'group' => esc_html__( 'Subtitle', 'woodmart' ),
					'param_name' => 'subtitle_color',
					'css_args' => array(
						'color' => array(
							' .title-subtitle',
						),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_colorpicker',
					'heading' => esc_html__( 'Background color', 'woodmart' ),
					'group' => esc_html__( 'Subtitle', 'woodmart' ),
					'param_name' => 'subtitle_bg_color',
					'css_args' => array(
						'background-color' => array(
							' .title-subtitle',
						),
					),
					'dependency' => array(
						'element' => 'subtitle_style',
						'value' => array( 'background' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_image_select',
					'heading' => esc_html__( 'Style', 'woodmart' ),
					'group' => esc_html__( 'Subtitle', 'woodmart' ),
					'param_name' => 'subtitle_style',
					'value' => array(
						esc_html__( 'Default', 'woodmart' ) => 'default',
						esc_html__( 'Background', 'woodmart' ) => 'background'
					),
					'images_value' => array(
						'default' => WOODMART_ASSETS_IMAGES . '/settings/subtitle-style/default.png',
						'background' => WOODMART_ASSETS_IMAGES . '/settings/subtitle-style/background.png',
					),
					'edit_field_class' => 'vc_col-sm-8 vc_column',
				),
				/**
				* Text after title
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Text', 'woodmart' ),
					'group' => esc_html__( 'Text', 'woodmart' ),
					'param_name' => 'text_divider'
				),
				array(
					'type' => 'textarea',
					'heading' => esc_html__( 'Text after title', 'woodmart' ),
					'group' => esc_html__( 'Text', 'woodmart' ),
					'param_name' => 'after_title'
				),
				array(
					'type' => 'woodmart_responsive_size',
					'heading' => esc_html__( 'Size', 'woodmart' ),
					'group' => esc_html__( 'Text', 'woodmart' ),
					'param_name' => 'after_font_size',
					'css_args' => array(
						'font-size' => array(
							' .title-after_title',
						),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_responsive_size',
					'heading' => esc_html__( 'Line height', 'woodmart' ),
					'group' => esc_html__( 'Text', 'woodmart' ),
					'param_name' => 'after_line_height_size',
					'css_args' => array(
						'line-height' => array(
							' .title-after_title',
						),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_colorpicker',
					'heading' => esc_html__( 'Color', 'woodmart' ),
					'group' => esc_html__( 'Text', 'woodmart' ),
					'param_name' => 'after_color',
					'css_args' => array(
						'color' => array(
							' .title-after_title',
						),
					),
				),
				/**
				* Custom sizes
				*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Desktop text size ( > 1024px )', 'woodmart' ),
					'param_name' => 'desktop_text_size',
					'hint' => esc_html__( 'Only number without px.', 'woodmart' ),
					'group' => esc_html__( 'Custom size (deprecated)', 'woodmart' ),
					'dependency' => array(
						'element' => 'size',
						'value' => array( 'custom' )
					) 
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Tablet text size ( < 1024px )', 'woodmart' ),
					'param_name' => 'tablet_text_size',
					'hint' => esc_html__( 'Only number without px.', 'woodmart' ),
					'group' => esc_html__( 'Custom size (deprecated)', 'woodmart' ),
					'dependency' => array(
						'element' => 'size',
						'value' => array( 'custom' )
					) 	
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Mobile text size ( < 767px )', 'woodmart' ),
					'param_name' => 'mobile_text_size',
					'hint' => esc_html__( 'Only number without px.', 'woodmart' ),
					'group' => esc_html__( 'Custom size (deprecated)', 'woodmart' ),
					'dependency' => array(
						'element' => 'size',
						'value' => array( 'custom' )
					) 
				),
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'woodmart' ),
					'param_name' => 'css',
					'group' => esc_html__( 'Design Options', 'woodmart' )
				),
			),
		) );
	}
	add_action( 'vc_before_init', 'woodmart_vc_map_title' );
}
