<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * Register widget that displays HTML static block
 *
 */

if ( ! class_exists( 'WOODMART_Static_Block_Widget' ) ) {
	class WOODMART_Static_Block_Widget extends WPH_Widget {
	
		function __construct() {
			
		
			// Configure widget array
			$args = array( 
				// Widget Backend label
				'label' => esc_html__( 'WOODMART HTML Block', 'woodmart' ), 
				// Widget Backend Description								
				'description' => esc_html__( 'Display HTML block', 'woodmart' ), 	
				'slug' => 'woodmart-html-block',
			 );
		
		
			// fields array

			$args['fields'] = array( 	
				array(
					'id' => 'id',
					'type' => 'dropdown', 
					'heading' => 'Select block',
					'value' => woodmart_get_static_blocks_array()
				)
			
			 ); // fields array

			// create widget
			$this->create_widget( $args );
		}
		
		// Output function

		function widget( $args, $instance )	{
	
			echo woodmart_get_html_block( $instance['id'] );
		}
	
	} // class
}
