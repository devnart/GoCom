<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* ------------------------------------------------------------------------------------------------
* Countdown timer
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_shortcode_countdown_timer' )) {
	function woodmart_shortcode_countdown_timer($atts, $content) {
		if( ! function_exists( 'wpb_getImageBySize' ) ) return;
		$click = $output = $class = '';
		extract(shortcode_atts( array(
			'date' => '2020/12/12',
			'woodmart_color_scheme' => 'dark',
			'size' => 'medium',
			'align' => 'center',
			'style' => 'standard',
			'css_animation' => 'none',
			'el_class' => ''
		), $atts ));

		$class .= ' ' . $el_class;
		$class .= ' color-scheme-' . $woodmart_color_scheme;
		$class .= ' text-' . $align;
		$class .= ' timer-size-' . $size;
		$class .= ' timer-style-' . $style;
		$class .= woodmart_get_css_animation( $css_animation );
		
		$timezone = 'GMT';

		$date = str_replace( '/', '-', $date );

		if ( apply_filters( 'woodmart_wp_timezone_element', false ) ) $timezone = get_option( 'timezone_string' );

		woodmart_enqueue_js_library( 'countdown-bundle' );
		woodmart_enqueue_js_script( 'countdown-element' );
		woodmart_enqueue_inline_style( 'countdown' );
		
		ob_start(); ?>
			<div class="wd-countdown-timer<?php echo esc_attr( $class ); ?>">
				<div class="wd-timer<?php echo woodmart_get_old_classes( ' woodmart-timer' ); ?>" data-end-date="<?php echo esc_attr( $date ) ?>" data-timezone="<?php echo esc_attr( $timezone ) ?>"></div>
			</div>
		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
