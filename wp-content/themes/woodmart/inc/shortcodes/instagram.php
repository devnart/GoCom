<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );}

/**
* ------------------------------------------------------------------------------------------------
* Instagram shortcode
* ------------------------------------------------------------------------------------------------
*/

if ( ! function_exists( 'woodmart_shortcode_instagram' ) ) {
	function woodmart_shortcode_instagram( $atts, $content = '' ) {
		$output      = $pics_classes = $picture_classes = $owl_atts = '';
		$parsed_atts = shortcode_atts(
			array(
				'title'                   => '',
				'username'                => 'flickr',
				'number'                  => 9,
				'size'                    => 'medium',
				'target'                  => '_self',
				'link'                    => '',
				'design'                  => 'grid',
				'spacing'                 => 0,
				'spacing_custom'          => 6,
				'rounded'                 => 0,
				'per_row'                 => 3,
				'hide_mask'               => 0,
				'hide_pagination_control' => '',
				'hide_prev_next_buttons'  => '',
				'el_class'                => '',
				'ajax_body'               => false,
				'content'                 => $content,
				'data_source'             => 'scrape',

				// Images.
				'images'                  => array(),
				'images_size'             => 'medium',
				'images_link'             => '',
				'images_likes'            => '1000-10000',
				'images_comments'         => '0-1000',
			),
			$atts
		);

		extract( $parsed_atts );

		$carousel_id = 'carousel-' . rand( 100, 999 );

		ob_start();

		$class = 'instagram-widget';

		$class .= $el_class ? ' ' . $el_class : '';

		if ( $design != '' ) {
			$class .= ' instagram-' . $design;
		}

		if ( $rounded == 1 ) {
			$class .= ' instagram-rounded';
		}

		$class .= ' data-source-' . $data_source;

		if ( ! $spacing ) {
			$spacing_custom = 0;
		}

		if ( $design == 'slider' ) {
			woodmart_enqueue_inline_style( 'owl-carousel' );
			$custom_sizes = apply_filters( 'woodmart_instagram_shortcode_custom_sizes', false );

			$owl_atts = woodmart_get_owl_attributes(
				array(
					'carousel_id'             => $carousel_id,
					'hide_pagination_control' => $hide_pagination_control,
					'hide_prev_next_buttons'  => $hide_prev_next_buttons,
					'slides_per_view'         => $per_row,
					'custom_sizes'            => $custom_sizes,
				)
			);

			if ( woodmart_get_opt( 'disable_owl_mobile_devices' ) ) {
				$class .= ' disable-owl-mobile';
			}

			$pics_classes .= ' owl-carousel ' . woodmart_owl_items_per_slide( $per_row, array(), false, false, $custom_sizes );
			$class        .= ' wd-carousel-container';
			$class        .= ' wd-carousel-spacing-' . $spacing_custom;
		} else {
			$pics_classes    .= ' row';
			$picture_classes .= woodmart_get_grid_el_class( 0, $per_row );
			$pics_classes    .= ' wd-spacing-' . $spacing_custom;
		}

		if ( 'images' === $data_source ) {
			$size        = 'large';
			$media_array = woodmart_get_instagram_custom_images( $images, $images_size, $images_link, $images_likes, $images_comments );
		} else {
			$media_array = woodmart_scrape_instagram( $username, $number, $ajax_body, $data_source );
		}

		unset( $parsed_atts['ajax_body'] );

		$encoded_atts = json_encode( $parsed_atts );

		if ( is_wp_error( $media_array ) && ( $media_array->get_error_code() === 'invalid_response_429' || apply_filters( 'woodmart_intagram_user_ajax_load', false ) || 'ajax' === $data_source ) ) {
			woodmart_enqueue_js_script( 'instagram-element' );
			$class      .= ' instagram-with-error';
			$media_array = array();
			$hide_mask   = true;
			for ( $i = 0; $i < $number; $i++ ) {
				$media_array[] = array(
					$size      => WOODMART_ASSETS . '/images/settings/instagram/insta-placeholder.jpg',
					'link'     => '#',
					'likes'    => '0',
					'comments' => '0',
				);
			}
		}

		woodmart_enqueue_inline_style( 'instagram' );

		echo '<div id="' . esc_attr( $carousel_id ) . '" data-atts="' . esc_attr( $encoded_atts ) . '" data-username="' . esc_attr( $username ) . '" class="instagram-pics ' . esc_attr( $class ) . '" ' . $owl_atts . '>';

		if ( ! empty( $title ) ) {
			echo '<h3 class="title">' . $title . '</h3>';
		};

		if ( $username != '' && ! is_wp_error( $media_array ) ) {
			if ( ! empty( $content ) ) : ?>
				<div class="instagram-content wd-fill">
					<div class="instagram-content-inner">
						<?php echo do_shortcode( $content ); ?>
					</div>
				</div>
				<?php
			endif;

			?>
				<div class="<?php echo esc_attr( $pics_classes ); ?>">
				<?php foreach ( $media_array as $item ) : ?>
				
					<?php
					if ( 'api' === $data_source ) {
						$size = 'large';
					}

						$image    = ( ! empty( $item[ $size ] ) ) ? $item[ $size ] : $item['thumbnail'];
						$bg_image = 'api' === $data_source || 'images' === $data_source ? 'background-image: url(' . $image . ');' : '';
					?>
				
					<div class="instagram-picture<?php echo esc_attr( $picture_classes ); ?>">
						<div class="wrapp-picture" style="<?php echo esc_attr( $bg_image ); ?>">
							<a href="<?php echo esc_url( $item['link'] ); ?>" target="<?php echo esc_attr( $target ); ?>"></a>
							
							<?php if ( 'api' !== $data_source && 'images' !== $data_source ) : ?>
								<?php echo apply_filters( 'woodmart_image', '<img src="' . esc_url( $image ) . '" />' ); ?>
							<?php endif; ?>
							
							<?php if ( $hide_mask == 0 ) : ?>
								<div class="hover-mask">
									<span class="instagram-likes"><span><?php echo esc_attr( woodmart_pretty_number( $item['likes'] ) ); ?></span></span>
									<span class="instagram-comments"><span><?php echo esc_attr( woodmart_pretty_number( $item['comments'] ) ); ?></span></span>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
				</div>
			<?php
		} else {
			echo '<div class="wd-notice wd-info">' . esc_html( $media_array->get_error_message() ) . '</div>';
		}

		if ( $link != '' ) {
			?>
			<p class="clear"><a href="//www.instagram.com/<?php echo trim( $username ); ?>" rel="me" target="<?php echo esc_attr( $target ); ?>"><?php echo esc_html( $link ); ?></a></p>
			<?php
		}

		echo '</div>';

		$output = ob_get_contents();
		ob_end_clean();

		return $output;

	}
}

if ( ! function_exists( 'woodmart_pretty_number' ) ) {
	function woodmart_pretty_number( $x = 0 ) {
		$x = (int) $x;

		if ( $x > 1000000 ) {
			return floor( $x / 1000000 ) . 'M';
		}

		if ( $x > 10000 ) {
			return floor( $x / 1000 ) . 'k';
		}
		return $x;
	}
}

if ( ! function_exists( 'woodmart_scrape_instagram' ) ) {
	function woodmart_scrape_instagram( $username, $slice = 9, $ajax_body = false, $data_source = 'scrape' ) {
		$username       = strtolower( $username );
		$transient_name = 'instagram-media-new-' . sanitize_title_with_dashes( $username ) . '-' . $data_source;
		$instagram      = get_transient( $transient_name );

		if ( false === $instagram ) {
			if ( 'scrape' === $data_source || 'ajax' === $data_source ) {
				$instagram = woodmart_get_scrape_insta_images(
					array(
						'username'  => $username,
						'ajax_body' => $ajax_body,
					)
				);

			} elseif ( 'api' === $data_source ) {
				$instagram = woodmart_get_api_insta_images();
			}

			if ( is_wp_error( $instagram ) ) {
				return $instagram;
			}

			if ( ! empty( $instagram ) ) {
				$instagram = function_exists( 'woodmart_compress' ) ? woodmart_compress( maybe_serialize( $instagram ) ) : '';
				set_transient( $transient_name, $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS * 2 ) );
			}
		}

		if ( ! empty( $instagram ) ) {
			$instagram = function_exists( 'woodmart_decompress' ) ? maybe_unserialize( woodmart_decompress( $instagram ) ) : array();
			return array_slice( $instagram, 0, $slice );
		} else {
			return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'woodmart' ) );
		}
	}
}

if ( ! function_exists( 'woodmart_get_api_insta_images' ) ) {
	function woodmart_get_api_insta_images() {
		$instagram_account_id   = get_option( 'instagram_account_id' );
		$instagram_access_token = get_option( 'instagram_access_token' );

		if ( ! $instagram_access_token || ! $instagram_account_id ) {
			return new WP_Error( 'no_token', esc_html__( 'You need connect your Instagram account in Theme settings -> General -> Connect instagram account', 'woodmart' ) );
		}

		$images_data = wp_remote_get( 'https://graph.facebook.com/v5.0/' . $instagram_account_id . '/media?access_token=' . $instagram_access_token . '&fields=timestamp,caption,media_type,media_url,thumbnail_url,like_count,comments_count,permalink' );

		if ( is_wp_error( $images_data ) ) {
			return $images_data;
		}

		$images_data_decoded = json_decode( $images_data['body'] );

		if ( is_object( $images_data_decoded ) ) {
			if ( property_exists( $images_data_decoded, 'error' ) ) {
				return new WP_Error( 'no_images', $images_data_decoded->error->message );
			}
		} else {
			return new WP_Error( 'no_images', esc_html__( 'Instagram API did not return any images.', 'woodmart' ) );
		}

		$instagram = array();

		foreach ( $images_data_decoded->data as $image ) {
			$caption = esc_html__( 'Instagram Image', 'woodmart' );

			if ( isset( $image->caption ) ) {
				$caption = $image->caption;
			}

			if ( 'VIDEO' === $image->media_type ) {
				$image_url = $image->thumbnail_url;
			} else {
				$image_url = $image->media_url;
			}

			$instagram[] = array(
				'description' => $caption,
				'link'        => preg_replace( '/^https:/i', '', $image->permalink ),
				'large'       => preg_replace( '/^https:/i', '', $image_url ),
				'comments'    => $image->comments_count,
				'likes'       => $image->like_count,
				'type'        => $image->media_type,
			);
		}

		return $instagram;
	}
}

if ( ! function_exists( 'woodmart_get_scrape_insta_images' ) ) {
	function woodmart_get_scrape_insta_images( $data ) {
		$by_hashtag = substr( $data['username'], 0, 1 ) === '#';

		if ( ! $data['ajax_body'] ) {
			$request_param = $by_hashtag ? 'explore/tags/' . substr( $data['username'], 1 ) : trim( $data['username'] );
			$remote        = wp_remote_get( 'https://www.instagram.com/' . $request_param . '/' );

			if ( is_wp_error( $remote ) ) {
				return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'woodmart' ) );
			}

			if ( 200 != wp_remote_retrieve_response_code( $remote ) ) {
				return new WP_Error( 'invalid_response_' . wp_remote_retrieve_response_code( $remote ), esc_html__( 'Instagram did not return a 200.', 'woodmart' ) );
			}

			$shards = explode( 'window._sharedData = ', $remote['body'] );
		} else {
			$remote = stripslashes( $data['ajax_body'] );
			$shards = explode( 'window._sharedData = ', $remote );
		}

		if ( ! isset( $shards[1] ) ) {
			return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'woodmart' ) );
		}

		$insta_json  = explode( ';</script>', $shards[1] );
		$insta_array = json_decode( $insta_json[0], true );

		if ( ! $insta_array ) {
			return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'woodmart' ) );
		}

		if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
			$images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
		} elseif ( $by_hashtag && isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
			$images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
		} else {
			return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'woodmart' ) );
		}

		if ( ! is_array( $images ) ) {
			return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'woodmart' ) );
		}

		$instagram = array();

		foreach ( $images as $image ) {
			$image   = $image['node'];
			$caption = esc_html__( 'Instagram Image', 'woodmart' );
			if ( ! empty( $image['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
				$caption = $image['edge_media_to_caption']['edges'][0]['node']['text'];
			}

			$image['thumbnail_src'] = preg_replace( '/^https:/i', '', $image['thumbnail_src'] );
			$image['thumbnail']     = preg_replace( '/^https:/i', '', $image['thumbnail_resources'][0]['src'] );
			$image['medium']        = preg_replace( '/^https:/i', '', $image['thumbnail_resources'][2]['src'] );
			$image['large']         = $image['thumbnail_src'];

			$type = ( $image['is_video'] ) ? 'video' : 'image';

			$instagram[] = array(
				'description' => $caption,
				'link'        => '//www.instagram.com/p/' . $image['shortcode'] . '/',
				'comments'    => $image['edge_media_to_comment']['count'],
				'likes'       => $image['edge_liked_by']['count'],
				'thumbnail'   => $image['thumbnail'],
				'medium'      => $image['medium'],
				'large'       => $image['large'],
				'type'        => $type,
			);
		}

		return $instagram;
	}
}

if ( ! function_exists( 'woodmart_instagram_ajax_query' ) ) {
	function woodmart_instagram_ajax_query() {
		if ( ! empty( $_POST['atts'] ) && ! empty( $_POST['body'] ) ) {
			$atts = woodmart_clean( $_POST['atts'] );

			$atts['ajax_body'] = trim( $_POST['body'] );
			$data              = woodmart_shortcode_instagram( $atts );
			echo json_encode( $data );
			die();
		}
	}

	add_action( 'wp_ajax_woodmart_instagram_ajax_query', 'woodmart_instagram_ajax_query' );
	add_action( 'wp_ajax_nopriv_woodmart_instagram_ajax_query', 'woodmart_instagram_ajax_query' );
}


if ( ! function_exists( 'woodmart_get_instagram_custom_images' ) ) {
	function woodmart_get_instagram_custom_images( $images, $size, $link, $likes, $comments ) {
		if ( ! $images ) {
			return new WP_Error( 'no_images', esc_html__( 'You need to upload your images manually to the element if you want to load them from your website. Otherwise you will need to connect your real Instagram account via API.', 'woodmart' ) );
		}

		$images_output = array();

		$images   = explode( ',', $images );
		$likes    = explode( '-', $likes );
		$comments = explode( '-', $comments );

		foreach ( $images as $key => $image ) {
			$images_output[] = array(
				'image_id' => $image,
				'large'    => wp_get_attachment_image_url( $image, $size ),
				'link'     => $link,
				'likes'    => wp_rand( $likes[0], $likes[1] ),
				'comments' => wp_rand( $comments[0], $comments[1] ),
			);
		}

		return $images_output;
	}
}
