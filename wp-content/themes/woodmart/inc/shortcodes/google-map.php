<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* ------------------------------------------------------------------------------------------------
* Google Map shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_shortcode_google_map' ) ) {
	function woodmart_shortcode_google_map( $atts, $content ) {
		$output = '';
		$parsed_atts = shortcode_atts( array(
			'title' => '',
			'lat' => 45.9,
			'lon' => 10.9,
			'style_json' => '',
			'zoom' => 15,
			'height' => 400,
			'scroll' => 'no',
			'mask' => '',
			'marker_text' => '',
			'marker_content' => '',
			'content_vertical' => 'top',
			'content_horizontal' => 'left',
			'content_width' => 300,
			'google_key' => woodmart_get_opt( 'google_map_api_key' ),
			'marker_icon' => '',
			'css_animation' => 'none',
			'el_class' => '',

			'init_type' => 'page_load',
			'init_offset' => '100',
			'map_init_placeholder' => '',
			'map_init_placeholder_size' => '',
		), $atts );
		
		extract( $parsed_atts );

		$minified = woodmart_get_opt( 'minified_js' ) ? '.min' : '';
		$version  = woodmart_get_theme_info( 'Version' );

		wp_enqueue_script( 'wd-google-map-api', 'https://maps.google.com/maps/api/js?libraries=geometry&v=3.44&key=' . $google_key, array(), $version, true );
		wp_enqueue_script( 'wd-maplace', WOODMART_THEME_DIR . '/js/libs/maplace' . $minified . '.js', array( 'wd-google-map-api' ), $version, true );

		woodmart_enqueue_js_script( 'google-map-element' );

		$content_wrapper_classes = '';
		$el_class = '';

		$content_wrapper_classes .= ' wd-items-' . $content_vertical;
		$content_wrapper_classes .= ' wd-justify-' . $content_horizontal;
		$el_class .= woodmart_get_css_animation( $css_animation );

		if ( $mask ) {
			$el_class .= ' map-mask-' . $mask;
		}

		if ( $content ) {
			$el_class .= ' map-container-with-content';
		}

		if ( 'page_load' !== $init_type ) {
			$el_class .= ' map-lazy-loading';
		}

		$uniqid = uniqid();
		
		if ( $marker_icon ) {
			$marker_url = wp_get_attachment_image_src( $marker_icon );
			$marker_icon = isset( $marker_url[0] ) ? $marker_url[0] : '';
		}else{
			$marker_icon = WOODMART_ASSETS_IMAGES . '/google-icon.png';
		}

		$map_args = array(
			'latitude'           => $lat,
			'longitude'          => $lon,
			'zoom'               => $zoom,
			'mouse_zoom'         => $scroll,
			'init_type'          => $init_type,
			'init_offset'        => $init_offset,
			'elementor'          => false,
			'json_style'         => rawurldecode( woodmart_decompress( $style_json ) ),
			'marker_icon'        => $marker_icon,
			'marker_text_needed' => $marker_text|| $title ? 'yes' : 'no',
			'marker_text'        => '<h3 style="min-width:300px; text-align:center; margin:15px;">' . $title . '</h3>' . esc_html( $marker_text ),
			'selector'           => 'wd-map-id-' . $uniqid,
		);

		$image_id = $map_init_placeholder;
		$image_size = 'full';

		if ( $map_init_placeholder_size ) {
			$image_size = $map_init_placeholder_size;
		}
		
		$placeholder = '<img src="'. WOODMART_ASSETS_IMAGES . '/google-map-placeholder.jpg">';
		
		if ( $image_id ) {
			$placeholder = wpb_getImageBySize( array( 'attach_id' => $image_id, 'thumb_size' => $image_size ) )['thumbnail'];
		}

		ob_start();

		woodmart_enqueue_inline_style( 'map' );

		?>
			<div class="google-map-container <?php echo esc_attr( $el_class );?>" style="height:<?php echo esc_attr( $height ); ?>px;" data-map-args='<?php echo wp_json_encode( $map_args ); ?>'>

				<?php if ( 'page_load' !== $init_type && $placeholder ): ?>
					<div class="wd-map-placeholder wd-fill">
						<?php echo $placeholder; ?>
					</div>
				<?php endif ?>
				
				<?php if ( 'button' === $init_type ): ?>
					<div class="wd-init-map-wrap wd-fill">
						<a href="#" rel="nofollow noopener" class="btn btn-color-white wd-init-map">
							<svg xmlns="http://www.w3.org/2000/svg" width="250.378" height="254.167" viewBox="0 0 66.246 67.248"><g transform="translate(-172.531 -218.455) scale(.98012)"><rect ry="5.238" rx="5.238" y="231.399" x="176.031" height="60.099" width="60.099" fill="#34a668" paint-order="markers stroke fill"/><path d="M206.477 260.9l-28.987 28.987a5.218 5.218 0 0 0 3.78 1.61h49.621c1.694 0 3.19-.798 4.146-2.037z" fill="#5c88c5"/><path d="M226.742 222.988c-9.266 0-16.777 7.17-16.777 16.014.007 2.762.663 5.474 2.093 7.875.43.703.83 1.408 1.19 2.107.333.502.65 1.005.95 1.508.343.477.673.957.988 1.44 1.31 1.769 2.5 3.502 3.637 5.168.793 1.275 1.683 2.64 2.466 3.99 2.363 4.094 4.007 8.092 4.6 13.914v.012c.182.412.516.666.879.667.403-.001.768-.314.93-.799.603-5.756 2.238-9.729 4.585-13.794.782-1.35 1.673-2.715 2.465-3.99 1.137-1.666 2.328-3.4 3.638-5.169.315-.482.645-.962.988-1.439.3-.503.617-1.006.95-1.508.359-.7.76-1.404 1.19-2.107 1.426-2.402 2-5.114 2.004-7.875 0-8.844-7.511-16.014-16.776-16.014z" fill="#dd4b3e" paint-order="markers stroke fill"/><ellipse ry="5.564" rx="5.828" cy="239.002" cx="226.742" fill="#802d27" paint-order="markers stroke fill"/><path d="M190.301 237.283c-4.67 0-8.457 3.853-8.457 8.606s3.786 8.607 8.457 8.607c3.043 0 4.806-.958 6.337-2.516 1.53-1.557 2.087-3.913 2.087-6.29 0-.362-.023-.722-.064-1.079h-8.257v3.043h4.85c-.197.759-.531 1.45-1.058 1.986-.942.958-2.028 1.548-3.901 1.548-2.876 0-5.208-2.372-5.208-5.299 0-2.926 2.332-5.299 5.208-5.299 1.399 0 2.618.407 3.584 1.293l2.381-2.38c0-.002-.003-.004-.004-.005-1.588-1.524-3.62-2.215-5.955-2.215zm4.43 5.66l.003.006v-.003z" fill="#fff" paint-order="markers stroke fill"/><path d="M215.184 251.929l-7.98 7.979 28.477 28.475c.287-.649.449-1.366.449-2.123v-31.165c-.469.675-.934 1.349-1.382 2.005-.792 1.275-1.682 2.64-2.465 3.99-2.347 4.065-3.982 8.038-4.585 13.794-.162.485-.527.798-.93.799-.363-.001-.697-.255-.879-.667v-.012c-.593-5.822-2.237-9.82-4.6-13.914-.783-1.35-1.673-2.715-2.466-3.99-1.137-1.666-2.327-3.4-3.637-5.169l-.002-.003z" fill="#c3c3c3"/><path d="M212.983 248.495l-36.952 36.953v.812a5.227 5.227 0 0 0 5.238 5.238h1.015l35.666-35.666a136.275 136.275 0 0 0-2.764-3.9 37.575 37.575 0 0 0-.989-1.44c-.299-.503-.616-1.006-.95-1.508-.083-.162-.176-.326-.264-.489z" fill="#fddc4f" paint-order="markers stroke fill"/><path d="M211.998 261.083l-6.152 6.151 24.264 24.264h.781a5.227 5.227 0 0 0 5.239-5.238v-1.045z" fill="#fff" paint-order="markers stroke fill"/></g></svg>
							<span><?php esc_attr_e( 'Show map','woodmart' ); ?></span>
						</a>
					</div>
				<?php endif ?>
				
				<div class="wd-google-map-wrapper wd-fill">
					<div id="wd-map-id-<?php echo esc_attr( $uniqid ); ?>" class="wd-google-map without-content wd-fill"></div>
				</div>

				<?php if ( $content ): ?>
					<div class="wd-google-map-content-wrap container<?php echo esc_attr( $content_wrapper_classes ); ?>">
						<div class="wd-google-map-content reset-last-child" style="max-width: <?php echo esc_attr( $content_width ); ?>px;">
							<?php echo do_shortcode( $content ); ?>
						</div>
					</div>
				<?php endif ?>
			</div>
		<?php

		return ob_get_clean();
	}
}
