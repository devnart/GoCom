<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* ------------------------------------------------------------------------------------------------
* Info box
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_shortcode_info_box' ) ) {
	function woodmart_shortcode_info_box( $atts, $content ) {
		$click = $output = $class = $text_class = $subtitle_class = $title_class = '';
		extract( shortcode_atts( array(
			'link' => '',
			'alignment' => 'left',
			'image_alignment' => 'top',
			'style' => '',
			'hover' => '',
			'woodmart_color_scheme' => '',
			'woodmart_hover_color_scheme' => 'light',
			'svg_animation' => '',
			'info_box_inline' => '',
			'woodmart_bg_position' => 'none',
			
			'bg_hover_color' => '',
			'bg_hover_color_gradient' => '',
			'bg_hover_colorpicker' => 'colorpicker',

			//Icon
			'icon_bg_color' => '',
			'icon_bg_hover_color' => '',
			'icon_border_color' => '',
			'icon_border_hover_color' => '',
			'image' => '',
			'icon_type' => 'icon',
			'icon_style' => 'simple',
			'icon_text' => '',
			'icon_text_color' => '',
			'icon_text_size' => 'default',
			'img_size' => '800x600',

			//Btn
			'btn_text' => '',
			'btn_position' => 'hover',
			'btn_color' => 'default',
			'btn_style' => 'default',
			'btn_shape' => 'rectangle',
			'btn_size' => 'default',

			//Title
			'title' => '',
			'title_size'  => 'default',
			'title_style' => 'default',
			'title_color' => '',
			'title_font_size' => '',
			'title_font_weight' => '',
			'title_tag' => 'h4',
			'title_font' => '',

			//Subtitle
			'subtitle' => '',
			'subtitle_color' => 'default',
			'subtitle_custom_color' => '',
			'subtitle_custom_bg_color' => '',
			'subtitle_style' => 'default',
			'subtitle_font_weight' => '',
			'subtitle_font' => '',

			//Content
			'custom_text_color' => '',

			//Extra
			'el_class' => '',
			'css_animation' => 'none',
			'css' => '',
			'woodmart_css_id' => '',
			'source' => 'shortcode'
		), $atts ));


		$images = explode(',', $image);

		if( $link != '' && empty( $btn_text ) ) {
			$class .= ' cursor-pointer';
		}

		if ( ! $woodmart_css_id ) $woodmart_css_id = uniqid();
		$id = 'wd-' . $woodmart_css_id;

		$class .= ' wd-info-box';
		$class .= woodmart_get_old_classes( ' woodmart-info-box' );
		if ( 'header' !== $source ) {
			$class .= ' wd-wpb';
		}
		$class .= ' text-' . $alignment;
		$class .= ' box-icon-align-' . $image_alignment;
		$class .= ' box-style-' . $style;
		$class .= ' color-scheme-' . $woodmart_color_scheme;
		$class .= ' wd-bg-' . $woodmart_bg_position;
		$class .= woodmart_get_css_animation( $css_animation );

		if ( ! $subtitle_custom_color && ! $subtitle_custom_bg_color ) {
			$subtitle_class .= ' subtitle-color-' . $subtitle_color;
		}
		$subtitle_class .= ' ' . woodmart_get_new_size_classes( 'infobox', $title_size, 'subtitle' );
		
		if ( $style == 'bg-hover' ) $class .= ' color-scheme-hover-' . $woodmart_hover_color_scheme;

		$subtitle_class .= ' subtitle-style-' . $subtitle_style;
		$subtitle_class .= ' wd-font-weight-' . $subtitle_font_weight;
		if ( $subtitle_font ) {
			$subtitle_class .= ' font-'. $subtitle_font;
		}

		// $class .= ' hover-' . $hover;
		if ( $svg_animation == 'yes' ) $class .= ' with-animation';
		$text_class .= ( $icon_type == 'icon' ) ? ' box-with-icon' : ' box-with-text text-size-'. $icon_text_size;
		$text_class .= ' box-icon-' . $icon_style;
		$class .= ( $el_class ) ? ' ' . $el_class : '';

		$title_class .= ' wd-font-weight-' . $title_font_weight;
		$title_class .= ' box-title-style-' . $title_style;
		if ( $title_font ) {
			$title_class .= ' font-'. $title_font;
		}
		$title_class .= ' ' . woodmart_get_new_size_classes( 'infobox', $title_size, 'title' );
		$class .= woodmart_get_old_classes( ' box-title-' . $title_size );

		$attributes = woodmart_vc_get_link_attr( $link );
		if ( count($images) > 1 ) {
			$class .= ' multi-icons';
		}

		if( ! empty( $btn_text ) ) {
			$class .= ' with-btn';
			$class .= ' box-btn-' . $btn_position;
		}

		if( function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$class .= ' ' . vc_shortcode_custom_css_class( $css );
		}

		$rand = "svg-" . rand(1000,9999);

		$sizes = woodmart_get_explode_size( $img_size, 128 );

        if ( isset( $attributes['target'] ) && $attributes['target'] == ' _blank' || $attributes['target'] == '_blank' ) {
        	$onclick = 'window.open("'. esc_url( $attributes['url'] ).'","_blank")';
        } elseif ( isset( $attributes['url'] ) ) {
        	$onclick = 'window.location.href="'. esc_url( $attributes['url'] ).'"';
		}

        woodmart_enqueue_inline_style( 'info-box' );
		
		ob_start(); ?>
			<div class="info-box-wrapper <?php if ( $info_box_inline == 'yes' ) echo 'inline-element'; ?>">
				<div id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>" <?php if( ! empty( $attributes['url'] ) && empty( $btn_text ) ): ?> onclick="<?php echo esc_js( $onclick ); ?>" <?php endif; ?> >
					<?php if ( $images[0] || $icon_text ): ?>
						<div class="box-icon-wrapper <?php echo esc_attr( $text_class ); ?>">
							<div class="info-box-icon">

							<?php if ( $icon_type == 'icon' ): ?>

								<?php $i=0; foreach ($images as $img_id): $i++; ?>
									<?php
										if ( function_exists( 'wpb_getImageBySize' ) ) {
											$img = wpb_getImageBySize( array( 'attach_id' => $img_id, 'thumb_size' => $img_size, 'class' => 'info-icon image-' . $i ) );
											$src = isset( $img['p_img_large'][0] ) ? $img['p_img_large'][0] : '';
											$image_output = $img['thumbnail'];
										} elseif ( function_exists( 'woodmart_get_image_url' ) ) {
											$src = woodmart_get_image_url(
												$img_id,
												'image',
												array(
													'image_size' => $img_size,
													'image'                  => array(
														'id' => $img_id,
													),
												)
											);
											$image_output = woodmart_get_image_html( // phpcs:ignore
												array(
													'image_size'             => $img_size,
													'image'                  => array(
														'id' => $img_id,
													),
												),
												'image'
											);
										}

										if( woodmart_is_svg( $src ) ) {
											if ( $svg_animation == 'yes' ) {
												woodmart_enqueue_js_library( 'vivus' );
												
												wp_add_inline_script('woodmart-theme', 'jQuery(document).ready(function($) {
												if ( $("#' . esc_js( $rand ) . '").length > 0 ) {
													new Vivus("' . esc_js( $rand ) . '", {
														type: "delayed",
														duration: 200,
														start: "inViewport",
														animTimingFunction: Vivus.EASE_OUT
													});
												}
												});', 'after');
											}
											echo '<div class="info-svg-wrapper info-icon" style="width: ' . $sizes[0] . 'px;height: ' . $sizes[1] . 'px;">' . woodmart_get_any_svg( $src, $rand ) . '</div>';
										} else {
											echo $image_output;
										}
									?>
								<?php endforeach ?>								
							<?php else: ?>
								<?php echo esc_attr( $icon_text ); ?>
							<?php endif ?>

							</div>
						</div>
					<?php endif; ?>
					<div class="info-box-content">
						<?php
							if( ! empty( $subtitle ) ) {
								echo '<div class="info-box-subtitle'. esc_attr( $subtitle_class ) .'">' . $subtitle . '</div>';
							}
							if( ! empty( $title ) ) {
								echo '<'. $title_tag .' class="info-box-title title' . esc_attr( $title_class ) . '">' . $title . '</'. $title_tag .'>';
							}
						?>
						<div class="info-box-inner set-cont-mb-s reset-last-child">
							<?php
								echo do_shortcode( wpautop( $content ) );
							?>
						</div>

						<?php
							if( ! empty( $btn_text ) ) {
								echo '<div class="info-btn-wrapper">';
								echo woodmart_shortcode_button( array(
										'title' 	 => $btn_text,
										'link' 	 	 => $link,
										'color' 	 => $btn_color,
										'style'   	 => $btn_style,
										'size' 		 => $btn_size,
										'align'  	 => $alignment,
										'shape'		 => $btn_shape,
									) );
								echo '</div>';
							}
						?>
						
					</div>

					<?php
					$style = '';
					if ( $bg_hover_color || $icon_text_color || $icon_bg_color || $icon_bg_hover_color || $icon_border_color || $icon_border_hover_color || $bg_hover_color_gradient || $title_color || $subtitle_custom_color || $subtitle_custom_bg_color || $custom_text_color ) {
						$style .= '<style>';

						if ( $bg_hover_color ) {
							if ( is_array( $bg_hover_color ) ) {
								$bg_hover_color = 'rgba(' . $bg_hover_color['r'] . ', ' . $bg_hover_color['g'] . ', ' . $bg_hover_color['b'] . ',' . $bg_hover_color['a'] . ')';
							}

							if ( ! woodmart_is_css_encode( $bg_hover_color ) ) {
								$style .= '#' . $id . ':after {background-color: ' . $bg_hover_color . ' !important;}';
							}
						}

						//Icon
						if ( $icon_text_color ) {
							if ( is_array( $icon_text_color ) ) {
								$icon_text_color = 'rgba(' . $icon_text_color['r'] . ', ' . $icon_text_color['g'] . ', ' . $icon_text_color['b'] . ',' . $icon_text_color['a'] . ')';
							}

							if ( ! woodmart_is_css_encode( $icon_text_color ) ) {
								$style .= '#' . $id . ' .box-with-text {color: ' . $icon_text_color . ' !important;}';
							}
						}

						if ( $icon_bg_color || $icon_border_color ) {
							if ( is_array( $icon_bg_color ) ) {
								$icon_bg_color = 'rgba(' . $icon_bg_color['r'] . ', ' . $icon_bg_color['g'] . ', ' . $icon_bg_color['b'] . ',' . $icon_bg_color['a'] . ')';
							}

							if ( is_array( $icon_border_color ) ) {
								$icon_border_color = 'rgba(' . $icon_border_color['r'] . ', ' . $icon_border_color['g'] . ', ' . $icon_border_color['b'] . ',' . $icon_border_color['a'] . ')';
							}

							$style .= '#' . $id . ' .info-box-icon {';

							if ( ! woodmart_is_css_encode( $icon_bg_color ) ) {
								$style .= 'background-color: ' . $icon_bg_color . ' !important;';
							}

							if ( ! woodmart_is_css_encode( $icon_border_color ) ) {
								$style .= 'border-color: ' . $icon_border_color . ' !important;';
							}

							$style .= '}';
						}

						if ( $icon_bg_hover_color || $icon_border_hover_color ) {
							if ( is_array( $icon_bg_hover_color ) ) {
								$icon_bg_hover_color = 'rgba(' . $icon_bg_hover_color['r'] . ', ' . $icon_bg_hover_color['g'] . ', ' . $icon_bg_hover_color['b'] . ',' . $icon_bg_hover_color['a'] . ')';
							}

							if ( is_array( $icon_border_hover_color ) ) {
								$icon_border_hover_color = 'rgba(' . $icon_border_hover_color['r'] . ', ' . $icon_border_hover_color['g'] . ', ' . $icon_border_hover_color['b'] . ',' . $icon_border_hover_color['a'] . ')';
							}

							$style .= '#' . $id . ':hover .info-box-icon{';

							if ( ! woodmart_is_css_encode( $icon_bg_hover_color ) ) {
								$style .= 'background-color: ' . $icon_bg_hover_color . ' !important;';
							}

							if ( ! woodmart_is_css_encode( $icon_border_hover_color ) ) {
								$style .= 'border-color: ' . $icon_border_hover_color . ' !important;';
							}

							$style .= '}';
						}

						//Gradient
						if ( $bg_hover_colorpicker == 'gradient' && $bg_hover_color_gradient ) {
							$style .= '#' . $id . ':after {' . woodmart_get_gradient_css( $bg_hover_color_gradient ) . ' !important;}';
						}

						//Title
						if ( $title_color ) {
							if ( is_array( $title_color ) ) {
								$title_color = 'rgba(' . $title_color['r'] . ', ' . $title_color['g'] . ', ' . $title_color['b'] . ',' . $title_color['a'] . ')';
							}

							if ( ! woodmart_is_css_encode( $title_color ) ) {
								$style .= '#' . $id . ' .info-box-title {color: ' . $title_color . ' !important;}';
							}
						}

						//Subtitle
						if ( $subtitle_custom_color || $subtitle_custom_bg_color ) {
							if ( is_array( $subtitle_custom_color ) ) {
								$subtitle_custom_color = 'rgba(' . $subtitle_custom_color['r'] . ', ' . $subtitle_custom_color['g'] . ', ' . $subtitle_custom_color['b'] . ',' . $subtitle_custom_color['a'] . ')';
							}

							if ( is_array( $subtitle_custom_bg_color ) ) {
								$subtitle_custom_bg_color = 'rgba(' . $subtitle_custom_bg_color['r'] . ', ' . $subtitle_custom_bg_color['g'] . ', ' . $subtitle_custom_bg_color['b'] . ',' . $subtitle_custom_bg_color['a'] . ')';
							}

							$style .= '#' . $id . ' .info-box-subtitle{';

							if ( ! woodmart_is_css_encode( $subtitle_custom_color ) ) {
								$style .= 'color: ' . $subtitle_custom_color . ' !important;';
							}

							if ( ! woodmart_is_css_encode( $subtitle_custom_bg_color ) ) {
								$style .= 'background-color: ' . $subtitle_custom_bg_color . ' !important;';
							}

							$style .= '}';
						}

						//Content
						if ( $custom_text_color ) {
							if ( is_array( $custom_text_color ) ) {
								$custom_text_color = 'rgba(' . $custom_text_color['r'] . ', ' . $custom_text_color['g'] . ', ' . $custom_text_color['b'] . ',' . $custom_text_color['a'] . ')';
							}

							if ( ! woodmart_is_css_encode( $custom_text_color ) ) {
								$style .= '#' . $id . ' .info-box-inner {color: ' . $custom_text_color . ' !important;}';
							}
						}

						$style .= '</style>';
					}

					echo apply_filters( 'woodmart_infobox_style', $style );
					?>
				</div>
			</div>
		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}


if( ! function_exists( 'woodmart_shortcode_info_box_carousel' ) ) {
	function woodmart_shortcode_info_box_carousel( $atts = array(), $content = null ) {
		$output = $class = $autoplay = $wrapper_classes = '';

		$parsed_atts = shortcode_atts( array_merge( woodmart_get_owl_atts(), array(
			'slider_spacing' => 30,
			'dragEndSpeed' => 600,
			'scroll_carousel_init' => 'no',
			'el_class' => '',
		) ), $atts );

		extract( $parsed_atts );

		$custom_sizes = apply_filters( 'woodmart_info_box_shortcode_custom_sizes', false );

		$class .= ' ' . $el_class;
		$class .= ' ' . woodmart_owl_items_per_slide( $slides_per_view, array(), false, false, $custom_sizes );

		$carousel_id = 'carousel-' . rand( 100, 999 );

		$parsed_atts['carousel_id'] = $carousel_id;
		$parsed_atts['custom_sizes'] = $custom_sizes;
		$owl_atts = woodmart_get_owl_attributes( $parsed_atts );
		woodmart_enqueue_inline_style( 'owl-carousel' );

		if ( $scroll_carousel_init == 'yes' ) {
			woodmart_enqueue_js_library( 'waypoints' );
			$wrapper_classes .= ' scroll-init';
		}

		if ( woodmart_get_opt( 'disable_owl_mobile_devices' ) ) {
			$wrapper_classes .= ' disable-owl-mobile';
		}
	
		$wrapper_classes .= ' wd-carousel-spacing-' . $slider_spacing;
		
		ob_start(); ?>
			<div id="<?php echo esc_attr( $carousel_id ); ?>" class="wd-carousel-container info-box-carousel-wrapper <?php echo esc_attr( $wrapper_classes ); ?>" <?php echo ! empty( $owl_atts ) ? $owl_atts : ''; ?>>
				<div class="owl-carousel info-box-carousel<?php echo esc_attr( $class ); ?>" >
					<?php echo do_shortcode( $content ); ?>
				</div>
			</div>
		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
