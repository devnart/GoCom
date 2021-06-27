<?php
/**
 * Wishlist UI.
 */

namespace XTS\WC_Wishlist;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}

use XTS\Singleton;
use XTS\WC_Wishlist\Wishlist;

/**
 * Wishlist UI.
 *
 * @since 1.0.0
 */
class Ui extends Singleton {

	/**
	 * Wishlist object.
	 *
	 * @var null
	 */
	private $wishlist = null;

	/**
	 * Can user edit this wishlist or just view it.
	 *
	 * @var boolean
	 */
	private $editable = true;

	/**
	 * Initialize action.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public function init() {
		if ( ! woodmart_woocommerce_installed() ) {
			return false;
		}

		add_action( 'init', array( $this, 'hooks' ), 100 );
		add_action( 'init', array( $this, 'button_hooks' ), 200 );

		add_action( 'wp', array( $this, 'hooks' ), 100 );
	}

	/**
	 * Register hooks and actions.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public function hooks() {
		if ( ! woodmart_get_opt( 'wishlist' ) ) {
			return false;
		}

		add_shortcode( 'woodmart_wishlist', array( $this, 'wishlist_page' ) );

		$wishlist_id = get_query_var( 'wishlist_id' );

		// Display public wishlist or personal.
		if ( $wishlist_id && (int) $wishlist_id > 0 ) {
			$this->editable = false;
			$this->wishlist = new Wishlist( $wishlist_id, false, true );
		} else {
			$this->wishlist = new Wishlist();
		}
	}

	/**
	 * Wishlist page shortcode output.
	 *
	 * @since 1.0.0
	 */
	public function get_wishlist() {
		return $this->wishlist;
	}

	/**
	 * Add buttons.
	 *
	 * @since 1.0.0
	 */
	public function button_hooks() {
		if ( ! woodmart_get_opt( 'wishlist' ) ) {
			return false;
		}

		add_filter( 'woocommerce_account_menu_items', array( $this, 'account_navigation' ), 15 );
		add_filter( 'woocommerce_get_endpoint_url', array( $this, 'account_navigation_url' ), 15, 4 );
		add_filter( 'woocommerce_account_menu_item_classes', array( $this, 'account_navigation_classes' ), 15, 2 );

		if ( ( woodmart_get_opt( 'wishlist_logged' ) && is_user_logged_in() ) || ! woodmart_get_opt( 'wishlist_logged' ) ) {
			add_action( 'woocommerce_single_product_summary', array( $this, 'add_to_wishlist_single_btn' ), 33 );
			add_action( 'woodmart_sticky_atc_actions', array( $this, 'add_to_wishlist_sticky_atc_btn' ), 10 );
		}

		if ( woodmart_get_opt( 'product_loop_wishlist' ) && ( ( woodmart_get_opt( 'wishlist_logged' ) && is_user_logged_in() ) || ! woodmart_get_opt( 'wishlist_logged' ) ) ) {
			add_action( 'woodmart_product_action_buttons', array( $this, 'add_to_wishlist_loop_btn' ), 30 );
		}
	}

	/**
	 * Wishlist page shortcode output.
	 *
	 * @since 1.0.0
	 */
	public function wishlist_page() {
		ob_start();
		?>
		<?php if ( woodmart_get_opt( 'wishlist_logged' ) && ! is_user_logged_in() ) : ?>
			<div class="woocommerce-notices-wrapper">
				<div class="woocommerce-info" role="alert">
					<?php esc_html_e( 'Wishlist is available only for logged in visitors.', 'woodmart' ); ?> 
					<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>">
						<?php esc_html_e( 'Sign in', 'woodmart' ); ?>
					</a>
				</div>
			</div>
			<?php return; ?>
		<?php endif; ?>

		<?php if ( is_user_logged_in() && $this->is_editable() && woodmart_get_opt( 'my_account_wishlist' ) ) : ?>
			<?php do_action( 'woocommerce_account_navigation' ); ?>
		<?php endif; ?>

		<div class="<?php echo ( is_user_logged_in() && $this->is_editable() && woodmart_get_opt( 'my_account_wishlist' ) ) ? 'woocommerce-MyAccount-content' : ''; ?>">
			<?php echo $this->wishlist_page_content(); //phpcs:ignore ?>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Content of the wishlist page with products.
	 *
	 * @since 1.0.0
	 *
	 * @param string $wishlist Wishlist object.
	 */
	public function wishlist_page_content( $wishlist = false ) {
		if ( ! $wishlist ) {
			$wishlist = $this->wishlist;
		}

		$wishlist_empty_text = woodmart_get_opt( 'wishlist_empty_text' );
		$products            = $wishlist->get_all();
		$wrapper_classes     = '';
		$url                 = woodmart_get_whishlist_page_url();
		$id                  = get_query_var( 'wishlist_id' );

		$ids = array();

		$ids = array_map(
			function( $item ) {
				return $item['product_id'];
			},
			$products
		);

		if ( $id && $id > 0 ) {
			$url .= $id . '/';
		}

		$columns = woodmart_get_opt( 'products_columns' );

		if ( woodmart_get_opt( 'products_columns' ) > 3 && is_user_logged_in() ) {
			$columns = woodmart_get_opt( 'products_columns' ) - 1;
		}

		$args = array(
			'include'        => implode( ',', $ids ),
			'post_type'      => 'ids',
			'items_per_page' => woodmart_get_opt( 'shop_per_page' ),
			'columns'        => $columns,
			'pagination'     => 'links',
			'force_not_ajax' => 'yes',
		);

		if ( ! $this->is_editable() ) {
			$wrapper_classes .= ' wd-wishlist-preview';
		}

		ob_start();

		?>
			<div class="wd-wishlist-content<?php echo esc_attr( $wrapper_classes ); ?>">
				<?php if ( count( $products ) > 0 ) : ?>
					<div class="wd-wishlist-heading-wrapper">
						<h4 class="wd-wishlist-title title">
							<?php esc_html_e( 'Your products wishlist', 'woodmart' ); ?>
						</h4>

						<?php if ( is_user_logged_in() && $this->is_editable() && woodmart_is_social_link_enable( 'share' ) ) : ?>
							<div class="wd-wishlist-share">
								<span>
									<?php esc_attr_e( 'Share', 'woodmart' ); ?>
								</span>
								<?php echo do_shortcode( '[social_buttons size="small" page_link="' . $url . $wishlist->get_id() . '/' . '"]' ); ?>
							</div>
						<?php endif; ?>
					</div>

					<?php if ( $this->is_editable() ) : ?>
						<?php add_action( 'woocommerce_before_shop_loop_item', array( $this, 'remove_btn' ), 10 ); ?>
					<?php endif; ?>

					<?php echo woodmart_shortcode_products( $args ); ?>

					<?php remove_action( 'woocommerce_before_shop_loop_item', array( $this, 'remove_btn' ), 10 ); ?>

				<?php else : ?>
					<p class="wd-empty-wishlist wd-empty-page">
						<?php esc_html_e( 'Wishlist is empty.', 'woodmart' ); ?>
					</p>

					<?php if ( $wishlist_empty_text ) : ?>
						<div class="wd-empty-page-text">
							<?php echo wp_kses( $wishlist_empty_text, woodmart_get_allowed_html() ); ?>
						</div>
					<?php endif; ?>

					<p class="return-to-shop">
						<a class="button" href="<?php echo esc_url( apply_filters( 'woodmart_wishlist_return_to_shop_url', wc_get_page_permalink( 'shop' ) ) ); ?>">
							<?php esc_html_e( 'Return to shop', 'woodmart' ); ?>
						</a>
					</p>
				<?php endif; ?>
			</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Remove button HTML.
	 *
	 * @since 1.0.0
	 */
	public function remove_btn() {
		woodmart_enqueue_js_script( 'wishlist' );
		?>
			<div class="wd-button-remove-wrap wd-action-btn wd-style-text wd-cross-icon">
				<a href="#" class="wd-wishlist-remove" data-key="<?php echo esc_attr( wp_create_nonce( 'wd-wishlist-remove' ) ); ?>" data-product-id="<?php echo esc_attr( get_the_ID() ); ?>">
					<?php esc_html_e( 'Remove', 'woodmart' ); ?>
				</a>
			</div>
		<?php
	}

	/**
	 * Add to wishlist button on loop product.
	 *
	 * @since 1.0.0
	 */
	public function add_to_wishlist_loop_btn() {
		$this->add_to_wishlist_btn( 'wd-action-btn wd-style-icon wd-wishlist-icon' );
	}

	/**
	 * Add to wishlist button on single product.
	 *
	 * @since 1.0.0
	 */
	public function add_to_wishlist_single_btn() {
		$this->add_to_wishlist_btn( 'wd-action-btn wd-style-text wd-wishlist-icon' );
	}

	/**
	 * Add to wishlist button on sticky add to cart.
	 *
	 * @since 1.0.0
	 */
	public function add_to_wishlist_sticky_atc_btn() {
		$this->add_to_wishlist_btn( 'wd-action-btn wd-style-icon wd-wishlist-icon' );
	}

	/**
	 * Add to wishlist button.
	 *
	 * @since 1.0.0
	 *
	 * @param string $classes Extra classes.
	 */
	public function add_to_wishlist_btn( $classes = '' ) {
		woodmart_enqueue_js_script( 'wishlist' );
		$added        = false;
		$link_classes = '';
		$text         = esc_html__( 'Add to wishlist', 'woodmart' );

		if ( $this->wishlist && $this->wishlist->get_all() && woodmart_get_opt( 'wishlist_save_button_state', '0' ) ) {
			$products = $this->wishlist->get_all();
			foreach ( $products as $product ) {
				if ( (int) get_the_ID() === (int) $product['product_id'] ) {
					$added = true;
				}
			}
		}

		if ( $added ) {
			$link_classes .= ' added';
			$text          = esc_html__( 'Browse Wishlist', 'woodmart' );
		}

		$classes .= woodmart_get_old_classes( ' woodmart-wishlist-btn' );

		?>
			<div class="wd-wishlist-btn <?php echo esc_attr( $classes ); ?>">
				<a class="<?php echo esc_attr( $link_classes ); ?>" href="<?php echo esc_url( woodmart_get_whishlist_page_url() ); ?>" data-key="<?php echo esc_attr( wp_create_nonce( 'woodmart-wishlist-add' ) ); ?>" data-product-id="<?php echo esc_attr( get_the_ID() ); ?>" data-added-text="<?php esc_html_e( 'Browse Wishlist', 'woodmart' ); ?>"><?php echo esc_html( $text ); ?></a>
			</div>
		<?php
	}

	/**
	 * Add wishlist title to account menu.
	 *
	 * @since 1.0.0
	 *
	 * @param array $items Menu items.
	 *
	 * @return array
	 */
	public function account_navigation( $items ) {
		unset( $items['customer-logout'] );

		if ( woodmart_get_opt( 'wishlist' ) && woodmart_get_opt( 'wishlist_page' ) && woodmart_get_opt( 'my_account_wishlist' ) ) {
			$items['wishlist'] = esc_html__( 'Wishlist', 'woodmart' );
		}

		$items['customer-logout'] = esc_html__( 'Logout', 'woodmart' );

		return $items;
	}

	/**
	 * Add URL to wishlist item in the menu.
	 *
	 * @since 1.0.0
	 *
	 * @param string $url Item url.
	 * @param string $endpoint Endpoint name.
	 * @param string $value Value.
	 * @param string $permalink Item permalink.
	 *
	 * @return string
	 */
	public function account_navigation_url( $url, $endpoint, $value, $permalink ) {
		if ( 'wishlist' === $endpoint ) {
			$url = woodmart_get_whishlist_page_url();
		}

		return $url;
	}

	/**
	 * Add active class to wishlist item in the menu.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $classes Item classes.
	 * @param string $endpoint Endpoint name.
	 *
	 * @return array
	 */
	public function account_navigation_classes( $classes, $endpoint ) {
		global $wp;

		$wishlist_page = function_exists( 'wpml_object_id_filter' ) ? wpml_object_id_filter( woodmart_get_opt( 'wishlist_page' ), 'page', true ) : woodmart_get_opt( 'wishlist_page' );

		if ( 'wishlist' === $endpoint && get_the_ID() == $wishlist_page ) {
			$classes[] = 'is-active';
		} elseif ( get_the_ID() == $wishlist_page ) {
			$key = array_search( 'is-active', $classes );
			if ( false !== $key ) {
				unset( $classes[ $key ] );
			}
		}

		return $classes;
	}

	/**
	 * Can user edit this wishlist or just view it.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public function is_editable() {
		return $this->editable;
	}
}
