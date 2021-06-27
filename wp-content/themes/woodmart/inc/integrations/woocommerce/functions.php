<?php

if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

if ( ! function_exists( 'woodmart_wc_ajax_variation_threshold' ) ) {
	/**
	 * AJAX variation threshold.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $limit Limit.
	 *
	 * @return mixed
	 */
	function woodmart_wc_ajax_variation_threshold( $limit ) {
		if ( woodmart_get_opt( 'ajax_variation_threshold' ) && 30 !== (int) woodmart_get_opt( 'ajax_variation_threshold' ) ) {
			$limit = woodmart_get_opt( 'ajax_variation_threshold' );
		}

		return $limit;
	}

	add_filter( 'woocommerce_ajax_variation_threshold', 'woodmart_wc_ajax_variation_threshold' );
}

if ( ! function_exists( 'woodmart_wc_products_shortcode_compatibility' ) ) {
	/**
	 * Woocommerce products shortcode compatibility.
	 *
	 * @since 1.0.0
	 */
	function woodmart_wc_products_shortcode_compatibility() {
		add_action(
			'woocommerce_shortcode_products_query',
			function( $query ) {
				if ( isset( $_GET['per_page'] ) ) { // phpcs:ignore
					$query['posts_per_page'] =  wc_clean( wp_unslash( $_GET['per_page'] ) ); // phpcs:ignore
				}

				return $query;
			}
		);

		add_action(
			'woocommerce_shortcode_before_products_loop',
			function( $attributes ) {
				woodmart_products_per_page_select( true );
				woodmart_products_view_select( true );
				woocommerce_catalog_ordering();

				woodmart_set_loop_prop( 'products_columns', $attributes['columns'] ); // phpcs:ignore

				if ( isset( $_GET['shop_view'] ) ) { // phpcs:ignore
					woodmart_set_loop_prop( 'products_view', wc_clean( wp_unslash( $_GET['shop_view'] ) ) ); // phpcs:ignore
				}

				if ( isset( $_GET['per_row'] ) ) { // phpcs:ignore
					woodmart_set_loop_prop( 'products_columns', wc_clean( wp_unslash( $_GET['per_row'] ) ) ); // phpcs:ignore
				}

				remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
				remove_action( 'woocommerce_before_shop_loop', 'woodmart_products_per_page_select', 25 );
				remove_action( 'woocommerce_before_shop_loop', 'woodmart_products_view_select', 27 );
			}
		);
	}

	add_action( 'wp', 'woodmart_wc_products_shortcode_compatibility', 10 );
}


if ( ! function_exists( 'woodmart_product_price_slider_script' ) ) {
	/**
	 * Enqueue script.
	 *
	 * @since 1.0.0
	 *
	 * @param string $template_name Template_name.
	 */
	function woodmart_product_price_slider_script( $template_name ) {
		if ( 'content-widget-price-filter.php' === $template_name ) {
			woodmart_enqueue_js_script( 'woocommerce-price-slider' );
		}
	}

	add_action( 'woocommerce_before_template_part', 'woodmart_product_price_slider_script', 10 );
}

if ( ! function_exists( 'woodmart_product_categories_widget_script' ) ) {
	/**
	 * Enqueue script.
	 *
	 * @since 1.0.0
	 *
	 * @param string $data Data.
	 *
	 * @return string
	 */
	function woodmart_product_categories_widget_script( $data ) {
		if ( woodmart_get_opt( 'categories_toggle' ) ) {
			woodmart_enqueue_js_script( 'categories-accordion' );
		}

		woodmart_enqueue_js_script( 'categories-dropdown' );

		return $data;
	}

	add_action( 'woocommerce_product_categories_widget_args', 'woodmart_product_categories_widget_script', 10 );
	add_action( 'woocommerce_product_categories_widget_dropdown_args', 'woodmart_product_categories_widget_script', 10 );
}

if ( ! function_exists( 'woodmart_woo_widgets_select2' ) ) {
	/**
	 * Enqueue style for woo widgets.
	 *
	 * @since 1.0.0
	 *
	 * @param string $data Data.
	 *
	 * @return string
	 */
	function woodmart_woo_widgets_select2( $data ) {
		woodmart_enqueue_inline_style( 'select2' );

		return $data;
	}

	add_action( 'woocommerce_product_categories_widget_dropdown_args', 'woodmart_woo_widgets_select2', 10 );
	add_action( 'woocommerce_layered_nav_any_label', 'woodmart_woo_widgets_select2', 10 );
}

if ( ! function_exists( 'woodmart_single_product_add_to_cart_scripts' ) ) {
	/**
	 * Enqueue single product scripts.
	 *
	 * @since 1.0.0
	 */
	function woodmart_single_product_add_to_cart_scripts() {
		if ( woodmart_get_opt( 'single_ajax_add_to_cart' ) ) {
			woodmart_enqueue_js_script( 'add-to-cart-all-types' );
		}

		if ( 'nothing' !== woodmart_get_opt( 'add_to_cart_action' ) ) {
			woodmart_enqueue_js_library( 'magnific' );
			woodmart_enqueue_js_script( 'action-after-add-to-cart' );
			woodmart_enqueue_inline_style( 'add-to-cart-popup' );
			woodmart_enqueue_inline_style( 'mfp-popup' );
		}
	}

	add_action( 'woocommerce_before_add_to_cart_form', 'woodmart_single_product_add_to_cart_scripts' );
}

if ( ! function_exists( 'woodmart_product_loop_add_to_cart_scripts' ) ) {
	/**
	 * Enqueue single product scripts.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $data Data.
	 *
	 * @return mixed
	 */
	function woodmart_product_loop_add_to_cart_scripts( $data ) {
		if ( 'nothing' !== woodmart_get_opt( 'add_to_cart_action' ) ) {
			woodmart_enqueue_js_library( 'magnific' );
			woodmart_enqueue_js_script( 'action-after-add-to-cart' );
			woodmart_enqueue_inline_style( 'add-to-cart-popup' );
			woodmart_enqueue_inline_style( 'mfp-popup' );
		}

		return $data;
	}

	add_action( 'woocommerce_loop_add_to_cart_link', 'woodmart_product_loop_add_to_cart_scripts' );
}

if ( ! function_exists( 'woodmart_get_previous_product' ) ) {
	/**
	 * Retrieves the previous product.
	 *
	 * @since 2.4.3
	 *
	 * @param bool         $in_same_term Optional. Whether post should be in a same taxonomy term. Default false.
	 * @param array|string $excluded_terms Optional. Comma-separated list of excluded term IDs. Default empty.
	 * @param string       $taxonomy Optional. Taxonomy, if $in_same_term is true. Default 'product_cat'.
	 *
	 * @return WC_Product|false Product object if successful. False if no valid product is found.
	 */
	function woodmart_get_previous_product( $in_same_term = false, $excluded_terms = '', $taxonomy = 'product_cat' ) {
		$product = new XTS\Modules\WC_Adjacent_Products( $in_same_term, $excluded_terms, $taxonomy, true );

		return $product->get_product();
	}
}

if ( ! function_exists( 'woodmart_get_next_product' ) ) {
	/**
	 * Retrieves the next product.
	 *
	 * @since 2.4.3
	 *
	 * @param bool         $in_same_term Optional. Whether post should be in a same taxonomy term. Default false.
	 * @param array|string $excluded_terms Optional. Comma-separated list of excluded term IDs. Default empty.
	 * @param string       $taxonomy Optional. Taxonomy, if $in_same_term is true. Default 'product_cat'.
	 *
	 * @return WC_Product|false Product object if successful. False if no valid product is found.
	 */
	function woodmart_get_next_product( $in_same_term = false, $excluded_terms = '', $taxonomy = 'product_cat' ) {
		$product = new XTS\Modules\WC_Adjacent_Products( $in_same_term, $excluded_terms, $taxonomy );

		return $product->get_product();
	}
}

if ( ! function_exists( 'woodmart_get_products_tab_ajax' ) ) {
	function woodmart_get_products_tab_ajax() {
		if ( ! empty( $_POST['atts'] ) ) {
			$atts = woodmart_clean( $_POST['atts'] );
			if ( isset( $atts['elementor'] ) && $atts['elementor'] ) {
				$data = woodmart_elementor_products_tab_template( $atts );
			} else {
				$data = woodmart_shortcode_products_tab( $atts );
			}
			echo json_encode( $data );
			die();
		}
	}

	add_action( 'wp_ajax_woodmart_get_products_tab_shortcode', 'woodmart_get_products_tab_ajax' );
	add_action( 'wp_ajax_nopriv_woodmart_get_products_tab_shortcode', 'woodmart_get_products_tab_ajax' );
}


if ( ! function_exists( 'woodmart_get_shortcode_products_ajax' ) ) {
	function woodmart_get_shortcode_products_ajax() {
		if ( ! empty( $_POST['atts'] ) ) {
			$atts              = woodmart_clean( $_POST['atts'] );
			$paged             = ( empty( $_POST['paged'] ) ) ? 2 : sanitize_text_field( (int) $_POST['paged'] );
			$atts['ajax_page'] = $paged;

			if ( isset( $atts['elementor'] ) && $atts['elementor'] ) {
				$data = woodmart_elementor_products_template( $atts );
			} else {
				$data = woodmart_shortcode_products( $atts );
			}

			echo json_encode( $data );

			die();
		}
	}

	add_action( 'wp_ajax_woodmart_get_products_shortcode', 'woodmart_get_shortcode_products_ajax' );
	add_action( 'wp_ajax_nopriv_woodmart_get_products_shortcode', 'woodmart_get_shortcode_products_ajax' );
}

if ( ! empty( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] && is_admin() ) {
	add_action(
		'init',
		function() {
			if ( function_exists( 'wc' ) ) {
				wc()->frontend_includes();
			}
		},
		5
	);
}

if ( ! function_exists( 'woodmart_widget_get_current_page_url' ) ) {
	function woodmart_widget_get_current_page_url( $link ) {
		if ( isset( $_GET['stock_status'] ) ) {
			$link = add_query_arg( 'stock_status', wc_clean( $_GET['stock_status'] ), $link );
		}

		return $link;
	}

	add_filter( 'woocommerce_widget_get_current_page_url', 'woodmart_widget_get_current_page_url' );
}

if ( ! function_exists( 'woodmart_get_filtered_price_new' ) ) {
	function woodmart_get_filtered_price_new() {
		global $wpdb;

		if ( ! is_shop() && ! is_product_taxonomy() ) {
			$sql = "
			SELECT min( FLOOR( min_price ) ) as min_price, MAX( CEILING( max_price ) ) as max_price
			FROM {$wpdb->wc_product_meta_lookup}";

			return $wpdb->get_row( $sql );
		}

		$args       = WC()->query->get_main_query()->query_vars;
		$tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
		$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

		if ( ! is_post_type_archive( 'product' ) && ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
			$tax_query[] = array(
				'taxonomy' => $args['taxonomy'],
				'terms'    => array( $args['term'] ),
				'field'    => 'slug',
			);
		}

		foreach ( $meta_query + $tax_query as $key => $query ) {
			if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
				unset( $meta_query[ $key ] );
			}
		}

		$meta_query = new WP_Meta_Query( $meta_query );
		$tax_query  = new WP_Tax_Query( $tax_query );
		$search     = WC_Query::get_main_search_query_sql();

		$meta_query_sql   = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql    = $tax_query->get_sql( $wpdb->posts, 'ID' );
		$search_query_sql = $search ? ' AND ' . $search : '';

		$sql = "
			SELECT min( min_price ) as min_price, MAX( max_price ) as max_price
			FROM {$wpdb->wc_product_meta_lookup}
			WHERE product_id IN (
				SELECT ID FROM {$wpdb->posts}
				" . $tax_query_sql['join'] . $meta_query_sql['join'] . "
				WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product' ) ) ) ) . "')
				AND {$wpdb->posts}.post_status = 'publish'
				" . $tax_query_sql['where'] . $meta_query_sql['where'] . $search_query_sql . '
			)';

		$sql = apply_filters( 'woocommerce_price_filter_sql', $sql, $meta_query_sql, $tax_query_sql );

		return $wpdb->get_row( $sql ); // WPCS: unprepared SQL ok.
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Main loop
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_woocommerce_main_loop' ) ) {

	add_action( 'woodmart_woocommerce_main_loop', 'woodmart_woocommerce_main_loop' );

	function woodmart_woocommerce_main_loop( $fragments = false ) {
		global $paged, $wp_query;

		$max_page = $wp_query->max_num_pages;

		if ( $fragments ) {
			ob_start();
		}

		if ( $fragments && isset( $_GET['loop'] ) ) {
			woodmart_set_loop_prop( 'woocommerce_loop', (int) sanitize_text_field( $_GET['loop'] ) );
		}

		if ( woocommerce_product_loop() ) : ?>

			<?php
			if ( ! $fragments ) {
				woocommerce_product_loop_start();}
			?>

			<?php if ( wc_get_loop_prop( 'total' ) || $fragments ) : ?>
				<?php
				while ( have_posts() ) :
					the_post();
					?>

					<?php
					/**
					 * Hook: woocommerce_shop_loop.
					 *
					 * @hooked WC_Structured_Data::generate_product_data() - 10
					 */
					do_action( 'woocommerce_shop_loop' );
					?>

					<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>
			<?php endif; ?>


			<?php
			if ( ! $fragments ) {
				woocommerce_product_loop_end();}
			?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
			if ( ! $fragments ) {
				do_action( 'woocommerce_after_shop_loop' );
			}
			?>

		<?php else : ?>

			<?php
			/**
			 * Hook: woocommerce_no_products_found.
			 *
			 * @hooked wc_no_products_found - 10
			 */
			do_action( 'woocommerce_no_products_found' );
			?>

			<?php
		endif;

		if ( $fragments ) {
			$output = ob_get_clean();
		}

		if ( $fragments ) {
			$output = array(
				'items'       => $output,
				'status'      => ( $max_page > $paged ) ? 'have-posts' : 'no-more-posts',
				'nextPage'    => str_replace( '&#038;', '&', next_posts( $max_page, false ) ),
				'currentPage' => strtok( woodmart_get_current_url(), '?' ),
			);

			echo json_encode( $output );
		}
	}
}
/**
 * ------------------------------------------------------------------------------------------------
 * Change number of products displayed per page
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_shop_products_per_page' ) ) {
	function woodmart_shop_products_per_page() {
		$per_page = 12;
		$number   = apply_filters( 'woodmart_shop_per_page', woodmart_get_products_per_page() );
		if ( is_numeric( $number ) ) {
			$per_page = $number;
		}
		return $per_page;
	}

	add_filter( 'loop_shop_per_page', 'woodmart_shop_products_per_page', 20 );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Set full width layouts for woocommerce pages on set up
 * ------------------------------------------------------------------------------------------------
 */

/**
 * ------------------------------------------------------------------------------------------------
 * Get base shop page link
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_shop_page_link' ) ) {
	function woodmart_shop_page_link( $keep_query = false, $taxonomy = '' ) {
		// Base Link decided by current page
		$link = '';

		if ( class_exists( 'Automattic\Jetpack\Constants' ) && Automattic\Jetpack\Constants::is_defined( 'SHOP_IS_ON_FRONT' ) ) {
			$link = home_url();
		} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) ) || is_shop() ) {
			$link = get_permalink( wc_get_page_id( 'shop' ) );
		} elseif ( is_product_category() ) {
			$link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
		} elseif ( is_product_tag() ) {
			$link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
		} elseif ( get_queried_object() ) {
			$queried_object = get_queried_object();

			if ( property_exists( $queried_object, 'taxonomy' ) ) {
				$link = get_term_link( $queried_object->slug, $queried_object->taxonomy );
			}
		}

		if ( $keep_query ) {

			// Min/Max
			if ( isset( $_GET['min_price'] ) ) {
				$link = add_query_arg( 'min_price', wc_clean( $_GET['min_price'] ), $link );
			}

			if ( isset( $_GET['max_price'] ) ) {
				$link = add_query_arg( 'max_price', wc_clean( $_GET['max_price'] ), $link );
			}

			// Orderby
			if ( isset( $_GET['orderby'] ) ) {
				$link = add_query_arg( 'orderby', wc_clean( $_GET['orderby'] ), $link );
			}

			if ( isset( $_GET['stock_status'] ) ) {
				$link = add_query_arg( 'stock_status', wc_clean( $_GET['stock_status'] ), $link );
			}

			if ( isset( $_GET['per_row'] ) ) {
				$link = add_query_arg( 'per_row', wc_clean( $_GET['per_row'] ), $link );
			}

			if ( isset( $_GET['per_page'] ) ) {
				$link = add_query_arg( 'per_page', wc_clean( $_GET['per_page'] ), $link );
			}

			if ( isset( $_GET['shop_view'] ) ) {
				$link = add_query_arg( 'shop_view', wc_clean( $_GET['shop_view'] ), $link );
			}

			if ( isset( $_GET['shortcode'] ) ) {
				$link = add_query_arg( 'shortcode', wc_clean( $_GET['shortcode'] ), $link );
			}

			/**
			 * Search Arg.
			 * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
			 */
			if ( get_search_query() ) {
				$link = add_query_arg( 's', rawurlencode( wp_specialchars_decode( get_search_query() ) ), $link );
			}

			// Post Type Arg
			if ( isset( $_GET['post_type'] ) ) {
				$link = add_query_arg( 'post_type', wc_clean( wp_unslash( $_GET['post_type'] ) ), $link );

				// Prevent post type and page id when pretty permalinks are disabled.
				if ( is_shop() ) {
					$link = remove_query_arg( 'page_id', $link );
				}
			}

			// Min Rating Arg
			if ( isset( $_GET['min_rating'] ) ) {
				$link = add_query_arg( 'min_rating', wc_clean( $_GET['min_rating'] ), $link );
			}

			// All current filters
			if ( $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes() ) {
				foreach ( $_chosen_attributes as $name => $data ) {
					if ( $name === $taxonomy ) {
						continue;
					}
					$filter_name = sanitize_title( str_replace( 'pa_', '', $name ) );
					if ( ! empty( $data['terms'] ) ) {
						$link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
					}
					if ( 'or' == $data['query_type'] ) {
						$link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
					}
				}
			}
		}

		$link = apply_filters( 'woodmart_shop_page_link', $link, $keep_query, $taxonomy );

		if ( is_string( $link ) ) {
			return $link;
		} else {
			return '';
		}
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Get product design option
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_product_design' ) ) {
	function woodmart_product_design() {
		$design = woodmart_get_opt( 'product_design' );
		if ( is_singular( 'product' ) ) {
			$custom = get_post_meta( get_the_ID(), '_woodmart_product_design', true );
			if ( ! empty( $custom ) && $custom != 'inherit' ) {
				$design = $custom;
			}
		}

		return $design;
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Is product sticky
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_product_sticky' ) ) {
	function woodmart_product_sticky() {
		$sticky = woodmart_get_opt( 'product_sticky' ) && in_array( woodmart_get_opt( 'single_product_style' ), array( 1, 2, 3 ) ) ? true : false;
		if ( is_singular( 'product' ) ) {
			$custom = get_post_meta( get_the_ID(), '_woodmart_product_sticky', true );
			if ( ! empty( $custom ) && $custom != 'inherit' ) {
				$sticky = $custom;
			}
		}

		return $sticky;
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Register new image size two times larger than standard woocommerce one
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_add_image_size' ) ) {
	add_action( 'after_setup_theme', 'woodmart_add_image_size' );

	function woodmart_add_image_size() {

		if ( ! function_exists( 'wc_get_image_size' ) ) {
			return;
		}

		$shop_catalog = wc_get_image_size( 'woocommerce_thumbnail' );

		$width  = (int) ( $shop_catalog['width'] * 2 );
		$height = ( ! empty( $shop_catalog['height'] ) ) ? (int) ( $shop_catalog['height'] * 2 ) : '';

		add_image_size( 'woodmart_shop_catalog_x2', $width, $height, $shop_catalog['crop'] );
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Custom thumbnail function for slider
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_template_loop_product_thumbnail' ) ) {
	function woodmart_template_loop_product_thumbnail() {
		echo woodmart_get_product_thumbnail();
	}
}

if ( ! function_exists( 'woodmart_get_product_thumbnail' ) ) {
	function woodmart_get_product_thumbnail( $size = 'woocommerce_thumbnail', $attach_id = false ) {
		global $post;
		$custom_size = $size;

		$defined_sizes = array( 'woocommerce_thumbnail', 'woodmart_shop_catalog_x2' );

		if ( woodmart_loop_prop( 'double_size' ) ) {
			$size = 'woodmart_shop_catalog_x2';
		}

		if ( has_post_thumbnail() ) {

			if ( ! $attach_id ) {
				$attach_id = get_post_thumbnail_id();
			}

			$props = wc_get_product_attachment_props( $attach_id, $post );

			if ( woodmart_loop_prop( 'img_size' ) ) {
				$custom_size = woodmart_loop_prop( 'img_size' );
			}

			$custom_size = apply_filters( 'woodmart_custom_img_size', $custom_size );

			if ( woodmart_is_elementor_installed() ) {
				$img = woodmart_get_image_html( // phpcs:ignore
					array(
						'image_size'             => $custom_size,
						'image_custom_dimension' => woodmart_loop_prop( 'img_size_custom' ),
						'image'                  => array(
							'id' => $attach_id,
						),
					),
					'image'
				);
			} elseif ( ! in_array( $custom_size, $defined_sizes ) && function_exists( 'wpb_getImageBySize' ) ) {
				$img = wpb_getImageBySize(
					array(
						'attach_id'  => $attach_id,
						'thumb_size' => $custom_size,
						'class'      => 'content-product-image',
					)
				);
				$img = isset( $img['thumbnail'] ) ? $img['thumbnail'] : '';

			} else {
				$img = wp_get_attachment_image(
					$attach_id,
					$size,
					array(
						'title' => $props['title'],
						'alt'   => $props['alt'],
					)
				);
			}

			return $img;

		} elseif ( wc_placeholder_img_src() ) {
			return wc_placeholder_img( $size );
		}
	}
}

if ( ! function_exists( 'woodmart_grid_swatches_attribute' ) ) {
	function woodmart_grid_swatches_attribute() {
		$custom = get_post_meta( get_the_ID(), '_woodmart_swatches_attribute', true );
		return empty( $custom ) ? woodmart_get_opt( 'grid_swatches_attribute' ) : $custom;
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Get product page classes (columns) for product images and product information blocks
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_product_images_class' ) ) {
	function woodmart_product_images_class() {
		$size   = woodmart_product_images_size();
		$layout = woodmart_get_opt( 'single_product_style' );

		$class = 'col-lg-' . $size . ' col-12';

		$class .= ( $layout == 4 || $layout == 5 ) ? ' col-md-12' : ' col-md-6';

		return $class;
	}

	function woodmart_product_images_size() {
		$summary_size = ( woodmart_product_summary_size() == 12 ) ? 12 : 12 - woodmart_product_summary_size();
		return apply_filters( 'woodmart_product_summary_size', $summary_size );
	}
}

if ( ! function_exists( 'woodmart_product_summary_class' ) ) {
	function woodmart_product_summary_class() {
		$size   = woodmart_product_summary_size();
		$layout = woodmart_get_opt( 'single_product_style' );

		$class = 'col-lg-' . $size . ' col-12';

		$class .= ( $layout == 4 || $layout == 5 ) ? ' col-md-12' : ' col-md-6';

		if ( woodmart_get_opt( 'single_product_variations_price' ) ) {
			woodmart_enqueue_js_script( 'variations-price' );
			$class .= ' wd-price-outside';
		}

		return $class;
	}

	function woodmart_product_summary_size() {
		$page_layout = woodmart_get_opt( 'single_product_style' );

		$size = 6;
		switch ( $page_layout ) {
			case 1:
				$size = 8;
				break;
			case 2:
				$size = 6;
				break;
			case 3:
				$size = 4;
				break;
			case 4:
				$size = 12;
				break;
			case 5:
				$size = 12;
				break;
		}
		return apply_filters( 'woodmart_product_summary_size', $size );
	}
}

if ( ! function_exists( 'woodmart_single_product_class' ) ) {
	function woodmart_single_product_class() {
		$classes   = array();
		$classes[] = 'single-product-page';
		$classes[] = 'single-product-content';

		$design      = woodmart_product_design();
		$product_bg  = woodmart_get_opt( 'product-background' );
		$page_layout = woodmart_get_opt( 'single_product_style' );

		$classes[] = 'product-design-' . $design;
		$classes[] = 'tabs-location-' . woodmart_get_opt( 'product_tabs_location' );
		$classes[] = 'tabs-type-' . woodmart_get_opt( 'product_tabs_layout' );
		$classes[] = 'meta-location-' . woodmart_get_opt( 'product_show_meta' );
		$classes[] = 'reviews-location-' . woodmart_get_opt( 'reviews_location' );

		if ( $design == 'alt' ) {
			$classes[] = 'product-align-center';
		}

		if ( $page_layout == 4 || $page_layout == 5 ) {
			$classes[] = 'image-full-width';
		}

		if ( woodmart_get_opt( 'single_full_width' ) ) {
			$classes[] = 'product-full-width';
		}

		if ( woodmart_get_opt( 'product_summary_shadow' ) ) {
			$classes[] = 'product-summary-shadow';
		}

		if ( woodmart_product_sticky() ) {
			$classes[] = 'product-sticky-on';
		}

		if ( ! empty( $product_bg ) && ! empty( $product_bg['background-color'] ) ) {
			$classes[] = 'product-has-bg';
		} else {
			$classes[] = 'product-no-bg';
		}

		return $classes;

	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Configure product image gallery JS
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_get_product_gallery_settings' ) ) {
	function woodmart_get_product_gallery_settings() {
		return apply_filters(
			'woodmart_product_gallery_settings',
			array(
				'images_slider' => woodmart_is_main_product_images_carousel(),
				'thumbs_slider' => array(
					'enabled'  => woodmart_is_product_thumb_enabled(),
					'position' => woodmart_get_opt( 'thums_position' ),
					'items'    => array(
						'desktop'          => 4,
						'tablet_landscape' => 3,
						'tablet'           => 4,
						'mobile'           => 3,
						'vertical_items'   => 3,
					),
				),
			)
		);
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * WooCommerce enqueues 3 stylesheets by default. You can disable them all with the following snippet
 * ------------------------------------------------------------------------------------------------
 */

add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * ------------------------------------------------------------------------------------------------
 * Disable photoswipe
 * ------------------------------------------------------------------------------------------------
 */

remove_action( 'wp_footer', 'woocommerce_photoswipe' );

/**
 * ------------------------------------------------------------------------------------------------
 * Remove ordering from toolbar
 * ------------------------------------------------------------------------------------------------
 */

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

/**
 * ------------------------------------------------------------------------------------------------
 * Unhook the WooCommerce wrappers
 * ------------------------------------------------------------------------------------------------
 */

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * ------------------------------------------------------------------------------------------------
 * Disable default product zoom init
 * ------------------------------------------------------------------------------------------------
 */
add_filter( 'woocommerce_single_product_zoom_enabled', '__return_false' );

/**
 * ------------------------------------------------------------------------------------------------
 * Get CSS class for widget in shop area. Based on the number of widgets
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_get_widget_column_class' ) ) {
	function woodmart_get_widget_column_class( $sidebar_id = 'filters-area' ) {
		global $_wp_sidebars_widgets;
		if ( empty( $_wp_sidebars_widgets ) ) :
			$_wp_sidebars_widgets = get_option( 'sidebars_widgets', array() );
		endif;

		$sidebars_widgets_count = $_wp_sidebars_widgets;

		if ( isset( $sidebars_widgets_count[ $sidebar_id ] ) || $sidebar_id == 'filters-area' ) {
			$count          = ( isset( $sidebars_widgets_count[ $sidebar_id ] ) ) ? count( $sidebars_widgets_count[ $sidebar_id ] ) : 0;
			$widget_count   = apply_filters( 'widgets_count_' . $sidebar_id, $count );
			$widget_classes = 'widget-count-' . $widget_count;
			$column         = 4;
			if ( $widget_count < 4 && $widget_count != 0 ) {
				$column = $widget_count;
			}
			$widget_classes .= woodmart_get_grid_el_class( 0, $column, false, 12, 6, 6 );
			return apply_filters( 'widget_class_' . $sidebar_id, $widget_classes );
		}
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Play with woocommerce hooks
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_woocommerce_hooks' ) ) {
	function woodmart_woocommerce_hooks() {
		global $woodmart_prefix;

		$product_meta_position = woodmart_get_opt( 'product_show_meta' );
		$product_show_meta     = ( $product_meta_position != 'hide' );
		$product_show_share    = woodmart_get_opt( 'product_share' );
		$product_show_desc     = woodmart_get_opt( 'product_short_description' );
		$tabs_location         = woodmart_get_opt( 'product_tabs_location' );
		$reviews_location      = woodmart_get_opt( 'reviews_location' );

		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

		// Reviews location
		if ( $reviews_location == 'separate' ) {
			add_filter( 'woocommerce_product_tabs', 'woodmart_disable_reviews_tab', 98 );
			add_action( 'woocommerce_after_single_product_summary', 'comments_template', 50 );
		}

		// Upsells position
		if ( is_singular( 'product' ) ) {
			if ( woodmart_get_opt( 'upsells_position' ) == 'sidebar' ) {
				add_action( 'woodmart_before_sidebar_area', 'woocommerce_upsell_display', 20 );
			} else {
				add_action( 'woodmart_woocommerce_after_sidebar', 'woocommerce_upsell_display', 10 );
			}
		}

		// Disable related products option
		if ( woodmart_get_opt( 'related_products' ) && ! get_post_meta( get_the_ID(), '_woodmart_related_off', true ) ) {
			add_action( 'woodmart_woocommerce_after_sidebar', 'woocommerce_output_related_products', 20 );
		}

		// Disable product tabs title option
		if ( woodmart_get_opt( 'hide_tabs_titles' ) || get_post_meta( get_the_ID(), '_woodmart_hide_tabs_titles', true ) ) {
			add_filter( 'woocommerce_product_description_heading', '__return_false', 20 );
			add_filter( 'woocommerce_product_additional_information_heading', '__return_false', 20 );
		}

		if ( woodmart_get_opt( 'shop_filters' ) ) {

			// Use our own order widget list?
			if ( apply_filters( 'woodmart_use_custom_order_widget', true ) ) {
				if ( ! is_active_widget( false, false, 'woodmart-woocommerce-sort-by', true ) ) {
					add_action( 'woodmart_before_filters_widgets', 'woodmart_sorting_widget', 10 );
				}
				if ( woodmart_get_opt( 'shop_filters_type' ) == 'widgets' ) {
					remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
				}
			}

			// Use our custom price filter widget list?
			if ( apply_filters( 'woodmart_use_custom_price_widget', true ) && ! is_active_widget( false, false, 'woodmart-price-filter', true ) ) {
				add_action( 'woodmart_before_filters_widgets', 'woodmart_price_widget', 20 );
			}

			// Add 'filters button'
			add_action( 'woocommerce_before_shop_loop', 'woodmart_filter_buttons', 40 );
		}

		add_action( 'woocommerce_cart_is_empty', 'woodmart_empty_cart_text', 20 );

		/**
		 * Remove default empty cart text
		 */
		remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );

		// Wrapp cart totals

		add_action(
			'woocommerce_before_cart_totals',
			function() {
				echo '<div class="cart-totals-inner">';
			},
			1
		);
		add_action(
			'woocommerce_after_cart_totals',
			function() {
				echo '</div><!--.cart-totals-inner-->';
			},
			200
		);

		// Brand tab for single product
		if ( woodmart_get_opt( 'brand_tab' ) ) {
			add_filter( 'woocommerce_product_tabs', 'woodmart_product_brand_tab' );
		}

		// Poduct brand
		if ( woodmart_get_opt( 'product_brand_location' ) == 'about_title' && is_singular( 'product' ) ) {
			add_action( 'woocommerce_single_product_summary', 'woodmart_product_brand', 3 );
		} elseif ( is_singular( 'product' ) ) {
			add_action( 'woodmart_before_sidebar_area', 'woodmart_product_brand', 10 );
		}

		// Product share

		if ( $product_meta_position != 'after_tabs' && $product_show_share ) {
			add_action( 'woocommerce_single_product_summary', 'woodmart_product_share_buttons', 60 );
		}

		// Disable meta and description if turned off
		if ( $product_meta_position != 'add_to_cart' ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		}

		if ( ! $product_show_desc ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
		}

		// Product tabs location

		if ( $tabs_location == 'summary' ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
			add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 39 );
		}

		if ( $product_meta_position == 'after_tabs' ) {
			add_action(
				'woodmart_after_product_tabs',
				function() {
					echo '<div class="wd-before-product-tabs"><div class="container">';
				},
				10
			);

			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
			if ( $product_show_meta ) {
				add_action( 'woodmart_after_product_tabs', 'woocommerce_template_single_meta', 20 );
			}
			if ( $product_show_share ) {
				add_action( 'woodmart_after_product_tabs', 'woodmart_product_share_buttons', 30 );
			}

			add_action(
				'woodmart_after_product_tabs',
				function() {
					echo '</div></div>';
				},
				200
			);
		}

		// Product video, 360 view, zoom
		$video_url          = get_post_meta( get_the_ID(), '_woodmart_product_video', true );
		$images_360_gallery = woodmart_get_360_gallery_attachment_ids();
		$image_action       = woodmart_get_opt( 'image_action' );

		if ( ! empty( $video_url ) || ! empty( $images_360_gallery ) || ! empty( $image_action ) ) {
			add_action( 'woodmart_on_product_image', 'woodmart_additional_galleries_open', 25 );
			add_action( 'woodmart_on_product_image', 'woodmart_additional_galleries_close', 100 );
		}

		if ( ! empty( $video_url ) ) {
			add_action( 'woodmart_on_product_image', 'woodmart_product_video_button', 30 );
		}

		if ( ! empty( $images_360_gallery ) ) {
			add_action( 'woodmart_on_product_image', 'woodmart_product_360_view', 40 );
		}

		if ( $image_action != 'popup' && woodmart_get_opt( 'photoswipe_icon' ) ) {
			add_action( 'woodmart_on_product_image', 'woodmart_product_zoom_button', 50 );
		}

		// Custom extra content
		$extra_block = get_post_meta( get_the_ID(), '_woodmart_extra_content', true );

		if ( ! empty( $extra_block ) && $extra_block != 0 ) {
			$extra_position = get_post_meta( get_the_ID(), '_woodmart_extra_position', true );
			if ( $extra_position == 'before' ) {
				add_action( 'woocommerce_before_single_product', 'woodmart_product_extra_content', 20 );
			} elseif ( $extra_position == 'prefooter' ) {
				add_action( 'woodmart_woocommerce_after_sidebar', 'woodmart_product_extra_content', 30 );
			} else {
				add_action( 'woodmart_after_product_content', 'woodmart_product_extra_content', 20 );

			}
		}

		// Custom tabs
		add_filter( 'woocommerce_product_tabs', 'woodmart_custom_product_tabs' );

		// Timer on the single product page
		add_action( 'woocommerce_single_product_summary', 'woodmart_single_product_countdown', 15 );

		// Instagram by hashbat for product
		add_action( 'woodmart_woocommerce_after_sidebar', 'woodmart_product_instagram', 80 );

		// Cart page move totals
		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );

		// Product attibutes after of short description
		if ( woodmart_get_opt( 'attr_after_short_desc' ) ) {
			add_action( 'woocommerce_single_product_summary', 'woodmart_display_product_attributes', 21 );
			add_filter( 'woocommerce_product_tabs', 'woodmart_remove_additional_information_tab', 98 );
		}

		// Single product stock progress bar
		if ( woodmart_get_opt( 'single_stock_progress_bar' ) ) {
			add_action( 'woocommerce_single_product_summary', 'woodmart_stock_progress_bar', 16 );
		}
	}

	add_action( 'wp', 'woodmart_woocommerce_hooks', 1000 );
}

if ( ! function_exists( 'woodmart_woocommerce_init_hooks' ) ) {
	function woodmart_woocommerce_init_hooks() {
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
		add_action( 'woocommerce_before_shop_loop_item_title', 'woodmart_template_loop_product_thumbnail', 10 );

		// Remove product content link
		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

		remove_action( 'woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open', 10 );
		remove_action( 'woocommerce_after_subcategory', 'woocommerce_template_loop_category_link_close', 10 );

		remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
		add_action( 'woocommerce_before_subcategory_title', 'woodmart_category_thumb_double_size', 10 );
	}

	add_action( 'init', 'woodmart_woocommerce_init_hooks', 1000 );
}

if ( ! function_exists( 'woodmart_single_product_countdown' ) ) {
	function woodmart_single_product_countdown( $tabs ) {
		$timer = woodmart_get_opt( 'product_countdown' );
		if ( $timer ) {
			woodmart_product_sale_countdown();
		}
	}
}

if ( ! function_exists( 'woodmart_display_product_attributes' ) ) {
	function woodmart_display_product_attributes() {
		global $product;
		if ( $product && ( $product->has_attributes() || apply_filters( 'wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions() ) ) ) {
			wc_display_product_attributes( $product );
		}
	}
}

if ( ! function_exists( 'woodmart_remove_additional_information_tab' ) ) {
	function woodmart_remove_additional_information_tab( $tabs ) {
		unset( $tabs['additional_information'] );
		return $tabs;
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Additional tab
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_custom_product_tabs' ) ) {
	function woodmart_custom_product_tabs( $tabs ) {
		$additional_tab_title = woodmart_get_opt( 'additional_tab_title' );
		$custom_tab_title     = get_post_meta( get_the_ID(), '_woodmart_product_custom_tab_title', true );

		if ( $additional_tab_title ) {
			$tabs['wd_additional_tab'] = array(
				'title'    => $additional_tab_title,
				'priority' => 50,
				'callback' => 'woodmart_additional_product_tab_content',
			);
		}

		if ( $custom_tab_title ) {
			$tabs['wd_custom_tab'] = array(
				'title'    => $custom_tab_title,
				'priority' => 60,
				'callback' => 'woodmart_custom_product_tab_content',
			);
		}

		return $tabs;
	}
}

if ( ! function_exists( 'woodmart_additional_product_tab_content' ) ) {
	function woodmart_additional_product_tab_content() {
		// The new tab content
		$tab_content = woodmart_get_opt( 'additional_tab_text' );
		echo do_shortcode( $tab_content );

	}
}

if ( ! function_exists( 'woodmart_custom_product_tab_content' ) ) {
	function woodmart_custom_product_tab_content() {
		// The new tab content
		$tab_content = get_post_meta( get_the_ID(), '_woodmart_product_custom_tab_content', true );
		echo do_shortcode( $tab_content );
	}
}


if ( ! function_exists( 'woodmart_disable_reviews_tab' ) ) {
	function woodmart_disable_reviews_tab( $tabs ) {
		unset( $tabs['reviews'] );  // Removes the reviews tab
		return $tabs;
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Filters buttons
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_filter_widgts_classes' ) ) {
	function woodmart_filter_widgts_classes( $count ) {

		if ( apply_filters( 'woodmart_use_custom_order_widget', true ) && ! is_active_widget( false, false, 'woodmart-woocommerce-sort-by', true ) ) {
			$count++;
		}

		if ( apply_filters( 'woodmart_use_custom_price_widget', true ) && ! is_active_widget( false, false, 'woodmart-price-filter', true ) ) {
			$count++;
		}

		return $count;
	}

	add_filter( 'widgets_count_filters-area', 'woodmart_filter_widgts_classes' );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Force WOODMART Swatche layered nav and price widget to work
 * ------------------------------------------------------------------------------------------------
 */


add_filter( 'woocommerce_is_layered_nav_active', 'woodmart_is_layered_nav_active' );
if ( ! function_exists( 'woodmart_is_layered_nav_active' ) ) {
	function woodmart_is_layered_nav_active() {
		return is_active_widget( false, false, 'woodmart-woocommerce-layered-nav', true );
	}
}

add_filter( 'woocommerce_is_price_filter_active', 'woodmart_is_layered_price_active' );

if ( ! function_exists( 'woodmart_is_layered_price_active' ) ) {
	function woodmart_is_layered_price_active() {
		$result = is_active_widget( false, false, 'woodmart-price-filter', true );
		if ( ! $result ) {
			$result = apply_filters( 'woodmart_use_custom_price_widget', true );
		}
		return $result;
	}
}



/**
 * ------------------------------------------------------------------------------------------------
 * Change the position of woocommerce breadcrumbs
 * ------------------------------------------------------------------------------------------------
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

// **********************************************************************//
// ! Items per page select on the shop page
// **********************************************************************//

if ( ! function_exists( 'woodmart_set_customer_session' ) ) {
	function woodmart_set_customer_session() {
		if ( ! function_exists( 'WC' ) || ! apply_filters( 'woodmart_woo_session', false ) ) {
			return;
		}

		if ( WC()->version > '2.1' && ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' ) ) :
			WC()->session->set_customer_session_cookie( true );
		endif;
	}
	add_action( 'woodmart_before_shop_page', 'woodmart_set_customer_session', 10 );
}

if ( apply_filters( 'woodmart_woo_session', false ) ) {
	add_action( 'woodmart_before_shop_page', 'woodmart_woo_products_per_page_action', 100 );
} else {
	add_action( 'init', 'woodmart_products_per_page_action', 100 );
}

if ( ! function_exists( 'woodmart_products_per_page_action' ) ) {
	function woodmart_products_per_page_action() {
		if ( isset( $_REQUEST['per_page'] ) && 1 != $_REQUEST['per_page'] && ! isset( $_REQUEST['_locale'] ) && ! isset( $_REQUEST['shortcode'] ) && apply_filters( 'woodmart_per_page_custom_expression', true ) ) {
			setcookie( 'shop_per_page', intval( $_REQUEST['per_page'] ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
		}
	}
}

if ( ! function_exists( 'woodmart_woo_products_per_page_action' ) ) {
	function woodmart_woo_products_per_page_action() {
		if ( isset( $_REQUEST['per_page'] ) ) :
			if ( ! class_exists( 'WC_Session_Handler' ) ) {
				return;
			}
			$s = WC()->session; // WC()->session
			if ( is_null( $s ) ) {
				return;
			}

			$s->set( 'shop_per_page', intval( $_REQUEST['per_page'] ) );
		endif;
	}
}

// **********************************************************************//
// ! Get Items per page number on the shop page
// **********************************************************************//

if ( ! function_exists( 'woodmart_get_products_per_page' ) ) {
	function woodmart_get_products_per_page() {
		if ( apply_filters( 'woodmart_woo_session', false ) ) {
			return woodmart_woo_get_products_per_page();
		} else {
			return woodmart_new_get_products_per_page();
		}
	}
}

if ( ! function_exists( 'woodmart_new_get_products_per_page' ) ) {
	function woodmart_new_get_products_per_page() {
		if ( isset( $_REQUEST['per_page'] ) && ! empty( $_REQUEST['per_page'] ) ) {
			return intval( $_REQUEST['per_page'] );
		} elseif ( isset( $_COOKIE['shop_per_page'] ) ) {
			$val = $_COOKIE['shop_per_page'];

			if ( ! empty( $val ) ) {
				return intval( $val );
			}
		}

		return intval( woodmart_get_opt( 'shop_per_page' ) );
	}
}

if ( ! function_exists( 'woodmart_woo_get_products_per_page' ) ) {
	function woodmart_woo_get_products_per_page() {
		if ( ! class_exists( 'WC_Session_Handler' ) ) {
			return;
		}
		$s = WC()->session; // WC()->session
		if ( is_null( $s ) ) {
			return intval( woodmart_get_opt( 'shop_per_page' ) );
		}

		if ( isset( $_REQUEST['per_page'] ) && ! empty( $_REQUEST['per_page'] ) ) :
			return intval( $_REQUEST['per_page'] );
		elseif ( $s->__isset( 'shop_per_page' ) ) :
			$val = $s->__get( 'shop_per_page' );
			if ( ! empty( $val ) ) {
				return intval( $s->__get( 'shop_per_page' ) );
			}
		endif;
		return intval( woodmart_get_opt( 'shop_per_page' ) );
	}
}


// **********************************************************************//
// ! Items view select on the shop page
// **********************************************************************//
if ( apply_filters( 'woodmart_woo_session', false ) ) {
	add_action( 'woodmart_before_shop_page', 'woodmart_woo_shop_view_action', 100 );
} else {
	add_action( 'init', 'woodmart_shop_view_action', 100 );
}

if ( ! function_exists( 'woodmart_shop_view_action' ) ) {
	function woodmart_shop_view_action() {
		if ( isset( $_REQUEST['shop_view'] ) && ! isset( $_REQUEST['shortcode'] ) ) {
			setcookie( 'shop_view', $_REQUEST['shop_view'], 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
		}
		if ( isset( $_REQUEST['per_row'] ) && ! isset( $_REQUEST['shortcode'] ) ) {
			setcookie( 'shop_per_row', $_REQUEST['per_row'], 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
		}
	}
}

if ( ! function_exists( 'woodmart_woo_shop_view_action' ) ) {
	function woodmart_woo_shop_view_action() {
		if ( ! class_exists( 'WC_Session_Handler' ) ) {
			return;
		}
		$s = WC()->session; // WC()->session
		if ( is_null( $s ) ) {
			return;
		}

		if ( isset( $_REQUEST['shop_view'] ) ) {
			$s->set( 'shop_view', $_REQUEST['shop_view'] );
		}
		if ( isset( $_REQUEST['per_row'] ) ) {
			$s->set( 'shop_per_row', $_REQUEST['per_row'] );
		}
	}
}
// **********************************************************************//
// ! Get Items per ROW number on the shop page
// **********************************************************************//

if ( ! function_exists( 'woodmart_get_products_columns_per_row' ) ) {
	function woodmart_get_products_columns_per_row() {
		if ( apply_filters( 'woodmart_woo_session', false ) ) {
			return woodmart_woo_get_products_columns_per_row();
		} else {
			return woodmart_new_get_products_columns_per_row();
		}
	}
}

if ( ! function_exists( 'woodmart_get_shop_view' ) ) {
	function woodmart_get_shop_view() {
		if ( apply_filters( 'woodmart_woo_session', false ) ) {
			return woodmart_woo_get_shop_view();
		} else {
			return woodmart_new_get_shop_view();
		}
	}
}

if ( ! function_exists( 'woodmart_new_get_products_columns_per_row' ) ) {
	function woodmart_new_get_products_columns_per_row() {
		if ( isset( $_REQUEST['per_row'] ) ) {
			return intval( $_REQUEST['per_row'] );
		} elseif ( isset( $_COOKIE['shop_per_row'] ) ) {
			return intval( $_COOKIE['shop_per_row'] );
		} else {
			return intval( woodmart_get_opt( 'products_columns' ) );
		}
	}
}

if ( ! function_exists( 'woodmart_new_get_shop_view' ) ) {
	function woodmart_new_get_shop_view() {
		if ( isset( $_REQUEST['shop_view'] ) ) {
			return $_REQUEST['shop_view'];
		} elseif ( isset( $_COOKIE['shop_view'] ) ) {
			return $_COOKIE['shop_view'];
		} else {
			$shop_view = woodmart_get_opt( 'shop_view' );
			if ( $shop_view == 'grid_list' ) {
				return 'grid';
			} elseif ( $shop_view == 'list_grid' ) {
				return 'list';
			} else {
				return $shop_view;
			}
		}
	}
}

if ( ! function_exists( 'woodmart_woo_get_products_columns_per_row' ) ) {
	function woodmart_woo_get_products_columns_per_row() {
		if ( ! class_exists( 'WC_Session_Handler' ) ) {
			return;
		}
		$s = WC()->session; // WC()->session
		if ( is_null( $s ) ) {
			return intval( woodmart_get_opt( 'products_columns' ) );
		}

		if ( isset( $_REQUEST['per_row'] ) ) {
			return intval( $_REQUEST['per_row'] );
		} elseif ( $s->__isset( 'shop_per_row' ) ) {
			return intval( $s->__get( 'shop_per_row' ) );
		} else {
			return intval( woodmart_get_opt( 'products_columns' ) );
		}
	}
}

if ( ! function_exists( 'woodmart_woo_get_shop_view' ) ) {
	function woodmart_woo_get_shop_view() {
		if ( ! class_exists( 'WC_Session_Handler' ) ) {
			return;
		}
		$s = WC()->session; // WC()->session
		if ( is_null( $s ) ) {
			return woodmart_get_opt( 'shop_view' );
		}

		if ( isset( $_REQUEST['shop_view'] ) ) {
			return $_REQUEST['shop_view'];
		} elseif ( $s->__isset( 'shop_view' ) ) {
			return $s->__get( 'shop_view' );
		} else {
			$shop_view = woodmart_get_opt( 'shop_view' );
			if ( $shop_view == 'grid_list' ) {
				return 'grid';
			} elseif ( $shop_view == 'list_grid' ) {
				return 'list';
			} else {
				return $shop_view;
			}
		}
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Remove () from numbers in categories widget
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_filter_product_categories_widget_args' ) ) {
	function woodmart_filter_product_categories_widget_args( $list_args ) {

		$list_args['walker'] = new WOODMART_WC_Product_Cat_List_Walker();

		return $list_args;
	}

	add_filter( 'woocommerce_product_categories_widget_args', 'woodmart_filter_product_categories_widget_args', 10, 1 );
}

/**
 * ------------------------------------------------------------------------------------------------
 * AJAX add to cart for all product types
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_ajax_add_to_cart' ) ) {
	function woodmart_ajax_add_to_cart() {
		// Get messages
		ob_start();

		wc_print_notices();

		$notices = ob_get_clean();

		// Get mini cart
		ob_start();

		woocommerce_mini_cart();

		$mini_cart = ob_get_clean();

		// Fragments and mini cart are returned
		$data = array(
			'notices'   => $notices,
			'fragments' => apply_filters(
				'woocommerce_add_to_cart_fragments',
				array(
					'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
				)
			),
			'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() ),
		);

		wp_send_json( $data );

		die();
	}
}

add_action( 'wp_ajax_woodmart_ajax_add_to_cart', 'woodmart_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_woodmart_ajax_add_to_cart', 'woodmart_ajax_add_to_cart' );

/**
 * ------------------------------------------------------------------------------------------------
 * Function to prepare classes for grid element (column)
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_get_grid_el_class' ) ) {
	function woodmart_get_grid_el_class( $loop = 0, $columns = 4, $different_sizes = false, $xs_size = false, $sm_size = 4, $lg_size = 3, $md_size = false ) {
		$classes = '';

		$items_wide = woodmart_get_wide_items_array( $different_sizes );

		if ( ! $xs_size ) {
			$xs_size = apply_filters( 'woodmart_grid_xs_default', 6 );
		}

		if ( $columns > 0 ) {
			$lg_size = 12 / $columns;
		}

		if ( ! $md_size ) {
			$md_size = $lg_size;
		}

		if ( $columns > 4 ) {
			$md_size = 3;
		}

		if ( $columns <= 3 ) {
			if ( $columns == 1 ) {
				$sm_size = 12;
				$xs_size = 12;
			} else {
				$sm_size = 6;
			}
		}

		// every third element make 2 times larger (for isotope grid)
		if ( $different_sizes && ( in_array( $loop, $items_wide ) ) ) {
			$lg_size *= 2;
			$md_size *= 2;
		}

		if ( in_array( $columns, array( 5, 7, 8, 9, 10, 11 ) ) ) {
			$lg_size = str_replace( '.', '_', round( 100 / $columns, 1 ) );
			if ( ! strpos( $lg_size, '_' ) ) {
				$lg_size = $lg_size . '_0';
			}
		}

		$sizes = array(
			array(
				'name'  => 'col-lg',
				'value' => $lg_size,
			),
			array(
				'name'  => 'col-md',
				'value' => $md_size,
			),
			array(
				'name'  => 'col-sm',
				'value' => $sm_size,
			),
			array(
				'name'  => 'col',
				'value' => $xs_size,
			),
		);

		$result_sizes = array();
		foreach ( $sizes as $index => $value ) {
			if ( isset( $sizes[ $index + 1 ] ) ) {
				$next = $sizes[ $index + 1 ];
			} else {
				continue;
			}

			if ( $value['value'] === $next['value'] ) {
				$result_sizes[ $next['name'] ] = $next['value'];
				unset( $result_sizes[ $value['name'] ] );
			} elseif ( $value['value'] !== $next['value'] ) {
				$result_sizes[ $value['name'] ] = $value['value'];
				$result_sizes[ $next['name'] ]  = $next['value'];
			}
		}

		if ( apply_filters( 'woodmart_old_product_grid_classes', false ) ) {
			$result_sizes = array(
				'col-lg' => $lg_size,
				'col-md' => $md_size,
				'col-sm' => $sm_size,
				'col'    => $xs_size,
			);
		}

		foreach ( $result_sizes as $size => $value ) {
			$classes .= ' ' . $size . '-' . $value;
		}

		if ( $loop > 0 && $columns > 0 ) {
			if ( 0 == ( $loop - 1 ) % $columns || 1 == $columns ) {
				$classes .= ' first ';
			}
			if ( 0 == $loop % $columns ) {
				$classes .= ' last ';
			}
		}

		return $classes;
	}
}


if ( ! function_exists( 'woodmart_get_wide_items_array' ) ) {
	function woodmart_get_wide_items_array( $different_sizes = false ) {
		$items_wide = apply_filters( 'woodmart_wide_items', array( 5, 6, 7, 8, 13, 14 ) );

		if ( is_array( $different_sizes ) ) {
			$items_wide = apply_filters( 'woodmart_wide_items', $different_sizes );
		}

		return $items_wide;
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Woodmart Related product count
 * ------------------------------------------------------------------------------------------------
 */
if ( ! function_exists( 'woodmart_related_count' ) ) {
	add_filter( 'woocommerce_output_related_products_args', 'woodmart_related_count' );
	function woodmart_related_count() {
		$args['posts_per_page'] = ( woodmart_get_opt( 'related_product_count' ) ) ? woodmart_get_opt( 'related_product_count' ) : 8;
		return $args;
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Reset loop
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_reset_loop' ) ) {
	function woodmart_reset_loop() {
		unset( $GLOBALS['woodmart_loop'] );
		woodmart_setup_loop();
	}
	add_action( 'woocommerce_after_shop_loop', 'woodmart_reset_loop', 1000 );
	add_action( 'loop_end', 'woodmart_reset_loop', 1000 );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Get loop prop
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_loop_prop' ) ) {
	function woodmart_loop_prop( $prop, $default = '' ) {
		woodmart_setup_loop();

		return isset( $GLOBALS['woodmart_loop'], $GLOBALS['woodmart_loop'][ $prop ] ) ? $GLOBALS['woodmart_loop'][ $prop ] : $default;
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Set loop prop
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_set_loop_prop' ) ) {
	function woodmart_set_loop_prop( $prop, $value = '' ) {
		if ( ! isset( $GLOBALS['woodmart_loop'] ) ) {
			woodmart_setup_loop();
		}

		$GLOBALS['woodmart_loop'][ $prop ] = $value;
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Setup loop
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_setup_loop' ) ) {
	function woodmart_setup_loop( $args = array() ) {
		if ( isset( $GLOBALS['woodmart_loop'] ) ) {
			return; // If the loop has already been setup, bail.
		}

		$default_args = array(
			'products_different_sizes'    => woodmart_get_opt( 'products_different_sizes' ),
			'product_categories_design'   => woodmart_get_opt( 'categories_design' ),
			'product_categories_shadow'   => woodmart_get_opt( 'categories_with_shadow' ),
			'products_columns'            => ( woodmart_get_opt( 'per_row_columns_selector' ) ) ? apply_filters( 'loop_shop_columns', woodmart_get_products_columns_per_row() ) : woodmart_get_opt( 'products_columns' ),
			'product_categories_style'    => false,
			'product_hover'               => woodmart_get_opt( 'products_hover' ),
			'products_view'               => woodmart_get_shop_view(),
			'products_masonry'            => woodmart_get_opt( 'products_masonry' ),
			'product_quantity'            => woodmart_get_opt( 'product_quantity' ),
			'img_size'                    => false,
			'img_size_custom'             => false,

			'timer'                       => woodmart_get_opt( 'shop_countdown' ),
			'progress_bar'                => woodmart_get_opt( 'grid_stock_progress_bar' ),
			'swatches'                    => false,

			'is_slider'                   => false,
			'is_shortcode'                => false,
			'is_quick_view'               => false,

			'woocommerce_loop'            => 0,
			'woodmart_loop'               => 0,

			'parts_media'                 => true,
			'parts_title'                 => true,
			'parts_meta'                  => true,
			'parts_text'                  => true,
			'parts_btn'                   => true,

			'blog_design'                 => woodmart_get_opt( 'blog_design' ),
			'blog_type'                   => false,
			'blog_columns'                => woodmart_get_opt( 'blog_columns' ),
			'double_size'                 => false,

			'portfolio_style'             => woodmart_get_opt( 'portoflio_style' ),
			'portfolio_column'            => woodmart_get_opt( 'projects_columns' ),
			'portfolio_image_size'        => woodmart_get_opt( 'portoflio_image_size' ),
			'portfolio_image_size_custom' => false,
		);

		$GLOBALS['woodmart_loop'] = wp_parse_args( $args, $default_args );
	}
	add_action( 'woocommerce_before_shop_loop', 'woodmart_setup_loop', 10 );
	add_action( 'wp', 'woodmart_setup_loop', 50 );
	add_action( 'loop_start', 'woodmart_setup_loop', 10 );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Hide woocommerce notice
 * ------------------------------------------------------------------------------------------------
 */
if ( ! function_exists( 'woodmart_hide_outdated_templates_notice' ) ) {
	function woodmart_hide_outdated_templates_notice( $value, $notice ) {
		if ( $notice == 'template_files' ) {
			return false;
		}

		return $value;
	}

	add_filter( 'woocommerce_show_admin_notice', 'woodmart_hide_outdated_templates_notice', 2, 10 );
}

if ( ! function_exists( 'woodmart_single_product_thumbnails_gallery_image_width' ) ) {
	/**
	 * Change default `gallery_thumbnail` size values
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function woodmart_single_product_thumbnails_gallery_image_width() {
		if ( woodmart_get_opt( 'single_product_thumbnails_gallery_image_width' ) ) {
			$size = array(
				'width'  => (int) woodmart_get_opt( 'single_product_thumbnails_gallery_image_width' ),
				'height' => 0,
				'crop'   => 0,
			);
		} else {
			$size = wc_get_image_size( 'woocommerce_thumbnail' );
		}

		if ( isset( $size['height'] ) && ! $size['height'] ) {
			$size['height'] = 0;
		}

		return $size;
	}

	add_filter( 'woocommerce_get_image_size_gallery_thumbnail', 'woodmart_single_product_thumbnails_gallery_image_width', 10 );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Change single product notice position
 * ------------------------------------------------------------------------------------------------
 */
remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );

add_action( 'woodmart_before_single_product_summary_wrap', 'woocommerce_output_all_notices', 10 );
add_action( 'woodmart_before_shop_page', 'woocommerce_output_all_notices', 10 );
