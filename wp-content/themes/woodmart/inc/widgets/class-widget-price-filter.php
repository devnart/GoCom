<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * Price filter step
 *
 */

if( ! class_exists( 'WOODMART_Widget_Price_Filter' ) ) {
	class WOODMART_Widget_Price_Filter extends WPH_Widget {

		private $_check_ranges = false;
	
		function __construct() {
			if( ! woodmart_woocommerce_installed() ) return;

			// Configure widget array
			$args = array( 
				// Widget Backend label
				'label' => esc_html__( 'WOODMART Price filter', 'woodmart' ),
				// Widget Backend Description								
				'description' =>esc_html__( 'Price filter list', 'woodmart' ),
				'slug' => 'woodmart-price-filter',
			 );
		
			// Configure the widget fields
		
			// fields array
			$args['fields'] = array(
				array(
					'id'	=> 'title',
					'type'  => 'text',
					'std'   => esc_html__( 'Price filter', 'woodmart' ),
					'name' 	=> esc_html__( 'Title', 'woodmart' )
				),
				array(
					'id'	      => 'show_ranges',
					'type'        => 'checkbox',
					'name' 	      => esc_html__( 'Show empty price ranges', 'woodmart' ),
					'description' => esc_html__( 'May increase this widget performance if you have a lot of products in your store.', 'woodmart' ),
				),
			);

			$this->_check_ranges = apply_filters('woodmart_check_ranges_price_filter', true);

			// create widget
			$this->create_widget( $args );
		}
		
		// Output function
		// Based on woo widget @version  2.3.0
		function widget( $args, $instance )	{
			global $wp, $wp_the_query;

			if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
				return;
			}

			if ( ! $wp_the_query->post_count ) {
				return;
			}

			$min_price = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '';
			$max_price = isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : '';

			// Find min and max price in current result set
			//WC 3.6.0
			if ( function_exists( 'WC' ) && version_compare( WC()->version, '3.6.0', '<' ) ) {
				$prices = $this->get_filtered_price();
			} else {
				$prices = $this->get_filtered_price_new();
			}
			
			$min = apply_filters( 'woocommerce_price_filter_widget_min_amount', floor( $prices->min_price ) );
			$max = apply_filters( 'woocommerce_price_filter_widget_max_amount', ceil( $prices->max_price ) );

			if ( $min === $max ) {
				return;
			}

			echo wp_kses_post( $args['before_widget'] );

			if ( $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance ) ) {
				echo wp_kses_post( $args['before_title'] ) . $title . wp_kses_post( $args['after_title'] );
			}

			/**
			 * Adjust max if the store taxes are not displayed how they are stored.
			 * Min is left alone because the product may not be taxable.
			 * Kicks in when prices excluding tax are displayed including tax.
			 */
			if ( wc_tax_enabled() && 'incl' === get_option( 'woocommerce_tax_display_shop' ) && ! wc_prices_include_tax() ) {
				$tax_classes = array_merge( array( '' ), WC_Tax::get_tax_classes() );
				$class_max   = $max;

				foreach ( $tax_classes as $tax_class ) {
					if ( $tax_rates = WC_Tax::get_rates( $tax_class ) ) {
						$class_max = $max + WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $max, $tax_rates ) );
					}
				}

				$max = $class_max;
			}

			$show_ranges = isset( $instance['show_ranges'] ) ? $instance['show_ranges'] : true;

			$links = $this->generate_price_links( $min, $max, $min_price, $max_price, $show_ranges );

			if( ! empty( $links ) ) {
				?>
					<div class="woodmart-price-filter">
						<ul>
							<?php foreach ($links as $link) : ?>
								<li>
									<a rel="nofollow noopener" href="<?php echo esc_url( $link['href'] ); ?>" class="<?php echo esc_attr( $link['class'] ); ?>"><?php echo wp_kses( $link['title'], woodmart_get_allowed_html() ); ?></a>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php
			}

			echo wp_kses_post( $args['after_widget'] );
		}

		/**
		 * Get filtered min price for current products.
		 * @return int
		 */
		protected function get_filtered_price() {
			global $wpdb, $wp_the_query;

			$args       = $wp_the_query->query_vars;
			$tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
			$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

			if ( ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
				$tax_query[] = array(
					'taxonomy' => $args['taxonomy'],
					'terms'    => array( $args['term'] ),
					'field'    => 'slug',
				);
			}

			foreach ( $meta_query as $key => $query ) {
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
			$sql .= " 	WHERE {$wpdb->posts}.post_type = 'product'
						AND {$wpdb->posts}.post_status = 'publish'
						AND price_meta.meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
						AND price_meta.meta_value > '' ";
			$sql .= $tax_query_sql['where'] . $meta_query_sql['where'];

			return $wpdb->get_row( $sql );
		}

		protected function get_filtered_price_new() {
			global $wpdb;

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

		private function generate_price_links( $min, $max, $min_price, $max_price, $show_ranges ) {
			$links = array();

			// Remember current filters/search
			$link = woodmart_shop_page_link( true );
			$link_no_price = remove_query_arg('min_price', $link);
			$link_no_price = remove_query_arg('max_price', $link_no_price);

			$need_more = false;

			$steps = 4;
			//$steps = $max / $step_value;

			$step_value = $max / $steps;

			if( $step_value < 10) $step_value = 10;

			$step_value = round( $step_value, -1 );

			$all_link_class = '';
			if ( empty( $min_price ) && empty( $max_price ) ) {
				$all_link_class = 'current-state';
			}
			// Link to all prices
			$links[] = array(
				'href' 	=> $link_no_price,
				'title' => esc_html__('All', 'woodmart'),
				'class' => $all_link_class
			);

			for( $i = 0; $i < (int) $steps; $i++ ) {

				$step_title = $step_class = $href = '';

				$step_min = $step_value * $i;

				$step_max = $step_value * ( $i + 1 );

				if( $step_max > $max ) {
					$need_more = true;
					$i++;
					break;				
				}

				$href = add_query_arg('min_price', $step_min, $link);
				$href = add_query_arg('max_price', $step_max, $href);

				$step_title = wc_price( $step_min ) . ' - ' . wc_price( $step_max );

				if( ! empty( $min_price ) && ! empty( $max_price ) && ( $min_price >= $step_min && $max_price <= $step_max ) 
					|| ( $i == 0 && ! empty( $max_price ) && $max_price <= $step_max )
					) {
					$step_class = 'current-state';
				}

				//WC 3.6.0
				if ( function_exists( 'WC' ) && version_compare( WC()->version, '3.6.0', '<' ) ) {
					if( $this->check_range($step_min, $step_max, $show_ranges ) ) {
						$links[] = array(
							'href' 	=> $href,
							'title' => $step_title,
							'class' => $step_class
						);
					}
				} else {
					if( $this->check_range_new($step_min, $step_max, $show_ranges ) ) {
						$links[] = array(
							'href' 	=> $href,
							'title' => $step_title,
							'class' => $step_class
						);
					}
				}
			}

			if( $max > $step_max ) {
				$need_more = true; 
				$step_min = $step_value * $i;
			}

			if( $need_more ) {

				$step_title = $step_class = $href = '';

				$href = add_query_arg('min_price', $step_min, $link);
				$href = add_query_arg('max_price', $max, $href);

				$step_title = wc_price( $step_min ) . ' +';

				if( $min_price >= $step_min && $max_price <= $max ) {
					$step_class = 'current-state';
				}

				//WC 3.6.0
				if ( function_exists( 'WC' ) && version_compare( WC()->version, '3.6.0', '<' ) ) {
					if( $this->check_range($step_min, $step_max, $show_ranges ) ) {
						$links[] = array(
							'href' 	=> $href,
							'title' => $step_title,
							'class' => $step_class
						);
					}
				} else {
					if( $this->check_range_new($step_min, $step_max, $show_ranges ) ) {
						$links[] = array(
							'href' 	=> $href,
							'title' => $step_title,
							'class' => $step_class
						);
					}
				}
			}

			return $links;
		}

		private function check_range($min, $max, $show_ranges) {
			global $wpdb;

			if( ! $this->_check_ranges || $show_ranges ) return true;

			$tax_query  = WC_Query::get_main_tax_query();
			$meta_query = WC_Query::get_main_meta_query();

			$meta_query      = new WP_Meta_Query( $meta_query );
			$tax_query       = new WP_Tax_Query( $tax_query );

			$meta_query_sql  = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
			$tax_query_sql   = $tax_query->get_sql( $wpdb->posts, 'ID' );

			// Generate query
			$query           = array();
			$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as range_count";
			$query['from']   = "FROM {$wpdb->posts}";

			$query['join']   = "
				INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
				INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
				INNER JOIN {$wpdb->terms} AS terms USING( term_id )
				INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )
				" . $tax_query_sql['join'];


			$query['where']   = "
				WHERE {$wpdb->posts}.post_type IN ( 'product' )
				AND {$wpdb->posts}.post_status = 'publish'
				AND {$wpdb->postmeta}.meta_key = '_price' AND CAST({$wpdb->postmeta}.meta_value AS DECIMAL) BETWEEN '" . $min . "' AND '" . $max . "'
				" . $tax_query_sql['where'] . "
			";

			if ( method_exists('WC_Query', 'get_main_search_query_sql') && $search = WC_Query::get_main_search_query_sql() ) {
				
				$query['where'] .= ' AND ' . $search;
				
				if ( woodmart_get_opt( 'search_by_sku' ) ) {
					// search for variations with a matching sku and return the parent.
					$sku_to_parent_id = $wpdb->get_col( $wpdb->prepare( "SELECT p.post_parent as post_id FROM {$wpdb->posts} as p join {$wpdb->postmeta} pm on p.ID = pm.post_id and pm.meta_key='_sku' and pm.meta_value LIKE '%%%s%%' where p.post_parent <> 0 group by p.post_parent", wc_clean( $_GET['s'] ) ) );
					
					//Search for a regular product that matches the sku.
					$sku_to_id = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_sku' AND meta_value LIKE '%%%s%%';", wc_clean( $_GET['s'] ) ) );
					
					$search_ids = array_merge( $sku_to_id, $sku_to_parent_id );
					
					$search_ids = array_filter( array_map( 'absint', $search_ids ) );
									
					if ( sizeof( $search_ids ) > 0 ) {
						$query['where'] = str_replace( '))', ") OR ({$wpdb->posts}.ID IN (" . implode( ',', $search_ids ) . ")))", $query['where'] );
					}
				}
				
			}

			$query             = implode( ' ', $query );
			$results           = $wpdb->get_var( $query );

			return $results > 0;
		}

		private function check_range_new($min, $max, $show_ranges) {
			global $wpdb;

			if( ! $this->_check_ranges || $show_ranges ) return true;

			$tax_query  = WC_Query::get_main_tax_query();
			$meta_query = WC_Query::get_main_meta_query();

			$meta_query      = new WP_Meta_Query( $meta_query );
			$tax_query       = new WP_Tax_Query( $tax_query );

			$meta_query_sql  = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
			$tax_query_sql   = $tax_query->get_sql( $wpdb->posts, 'ID' );

			// Generate query
			$query           = array();
			$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as range_count";
			$query['from']   = "FROM {$wpdb->posts}";

			$query['join']   = "
				INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
				INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
				INNER JOIN {$wpdb->terms} AS terms USING( term_id )
				INNER JOIN {$wpdb->wc_product_meta_lookup} ON ( {$wpdb->posts}.ID = {$wpdb->wc_product_meta_lookup}.product_id )
				" . $tax_query_sql['join'];


			$query['where']   = "
				WHERE {$wpdb->posts}.post_type IN ( 'product' )
				AND {$wpdb->posts}.post_status = 'publish'
				AND {$wpdb->wc_product_meta_lookup}.min_price >= '" . $min . "' AND {$wpdb->wc_product_meta_lookup}.max_price <= '" . $max . "'
				" . $tax_query_sql['where'] . "
			";

			if ( method_exists('WC_Query', 'get_main_search_query_sql') && $search = WC_Query::get_main_search_query_sql() ) {
				
				$query['where'] .= ' AND ' . $search;
				
				if ( woodmart_get_opt( 'search_by_sku' ) ) {
					// search for variations with a matching sku and return the parent.
					$sku_to_parent_id = $wpdb->get_col( $wpdb->prepare( "SELECT p.post_parent as post_id FROM {$wpdb->posts} as p join {$wpdb->wc_product_meta_lookup} ml on p.ID = ml.product_id and ml.sku LIKE '%%%s%%' where p.post_parent <> 0 group by p.post_parent", wc_clean( $_GET['s'] ) ) );
					
					//Search for a regular product that matches the sku.
					$sku_to_id = $wpdb->get_col( $wpdb->prepare( "SELECT product_id FROM {$wpdb->wc_product_meta_lookup} WHERE sku LIKE '%%%s%%';", wc_clean( $_GET['s'] ) ) );
					
					$search_ids = array_merge( $sku_to_id, $sku_to_parent_id );
					
					$search_ids = array_filter( array_map( 'absint', $search_ids ) );
									
					if ( sizeof( $search_ids ) > 0 ) {
						$query['where'] = str_replace( '))', ") OR ({$wpdb->posts}.ID IN (" . implode( ',', $search_ids ) . ")))", $query['where'] );
					}
				}
				
			}

			$query             = implode( ' ', $query );
			$results           = $wpdb->get_var( $query );

			return $results > 0;
		}

		function form( $instance ) {
			parent::form( $instance );
		}
	}
}

