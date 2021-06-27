<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * AJAX search widget
 *
 */

if( ! class_exists( 'WOODMART_Widget_Search' ) ) {
	class WOODMART_Widget_Search extends WPH_Widget {

		function __construct() {
			if( ! woodmart_woocommerce_installed() ) return;

			// Configure widget array
			$args = array( 
				// Widget Backend label
				'label' => esc_html__( 'WOODMART AJAX Search', 'woodmart' ),
				// Widget Backend Description								
				'description' =>esc_html__( 'Search form by products with AJAX', 'woodmart' ),
				'slug' => 'woodmart-ajax-search',
			 );
		
			// Configure the widget fields
		
			// fields array
			$args['fields'] = array(
				array(
					'id'	=> 'title',
					'type'  => 'text',
					'std'   => esc_html__( 'Search products', 'woodmart' ),
					'name' 	=> esc_html__( 'Title', 'woodmart' )
				),
				array(
					'id'   => 'post_type',
					'type' => 'dropdown',
					'std'  => 'product',
					'name' => esc_html__( 'Search post type', 'woodmart' ),
					'fields' => array(
						esc_html__( 'Product', 'woodmart' ) => 'product',
						esc_html__( 'Post', 'woodmart' ) => 'post',
						esc_html__( 'Portfolio', 'woodmart' ) => 'portfolio'
					)
				),
				array(
					'id'	=> 'number',
					'type'  => 'number',
					'std'   => 4,
					'name' 	=> esc_html__( 'Number of products to show', 'woodmart' ),
				),
				array(
					'id'	=> 'price',
					'type'  => 'checkbox',
					'std'   => 1,
					'name' 	=> esc_html__( 'Show price', 'woodmart' ),
				),
				array(
					'id'	=> 'thumbnail',
					'type'  => 'checkbox',
					'std'   => 1,
					'name' 	=> esc_html__( 'Show thumbnail', 'woodmart' ),
				),
				array(
					'id'	=> 'categories',
					'type'  => 'checkbox',
					'std'   => 1,
					'name' 	=> esc_html__( 'Show categories', 'woodmart' ),
				),
			);

			// create widget
			$this->create_widget( $args );
		}
		

		function widget( $args, $instance )	{

			extract($args);

			echo wp_kses_post( $before_widget );

			$number = empty( $instance['number'] ) ? 3 : absint( $instance['number'] );
			$thumbnail = empty( $instance['thumbnail'] ) ? 0 : absint( $instance['thumbnail'] );
			$price = empty( $instance['price'] ) ? 0 : absint( $instance['price'] );
			$post_type = empty( $instance['post_type'] ) ? 'product' : $instance['post_type'];
			
			$categories = true;
			
			if ( isset( $instance['categories'] ) ) {
				$categories = empty( $instance['categories'] ) ? 0 : absint( $instance['price'] );
			}
			
			if ( $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance ) ) {
				echo wp_kses_post( $before_title ) . $title . wp_kses_post( $after_title );
			}

			woodmart_search_form( array(
				'ajax' => true,
				'count' => $number,
				'thumbnail' => $thumbnail,
				'show_categories' => $categories,
				'post_type' => $post_type,
				'price' => $price,
			) );


			echo wp_kses_post( $after_widget );
		}

		function form( $instance ) {
			parent::form( $instance );
		}
	}
}

