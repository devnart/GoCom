<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
* Author area element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_vc_map_author_area' ) ) {
	function woodmart_vc_map_author_area() {
		if ( ! shortcode_exists( 'author_area' ) ) {
			return;
		}

		vc_map( array(
			'name' => esc_html__( 'Author area', 'woodmart' ),
			'base' => 'author_area',
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'description' => esc_html__( 'Widget for author information', 'woodmart' ),
        	'icon' => WOODMART_ASSETS . '/images/vc-icon/author-area.svg',
			'params' =>  woodmart_get_author_area_params()
		) );
	}
	add_action( 'vc_before_init', 'woodmart_vc_map_author_area' );
}

if( ! function_exists( 'woodmart_get_author_area_params' ) ) {
	function woodmart_get_author_area_params() {
		return apply_filters( 'woodmart_get_author_area_params', array(
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
				'param_name' => 'image',
				'value' => '',
				'hint' => esc_html__( 'Select image from media library.', 'woodmart' ),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Image size', 'woodmart' ),
				'param_name' => 'img_size',
				'hint' => esc_html__( 'Enter image size. Example: \'thumbnail\', \'medium\', \'large\', \'full\' or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use \'thumbnail\' size.', 'woodmart' ),
				'description' => esc_html__( 'Example: \'thumbnail\', \'medium\', \'large\', \'full\' or enter image size in pixels: \'200x100\'.', 'woodmart' ),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
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
				'heading' => esc_html__( 'Title', 'woodmart' ),
				'param_name' => 'title',
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Author name', 'woodmart' ),
				'param_name' => 'author_name',
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			array(
				'type' => 'textarea_html',
				'holder' => 'div',
				'heading' => esc_html__( 'Author bio', 'woodmart' ),
				'param_name' => 'content',
				'hint' => esc_html__( 'Write down a few words about the author.', 'woodmart' )
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
				'type' => 'woodmart_image_select',
				'heading' => esc_html__( 'Text alignment', 'woodmart' ),
				'param_name' => 'alignment',
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
				'std' => 'left',
				'wood_tooltip' => true,
				'hint' => esc_html__( 'Select image alignment.', 'woodmart' ),
				'edit_field_class' => 'vc_col-sm-6 vc_column title-align',
			),
			array(
				'type' => 'woodmart_button_set',
				'heading' => esc_html__( 'Color Scheme', 'woodmart' ),
				'param_name' => 'woodmart_color_scheme',
				'value' => array(
					esc_html__( 'Inherit', 'woodmart' ) => '',
					esc_html__( 'Light', 'woodmart' ) => 'light',
					esc_html__( 'Dark', 'woodmart' ) => 'dark',
				),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			/**
			* Link
			*/
			array(
				'type' => 'woodmart_title_divider',
				'holder' => 'div',
				'title' => esc_html__( 'Link', 'woodmart' ),
				'param_name' => 'link_divider'
			),
			array(
				'type' => 'vc_link',
				'heading' => esc_html__( 'Author link', 'woodmart'),
				'param_name' => 'link',
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Link text', 'woodmart'),
				'param_name' => 'link_text',
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
			( function_exists( 'vc_map_add_css_animation' ) ) ? vc_map_add_css_animation( true ) : '',
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Extra class name', 'woodmart' ),
				'param_name' => 'el_class',
				'hint' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' )
			)
		) );
	}
}
