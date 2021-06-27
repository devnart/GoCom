<?php
/**
 * Cookies storage.
 */

namespace XTS\WC_Wishlist;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}

use XTS\WC_Wishlist\Storage;

/**
 * Cookies storage.
 *
 * @since 1.0.0
 */
class Cookies_Storage implements Storage {

	/**
	 * Cookie name.
	 *
	 * @var string
	 */
	private $cookie_name = '';

	/**
	 * Set cookie name in the constructor.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->cookie_name = 'woodmart_wishlist_products';
		if ( is_multisite() ) {
			$this->cookie_name .= '_' . get_current_blog_id();
		}
	}

	/**
	 * Add product to the wishlist.
	 *
	 * @since 1.0
	 *
	 * @param integer $product_id Product id.
	 *
	 * @return boolean
	 */
	public function add( $product_id ) {
		$all = $this->get_all();

		if ( $this->is_product_exists( $product_id ) ) {
			return false;
		}

		$all[ $product_id ] = array(
			'product_id' => $product_id,
		);

		woodmart_set_cookie( $this->cookie_name, wp_json_encode( $all ) );

		return true;
	}

	/**
	 * Remove product from the wishlist.
	 *
	 * @since 1.0
	 *
	 * @param integer $product_id Product id.
	 *
	 * @return boolean
	 */
	public function remove( $product_id ) {
		$all = $this->get_all();

		if ( ! $this->is_product_exists( $product_id ) ) {
			return false;
		}

		if ( isset( $all[ $product_id ] ) ) {
			unset( $all[ $product_id ] );
			woodmart_set_cookie( $this->cookie_name, wp_json_encode( $all ) );
		}

		return true;
	}

	/**
	 * Get all products.
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public function get_all() {
		$cookie = woodmart_get_cookie( $this->cookie_name );

		if ( $cookie ) {
			return json_decode( wp_unslash( $cookie ), true );
		}

		return array();
	}

	/**
	 * Is product in compare.
	 *
	 * @since 1.0
	 *
	 * @param integer $product_id Product id.
	 *
	 * @return boolean
	 */
	public function is_product_exists( $product_id ) {
		$all = $this->get_all();

		return isset( $all[ $product_id ] );
	}

}
