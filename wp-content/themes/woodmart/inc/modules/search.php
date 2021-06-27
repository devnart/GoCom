<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

// **********************************************************************// 
// Search full screen
// **********************************************************************// 
if( ! function_exists( 'woodmart_search_full_screen' ) ) {
	function woodmart_search_full_screen() {

		if ( ! whb_is_full_screen_search() ) return;

		$search_args = array(
			'type' => 'full-screen'
		);

		$settings = whb_get_settings();
		if( isset( $settings['search'] ) ) {
			$search_args['post_type'] = $settings['search']['post_type'];
			$search_args['ajax'] = $settings['search']['ajax'];
			$search_args['count'] = ( isset( $settings['search']['ajax_result_count'] ) && $settings['search']['ajax_result_count'] ) ? $settings['search']['ajax_result_count'] : 40;
		}

		woodmart_search_form( $search_args );

	}

	add_action( 'wp_footer', 'woodmart_search_full_screen', 1 );
}

// **********************************************************************// 
// Search form
// **********************************************************************// 
if( ! function_exists( 'woodmart_search_form' ) ) {
	function woodmart_search_form( $args = array() ) {

		$args = wp_parse_args( $args, array(
			'ajax' => false,
			'post_type' => false,
			'show_categories' => false,
			'type' => 'form',
			'thumbnail' => true,
			'price' => true,
			'count' => 20,
			'icon_type' => '',
			'search_style' => '',
			'custom_icon' => '',
			'el_classes' => '',
			'wrapper_custom_classes' => '',
		) );

		extract( $args );
		
		ob_start();

		$class = '';
		$btn_classes = '';
		$data  = '';
		$wrapper_classes = '';
		$dropdowns_classes = '';

		if ( $show_categories && $post_type == 'product' ) {
			$class .= ' wd-with-cat';
			$class .= woodmart_get_old_classes( ' has-categories-dropdown' );
		}

		if ( $icon_type == 'custom' ) {
			$btn_classes .= ' wd-with-img';
			$btn_classes .= woodmart_get_old_classes( ' woodmart-searchform-custom-icon' );
		}

		if ( $search_style ) {
			$class .= ' wd-style-' . $search_style;
			$class .= woodmart_get_old_classes( ' search-style-' . $search_style );
		}

		$ajax_args = array(
			'thumbnail' => $thumbnail,
			'price' => $price,
			'post_type' => $post_type,
			'count' => $count,
			'sku' => woodmart_get_opt( 'show_sku_on_ajax' ) ? '1' : '0',
			'symbols_count' => apply_filters( 'woodmart_ajax_search_symbols_count', 3 ),
		);

		if( $ajax ) {
			$class .= ' woodmart-ajax-search';
			woodmart_enqueue_js_library( 'autocomplete' );
			woodmart_enqueue_js_script( 'ajax-search' );
			foreach ($ajax_args as $key => $value) {
				$data .= ' data-' . $key . '="' . $value . '"';
			}
		}

		switch ( $post_type ) {
			case 'product':
				$placeholder = esc_attr_x( 'Search for products', 'submit button', 'woodmart' );
				$description = esc_html__( 'Start typing to see products you are looking for.', 'woodmart' );
			break;

			case 'portfolio':
				$placeholder = esc_attr_x( 'Search for projects', 'submit button', 'woodmart' );
				$description = esc_html__( 'Start typing to see projects you are looking for.', 'woodmart' );
			break;
		
			default:
				$placeholder = esc_attr_x( 'Search for posts', 'submit button', 'woodmart' );
				$description = esc_html__( 'Start typing to see posts you are looking for.', 'woodmart' );
			break;
		}

		if ( $el_classes ) {
			$class .= ' ' . $el_classes;
		}

		if ( $wrapper_custom_classes ) {
			$wrapper_classes .= ' ' . $wrapper_custom_classes;
		}

		if ( 'dropdown' === $type ) {
			$wrapper_classes .= ' wd-dropdown';
		}

		if ( 'full-screen' === $type ) {
			woodmart_enqueue_js_script( 'search-full-screen' );
			$wrapper_classes .= ' wd-fill';
		} else {
			$dropdowns_classes .= ' wd-dropdown';
		}

		if ( 'light' === whb_get_dropdowns_color() ) {
			if ( 'form' !== $type ) {
				$wrapper_classes .= ' color-scheme-light';
			}
			$dropdowns_classes .= ' color-scheme-light';
		}

		$wrapper_classes .= woodmart_get_old_classes( ' woodmart-search-' . $type );
		$dropdowns_classes .= woodmart_get_old_classes( ' woodmart-search-results' );

		?>
			<div class="wd-search-<?php echo esc_attr( $type ); ?><?php echo esc_attr( $wrapper_classes ); ?>">
				<?php if ( $type == 'full-screen' ): ?>
					<span class="wd-close-search wd-action-btn wd-style-icon wd-cross-icon<?php echo woodmart_get_old_classes( ' woodmart-close-search' ); ?>"><a></a></span>
				<?php endif ?>
				<form role="search" method="get" class="searchform <?php echo esc_attr( $class ); ?>" action="<?php echo esc_url( home_url( '/' ) ); ?>" <?php echo ! empty( $data ) ? $data : ''; ?>>
					<input type="text" class="s" placeholder="<?php echo esc_attr( $placeholder ); ?>" value="<?php echo get_search_query(); ?>" name="s" aria-label="<?php esc_html_e( 'Search', 'woodmart' ); ?>" title="<?php echo esc_attr( $placeholder ); ?>" />
					<input type="hidden" name="post_type" value="<?php echo esc_attr( $post_type ); ?>">
					<?php if( $show_categories && $post_type == 'product' ) woodmart_show_categories_dropdown(); ?>
					<button type="submit" class="searchsubmit<?php echo esc_attr( $btn_classes ); ?>">
						<?php echo esc_attr_x( 'Search', 'submit button', 'woodmart' ); ?>
						<?php 
							if ( $icon_type == 'custom' ) {
								echo whb_get_custom_icon( $custom_icon );
							}
						?>
					</button>
				</form>
				<?php if ( $type == 'full-screen' ): ?>
					<div class="search-info-text"><span><?php echo esc_html( $description ); ?></span></div>
				<?php endif ?>
				<?php if ( $ajax ): ?>
					<div class="search-results-wrapper">
						<div class="wd-dropdown-results wd-scroll<?php echo esc_attr( $dropdowns_classes ); ?>">
							<div class="wd-scroll-content"></div>
						</div>

						<?php if ( 'full-screen' === $type ) : ?>
							<div class="wd-search-loader wd-fill<?php echo woodmart_get_old_classes( ' woodmart-search-loader' ); ?>"></div>
						<?php endif; ?>
					</div>
				<?php endif ?>
			</div>
		<?php

		echo apply_filters( 'get_search_form', ob_get_clean() );
	}
}

if( ! function_exists( 'woodmart_show_categories_dropdown' ) ) {
	function woodmart_show_categories_dropdown() {
		$args = array( 
			'hide_empty' => 1,
			'parent' => 0
		);
		$terms = get_terms('product_cat', apply_filters( 'woodmart_header_search_categories_dropdown_args', $args ) );
		if( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$dropdown_classes = '';

			if ( 'light' === whb_get_dropdowns_color() ) {
				$dropdown_classes .= ' color-scheme-light';
			}

			$dropdown_classes .= woodmart_get_old_classes( ' list-wrapper' );

			woodmart_enqueue_js_script( 'simple-dropdown' );
			?>
			<div class="wd-search-cat wd-scroll<?php echo woodmart_get_old_classes( ' search-by-category' ); ?>">
				<input type="hidden" name="product_cat" value="0">
				<a href="#" rel="noffollow" data-val="0">
					<span>
						<?php esc_html_e( 'Select category', 'woodmart' ); ?>
					</span>
				</a>
				<div class="wd-dropdown wd-dropdown-search-cat wd-dropdown-menu wd-scroll-content wd-design-default<?php echo esc_attr( $dropdown_classes ); ?>">
					<ul class="wd-sub-menu<?php echo woodmart_get_old_classes( ' sub-menu' ); ?>">
						<li style="display:none;"><a href="#" data-val="0"><?php esc_html_e('Select category', 'woodmart'); ?></a></li>
						<?php
							if( ! apply_filters( 'woodmart_show_only_parent_categories_dropdown', false ) ) {
						        $args = array(
						            'title_li' => false,
									'taxonomy' => 'product_cat',
									'use_desc_for_title' => false,
						            'walker' => new WOODMART_Custom_Walker_Category(),
						        );
						        wp_list_categories($args);
							} else {
							    foreach ( $terms as $term ) {
							        ?>
										<li><a href="#" data-val="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_attr( $term->name ); ?></a></li>
							        <?php
							    }
							}
						?>
					</ul>
				</div>
			</div>
			<?php
		}
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Blog results on search page
 * ------------------------------------------------------------------------------------------------
 */
if ( ! function_exists( 'woodmart_show_blog_results_on_search_page' ) ) {
	function woodmart_show_blog_results_on_search_page() {
		if ( ! is_search() || ! woodmart_get_opt( 'enqueue_posts_results' ) ) {
			return;
		}

		$search_query = get_search_query();
		$column = woodmart_get_opt( 'search_posts_results_column' );

		ob_start();

		?>
		<div class="wd-blog-search-results">
			<h4 class="slider-title">
				<?php esc_html_e( 'Results from blog', 'woodmart' ); ?>
			</h4>
		
			<?php echo do_shortcode( '[woodmart_blog slides_per_view="' . $column . '" blog_design="carousel" search="' . $search_query . '" items_per_page="10"]' ); ?>
		
			<div class="wd-search-show-all">
				<a href="<?php echo esc_url( home_url() ) ?>?s=<?php echo esc_attr( $search_query ); ?>&post_type=post" class="button">
					<?php esc_html_e( 'Show all blog results', 'woodmart' ); ?>
				</a>
			</div>
		</div>
		<?php
		
		echo ob_get_clean();
	}
	
	add_action( 'woocommerce_after_shop_loop', 'woodmart_show_blog_results_on_search_page', 100 );
	add_action( 'woodmart_after_portfolio_loop', 'woodmart_show_blog_results_on_search_page', 100 );
	add_action( 'woodmart_after_no_product_found', 'woodmart_show_blog_results_on_search_page', 100 );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Ajax search
 * ------------------------------------------------------------------------------------------------
 */
if ( ! function_exists( 'woodmart_init_search_by_sku' ) ) {
	function woodmart_init_search_by_sku() {
		if ( apply_filters( 'woodmart_search_by_sku', woodmart_get_opt( 'search_by_sku' ) ) && woodmart_woocommerce_installed() ) {
			add_filter( 'posts_search', 'woodmart_product_search_sku', 9 );
		}
	}

	add_action( 'init', 'woodmart_init_search_by_sku', 10 );
}

if ( ! function_exists( 'woodmart_ajax_suggestions' ) ) {
	function woodmart_ajax_suggestions() {

		$allowed_types = array( 'post', 'product', 'portfolio' );
		$post_type = 'product';

		if ( apply_filters( 'woodmart_search_by_sku', woodmart_get_opt( 'search_by_sku' ) ) && woodmart_woocommerce_installed() ) {
			add_filter( 'posts_search', 'woodmart_product_ajax_search_sku', 10 );
		}
		
		$query_args = array(
			'posts_per_page' => 5,
			'post_status'    => 'publish',
			'post_type'      => $post_type,
			'no_found_rows'  => 1,
		);

		if ( ! empty( $_REQUEST['post_type'] ) && in_array( $_REQUEST['post_type'], $allowed_types ) ) {
			$post_type = strip_tags( $_REQUEST['post_type'] );
			$query_args['post_type'] = $post_type;
		}

		if ( $post_type == 'product' && woodmart_woocommerce_installed() ) {
			
			$product_visibility_term_ids = wc_get_product_visibility_term_ids();
			$query_args['tax_query']['relation'] = 'AND';

			$query_args['tax_query'][] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $product_visibility_term_ids['exclude-from-search'],
				'operator' => 'NOT IN',
			);
			
			if ( apply_filters( 'woodmart_ajax_search_product_cat_args_old_style', false ) ) {
				if ( ! empty( $_REQUEST['product_cat'] ) ) {
					$query_args['product_cat'] = strip_tags( $_REQUEST['product_cat'] );
				}
			} else {
				if ( ! empty( $_REQUEST['product_cat'] ) ) {
					$query_args['tax_query'][] = array(
						'taxonomy' => 'product_cat',
						'field'    => 'slug',
						'terms'    => strip_tags( $_REQUEST['product_cat'] ),
					);
				}
			}
		}

		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) && $post_type == 'product' ) {
			$query_args['meta_query'][] = array( 'key' => '_stock_status', 'value' => 'outofstock', 'compare' => 'NOT IN' );
		}

		if ( ! empty( $_REQUEST['query'] ) ) {
			$query_args['s'] = sanitize_text_field( $_REQUEST['query'] );
		}

		if ( ! empty( $_REQUEST['number'] ) ) {
			$query_args['posts_per_page'] = (int) $_REQUEST['number'];
		}

		$results = new WP_Query( apply_filters( 'woodmart_ajax_search_args', $query_args ) );

		if ( woodmart_get_opt( 'relevanssi_search' ) && function_exists( 'relevanssi_do_query' ) ) {
			relevanssi_do_query( $results );
		}

		$suggestions = array();

		if ( $results->have_posts() ) {

			if ( $post_type == 'product' && woodmart_woocommerce_installed() ) {
				$factory = new WC_Product_Factory();
			}

			while ( $results->have_posts() ) {
				$results->the_post();

				if ( $post_type == 'product' && woodmart_woocommerce_installed() ) {
					$product = $factory->get_product( get_the_ID() );

					$suggestions[] = array(
						'value' => html_entity_decode( get_the_title() ),
						'permalink' => get_the_permalink(),
						'price' => $product->get_price_html(),
						'thumbnail' => $product->get_image(),
						'sku' => $product->get_sku() ? esc_html__( 'SKU:', 'woodmart' ) . ' ' . $product->get_sku() : '',
					);
				} else {
					$suggestions[] = array(
						'value' => html_entity_decode( get_the_title() ),
						'permalink' => get_the_permalink(),
						'thumbnail' => get_the_post_thumbnail( null, 'medium', '' ),
					);
				}
			}

			wp_reset_postdata();
		} else {
			$suggestions[] = array(
				'value' => ( $post_type == 'product' ) ? esc_html__( 'No products found', 'woodmart' ) : esc_html__( 'No posts found', 'woodmart' ),
				'no_found' => true,
				'permalink' => ''
			);
		}

		if ( woodmart_get_opt( 'enqueue_posts_results' ) && 'post' !== $post_type ) {
			$post_suggestions = woodmart_get_post_suggestions();
			$suggestions = array_merge( $suggestions, $post_suggestions );
		}

		echo json_encode( array(
			'suggestions' => $suggestions,
		) );

		die();
	}

	add_action( 'wp_ajax_woodmart_ajax_search', 'woodmart_ajax_suggestions', 10 );
	add_action( 'wp_ajax_nopriv_woodmart_ajax_search', 'woodmart_ajax_suggestions', 10 );
}

if ( ! function_exists( 'woodmart_get_post_suggestions' ) ) {
	function woodmart_get_post_suggestions() {
		$query_args = array(
			'posts_per_page' => 5,
			'post_status'    => 'publish',
			'post_type'      => 'post',
			'no_found_rows'  => 1,
		);
		
		if ( ! empty( $_REQUEST['query'] ) ) {
			$query_args['s'] = sanitize_text_field( $_REQUEST['query'] );
		}
		
		if ( ! empty( $_REQUEST['number'] ) ) {
			$query_args['posts_per_page'] = (int) $_REQUEST['number'];
		}
		
		$results = new WP_Query( $query_args );
		$suggestions = array();

		if ( $results->have_posts() ) {

			$suggestions[] = array(
				'value' => '',
				'divider' => esc_html__( 'Results from blog', 'woodmart' ),
			);

			while ( $results->have_posts() ) {
				$results->the_post();
			
				$suggestions[] = array(
					'value' => html_entity_decode( get_the_title() ),
					'permalink' => get_the_permalink(),
					'thumbnail' => get_the_post_thumbnail( null, 'medium', '' ),
				);
			}
			
			wp_reset_postdata();
		}
		
		return $suggestions;
	}
}

if ( ! function_exists( 'woodmart_product_search_sku' ) ) {
	function woodmart_product_search_sku( $where, $class = false ) {
		global $pagenow, $wpdb, $wp;

		$type = array('product', 'jam');
		
		if ( ( is_admin() ) //if ((is_admin() && 'edit.php' != $pagenow) 
				|| !is_search()  
				|| !isset( $wp->query_vars['s'] ) 
				//post_types can also be arrays..
				|| (isset( $wp->query_vars['post_type'] ) && 'product' != $wp->query_vars['post_type'] )
				|| (isset( $wp->query_vars['post_type'] ) && is_array( $wp->query_vars['post_type'] ) && !in_array( 'product', $wp->query_vars['post_type'] ) ) 
				) {
			return $where;
		}

		$s = $wp->query_vars['s'];

		//WC 3.6.0
		if ( function_exists( 'WC' ) && version_compare( WC()->version, '3.6.0', '<' ) ) {
			return woodmart_sku_search_query( $where, $s );
		} else {
			return woodmart_sku_search_query_new( $where, $s );
		}
	}
}

if ( ! function_exists( 'woodmart_product_ajax_search_sku' ) ) {
	function woodmart_product_ajax_search_sku( $where ) {
		if ( ! empty( $_REQUEST['query'] ) ) {
			$s = sanitize_text_field( $_REQUEST['query'] );

			//WC 3.6.0
			if ( function_exists( 'WC' ) && version_compare( WC()->version, '3.6.0', '<' ) ) {
				return woodmart_sku_search_query( $where, $s );
			} else {
				return woodmart_sku_search_query_new( $where, $s );
			}
		}

		return $where;
	}
}

if ( ! function_exists( 'woodmart_sku_search_query' ) ) {
	function woodmart_sku_search_query( $where, $s ) {
		global $wpdb;

		$search_ids = array();
		$terms = explode( ',', $s );

		foreach ( $terms as $term ) {
			//Include the search by id if admin area.
			if ( is_admin() && is_numeric( $term ) ) {
				$search_ids[] = $term;
			}
			// search for variations with a matching sku and return the parent.

			$sku_to_parent_id = $wpdb->get_col( $wpdb->prepare( "SELECT p.post_parent as post_id FROM {$wpdb->posts} as p join {$wpdb->postmeta} pm on p.ID = pm.post_id and pm.meta_key='_sku' and pm.meta_value LIKE '%%%s%%' where p.post_parent <> 0 group by p.post_parent", wc_clean( $term ) ) );

			//Search for a regular product that matches the sku.
			$sku_to_id = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_sku' AND meta_value LIKE '%%%s%%';", wc_clean( $term ) ) );

			$search_ids = array_merge( $search_ids, $sku_to_id, $sku_to_parent_id );
		}

		$search_ids = array_filter( array_map( 'absint', $search_ids ) );

		if ( sizeof( $search_ids ) > 0 ) {
			$where = str_replace( ')))', ") OR ({$wpdb->posts}.ID IN (" . implode( ',', $search_ids ) . "))))", $where );
		}
		
		#remove_filters_for_anonymous_class('posts_search', 'WC_Admin_Post_Types', 'product_search', 10);
		return $where;
	}
}

if ( ! function_exists( 'woodmart_sku_search_query_new' ) ) {
	function woodmart_sku_search_query_new( $where, $s ) {
		global $wpdb;

		$search_ids = array();
		$terms = explode( ',', $s );

		foreach ( $terms as $term ) {
			//Include the search by id if admin area.
			if ( is_admin() && is_numeric( $term ) ) {
				$search_ids[] = $term;
			}
			// search for variations with a matching sku and return the parent.

			$sku_to_parent_id = $wpdb->get_col( $wpdb->prepare( "SELECT p.post_parent as post_id FROM {$wpdb->posts} as p join {$wpdb->wc_product_meta_lookup} ml on p.ID = ml.product_id and ml.sku LIKE '%%%s%%' where p.post_parent <> 0 group by p.post_parent", wc_clean( $term ) ) );

			//Search for a regular product that matches the sku.
			$sku_to_id = $wpdb->get_col( $wpdb->prepare( "SELECT product_id FROM {$wpdb->wc_product_meta_lookup} WHERE sku LIKE '%%%s%%';", wc_clean( $term ) ) );

			$search_ids = array_merge( $search_ids, $sku_to_id, $sku_to_parent_id );
		}

		$search_ids = array_filter( array_map( 'absint', $search_ids ) );

		if ( sizeof( $search_ids ) > 0 ) {
			$where = str_replace( ')))', ") OR ({$wpdb->posts}.ID IN (" . implode( ',', $search_ids ) . "))))", $where );
		}
		
		#remove_filters_for_anonymous_class('posts_search', 'WC_Admin_Post_Types', 'product_search', 10);
		return $where;
	}
}

if ( ! function_exists( 'woodmart_rlv_index_variation_skus' ) ) {
	function woodmart_rlv_index_variation_skus( $content, $post ) {
		if ( ! woodmart_get_opt( 'search_by_sku' ) || ! woodmart_get_opt( 'relevanssi_search' ) || ! function_exists( 'relevanssi_do_query' ) ) {
			return $content;
		}

		if ( $post->post_type == 'product' ) {
			
			$args = array( 'post_parent' => $post->ID, 'post_type' => 'product_variation', 'posts_per_page' => -1 );
			$variations = get_posts( $args );
			if ( !empty( $variations)) {
				foreach ( $variations as $variation ) {
					$sku = get_post_meta( $variation->ID, '_sku', true );
					$content .= " $sku";
				}
			}
		}
		
		return $content;
	}
	
	add_filter( 'relevanssi_content_to_index', 'woodmart_rlv_index_variation_skus', 10, 2 );
}
