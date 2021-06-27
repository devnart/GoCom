<?php
if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * ------------------------------------------------------------------------------------------------
 *  Wishlist element map
 * ------------------------------------------------------------------------------------------------
 */
if ( ! function_exists( 'woodmart_vc_map_wishlist' ) ) {
	function woodmart_vc_map_wishlist() {
		vc_map(
			array(
				'name'        => esc_html__( 'Wishlist', 'woodmart' ),
				'base'        => 'woodmart_wishlist',
				'category'    => esc_html__( 'Theme elements', 'woodmart' ),
				'description' => esc_html__( 'Required for the wishlist page.', 'woodmart' ),
				'icon'        => WOODMART_ASSETS . '/images/vc-icon/wishlist.svg',
			)
		);
	}
	
	add_action( 'vc_before_init', 'woodmart_vc_map_wishlist' );
}
