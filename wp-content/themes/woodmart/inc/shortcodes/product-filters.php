<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* ------------------------------------------------------------------------------------------------
* Product filters
* ------------------------------------------------------------------------------------------------
*/

if ( ! function_exists( 'woodmart_product_filters_shortcode' ) ) {
	function woodmart_product_filters_shortcode( $atts, $content ) {
		global $wp;
		$classes = '';
		extract( shortcode_atts( array(
			'woodmart_color_scheme' => 'dark',
			'css' => '',
			'el_class' => '',
		), $atts) );
		
		if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$classes .= ' ' . vc_shortcode_custom_css_class( $css );
		}
		
		$classes .= ' color-scheme-' . $woodmart_color_scheme;
		$classes .= ( $el_class ) ? ' ' . $el_class : '';

		$form_action = wc_get_page_permalink( 'shop' );

		if ( woodmart_is_shop_archive() && apply_filters( 'woodmart_filters_form_action_without_cat_widget', false ) ) {
			if ( '' === get_option( 'permalink_structure' ) ) {
				$form_action = remove_query_arg( array( 'page', 'paged', 'product-page' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
			} else {
				$form_action = preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ) );
			}
		}

		if ( woodmart_is_shop_archive() ) {
			$classes .= ' with-ajax';
		}

		$classes .= woodmart_get_old_classes( ' woodmart-product-filters' );

		woodmart_enqueue_js_script( 'product-filters' );

		$output = '<form action="' . esc_url( $form_action ) . '" class="wd-product-filters' . esc_attr( $classes ) . '" method="GET">';
			$output .= do_shortcode( $content );
			$output .= '<div class="wd-pf-btn"><button type="submit">' . esc_html__( 'Filter', 'woodmart' ) . '</button></div>';
		$output .= '</form>';

		return $output;
	}
	
}

/**
* ------------------------------------------------------------------------------------------------
* Categories widget
* ------------------------------------------------------------------------------------------------
*/
if ( ! function_exists( 'woodmart_filters_categories_shortcode' ) ) {
	function woodmart_filters_categories_shortcode( $atts, $content ) {
		global $wp_query, $post;
		$classes = '';
		extract( shortcode_atts( array(
			'title' => esc_html__( 'Categories', 'woodmart' ),
			'hierarchical' => 1,
			'order_by' => 'name',
			'hide_empty' => '',
			'show_categories_ancestors' => '',
			'el_class' => '',
		), $atts) );

		$classes .= ( $el_class ) ? ' ' . $el_class : '';
		
		$list_args = array(
			'hierarchical' => $hierarchical,
			'taxonomy' => 'product_cat',
			'hide_empty' => $hide_empty,
 			'title_li' => false,
			'walker' => new WOODMART_Custom_Walker_Category(),
			'use_desc_for_title' => false,
			'orderby' => $order_by,
			'echo' => false,
		);
		
		if ( 'order' === $order_by ) {
			$list_args['orderby']  = 'meta_value_num';
			$list_args['meta_key'] = 'order';
		}

		$cat_ancestors = array();

		if ( is_tax( 'product_cat' ) ) {
			$current_cat = $wp_query->queried_object;
			$cat_ancestors = get_ancestors( $current_cat->term_id, 'product_cat' );
		} 

		$list_args['current_category'] = ( isset( $current_cat ) ) ? $current_cat->term_id : '';
		$list_args['current_category_ancestors'] = $cat_ancestors;

		if ( $show_categories_ancestors && isset( $current_cat ) ) {
			$is_cat_has_children = get_term_children( $current_cat->term_id, 'product_cat' );
			if ( $is_cat_has_children ) {
				$list_args['child_of'] = $current_cat->term_id;
			} elseif ( $current_cat->parent != 0 ) {
				$list_args['child_of'] = $current_cat->parent;
			}
			$list_args['depth'] = 1;
		}

		$output = '<div class="wd-pf-checkboxes wd-pf-categories">';
			$output .= '<div class="wd-pf-title"><span class="title-text">' . esc_html( $title ) . '</span><ul class="wd-pf-results"></ul></div>';
			$output .= '<div class="wd-pf-dropdown wd-scroll">';
				$output .= '<ul class="wd-scroll-content">';
					if ( $show_categories_ancestors && isset( $current_cat ) && $is_cat_has_children ) {
						$output .= '<li style="display:none;" class="pf-active cat-item cat-item-' . esc_attr( $current_cat->term_id ) . '">';
							$output .= '<a class="pf-value" href="' . esc_url( get_category_link( $current_cat->term_id ) ) . '" data-val="' . esc_attr( $current_cat->slug ) . '" data-title="' . esc_attr( $current_cat->name ) . '">' . esc_attr( $current_cat->name ) . '</a>';
						$output .= '</li>';
					}
					$output .= wp_list_categories( $list_args );
				$output .= '</ul>';
			$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
	
}

if ( ! function_exists( 'woodmart_stock_status_shortcode' ) ) {
	function woodmart_stock_status_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'title' => esc_html__( 'Stock status', 'woodmart' ),
			'instock' => 1,
			'onsale' => 1,
			'el_class' => '',
		), $atts) );
		
		ob_start();
		?>
			<div class="wd-pf-checkboxes wd-pf-stock multi_select">
				<input type="hidden" class="result-input" name="stock_status">
				<div class="wd-pf-title">
					<span class="title-text"><?php echo esc_html( $title ); ?></span>
					<ul class="wd-pf-results"></ul>
				</div>
		
				<div class="wd-pf-dropdown wd-scroll">
					<ul class="wd-scroll-content">
						<?php if ( $onsale ) : ?>
							<li>
								<span class="pf-value" data-val="onsale" data-title="<?php esc_html_e( 'On sale', 'woodmart' ); ?>">
									<?php esc_html_e( 'On sale', 'woodmart' ); ?>
								</span>
							</li>
						<?php endif; ?>
						
						<?php if ( $instock ) : ?>
							<li>
								<span class="pf-value" data-val="instock" data-title="<?php esc_html_e( 'In stock', 'woodmart' ); ?>">
									<?php esc_html_e( 'In stock', 'woodmart' ); ?>
								</span>
							</li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
		<?php

		return ob_get_clean();
	}
}

/**
* ------------------------------------------------------------------------------------------------
* Attributes widget
* ------------------------------------------------------------------------------------------------
*/
if ( ! function_exists( 'woodmart_filters_attribute_shortcode' ) ) {
	function woodmart_filters_attribute_shortcode( $atts, $content ) {
		$classes = '';
		extract( shortcode_atts( array(
			'title' => esc_html__( 'Filter by', 'woodmart' ),
			'attribute' => '',
			'categories' => '',
			'query_type' => 'and',
			'size' => 'normal',
			'labels' => 1,
			'el_class' => '',
		), $atts) );

		if ( isset( $categories ) ) {
			$categories = explode( ',', $categories );
			$categories = array_map( 'trim', $categories );
		} else {
			$categories = array();
		}

		$classes .= ( $el_class ) ? ' ' . $el_class : '';

		ob_start();

		the_widget( 'WOODMART_Widget_Layered_Nav', 
			array( 
				'template' => 'filter-element',
				'attribute' => $attribute,
				'query_type' => $query_type,
				'size' => $size,
				'labels' => $labels,
				'filter-title' => $title,
				'categories' => $categories,
			),
			array(							
				'before_widget' => '',
				'after_widget'  => '',
			)
		);

		return ob_get_clean();
		
	}

}

/**
* ------------------------------------------------------------------------------------------------
* Price slider widget
* ------------------------------------------------------------------------------------------------
*/
if ( ! function_exists( 'woodmart_filters_price_slider_shortcode' ) ) {
	function woodmart_filters_price_slider_shortcode( $atts, $content ) {
		global $wpdb;

		$classes = '';
		extract( shortcode_atts( array(
			'title' => esc_html__( 'Filter by price', 'woodmart' ),
			'el_class' => '',
		), $atts) );

		$classes .= ( $el_class ) ? ' ' . $el_class : '';

		wp_localize_script( 'woodmart-theme', 'woocommerce_price_slider_params', array(
				'currency_format_num_decimals' => 0,
				'currency_format_symbol'       => get_woocommerce_currency_symbol(),
				'currency_format_decimal_sep'  => esc_attr( wc_get_price_decimal_separator() ),
				'currency_format_thousand_sep' => esc_attr( wc_get_price_thousand_separator() ),
				'currency_format'              => esc_attr( str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), get_woocommerce_price_format() ) ),
			)
		);
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'wc-jquery-ui-touchpunch' );
		wp_enqueue_script( 'accounting' );

		ob_start();

		
		//WC 3.6.0
		if ( function_exists( 'WC' ) && version_compare( WC()->version, '3.6.0', '<' ) ) {
			$prices = woodmart_get_filtered_price();
		} else {
			$prices = woodmart_get_filtered_price_new();
		}
		
		$min = apply_filters( 'woocommerce_price_filter_widget_min_amount', floor( $prices->min_price ) );
		$max = apply_filters( 'woocommerce_price_filter_widget_max_amount', ceil( $prices->max_price ) );

		if ( $min === $max ) {
			return;
		}
		
		if ( ( is_shop() || is_product_taxonomy() ) && ! wc()->query->get_main_query()->post_count ) {
			return;
		}

		$min_price = isset( $_GET['min_price'] ) ? wc_clean( wp_unslash( $_GET['min_price'] ) ) : $min;
		$max_price = isset( $_GET['max_price'] ) ? wc_clean( wp_unslash( $_GET['max_price'] ) ) : $max;

		echo '<div class="wd-pf-checkboxes wd-pf-price-range multi_select widget_price_filter">
				<div class="wd-pf-title"><span class="title-text">' . esc_html( $title ) . '</span><ul class="wd-pf-results"></ul></div>
				<div class="wd-pf-dropdown">
					<div class="price_slider_wrapper">
						<div class="price_slider_widget" style="display:none;"></div>
						<div class="filter_price_slider_amount">
							<input type="hidden" class="min_price" name="min_price" value="' . esc_attr( $min_price ) . '" data-min="' . esc_attr( $min ) . '">
							<input type="hidden" class="max_price" name="max_price" value="' . esc_attr( $max_price ) . '" data-max="' . esc_attr( $max ) . '">
							<div class="price_label" style="display:none;"><span class="from"></span><span class="to"></span></div>
						</div>
					</div>
				</div>
			</div>'; 

		return ob_get_clean();
	}

}

if ( ! function_exists( 'woodmart_get_filtered_price' ) ) {
	function woodmart_get_filtered_price() {
		global $wpdb;

		if ( ! is_shop() && ! is_product_taxonomy() ) {
			$sql = "SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM {$wpdb->posts} LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id WHERE {$wpdb->posts}.post_type = 'product' AND {$wpdb->posts}.post_status = 'publish' AND price_meta.meta_key = '_price'";

			return $wpdb->get_row( $sql ); 
		}

		$args       = wc()->query->get_main_query()->query_vars;
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

		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

		$sql  = "SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM {$wpdb->posts} ";
		$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
		$sql .= " 	WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product' ) ) ) ) . "')
			AND {$wpdb->posts}.post_status = 'publish'
			AND price_meta.meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
			AND price_meta.meta_value > '' ";
		$sql .= $tax_query_sql['where'] . $meta_query_sql['where'];

		$search = WC_Query::get_main_search_query_sql();
		if ( $search ) {
			$sql .= ' AND ' . $search;
		}

		return $wpdb->get_row( $sql ); 
	}
}

if ( ! function_exists( 'woodmart_orderby_filter_template' ) ) {
	function woodmart_orderby_filter_template() {
		$options = array(
			'menu_order' => esc_html__( 'Default sorting', 'woocommerce' ),
			'popularity' => esc_html__( 'Sort by popularity', 'woocommerce' ),
			'rating'     => esc_html__( 'Sort by average rating', 'woocommerce' ),
			'date'       => esc_html__( 'Sort by latest', 'woocommerce' ),
			'price'      => esc_html__( 'Sort by price: low to high', 'woocommerce' ),
			'price-desc' => esc_html__( 'Sort by price: high to low', 'woocommerce' ),
		);

		ob_start();
		?>
		<div class="wd-pf-checkboxes wd-pf-sortby">
			<input type="hidden" class="result-input" name="orderby">

			<div class="wd-pf-title">
				<span class="title-text">
					<?php echo esc_html__( 'Sort by', 'woodmart' ); ?>
				</span>

				<ul class="wd-pf-results"></ul>
			</div>

			<div class="wd-pf-dropdown wd-scroll">
				<ul class="wd-scroll-content">
					<?php foreach ( $options as $key => $value ) : ?>
						<li>
							<span class="pf-value" data-val="<?php echo esc_attr( $key ); ?>" data-title="<?php echo esc_attr( $value ); ?>">
								<?php echo esc_html( $value ); ?>
							</span>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php

		return ob_get_clean();
	}
}