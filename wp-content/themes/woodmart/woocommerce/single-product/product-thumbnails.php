<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.5.1
 */

defined( 'ABSPATH' ) || exit;

global $post, $product;

$is_quick_view = woodmart_loop_prop( 'is_quick_view' );

$attachment_ids = $product->get_gallery_image_ids();

$thums_position = woodmart_get_opt( 'thums_position' );

$product_design = woodmart_product_design();

// Full size images for sticky product design
if ( $product_design == 'sticky' ) {
	$thums_position = 'bottom';
}

if ( $attachment_ids && $product->get_image_id() ) {
	foreach ( $attachment_ids as $attachment_id ) {
		$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
		$thumbnail_size    = apply_filters(
			'woocommerce_gallery_thumbnail_size',
			array(
				$gallery_thumbnail['width'],
				$gallery_thumbnail['height'],
			)
		);
		$full_size_image   = wp_get_attachment_image_src( $attachment_id, 'full' );
		$thumbnail         = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );

		$attributes = array(
			'title'                   => get_post_field( 'post_title', $attachment_id ),
			'data-caption'            => get_post_field( 'post_excerpt', $attachment_id ),
			'data-src'                => $full_size_image[0] ? $full_size_image[0] : '',
			'data-large_image'        => $full_size_image[0] ? $full_size_image[0] : '',
			'data-large_image_width'  => $full_size_image[1] ? $full_size_image[1] : '',
			'data-large_image_height' => $full_size_image[2] ? $full_size_image[2] : '',
			'class'                   => apply_filters( 'woodmart_single_product_gallery_image_class', '' ),
		);

		$html  = '<div class="product-image-wrap"><figure data-thumb="' . esc_url( $thumbnail[0] ) . '" class="woocommerce-product-gallery__image"><a data-elementor-open-lightbox="no" href="' . esc_url( $full_size_image[0] ? $full_size_image[0] : '' ) . '">';
		$html .= wp_get_attachment_image( $attachment_id, 'woocommerce_single', false, $attributes );
		$html .= '</a></figure></div>';

		echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );
	}
}
