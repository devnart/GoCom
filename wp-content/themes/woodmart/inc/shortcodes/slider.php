<?php
if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

if ( ! function_exists( 'woodmart_shortcode_slider' ) ) {
	function woodmart_shortcode_slider( $atts ) {
		$output = $class = '';

		$parsed_atts = shortcode_atts(
			array(
				'slider'    => '',
				'el_class'  => '',
				'elementor' => false,
			),
			$atts
		);

		extract( $parsed_atts );

		$class .= ' ' . $el_class;
		$class .= ' ' . woodmart_owl_items_per_slide( 1 );
		$class .= woodmart_get_old_classes( ' woodmart-slider' );

//		$slider = apply_filters( 'wpml_object_id', $slider, 'woodmart_slide', true );

		$slider_term = get_term_by( 'slug', $slider, 'woodmart_slider' );

		if ( is_wp_error( $slider_term ) || empty( $slider_term ) ) {
			return;
		}

		$args = array(
			'numberposts' => -1,
			'post_type'   => 'woodmart_slide',
			'orderby'     => 'menu_order',
			'order'       => 'ASC',
			'tax_query'   => array(
				array(
					'taxonomy' => 'woodmart_slider',
					'field'    => 'id',
					'terms'    => $slider_term->term_id,
				),
			),
		);

		$slides = get_posts( $args );

		if ( is_wp_error( $slides ) || empty( $slides ) ) {
			return;
		}

		$stretch_slider = get_term_meta( $slider_term->term_id, 'stretch_slider', true );

		$carousel_id = 'slider-' . $slider_term->term_id;

		$animation = get_term_meta( $slider_term->term_id, 'animation', true );

		$slide_speed_default = 900;// ($animation == 'fade') ? false : 900;

		$slide_speed = apply_filters( 'woodmart_slider_sliding_speed', $slide_speed_default );

		$slider_atts = array(
			'carousel_id'             => $carousel_id,
			'hide_pagination_control' => get_term_meta( $slider_term->term_id, 'pagination_style', true ) == '0' ? 'yes' : 'no',
			'hide_prev_next_buttons'  => get_term_meta( $slider_term->term_id, 'arrows_style', true ) == '0' ? 'yes' : 'no',
			'autoplay'                => ( get_term_meta( $slider_term->term_id, 'autoplay', true ) == 'on' ) ? 'yes' : 'no',
			'speed'                   => get_term_meta( $slider_term->term_id, 'autoplay_speed', true ) ? get_term_meta( $slider_term->term_id, 'autoplay_speed', true ) : 9000,
			'sliding_speed'           => $slide_speed,
			'animation'               => ( $animation == 'fade' ) ? 'fadeOut' : false,
			'content_animation'       => true,
			'autoheight'              => 'yes',
			'wrap'                    => 'yes',
		);

		woodmart_enqueue_js_library( 'owl' );
		woodmart_enqueue_js_script( 'slider-element' );
		woodmart_enqueue_inline_style( 'slider' );
		woodmart_enqueue_inline_style( 'owl-carousel' );

		if ( ! $elementor ) {
			ob_start();
		}

		$wrapper_attributes = '';

		$first_slide_key = array_key_first( $slides );

		?>
			<?php woodmart_get_slider_css( $slider_term->term_id, $carousel_id, $slides ); ?>
			<div id="<?php echo esc_attr( $carousel_id ); ?>" class="wd-slider-wrapper<?php echo woodmart_get_slider_class( $slider_term->term_id ); ?>" <?php if( 'on' === $stretch_slider && 'wpb' === woodmart_get_opt( 'page_builder', 'wpb' ) ) : ?>data-vc-full-width="true" data-vc-full-width-init="true" data-vc-stretch-content="true"<?php endif; ?> <?php echo woodmart_get_owl_attributes( $slider_atts ); ?> <?php echo $wrapper_attributes; ?>>
				<div class="owl-carousel wd-slider<?php echo esc_attr( $class ); ?>">
					<?php foreach ( $slides as $key => $slide ) : ?>
						<?php
							$slide_id        = 'slide-' . $slide->ID;
							$slide_animation = get_post_meta( $slide->ID, 'slide_animation', true );
							$slide_classes = '';

							if ( $key === $first_slide_key ) {
								$slide_classes .= ' woodmart-loaded';
								woodmart_lazy_loading_deinit( true );
							}

							$slide_classes .= woodmart_get_old_classes( ' woodmart-slide' );
						?>
						<div id="<?php echo esc_attr( $slide_id ); ?>" class="wd-slide<?php echo esc_attr( $slide_classes ); ?>">
							<div class="container wd-slide-container<?php echo woodmart_get_old_classes( ' woodmart-slide-container' ); ?><?php echo woodmart_get_slide_class( $slide->ID ); ?>">
								<div class="wd-slide-inner<?php echo woodmart_get_old_classes( ' woodmart-slide-inner' ); ?> <?php echo ( ! empty( $slide_animation ) && $slide_animation != 'none' ) ? 'slide-animation anim-' . esc_attr( $slide_animation ) : ''; ?>">
									<?php if ( woodmart_is_elementor_installed() && Elementor\Plugin::$instance->db->is_built_with_elementor( $slide->ID ) ) : ?>
										<?php echo woodmart_elementor_get_content( $slide->ID ); // phpcs:ignore ?>
									<?php else : ?>
										<?php echo apply_filters( 'the_content', $slide->post_content ); ?>
									<?php endif; ?>
								</div>
							</div>
						</div>

						<?php
						if ( $key === $first_slide_key ) {
							woodmart_lazy_loading_init();
						}
						?>

					<?php endforeach; ?>
				</div>
			</div>
			<?php
			if ( 'on' === $stretch_slider && 'wpb' === woodmart_get_opt( 'page_builder', 'wpb' ) ) {
				echo '<div class="vc_row-full-width vc_clearfix"></div>';
			}
			?>
		<?php

		if ( ! $elementor ) {
			$output = ob_get_contents();
			ob_end_clean();

			return $output;
		}
	}
}


if ( ! function_exists( 'woodmart_get_slider_css' ) ) {
	function woodmart_get_slider_css( $id, $el_id, $slides ) {

		$height        = get_term_meta( $id, 'height', true );
		$height_tablet = get_term_meta( $id, 'height_tablet', true );
		$height_mobile = get_term_meta( $id, 'height_mobile', true );

		echo '<style>';
		?>

			#<?php echo esc_attr( $el_id ); ?> .wd-slide {
				min-height: <?php echo esc_attr( $height ); ?>px;
			}
			
			@media (min-width: 1025px) {
				.browser-Internet #<?php echo esc_attr( $el_id ); ?> .wd-slide {
					height: <?php echo esc_attr( $height ); ?>px;
				}
			}

			@media (max-width: 1024px) {
				#<?php echo esc_attr( $el_id ); ?> .wd-slide {
					min-height: <?php echo esc_attr( $height_tablet ); ?>px;
				}
			}

			@media (max-width: 767px) {
				#<?php echo esc_attr( $el_id ); ?> .wd-slide {
					min-height: <?php echo esc_attr( $height_mobile ); ?>px;
				}
			}

			<?php
			foreach ( $slides as $slide ) {
				$image = '';
				if ( has_post_thumbnail( $slide->ID ) ) {
					$image = wp_get_attachment_url( get_post_thumbnail_id( $slide->ID ) );
				}

				$bg_color           = get_post_meta( $slide->ID, 'bg_color', true );
				$content_full_width = get_post_meta( $slide->ID, 'content_full_width', true );
				$width              = get_post_meta( $slide->ID, 'content_width', true );
				$width_tablet       = get_post_meta( $slide->ID, 'content_width_tablet', true );
				$width_mobile       = get_post_meta( $slide->ID, 'content_width_mobile', true );

				$bg_image_tablet = get_post_meta( $slide->ID, 'bg_image_tablet', true );
				$bg_image_mobile = get_post_meta( $slide->ID, 'bg_image_mobile', true );

				?>
						#slide-<?php echo esc_attr( $slide->ID ); ?>.woodmart-loaded {
						<?php woodmart_maybe_set_css_rule( 'background-image', $image ); ?>
						}

						#slide-<?php echo esc_attr( $slide->ID ); ?> {
						<?php woodmart_maybe_set_css_rule( 'background-color', $bg_color ); ?>
						}
					
					<?php if ( ! $content_full_width ) : ?>
						#slide-<?php echo esc_attr( $slide->ID ); ?> .wd-slide-inner {
							<?php woodmart_maybe_set_css_rule( 'max-width', $width ); ?>
						}
						<?php endif; ?>

						@media (max-width: 1024px) {
						<?php if ( $bg_image_tablet ) : ?>
							#slide-<?php echo esc_attr( $slide->ID ); ?>.woodmart-loaded {
								<?php woodmart_maybe_set_css_rule( 'background-image', $bg_image_tablet ); ?>
							}
							<?php endif; ?>
					
						<?php if ( ! $content_full_width ) : ?>
							#slide-<?php echo esc_attr( $slide->ID ); ?> .wd-slide-inner {
								<?php woodmart_maybe_set_css_rule( 'max-width', $width_tablet ); ?>
							}
							<?php endif; ?>
						}

						@media (max-width: 767px) {
						<?php if ( $bg_image_mobile ) : ?>
							#slide-<?php echo esc_attr( $slide->ID ); ?>.woodmart-loaded {
								<?php woodmart_maybe_set_css_rule( 'background-image', $bg_image_mobile ); ?>
							}
							<?php endif; ?>
					
						<?php if ( ! $content_full_width ) : ?>
							#slide-<?php echo esc_attr( $slide->ID ); ?> .wd-slide-inner {
								<?php woodmart_maybe_set_css_rule( 'max-width', $width_mobile ); ?>
							}
							<?php endif; ?>
						}

					<?php if ( get_post_meta( $slide->ID, '_wpb_shortcodes_custom_css', true ) ) : ?>
							<?php echo get_post_meta( $slide->ID, '_wpb_shortcodes_custom_css', true ); ?>
						<?php endif; ?>

					<?php if ( get_post_meta( $slide->ID, 'woodmart_shortcodes_custom_css', true ) ) : ?>
							<?php echo get_post_meta( $slide->ID, 'woodmart_shortcodes_custom_css', true ); ?>
						<?php endif; ?>

					<?php
			}
			?>

		<?php
		echo '</style>';
	}
}

if ( ! function_exists( 'woodmart_maybe_set_css_rule' ) ) {
	function woodmart_maybe_set_css_rule( $rule, $value = '', $before = '', $after = '' ) {

		if ( in_array( $rule, array( 'width', 'height', 'max-width', 'max-height' ) ) && empty( $after ) ) {
			$after = 'px';
		}

		if ( in_array( $rule, array( 'background-image' ) ) && ( empty( $before ) || empty( $after ) ) ) {
			$before = 'url(';
			$after  = ')';
		}

		echo ! empty( $value ) ? $rule . ':' . $before . $value . $after . ';' : '';
	}
}

if ( ! function_exists( 'woodmart_get_slider_class' ) ) {
	function woodmart_get_slider_class( $id ) {
		$class = '';

		$arrows_style         = get_term_meta( $id, 'arrows_style', true );
		$pagination_style     = get_term_meta( $id, 'pagination_style', true );
		$pagination_color     = get_term_meta( $id, 'pagination_color', true );
		$stretch_slider       = get_term_meta( $id, 'stretch_slider', true );
		$stretch_content      = get_term_meta( $id, 'stretch_content', true );
		$scroll_carousel_init = get_term_meta( $id, 'scroll_carousel_init', true );

		$class .= ' arrows-style-' . $arrows_style;
		$class .= ' pagin-style-' . $pagination_style;
		$class .= ' pagin-color-scheme-' . $pagination_color;
		$class .= woodmart_get_old_classes( ' woodmart-slider-wrapper' );

		if ( $scroll_carousel_init == 'on' ) {
			woodmart_enqueue_js_library( 'waypoints' );
			$class .= ' scroll-init';
		}

		if ( 'on' === $stretch_slider ) {
			if ( 'wpb' === woodmart_get_opt( 'page_builder', 'wpb' ) ) {
				$class .= ' vc_row vc_row-fluid';
			} else {
				$class .= ' wd-section-stretch';
			}

			if ( 'on' === $stretch_content ) {
				$class .= ' wd-full-width-content';
			}
		} else {
			$class .= ' slider-in-container';
		}

		return $class;
	}
}

if ( ! function_exists( 'woodmart_get_slide_class' ) ) {
	function woodmart_get_slide_class( $id ) {
		$class = '';

		$v_align         = get_post_meta( $id, 'vertical_align', true );
		$h_align         = get_post_meta( $id, 'horizontal_align', true );
		$full_width      = get_post_meta( $id, 'content_full_width', true );
		$without_padding = get_post_meta( $id, 'content_without_padding', true );

		$class .= ' wd-items-' . $v_align;
		$class .= ' wd-justify-' . $h_align;

		$class .= ' content-' . ( $full_width ? 'full-width' : 'fixed' );
		$class .= $without_padding ? ' slide-without-padding' : '';

		return apply_filters( 'woodmart_slide_classes', $class );
	}
}
