<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * Widget sort by
 *
 */

if ( ! class_exists( 'WOODMART_Widget_Sorting' ) ) {
	class WOODMART_Widget_Sorting extends WPH_Widget {
	
		function __construct() {
			if( ! woodmart_woocommerce_installed() || ! function_exists( 'wc_get_attribute_taxonomies' ) ) return;

			// Configure widget array
			$args = array( 
				// Widget Backend label
				'label' => esc_html__( 'WOODMART WooCommerce Sort by', 'woodmart' ),
				// Widget Backend Description								
				'description' =>esc_html__( 'Sort products by name, price, popularity etc.', 'woodmart' ),
				'slug' => 'woodmart-woocommerce-sort-by',
			 );
		
			// Configure the widget fields
		
			// fields array
			$args['fields'] = array(
				array(
					'id'	=> 'title',
					'type'  => 'text',
					'std'   => esc_html__( 'Sort by', 'woodmart' ),
					'name' 	=> esc_html__( 'Title', 'woodmart' )
				),
			);

			// create widget
			$this->create_widget( $args );
		}
		
		// Output function
		// Based on woo widget @version  2.3.0
		function widget( $args, $instance )	{
			global $wp_query;

			if ( ! woocommerce_products_will_display() ) {
				return;
			}

			$orderby                 = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
			$show_default_orderby    = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
			$catalog_orderby_options = apply_filters( 'woocommerce_catalog_orderby', array(
				'menu_order' => esc_html__( 'Default', 'woodmart' ),
				'popularity' => esc_html__( 'Popularity', 'woodmart' ),
				'rating'     => esc_html__( 'Average rating', 'woodmart' ),
				'date'       => esc_html__( 'Newness', 'woodmart' ),
				'price'      => esc_html__( 'Price: low to high', 'woodmart' ),
				'price-desc' => esc_html__( 'Price: high to low', 'woodmart' )
			) );

			if ( ! $show_default_orderby ) {
				unset( $catalog_orderby_options['menu_order'] );
			}

			if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
				unset( $catalog_orderby_options['rating'] );
			}

			echo wp_kses_post( $args['before_widget'] );

			if ( $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance ) ) {
				echo wp_kses_post( $args['before_title'] ) . $title . wp_kses_post( $args['after_title'] );
			}
			
			wc_get_template( 'loop/orderby.php', array( 
				'catalog_orderby_options' => $catalog_orderby_options, 
				'orderby' => $orderby, 
				'show_default_orderby' => $show_default_orderby, 
				'list' => true
			) );

			echo wp_kses_post( $args['after_widget'] );
		}

		function form( $instance ) {
			parent::form( $instance );
		}
	
	} // class
}
