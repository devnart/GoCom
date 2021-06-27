<?php
/**
 * Cross-sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cross-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     4.4.0
 */

defined( 'ABSPATH' ) || exit;

$heading = apply_filters( 'woocommerce_product_cross_sells_products_heading', __( 'You may be interested in&hellip;', 'woocommerce' ) );

if ( $cross_sells ) : ?>

	<div class="cross-sells">
		
		<?php if ( $heading ) : ?>
			<h3 class="title slider-title"><?php echo esc_html( $heading ); ?></h3>
		<?php endif; ?>

		<?php
			woodmart_enqueue_inline_style( 'product-loop' );

			$slider_args = array(
				'slides_per_view' => apply_filters( 'woodmart_cross_sells_products_per_view', 4 ),
				'hide_pagination_control' => true,
				'hide_prev_next_buttons' => true,
				'carousel_js_inline' => 'yes',
				'img_size' => 'woocommerce_thumbnail',
				'custom_sizes' => apply_filters( 'woodmart_cross_sells_custom_sizes', false ),
				'product_quantity' => woodmart_get_opt( 'product_quantity' )
			);
		
			woodmart_set_loop_prop( 'products_view', 'carousel' );

			echo woodmart_generate_posts_slider( $slider_args, false, $cross_sells );
			
		?>

	</div>

<?php endif;

wp_reset_postdata();