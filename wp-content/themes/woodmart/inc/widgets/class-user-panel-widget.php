<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * Register widget based on VC_MAP parameters that displays user panel
 *
 */

if ( ! class_exists( 'WOODMART_User_Panel_Widget' ) ) {
	class WOODMART_User_Panel_Widget extends WPH_Widget {
	
		function __construct() {
			if( ! function_exists( 'woodmart_get_user_panel_params' ) ) return;
		
			// Configure widget array
			$args = array( 
				// Widget Backend label
				'label' => esc_html__( 'WOODMART User Panel', 'woodmart' ), 
				// Widget Backend Description								
				'description' => esc_html__( 'User panel to use in My Account area', 'woodmart' ), 
				'slug' => 'woodmart-user-panel',
			 );
		
			// Configure the widget fields
		
			// fields array
			$args['fields'] = woodmart_get_user_panel_params();

			// create widget
			$this->create_widget( $args );
		}
		
		// Output function

		function widget( $args, $instance )	{
			extract($args);

			echo wp_kses_post( $before_widget );

			if(!empty($instance['title'])) { echo wp_kses_post( $before_title ) . $instance['title'] . wp_kses_post( $after_title ); };

			do_action( 'wpiw_before_widget', $instance );

			$instance['title'] = '';

			echo woodmart_shortcode_user_panel( $instance );

			do_action( 'wpiw_after_widget', $instance );

			echo wp_kses_post( $after_widget );
		}

		function form( $instance ) {
			parent::form( $instance );
		}
	
	} // class
}
