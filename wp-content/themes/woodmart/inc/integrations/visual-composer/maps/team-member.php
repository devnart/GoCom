<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
* Team Member element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_vc_map_team_member' ) ) {
	function woodmart_vc_map_team_member() {
		if ( ! shortcode_exists( 'team_member' ) ) {
			return;
		}

		vc_map( array(
			'name' => esc_html__( 'Team Member', 'woodmart' ),
			'base' => 'team_member',
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'description' => esc_html__( 'Display information about some person', 'woodmart' ),
        	'icon' => WOODMART_ASSETS . '/images/vc-icon/team-member.svg',
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
					'heading' => esc_html__( 'Name', 'woodmart' ),
					'param_name' => 'name',
					'value' => '',
					'hint' => esc_html__( 'Enter the person’s name.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title', 'woodmart' ),
					'param_name' => 'position',
					'value' => '',
					'hint' => esc_html__( 'Enter the person’s title or job position. For example: CEO or Senior Developer.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textarea_html',
					'heading' => esc_html__( 'Text', 'woodmart' ),
					'param_name' => 'content',
					'hint' => esc_html__( 'You can add some member bio here.', 'woodmart' )
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
						esc_html__( 'Default', 'woodmart' ) => 'default',
						esc_html__( 'With hover', 'woodmart' ) => 'hover',
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
					'std' => 'left',
					'wood_tooltip' => true,
					'edit_field_class' => 'vc_col-sm-6 vc_column title-align',
				),
				/**
				* Social links
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Social links', 'woodmart' ),
					'param_name' => 'links_divider'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Facebook link', 'woodmart' ),
					'param_name' => 'facebook',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Twitter link', 'woodmart' ),
					'param_name' => 'twitter',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Linkedin link', 'woodmart' ),
					'param_name' => 'linkedin',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Skype link', 'woodmart' ),
					'param_name' => 'skype',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Instagram link', 'woodmart' ),
					'param_name' => 'instagram',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				/**
				* Buttons
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Buttons', 'woodmart' ),
					'param_name' => 'buttons_divider'
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Social buttons size', 'woodmart' ),
					'param_name' => 'size',
					'value' => array(
						esc_html__( 'Default (18px)', 'woodmart' ) => '',
						esc_html__( 'Small (14px)', 'woodmart' ) => 'small',
						esc_html__( 'Large (22px)', 'woodmart' ) => 'large'
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_image_select',
					'heading' => esc_html__( 'Social button style', 'woodmart' ),
					'param_name' => 'style',
					'value' => array( 
						esc_html__( 'Default', 'woodmart' ) => '',
						esc_html__( 'Simple', 'woodmart' ) => 'simple',
						esc_html__( 'Colored', 'woodmart' ) => 'colored',
						esc_html__( 'Colored alternative', 'woodmart' ) => 'colored-alt',
						esc_html__( 'Bordered', 'woodmart' ) => 'bordered'
					),
					'images_value' => array(
						'' => WOODMART_ASSETS_IMAGES . '/settings/social-buttons/style/default.png',
						'simple' => WOODMART_ASSETS_IMAGES . '/settings/social-buttons/style/simple.png',
						'colored' => WOODMART_ASSETS_IMAGES . '/settings/social-buttons/style/colored.png',
						'colored-alt' => WOODMART_ASSETS_IMAGES . '/settings/social-buttons/style/colored-alt.png',
						'bordered' => WOODMART_ASSETS_IMAGES . '/settings/social-buttons/style/bordered.png',
					),
					'wood_tooltip' => true,
					'std' => 'default',
					'edit_field_class' => 'vc_col-xs-12 vc_column social-style',
				),
				array(
					'type' => 'woodmart_image_select',
					'heading' => esc_html__( 'Social button form', 'woodmart' ),
					'param_name' => 'form',
					'value' => array( 
						esc_html__( 'Circle', 'woodmart' ) => 'circle',
						esc_html__( 'Square', 'woodmart' ) => 'square'
					),
					'images_value' => array(
						'circle' => WOODMART_ASSETS_IMAGES . '/settings/social-buttons/shape/circle.png',
						'square' => WOODMART_ASSETS_IMAGES . '/settings/social-buttons/shape/square.png',
					),
					'wood_tooltip' => true,
					'std' => 'default',
					'edit_field_class' => 'vc_col-xs-12 vc_column social-form',
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
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Color Scheme', 'woodmart' ),
					'param_name' => 'woodmart_color_scheme',
					'value' => array(
						esc_html__( 'Inherit', 'woodmart' ) => '',
						esc_html__( 'Light', 'woodmart' ) => 'light',
						esc_html__( 'Dark', 'woodmart' ) => 'dark',
					),
				),
				( function_exists( 'vc_map_add_css_animation' ) ) ? vc_map_add_css_animation( true ) : '',
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'woodmart' ),
					'param_name' => 'el_class',
					'hint' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' )
				)
			),
		) );
	}
	add_action( 'vc_before_init', 'woodmart_vc_map_team_member' );
}
