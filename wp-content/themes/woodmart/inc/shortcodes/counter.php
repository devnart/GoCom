<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );}

/**
* ------------------------------------------------------------------------------------------------
* Counter shortcode
* ------------------------------------------------------------------------------------------------
*/
if ( ! function_exists( 'woodmart_shortcode_animated_counter' ) ) {
	function woodmart_shortcode_animated_counter( $atts ) {
		$output = $label = $el_class = '';
		extract(
			shortcode_atts(
				array(
					'label'           => '',
					'value'           => 100,
					'time'            => 1000,
					'color_scheme'    => '',
					'color'           => '',
					'size'            => 'default',
					'font_weight'     => 600,
					'align'           => 'center',
					'css'             => '',
					'css_animation'   => 'none',
					'el_class'        => '',
					'woodmart_css_id' => '',
				),
				$atts
			)
		);

		if ( ! $woodmart_css_id ) {
			$woodmart_css_id = uniqid();
		}
		$counter_id = 'wd-' . $woodmart_css_id;

		$el_class .= ' counter-' . $size;
		$el_class .= ' text-' . $align;
		$el_class .= ' color-scheme-' . $color_scheme;
		$el_class .= woodmart_get_css_animation( $css_animation );

		if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$el_class .= ' ' . vc_shortcode_custom_css_class( $css );
		}

		woodmart_enqueue_js_library( 'waypoints' );
		woodmart_enqueue_js_script( 'counter-element' );
		woodmart_enqueue_inline_style( 'counter' );

		ob_start();
		?>
			<div class="woodmart-counter wd-wpb <?php echo esc_attr( $el_class ); ?>" id="<?php echo esc_attr( $counter_id ); ?>">
				<span class="counter-value wd-font-weight-<?php echo esc_attr( $font_weight ); ?>" data-state="new" data-final="<?php echo esc_attr( $value ); ?>"><?php echo esc_attr( $value ); ?></span>
				<?php if ( $label != '' ) : ?>
					<span class="counter-label"><?php echo esc_html( $label ); ?></span>
				<?php endif ?>
				<?php
				if ( $color_scheme == 'custom' && $color && ! woodmart_is_css_encode( $color ) ) {
					$css = '.woodmart-counter#' . esc_attr( $counter_id ) . '{';
					$css .= 'color: ' . esc_attr( $color ) . ';';
					$css .= '}';
					wp_add_inline_style( 'woodmart-inline-css', $css );
				}
				?>
			</div>
			
		<?php
		$output .= ob_get_clean();

		return $output;
	}
}
