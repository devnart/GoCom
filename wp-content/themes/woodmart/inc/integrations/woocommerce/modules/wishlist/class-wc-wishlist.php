<?php
/**
 * Wishlist.
 */

namespace XTS\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}

use XTS\Options;
use XTS\WC_Wishlist\Wishlist;
use XTS\WC_Wishlist\Ui;

/**
 * Wishlist.
 *
 * @since 1.0.0
 */
class WC_Wishlist {

	/**
	 * Name of the products in wishlist table.
	 *
	 * @var string
	 */
	private $products_table = '';

	/**
	 * Name of the wishlists table.
	 *
	 * @var string
	 */
	private $wishlists_table = '';

	/**
	 * Is table installed.
	 *
	 * @var string
	 */
	private $is_installed;

	/**
	 * Class basic constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Base initialization class required for Module class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		global $wpdb;

		$this->define_constants();
		$this->include_files();

		$this->products_table  = $wpdb->prefix . 'woodmart_wishlist_products';
		$this->wishlists_table = $wpdb->prefix . 'woodmart_wishlists';
		$this->is_installed    = $this->is_installed();

		$wpdb->woodmart_products_table  = $this->products_table;
		$wpdb->woodmart_wishlists_table = $this->wishlists_table;

		add_action( 'init', array( $this, 'add_options' ), 120 );
		add_action( 'after_switch_theme', array( $this, 'install' ) );
		add_action( 'admin_init', array( $this, 'theme_settigns_install' ), 100 );

		add_action( 'wp_ajax_woodmart_add_to_wishlist', array( $this, 'add_to_wishlist_action' ) );
		add_action( 'wp_ajax_nopriv_woodmart_add_to_wishlist', array( $this, 'add_to_wishlist_action' ) );

		add_action( 'wp_ajax_woodmart_remove_from_wishlist', array( $this, 'remove_from_wishlist_action' ) );
		add_action( 'wp_ajax_nopriv_woodmart_remove_from_wishlist', array( $this, 'remove_from_wishlist_action' ) );

		if ( ! $this->is_installed ) {
			return;
		}

		Ui::get_instance();

		add_action( 'init', array( $this, 'custom_rewrite_rule' ), 10, 0 );

		add_filter( 'query_vars', array( $this, 'add_query_vars' ) );
	}

	/**
	 * Add rewrite rules for wishlist.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function custom_rewrite_rule() {
		$id = woodmart_get_opt( 'wishlist_page' );
		add_rewrite_rule( '^wishlist/([^/]*)/page/([^/]*)?', 'index.php?page_id=' . ( (int) $id ) . '&wishlist_id=$matches[1]&paged=$matches[2]', 'top' );
		add_rewrite_rule( '^wishlist/page/([^/]*)?', 'index.php?page_id=' . ( (int) $id ) . '&paged=$matches[1]', 'top' );
		add_rewrite_rule( '^wishlist/([^/]*)/?', 'index.php?page_id=' . ( (int) $id ) . '&wishlist_id=$matches[1]', 'top' );
	}

	/**
	 * Add query vars for wishlist rewrite rules.
	 *
	 * @since 1.0
	 *
	 * @param array $vars Vars.
	 *
	 * @return boolean
	 */
	public function add_query_vars( $vars ) {
		$vars[] = 'wishlist_id';
		return $vars;
	}

	/**
	 * Add product to the wishlist AJAX action.
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	public function add_to_wishlist_action() {
		if ( ! is_user_logged_in() && woodmart_get_opt( 'wishlist_logged' ) ) {
			return false;
		}

		$product_id = (int) trim( $_GET['product_id'] ); //phpcs:ignore

		if ( defined( 'ICL_SITEPRESS_VERSION' ) && function_exists( 'wpml_object_id_filter' ) ) {
			global $sitepress;
			$product_id = wpml_object_id_filter( $product_id, 'product', true, $sitepress->get_default_language() );
		}

		$wishlist = $this->get_wishlist();

		$result = $wishlist->add( $product_id );
		$wishlist->update_count_cookie();

		$response = array(
			'status' => 'success',
			'count'  => $wishlist->get_count(),
		);

		add_filter( 'woodmart_is_ajax', '__return_false' );

		$response['wishlist_content'] = Ui::get_instance()->wishlist_page_content( $wishlist );

		echo wp_json_encode( $response );

		exit;
	}

	/**
	 * Remove product from the wishlist AJAX action.
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	public function remove_from_wishlist_action() {
		check_ajax_referer( 'wd-wishlist-remove', 'key' );

		if ( ! is_user_logged_in() && woodmart_get_opt( 'wishlist_logged' ) ) {
			return false;
		}

		$product_id = (int) trim( $_GET['product_id'] ); //phpcs:ignore

		if ( defined( 'ICL_SITEPRESS_VERSION' ) && function_exists( 'wpml_object_id_filter' ) ) {
			global $sitepress;
			$product_id = wpml_object_id_filter( $product_id, 'product', true, $sitepress->get_default_language() );
		}

		$wishlist = $this->get_wishlist();

		$result = $wishlist->remove( $product_id );
		$wishlist->update_count_cookie();

		$response = array(
			'status' => 'success',
			'count'  => $wishlist->get_count(),
		);

		add_filter( 'woodmart_is_ajax', '__return_false' );

		$response['wishlist_content'] = Ui::get_instance()->wishlist_page_content( $wishlist );

		echo wp_json_encode( $response );

		exit;
	}

	/**
	 * Get wishlist object.
	 *
	 * @since 1.0
	 *
	 * @return object
	 */
	public function get_wishlist() {
		$wishlist = new Wishlist();

		return $wishlist;
	}

	/**
	 * Define constants.
	 *
	 * @since 1.0.0
	 */
	private function define_constants() {
		define( 'XTS_WISHLIST_DIR', WOODMART_THEMEROOT . '/inc/integrations/woocommerce/modules/wishlist/' );
	}

	/**
	 * Include main files.
	 *
	 * @since 1.0.0
	 */
	private function include_files() {
		$files = array(
			'class-storage-interface',
			'class-db-storage',
			'class-cookies-storage',
			'class-ui',
			'class-wishlist',
			'functions',
		);

		foreach ( $files as $file ) {
			$path = XTS_WISHLIST_DIR . $file . '.php';
			if ( file_exists( $path ) ) {
				require_once $path;
			}
		}
	}

	/**
	 * Install module and create tables on theme settings save.
	 *
	 * @since 1.0
	 */
	public function theme_settigns_install() {
		if ( ! isset( $_GET['settings-updated'] ) ) {
			return;
		}

		$this->install();
	}

	/**
	 * Install module and create tables.
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	public function install() {
		global $wpdb;

		if ( $this->is_installed ) {
			return;
		}

		$sql = "CREATE TABLE {$this->wishlists_table} (
					ID INT( 11 ) NOT NULL AUTO_INCREMENT,
					user_id INT( 11 ) NOT NULL,
					date_created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY  ( ID )
				) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

		$sql .= "CREATE TABLE {$this->products_table} (
					ID int( 11 ) NOT NULL AUTO_INCREMENT,
					product_id varchar( 255 ) NOT NULL,
					wishlist_id int( 11 ) NULL,
					date_added timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY  ( ID ),
					KEY ( product_id )
				) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		return true;
	}

	/**
	 * Uninstall tables.
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	public function uninstall() {
		global $wpdb;

		if ( ! $this->is_installed ) {
			return;
		}

		$sql = "DROP TABLE IF EXISTS {$this->wishlists_table};";//phpcs:ignore
		$wpdb->query( $sql );//phpcs:ignore

		$sql = "DROP TABLE IF EXISTS {$this->products_table};";//phpcs:ignore
		$wpdb->query( $sql );//phpcs:ignore

		return true;
	}

	/**
	 * Is tables installed..
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	public function is_installed() {
		global $wpdb;
		$products_table_count  = $wpdb->query( "SHOW TABLES LIKE '{$this->products_table}%'" );//phpcs:ignore
		$wishlists_table_count = $wpdb->query( "SHOW TABLES LIKE '{$this->wishlists_table}%'" );//phpcs:ignore

		return (bool) ( 1 === $products_table_count && 1 === $wishlists_table_count );
	}

	/**
	 * Add options.
	 *
	 * @since 1.0.0
	 */
	public function add_options() {
		Options::add_section(
			array(
				'id'       => 'wishlist_section',
				'name'     => esc_html__( 'Wishlist', 'woodmart' ),
				'parent'   => 'shop_section',
				'priority' => 81,
			)
		);

		Options::add_field(
			array(
				'id'          => 'wishlist',
				'type'        => 'switcher',
				'name'        => esc_html__( 'Wishlist', 'woodmart' ),
				'description' => esc_html__( 'Enable wishlist functionality built in with the theme. Read more information in our documentation.', 'woodmart' ),
				'section'     => 'wishlist_section',
				'default'     => '1',
				'priority'    => 10,
			)
		);

		Options::add_field(
			array(
				'id'          => 'wishlist_logged',
				'type'        => 'switcher',
				'name'        => esc_html__( 'Only for logged in', 'woodmart' ),
				'description' => esc_html__( 'Disable wishlist for guests customers.', 'woodmart' ),
				'section'     => 'wishlist_section',
				'default'     => '0',
				'priority'    => 20,
			)
		);

		Options::add_field(
			array(
				'id'           => 'wishlist_page',
				'type'         => 'select',
				'name'         => esc_html__( 'Wishlist page', 'woodmart' ),
				'description'  => esc_html__( 'Select a page for wishlist table. It should contain the shortcode shortcode: [woodmart_wishlist]', 'woodmart' ),
				'section'      => 'wishlist_section',
				'empty_option' => true,
				'options'      => woodmart_get_pages_array(),
				'default'      => 267,
				'priority'     => 30,
			)
		);

		Options::add_field(
			array(
				'id'          => 'product_loop_wishlist',
				'type'        => 'switcher',
				'name'        => esc_html__( 'Show button on products in loop', 'woodmart' ),
				'description' => esc_html__( 'Display wishlist product button on all products grids and lists.', 'woodmart' ),
				'section'     => 'wishlist_section',
				'default'     => '1',
				'priority'    => 40,
			)
		);

		Options::add_field(
			array(
				'id'          => 'wishlist_save_button_state',
				'type'        => 'switcher',
				'name'        => esc_html__( 'Save button state after adding to the list', 'woodmart' ),
				'description' => esc_html__( 'You can enable this option to show the "Browse wishlist" button when you visit the product that has been already added to the wishlist.  IMPORTANT: It will not work if you use some full-page cache like WP Rocket or WP Total Cache.', 'woodmart' ),
				'section'     => 'wishlist_section',
				'default'     => '0',
				'priority'    => 50,
			)
		);

		Options::add_field(
			array(
				'id'          => 'wishlist_empty_text',
				'type'        => 'textarea',
				'name'        => esc_html__( 'Empty wishlist text', 'woodmart' ),
				'description' => esc_html__( 'Text will be displayed if user don\'t add any products to wishlist.', 'woodmart' ),
				'section'     => 'wishlist_section',
				'wysiwyg'     => false,
				'default'     => 'You don\'t have any products in the wishlist yet. <br> You will find a lot of interesting products on our "Shop" page.',
				'priority'    => 60,
			)
		);
	}
}

new WC_Wishlist();
