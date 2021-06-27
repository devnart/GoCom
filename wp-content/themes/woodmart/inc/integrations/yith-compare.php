<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Compare button
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_configure_compare' ) ) {
	add_action( 'init', 'woodmart_configure_compare' );
	function woodmart_configure_compare() {
		global $yith_woocompare;

		add_action( 'woocommerce_single_product_summary', 'woodmart_add_to_compare_single_btn', 33 );

		if ( class_exists( 'YITH_Woocompare' ) ) {
			$compare = $yith_woocompare->obj;
			remove_action( 'woocommerce_after_shop_loop_item', array( $compare, 'add_compare_link' ), 20 );
			remove_action( 'woocommerce_single_product_summary', array( $compare, 'add_compare_link' ), 35 );
		}
	}
}

if( ! function_exists( 'woodmart_compare_add_product_url' ) ) {
    function woodmart_compare_add_product_url( $product_id ) {
    	$action_add = 'yith-woocompare-add-product';
        $url_args = array(
            'action' => 'yith-woocompare-add-product',
            'id' => $product_id
        );
        return apply_filters( 'yith_woocompare_add_product_url', esc_url_raw( add_query_arg( $url_args ) ), $action_add );
    }
}

if( ! function_exists( 'woodmart_compare_styles' ) ) {
	add_action( 'wp_print_styles', 'woodmart_compare_styles', 200 );
	function woodmart_compare_styles() {
		if( ! class_exists( 'YITH_Woocompare' ) ) return;
		$view_action = 'yith-woocompare-view-table';
		if ( ( ! defined('DOING_AJAX') || ! DOING_AJAX ) && ( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != $view_action ) ) return;
		wp_enqueue_style( 'woodmart-style' );
		wp_enqueue_style( 'woodmart-dynamic-style' );
	}
}
