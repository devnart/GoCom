<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Function returns quick view of the product by ID
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_quick_view' ) ) {
	function woodmart_quick_view($id = false) {
		if( isset($_GET['id']) ) {
			$id = sanitize_text_field( (int) $_GET['id'] );
		}
		if( ! $id || ! woodmart_woocommerce_installed() ) {
			return;
		}

		global $post, $product;


		$args = array( 'post__in' => array($id), 'post_type' => 'product' );

		$quick_posts = get_posts( $args );

		$quick_view_variable = woodmart_get_opt( 'quick_view_variable' );
		$quick_view_layout = ( woodmart_get_opt( 'quick_view_layout' ) ) ? woodmart_get_opt( 'quick_view_layout' ) : 'horizontal';

		echo '<div class="mfp-with-anim wd-popup popup-quick-view">';

		foreach( $quick_posts as $post ) :
			setup_postdata($post);
			$product = wc_get_product($post);
        	remove_action( 'woocommerce_single_product_summary', 'woodmart_before_compare_button', 33 );
        	remove_action( 'woocommerce_single_product_summary', 'woodmart_add_to_compare_single_btn', 33 );
			remove_action( 'woocommerce_single_product_summary', 'woodmart_after_compare_button', 37 );
			
			if ( woodmart_get_opt( 'attr_after_short_desc' ) ) {
				add_action( 'woocommerce_single_product_summary', 'woodmart_display_product_attributes', 21 );
				add_filter( 'woocommerce_product_tabs', 'woodmart_remove_additional_information_tab', 98 );
			}
			
			// Remove wishlist.
			remove_action( 'woocommerce_single_product_summary', array( XTS\WC_Wishlist\UI::get_instance(), 'add_to_wishlist_single_btn' ), 33 );
			
			//Remove before and after add to cart button text
			remove_action( 'woocommerce_single_product_summary', 'woodmart_before_add_to_cart_area', 25 );
			remove_action( 'woocommerce_single_product_summary', 'woodmart_after_add_to_cart_area', 31 );
			
        	remove_action( 'woocommerce_before_single_product', 'wc_print_notices', 10 );

        	// Add brand image
        	add_action( 'woocommerce_single_product_summary', 'woodmart_product_brand', 8 );

        	// Disable add to cart button for catalog mode
			if( woodmart_get_opt( 'catalog_mode' ) ) {
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			} elseif( ! $quick_view_variable ) {
				// If no needs to show variations
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_loop_add_to_cart', 30 );
			}

			if( woodmart_get_opt( 'product_share' ) ) add_action( 'woocommerce_single_product_summary', 'woodmart_product_share_buttons', 45 );
			get_template_part('woocommerce/content', 'quick-view-' . $quick_view_layout );
		endforeach;

		echo '</div>';

		wp_reset_postdata(); 

		die();
	}

	add_action( 'wp_ajax_woodmart_quick_view', 'woodmart_quick_view' );
	add_action( 'wp_ajax_nopriv_woodmart_quick_view', 'woodmart_quick_view' );

}

if( ! function_exists( 'woodmart_product_images_slider' ) ) {
	function woodmart_product_images_slider() {
		wc_get_template( 'quick-view/product-images.php' );
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Quick View button
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_quick_view_btn' ) ) {
	function woodmart_quick_view_btn( $id = false ) {
		if( ! $id ) $id = get_the_ID();
		woodmart_enqueue_js_library( 'owl' );
		woodmart_enqueue_js_library( 'magnific' );
		woodmart_enqueue_inline_style( 'mfp-popup' );
		woodmart_enqueue_js_script( 'product-images-gallery' );
		woodmart_enqueue_js_script( 'quick-view' );
		woodmart_enqueue_js_library( 'tooltips' );
		woodmart_enqueue_js_script( 'btns-tooltips' );
		woodmart_enqueue_js_script( 'swatches-variations' );
		woodmart_enqueue_js_script( 'add-to-cart-all-types' );
		woodmart_enqueue_js_script( 'woocommerce-quantity' );
		wp_enqueue_script( 'wc-add-to-cart-variation' );
		wp_enqueue_script( 'imagesloaded' );

		if ( woodmart_get_opt( 'quick_view' ) ): ?>
			<div class="quick-view wd-action-btn wd-style-icon wd-quick-view-icon<?php echo woodmart_get_old_classes( ' wd-quick-view-btn' ); ?>">
				<a 
					href="<?php echo esc_url( get_the_permalink( $id ) ); ?>" 
					class="open-quick-view quick-view-button"
					data-id="<?php echo esc_attr( $id ); ?>"><?php esc_html_e( 'Quick view', 'woodmart' ); ?></a>
			</div>
		<?php endif;
	}
}
