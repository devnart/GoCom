<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * Register widget based on VC_MAP parameters that display isntagram widget
 *
 */

if ( ! class_exists( 'WOODMART_Instagram_Widget' ) ) {
	class WOODMART_Instagram_Widget extends WPH_Widget {
	
		function __construct() {
			if( ! function_exists( 'woodmart_get_instagram_params' ) ) return;
		
			// Configure widget array
			$args = array( 
				// Widget Backend label
				'label' => esc_html__( 'WOODMART Instagram', 'woodmart' ), 
				// Widget Backend Description								
				'description' => esc_html__( 'Instagram photos', 'woodmart' ), 		
				'slug' => 'woodmart-instagram',
			 );
		
			// Configure the widget fields
		
			// fields array
			$args['fields'] = woodmart_get_instagram_params();

			// create widget
			$this->create_widget( $args );
		}
		
		// Output function

		function widget( $args, $instance )	{

			extract($args);

			echo wp_kses_post( $before_widget );

			if(!empty($instance['title'])) { echo wp_kses_post( $before_title ). $instance['title'] . wp_kses_post( $after_title ); };

			do_action( 'wpiw_before_widget', $instance );

			$instance['title'] = '';
			$instance['spacing'] = 1;
			$instance['spacing_custom'] = 6;
			$instance['per_row'] = 3;
			$instance['username'] = $instance['username'] ? $instance['username'] : 'flickr';

			echo woodmart_shortcode_instagram( $instance );

			do_action( 'wpiw_after_widget', $instance );

			echo wp_kses_post( $after_widget );
		}
	
	} // class
}
