<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Function returns quick shop of the product by ID. Variations form HTML
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_quick_shop' ) ) {
	function woodmart_quick_shop($id = false) {
		if( isset($_GET['id']) ) {
			$id = sanitize_text_field( (int) $_GET['id'] );
		}
		if( ! $id || ! woodmart_woocommerce_installed() ) {
			return;
		}

		global $post;

		$args = array( 'post__in' => array($id), 'post_type' => 'product' );

		$quick_posts = get_posts( $args );

		$quick_view_variable = woodmart_get_opt( 'quick_view_variable' );

		foreach( $quick_posts as $post ) :
			setup_postdata($post);
        	woocommerce_template_single_add_to_cart();
		endforeach; 

		wp_reset_postdata(); 

		die();
	}

	add_action( 'wp_ajax_woodmart_quick_shop', 'woodmart_quick_shop' );
	add_action( 'wp_ajax_nopriv_woodmart_quick_shop', 'woodmart_quick_shop' );

}

/**
 * ------------------------------------------------------------------------------------------------
 * Quick shop element
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_quick_shop_wrapper' ) ) {
	function woodmart_quick_shop_wrapper() {
		?>
			<div class="quick-shop-wrapper wd-fill wd-scroll">
				<div class="quick-shop-close wd-action-btn wd-style-text wd-cross-icon"><a href="#" rel="nofollow noopener"><?php esc_html_e('Close', 'woodmart'); ?></a></div>
				<div class="quick-shop-form wd-scroll-content">
				</div>
			</div>
		<?php
	}
}
