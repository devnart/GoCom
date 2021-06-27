<?php

if ( ! defined( 'IMAGIFY_VERSION' ) ) {
	return;
}

if ( ! function_exists( 'woodmart_single_product_gallery_images_webp' ) ) {
	/**
	 * @param $class
	 *
	 * @return string
	 */
	function woodmart_single_product_gallery_images_webp( $class ) {
		$class .= ' imagify-no-webp';

		return $class;
	}

	add_filter( 'woodmart_single_product_gallery_image_class', 'woodmart_single_product_gallery_images_webp' );
}
