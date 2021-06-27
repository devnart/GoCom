<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
*  360 View element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_vc_map_3d_view' ) ) {
	function woodmart_vc_map_3d_view() {
		if ( ! shortcode_exists( 'woodmart_3d_view' ) ) {
			return;
		}

		vc_map( array(
			'name' => esc_html__( '360 degree view', 'woodmart' ),
			'base' => 'woodmart_3d_view',
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'description' => esc_html__( 'Showcase your product as 3D model', 'woodmart' ),
        	'icon' => WOODMART_ASSETS . '/images/vc-icon/360-degree.svg',
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title', 'woodmart' ),
					'param_name' => 'title',
				),
				array(
					'type' => 'attach_images',
					'heading' => esc_html__( 'Images', 'woodmart' ),
					'param_name' => 'images',
					'value' => '',
					'hint' => esc_html__( 'Select images from media library. All images should represent your product from different angles of view.', 'woodmart' )
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
	add_action( 'vc_before_init', 'woodmart_vc_map_3d_view' );
}
