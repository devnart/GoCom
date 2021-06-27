<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
*  Video poster map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_add_field_to_video' ) ) { 
	function woodmart_add_field_to_video() {

	    $vc_video_new_params = array(
	         
	        array(
				'type' => 'woodmart_switch',
				'heading' => esc_html__( 'Add poster to video', 'woodmart' ),
				'param_name' => 'image_poster_switch',
				'group' => esc_html__( 'Woodmart Extras', 'woodmart' ),
				'true_state' => 'yes',
				'false_state' => 'no',
				'default' => 'no',
			),
	        array(
	            'type' => 'attach_image',
				'heading' => esc_html__( 'Image', 'woodmart' ),
				'param_name' => 'poster_image',
				'value' => '',
				'hint' => esc_html__( 'Select image from media library.', 'woodmart' ),
	            'group' => esc_html__( 'Woodmart Extras', 'woodmart' ),
				'dependency' => array(
					'element' => 'image_poster_switch',
					'value' => array( 'yes' ),
				),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
	        ),
	        array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Image size', 'woodmart' ),
				'group' => esc_html__( 'Woodmart Extras', 'woodmart' ),
				'param_name' => 'img_size',
				'hint' => esc_html__( 'Enter image size. Example: \'thumbnail\', \'medium\', \'large\', \'full\' or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use \'thumbnail\' size.', 'woodmart' ),
				'dependency' => array(
					'element' => 'image_poster_switch',
					'value' => array( 'yes' ),
				),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
				'description' => esc_html__( 'Example: \'thumbnail\', \'medium\', \'large\', \'full\' or enter image size in pixels: \'200x100\'.', 'woodmart' ),
			),      
	     
	    );
	     
	    vc_add_params( 'vc_video', $vc_video_new_params ); 
	}      
	add_action( 'vc_after_init', 'woodmart_add_field_to_video' ); 
}

// **********************************************************************//
//  Function return vc_video with image mask.
// **********************************************************************//
if( ! function_exists( 'woodmart_add_video_poster' ) ) {
	function woodmart_add_video_poster( $output, $obj, $attr ) {
		if ( isset( $attr['image_poster_switch'] ) && $attr['image_poster_switch'] === 'yes' ) {
			woodmart_enqueue_js_script( 'video-element' );
			$image_id = $attr['poster_image'];
			$image_size = 'full';
			if ( isset( $attr['img_size'] ) ) $image_size = $attr['img_size'];
			$image = woodmart_get_image_src( $image_id, $image_size );
			$output = preg_replace_callback('/wpb_video_wrapper.*?>/',
				function ( $matches ) use( $image ) {
				   return $matches[0] . '<div class="wd-video-poster-wrapper"><div class="wd-video-poster" style="background-image:url(' . esc_url( $image ) . ')";></div><div class="button-play"></div></div>';
				}, $output );
		}
		return $output;
	}
}

add_filter( 'vc_shortcode_output', 'woodmart_add_video_poster', 10, 3 );
