<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
 * ------------------------------------------------------------------------------------------------
 * Categories grid shortcode
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_shortcode_categories' ) ) {
	function woodmart_shortcode_categories( $atts, $content ) {
		$extra_class = $carousel_classes = '';
		
		$parsed_atts = shortcode_atts( array_merge( woodmart_get_owl_atts(), array(
			'title' => esc_html__( 'Categories', 'woodmart' ),
			'number'     => null,
			'orderby'    => '',
			'order'      => 'ASC',
			'columns'    => '4',
			'hide_empty' => 'yes',
			'categories_with_shadow' => woodmart_get_opt( 'categories_with_shadow' ),
			'parent'     => '',
			'style'      => 'default',
			'ids'        => '',
			'categories_design' => woodmart_get_opt( 'categories_design' ),
			'spacing' => 30,
			'lazy_loading' => 'no',
			'scroll_carousel_init' => 'no',
			'el_class' => ''
		) ), $atts );
		
		extract( $parsed_atts );
		
		if ( isset( $ids ) ) {
			$ids = explode( ',', $ids );
			$ids = array_map( 'trim', $ids );
		} else {
			$ids = array();
		}
		
		$hide_empty = ( $hide_empty == 'yes' || $hide_empty == 1 ) ? 1 : 0;
		
		// get terms and workaround WP bug with parents/pad counts
		$args = array(
			'order'      => $order,
			'hide_empty' => $hide_empty,
			'include'    => $ids,
			'pad_counts' => true,
			'child_of'   => $parent
		);
		
		if ( $orderby ) $args['orderby'] = $orderby;
		
		$product_categories = get_terms( 'product_cat', $args );
		
		if ( '' !== $parent ) {
			$product_categories = wp_list_filter( $product_categories, array( 'parent' => $parent ) );
		}
		
		if ( $hide_empty ) {
			foreach ( $product_categories as $key => $category ) {
				if ( $category->count == 0 ) {
					unset( $product_categories[ $key ] );
				}
			}
		}
		
		if ( $number ) {
			$product_categories = array_slice( $product_categories, 0, $number );
		}
		
		$columns = absint( $columns );
		
		if( $style == 'masonry' ) {
			$extra_class .= ' categories-masonry';
			wp_enqueue_script( 'imagesloaded' );
			woodmart_enqueue_js_library( 'isotope-bundle' );
			woodmart_enqueue_js_script( 'shop-masonry' );
		}
		
		woodmart_set_loop_prop( 'products_different_sizes', false );
		
		if( $style == 'masonry-first' ) {
			woodmart_set_loop_prop( 'products_different_sizes', array( 1 ) );
			$extra_class .= ' categories-masonry';
			$columns = 4;
			wp_enqueue_script( 'imagesloaded' );
			woodmart_enqueue_js_library( 'isotope-bundle' );
			woodmart_enqueue_js_script( 'shop-masonry' );
		}
		
		if ( $style == 'carousel' ) {
			$extra_class .= ' wd-carousel-spacing-' . $spacing;
		} else {
			$extra_class .= ' wd-spacing-' . $spacing;
		}
		
		$extra_class .= $el_class ? ' ' . $el_class : '';
		
		if ( empty( $categories_design ) || $categories_design == 'inherit' ) $categories_design = woodmart_get_opt( 'categories_design' );
		
		woodmart_set_loop_prop( 'product_categories_design', $categories_design );
		woodmart_set_loop_prop( 'product_categories_shadow', $categories_with_shadow );
		woodmart_set_loop_prop( 'products_columns', $columns );
		woodmart_set_loop_prop( 'product_categories_style', $style );
		
		$carousel_id = 'carousel-' . rand( 100, 999 );

		ob_start();

		if ( $lazy_loading == 'yes' ) {
			woodmart_lazy_loading_init( true );
			woodmart_enqueue_inline_style( 'lazy-loading' );
		}

		if ( $product_categories ) {
			woodmart_enqueue_inline_style( 'categories-loop' );
			if ( $style == 'carousel' ) {
				woodmart_enqueue_inline_style( 'owl-carousel' );
				$custom_sizes = apply_filters( 'woodmart_categories_shortcode_custom_sizes', false );
				
				$parsed_atts['carousel_id'] = $carousel_id;
				$parsed_atts['post_type'] = 'product';
				$parsed_atts['custom_sizes'] = $custom_sizes;
				
				if ( $scroll_carousel_init == 'yes' ) {
					woodmart_enqueue_js_library( 'waypoints' );
					$carousel_classes .= ' scroll-init';
				}
				
				if ( woodmart_get_opt( 'disable_owl_mobile_devices' ) ) {
					$carousel_classes .= ' disable-owl-mobile';
				}
				
				$carousel_classes .= ' wd-wpb categories-style-' . $style;

				?>
				
				<div id="<?php echo esc_attr( $carousel_id ); ?>" class="products woocommerce wd-carousel-container <?php echo esc_attr( $carousel_classes ); ?> <?php echo esc_attr( $extra_class ); ?>" <?php echo woodmart_get_owl_attributes( $parsed_atts ); ?>>
					<div class="owl-carousel carousel-items <?php echo woodmart_owl_items_per_slide( $slides_per_view, array(), 'product', false, $custom_sizes ); ?>">
						<?php foreach ( $product_categories as $category ): ?>
							<?php
							wc_get_template( 'content-product-cat.php', array(
								'category' => $category
							) );
							?>
						<?php endforeach; ?>
					</div>
				</div> <!-- end #<?php echo esc_html( $carousel_id ); ?> -->
				
				<?php
			} else {
				
				foreach ( $product_categories as $category ) {
					wc_get_template( 'content-product-cat.php', array(
						'category' => $category
					) );
				}
			}
			
		}
		
		woodmart_reset_loop();
		
		if ( function_exists( 'woocommerce_reset_loop' ) ) woocommerce_reset_loop();
		
		if ( $lazy_loading == 'yes' ) {
			woodmart_lazy_loading_deinit();
		}
		
		if( $style == 'carousel' ) {
			return ob_get_clean();
		} else {
			return '<div class="products woocommerce row categories-style-'. esc_attr( $style ) . ' ' . esc_attr( $extra_class ) . ' columns-' . $columns . '">' . ob_get_clean() . '</div>';
		}
	}
}