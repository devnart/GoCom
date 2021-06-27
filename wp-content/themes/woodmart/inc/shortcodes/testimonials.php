<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* ------------------------------------------------------------------------------------------------
* Testimonials shortcodes
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_shortcode_testimonials' ) ) {
	function woodmart_shortcode_testimonials($atts = array(), $content = null) {
		$output = $class = $wrapper_classes = $owl_atts = $autoplay = '';

		$parsed_atts = shortcode_atts( array_merge( woodmart_get_owl_atts(), array(
			'layout' => 'slider', // grid slider
			'style' => 'standard', // standard boxed
			'align' => 'center', // left center
			'text_size' => '', 
			'columns' => 3,
			'spacing' => 30,
			'name' => '',
			'title' => '',
			'stars_rating' => 'no',
			'el_class' => ''
		) ), $atts );

		extract( $parsed_atts );

		$wrapper_classes .= ' testimonials-' . $layout;
		$wrapper_classes .= ' testimon-style-' . $style;
		$wrapper_classes .= ' testimon-align-' . $align;
		$wrapper_classes .= ' ' . woodmart_get_new_size_classes( 'testimonials', $text_size, 'text' );

		if ( $stars_rating == 'yes' ) $wrapper_classes .= ' testimon-with-rating';

		$wrapper_classes .= ' ' . $el_class;

		$carousel_id = 'carousel-' . rand( 1000, 10000 );

		if ( $layout == 'slider' ) {
			woodmart_enqueue_inline_style( 'owl-carousel' );
			$custom_sizes = apply_filters( 'woodmart_testimonials_shortcode_custom_sizes', false );

			$parsed_atts['carousel_id'] = $carousel_id;
			$parsed_atts['custom_sizes'] = $custom_sizes;

			$owl_atts = woodmart_get_owl_attributes( $parsed_atts );
			$class .= ' owl-carousel ' . woodmart_owl_items_per_slide( $slides_per_view, array(), false, false, $custom_sizes );
			
			$wrapper_classes .= ' wd-carousel-container';
			$wrapper_classes .= ' wd-carousel-spacing-' . $spacing;

			if ( woodmart_get_opt( 'disable_owl_mobile_devices' ) ) {
				$wrapper_classes .= ' disable-owl-mobile';
			}
		} else {
			$class .= ' row';
			$class .= ' wd-spacing-' . $spacing;
			$class .= ' wd-columns-' . $columns;
		}

		ob_start(); ?>
			<?php if ( $title != '' ): ?>
				<h2 class="title slider-title"><?php echo esc_html( $title ); ?></h2>
			<?php endif ?>

			<div id="<?php echo esc_attr( $carousel_id ); ?>" class="testimonials testimonials-wrapper<?php echo esc_attr( $wrapper_classes ); ?>" <?php echo 'slider' == $layout ? $owl_atts : ''; ?>>
				<div class="<?php echo esc_attr( $class ); ?>" >
					<?php echo do_shortcode( $content ); ?>
				</div>
			</div>
		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}	
}

if( ! function_exists( 'woodmart_shortcode_testimonial' ) ) {
	function woodmart_shortcode_testimonial($atts, $content) {
		if( ! function_exists( 'wpb_getImageBySize' ) ) return;
		$output = $class = '';
		extract(shortcode_atts( array(
			'image' => '',
			'img_size' => '100x100',
			'name' => '',
			'title' => '',
			'el_class' => ''
		), $atts ));

		$img_id = preg_replace( '/[^\d]/', '', $image );

		$class .= ' ' . $el_class;

		woodmart_enqueue_inline_style( 'testimonial' );

		ob_start(); ?>

			<div class="testimonial column<?php echo esc_attr( $class ); ?>" >
				<div class="testimonial-inner">
					<?php if ( $img_id ): ?>
						<div class="testimonial-avatar">
							<?php echo wpb_getImageBySize( array( 'attach_id' => $img_id, 'thumb_size' => $img_size, 'class' => 'testimonial-avatar-image' ) )['thumbnail']; ?>
						</div>
					<?php endif ?>
					<div class="testimonial-content">
						<div class="testimonial-rating">
							<span class="star-rating">
								<span style="width:100%"></span>
							</span>
						</div>
						<?php echo do_shortcode( $content ); ?>
						<footer>
							<?php echo esc_html( $name ); ?>
							<?php if ( $title ): ?>
								<span><?php echo esc_html( $title ); ?></span>
							<?php endif ?>
						</footer>
					</div>
				</div>
			</div>

		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
