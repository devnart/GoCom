<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
*  Google Map element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_vc_map_google_map' ) ) {
	function woodmart_vc_map_google_map() {
		if ( ! shortcode_exists( 'woodmart_google_map' ) ) {
			return;
		}

		vc_map( array(
			'name' => esc_html__( 'Google Map', 'woodmart' ),
			'description' => esc_html__( 'Shows Google map block', 'woodmart' ),
			'base' => 'woodmart_google_map',
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'as_parent' => array( 'except' => 'testimonial' ),
			'content_element' => true,
		    'js_view' => 'VcColumnView',
        	'icon' => WOODMART_ASSETS . '/images/vc-icon/google-maps.svg',
			'params' => array(
				/**
				* Settings
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Settings', 'woodmart' ),
					'param_name' => 'settings_divider'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Latitude (required)', 'woodmart' ),
					'param_name' => 'lat',
					'hint' => wp_kses( __( 'You can use <a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">this service</a> to get coordinates of your location.', 'woodmart' ), array(
                        'a' => array( 
                            'href' => array(), 
                            'target' => array()
                        )
                    ) ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Longitude (required)', 'woodmart' ),
					'param_name' => 'lon',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Google API key (required)', 'woodmart' ),
					'param_name' => 'google_key',
					'hint' => wp_kses( __( 'Obtain API key <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">here</a> to use our Google Map VC element.', 'woodmart' ), array(
                        'a' => array( 
                            'href' => array(), 
                            'target' => array()
                        )
                    ) )
				),
				/**
				* Marker settings
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Marker settings', 'woodmart' ),
					'param_name' => 'marker_divider'
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__( 'Marker icon', 'woodmart' ),
					'param_name' => 'marker_icon',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title', 'woodmart' ),
					'param_name' => 'title',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textarea',
					'heading' => esc_html__( 'Text on marker', 'woodmart' ),
					'param_name' => 'marker_text',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				/**
				* Content settings
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Content settings', 'woodmart' ),
					'param_name' => 'content_set_divider'
				),
				array(
					'type' => 'woodmart_image_select',
					'heading' => esc_html__( 'Content on the map horizontal position', 'woodmart' ),
					'param_name' => 'content_horizontal',
					'value' => array( 
						esc_html__( 'Left', 'woodmart' ) => 'left',
						esc_html__( 'Center', 'woodmart' ) => 'center',
						esc_html__( 'Right', 'woodmart' ) => 'right'
					),
					'images_value' => array(
						'left' => WOODMART_ASSETS_IMAGES . '/settings/content-align/horizontal/left.png',
						'center' => WOODMART_ASSETS_IMAGES . '/settings/content-align/horizontal/center.png',
						'right' => WOODMART_ASSETS_IMAGES . '/settings/content-align/horizontal/right.png',
					),
					'std' => 'left',
					'wood_tooltip' => true,
					'edit_field_class' => 'vc_col-sm-6 vc_column content-position',
				),
				array(
					'type' => 'woodmart_image_select',
					'heading' => esc_html__( 'Content on the map vertical position', 'woodmart' ),
					'param_name' => 'content_vertical',
					'value' => array( 
						esc_html__( 'Top', 'woodmart' ) => 'top',
						esc_html__( 'Middle', 'woodmart' ) => 'middle',
						esc_html__( 'Bottom', 'woodmart' ) => 'bottom'
					),
					'images_value' => array(
						'top' => WOODMART_ASSETS_IMAGES . '/settings/content-align/vertical/top.png',
						'middle' => WOODMART_ASSETS_IMAGES . '/settings/content-align/vertical/middle.png',
						'bottom' => WOODMART_ASSETS_IMAGES . '/settings/content-align/vertical/bottom.png',
					),
					'std' => 'top',
					'wood_tooltip' => true,
					'edit_field_class' => 'vc_col-sm-6 vc_column content-position',
				),
				array(
					'type' => 'woodmart_slider',
					'heading' => esc_html__( 'Content width', 'woodmart' ),
					'param_name' => 'content_width',
					'min' => '100',
					'max' => '2000',
					'step' => '10',
					'default' => '300',
					'units' => 'px',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'hint' => esc_html__( 'Default: 300', 'woodmart' )
				),
				/**
				* Map settings
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Map settings', 'woodmart' ),
					'param_name' => 'map_set_divider'
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Map mask', 'woodmart' ),
					'hint' => esc_html__( 'Add an overlay to your map to make the content look cleaner on the map.', 'woodmart' ),
					'param_name' => 'mask',
					'value' => array(
						esc_html__( 'Without', 'woodmart' ) => '',
						esc_html__( 'Dark', 'woodmart' ) => 'dark',
						esc_html__( 'Light', 'woodmart' ) => 'light',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_slider',
					'heading' => esc_html__( 'Zoom', 'woodmart' ),
					'param_name' => 'zoom',
					'min' => '0',
					'max' => '19',
					'step' => '1',
					'default' => '15',
					'units' => '',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'hint' => esc_html__( 'Zoom level when focus the marker<br> 0 - 19', 'woodmart' )
				),
				array(
					'type' => 'woodmart_slider',
					'heading' => esc_html__( 'Map height', 'woodmart' ),
					'param_name' => 'height',
					'min' => '100',
					'max' => '2000',
					'step' => '10',
					'default' => '400',
					'units' => 'px',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'hint' => esc_html__( 'Default: 400', 'woodmart' )
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Zoom with mouse wheel', 'woodmart' ),
					'param_name' => 'scroll',
					'true_state' => 'yes',
					'false_state' => 'no',
					'default' => 'no',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textarea_raw_html',
					'heading' => esc_html__( 'Styles (JSON)', 'woodmart' ),
					'param_name' => 'style_json',
					'description' => 'Styled maps allow you to customize the presentation of the standard Google base maps, changing the visual display of such elements as roads, parks, and built-up areas.<br>
You can find more Google Maps styles on the website: <a target="_blank" href="http://snazzymaps.com/">Snazzy Maps</a><br>
Just copy JSON code and paste it here<br>
For example:<br>
[{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#46bcec"},{"visibility":"on"}]}]
					'
				),
				/**
				* Loading settings
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Lazy loading settings', 'woodmart' ),
					'param_name' => 'loading_set_divider'
				),
				array(
					'type' => 'woodmart_button_set',
					'heading' => esc_html__( 'Init event', 'woodmart' ),
					'param_name' => 'init_type',
					'value' =>  array(
						esc_html__( 'On page load', 'woodmart' ) => 'page_load',
	                    esc_html__( 'On scroll', 'woodmart' ) => 'scroll',
						esc_html__( 'On button click', 'woodmart' ) => 'button',
						esc_html__( 'On user interaction', 'woodmart' ) => 'interaction',
					),
					'hint' => esc_html__( 'For a better performance you can initialize the Google map only when you scroll down the page or when you click on it.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_slider',
					'heading' => esc_html__( 'Scroll offset', 'woodmart' ),
					'param_name' => 'init_offset',
					'min' => '0',
					'max' => '1000',
					'step' => '10',
					'default' => '100',
					'units' => 'px',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'hint' => esc_html__( 'Default: 100', 'woodmart' ),
					'dependency' => array(
						'element' => 'init_type',
						'value' => array( 'scroll' ),
					),
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__( 'Placeholder', 'woodmart' ),
					'param_name' => 'map_init_placeholder',
					'value' => '',
					'hint' => esc_html__( 'Select image from media library.', 'woodmart' ),
					'dependency' => array(
						'element' => 'init_type',
						'value' => array( 'scroll', 'button', 'interaction' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Placeholder size', 'woodmart' ),
					'param_name' => 'map_init_placeholder_size',
					'hint' => esc_html__( 'Enter image size. Example: \'thumbnail\', \'medium\', \'large\', \'full\' or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use \'thumbnail\' size.', 'woodmart' ),
					'dependency' => array(
						'element' => 'init_type',
						'value' => array( 'scroll', 'button', 'interaction' ),
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
	add_action( 'vc_before_init', 'woodmart_vc_map_google_map' );
}

// A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
if( class_exists( 'WPBakeryShortCodesContainer' ) ){
    class WPBakeryShortCode_woodmart_google_map extends WPBakeryShortCodesContainer {

    }
}
