<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* ------------------------------------------------------------------------------------------------
* 3D view - images in 360 slider
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_shortcode_3d_view' ) ) {
	function woodmart_shortcode_3d_view( $atts, $content ) {
		if( ! function_exists( 'wpb_getImageBySize' ) ) return;
		$click = $output = $class = '';
		extract( shortcode_atts( array(
			'images' => '',
			'img_size' => 'full',
			'title' => '',
			'link' => '',
			'style' => '',
			'el_class' => ''
		), $atts ) );

		$id = rand( 100, 999 );

		$images = explode( ',', $images );

		if( $link != '' ) {
			$class .= ' cursor-pointer';
		}

		$class .= ' ' . $el_class;

		$frames_count = count( $images );

		if ( $frames_count < 2 ) return;

		woodmart_enqueue_js_library( 'threesixty' );
		woodmart_enqueue_js_script( 'view3d-element' );
		woodmart_enqueue_inline_style( '360degree' );

		$args = array(
			'frames_count' => count( $images ),
			'images'       => array(),
			'width'        => '',
			'height'       => '',
		);

		 foreach ( $images as $img_id ) {
			 $img = wpb_getImageBySize( array( 'attach_id' => $img_id, 'thumb_size' => $img_size, 'class' => 'threed-view-image' ) );
			 $args['width'] = $img['p_img_large'][1];
			 $args['height'] = $img['p_img_large'][2];
			 $args['images'][] = $img['p_img_large'][0];
		 }

		ob_start(); ?>
			<div class="wd-threed-view<?php echo esc_attr( $class ); ?> threed-id-<?php echo esc_attr( $id ); ?>" <?php if( ! empty( $link ) ): ?>onclick="window.location.href='<?php echo esc_js( $link ) ?>'"<?php endif; ?> data-args='<?php echo wp_json_encode( $args ); ?>'>
				<?php if ( ! empty( $title ) ): ?>
					<h3 class="threed-title"><span><?php echo wp_kses( $title, woodmart_get_allowed_html() ); ?></span></h3>
				<?php endif ?>
				<ul class="threed-view-images"></ul>
			    <div class="spinner">
			        <span>0%</span>
			    </div>
			</div>
		<?php

		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
