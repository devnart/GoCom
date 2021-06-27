<?php
/**
 * Enqueue functions.
 *
 * @package woodmart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

// JS.
if ( ! function_exists( 'woodmart_is_combined_needed' ) ) {
	/**
	 * Is combined needed.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Combined key.
	 * @param mixed  $default Default value.
	 *
	 * @return bool
	 */
	function woodmart_is_combined_needed( $key, $default ) {
		return woodmart_get_opt( $key, $default ) || ( woodmart_is_elementor_installed() && ( woodmart_elementor_is_edit_mode() || woodmart_elementor_is_preview_mode() ) );
	}
}

if ( ! function_exists( 'woodmart_register_libraries_scripts' ) ) {
	/**
	 * Register libraries scripts.
	 *
	 * @since 1.0.0
	 */
	function woodmart_register_libraries_scripts() {
		$config   = woodmart_get_config( 'js-libraries' );
		$minified = woodmart_get_opt( 'minified_js' ) ? '.min' : '';
		$version  = woodmart_get_theme_info( 'Version' );

		if ( woodmart_is_combined_needed( 'combined_js_libraries', false ) ) {
			return;
		}

		foreach ( $config as $key => $libraries ) {
			foreach ( $libraries as $library ) {
				$src = WOODMART_THEME_DIR . $library['file'] . $minified . '.js';

				wp_register_script( 'wd-' . $library['name'] . '-library', $src, $library['dependency'], $version, $library['in_footer'] );
			}
		}
	}

	add_action( 'wp_enqueue_scripts', 'woodmart_register_libraries_scripts', 10 );
}

if ( ! function_exists( 'woodmart_register_scripts' ) ) {
	/**
	 * Register scripts.
	 *
	 * @since 1.0.0
	 */
	function woodmart_register_scripts() {
		$config   = woodmart_get_config( 'js-scripts' );
		$minified = woodmart_get_opt( 'minified_js' ) ? '.min' : '';
		$version  = woodmart_get_theme_info( 'Version' );

		if ( woodmart_is_combined_needed( 'combined_js', false ) ) {
			return;
		}

		foreach ( $config as $key => $scripts ) {
			foreach ( $scripts as $script ) {
				$src = WOODMART_THEME_DIR . $script['file'] . $minified . '.js';

				$name = 'woodmart-theme' !== $script['name'] ? 'wd-' . $script['name'] : $script['name'];

				wp_register_script( $name, $src, array(), $version, $script['in_footer'] );
			}
		}
	}

	add_action( 'wp_enqueue_scripts', 'woodmart_register_scripts', 20 );
}

if ( ! function_exists( 'woodmart_enqueue_base_scripts' ) ) {
	/**
	 * Enqueue base scripts.
	 *
	 * @since 1.0.0
	 */
	function woodmart_enqueue_base_scripts() {
		$minified = woodmart_get_opt( 'minified_js' ) ? '.min' : '';
		$version  = woodmart_get_theme_info( 'Version' );

		// General.
		wp_enqueue_script( 'wpb_composer_front_js', false, array(), $version ); // phpcs:ignore
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		if ( woodmart_is_elementor_installed() ) {
			Elementor\Plugin::$instance->frontend->enqueue_scripts();
		}

		// Libraries.
		if ( woodmart_is_combined_needed( 'combined_js_libraries', false ) ) {
			wp_enqueue_script( 'wd-libraries', WOODMART_THEME_DIR . '/js/libs/combine' . $minified . '.js', array( 'jquery' ), $version, true );
		} else {
			woodmart_enqueue_js_library( 'device' );

			if ( woodmart_get_opt( 'ajax_shop' ) && woodmart_is_shop_archive() && ( function_exists( 'woodmart_elementor_has_location' ) && ! woodmart_elementor_has_location( 'archive' ) || ! function_exists( 'woodmart_elementor_has_location' ) ) ) {
				woodmart_enqueue_js_library( 'pjax' );
			}

			if ( ( woodmart_get_opt( 'ajax_portfolio' ) && woodmart_is_portfolio_archive() ) ) {
				woodmart_enqueue_js_library( 'pjax' );
			}

			if ( ! woodmart_woocommerce_installed() ) {
				woodmart_enqueue_js_library( 'cookie' );
			}

			$config = woodmart_get_config( 'js-libraries' );
			foreach ( $config as $key => $libraries ) {
				foreach ( $libraries as $library ) {
					if ( 'always' === woodmart_get_opt( $library['name'] . '_library' ) ) {
						woodmart_enqueue_js_library( $library['name'] );
					}
				}
			}
		}

		if ( woodmart_is_elementor_installed() && ( woodmart_elementor_is_edit_mode() || woodmart_elementor_is_preview_mode() ) ) {
			wp_enqueue_script( 'wd-google-map-api', 'https://maps.google.com/maps/api/js?libraries=geometry&v=3.44&key=' . woodmart_get_opt( 'google_map_api_key' ), array(), $version, true );
			wp_enqueue_script( 'wd-maplace', WOODMART_THEME_DIR . '/js/libs/maplace' . $minified . '.js', array( 'wd-google-map-api' ), $version, true );
		}

		if ( 'always' === woodmart_get_opt( 'swiper_library' ) && ! woodmart_get_opt( 'elementor_frontend' ) ) {
			wp_enqueue_script( 'swiper' );
		}

		if ( 'always' === woodmart_get_opt( 'el_waypoints_library' ) && ! woodmart_get_opt( 'elementor_frontend' ) ) {
			wp_enqueue_script( 'elementor-waypoints' );
		}

		// Scripts.
		if ( woodmart_is_combined_needed( 'combined_js', false ) ) {
			wp_enqueue_script( 'imagesloaded' );
			wp_enqueue_script( 'woodmart-theme', WOODMART_THEME_DIR . '/js/scripts/combine' . $minified . '.js', array(), $version, true );
		} else {
			woodmart_enqueue_js_script( 'woodmart-theme' );

			if ( woodmart_woocommerce_installed() ) {
				woodmart_enqueue_js_script( 'woocommerce-notices' );
				woodmart_enqueue_js_script( 'woocommerce-wrapp-table' );
			}

			if ( woodmart_get_opt( 'widget_toggle' ) ) {
				woodmart_enqueue_js_script( 'widgets-hidable' );
			}

			if ( ( woodmart_get_opt( 'ajax_shop' ) && woodmart_is_shop_archive() ) ) {
				woodmart_enqueue_js_script( 'ajax-filters' );
				woodmart_enqueue_js_script( 'shop-page-init' );
				woodmart_enqueue_js_script( 'back-history' );
			}

			if ( ( woodmart_get_opt( 'ajax_portfolio' ) && woodmart_is_portfolio_archive() ) ) {
				woodmart_enqueue_js_script( 'ajax-portfolio' );
			}

			$scripts_always = woodmart_get_opt( 'scripts_always_use' );
			if ( is_array( $scripts_always ) ) {
				foreach ( $scripts_always as $script ) {
					woodmart_enqueue_js_script( $script );
				}
			}
		}

		wp_add_inline_script( 'woodmart-theme', woodmart_settings_js() );
		wp_localize_script( 'woodmart-theme', 'woodmart_settings', woodmart_get_localized_string_array() );

		wp_register_style( 'woodmart-inline-css', '' );
	}

	add_action( 'wp_enqueue_scripts', 'woodmart_enqueue_base_scripts', 30 );
}

if ( ! function_exists( 'woodmart_enqueue_js_script' ) ) {
	/**
	 * Enqueue js script.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key        Script name.
	 * @param string $responsive Responsive key.
	 */
	function woodmart_enqueue_js_script( $key, $responsive = '' ) {
		$config          = woodmart_get_config( 'js-scripts' );
		$scripts_not_use = woodmart_get_opt( 'scripts_not_use' );

		if ( ! isset( $config[ $key ] ) || woodmart_is_combined_needed( 'combined_js', false ) ) {
			return;
		}

		foreach ( $config[ $key ] as $data ) {
			if ( ( 'only_mobile' === $responsive && ! wp_is_mobile() ) || ( 'only_desktop' === $responsive && wp_is_mobile() ) || ( is_array( $scripts_not_use ) && in_array( $data['name'], $scripts_not_use ) ) ) { // phpcs:ignore
				continue;
			}

			$name = 'woodmart-theme' !== $data['name'] ? 'wd-' . $data['name'] : $data['name'];
			wp_enqueue_script( $name );
		}
	}
}

if ( ! function_exists( 'woodmart_enqueue_js_library' ) ) {
	/**
	 * Enqueue js library.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key        Script name.
	 * @param string $responsive Responsive key.
	 */
	function woodmart_enqueue_js_library( $key, $responsive = '' ) {
		$config = woodmart_get_config( 'js-libraries' );

		if ( ! isset( $config[ $key ] ) || woodmart_is_combined_needed( 'combined_js_libraries', false ) ) {
			return;
		}

		foreach ( $config[ $key ] as $data ) {
			if ( ( 'only_mobile' === $responsive && ! wp_is_mobile() ) || ( 'only_desktop' === $responsive && wp_is_mobile() ) || 'not_use' === woodmart_get_opt( $data['name'] . '_library' ) ) {
				continue;
			}

			wp_enqueue_script( 'wd-' . $data['name'] . '-library' );
		}
	}
}

if ( ! function_exists( 'woodmart_dequeue_scripts' ) ) {
	/**
	 * Dequeue scripts.
	 *
	 * @since 1.0.0
	 */
	function woodmart_dequeue_scripts() {
		$dequeue_scripts = explode( ',', woodmart_get_opt( 'dequeue_scripts' ) );

		if ( is_array( $dequeue_scripts ) ) {
			foreach ( $dequeue_scripts as $script ) {
				wp_deregister_script( trim( $script ) );
				wp_dequeue_script( trim( $script ) );
			}
		}

		wp_dequeue_script( 'flexslider' );
		wp_dequeue_script( 'photoswipe-ui-default' );
		wp_dequeue_script( 'prettyPhoto-init' );
		wp_dequeue_script( 'prettyPhoto' );
		wp_dequeue_style( 'photoswipe-default-skin' );

		// Remove CF7.
		if ( ! woodmart_get_opt( 'cf7_js', '1' ) ) {
			wp_deregister_script( 'contact-form-7' );
			wp_dequeue_script( 'contact-form-7' );
		}

		// Remove animations.
		if ( ! woodmart_get_opt( 'elementor_animations', '1' ) ) {
			wp_deregister_style( 'elementor-animations' );
			wp_dequeue_style( 'elementor-animations' );
		}

		// Remove icons.
		if ( ! woodmart_get_opt( 'elementor_icons', '1' ) && ( ! is_user_logged_in() || ( is_user_logged_in() && ! current_user_can( 'administrator' ) ) ) ) {
			wp_deregister_style( 'elementor-icons' );
			wp_dequeue_style( 'elementor-icons' );
		}

		// Remove dialog.
		if ( ! woodmart_get_opt( 'elementor_dialog_library' ) && woodmart_is_elementor_installed() ) {
			$scripts = wp_scripts();
			if ( ! ( $scripts instanceof WP_Scripts ) ) {
				return;
			}

			$handles_to_remove = [
				'elementor-dialog',
			];

			$handles_updated = false;

			foreach ( $scripts->registered as $dependency_object_id => $dependency_object ) {
				if ( 'elementor-frontend' === $dependency_object_id ) {
					if ( ! ( $dependency_object instanceof _WP_Dependency ) || empty( $dependency_object->deps ) ) {
						return;
					}

					foreach ( $dependency_object->deps as $dep_key => $handle ) {
						if ( in_array( $handle, $handles_to_remove ) ) { // phpcs:ignore
							unset( $dependency_object->deps[ $dep_key ] );
							$dependency_object->deps = array_values( $dependency_object->deps );
							$handles_updated         = true;
						}
					}
				}
			}

			if ( $handles_updated && ! woodmart_elementor_is_edit_mode() && ! woodmart_elementor_is_preview_mode() ) {
				wp_deregister_script( 'elementor-dialog' );
				wp_dequeue_script( 'elementor-dialog' );
			}
		}

		// Elementor frontend.
		if ( ! woodmart_get_opt( 'elementor_frontend', '1' ) && woodmart_is_elementor_installed() && ! woodmart_elementor_is_edit_mode() && ! woodmart_elementor_is_preview_mode() ) {
			wp_deregister_script( 'elementor-frontend' );
			wp_dequeue_script( 'elementor-frontend' );
		}

		// Zoom.
		if ( 'zoom' !== woodmart_get_opt( 'image_action' ) ) {
			wp_deregister_script( 'zoom' );
			wp_dequeue_script( 'zoom' );
		}
	}

	add_action( 'wp_enqueue_scripts', 'woodmart_dequeue_scripts', 2000 );
}

if ( ! function_exists( 'woodmart_clear_menu_transient' ) ) {
	/**
	 * Clear menu session storage key hash on save menu/html block.
	 *
	 * @since 1.0.0
	 */
	function woodmart_clear_menu_transient() {
		delete_transient( 'woodmart-menu-hash-time' );
	}

	add_action( 'wp_update_nav_menu_item', 'woodmart_clear_menu_transient', 11, 1 );
	add_action( 'save_post_cms_block', 'woodmart_clear_menu_transient', 30, 3 );
}

if ( ! function_exists( 'woodmart_get_localized_string_array' ) ) {
	/**
	 * Get localize array
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function woodmart_get_localized_string_array() {
		$menu_hash_transient = get_transient( 'woodmart-menu-hash-time' );
		if ( false === $menu_hash_transient ) {
			$menu_hash_transient = time();
			set_transient( 'woodmart-menu-hash-time', $menu_hash_transient );
		}

		$site_custom_width     = woodmart_get_opt( 'site_custom_width' );
		$predefined_site_width = woodmart_get_opt( 'site_width' );

		$site_width = '';

		if ( 'full-width' === $predefined_site_width ) {
			$site_width = 1222;
		} elseif ( 'boxed' === $predefined_site_width ) {
			$site_width = 1160;
		} elseif ( 'boxed-2' === $predefined_site_width ) {
			$site_width = 1160;
		} elseif ( 'wide' === $predefined_site_width ) {
			$site_width = 1600;
		} elseif ( 'custom' === $predefined_site_width ) {
			$site_width = $site_custom_width;
		}

		return array(
			'menu_storage_key'                       => apply_filters( 'woodmart_menu_storage_key', 'woodmart_' . md5( get_current_blog_id() . '_' . get_site_url( get_current_blog_id(), '/' ) . get_template() . $menu_hash_transient ) ),
			'ajax_dropdowns_save'                    => apply_filters( 'xts_ajax_dropdowns_save', true ),
			'photoswipe_close_on_scroll'             => apply_filters( 'woodmart_photoswipe_close_on_scroll', true ),
			'woocommerce_ajax_add_to_cart'           => get_option( 'woocommerce_enable_ajax_add_to_cart' ),
			'variation_gallery_storage_method'       => woodmart_get_opt( 'variation_gallery_storage_method', 'old' ),
			'elementor_no_gap'                       => woodmart_get_opt( 'negative_gap', 'enabled' ),
			'adding_to_cart'                         => esc_html__( 'Processing', 'woodmart' ),
			'added_to_cart'                          => esc_html__( 'Product was successfully added to your cart.', 'woodmart' ),
			'continue_shopping'                      => esc_html__( 'Continue shopping', 'woodmart' ),
			'view_cart'                              => esc_html__( 'View Cart', 'woodmart' ),
			'go_to_checkout'                         => esc_html__( 'Checkout', 'woodmart' ),
			'loading'                                => esc_html__( 'Loading...', 'woodmart' ),
			'countdown_days'                         => esc_html__( 'days', 'woodmart' ),
			'countdown_hours'                        => esc_html__( 'hr', 'woodmart' ),
			'countdown_mins'                         => esc_html__( 'min', 'woodmart' ),
			'countdown_sec'                          => esc_html__( 'sc', 'woodmart' ),
			'cart_url'                               => ( woodmart_woocommerce_installed() ) ? esc_url( wc_get_cart_url() ) : '',
			'ajaxurl'                                => admin_url( 'admin-ajax.php' ),
			'add_to_cart_action'                     => ( woodmart_get_opt( 'add_to_cart_action' ) ) ? esc_js( woodmart_get_opt( 'add_to_cart_action' ) ) : 'widget',
			'added_popup'                            => ( woodmart_get_opt( 'added_to_cart_popup' ) ) ? 'yes' : 'no',
			'categories_toggle'                      => ( woodmart_get_opt( 'categories_toggle' ) ) ? 'yes' : 'no',
			'enable_popup'                           => ( woodmart_get_opt( 'promo_popup' ) ) ? 'yes' : 'no',
			'popup_delay'                            => ( woodmart_get_opt( 'promo_timeout' ) ) ? (int) woodmart_get_opt( 'promo_timeout' ) : 1000,
			'popup_event'                            => woodmart_get_opt( 'popup_event' ),
			'popup_scroll'                           => ( woodmart_get_opt( 'popup_scroll' ) ) ? (int) woodmart_get_opt( 'popup_scroll' ) : 1000,
			'popup_pages'                            => ( woodmart_get_opt( 'popup_pages' ) ) ? (int) woodmart_get_opt( 'popup_pages' ) : 0,
			'promo_popup_hide_mobile'                => ( woodmart_get_opt( 'promo_popup_hide_mobile' ) ) ? 'yes' : 'no',
			'product_images_captions'                => ( woodmart_get_opt( 'product_images_captions' ) ) ? 'yes' : 'no',
			'ajax_add_to_cart'                       => ( apply_filters( 'woodmart_ajax_add_to_cart', true ) ) ? woodmart_get_opt( 'single_ajax_add_to_cart' ) : false,
			'all_results'                            => esc_html__( 'View all results', 'woodmart' ),
			'product_gallery'                        => woodmart_get_product_gallery_settings(),
			'zoom_enable'                            => ( woodmart_get_opt( 'image_action' ) === 'zoom' ) ? 'yes' : 'no',
			'ajax_scroll'                            => ( woodmart_get_opt( 'ajax_scroll' ) ) ? 'yes' : 'no',
			'ajax_scroll_class'                      => apply_filters( 'woodmart_ajax_scroll_class', '.main-page-wrapper' ),
			'ajax_scroll_offset'                     => apply_filters( 'woodmart_ajax_scroll_offset', 100 ),
			'infinit_scroll_offset'                  => apply_filters( 'woodmart_infinit_scroll_offset', 300 ),
			'product_slider_auto_height'             => ( woodmart_get_opt( 'product_slider_auto_height' ) ) ? 'yes' : 'no',
			'price_filter_action'                    => ( apply_filters( 'price_filter_action', 'click' ) === 'submit' ) ? 'submit' : 'click',
			'product_slider_autoplay'                => apply_filters( 'woodmart_product_slider_autoplay', false ),
			'close'                                  => esc_html__( 'Close (Esc)', 'woodmart' ),
			'share_fb'                               => esc_html__( 'Share on Facebook', 'woodmart' ),
			'pin_it'                                 => esc_html__( 'Pin it', 'woodmart' ),
			'tweet'                                  => esc_html__( 'Tweet', 'woodmart' ),
			'download_image'                         => esc_html__( 'Download image', 'woodmart' ),
			'cookies_version'                        => ( woodmart_get_opt( 'cookies_version' ) ) ? (int) woodmart_get_opt( 'cookies_version' ) : 1,
			'header_banner_version'                  => ( woodmart_get_opt( 'header_banner_version' ) ) ? (int) woodmart_get_opt( 'header_banner_version' ) : 1,
			'promo_version'                          => ( woodmart_get_opt( 'promo_version' ) ) ? (int) woodmart_get_opt( 'promo_version' ) : 1,
			'header_banner_close_btn'                => woodmart_get_opt( 'header_close_btn' ) ? 'yes' : 'no',
			'header_banner_enabled'                  => woodmart_get_opt( 'header_banner' ) ? 'yes' : 'no',
			'whb_header_clone'                       => woodmart_get_config( 'header-clone-structure' ),
			'pjax_timeout'                           => apply_filters( 'woodmart_pjax_timeout', 5000 ),
			'split_nav_fix'                          => apply_filters( 'woodmart_split_nav_fix', false ),
			'shop_filters_close'                     => woodmart_get_opt( 'shop_filters_close' ) ? 'yes' : 'no',
			'woo_installed'                          => woodmart_woocommerce_installed(),
			'base_hover_mobile_click'                => woodmart_get_opt( 'base_hover_mobile_click' ) ? 'yes' : 'no',
			'centered_gallery_start'                 => apply_filters( 'woodmart_centered_gallery_start', 1 ),
			'quickview_in_popup_fix'                 => apply_filters( 'woodmart_quickview_in_popup_fix', false ),
			'one_page_menu_offset'                   => apply_filters( 'woodmart_one_page_menu_offset', 150 ),
			'hover_width_small'                      => apply_filters( 'woodmart_hover_width_small', true ),
			'is_multisite'                           => is_multisite(),
			'current_blog_id'                        => get_current_blog_id(),
			'swatches_scroll_top_desktop'            => woodmart_get_opt( 'swatches_scroll_top_desktop' ) ? 'yes' : 'no',
			'swatches_scroll_top_mobile'             => woodmart_get_opt( 'swatches_scroll_top_mobile' ) ? 'yes' : 'no',
			'lazy_loading_offset'                    => woodmart_get_opt( 'lazy_loading_offset' ),
			'add_to_cart_action_timeout'             => woodmart_get_opt( 'add_to_cart_action_timeout' ) ? 'yes' : 'no',
			'add_to_cart_action_timeout_number'      => woodmart_get_opt( 'add_to_cart_action_timeout_number' ),
			'single_product_variations_price'        => woodmart_get_opt( 'single_product_variations_price' ) ? 'yes' : 'no',
			'google_map_style_text'                  => esc_html__( 'Custom style', 'woodmart' ),
			'quick_shop'                             => woodmart_get_opt( 'quick_shop_variable' ) ? 'yes' : 'no',
			'sticky_product_details_offset'          => apply_filters( 'woodmart_sticky_product_details_offset', 150 ),
			'preloader_delay'                        => apply_filters( 'woodmart_preloader_delay', 300 ),
			'comment_images_upload_size_text'   => sprintf( esc_html__( 'Some files are too large. Allowed file size is %s.', 'woodmart' ), size_format( (int) woodmart_get_opt( 'single_product_comment_images_upload_size' ) * MB_IN_BYTES ) ), // phpcs:ignore
			'comment_images_count_text'         => sprintf( esc_html__( 'You can upload up to %s images to your review.', 'woodmart' ), woodmart_get_opt( 'single_product_comment_images_count' ) ), // phpcs:ignore
			'comment_images_upload_mimes_text'  => sprintf( esc_html__( 'You are allowed to upload images only in %s formats.', 'woodmart' ), apply_filters( 'xts_comment_images_upload_mimes', 'png, jpeg' ) ), // phpcs:ignore
			'comment_images_added_count_text'   => esc_html__( 'Added %s image(s)', 'woodmart' ), // phpcs:ignore
			'comment_images_upload_size'             => (int) woodmart_get_opt( 'single_product_comment_images_upload_size' ) * MB_IN_BYTES,
			'comment_images_count'                   => woodmart_get_opt( 'single_product_comment_images_count' ),
			'search_input_padding'                   => apply_filters( 'wd_search_input_padding', false ) ? 'yes' : 'no',
			'comment_images_upload_mimes'            => apply_filters(
				'woodmart_comment_images_upload_mimes',
				array(
					'jpg|jpeg|jpe' => 'image/jpeg',
					'png'          => 'image/png',
				)
			),
			'home_url'                               => home_url( '/' ),
			'shop_url'                               => woodmart_woocommerce_installed() ? esc_url( wc_get_page_permalink( 'shop' ) ) : '',
			'age_verify'                             => ( woodmart_get_opt( 'age_verify' ) ) ? 'yes' : 'no',
			'age_verify_expires'                     => apply_filters( 'woodmart_age_verify_expires', 30 ),
			'cart_redirect_after_add'                => get_option( 'woocommerce_cart_redirect_after_add' ),
			'swatches_labels_name'                   => woodmart_get_opt( 'swatches_labels_name' ) ? 'yes' : 'no',
			'product_categories_placeholder'         => esc_html__( 'Select a category', 'woocommerce' ),
			'product_categories_no_results'          => esc_html__( 'No matches found', 'woocommerce' ),
			'cart_hash_key'                          => apply_filters( 'woocommerce_cart_hash_key', 'wc_cart_hash_' . md5( get_current_blog_id() . '_' . get_site_url( get_current_blog_id(), '/' ) . get_template() ) ),
			'fragment_name'                          => apply_filters( 'woocommerce_cart_fragment_name', 'wc_fragments_' . md5( get_current_blog_id() . '_' . get_site_url( get_current_blog_id(), '/' ) . get_template() ) ),
			'photoswipe_template'                    => '<div class="pswp" aria-hidden="true" role="dialog" tabindex="-1"><div class="pswp__bg"></div><div class="pswp__scroll-wrap"><div class="pswp__container"><div class="pswp__item"></div><div class="pswp__item"></div><div class="pswp__item"></div></div><div class="pswp__ui pswp__ui--hidden"><div class="pswp__top-bar"><div class="pswp__counter"></div><button class="pswp__button pswp__button--close" title="' . esc_html__( 'Close (Esc)', 'woocommerce' ) . '"></button> <button class="pswp__button pswp__button--share" title="' . esc_html__( 'Share', 'woocommerce' ) . '"></button> <button class="pswp__button pswp__button--fs" title="' . esc_html__( 'Toggle fullscreen', 'woocommerce' ) . '"></button> <button class="pswp__button pswp__button--zoom" title="' . esc_html__( 'Zoom in/out', 'woocommerce' ) . '"></button><div class="pswp__preloader"><div class="pswp__preloader__icn"><div class="pswp__preloader__cut"><div class="pswp__preloader__donut"></div></div></div></div></div><div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap"><div class="pswp__share-tooltip"></div></div><button class="pswp__button pswp__button--arrow--left" title="' . esc_html__( 'Previous (arrow left)', 'woocommerce' ) . '"></button> <button class="pswp__button pswp__button--arrow--right" title="' . esc_html__( 'Next (arrow right)', 'woocommerce' ) . '>"></button><div class="pswp__caption"><div class="pswp__caption__center"></div></div></div></div></div>',
			'load_more_button_page_url'              => apply_filters( 'woodmart_load_more_button_page_url', true ) ? 'yes' : 'no',
			'menu_item_hover_to_click_on_responsive' => apply_filters( 'woodmart_menu_item_hover_to_click_on_responsive', false ) ? 'yes' : 'no',
			'clear_menu_offsets_on_resize'           => apply_filters( 'woodmart_clear_menu_offsets_on_resize', true ) ? 'yes' : 'no',
			'three_sixty_framerate'                  => apply_filters( 'woodmart_three_sixty_framerate', 60 ),
			'site_width'                             => $site_width,
			'combined_css'                           => woodmart_get_opt( 'combined_css', true ) ? 'yes' : 'no',
		);
	}
}

// CSS.
if ( ! function_exists( 'woodmart_enqueue_base_styles' ) ) {
	function woodmart_enqueue_base_styles() {
		$uploads  = wp_upload_dir();
		$version  = woodmart_get_theme_info( 'Version' );
		$minified = woodmart_get_opt( 'minified_css' ) ? '.min' : '';
		$is_rtl   = is_rtl() ? '-rtl' : '';

		if ( 'elementor' === woodmart_get_opt( 'page_builder', 'wpb' ) ) {
			$style_url = WOODMART_STYLES . '/style' . $is_rtl . '-elementor' . $minified . '.css';
		} else {
			$style_url = WOODMART_THEME_DIR . '/style' . $minified . '.css';

			if ( $is_rtl ) {
				$style_url = WOODMART_STYLES . '/style' . $is_rtl . $minified . '.css';
			}
		}

		if ( woodmart_is_elementor_installed() ) {
			Elementor\Plugin::$instance->frontend->enqueue_styles();
		}

		if ( class_exists( 'WeDevs_Dokan' ) ) {
			wp_deregister_style( 'dokan-fontawesome' );
			wp_dequeue_style( 'dokan-fontawesome' );

			wp_enqueue_style( 'vc_font_awesome_5' );
			wp_enqueue_style( 'vc_font_awesome_5_shims' );

			wp_enqueue_style( 'elementor-icons-fa-solid' );
			wp_enqueue_style( 'elementor-icons-fa-brands' );
			wp_enqueue_style( 'elementor-icons-fa-regular' );
		}

		wp_deregister_style( 'font-awesome' );
		wp_dequeue_style( 'font-awesome' );

		wp_dequeue_style( 'vc_pageable_owl-carousel-css' );
		wp_dequeue_style( 'vc_pageable_owl-carousel-css-theme' );

		wp_deregister_style( 'woocommerce_prettyPhoto_css' );
		wp_dequeue_style( 'woocommerce_prettyPhoto_css' );

		wp_deregister_style( 'contact-form-7' );
		wp_dequeue_style( 'contact-form-7' );
		wp_deregister_style( 'contact-form-7-rtl' );
		wp_dequeue_style( 'contact-form-7-rtl' );

		$wpbfile = get_option( 'woodmart-generated-wpbcss-file' );
		if ( isset( $wpbfile['name'] ) && 'wpb' === woodmart_get_opt( 'builder', 'wpb' ) && defined( 'WPB_VC_VERSION' ) ) {
			$wpbfile_path = $uploads['basedir'] . $wpbfile['name'];
			$wpbfile_url  = $uploads['baseurl'] . $wpbfile['name'];

			$wpbfile_data    = file_exists( $wpbfile_path ) ? get_file_data( $wpbfile_path, array( 'Version' => 'Version' ) ) : array();
			$wpbfile_version = isset( $wpbfile_data['Version'] ) ? $wpbfile_data['Version'] : '';
			if ( $wpbfile_version && version_compare( WOODMART_WPB_CSS_VERSION, $wpbfile_version, '==' ) ) {
				$inline_styles = wp_styles()->get_data( 'js_composer_front', 'after' );

				wp_deregister_style( 'js_composer_front' );
				wp_dequeue_style( 'js_composer_front' );
				wp_register_style( 'js_composer_front', $wpbfile_url, array(), $version );
				if ( ! empty( $inline_styles ) ) {
					$inline_styles = implode( "\n", $inline_styles );
					wp_add_inline_style( 'js_composer_front', $inline_styles );
				}
			}
		}

		wp_enqueue_style( 'js_composer_front', false, array(), $version );

		if ( 'always' === woodmart_get_opt( 'font_awesome_css' ) ) {
			if ( 'wpb' === woodmart_get_opt( 'page_builder', 'wpb' ) ) {
				wp_enqueue_style( 'vc_font_awesome_5' );
				wp_enqueue_style( 'vc_font_awesome_5_shims' );
			} else {
				wp_enqueue_style( 'elementor-icons-fa-solid' );
				wp_enqueue_style( 'elementor-icons-fa-brands' );
				wp_enqueue_style( 'elementor-icons-fa-regular' );
			}
		}

		if ( woodmart_get_opt( 'light_bootstrap_version' ) ) {
			wp_enqueue_style( 'bootstrap', WOODMART_STYLES . '/bootstrap-light.min.css', array(), $version );
		} else {
			wp_enqueue_style( 'bootstrap', WOODMART_STYLES . '/bootstrap.min.css', array(), $version );
		}

		if ( woodmart_get_opt( 'disable_gutenberg_css' ) ) {
			wp_deregister_style( 'wp-block-library' );
			wp_dequeue_style( 'wp-block-library' );

			wp_deregister_style( 'wc-block-style' );
			wp_dequeue_style( 'wc-block-style' );
		}

		if ( woodmart_is_elementor_installed() && woodmart_get_opt( 'elementor_optimized_css' ) && ! woodmart_elementor_is_edit_mode() && ! woodmart_elementor_is_preview_mode() ) {
			wp_deregister_style( 'elementor-frontend' );
			wp_dequeue_style( 'elementor-frontend' );

			wp_enqueue_style( 'elementor-frontend', WOODMART_STYLES . '/elementor-optimized' . $is_rtl . '.min.css', array(), $version );
		}

		if ( ! woodmart_is_combined_needed( 'combined_css', false ) ) {
			$style_url = WOODMART_THEME_DIR . '/css/parts/base' . $is_rtl . '.min.css';
		}

		wp_enqueue_style( 'woodmart-style', $style_url, array( 'bootstrap' ), $version );

		// load typekit fonts.
		$typekit_id = woodmart_get_opt( 'typekit_id' );

		if ( $typekit_id ) {
			wp_enqueue_style( 'woodmart-typekit', 'https://use.typekit.net/' . esc_attr( $typekit_id ) . '.css', array(), $version );
		}

		if ( woodmart_is_elementor_installed() && function_exists( 'woodmart_elementor_is_edit_mode' ) && ( woodmart_elementor_is_edit_mode() || woodmart_elementor_is_preview_page() || woodmart_elementor_is_preview_mode() ) ) {
			wp_enqueue_style( 'woodmart-elementor-editor', WOODMART_THEME_DIR . '/inc/integrations/elementor/assets/css/editor.css', array(), $version );
		}

		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
	}

	add_action( 'wp_enqueue_scripts', 'woodmart_enqueue_base_styles', 10000 );
}

if ( ! function_exists( 'woodmart_force_enqueue_styles' ) ) {
	/**
	 * Force enqueue styles.
	 */
	function woodmart_force_enqueue_styles() {
		$styles_always = woodmart_get_opt( 'styles_always_use' );
		if ( is_array( $styles_always ) ) {
			foreach ( $styles_always as $style ) {
				woodmart_force_enqueue_style( $style );
			}
		}

		$predefined_site_width = woodmart_get_opt( 'site_width' );

		if ( 'boxed' === $predefined_site_width || 'boxed-2' === $predefined_site_width ) {
			woodmart_force_enqueue_style( 'layout-wrapper-boxed' );
		}

		if ( woodmart_get_opt( 'lazy_loading' ) ) {
			woodmart_force_enqueue_style( 'lazy-loading' );
		}

		if ( is_singular( 'post' ) || woodmart_is_blog_archive() ) {
			woodmart_force_enqueue_style( 'blog-base' );
		}

		if ( is_singular( 'portfolio' ) || woodmart_is_portfolio_archive() ) {
			woodmart_force_enqueue_style( 'portfolio-base' );
		}

		if ( is_404() ) {
			woodmart_force_enqueue_style( 'page-404' );
		}

		if ( is_search() ) {
			woodmart_force_enqueue_style( 'page-search-results' );
		}

		if ( ! woodmart_get_opt( 'disable_gutenberg_css' ) ) {
			woodmart_force_enqueue_style( 'wp-gutenberg' );
		}

		if ( class_exists( 'ANR' ) ) {
			woodmart_force_enqueue_style( 'advanced-nocaptcha' );
		}

		if ( defined( 'WPCF7_VERSION' ) && woodmart_get_opt( 'cf7_css_js', '1' ) ) {
			woodmart_force_enqueue_style( 'wpcf7' );
		}

		if ( class_exists( 'bbPress' ) ) {
			woodmart_force_enqueue_style( 'bbpress', true );
		}

		if ( class_exists( 'WOOCS_STARTER' ) ) {
			woodmart_force_enqueue_style( 'woo-curr-switch', true );
		}

		if ( class_exists( 'WeDevs_Dokan' ) ) {
			woodmart_force_enqueue_style( 'woo-dokan-vend', true );
		}

		if ( class_exists( 'WooCommerce_Germanized' ) ) {
			woodmart_force_enqueue_style( 'woo-germanized' );
		}

		if ( function_exists( '_mc4wp_load_plugin' ) ) {
			woodmart_force_enqueue_style( 'mc4wp' );
		}

		if ( defined( 'WC_GATEWAY_PPEC_VERSION' ) ) {
			woodmart_force_enqueue_style( 'woo-paypal-express' );
		}

		if ( defined( 'RS_REVISION' ) ) {
			woodmart_force_enqueue_style( 'revolution-slider' );
		}

		if ( defined( 'WC_STRIPE_VERSION' ) ) {
			woodmart_force_enqueue_style( 'woo-stripe', true );
		}

		if ( class_exists( 'WCFM_Dependencies' ) ) {
			woodmart_force_enqueue_style( 'woo-wcfm-fm' );
			woodmart_force_enqueue_style( 'colorbox-popup' );
		}

		if ( class_exists( 'WC_Dependencies_Product_Vendor' ) ) {
			woodmart_force_enqueue_style( 'woo-wc-marketplace', true );
		}

		if ( class_exists( 'WC_Vendors' ) ) {
			woodmart_force_enqueue_style( 'woo-wc-vendors', true );
		}

		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			woodmart_force_enqueue_style( 'wpml', true );
		}

		if ( defined( 'YITH_WOOCOMPARE_VERSION' ) ) {
			woodmart_force_enqueue_style( 'woo-yith-compare', true );
			woodmart_force_enqueue_style( 'colorbox-popup' );
		}

		if ( defined( 'YITH_WPV_VERSION' ) ) {
			woodmart_force_enqueue_style( 'woo-yith-vendor', true );
		}

		if ( defined( 'YITH_YWRAQ_VERSION' ) ) {
			woodmart_force_enqueue_style( 'woo-yith-req-quote', true );
		}

		if ( defined( 'YITH_WCWL' ) ) {
			woodmart_force_enqueue_style( 'woo-yith-wishlist', true );
		}

		if ( woodmart_is_elementor_installed() ) {
			woodmart_force_enqueue_style( 'elementor-base' );
		}

		if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			woodmart_force_enqueue_style( 'elementor-pro-base', true );
		}

		if ( defined( 'WPB_VC_VERSION' ) ) {
			woodmart_force_enqueue_style( 'wpbakery-base' );
		}

		if ( woodmart_get_opt( 'sticky_notifications' ) ) {
			woodmart_force_enqueue_style( 'notices-fixed' );
		}

		if ( woodmart_woocommerce_installed() ) {
			woodmart_force_enqueue_style( 'woocommerce-base' );

			if ( is_cart() || is_checkout() || is_account_page() ) {
				woodmart_force_enqueue_style( 'select2' );
			}

			if ( is_cart() ) {
				woodmart_force_enqueue_style( 'page-cart' );
				woodmart_force_enqueue_style( 'page-checkout' );
			}

			if ( is_checkout() ) {
				woodmart_force_enqueue_style( 'page-checkout' );
			}

			if ( is_account_page() ) {
				woodmart_force_enqueue_style( 'page-my-account' );
			}

			if ( is_product() ) {
				woodmart_force_enqueue_style( 'page-single-product' );
			}

			if ( is_product_taxonomy() || is_shop() || is_product_category() || is_product_tag() || woodmart_is_product_attribute_archive() ) {
				woodmart_force_enqueue_style( 'page-shop' );
			}

			if ( (int) woodmart_get_opt( 'compare_page' ) === (int) woodmart_get_the_ID() ) {
				woodmart_force_enqueue_style( 'page-compare' );
			}

			$compare_page  = function_exists( 'wpml_object_id_filter' ) ? wpml_object_id_filter( woodmart_get_opt( 'compare_page' ), 'page', true ) : woodmart_get_opt( 'compare_page' );
			$wishlist_page = function_exists( 'wpml_object_id_filter' ) ? wpml_object_id_filter( woodmart_get_opt( 'wishlist_page' ), 'page', true ) : woodmart_get_opt( 'wishlist_page' );

			if ( (int) woodmart_get_the_ID() === (int) $compare_page ) {
				woodmart_force_enqueue_style( 'page-compare' );
			}

			if ( (int) woodmart_get_the_ID() === (int) $wishlist_page ) {
				woodmart_force_enqueue_style( 'page-wishlist' );
				woodmart_force_enqueue_style( 'page-my-account' );
			}
		}
	}

	add_action( 'wp_enqueue_scripts', 'woodmart_force_enqueue_styles', 10001 );
}

if ( ! function_exists( 'woodmart_enqueue_inline_style' ) ) {
	/**
	 * Enqueue inline style by key.
	 *
	 * @param string $key File slug.
	 */
	function woodmart_enqueue_inline_style( $key ) {
		if ( function_exists( 'wc' ) && wc()->is_rest_api_request() ) {
			return;
		}

		WOODMART_Registry()->pagecssfiles->enqueue_inline_style( $key );
	}
}

if ( ! function_exists( 'woodmart_force_enqueue_style' ) ) {
	/**
	 * Enqueue style by key.
	 *
	 * @param string $key File slug.
	 * @param bool   $ignore_combined Ignore combined.
	 */
	function woodmart_force_enqueue_style( $key, $ignore_combined = false ) {
		WOODMART_Registry()->pagecssfiles->enqueue_style( $key, $ignore_combined );
	}
}

if ( ! function_exists( 'woodmart_enqueue_inline_style_anchor' ) ) {
	function woodmart_enqueue_inline_style_anchor() {
		wp_enqueue_style( 'woodmart-inline-css' );
	}

	add_action( 'wp_footer', 'woodmart_enqueue_inline_style_anchor', 10 );
}

if ( ! function_exists( 'woodmart_enqueue_ie_polyfill' ) ) {
	function woodmart_enqueue_ie_polyfill() {
		?>
		<script>window.MSInputMethodContext && document.documentMode && document.write('<script src="<?php echo esc_url( WOODMART_THEME_DIR . '/js/libs/ie11CustomProperties.min.js' ); ?>"><\/script>');</script>
		<?php
	}

	add_action( 'wp_head', 'woodmart_enqueue_ie_polyfill', -100 );
}
