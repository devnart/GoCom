<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );}

/**
* ------------------------------------------------------------------------------------------------
* Promo banner - image with text and hover effect
* ------------------------------------------------------------------------------------------------
*/

if ( ! function_exists( 'woodmart_shortcode_promo_banner' ) ) {
	function woodmart_shortcode_promo_banner( $atts, $content ) {
		$click = $output = $class = $subtitle_class = $title_class = $title_wrap_class = $content_banner = $content_wrap_classes = $inner_class = $btn_wrapper_classes = $banner_image_classes = '';
		extract(
			shortcode_atts(
				array(
					'image'                      => '',
					'img_size'                   => '800x600',
					'image_type'                 => 'image',
					'image_bg_position'          => 'center',
					'height'                     => 300,
					'link'                       => '',
					'text_alignment'             => 'left',
					'vertical_alignment'         => 'top',
					'horizontal_alignment'       => 'left',
					'style'                      => '',
					'hover'                      => 'zoom',
					'increase_spaces'            => '',
					'woodmart_color_scheme'      => 'light',
					'content_width'              => '100',

					// Button
					'btn_text'                   => '',
					'btn_position'               => 'hover',
					'btn_color'                  => 'default',
					'btn_style'                  => 'default',
					'btn_shape'                  => 'rectangle',
					'btn_size'                   => 'default',
					'hide_btn_tablet'            => 'no',
					'hide_btn_mobile'            => 'no',

					// Title
					'custom_title_color'         => '',
					'title'                      => '',
					'title_tag'                  => 'h4',
					'title_size'                 => 'default',
					'font_weight'                => '',
					'title_font'                 => '',

					// Subtitle
					'subtitle'                   => '',
					'subtitle_color'             => 'default',
					'custom_subtitle_color'      => '',
					'custom_subtitle_bg_color'   => '',
					'subtitle_style'             => 'default',
					'subtitle_font_weight'       => '',
					'subtitle_font'              => '',

					// Text
					'custom_text_color'          => '',
					'content_text_size'          => 'default',

					// Old custom sizes
					'title_desktop_text_size'    => '',
					'subtitle_desktop_text_size' => '',
					'title_tablet_text_size'     => '',
					'subtitle_tablet_text_size'  => '',
					'title_mobile_text_size'     => '',
					'subtitle_mobile_text_size'  => '',

					// Extra
					'woodmart_css_id'            => '',
					'css_animation'              => 'none',
					'el_class'                   => '',
				),
				$atts
			)
		);

		if ( ! $woodmart_css_id ) {
			$woodmart_css_id = uniqid();
		}
		$id = 'wd-' . $woodmart_css_id;

		$class .= ' banner-' . $style;
		$class .= ' banner-hover-' . $hover;
		$class .= ' color-scheme-' . $woodmart_color_scheme;
		$class .= ' banner-btn-size-' . $btn_size;
		$class .= ' banner-btn-style-' . $btn_style;
		$class .= ' wd-display-' . $image_type;
		$class .= woodmart_get_css_animation( $css_animation );

		$banner_image_classes .= ' wd-bg-position-' . $image_bg_position;

		if ( ! $custom_subtitle_color && ! $custom_subtitle_bg_color ) {
			$subtitle_class .= ' subtitle-color-' . $subtitle_color;
		}

		$subtitle_class .= ' subtitle-style-' . $subtitle_style;
		$subtitle_class .= ' ' . woodmart_get_new_size_classes( 'banner', $title_size, 'subtitle' );
		$title_wrap_class .= woodmart_get_old_classes( ' banner-title-' . $title_size );

		$title_class .= ' wd-font-weight-' . $font_weight;
		if ( $title_font ) {
			$title_class .= ' font-' . $title_font;
		}
		$title_class .= ' ' . woodmart_get_new_size_classes( 'banner', $title_size, 'title' );

		$subtitle_class .= ' wd-font-weight-' . $subtitle_font_weight;
		if ( $subtitle_font ) {
			$subtitle_class .= ' font-' . $subtitle_font;
		}

		$content_banner .= ' text-' . $text_alignment;

		if ( $style == 'content-background' ) {
			$btn_position          = 'static';
			$content_wrap_classes .= ' wd-width-' . $content_width;
		} else {
			$content_banner .= ' wd-width-' . $content_width;
		}

		$content_wrap_classes .= ' wd-items-' . $vertical_alignment;
		$content_wrap_classes .= ' wd-justify-' . $horizontal_alignment;

		$class .= woodmart_get_old_classes( ' banner-vr-align-' . $vertical_alignment );
		$class .= woodmart_get_old_classes( ' banner-hr-align-' . $horizontal_alignment );

		$inner_class .= ' ' . woodmart_get_new_size_classes( 'banner', $content_text_size, 'content' );

		if ( $increase_spaces == 'yes' ) {
			$class .= ' banner-increased-padding';
		}
		$class .= ' ' . $el_class;

		if ( strrpos( $link, '|' ) || strrpos( $link, 'rl:' ) ) {
			$attributes = woodmart_vc_get_link_attr( $link );
		} else {
			$attributes = array(
				'target' => '',
				'url'    => $link,
			);
		}

		if ( ! empty( $btn_text ) ) {
			$class .= ' with-btn';
			$class .= ' banner-btn-position-' . $btn_position;
		}

		if ( isset( $attributes['target'] ) && $attributes['target'] == ' _blank' || $attributes['target'] == '_blank' ) {
			$onclick = 'window.open(\'' . esc_url( $attributes['url'] ) . '\',\'_blank\')';
		} elseif ( isset( $attributes['url'] ) ) {
			$onclick = 'window.location.href=\'' . esc_url( $attributes['url'] ) . '\'';
		}

		if ( $hover == 'parallax' ) {
			woodmart_enqueue_js_library( 'panr-parallax-bundle' );
			woodmart_enqueue_js_script( 'banner-element' );
		}

		if ( $link && $attributes['url'] ) {
			$class .= ' cursor-pointer';
		}

		// Button
		$btn_wrapper_classes .= ( $hide_btn_tablet == 'yes' ) ? ' banner-hide-btn-tablet' : '';
		$btn_wrapper_classes .= ( $hide_btn_mobile == 'yes' ) ? ' banner-hide-btn-mobile' : '';

		// Image settings.
		$image_url = '';

		if ( function_exists( 'wpb_getImageBySize' ) ) {
			$img          = wpb_getImageBySize(
				array(
					'attach_id'  => $image,
					'thumb_size' => $img_size,
					'class'      => 'promo-banner-image',
				)
			);
			$image_output = $img['thumbnail'];
			if ( $image ) {
				$image_url = woodmart_get_image_src( $image, $img_size );
			}
		} elseif ( function_exists( 'woodmart_get_image_url' ) ) {
			$image_url    = woodmart_get_image_url(
				$image,
				'image',
				array(
					'image_size' => $img_size,
					'image'      => array(
						'id' => $image,
					),
				)
			);
			$image_output = woodmart_get_image_html( // phpcs:ignore
				array(
					'image_size' => $img_size,
					'image'      => array(
						'id' => $image,
					),
				),
				'image'
			);
		}

		woodmart_enqueue_inline_style( 'banner' );

		ob_start(); ?>
		<div class="promo-banner-wrapper">
			<div id="<?php echo esc_attr( $id ); ?>" class="promo-banner<?php echo esc_attr( $class ); ?>" 
								<?php
								if ( $link && $attributes['url'] ) {
									echo 'onclick="' . $onclick . '"';}
								?>
			 >

				<div class="main-wrapp-img">
					<?php if ( 'background' === $image_type ) : ?>
						<div class="banner-image<?php echo esc_attr( $banner_image_classes ); ?>" style="background-image: url(<?php echo esc_url( $image_url ); ?>); height:<?php echo esc_attr( $height ); ?>px;"></div>
					<?php else : ?>
						<div class="banner-image">
							<?php echo $image_output; ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="wrapper-content-banner wd-fill <?php echo esc_attr( $content_wrap_classes ); ?>">
					<div class="content-banner<?php echo esc_attr( $content_banner ); ?>">
							<div class="banner-title-wrap<?php echo esc_attr( $title_wrap_class ); ?>">
																	<?php
																	if ( ! empty( $subtitle ) ) {
																		echo '<div class="banner-subtitle' . esc_attr( $subtitle_class ) . '">' . $subtitle . '</div>';
																	}
																	if ( ! empty( $title ) ) {
																		echo '<' . $title_tag . ' class="banner-title' . esc_attr( $title_class ) . '">' . $title . '</' . $title_tag . '>';
																	}
																	?>
								</div>
						<?php if ( $content ) : ?>
							<div class="banner-inner set-cont-mb-s reset-last-child<?php echo esc_attr( $inner_class ); ?>">
								<?php
									echo do_shortcode( wpautop( $content ) );
								?>
							</div>
						<?php endif ?>
						<?php
						if ( ! empty( $btn_text ) ) {
							echo '<div class="banner-btn-wrapper' . esc_attr( $btn_wrapper_classes ) . '">';
								echo woodmart_shortcode_button(
									array(
										'title' => $btn_text,
										'color' => $btn_color,
										'style' => $btn_style,
										'size'  => $btn_size,
										'align' => $text_alignment,
										'shape' => $btn_shape,
									)
								);
							echo '</div>';
						}
						?>
					</div>
				</div>
				<?php
				$css = '';
				if ( $custom_title_color && ! woodmart_is_css_encode( $custom_title_color ) ) $css .= '#' . $id . ' .banner-title{color:' . $custom_title_color . '}';
				if ( $custom_subtitle_color && ! woodmart_is_css_encode( $custom_subtitle_color ) ) $css .= '#' . $id . ' .banner-subtitle{color:' . $custom_subtitle_color . '}';
				if ( $custom_text_color && ! woodmart_is_css_encode( $custom_text_color ) ) $css .= '#' . $id . ' .banner-inner{color:' . $custom_text_color . '}';

				// Text size
				if ( $title_desktop_text_size ) $css .= woodmart_responsive_text_size_css( $id, 'banner-title', $title_desktop_text_size, 'return' );
				if ( $subtitle_desktop_text_size ) $css .= woodmart_responsive_text_size_css( $id, 'banner-subtitle', $subtitle_desktop_text_size, 'return' );

				if ( $title_tablet_text_size || $subtitle_tablet_text_size ) {
					$css .= '@media (max-width:1024px){';
					if ( $title_tablet_text_size ) $css .= woodmart_responsive_text_size_css( $id, 'banner-title', $title_tablet_text_size, 'return' );
					if ( $subtitle_tablet_text_size ) $css .= woodmart_responsive_text_size_css( $id, 'banner-subtitle', $subtitle_tablet_text_size, 'return' );
					$css .= '}';
				}

				if ( $title_mobile_text_size || $subtitle_mobile_text_size ) {
					$css .= '@media (max-width:767px){';
					if ( $title_mobile_text_size ) $css .= woodmart_responsive_text_size_css( $id, 'banner-title', $title_mobile_text_size, 'return' );
					if ( $subtitle_mobile_text_size ) $css .= woodmart_responsive_text_size_css( $id, 'banner-subtitle', $subtitle_mobile_text_size, 'return' );
					$css .= '}';
				}

				wp_add_inline_style( 'woodmart-inline-css', $css );
				?>
			</div>
		</div>
		
		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}


if ( ! function_exists( 'woodmart_shortcode_banners_carousel' ) ) {
	function woodmart_shortcode_banners_carousel( $atts = array(), $content = null ) {
		$output = $class = $autoplay = $wrapper_classes = '';

		$parsed_atts = shortcode_atts(
			array_merge(
				woodmart_get_owl_atts(),
				array(
					'slider_spacing'       => 30,
					'dragEndSpeed'         => 600,
					'scroll_carousel_init' => 'no',
					'el_class'             => '',
				)
			),
			$atts
		);

		extract( $parsed_atts );

		$custom_sizes = apply_filters( 'woodmart_promo_banner_shortcode_custom_sizes', false );

		$class .= ' ' . $el_class;
		$class .= ' ' . woodmart_owl_items_per_slide( $slides_per_view, array(), false, false, $custom_sizes );

		$carousel_id = 'carousel-' . rand( 100, 999 );

		$parsed_atts['custom_sizes'] = $custom_sizes;

		if ( $scroll_carousel_init == 'yes' ) {
			woodmart_enqueue_js_library( 'waypoints' );
			$wrapper_classes .= ' scroll-init';
		}

		if ( woodmart_get_opt( 'disable_owl_mobile_devices' ) ) {
			$wrapper_classes .= ' disable-owl-mobile';
		}

		$wrapper_classes .= ' wd-carousel-spacing-' . $slider_spacing;

		woodmart_enqueue_inline_style( 'owl-carousel' );

		ob_start();
		?>

			<div id="<?php echo esc_attr( $carousel_id ); ?>" class="wd-carousel-container banners-carousel-wrapper <?php echo esc_attr( $wrapper_classes ); ?>" <?php echo woodmart_get_owl_attributes( $parsed_atts ); ?>>
				<div class="owl-carousel banners-carousel<?php echo esc_attr( $class ); ?>" >
					<?php echo do_shortcode( $content ); ?>
				</div>
			</div>

		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
