<?php
/**
 * Instagram template function.
 *
 * @package xts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'woodmart_elementor_instagram_template' ) ) {
	function woodmart_elementor_instagram_template( $settings ) {
		$default_settings = [
			'username'                => 'flickr',
			'number'                  => [ 'size' => 9 ],
			'size'                    => 'medium',
			'target'                  => '_self',
			'link'                    => '',
			'design'                  => 'grid',
			'spacing'                 => 0,
			'spacing_custom'          => 6,
			'rounded'                 => 0,
			'per_row'                 => [ 'size' => 3 ],
			'hide_mask'               => 0,
			'hide_pagination_control' => '',
			'hide_prev_next_buttons'  => '',
			'ajax_body'               => false,
			'content'                 => '',
			'data_source'             => 'scrape',
			'custom_sizes'            => apply_filters( 'woodmart_instagram_shortcode_custom_sizes', false ),

			// Images.
			'images'                  => array(),
			'images_size'             => 'medium',
			'images_link'             => '',
			'images_likes'            => '1000-10000',
			'images_comments'         => '0-1000',
		];

		$settings            = wp_parse_args( $settings, $default_settings );
		$settings['number']  = $settings['number']['size'];
		$settings['per_row'] = $settings['per_row']['size'];
		$wrapper_classes     = '';
		$owl_attributes      = '';
		$pics_classes        = '';
		$picture_classes     = '';
		$carousel_id         = 'carousel-' . rand( 100, 999 );

		// Wrapper classes.
		$wrapper_classes .= ' data-source-' . $settings['data_source'];
		if ( '' !== $settings['design'] ) {
			$wrapper_classes .= ' instagram-' . $settings['design'];
		}
		if ( 1 == $settings['rounded'] ) {
			$wrapper_classes .= ' instagram-rounded';
		}

		// Settings.
		if ( ! $settings['spacing'] ) {
			$settings['spacing_custom'] = 0;
		}

		if ( 'slider' === $settings['design'] ) {
			woodmart_enqueue_inline_style( 'owl-carousel' );
			$owl_attributes = woodmart_get_owl_attributes(
				array(
					'carousel_id'             => $carousel_id,
					'hide_pagination_control' => $settings['hide_pagination_control'],
					'hide_prev_next_buttons'  => $settings['hide_prev_next_buttons'],
					'slides_per_view'         => $settings['per_row'],
					'custom_sizes'            => $settings['custom_sizes'],
				)
			);

			if ( woodmart_get_opt( 'disable_owl_mobile_devices' ) ) {
				$wrapper_classes .= ' disable-owl-mobile';
			}

			$pics_classes    .= ' owl-carousel ' . woodmart_owl_items_per_slide( $settings['per_row'], array(), false, false, $settings['custom_sizes'] );
			$wrapper_classes .= ' wd-carousel-container';
			$wrapper_classes .= ' wd-carousel-spacing-' . $settings['spacing_custom'];
		} else {
			$pics_classes    .= ' row';
			$picture_classes .= woodmart_get_grid_el_class( 0, $settings['per_row'] );
			$pics_classes    .= ' wd-spacing-' . $settings['spacing_custom'];
		}

		if ( 'images' === $settings['data_source'] ) {
			$images = array();
			foreach ( $settings['images'] as $image ) {
				$images[] = $image['id'];
			}

			$media_array = woodmart_get_instagram_custom_images( implode( ',', $images ), $settings['images_size'], $settings['images_link'], $settings['images_likes'], $settings['images_comments'] );
		} else {
			$media_array = woodmart_scrape_instagram( $settings['username'], $settings['number'], $settings['ajax_body'], $settings['data_source'] );
		}

		unset( $settings['ajax_body'] );

		$encoded_attributes = json_encode( $settings );

		if ( is_wp_error( $media_array ) && ( $media_array->get_error_code() === 'invalid_response_429' || apply_filters( 'woodmart_intagram_user_ajax_load', false ) || 'ajax' === $settings['data_source'] ) ) {
			woodmart_enqueue_js_script( 'instagram-element' );
			$wrapper_classes      .= ' instagram-with-error';
			$media_array           = array();
			$settings['hide_mask'] = true;
			for ( $i = 0; $i < $settings['number']; $i++ ) {
				$media_array[] = array(
					$settings['size'] => WOODMART_ASSETS . '/images/settings/instagram/insta-placeholder.jpg',
					'link'            => '#',
					'likes'           => '0',
					'comments'        => '0',
				);
			}
		}

		woodmart_enqueue_inline_style( 'instagram' );

		?>
		<div id="<?php echo esc_attr( $carousel_id ); ?>" data-atts="<?php echo esc_attr( $encoded_attributes ); ?>" data-username="<?php echo esc_attr( $settings['username'] ); ?>" class="instagram-pics instagram-widget<?php echo esc_attr( $wrapper_classes ); ?>" <?php echo $owl_attributes; ?>>
			<?php if ( $settings['username'] && ! is_wp_error( $media_array ) ) : ?>
				<?php if ( $settings['content'] ) : ?>
					<div class="instagram-content wd-fill">
						<div class="instagram-content-inner">
							<?php echo do_shortcode( $settings['content'] ); ?>
						</div>
					</div>
				<?php endif; ?>

				<div class="<?php echo esc_attr( $pics_classes ); ?>">
					<?php foreach ( $media_array as $item ) : ?>
						<?php
						if ( 'api' === $settings['data_source'] || 'images' === $settings['data_source'] ) {
							$settings['size'] = 'large';
						}

						$image    = ! empty( $item[ $settings['size'] ] ) ? $item[ $settings['size'] ] : $item['thumbnail'];
						$bg_image = 'api' === $settings['data_source'] || 'images' === $settings['data_source'] ? 'background-image: url(' . $image . ');' : '';
						?>

						<div class="instagram-picture<?php echo esc_attr( $picture_classes ); ?>">
							<div class="wrapp-picture" style="<?php echo esc_attr( $bg_image ); ?>">
								<a href="<?php echo esc_url( $item['link'] ); ?>" target="<?php echo esc_attr( $settings['target'] ); ?>"></a>

								<?php if ( 'api' !== $settings['data_source'] && 'images' !== $settings['data_source'] ) : ?>
									<?php echo apply_filters( 'woodmart_image', '<img src="' . esc_url( $image ) . '" />' ); ?>
								<?php endif; ?>

								<?php if ( 0 == $settings['hide_mask'] ) : ?>
									<div class="hover-mask">
										<span class="instagram-likes"><span><?php echo esc_attr( woodmart_pretty_number( $item['likes'] ) ); ?></span></span>
										<span class="instagram-comments"><span><?php echo esc_attr( woodmart_pretty_number( $item['comments'] ) ); ?></span></span>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php elseif ( is_wp_error( $media_array ) ) : ?>
				<?php echo '<div class="wd-notice wd-info">' . esc_html( $media_array->get_error_message() ) . '</div>'; ?>
			<?php endif; ?>

			<?php if ( $settings['link'] ) : ?>
				<p class="clear">
					<a href="//www.instagram.com/<?php echo trim( $settings['username'] ); ?>" rel="me" target="<?php echo esc_attr( $settings['target'] ); ?>">
						<?php echo esc_html( $settings['link'] ); ?>
					</a>
				</p>
			<?php endif; ?>
		</div>
		<?php
	}
}
