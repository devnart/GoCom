<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * Register widget based on VC_MAP parameters that display banner shortcode
 *
 */

if ( ! class_exists( 'WOODMART_Banner_Widget' ) ) {
	class WOODMART_Banner_Widget extends WPH_Widget {
	
		function __construct() {
			if( ! function_exists( 'woodmart_get_banner_params' ) ) return;
		
			// Configure widget array
			$args = array( 
				// Widget Backend label
				'label' => esc_html__( 'WOODMART Banner', 'woodmart' ), 
				// Widget Backend Description								
				'description' => esc_html__( 'Promo banner with text', 'woodmart' ),
				'slug' => 'woodmart-banner',
			 );
		
			// Configure the widget fields
		
			// fields array
			$args['fields'] = woodmart_get_banner_params();

			// create widget
			$this->create_widget( $args );
		}
		
		// Output function

		function widget( $args, $instance )	{

			extract($args);
			
 			echo wp_kses_post( $before_widget );
			
			echo woodmart_shortcode_promo_banner( $instance, $instance['content'] );

			echo wp_kses_post( $after_widget );
			
		}

		function form( $instance ) {
			$id = uniqid();
			echo '<div class="widget-'. $id .'">';
				parent::form( $instance );
				echo "<script>
					jQuery(document).ready(function() {
						if ( typeof woodmart_media_init !== 'undefined' ) {
							woodmart_media_init('.widget-". $id ." .woodmart-image-upload', '.widget-". $id ." .woodmart-image-upload-btn', '.widget-". $id ." .woodmart-image-src');
						}
					});
				</script>";
			echo '</div>';
		}
	
	} // class
}
