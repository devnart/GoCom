<?php
/**
 * Products template function
 *
 * @package xts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'woodmart_get_elementor_products_config' ) ) {
	/**
	 * Products element config
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function woodmart_get_elementor_products_config() {
		return array(
			// General.
			'element_title'            => '',

			// Query.
			'post_type'                => 'product',
			'include'                  => '',
			'taxonomies'               => '',
			'offset'                   => '',
			'orderby'                  => '',
			'query_type'               => 'OR',
			'order'                    => '',
			'meta_key'                 => '', // phpcs:ignore
			'exclude'                  => '',
			'shop_tools'               => '0',

			// Carousel.
			'speed'                    => '5000',
			'slides_per_view'          => '4',
			'wrap'                     => '',
			'autoplay'                 => 'no',
			'center_mode'              => 'no',
			'hide_pagination_control'  => '',
			'hide_prev_next_buttons'   => '',
			'scroll_per_page'          => 'yes',
			'carousel_js_inline'       => 'no',

			// Layout.
			'layout'                   => 'grid',
			'pagination'               => '',
			'items_per_page'           => 12,
			'spacing'                  => woodmart_get_opt( 'products_spacing' ),
			'columns'                  => [ 'size' => 4 ],
			'products_masonry'         => woodmart_get_opt( 'products_masonry' ),
			'products_different_sizes' => woodmart_get_opt( 'products_different_sizes' ),
			'product_quantity'         => woodmart_get_opt( 'product_quantity' ),

			// Design.
			'product_hover'            => woodmart_get_opt( 'products_hover' ),
			'sale_countdown'           => 0,
			'stock_progress_bar'       => 0,
			'highlighted_products'     => 0,
			'products_bordered_grid'   => 0,
			'img_size'                 => 'woocommerce_thumbnail',

			// Extra.
			'ajax_page'                => '',
			'force_not_ajax'           => 'no',
			'lazy_loading'             => 'no',
			'scroll_carousel_init'     => 'no',
			'custom_sizes'             => apply_filters( 'woodmart_products_shortcode_custom_sizes', false ),
			'elementor'                => true,
		);
	}
}


if ( ! function_exists( 'woodmart_elementor_products_template' ) ) {
	function woodmart_elementor_products_template( $settings ) {
		if ( ! woodmart_woocommerce_installed() ) {
			return '';
		}

		$default_settings = woodmart_get_elementor_products_config();
		$settings         = wp_parse_args( $settings, $default_settings );

		if ( ! $settings['spacing'] ) {
			$settings['spacing'] = woodmart_get_opt( 'products_spacing' );
		}

		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		if ( isset( $_GET['product-page'] ) ) { // phpcs:ignore
			$paged = wc_clean( wp_unslash( $_GET['product-page'] ) ); // phpcs:ignore
		}

		$is_ajax                    = woodmart_is_woo_ajax() && 'yes' !== $settings['force_not_ajax'];
		$settings['force_not_ajax'] = 'no';
		$wrapper_classes            = '';
		$products_element_classes   = '';
		$encoded_settings           = wp_json_encode( array_intersect_key( $settings, $default_settings ) );

		if ( $settings['ajax_page'] > 1 ) {
			$paged = $settings['ajax_page'];
		}

		// Query settings.
		$ordering_args = WC()->query->get_catalog_ordering_args( $settings['orderby'], $settings['order'] );

		$query_args = [
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'paged'               => $paged,
			'orderby'             => $ordering_args['orderby'],
			'order'               => $ordering_args['order'],
			'posts_per_page'      => $settings['items_per_page'],
			'meta_query'          => WC()->query->get_meta_query(), // phpcs:ignore
			'tax_query'           => WC()->query->get_tax_query(), // phpcs:ignore
		];

		if ( 'new' === $settings['post_type'] ) {
			$query_args['meta_query'][] = array(
				'relation' => 'OR',
				array(
					'key'     => '_woodmart_new_label',
					'value'   => 'on',
					'compare' => 'IN',
				),
				array(
					'key'     => '_woodmart_new_label_date',
					'value'   => date( 'Y-m-d' ), // phpcs:ignore
					'compare' => '>',
					'type'    => 'DATE',
				),
			);
		}
		if ( $ordering_args['meta_key'] ) {
			$query_args['meta_key'] = $ordering_args['meta_key']; // phpcs:ignore
		}
		if ( $settings['meta_key'] ) {
			$query_args['meta_key'] = $settings['meta_key']; // phpcs:ignore
		}
		if ( 'ids' === $settings['post_type'] && $settings['include'] ) {
			$query_args['post__in'] = $settings['include'];
		}
		if ( $settings['exclude'] ) {
			$query_args['post__not_in'] = $settings['exclude'];
		}
		if ( $settings['taxonomies'] ) {
			$taxonomy_names = get_object_taxonomies( 'product' );
			$terms          = get_terms(
				$taxonomy_names,
				[
					'orderby'    => 'name',
					'include'    => $settings['taxonomies'],
					'hide_empty' => false,
				]
			);

			if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
				if ( 'featured' === $settings['post_type'] ) {
					$query_args['tax_query'] = [ 'relation' => 'AND' ]; // phpcs:ignore
				}

				$relation = $settings['query_type'] ? $settings['query_type'] : 'OR';
				if ( count( $terms ) > 1 ) {
					$query_args['tax_query']['categories'] = [ 'relation' => $relation ];
				}

				foreach ( $terms as $term ) {
					$query_args['tax_query']['categories'][] = [
						'taxonomy'         => $term->taxonomy,
						'field'            => 'slug',
						'terms'            => [ $term->slug ],
						'include_children' => true,
						'operator'         => 'IN',
					];
				}
			}
		}
		if ( 'featured' === $settings['post_type'] ) {
			$query_args['tax_query'][] = [
				'taxonomy'         => 'product_visibility',
				'field'            => 'name',
				'terms'            => 'featured',
				'operator'         => 'IN',
				'include_children' => false,
			];
		}
		if ( apply_filters( 'woodmart_hide_out_of_stock_items', false ) && 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			$query_args['meta_query'][] = [
				'key'     => '_stock_status',
				'value'   => 'outofstock',
				'compare' => 'NOT IN',
			];
		}
		if ( $settings['order'] ) {
			$query_args['order'] = $settings['order'];
		}
		if ( $settings['offset'] ) {
			$query_args['offset'] = $settings['offset'];
		}
		if ( 'sale' === $settings['post_type'] ) {
			$query_args['post__in'] = array_merge( [ 0 ], wc_get_product_ids_on_sale() );
		}
		if ( 'bestselling' === $settings['post_type'] ) {
			$query_args['orderby']  = 'meta_value_num';
			$query_args['meta_key'] = 'total_sales'; // phpcs:ignore
			$query_args['order']    = 'DESC';
		}

		WC()->query->remove_ordering_args();

		if ( isset( $_GET['orderby'] ) && $settings['shop_tools'] ) { // phpcs:ignore
			$element_orderby = wc_clean( wp_unslash( $_GET['orderby'] ) ); // phpcs:ignore

			if ( 'date' === $element_orderby ) {
				$query_args['orderby'] = 'date';
				$query_args['order']   = 'DESC';
			} elseif ( 'price-desc' === $element_orderby ) {
				$query_args['orderby'] = 'price';
				$query_args['order']   = 'DESC';
			} else {
				$query_args['orderby'] = $element_orderby;
				$query_args['order']   = 'ASC';
			}
		}

		if ( 'price' === $query_args['orderby'] ) {
			$query_args['orderby']  = 'meta_value_num';
			$query_args['meta_key'] = '_price'; // phpcs:ignore
		}

		if ( isset( $_GET['per_page'] ) && $settings['shop_tools'] ) { // phpcs:ignore
			$query_args['posts_per_page'] = wc_clean( wp_unslash( $_GET['per_page'] ) ); // phpcs:ignore
		}

		if ( 'top_rated_products' === $settings['post_type'] ) {
			add_filter( 'posts_clauses', 'woodmart_order_by_rating_post_clauses' );
			$products = new WP_Query( apply_filters( 'woodmart_product_element_query_args', $query_args ) );
			remove_filter( 'posts_clauses', 'woodmart_order_by_rating_post_clauses' );
		} else {
			$products = new WP_Query( apply_filters( 'woodmart_product_element_query_args', $query_args ) );
		}

		// Element settings.
		if ( 'inherit' === $settings['product_hover'] ) {
			$settings['product_hover'] = woodmart_get_opt( 'products_hover' );
		}

		// Loop settings.
		woodmart_set_loop_prop( 'timer', $settings['sale_countdown'] );
		woodmart_set_loop_prop( 'progress_bar', $settings['stock_progress_bar'] );
		woodmart_set_loop_prop( 'product_hover', $settings['product_hover'] );
		woodmart_set_loop_prop( 'products_view', $settings['layout'] );
		woodmart_set_loop_prop( 'is_shortcode', true );
		woodmart_set_loop_prop( 'img_size', $settings['img_size'] );
		woodmart_set_loop_prop( 'img_size_custom', $settings['img_size_custom'] );
		if ( isset( $settings['columns']['size'] ) ) {
			woodmart_set_loop_prop( 'products_columns', $settings['columns']['size'] );
		}
		if ( $settings['products_masonry'] ) {
			woodmart_set_loop_prop( 'products_masonry', 'enable' === $settings['products_masonry'] );
		}
		if ( $settings['products_different_sizes'] ) {
			woodmart_set_loop_prop( 'products_different_sizes', 'enable' === $settings['products_different_sizes'] );
		}
		if ( $settings['product_quantity'] ) {
			woodmart_set_loop_prop( 'product_quantity', 'enable' === $settings['product_quantity'] );
		}
		if ( 'arrows' !== $settings['pagination'] ) {
			woodmart_set_loop_prop( 'woocommerce_loop', $settings['items_per_page'] * ( $paged - 1 ) );
		}
		if ( isset( $_GET['shop_view'] ) && $settings['shop_tools'] ) { // phpcs:ignore
			woodmart_set_loop_prop( 'products_view', wc_clean( wp_unslash( $_GET['shop_view'] ) ) ); // phpcs:ignore
		}

		if ( isset( $_GET['per_row'] ) && $settings['shop_tools'] ) { // phpcs:ignore
			woodmart_set_loop_prop( 'products_columns', wc_clean( wp_unslash( $_GET['per_row'] ) ) ); // phpcs:ignore
			$settings['columns']['size'] = wc_clean( wp_unslash( $_GET['per_row'] ) ); // phpcs:ignore
		}

		if ( 'carousel' === $settings['layout'] ) {
			$settings['slides_per_view'] = $settings['slides_per_view']['size'];
			woodmart_enqueue_inline_style( 'product-loop' );
			return woodmart_generate_posts_slider( $settings, $products );
		}

		// Classes.
		$wrapper_classes .= ' pagination-' . $settings['pagination'];
		if ( 'list' === $settings['layout'] ) {
			$wrapper_classes .= ' elements-list';
		} else {
			if ( ! $settings['highlighted_products'] ) {
				$wrapper_classes .= ' wd-spacing-' . $settings['spacing'];
			}

			$wrapper_classes .= ' grid-columns-' . $settings['columns']['size'];
		}
		if ( $settings['products_bordered_grid'] && ! $settings['highlighted_products'] ) {
			$wrapper_classes .= ' products-bordered-grid';
		}
		if ( woodmart_loop_prop( 'product_quantity' ) ) {
			$wrapper_classes .= ' wd-quantity-enabled';
		}
		if ( 'none' !== woodmart_get_opt( 'product_title_lines_limit' ) && 'list' !== $settings['layout'] ) {
			$wrapper_classes .= ' title-line-' . woodmart_get_opt( 'product_title_lines_limit' );
		}
		if ( woodmart_loop_prop( 'products_masonry' ) ) {
			$wrapper_classes .= ' grid-masonry';
			wp_enqueue_script( 'imagesloaded' );
			woodmart_enqueue_js_library( 'isotope-bundle' );
			woodmart_enqueue_js_script( 'shop-masonry' );
		}

		$products_element_classes .= $settings['highlighted_products'] ? ' wd-highlighted-products' : '';
		$products_element_classes .= $settings['highlighted_products'] ? woodmart_get_old_classes( ' woodmart-highlighted-products' ) : '';
		$products_element_classes .= $settings['element_title'] ? ' with-title' : '';

		if ( $is_ajax ) {
			ob_start();
		}

		wc_set_loop_prop( 'total', $products->found_posts );
		wc_set_loop_prop( 'total_pages', $products->max_num_pages );
		wc_set_loop_prop( 'current_page', $products->query['paged'] );
		wc_set_loop_prop( 'is_shortcode', true );

		if ( $products->have_posts() ) {
			woodmart_enqueue_inline_style( 'product-loop' );
		}

		// Lazy loading.
		if ( 'yes' === $settings['lazy_loading'] ) {
			woodmart_lazy_loading_init( true );
			woodmart_enqueue_inline_style( 'lazy-loading' );
		}

		?>
		<?php if ( ! $is_ajax ) : ?>
			<div class="wd-products-element<?php echo esc_attr( $products_element_classes ); ?>">
			<?php if ( 'arrows' === $settings['pagination'] ) : ?>
				<div class="wd-products-loader"><span class="wd-loader"></span></div>
			<?php endif; ?>

			<?php if ( $settings['shop_tools'] ) : ?>
				<div class="shop-loop-head">
					<div class="wd-shop-tools">
						<?php woodmart_products_per_page_select( true ); ?>
						<?php woodmart_products_view_select( true ); ?>
						<?php woocommerce_catalog_ordering(); ?>
					</div>
				</div>
			<?php endif; ?>

			<div class="products elements-grid align-items-start row wd-products-holder<?php echo esc_attr( $wrapper_classes ); ?>" data-paged="1" data-atts="<?php echo esc_attr( $encoded_settings ); ?>" data-source="shortcode" data-columns="<?php echo esc_attr( $settings['columns']['size'] ); ?>">
		<?php endif; ?>

		<?php if ( ( ! $is_ajax || 'arrows' === $settings['pagination'] ) && $settings['element_title'] ) : ?>
			<h4 class="title element-title col-12">
				<?php echo esc_html( $settings['element_title'] ); ?>
			</h4>
		<?php endif; ?>

		<?php while ( $products->have_posts() ) : ?>
			<?php $products->the_post(); ?>
			<?php wc_get_template_part( 'content', 'product' ); ?>
		<?php endwhile; ?>

		<?php if ( ! $is_ajax ) : ?>
		</div>
	<?php endif; ?>

		<?php if ( $products->max_num_pages > 1 && ! $is_ajax && $settings['pagination'] ) : ?>
			<?php wp_enqueue_script( 'imagesloaded' ); ?>
			<?php woodmart_enqueue_js_script( 'products-load-more' ); ?>
			<?php if ( 'infinit' === $settings['pagination'] ) : ?>
				<?php woodmart_enqueue_js_library( 'waypoints' ); ?>
			<?php endif; ?>
			<div class="products-footer">
				<?php if ( 'more-btn' === $settings['pagination'] || 'infinit' === $settings['pagination'] ) : ?>
					<a href="#" rel="nofollow noopener" class="btn wd-load-more wd-products-load-more load-on-<?php echo 'more-btn' === $settings['pagination'] ? 'click' : 'scroll'; ?>"><span class="load-more-label"><?php esc_html_e( 'Load more products', 'woodmart' ); ?></span></a>
					<div class="btn wd-load-more wd-load-more-loader"><span class="load-more-loading"><?php esc_html_e( 'Loading...', 'woodmart' ); ?></span></div>
				<?php elseif ( 'arrows' === $settings['pagination'] ) : ?>
					<div class="wrap-loading-arrow">
						<div class="wd-products-load-prev wd-btn-arrow disabled"></div>
						<div class="wd-products-load-next wd-btn-arrow"></div>
					</div>
				<?php elseif ( 'links' === $settings['pagination'] ) : ?>
					<?php woocommerce_pagination(); ?>
				<?php endif ?>
			</div>
		<?php endif; ?>

		<?php if ( ! $is_ajax ) : ?>
			</div>
		<?php endif; ?>

		<?php

		wc_reset_loop();
		wp_reset_postdata();
		woodmart_reset_loop();

		// Lazy loading.
		if ( 'yes' === $settings['lazy_loading'] ) {
			woodmart_lazy_loading_deinit();
		}

		if ( $is_ajax ) {
			return [
				'items'  => ob_get_clean(),
				'status' => $products->max_num_pages > $paged ? 'have-posts' : 'no-more-posts',
			];
		}
	}
}
