<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* ------------------------------------------------------------------------------------------------
* Image hotspot shortcode
* ------------------------------------------------------------------------------------------------
*/

if ( ! function_exists( 'woodmart_image_hotspot_shortcode' ) ) {
	function woodmart_image_hotspot_shortcode( $atts, $content ) {
		$image = $classes = '';
		extract( shortcode_atts( array(
			'img' => '',
			'img_size' => 'full',
			'action' => 'hover',
			'icon' => 'default',
			'woodmart_color_scheme' => 'dark',
			'el_class' => '',
			'css' => '',
		), $atts) );

		$classes .= ' hotspot-action-' . $action;
		$classes .= ' hotspot-icon-' . $icon;
		$classes .= ' color-scheme-' . $woodmart_color_scheme;
		$classes .= ( $el_class ) ? ' ' . $el_class : '';

		if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$classes .= ' ' . vc_shortcode_custom_css_class( $css );
		}

		wp_enqueue_script( 'imagesloaded' );
		woodmart_enqueue_js_script( 'hotspot-element' );
		woodmart_enqueue_js_script( 'product-more-description' );
		woodmart_enqueue_inline_style( 'image-hotspot' );

		ob_start();

		?>
		<div class="wd-image-hotspot-wrapper<?php echo esc_attr( $classes ); ?>">
			<div class="wd-image-hotspot-hotspots">
				<?php 
					if ( function_exists( 'wpb_getImageBySize' ) && $img ) {
						echo wpb_getImageBySize( array( 'attach_id' => $img, 'thumb_size' => $img_size, 'class' => 'wd-image-hotspot-img' ) )['thumbnail'];
					}
					echo do_shortcode( $content );
				?>
			</div>
		</div>
		<?php

		return  ob_get_clean();
	}
	
}

/**
* ------------------------------------------------------------------------------------------------
* Image hotspot shortcode
* ------------------------------------------------------------------------------------------------
*/

if ( ! function_exists( 'woodmart_hotspot_shortcode' ) ) {
	function woodmart_hotspot_shortcode( $atts, $content ) {
		$output = $classes = $content_classes = $product = $image = '';
		extract( shortcode_atts( array(
			'hotspot' => '',
			'hotspot_type' => 'product',
			'hotspot_dropdown_side' => 'left',
			'product_id' => '',
			'title' => '',
			'link_text' => '',
			'link' => '',
			'img' => '',
			'img_size' => 'full',
			'el_class' => '',
		), $atts) );

		$classes .= ' hotspot-type-' . $hotspot_type;
		$classes .= ( $el_class ) ? ' ' . $el_class : '';
		$content_classes .= ' hotspot-dropdown-' . $hotspot_dropdown_side;

		$position = explode( '||', $hotspot );
		$left = ( isset( $position[0] ) && $position[0] ) ? $position[0] : '50';
		$top = ( isset( $position[1] ) && $position[1] ) ? $position[1] : '50';

		if ( $product_id && woodmart_woocommerce_installed() ) $product = wc_get_product( $product_id );

		if ( $hotspot_type == 'product' && $product ) {
			$rating_count = $product->get_rating_count();
			$average = $product->get_average_rating();

			$args = array(
				'class' => implode( ' ', array_filter( array(
					'button',
					'product_type_' . $product->get_type(),
					$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
					$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
				) ) ),
				'attributes' => wc_implode_html_attributes( array(
					'data-product_id'  => $product->get_id(),
					'rel' => 'nofollow',
				) ),
				'url' => $product->add_to_cart_url(),
				'text' => $product->add_to_cart_text(),
			);

			$output = '<div class="hotspot-product hotspot-content' . esc_attr( $content_classes ) . '">';
				$output .= '<div class="hotspot-content-image"><a href="' . esc_url( get_permalink( $product->get_ID() ) ) . '">' . $product->get_image() . '</a></div>';
				$output .= '<h4 class="hotspot-content-title"><a href="' . esc_url( get_permalink( $product->get_ID() ) ) . '">' . esc_html( $product->get_title() ) . '</a></h4>';
				if ( wc_review_ratings_enabled() ) {
					$output .= wc_get_rating_html( $average, $rating_count );
				}
				$output .= '<div class="price">' . $product->get_price_html() . '</div>';
				$output .= '<div class="hotspot-content-text wd-more-desc set-cont-mb-s reset-last-child'. woodmart_get_old_classes( ' woodmart-more-desc' ) .'"><div class="wd-more-desc-inner'. woodmart_get_old_classes( ' woodmart-more-desc-inner' ) .'">' . do_shortcode( $product->get_short_description() ) . '</div></div>';
				$output .= '<a href="' . esc_url( $args['url'] ) . '" class="' . esc_attr( $args['class'] ) . '" ' . $args['attributes'] . '>' . esc_html( $args['text'] ) . '</a>';
			$output .= '</div>';
		}

		if ( $hotspot_type == 'text' && ( $title || $content || $link_text || isset( $image['thumbnail'] ) ) ) {
			if ( $link ) $attributes = woodmart_get_link_attributes( $link );

			if ( function_exists( 'wpb_getImageBySize' ) ) {
				$image = wpb_getImageBySize( array( 'attach_id' => $img, 'thumb_size' => $img_size, 'class' => 'wd-image-hotspot-img' ) );
			}

			$image_allowed_tags = array( 'img' => array( 'width' => true, 'height' => true, 'src' => true, 'alt' => true, 'data-wood-src' => true, 'data-srcset' => true, 'class' => true ) );
			
			$output = '<div class="hotspot-text hotspot-content' . esc_attr( $content_classes ) . '">';
				if ( isset( $image['thumbnail'] ) ) $output .= '<div class="hotspot-content-image">' . wp_kses( $image['thumbnail'], $image_allowed_tags ). '</div>';
				if ( $title ) $output .= '<h4 class="hotspot-content-title">' . esc_html( $title ) . '</h4>';
				if ( $content ) $output .= '<div class="hotspot-content-text set-cont-mb-s reset-last-child">' . $content . '</div>';
				if ( $link_text && $link ) $output .= '<a class="btn btn-color-primary btn-size-small" ' . $attributes . '>' . esc_html( $link_text ) . '</a>';
			$output .= '</div>';
		}

		if ( ! $output ) return;
		echo '<div class="wd-image-hotspot' . esc_attr( $classes ) . '" style="left: ' . esc_attr( $left ) . '%; top: ' . esc_attr( $top ) . '%;">';
			echo '<span class="hotspot-sonar"></span>';
			echo '<div class="hotspot-btn wd-fill"></div>';
			echo apply_filters( 'woodmart_hotspot_content', $output );
		echo '</div>';

	}
}

if( ! function_exists( 'woodmart_get_hotspot_image' ) ) {
	function woodmart_get_hotspot_image() {
		check_ajax_referer( 'woodmart-get-hotspot-image-nonce', 'security' );

		$background_image = wp_get_attachment_image_src( sanitize_text_field( $_GET[ 'image_id' ] ), 'full' );

		if ( ! $background_image ) {
			$response = array(
				'status' => 'warning',
				'html' => '<div class="woodmart-warning">You need to upload an image for the parent element first.</div>',
			);
			echo json_encode($response);
			die();
		}

		$response = array(
			'status' => 'success',
			'html' => '<img class="wd-hotspot-img" src="' . esc_url( $background_image[0] ) . '" width="' . esc_attr( $background_image[1] ) . '" height="' . esc_attr( $background_image[2] ) . '">',
		);

		echo json_encode($response);
		die();
	}

	add_action( 'wp_ajax_woodmart_get_hotspot_image', 'woodmart_get_hotspot_image' );
}
