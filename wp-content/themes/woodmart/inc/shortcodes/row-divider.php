<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );}

/**
* ------------------------------------------------------------------------------------------------
* Section divider shortcode
* ------------------------------------------------------------------------------------------------
*/

if ( ! function_exists( 'woodmart_row_divider' ) ) {
	function woodmart_row_divider( $atts ) {
		extract(
			shortcode_atts(
				array(
					'position'        => 'top',
					'color'           => '#e1e1e1',
					'style'           => 'waves-small',
					'content_overlap' => '',
					'custom_height'   => '',
					'el_class'        => '',
					'woodmart_css_id' => '',
				),
				$atts
			)
		);

		if ( ! $woodmart_css_id ) {
			$woodmart_css_id = uniqid();
		}
		$divider_id = 'wd-' . $woodmart_css_id;

		$classes  = $divider_id;
		$classes .= ' dvr-position-' . $position;
		$classes .= ' dvr-style-' . $style;
		$classes .= woodmart_get_old_classes( ' woodmart-row-divider' );

		( $content_overlap == 'enable' ) ? $classes .= ' dvr-overlap-enable' : false;
		( $el_class != '' ) ? $classes              .= ' ' . $el_class : false;
		woodmart_enqueue_inline_style( 'dividers' );
		ob_start();
		?>
			<div id="<?php echo esc_attr( $divider_id ); ?>" class="wd-row-divider <?php echo esc_attr( $classes ); ?>">
				<?php echo woodmart_get_svg_content( $style . '-' . $position ); ?>
				<?php
				if ( $color || $custom_height ) {
					$css = '.' . esc_attr( $divider_id ) . ' svg {';
					if ( $color && ! woodmart_is_css_encode( $color ) ) {
						$css .= 'fill: ' . esc_attr( $color ) . ';';
					}

					if ( $custom_height ) {
						$css .= 'height: ' . esc_attr( $custom_height  ) . ';';
					}
					$css .= '}';

					wp_add_inline_style( 'woodmart-inline-css', $css );
				}
				?>
			</div>
		<?php

		return ob_get_clean();
	}
}
