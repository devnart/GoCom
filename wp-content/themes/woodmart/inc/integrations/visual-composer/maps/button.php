<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
*  Button element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_vc_shortcode_button' ) ) {
	function woodmart_vc_shortcode_button() {
		if ( ! shortcode_exists( 'woodmart_button' ) ) {
			return;
		}
		
		vc_map( woodmart_get_woodmart_button_shortcode_args() );
	}
	add_action( 'vc_before_init', 'woodmart_vc_shortcode_button' );
}

if( ! function_exists( 'woodmart_get_woodmart_button_shortcode_args' ) ) {
	function woodmart_get_woodmart_button_shortcode_args() {
		return array(
			'name' => esc_html__( 'Button', 'woodmart' ),
			'base' => 'woodmart_button',
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'description' => esc_html__( 'Simple button in different theme styles', 'woodmart' ),
        	'icon' => WOODMART_ASSETS . '/images/vc-icon/button.svg',
			'params' => woodmart_get_button_shortcode_params()
		);
	}
}

if( ! function_exists( 'woodmart_get_button_shortcode_params' ) ) {
	function woodmart_get_button_shortcode_params() {
		return apply_filters( 'woodmart_get_button_shortcode_params', array(
				array(
					'type' => 'woodmart_css_id',
					'param_name' => 'woodmart_css_id'
				),
				/**
				* Button
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Button', 'woodmart' ),
					'param_name' => 'button_divider'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title', 'woodmart' ),
					'param_name' => 'title',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'vc_link',
					'heading' => esc_html__( 'Link', 'woodmart' ),
					'param_name' => 'link',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_dropdown',
					'heading' => esc_html__( 'Predefined button color', 'woodmart' ),
					'param_name' => 'color',
					'value' => array(
						esc_html__( 'Default', 'woodmart' ) => 'default',
						esc_html__( 'Primary color', 'woodmart' ) => 'primary',
						esc_html__( 'Alternative color', 'woodmart' ) => 'alt',
						esc_html__( 'White', 'woodmart' ) => 'white',
						esc_html__( 'Black', 'woodmart' ) => 'black',
					),
					'style' => array(
						'default' => '#f3f3f3',
						'primary' => woodmart_get_color_value( 'primary-color', '#7eb934' ),
						'alt' => woodmart_get_color_value( 'secondary-color', '#fbbc34' ),
						'black' => '#212121',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Button size', 'woodmart' ),
					'param_name' => 'size',
					'value' => array(
						esc_html__( 'Default', 'woodmart' ) => 'default',
						esc_html__( 'Extra Small', 'woodmart' ) => 'extra-small',
						esc_html__( 'Small', 'woodmart' ) => 'small',
						esc_html__( 'Large', 'woodmart' ) => 'large',
						esc_html__( 'Extra Large', 'woodmart' ) => 'extra-large',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_image_select',
					'heading' => esc_html__( 'Button style', 'woodmart' ),
					'param_name' => 'style',
				    'value' => array( 
						esc_html__( 'Default', 'woodmart' ) => 'default',
						esc_html__( 'Bordered', 'woodmart' ) => 'bordered',
						esc_html__( 'Link button', 'woodmart' ) => 'link',
						esc_html__( '3D', 'woodmart' ) => '3d',
					),
					'images_value' => array(
						'default' => WOODMART_ASSETS_IMAGES . '/settings/buttons/style/default.png',
						'bordered' => WOODMART_ASSETS_IMAGES . '/settings/buttons/style/bordered.png',
						'link' => WOODMART_ASSETS_IMAGES . '/settings/buttons/style/link.png',
						'3d' => WOODMART_ASSETS_IMAGES . '/settings/buttons/style/3d.png',
					),
					'title' => false,
					'std' => 'default',
					'edit_field_class' => 'vc_col-xs-12 vc_column button-style',
				),
				array(
					'type' => 'woodmart_image_select',
					'heading' => esc_html__( 'Button shape', 'woodmart' ),
					'param_name' => 'shape',
				    'value' => array( 
						esc_html__( 'Rectangle', 'woodmart' ) => 'rectangle',
						esc_html__( 'Circle', 'woodmart' ) => 'round',
						esc_html__( 'Round', 'woodmart' ) => 'semi-round',
					),
					'images_value' => array(
						'rectangle' => WOODMART_ASSETS_IMAGES . '/settings/buttons/shape/rectangle.png',
						'round' => WOODMART_ASSETS_IMAGES . '/settings/buttons/shape/circle.png',
						'semi-round' => WOODMART_ASSETS_IMAGES . '/settings/buttons/shape/round.png',
					),
					'dependency' => array(
						'element' => 'style',
						'value_not_equal_to' => array( 'round', 'link' ),
					),
					'title' => false,
					'std' => 'rectangle',
					'edit_field_class' => 'vc_col-xs-12 vc_column button-shape',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Icon library', 'woodmart' ),
					'value' => array(
						esc_html__( 'Font Awesome', 'woodmart' ) => 'fontawesome',
						esc_html__( 'Open Iconic', 'woodmart' ) => 'openiconic',
						esc_html__( 'Typicons', 'woodmart' ) => 'typicons',
						esc_html__( 'Entypo', 'woodmart' ) => 'entypo',
						esc_html__( 'Linecons', 'woodmart' ) => 'linecons',
						esc_html__( 'Mono Social', 'woodmart' ) => 'monosocial',
						esc_html__( 'Material', 'woodmart' ) => 'material'
					),
					'param_name' => 'icon_library',
					'hint' => esc_html__( 'Select icon library.', 'woodmart' ),
					'dependency' => array(
						'element' => 'list_type',
						'value' => 'icon'
					)
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'woodmart' ),
					'param_name' => 'icon_fontawesome',
					'value' => '',
					'settings' => array(
						'emptyIcon' => true,
						'iconsPerPage' => 4000
					),
					'dependency' => array(
						'element' => 'icon_library',
						'value' => array('fontawesome')
					),
					'hint' => esc_html__( 'Select icon from library.', 'woodmart' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'woodmart' ),
					'param_name' => 'icon_openiconic',
					'settings' => array(
						'emptyIcon' => true,
						'type' => 'openiconic',
						'iconsPerPage' => 4000
					),
					'dependency' => array(
						'element' => 'icon_library',
						'value' => array('openiconic')
					),
					'hint' => esc_html__( 'Select icon from library.', 'woodmart' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'woodmart' ),
					'param_name' => 'icon_typicons',
					'settings' => array(
						'emptyIcon' => true,
						'type' => 'typicons',
						'iconsPerPage' => 4000
					),
					'dependency' => array(
						'element' => 'icon_library',
						'value' => array('typicons')
					),
					'hint' => esc_html__( 'Select icon from library.', 'woodmart' )
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'woodmart' ),
					'param_name' => 'icon_entypo',
					'settings' => array(
						'emptyIcon' => true,
						'type' => 'entypo',
						'iconsPerPage' => 4000
					),
					'dependency' => array(
						'element' => 'icon_library',
						'value' => array('entypo')
					)
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'woodmart' ),
					'param_name' => 'icon_linecons',
					'settings' => array(
						'emptyIcon' => true,
						'type' => 'linecons',
						'iconsPerPage' => 4000
					),
					'dependency' => array(
						'element' => 'icon_library',
						'value' => array('linecons')
					),
					'hint' => esc_html__( 'Select icon from library.', 'woodmart' )
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'woodmart' ),
					'param_name' => 'icon_monosocial',
					'settings' => array(
						'emptyIcon' => true,
						'type' => 'monosocial',
						'iconsPerPage' => 4000
					),
					'dependency' => array(
						'element' => 'icon_library',
						'value' => array('monosocial')
					),
					'hint' => esc_html__( 'Select icon from library.', 'woodmart' )
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'woodmart' ),
					'param_name' => 'icon_material',
					'settings' => array(
						'emptyIcon' => true,
						'type' => 'material',
						'iconsPerPage' => 4000
					),
					'dependency' => array(
						'element' => 'icon_library',
						'value' => array('material')
					),
					'hint' => esc_html__( 'Select icon from library.', 'woodmart' )
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Button icon position', 'woodmart' ),
					'param_name' => 'icon_position',
					'value' => array(
						esc_html__( 'Left', 'woodmart' ) => 'left',
						esc_html__( 'Right', 'woodmart' ) => 'right',
					),
					'std' => 'right',
					'edit_field_class' => 'vc_col-xs-12 vc_column button-style',
				),
				/**
				* Layout
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Layout', 'woodmart' ),
					'param_name' => 'layout_divider',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Full width', 'woodmart' ),
					'param_name' => 'full_width',
					'true_state' => 'yes',
					'false_state' => 'no',
					'default' => 'no',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Button inline', 'woodmart' ),
					'param_name' => 'button_inline',
					'true_state' => 'yes',
					'false_state' => 'no',
					'default' => 'no',
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
					'dependency' => array(
						'element' => 'button_inline',
						'value_not_equal_to' => array( 'yes' ),
					),
					'std' => 'center',
					'wood_tooltip' => true,
					'edit_field_class' => 'vc_col-sm-6 vc_column title-align',
				),
				/**
				* Idle state
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Idle state', 'woodmart' ),
					'group' => esc_html__( 'Custom color', 'woodmart' ),
					'param_name' => 'idle_divider',
				),
				array(
					'type' => 'woodmart_colorpicker',
					'heading' => esc_html__( 'Background color', 'woodmart' ),
					'param_name' => 'bg_color',
					'css_args' => array(
						'background-color' => array(
							' a',
						),
						'border-color' => array(
							' a',
						),
					),
					'group' => esc_html__( 'Custom color', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column', 
				),
				array(
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Text color scheme', 'woodmart' ),
					'param_name' => 'color_scheme',
					'group' => esc_html__( 'Custom color', 'woodmart' ),
					'value' => array(
						esc_html__( 'Light', 'woodmart' ) => 'light',
						esc_html__( 'Dark', 'woodmart' ) => 'dark',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column', 
				),
				/**
				* Hover state
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Hover state', 'woodmart' ),
					'group' => esc_html__( 'Custom color', 'woodmart' ),
					'param_name' => 'hover_divider',
				),
				array(
					'type' => 'woodmart_colorpicker',
					'heading' => esc_html__( 'Background color on hover', 'woodmart' ),
					'param_name' => 'bg_color_hover',
					'css_args' => array(
						'background-color' => array(
							' a:hover',
						),
						'border-color' => array(
							' a:hover',
						),
					),
					'group' => esc_html__( 'Custom color', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column', 
				),
				array(
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Text color scheme on hover', 'woodmart' ),
					'param_name' => 'color_scheme_hover',
					'group' => esc_html__( 'Custom color', 'woodmart' ),
					'value' => array(
						esc_html__( 'Light', 'woodmart' ) => 'light',
						esc_html__( 'Dark', 'woodmart' ) => 'dark',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column', 
				),
				/**
				 * Smooth scroll
				 */
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Smooth scroll', 'woodmart' ),
					'param_name' => 'smooth_divider',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Smooth scroll', 'woodmart' ),
					'hint' => esc_html__( 'When you turn on this option you need to specify this button link with a hash symbol. For example #section-id Then you need to have a section with an ID of "section-id" and this button click will smoothly scroll the page to that section.', 'woodmart' ),
					'param_name' => 'button_smooth_scroll',
					'true_state' => 'yes',
					'false_state' => 'no',
					'default' => 'no',
					'edit_field_class' => 'vc_col-sm-12 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Smooth scroll time (ms)', 'woodmart' ),
					'param_name' => 'button_smooth_scroll_time',
					'dependency' => array(
						'element' => 'button_smooth_scroll',
						'value_not_equal_to' => array( 'no' ),
					),
					'std' => '100',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Smooth scroll offset (px)', 'woodmart' ),
					'param_name' => 'button_smooth_scroll_offset',
					'dependency' => array(
						'element' => 'button_smooth_scroll',
						'value_not_equal_to' => array( 'no' ),
					),
					'std' => '100',
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
				),
			)
		);
	}
}
