<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );}

/**
 * ------------------------------------------------------------------------------------------------
 * Section title shortcode
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_shortcode_title' ) ) {
	function woodmart_shortcode_title( $atts ) {
		extract(
			shortcode_atts(
				array(
					'link'                    => '',
					'align'                   => 'center',
					'tag'                     => 'h4',
					'image'                   => '',
					'img_size'                => '200x50',
					'title_width'             => '100',

					'woodmart_css_id'         => '',
					'css_animation'           => 'none',
					'el_class'                => '',
					'css'                     => '',

					// Title
					'title'                   => '',
					'color'                   => 'default',
					'title_custom_color'      => '',
					'woodmart_color_gradient' => '',
					'style'                   => 'default',
					'size'                    => 'default',
					'font_weight'             => '',
					'title_font_size'         => '',

					// Old size
					'desktop_text_size'       => '',
					'tablet_text_size'        => '',
					'mobile_text_size'        => '',

					// Subtitle
					'subtitle'                => '',
					'subtitle_font'           => 'default',
					'subtitle_style'          => 'default',
					'subtitle_font_weight'    => '',

					// Text
					'after_title'             => '',

				),
				$atts
			)
		);

		$output = $attrs = '';

		if ( ! $woodmart_css_id ) {
			$woodmart_css_id = uniqid();
		}
		$title_id = 'wd-' . $woodmart_css_id;

		$title_class = $subtitle_class = $title_container_class = $after_title_class = '';

		$title_class .= ' wd-title-color-' . $color;
		$title_class .= ' wd-title-style-' . $style;
		$title_class .= ' wd-width-' . $title_width;
		$title_class .= ' text-' . $align;
		$title_class .= woodmart_get_css_animation( $css_animation );
		$title_class .= ( $el_class ) ? ' ' . $el_class : '';

		if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$title_class .= ' ' . vc_shortcode_custom_css_class( $css );
		}

		if ( ! $title ) {
			$title_class .= ' wd-title-empty';
		}

		$subtitle_class .= ' subtitle-color-' . $color;
		$subtitle_class .= ' font-' . $subtitle_font;
		$subtitle_class .= ' subtitle-style-' . $subtitle_style;
		$subtitle_class .= ' wd-font-weight-' . $subtitle_font_weight;
		$subtitle_class .= ' ' . woodmart_get_new_size_classes( 'title', $size, 'subtitle' );

		$title_container_class .= ' wd-font-weight-' . $font_weight;
		$title_container_class .= ' ' . woodmart_get_new_size_classes( 'title', $size, 'title' );

		$after_title_class .= ' ' . woodmart_get_new_size_classes( 'title', $size, 'after_title' );

		$gradient_style = ( $color == 'gradient' ) ? 'style="' . woodmart_get_gradient_css( $woodmart_color_gradient ) . ';"' : '';

		woodmart_enqueue_inline_style( 'section-title' );

		ob_start();

		?>
		
		<div id="<?php echo esc_attr( $title_id ); ?>" class="title-wrapper wd-wpb set-mb-s reset-last-child <?php echo esc_attr( $title_class ); ?>">
			<?php if ( $subtitle != '' ) : ?>
				<div class="title-subtitle <?php echo esc_attr( $subtitle_class ); ?>"><?php echo wp_kses( $subtitle, woodmart_get_allowed_html() ); ?></div>
			<?php endif; ?>

			<div class="liner-continer">
				<?php echo '<' . $tag . ' class="woodmart-title-container title ' . $title_container_class . '" ' . $gradient_style . '>' . $title . '</' . $tag . '>'; ?>

				<?php if ( $image ) : ?>
					<?php echo woodmart_display_icon( $image, $img_size, 128 ); ?>
				<?php endif; ?>
			</div>
			
			<?php if ( $after_title != '' ) : ?>
				<div class="title-after_title  set-cont-mb-s reset-last-child <?php echo esc_attr( $after_title_class ); ?>"><?php echo wp_kses( $after_title, woodmart_get_allowed_html() ); ?></div>
			<?php endif; ?>

			<?php
			if ( $size == 'custom' && ! $title_font_size  ) {
				$css = '';

				if ( $desktop_text_size ) {
					$css .= woodmart_responsive_text_size_css( $title_id, 'woodmart-title-container', $desktop_text_size, 'return' );
				}

				if ( $tablet_text_size ) {
					$css .= '@media (max-width: 1024px) {';
					$css .= woodmart_responsive_text_size_css( $title_id, 'woodmart-title-container', $tablet_text_size, 'return' );
					$css .= '}';
				}

				if ( $mobile_text_size  ) {
					$css .= '@media (max-width: 767px) {';
					$css .= woodmart_responsive_text_size_css( $title_id, 'woodmart-title-container', $mobile_text_size, 'return' );
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
