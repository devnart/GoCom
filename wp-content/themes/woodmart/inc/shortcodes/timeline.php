<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );}

/**
* ------------------------------------------------------------------------------------------------
* Timeline shortcode
* ------------------------------------------------------------------------------------------------
*/

if ( ! function_exists( 'woodmart_timeline_shortcode' ) ) {
	function woodmart_timeline_shortcode( $atts, $content ) {
		extract(
			shortcode_atts(
				array(
					'line_color'      => '#e1e1e1',
					'dots_color'      => '#1e73be',
					'line_style'      => 'default',
					'item_style'      => 'default',
					'el_class'        => '',
					'woodmart_css_id' => '',
				),
				$atts
			)
		);

		if ( ! $woodmart_css_id ) {
			$woodmart_css_id = uniqid();
		}
		$timeline_id = 'wd-' . $woodmart_css_id;

		$classes  = 'wd-timeline-wrapper';
		$classes .= ' wd-item-' . $item_style;
		$classes .= ' wd-line-' . $line_style;
		$classes .= $el_class ? ' ' . $el_class : '';

		woodmart_enqueue_inline_style( 'timeline' );

		ob_start();
		?>
		<div id="<?php echo esc_attr( $timeline_id ); ?>" class="<?php echo esc_attr( $classes ); ?>">
			<div class="woodmart-timeline-line">
				<span class="line-dot dot-start"></span>
				<span class="line-dot dot-end"></span>
			</div>
			<div class="wd-timeline">
				<?php echo do_shortcode( $content ); ?>
			</div>
			<?php
			if ( $line_color || $dots_color ) {
				$css = '';

				if ( $dots_color && ! woodmart_is_css_encode( $dots_color ) ) {
					$css .= '#' . esc_attr( $timeline_id ) . ' .woodmart-timeline-dot {';
					$css .= 'background-color: ' . esc_attr( $dots_color ) . ';';
					$css .= '}';
				}

				if ( $line_color && ! woodmart_is_css_encode( $line_color ) ) {
					$css .= '#' . esc_attr( $timeline_id ) . ' .dot-start,';
					$css .= '#' . esc_attr( $timeline_id ) . ' .dot-end {';
					$css .= 'background-color: ' . esc_attr( $line_color ) . ';';
					$css .= '}';

					$css .= '#' . esc_attr( $timeline_id ) . ' .woodmart-timeline-line {';
					$css .= 'border-color: ' . esc_attr( $line_color ) . ';';
					$css .= '}';
				}

				wp_add_inline_style( 'woodmart-inline-css', $css );
			}
			?>
		</div>
		<?php

		return ob_get_clean();
	}
}

/**
* ------------------------------------------------------------------------------------------------
* Timeline item shortcode
* ------------------------------------------------------------------------------------------------
*/

if ( ! function_exists( 'woodmart_timeline_item_shortcode' ) ) {
	function woodmart_timeline_item_shortcode( $atts, $content ) {
		extract(
			shortcode_atts(
				array(
					'title_primary'      => '',
					'image_primary'      => '',
					'img_size_primary'   => 'full',
					'title_secondary'    => '',
					'content_secondary'  => '',
					'image_secondary'    => '',
					'img_size_secondary' => 'full',
					'position'           => 'left',
					'color_bg'           => '',
					'el_class'           => '',
					'woodmart_css_id'    => '',
				),
				$atts
			)
		);

		$classes  = 'wd-timeline-item';
		$classes .= ' wd-item-position-' . $position;

		if ( ! $woodmart_css_id ) {
			$woodmart_css_id = uniqid();
		}
		$id = 'wd-' . $woodmart_css_id;

		( $el_class != '' ) ? $classes .= ' ' . $el_class : false;
		ob_start();
		?>
		<div id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $classes ); ?>">

			<div class="woodmart-timeline-dot"></div>

			<div class="timeline-col timeline-col-primary">
				<span class="timeline-arrow"></span>
				<?php if ( $image_primary ) : ?>
					<div class="wd-timeline-image" >
						<?php if ( function_exists( 'wpb_getImageBySize' ) ) : ?>
							<?php
							echo wpb_getImageBySize(
								array(
									'attach_id'  => $image_primary,
									'thumb_size' => $img_size_primary,
								)
							)['thumbnail'];
							?>
						<?php endif; ?>
					</div>
				<?php endif ?>
				<h4 class="wd-timeline-title"><?php echo esc_attr( $title_primary ); ?></h4>
				<div class="wd-timeline-content set-cont-mb-s reset-last-child"><?php echo do_shortcode( $content ); ?></div>
			</div>

			<div class="timeline-col timeline-col-secondary">	
				<span class="timeline-arrow"></span>
				<?php if ( $image_secondary ) : ?>
					<div class="wd-timeline-image" >
						<?php if ( function_exists( 'wpb_getImageBySize' ) ) : ?>
							<?php
							echo wpb_getImageBySize(
								array(
									'attach_id'  => $image_secondary,
									'thumb_size' => $img_size_secondary,
								)
							)['thumbnail'];
							?>
						<?php endif; ?>
					</div>
				<?php endif ?>
				<h4 class="wd-timeline-title"><?php echo esc_attr( $title_secondary ); ?></h4>
				<div class="wd-timeline-content set-cont-mb-s reset-last-child"><?php echo do_shortcode( $content_secondary ); ?></div>
			</div>
			<?php
			if ( $color_bg && ! woodmart_is_css_encode( $color_bg ) ) {
				$css = '#' . esc_attr( $id ) . ',';
				$css .= '#' . esc_attr( $id ) . ' .timeline-col-primary,';
				$css .= '#' . esc_attr( $id ) . ' .timeline-col-secondary {';
				$css .= 'background-color: ' . esc_attr( $color_bg  ) . ';';
				$css .= '}';

				$css .= '#' . esc_attr( $id ) . ' .timeline-arrow {';
				$css .= 'color: ' . esc_attr( $color_bg ) . ';';
				$css .= '}';

				wp_add_inline_style( 'woodmart-inline-css', $css );
			}
			?>
		</div>
		<?php

		return ob_get_clean();
	}
}

/**
* ------------------------------------------------------------------------------------------------
* Timeline breakpoint shortcode
* ------------------------------------------------------------------------------------------------
*/

if ( ! function_exists( 'woodmart_timeline_breakpoint_shortcode' ) ) {
	function woodmart_timeline_breakpoint_shortcode( $atts, $content ) {
		extract(
			shortcode_atts(
				array(
					'title'           => '',
					'color_bg'        => '',
					'el_class'        => '',
					'woodmart_css_id' => '',
				),
				$atts
			)
		);

		$classes = 'wd-timeline-breakpoint';

		if ( ! $woodmart_css_id ) {
			$woodmart_css_id = uniqid();
		}
		$id = 'wd-' . $woodmart_css_id;

		( $el_class != '' ) ? $classes .= ' ' . $el_class : false;
		ob_start();
		?>
		<div id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $classes ); ?>">
			<span class="woodmart-timeline-breakpoint-title"><?php echo esc_attr( $title ); ?></span>
			<?php
			if ( $color_bg && ! woodmart_is_css_encode( $color_bg ) ) {
				$css = '#' . esc_attr( $id ) . ' .woodmart-timeline-breakpoint-title {';
				$css .= 'background-color: ' . esc_attr( $color_bg ) . ';';
				$css .= '}';

				wp_add_inline_style( 'woodmart-inline-css', $css );
			}
			?>
		</div>
		<?php

		return ob_get_clean();
	}
}
