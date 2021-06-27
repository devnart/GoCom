<?php

/**
 * ------------------------------------------------------------------------------------------------
 * WC Marketplace style fix
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_wcmp_dequeue_styles' ) ) {
	function woodmart_wcmp_dequeue_styles() {
		if ( class_exists( 'WCMp' ) && is_vendor_dashboard() && ( is_user_wcmp_vendor( get_current_user_id() ) || is_user_wcmp_pending_vendor( get_current_user_id() ) || is_user_wcmp_rejected_vendor( get_current_user_id() ) ) ) {
			wp_dequeue_style( 'bootstrap' );
			wp_deregister_style('bootstrap');
		}
	}

	add_action( 'wp_enqueue_scripts', 'woodmart_wcmp_dequeue_styles', 10005 );
}

if ( ! function_exists( 'woodmart_wcmp_custom_css' ) ) {
	function woodmart_wcmp_custom_css() {
		if ( class_exists( 'WCMp' ) && is_vendor_dashboard() && ( is_user_wcmp_vendor( get_current_user_id() ) || is_user_wcmp_pending_vendor( get_current_user_id() ) || is_user_wcmp_rejected_vendor( get_current_user_id() ) ) ) {
			$css = apply_filters( 'woodmart_wcmp_custom_css', 'body[class*="wcmp"] .cart-widget-side,body[class*="wcmp"] .mobile-nav,body[class*="wcmp"] .wd-cookies-popup,body[class*="wcmp"] .pswp,body[class*="wcmp"] .scrollToTop,body[class*="wcmp"] .wd-search-full-screen,body[class*="wcmp"] .wd-promo-popup {display: none;} body[class*="wcmp"] .amount {color: inherit;}' );
			
			echo '<style data-type="woodmart-wcmp-custom-css">' . $css . '</style>';
		}
	}

	add_action( 'wp_enqueue_scripts', 'woodmart_wcmp_custom_css', 10010 );
}
