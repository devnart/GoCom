<?php
if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
* ------------------------------------------------------------------------------------------------
*  Compare element map
* ------------------------------------------------------------------------------------------------
*/
if ( ! function_exists( 'woodmart_vc_map_compare' ) ) {
	function woodmart_vc_map_compare() {
		if ( ! shortcode_exists( 'woodmart_compare' ) ) {
			return;
		}

		vc_map(
			array(
				'name'        => esc_html__( 'Compare', 'woodmart' ),
				'base'        => 'woodmart_compare',
				'category'    => esc_html__( 'Theme elements', 'woodmart' ),
				'description' => esc_html__( 'Required for the compare table page.', 'woodmart' ),
				'icon'        => WOODMART_ASSETS . '/images/vc-icon/compare.svg',
			)
		);
	}
	add_action( 'vc_before_init', 'woodmart_vc_map_compare' );
}
