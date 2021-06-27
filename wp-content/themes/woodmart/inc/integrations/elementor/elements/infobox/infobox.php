<?php
/**
 * Banner template function.
 *
 * @package xts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'woodmart_elementor_infobox_carousel_template' ) ) {
	function woodmart_elementor_infobox_carousel_template( $settings ) {
		$default_settings = [
			'content_repeater'        => array(),

			// Carousel.
			'speed'                   => '5000',
			'slides_per_view'         => [ 'size' => 4 ],
			'slider_spacing'          => 30,
			'wrap'                    => '',
			'autoplay'                => 'no',
			'center_mode'             => 'no',
			'hide_pagination_control' => '',
			'hide_prev_next_buttons'  => '',
			'scroll_per_page'         => 'yes',
			'scroll_carousel_init'    => 'no',
			'custom_sizes'            => apply_filters( 'woodmart_info_box_shortcode_custom_sizes', false ),
		];

		$settings         = wp_parse_args( $settings, $default_settings );
		$carousel_classes = '';
		$wrapper_classes  = '';

		$settings['slides_per_view'] = $settings['slides_per_view']['size'];

		$carousel_classes .= ' ' . woodmart_owl_items_per_slide(
			$settings['slides_per_view'],
			array(),
			false,
			false,
			$settings['custom_sizes']
		);

		if ( 'yes' === $settings['scroll_carousel_init'] ) {
			woodmart_enqueue_js_library( 'waypoints' );
			$wrapper_classes .= ' scroll-init';
		}

		if ( woodmart_get_opt( 'disable_owl_mobile_devices' ) ) {
			$wrapper_classes .= ' disable-owl-mobile';
		}

		$wrapper_classes .= ' wd-carousel-spacing-' . $settings['slider_spacing'];

		woodmart_enqueue_inline_style( 'owl-carousel' );

		?>
		<div class="wd-carousel-container info-box-carousel-wrapper<?php echo esc_attr( $wrapper_classes ); ?>" <?php echo woodmart_get_owl_attributes( $settings ); ?>>
			<div class="owl-carousel info-box-carousel<?php echo esc_attr( $carousel_classes ); ?>">
				<?php foreach ( $settings['content_repeater'] as $index => $infobox ) : ?>
					<?php
					$infobox                    = $infobox + $settings;
					$infobox['wrapper_classes'] = ' elementor-repeater-item-' . $infobox['_id'];
					?>
					<?php woodmart_elementor_infobox_template( $infobox ); ?>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'woodmart_elementor_infobox_template' ) ) {
	function woodmart_elementor_infobox_template( $settings ) {
		$default_settings = [
			'link'                        => '',
			'alignment'                   => 'left',
			'image_alignment'             => 'top',
			'style'                       => '',
			'hover'                       => '',
			'woodmart_color_scheme'       => '',
			'woodmart_hover_color_scheme' => 'light',
			'svg_animation'               => '',

			'bg_hover_color'              => '',
			'bg_hover_color_gradient'     => '',
			'bg_hover_colorpicker'        => 'colorpicker',

			// Icon
			'icon_bg_color'               => '',
			'icon_bg_hover_color'         => '',
			'icon_border_color'           => '',
			'icon_border_hover_color'     => '',
			'image'                       => '',
			'icon_type'                   => 'text',
			'icon_style'                  => 'simple',
			'icon_text'                   => '',
			'icon_text_color'             => '',
			'icon_text_size'              => 'default',

			// Btn
			'btn_text'                    => '',
			'btn_position'                => 'static',
			'btn_color'                   => 'default',
			'btn_style'                   => 'default',
			'btn_shape'                   => 'rectangle',
			'btn_size'                    => 'default',

			// Title
			'title'                       => '',
			'title_size'                  => 'default',
			'title_style'                 => 'default',
			'title_color'                 => '',
			'title_font_size'             => '',
			'title_tag'                   => 'h4',

			// Subtitle
			'subtitle'                    => '',
			'subtitle_color'              => 'default',
			'subtitle_custom_color'       => '',
			'subtitle_custom_bg_color'    => '',
			'subtitle_style'              => 'default',

			// Content
			'custom_text_color'           => '',

			// Extra
			'wrapper_classes'             => '',
		];

		$settings         = wp_parse_args( $settings, $default_settings );
		$wrapper_classes  = '';
		$subtitle_classes = '';
		$title_classes    = '';
		$content_classes  = '';
		$icon_classes     = '';
		$image_output     = '';

		// Wrapper classes.
		$wrapper_classes .= ' text-' . $settings['alignment'];
		$wrapper_classes .= ' box-icon-align-' . $settings['image_alignment'];
		$wrapper_classes .= ' box-style-' . $settings['style'];
		$wrapper_classes .= ' color-scheme-' . $settings['woodmart_color_scheme'];
		$wrapper_classes .= $settings['wrapper_classes'] ? ' ' . $settings['wrapper_classes'] : '';
		if ( 'bg-hover' === $settings['style'] ) {
			$wrapper_classes .= ' color-scheme-hover-' . $settings['woodmart_hover_color_scheme'];
		}
		if ( 'yes' === $settings['svg_animation'] ) {
			woodmart_enqueue_js_library( 'vivus' );
			woodmart_enqueue_js_script( 'infobox-element' );
			$wrapper_classes .= ' with-animation';
		}
		if ( $settings['btn_text'] ) {
			$wrapper_classes .= ' with-btn';
			$wrapper_classes .= ' box-btn-' . $settings['btn_position'];
		}

		// Title classes.
		$title_classes .= ' box-title-style-' . $settings['title_style'];
		if ( woodmart_elementor_is_edit_mode() && ! strstr( $settings['wrapper_classes'], 'elementor-repeater-item' ) ) {
			$title_classes .= ' elementor-inline-editing';
		}
		$title_classes   .= ' ' . woodmart_get_new_size_classes( 'infobox', $settings['title_size'], 'title' );
		$wrapper_classes .= woodmart_get_old_classes( ' box-title-' . $settings['title_size'] );
		$wrapper_classes .= woodmart_get_old_classes( ' woodmart-info-box' );

		// Subtitle classes.
		if ( ! $settings['subtitle_custom_color'] && ! $settings['subtitle_custom_bg_color'] ) {
			$subtitle_classes .= ' subtitle-color-' . $settings['subtitle_color'];
		}
		$subtitle_classes .= ' subtitle-style-' . $settings['subtitle_style'];
		if ( woodmart_elementor_is_edit_mode() && ! strstr( $settings['wrapper_classes'], 'elementor-repeater-item' ) ) {
			$subtitle_classes .= ' elementor-inline-editing';
		}
		$subtitle_classes .= ' ' . woodmart_get_new_size_classes( 'infobox', $settings['title_size'], 'subtitle' );

		// Content classes.
		if ( woodmart_elementor_is_edit_mode() && ! strstr( $settings['wrapper_classes'], 'elementor-repeater-item' ) ) {
			$content_classes .= ' elementor-inline-editing';
		}

		// Text classes.
		if ( 'icon' === $settings['icon_type'] ) {
			$icon_classes .= ' box-with-icon';
		} else {
			$icon_classes .= ' box-with-text text-size-' . $settings['icon_text_size'];
		}
		$icon_classes .= ' box-icon-' . $settings['icon_style'];

		// Link settings.
		if ( $settings['link'] && $settings['link']['url'] && ! $settings['btn_text'] ) {
			$wrapper_classes .= ' cursor-pointer';
		}
		if ( isset( $settings['link']['is_external'] ) && 'on' === $settings['link']['is_external'] ) {
			$onclick = 'window.open(\'' . esc_url( $settings['link']['url'] ) . '\',\'_blank\')';
		} else {
			$onclick = 'window.location.href=\'' . esc_url( $settings['link']['url'] ) . '\'';
		}

		// Image settings.
		$rand              = 'svg-' . rand( 999, 9999 );
		$custom_image_size = isset( $settings['image_custom_dimension']['width'] ) && $settings['image_custom_dimension']['width'] ? $settings['image_custom_dimension'] : [
			'width'  => 128,
			'height' => 128,
		];

		if ( isset( $settings['image']['id'] ) && $settings['image']['id'] ) {
			$image_output = woodmart_get_image_html( $settings, 'image' );

			if ( woodmart_is_svg( woodmart_get_image_url( $settings['image']['id'], 'image', $settings ) ) ) {
				$image_output = '<span class="info-svg-wrapper info-icon" style="width:' . esc_attr( $custom_image_size['width'] ) . 'px; height:' . esc_attr( $custom_image_size['height'] ) . 'px;">' . woodmart_get_any_svg(
					woodmart_get_image_url(
						$settings['image']['id'],
						'image',
						$settings
					),
					$rand
				) . '</span>';
			}
		}

		woodmart_enqueue_inline_style( 'info-box' );

		?>
		<div class="info-box-wrapper">
			<div class="wd-info-box<?php echo esc_attr( $wrapper_classes ); ?>"<?php echo $settings['link']['url'] && ! woodmart_elementor_is_edit_mode() ? 'onclick="' . $onclick . '"' : ''; ?>>
				<?php if ( $image_output || $settings['icon_text'] ) : ?>
					<div class="box-icon-wrapper <?php echo esc_attr( $icon_classes ); ?>">
						<div class="info-box-icon">
							<?php if ( 'icon' === $settings['icon_type'] ) : ?>
								<?php echo $image_output; ?>
							<?php else : ?>
								<?php echo esc_attr( $settings['icon_text'] ); ?>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>

				<div class="info-box-content">
					<?php if ( $settings['subtitle'] ) : ?>
						<div class="info-box-subtitle<?php echo esc_attr( $subtitle_classes ); ?>"
							  data-elementor-setting-key="subtitle">
							<?php echo nl2br( $settings['subtitle'] ); ?>
						</div>
					<?php endif; ?>

					<?php if ( $settings['title'] ) : ?>
						<<?php echo esc_attr( $settings['title_tag'] ); ?>
						class="info-box-title title<?php echo esc_attr( $title_classes ); ?>" data-elementor-setting-key="title">
								<?php echo nl2br( $settings['title'] ); ?>
						</<?php echo esc_attr( $settings['title_tag'] ); ?>>
					<?php endif; ?>

					<div class="info-box-inner set-cont-mb-s reset-last-child<?php echo esc_attr( $content_classes ); ?>"
						 data-elementor-setting-key="content">
						<?php echo do_shortcode( wpautop( $settings['content'] ) ); ?>
					</div>
	
					<?php if ( $settings['btn_text'] ) : ?>
						<div class="info-btn-wrapper">
							<?php
							woodmart_elementor_button_template(
								array(
									'title'       => $settings['btn_text'],
									'color'       => $settings['btn_color'],
									'style'       => $settings['btn_style'],
									'size'        => $settings['btn_size'],
									'align'       => $settings['alignment'],
									'shape'       => $settings['btn_shape'],
									'text'        => $settings['btn_text'],
									'inline_edit' => false,
								) + $settings
							);
							?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}
}
