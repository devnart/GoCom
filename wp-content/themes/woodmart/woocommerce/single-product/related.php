<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$related_product_view = woodmart_get_opt( 'related_product_view' );

if ( $related_products ) : ?>

	<div class="related-products">
		
		<?php
		$heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Related products', 'woocommerce' ) );
		
		if ( $heading ) :
			?>
		<h3 class="title slider-title"><?php echo esc_html( $heading ); ?></h3>
		<?php endif; ?>
		
		<?php
			woodmart_enqueue_inline_style( 'product-loop' );
			if ( $related_product_view == 'slider' ) {
				$slider_args = array(
					'slides_per_view' => ( woodmart_get_opt( 'related_product_columns' ) ) ? woodmart_get_opt( 'related_product_columns' ) : apply_filters( 'woodmart_related_products_per_view', 4 ),
					'img_size' => 'woocommerce_thumbnail',
					'products_bordered_grid' => woodmart_get_opt( 'products_bordered_grid' ),
					'custom_sizes' => apply_filters( 'woodmart_product_related_custom_sizes', false ),
					'product_quantity' => woodmart_get_opt( 'product_quantity' )
				);
				
				woodmart_set_loop_prop( 'products_view', 'carousel' );

				echo woodmart_generate_posts_slider( $slider_args, false, $related_products );
			}elseif ( $related_product_view == 'grid' ) {
		
				woodmart_set_loop_prop( 'products_columns', woodmart_get_opt( 'related_product_columns' ) );
				woodmart_set_loop_prop( 'products_different_sizes', false );
				woodmart_set_loop_prop( 'products_masonry', false );
				woodmart_set_loop_prop( 'products_view', 'grid' );
				
				woocommerce_product_loop_start();

				foreach ( $related_products as $related_product ) {
					$post_object = get_post( $related_product->get_id() );

					setup_postdata( $GLOBALS['post'] = $post_object );

					wc_get_template_part( 'content', 'product' ); 
				}

				woocommerce_product_loop_end();
				
				woodmart_reset_loop();
				
				if ( function_exists( 'woocommerce_reset_loop' ) ) woocommerce_reset_loop();
			}
			
		?>
		
	</div>

<?php endif;

wp_reset_postdata();