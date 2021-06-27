<?php
/**
 * The template for displaying product widget entries.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-widget-product.php.
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product, $woodmart_widget_product_img_size, $woodmart_widget_product_img_size_custom;

//WC 3.5.0
if ( function_exists( 'WC' ) && version_compare( WC()->version, '3.5.0', '>=' ) && ! is_a( $product, 'WC_Product' ) ) {
	return;
}

if ( $woodmart_widget_product_img_size && function_exists( 'wpb_getImageBySize' ) && 'wpb' === woodmart_get_opt( 'page_builder', 'wpb' ) ) {
	$image_data = wpb_getImageBySize( array( 'attach_id' => get_post_thumbnail_id( $product->get_id() ), 'thumb_size' => $woodmart_widget_product_img_size ) ); 
	$img = isset( $image_data['thumbnail'] ) ? $image_data['thumbnail'] : '';
} elseif ( $woodmart_widget_product_img_size && woodmart_is_elementor_installed() ) {
	$img = woodmart_get_image_html( // phpcs:ignore
		array(
			'image_size'             => $woodmart_widget_product_img_size,
			'image_custom_dimension' => $woodmart_widget_product_img_size_custom,
			'image'                  => array(
				'id' => get_post_thumbnail_id( $product->get_id() ),
			),
		),
		'image'
	);
} else {
	$img = $product->get_image();
}

?>
<li>
	<?php do_action( 'woocommerce_widget_product_item_start', $args ); ?>
	<span class="widget-product-wrap">
		<a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>" class="widget-product-img">
			<?php echo apply_filters( 'woodmart_product_widget_image', $img ); ?>
		</a>
		<span class="widget-product-info">
			<a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>" class="wd-entities-title">
				<?php echo wp_kses_post( $product->get_title() ); ?>
			</a>
			<?php if ( ! empty( $show_rating ) ) : ?>
				<?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
			<?php endif; ?>
			<span class="price"><?php echo apply_filters( 'woodmart_product_widget_price_html', $product->get_price_html() ); ?></span>
		</span>
	</span>
	<?php do_action( 'woocommerce_widget_product_item_end', $args ); ?>
</li>
