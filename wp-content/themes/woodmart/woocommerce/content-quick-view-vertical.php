<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;

$product = wc_get_product( $post );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

$classes = array();
$classes[] = 'product-quick-view single-product-content quick-view-vertical';

woodmart_set_loop_prop( 'is_quick_view', 'quick-view' );

/**
 * woocommerce_before_single_product hook
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}

if ( function_exists( 'woodmart_quick_view_vg_data' ) ) {
	woodmart_quick_view_vg_data(true);
}

?>

<div id="product-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

	<div class="row product-image-summary">
		<div class="col-lg-12 col-md-12 col-12 product-images woocommerce-product-gallery">
			<?php
				woodmart_product_images_slider();
				woodmart_view_product_button();
			?>
		</div>
		<div class="col-lg-12 col-md-12 col-12 summary entry-summary">
			<div class="summary-inner wd-scroll">
				<div class="wd-scroll-content">
					<?php
						/**
						 * woocommerce_single_product_summary hook
						 *
						 * @hooked woocommerce_template_single_title - 5
						 * @hooked woocommerce_template_single_rating - 10
						 * @hooked woocommerce_template_single_price - 10
						 * @hooked woocommerce_template_single_excerpt - 20
						 * @hooked woocommerce_template_loop_add_to_cart - 30
						 * @hooked woocommerce_template_single_meta - 40
						 * @hooked woocommerce_template_single_sharing - 50
						 */
						do_action( 'woocommerce_single_product_summary' );
					?>
				</div>
			</div>
		</div><!-- .summary -->
	</div>


</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
