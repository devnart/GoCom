<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );}

/**
* ------------------------------------------------------------------------------------------------
* Woodmart responsive text block shortcode
* ------------------------------------------------------------------------------------------------
*/

if ( ! function_exists( 'woodmart_shortcode_responsive_text_block' ) ) {
	function woodmart_shortcode_responsive_text_block( $atts, $content ) {
		extract(
			shortcode_atts(
				array(
					'text'              => 'Title',
					'font'              => 'primary',
					'font_weight'       => '',
					'content_width'     => '100',
					'color_scheme'      => '',
					'color'             => '',
					'size'              => 'default',
					'align'             => 'center',
					'text_font_size'    => '',
					'inline'            => 'no',

					// Old size
					'desktop_text_size' => '',
					'tablet_text_size'  => '',
					'mobile_text_size'  => '',

					'woodmart_css_id'   => '',
					'css_animation'     => 'none',
					'el_class'          => '',
					'css'               => '',
				),
				$atts
			)
		);

		$text_class = $text_wrapper_class = '';

		if ( ! $woodmart_css_id ) {
			$woodmart_css_id = uniqid();
		}

		$text_id = 'wd-' . $woodmart_css_id;

		$text_wrapper_class .= ' color-scheme-' . $color_scheme;
		$text_wrapper_class .= ' wd-width-' . $content_width;
		$text_wrapper_class .= ' text-' . $align;
		$text_wrapper_class .= $inline == 'yes' ? ' inline-element' : '';
		$text_wrapper_class .= woodmart_get_css_animation( $css_animation );

		$text_class .= ' font-' . $font;
		$text_class .= ' wd-font-weight-' . $font_weight;
		$text_class .= ' ' . woodmart_get_new_size_classes( 'text', $size, 'title' );

		if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$text_wrapper_class .= ' ' . vc_shortcode_custom_css_class( $css );
		}

		if ( $el_class != '' ) {
			$text_wrapper_class .= ' ' . $el_class;
		}
		woodmart_enqueue_inline_style( 'responsive-text' );
		ob_start();
		?>	
			<div id="<?php echo esc_attr( $text_id ); ?>" class="wd-text-block-wrapper wd-wpb<?php echo esc_attr( $text_wrapper_class ); ?>">
				<div class="woodmart-title-container woodmart-text-block reset-last-child<?php echo esc_attr( $text_class ); ?>">
					<?php echo do_shortcode( $content ); ?>
				</div>
				<?php
				if ( ( $size == 'custom' && ! $text_font_size ) || ( $color_scheme == 'custom' && ! woodmart_is_css_encode( $color ) ) ) {
					$css = '';

					if ( $desktop_text_size || $color ) {
						$css .= '#' . esc_attr( $text_id ) . ' .woodmart-text-block  {';
						if ( $desktop_text_size ) {
							$css .= 'font-size: ' . esc_attr( $desktop_text_size ) . 'px;';
							$css .= 'line-height: ' . esc_attr( (int) $desktop_text_size + 10 ) . 'px;';
						}

						if ( $color ) {
							$css .= 'color: ' . esc_attr( $color ) . ';';
						}
						$css .= '}';
					}

					if ( $tablet_text_size ) {
						$css .= '@media (max-width: 1024px) {';
						$css .= woodmart_responsive_text_size_css( $text_id, 'woodmart-text-block', $tablet_text_size, 'return' );
						$css .= '}';
					}

					if ( $mobile_text_size ) {
						$css .= '@media (max-width: 767px) {';
						$css .= woodmart_responsive_text_size_css( $text_id, 'woodmart-text-block', $mobile_text_size, 'return' );
						$css .= '}';
					}

					wp_add_inline_style( 'woodmart-inline-css', $css );
				}
				?>
			</div>
		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;

	}
}
