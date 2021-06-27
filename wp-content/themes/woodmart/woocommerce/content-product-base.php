<?php 
global $product;

$action_classes  = '';
$add_btn_classes = 'wd-add-btn-replace';

woodmart_enqueue_js_script( 'btns-tooltips' );
woodmart_enqueue_js_library( 'tooltips' );

if ( 'carousel' === woodmart_loop_prop('products_view') ) {
	$action_classes  .= ' wd-buttons wd-pos-r-t';
	$action_classes  .= woodmart_get_old_classes( ' woodmart-buttons' );
	$add_btn_classes  = 'wd-action-btn wd-style-icon wd-add-cart-icon' . woodmart_get_old_classes( ' wd-add-cart-btn' );
} else {
	$action_classes  .= ' wd-bottom-actions';
}

$add_btn_classes .= woodmart_get_old_classes( ' woodmart-add-btn' );

do_action( 'woocommerce_before_shop_loop_item' ); ?>

<div class="product-wrapper">
	<div class="content-product-imagin"></div>
	<div class="product-element-top">
		<a href="<?php echo esc_url( get_permalink() ); ?>" class="product-image-link">
			<?php
				/**
				 * woocommerce_before_shop_loop_item_title hook
				 *
				 * @hooked woocommerce_show_product_loop_sale_flash - 10
				 * @hooked woodmart_template_loop_product_thumbnail - 10
				 */
				do_action( 'woocommerce_before_shop_loop_item_title' );
			?>
		</a>
		<?php woodmart_hover_image(); ?>
		<div class="wrapp-swatches"><?php echo woodmart_swatches_list();?><?php woodmart_add_to_compare_loop_btn(); ?></div>
		<?php woodmart_quick_shop_wrapper(); ?>
	</div>

	<div class="product-information">
		<?php
			/**
			 * woocommerce_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_product_title - 10
			 */
			
			do_action( 'woocommerce_shop_loop_item_title' );
		?>
		<?php
			woodmart_product_categories();
			woodmart_product_brands_links();
		?>
		<div class="product-rating-price">
			<div class="wrapp-product-price">
				<?php
					/**
					 * woocommerce_after_shop_loop_item_title hook
					 *
					 * @hooked woocommerce_template_loop_rating - 5
					 * @hooked woocommerce_template_loop_price - 10
					 */
					do_action( 'woocommerce_after_shop_loop_item_title' );
				?>
			</div>
		</div>
		<div class="fade-in-block">
			<div class="hover-content wd-more-desc<?php echo woodmart_get_old_classes( ' woodmart-more-desc' ); ?>">
				<div class="hover-content-inner wd-more-desc-inner<?php echo woodmart_get_old_classes( ' woodmart-more-desc-inner' ); ?>">
					<?php 
						if ( woodmart_get_opt( 'base_hover_content' ) == 'excerpt' ) {
							echo do_shortcode( get_the_excerpt() );
						}else if ( woodmart_get_opt( 'base_hover_content' ) == 'additional_info' ){
							wc_display_product_attributes( $product );
						}
					?>
				</div>
				<a href="#" rel="noffollow" class="wd-more-desc-btn<?php echo woodmart_get_old_classes( ' woodmart-more-desc-btn' ); ?>"><span></span></a>
			</div>
			<div class="<?php echo esc_attr( $action_classes ); ?>">
				<div class="wrap-wishlist-button"><?php do_action( 'woodmart_product_action_buttons' ); ?></div>
				<div class="wd-add-btn <?php echo esc_attr( $add_btn_classes ); ?>"><?php do_action( 'woocommerce_after_shop_loop_item' ); ?></div>
				<div class="wrap-quickview-button"><?php woodmart_quick_view_btn( get_the_ID() ); ?></div>
			</div>
			<?php if ( woodmart_loop_prop( 'progress_bar' ) ): ?>
				<?php woodmart_stock_progress_bar(); ?>
			<?php endif ?>
			
			<?php if ( woodmart_loop_prop( 'timer' ) ): ?>
				<?php woodmart_product_sale_countdown(); ?>
			<?php endif ?>
		</div>
	</div>
</div>
