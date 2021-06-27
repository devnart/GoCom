<?php
/**
 * Banner template function.
 *
 * @package xts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'woodmart_elementor_banner_template' ) ) {
	function woodmart_elementor_banner_carousel_template( $settings ) {
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
			'custom_sizes'            => apply_filters( 'woodmart_promo_banner_shortcode_custom_sizes', false ),
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
		<div class="wd-carousel-container banners-carousel-wrapper<?php echo esc_attr( $wrapper_classes ); ?>" <?php echo woodmart_get_owl_attributes( $settings ); ?>>
			<div class="owl-carousel banners-carousel<?php echo esc_attr( $carousel_classes ); ?>">
				<?php foreach ( $settings['content_repeater'] as $index => $banner ) : ?>
					<?php
					$banner                    = $banner + $settings;
					$banner['wrapper_classes'] = ' elementor-repeater-item-' . $banner['_id'];
					?>
					<?php woodmart_elementor_banner_template( $banner ); ?>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'woodmart_elementor_banner_template' ) ) {
	function woodmart_elementor_banner_template( $settings ) {
		$default_settings = [
			'image'                    => '',
			'link'                     => '',
			'text_alignment'           => 'left',
			'vertical_alignment'       => 'top',
			'horizontal_alignment'     => 'left',
			'style'                    => '',
			'hover'                    => 'zoom',
			'increase_spaces'          => '',
			'woodmart_color_scheme'    => 'light',

			// Button
			'btn_text'                 => '',
			'btn_position'             => 'hover',
			'btn_color'                => 'default',
			'btn_style'                => 'default',
			'btn_shape'                => 'rectangle',
			'btn_size'                 => 'default',
			'hide_btn_tablet'          => 'no',
			'hide_btn_mobile'          => 'no',
			'title_decoration_style'   => 'default',

			// Title
			'custom_title_color'       => '',
			'title'                    => '',
			'title_tag'                => 'h4',
			'title_size'               => 'default',

			// Subtitle
			'subtitle'                 => '',
			'subtitle_color'           => 'default',
			'custom_subtitle_color'    => '',
			'custom_subtitle_bg_color' => '',
			'subtitle_style'           => 'default',

			// Text
			'custom_text_color'        => '',
			'content_text_size'        => 'default',

			// Extra.
			'wrapper_classes'          => '',
		];

		$settings = wp_parse_args( $settings, $default_settings );

		if ( 'parallax' === $settings['hover'] ) {
			woodmart_enqueue_js_library( 'panr-parallax-bundle' );
			woodmart_enqueue_js_script( 'banner-element' );
		}

		// Classes.
		$banner_classes          = '';
		$subtitle_classes        = '';
		$title_classes           = '';
		$content_classes         = '';
		$inner_classes           = '';
		$btn_wrapper_classes     = '';
		$image_url               = '';
		$wrapper_content_classes = '';
		$title_wrapper_classes   = '';

		// Banner classes.
		$banner_classes .= ' banner-' . $settings['style'];
		$banner_classes .= ' banner-hover-' . $settings['hover'];
		$banner_classes .= ' color-scheme-' . $settings['woodmart_color_scheme'];
		$banner_classes .= ' banner-btn-size-' . $settings['btn_size'];
		$banner_classes .= ' banner-btn-style-' . $settings['btn_style'];
		$banner_classes .= ' wd-display-' . $settings['image_type'];
		if ( 'yes' === $settings['increase_spaces'] ) {
			$banner_classes .= ' banner-increased-padding';
		}
		if ( 'content-background' === $settings['style'] ) {
			$settings['btn_position'] = 'static';
		}
		if ( $settings['btn_text'] ) {
			$banner_classes .= ' with-btn';
			$banner_classes .= ' banner-btn-position-' . $settings['btn_position'];
		}

		// Subtitle classes.
		if ( woodmart_elementor_is_edit_mode() && ! strstr( $settings['wrapper_classes'], 'elementor-repeater-item' ) ) {
			$subtitle_classes .= ' elementor-inline-editing';
		}
		$subtitle_classes .= ' subtitle-style-' . $settings['subtitle_style'];
		if ( ! $settings['custom_subtitle_color'] && ! $settings['custom_subtitle_bg_color'] ) {
			$subtitle_classes .= ' subtitle-color-' . $settings['subtitle_color'];
		}
		$subtitle_classes .= ' ' . woodmart_get_new_size_classes( 'banner', $settings['title_size'], 'subtitle' );

		// Content classes.
		$content_classes .= ' text-' . $settings['text_alignment'];

		// Wrapper content classes.
		$wrapper_content_classes .= ' wd-items-' . $settings['vertical_alignment'];
		$wrapper_content_classes .= ' wd-justify-' . $settings['horizontal_alignment'];
		$banner_classes          .= woodmart_get_old_classes( ' banner-vr-align-' . $settings['vertical_alignment'] );
		$banner_classes          .= woodmart_get_old_classes( ' banner-hr-align-' . $settings['horizontal_alignment'] );

		// Title classes.
		if ( woodmart_elementor_is_edit_mode() && ! strstr( $settings['wrapper_classes'], 'elementor-repeater-item' ) ) {
			$title_classes .= ' elementor-inline-editing';
		}
		if ( 'default' !== $settings['title_decoration_style'] ) {
			$title_classes .= ' wd-underline-' . $settings['title_decoration_style'];
		}
		$title_classes         .= ' ' . woodmart_get_new_size_classes( 'banner', $settings['title_size'], 'title' );
		$title_wrapper_classes .= woodmart_get_old_classes( ' banner-title-' . $settings['title_size'] );

		// Content classes.
		if ( woodmart_elementor_is_edit_mode() && ! strstr( $settings['wrapper_classes'], 'elementor-repeater-item' ) ) {
			$inner_classes .= ' elementor-inline-editing';
		}
		$inner_classes .= ' ' . woodmart_get_new_size_classes( 'banner', $settings['content_text_size'], 'content' );

		// Button classes.
		if ( 'yes' === $settings['hide_btn_tablet'] ) {
			$btn_wrapper_classes .= ' banner-hide-btn-tablet';
		}
		if ( 'yes' === $settings['hide_btn_mobile'] ) {
			$btn_wrapper_classes .= ' banner-hide-btn-mobile';
		}

		// Link settings.
		if ( $settings['link'] && $settings['link']['url'] ) {
			$banner_classes .= ' cursor-pointer';
		}
		if ( isset( $settings['link']['is_external'] ) && 'on' === $settings['link']['is_external'] ) {
			$onclick = 'window.open(\'' . esc_url( $settings['link']['url'] ) . '\',\'_blank\')';
		} else {
			$onclick = 'window.location.href=\'' . esc_url( $settings['link']['url'] ) . '\'';
		}

		// Image settings.
		if ( $settings['image']['id'] ) {
			$image_url = woodmart_get_image_url( $settings['image']['id'], 'image', $settings );
		} elseif ( $settings['image']['url'] ) {
			$image_url = $settings['image']['url'];
		}

		woodmart_enqueue_inline_style( 'banner' );

		?>
		<div class="promo-banner-wrapper<?php echo esc_attr( $settings['wrapper_classes'] ); ?>">
			<div class="promo-banner<?php echo esc_attr( $banner_classes ); ?>" <?php echo $settings['link']['url'] && ! woodmart_elementor_is_edit_mode() ? 'onclick="' . $onclick . '"' : ''; ?>>
				<div class="main-wrapp-img">
					<?php if ( 'background' === $settings['image_type'] && 'parallax' !== $settings['hover'] ) : ?>
						<div class="banner-image" style="background-image: url(<?php echo esc_url( $image_url ); ?>);"></div>
					<?php else : ?>
						<div class="banner-image">
							<?php echo apply_filters( 'woodmart_image', '<img src="' . esc_url( $image_url ) . '" class="promo-banner-image" alt="promo-banner-image">' ); ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="wrapper-content-banner wd-fill<?php echo esc_attr( $wrapper_content_classes ); ?>">
					<div class="content-banner<?php echo esc_attr( $content_classes ); ?>">
						<div class="banner-title-wrap<?php echo esc_attr( $title_wrapper_classes ); ?>">
						<?php if ( $settings['subtitle'] ) : ?>
							<div class="banner-subtitle<?php echo esc_attr( $subtitle_classes ); ?>" data-elementor-setting-key="subtitle">
								<?php echo nl2br( $settings['subtitle'] ); ?>
							</div>
						<?php endif; ?>

						<?php if ( $settings['title'] ) : ?>
							<<?php echo esc_attr( $settings['title_tag'] ); ?> class="banner-title<?php echo esc_attr( $title_classes ); ?>" data-elementor-setting-key="title">
								<?php echo nl2br( $settings['title'] ); ?>
							</<?php echo esc_attr( $settings['title_tag'] ); ?>>
						<?php endif; ?>
					</div>

					<?php if ( $settings['content'] ) : ?>
						<div class="banner-inner set-cont-mb-s reset-last-child<?php echo esc_attr( $inner_classes ); ?>" data-elementor-setting-key="content">
							<?php echo do_shortcode( wpautop( $settings['content'] ) ); ?>
						</div>
					<?php endif ?>

					<?php if ( $settings['btn_text'] ) : ?>
						<div class="banner-btn-wrapper<?php echo esc_attr( $btn_wrapper_classes ); ?>">
							<?php
							unset( $settings['inline_editing_key'] );
							unset( $settings['link'] );
							woodmart_elementor_button_template(
								array(
									'title'       => $settings['btn_text'],
									'color'       => $settings['btn_color'],
									'style'       => $settings['btn_style'],
									'size'        => $settings['btn_size'],
									'align'       => $settings['text_alignment'],
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
		</div>
		<?php
	}
}
