<?php
/**
 * Button template function.
 *
 * @package xts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'woodmart_elementor_button_template' ) ) {
	function woodmart_elementor_button_template( $settings ) {
		$default_settings = [
			'text'                        => 'Read more',
			'link'                        => '',
			'button_smooth_scroll'        => 'no',
			'button_smooth_scroll_time'   => '100',
			'button_smooth_scroll_offset' => '100',

			// General.
			'color'                       => 'default',
			'style'                       => 'default',
			'shape'                       => 'rectangle',
			'size'                        => 'default',

			// Layout.
			'full_width'                  => 'no',
			'align'                       => 'center',

			// Colors.
			'color_scheme'                => 'light',
			'color_scheme_hover'          => 'light',
			'bg_color'                    => '',
			'bg_color_hover'              => '',
			'custom_classes'              => '',
			'inline_edit'                 => true,
		];

		$settings = wp_parse_args( $settings, $default_settings );

		// Classes.
		$wrapper_attrs      = '';
		$wrapper_classes    = '';
		$link_classes       = '';
		$text_classes       = '';
		$inline_editing_key = '';

		$wrapper_classes .= 'wd-button-wrapper';
		$wrapper_classes .= woodmart_get_old_classes( ' woodmart-button-wrapper' );
		$wrapper_classes .= ' text-' . $settings['align'];

		$link_classes .= 'btn';
		$link_classes .= ' btn-style-' . $settings['style'];
		$link_classes .= ' btn-shape-' . $settings['shape'];
		$link_classes .= ' btn-size-' . $settings['size'];
		$link_classes .= $settings['custom_classes'] ? ' ' . $settings['custom_classes'] : '';

		// Link settings.
		$link_attrs = woodmart_get_link_attrs( $settings['link'] );

		// Wrapper.
		if ( 'yes' === $settings['button_smooth_scroll'] ) {
			woodmart_enqueue_js_script( 'button-element' );
			$wrapper_classes .= ' wd-smooth-scroll';
			$wrapper_attrs   .= ' data-smooth-time="' . $settings['button_smooth_scroll_time'] . '"';
			$wrapper_attrs   .= ' data-smooth-offset="' . $settings['button_smooth_scroll_offset'] . '"';
		}

		// Link classes.
		if ( $settings['bg_color'] || $settings['bg_color_hover'] ) {
			$link_classes .= ' btn-scheme-' . $settings['color_scheme'];
			$link_classes .= ' btn-scheme-hover-' . $settings['color_scheme_hover'];
		} else {
			$link_classes .= ' btn-color-' . $settings['color'];

		}
		if ( 'yes' === $settings['full_width'] ) {
			$link_classes .= ' btn-full-width';
		}

		// Icon settings.
		$icon_output = '';
		if ( $settings['icon'] ) {
			$link_classes .= ' btn-icon-pos-' . $settings['icon_position'];
			$icon_output   = woodmart_elementor_get_render_icon(
				$settings['icon'],
				[
					'class' => 'wd-icon',
				],
				'span'
			);
		}

		// Text classes.
		if ( woodmart_elementor_is_edit_mode() && $settings['inline_edit'] ) {
			$text_classes .= ' elementor-inline-editing';
		}
		if ( isset( $settings['inline_editing_key'] ) ) {
			$inline_editing_key = $settings['inline_editing_key'];
		}

		?>
		<div class="<?php echo esc_attr( $wrapper_classes ); ?>" <?php echo $wrapper_attrs; ?>>
			<a class="<?php echo esc_attr( $link_classes ); ?>" <?php echo $link_attrs; ?>>
				<span class="wd-btn-text<?php echo esc_attr( $text_classes ); ?>" data-elementor-setting-key="<?php echo esc_attr( $inline_editing_key ); ?>text">
					<?php echo esc_html( $settings['text'] ); ?>
				</span>

				<?php if ( $icon_output ) : ?>
					<span class="wd-btn-icon">
						<?php echo $icon_output; ?>
					</span>
				<?php endif; ?>
			</a>
		</div>
		<?php
	}
}
