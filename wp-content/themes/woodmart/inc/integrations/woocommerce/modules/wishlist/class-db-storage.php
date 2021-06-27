<?php
/**
 * Database storage.
 */

namespace XTS\WC_Wishlist;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}

use XTS\WC_Wishlist\Storage;
use XTS\Modules\WC_Wishlist;

/**
 * Database storage.
 *
 * @since 1.0.0
 */
class DB_Storage implements Storage {

	/**
	 * Wishlist id.
	 *
	 * @var int
	 */
	private $wishlist_id = 0;

	/**
	 * User id.
	 *
	 * @var int
	 */
	private $user_id = 0;

	/**
	 * Transient name.
	 *
	 * @var string
	 */
	private $cache_name = '';

	/**
	 * Is tables installed.
	 *
	 * @var string
	 */
	private $is_table_exists;

	/**
	 * Set cookie name in the constructor.
	 *
	 * @since 1.0
	 *
	 * @param integer $wishlist_id Wishlist id.
	 * @param integer $user_id User id.
	 *
	 * @return void
	 */
	public function __construct( $wishlist_id, $user_id ) {
		$this->wishlist_id = $wishlist_id;
		$this->user_id     = $user_id;
		$this->cache_name  = 'woodmart_wishlist_' . $this->wishlist_id;
		$this->is_table_exists = $this->is_table_exists();
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
		global $wpdb;

		if ( $this->is_product_exists( $product_id ) ) {
			return false;
		}

		if ( ! $this->wishlist_id ) {
			return false;
		}

		if ( ! $this->is_table_exists ) {
			return;
		}

		delete_user_meta( $this->user_id, $this->cache_name );

		return $wpdb->insert(
			$wpdb->woodmart_products_table,
			array(
				'product_id'  => $product_id,
				'wishlist_id' => $this->wishlist_id,
			),
			array(
				'%d',
				'%d',
			)
		);
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
		global $wpdb;

		if ( ! $this->is_product_exists( $product_id ) ) {
			return false;
		}

		if ( ! $this->is_table_exists ) {
			return;
		}

		delete_user_meta( $this->user_id, $this->cache_name );

		return $wpdb->delete(
			$wpdb->woodmart_products_table,
			array(
				'product_id'  => $product_id,
				'wishlist_id' => $this->wishlist_id,
			),
			array( '%d', '%d' )
		);
	}

	/**
	 * Get all products.
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public function get_all() {
		global $wpdb;

		if ( ! $this->wishlist_id ) {
			return array();
		}

		if ( ! $this->is_table_exists ) {
			return;
		}

		$cache = get_user_meta( $this->user_id, $this->cache_name, true );

		if ( empty( $cache ) || $cache['expires'] < time() ) {

			$products = $wpdb->get_results(
				$wpdb->prepare(
					"
						SELECT *
						FROM $wpdb->woodmart_products_table
						WHERE wishlist_id = %d
					",
					$this->wishlist_id
				),
				ARRAY_A
			);

			$cache = array(
				'expires'  => time() + WEEK_IN_SECONDS,
				'products' => $products,
			);

			update_user_meta( $this->user_id, $this->cache_name, $cache );
		}

		return $cache['products'];
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
		global $wpdb;

		if ( ! $this->is_table_exists ) {
			return;
		}

		$id = $wpdb->get_var(
			$wpdb->prepare(
				"
				SELECT ID
				FROM $wpdb->woodmart_products_table
				WHERE wishlist_id = %d
				AND product_id = %d
			",
				$this->wishlist_id,
				$product_id
			)
		);

		return ! is_null( $id );
	}

	/**
	 * Is tables installed..
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	public function is_table_exists() {
		global $wpdb;
		$products_table_count = $wpdb->query( "SHOW TABLES LIKE '{$wpdb->woodmart_products_table}%'" );//phpcs:ignore

		return (bool) ( 1 === $products_table_count );
	}
}
