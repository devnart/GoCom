<?php
/**
 * Wishlist.
 */

namespace XTS\WC_Wishlist;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}

use XTS\WC_Wishlist\DB_Storage;
use XTS\WC_Wishlist\Cookies_Storage;

/**
 * Wishlist.
 *
 * @since 1.0.0
 */
class Wishlist {

	/**
	 * Wishlist id.
	 *
	 * @var int
	 */
	private $id = 0;

	/**
	 * User id.
	 *
	 * @var int
	 */
	private $user_id = 0;

	/**
	 * Use cookies or db.
	 *
	 * @var int
	 */
	private $use_cookies = false;

	/**
	 * Storage object.
	 *
	 * @var object
	 */
	private $storage = null;


	/**
	 * Is tables installed.
	 *
	 * @var string
	 */
	private $is_table_exists;
	

	/**
	 * Set up wishlist object and storage.
	 *
	 * @since 1.0
	 *
	 * @param integer $id Wishlist id.
	 * @param integer $user_id User id.
	 * @param boolean $read_only Read only wishlist..
	 *
	 * @return void
	 */
	public function __construct( $id = false, $user_id = false, $read_only = false ) {
		$this->id      = $id;
		$this->user_id = $user_id;
		$this->is_table_exists = $this->is_table_exists();

		if ( $read_only ) {
			$this->user_id = $this->get_current_wishlist_user();
		} elseif ( ! $user_id ) {
			$this->user_id = $this->get_current_user_id();
		}

		if ( ! $id ) {
			$this->id = $this->get_current_user_wishlist();
		}

		if ( is_user_logged_in() && ! $this->has_wishlist() && ! $read_only ) {
			$this->create();
		}

		if ( ! is_user_logged_in() && ! $read_only ) {
			$this->use_cookies = true;
			$this->storage     = new Cookies_Storage();
		} else {
			$this->storage = new DB_Storage( $this->get_id(), $this->get_user_id() );
		}

		// Move products from cookies to database if you just logged in and clean cookie.
		if ( is_user_logged_in() && ! $read_only ) {
			$this->move_products_if_needed();
		}

		if ( get_queried_object_id() == woodmart_get_opt( 'wishlist_page' ) ) {
			$this->remove_unnecessary_products();
		}
	}
	
	/**
	 * Remove unnecessary products.
	 *
	 * @since 1.0
	 */
	public function remove_unnecessary_products() {
		$unnecessary_products = get_transient( 'wishlist_unnecessary_products' );
		
		if ( false !== $unnecessary_products ) {
			return;
		}
		
		foreach ( $this->get_all() as $product_data ) {
			$product_id = $product_data['product_id'];
			
			if ( 'publish' !== get_post_status( $product_id ) || 'product' !== get_post_type( $product_id ) ) {
				$this->remove( $product_id );
			}
		}
		
		$this->update_count_cookie();

		set_transient( 'wishlist_unnecessary_products', true, DAY_IN_SECONDS );
	}

	/**
	 * Get wishlist ID.
	 *
	 * @since 1.0
	 *
	 * @return integer
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get user id.
	 *
	 * @since 1.0
	 *
	 * @return integer
	 */
	public function get_user_id() {
		return $this->user_id;
	}

	/**
	 * Has wishlist in the database.
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	private function has_wishlist() {
		return $this->get_current_user_wishlist();
	}

	/**
	 * Get current user ID if logged in.
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	private function get_current_user_id() {

		if ( ! is_user_logged_in() ) {
			return false;
		}

		$current_user = wp_get_current_user();

		if ( ! $current_user->exists() ) {
			return false;
		}

		return $current_user->ID;
	}

	/**
	 * Get current wishlist ID from the database.
	 *
	 * @since 1.0
	 *
	 * @return integer
	 */
	private function get_current_user_wishlist() {
		global $wpdb;

		if ( ! is_user_logged_in() ) {
			return false;
		}

		if ( ! $this->is_table_exists ) {
			return;
		}

		$id = $wpdb->get_var(
			$wpdb->prepare(
				"
				SELECT ID
				FROM $wpdb->woodmart_wishlists_table
				WHERE user_id = %d
			",
				$this->get_user_id()
			)
		);

		return $id;
	}

	/**
	 * Get user for this wishlist.
	 *
	 * @since 1.0
	 *
	 * @return integer
	 */
	private function get_current_wishlist_user() {
		global $wpdb;

		if ( ! $this->is_table_exists ) {
			return;
		}

		$id = $wpdb->get_var(
			$wpdb->prepare(
				"
				SELECT user_id
				FROM $wpdb->woodmart_wishlists_table
				WHERE ID = %d
			",
				$this->get_id()
			)
		);

		return $id;
	}

	/**
	 * Create wishlist in the database.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	private function create() {
		global $wpdb;

		if ( ! $this->is_table_exists ) {
			return;
		}

		$wpdb->insert(
			$wpdb->woodmart_wishlists_table,
			array(
				'user_id' => $this->get_user_id(),
			),
			array(
				'%d',
			)
		);

		$this->id = $this->get_current_user_wishlist();
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
		$wishlists_table_count = $wpdb->query( "SHOW TABLES LIKE '{$wpdb->woodmart_wishlists_table}%'" );//phpcs:ignore

		return (bool) ( 1 === $wishlists_table_count );
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
		return $this->storage->add( $product_id );
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
		return $this->storage->remove( $product_id );
	}

	/**
	 * Get all products.
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public function get_all() {
		return $this->storage->get_all();
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
		return $this->storage->is_product_exists( $product_id );
	}

	/**
	 * Update count products cookie.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function update_count_cookie() {
		$cookie_name = 'woodmart_wishlist_count';

		if ( is_user_logged_in() ) {
			$cookie_name .= '_logged';
		}

		if ( is_multisite() ) {
			$cookie_name .= '_' . get_current_blog_id();
		}

		woodmart_set_cookie( $cookie_name, $this->get_count() );
	}

	/**
	 * Get number of products in the wishlist.
	 *
	 * @since 1.0
	 *
	 * @return integer
	 */
	public function get_count() {
		$all = $this->get_all();

		return count( $all );
	}

	/**
	 * Move products from cookie to database if needed.
	 *
	 * @since 1.0
	 *
	 * @return integer
	 */
	public function move_products_if_needed() {

		$cookie_storage = new Cookies_Storage();

		$cookie_products = $cookie_storage->get_all();
		
		if ( empty( $cookie_products ) ) {
			return false;
		}

		foreach ( $cookie_products as $item ) {
			$this->storage->add( $item['product_id'] );
			$cookie_storage->remove( $item['product_id'] );
		}

		$cookie_name = 'woodmart_wishlist_count';

		if ( is_multisite() ) {
			$cookie_name .= '_' . get_current_blog_id();
		}

		woodmart_set_cookie( $cookie_name, false );

		$this->update_count_cookie();
	}
}
