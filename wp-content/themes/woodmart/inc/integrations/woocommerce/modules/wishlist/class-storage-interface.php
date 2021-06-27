<?php
/**
 * Storage interface.
 */

namespace XTS\WC_Wishlist;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * Storage interface.
 *
 * @since 1.0.0
 */
interface Storage {

	/**
	 * Add product to the wishlist.
	 *
	 * @since 1.0
	 *
	 * @param integer $product_id Product id.
	 *
	 * @return boolean
	 */
	public function add( $product_id );

	/**
	 * Remove product from the wishlist.
	 *
	 * @since 1.0
	 *
	 * @param integer $product_id Product id.
	 *
	 * @return boolean
	 */
	public function remove( $product_id );

	/**
	 * Get all products.
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public function get_all();

	/**
	 * Is product in compare.
	 *
	 * @since 1.0
	 *
	 * @param integer $product_id Product id.
	 *
	 * @return boolean
	 */
	public function is_product_exists( $product_id );
}
