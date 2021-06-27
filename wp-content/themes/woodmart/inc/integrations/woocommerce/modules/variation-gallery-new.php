<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

if ( 'new' !== woodmart_get_opt( 'variation_gallery_storage_method', 'old' ) ) {
	return;
}

if ( ! function_exists( 'woodmart_avi_save_images' ) ) {
	function woodmart_avi_save_images( $variation_id ) {
		if ( isset( $_POST['wd_additional_variation_images'] ) ) { // phpcs:ignore
			if ( isset( $_POST['wd_additional_variation_images'][ $variation_id ] ) ) { // phpcs:ignore
				$ids = sanitize_text_field( wp_unslash( $_POST['wd_additional_variation_images'][ $variation_id ] ) ); // phpcs:ignore
				update_post_meta( $variation_id, 'wd_additional_variation_images_data', $ids );
			} else {
				delete_post_meta( $variation_id, 'wd_additional_variation_images_data' );
			}
		} else {
			delete_post_meta( $variation_id, 'wd_additional_variation_images_data' );
		}
	}

	add_action( 'woocommerce_save_product_variation', 'woodmart_avi_save_images', 10, 2 );
}

if ( ! function_exists( 'woodmart_avi_get_attachments_data' ) ) {
	function woodmart_avi_get_attachments_data( $variation ) {
		$attachments      = woodmart_avi_get_attachments( $variation );
		$attachments_data = array();

		if ( ! $attachments ) {
			return $attachments_data;
		}

		foreach ( $attachments as $attachment_id ) {
			$attachments_data[] = array(
				'id'  => $attachment_id,
				'url' => wp_get_attachment_image_src( $attachment_id ),
			);
		}

		return $attachments_data;
	}
}

if ( ! function_exists( 'woodmart_avi_get_attachments' ) ) {
	function woodmart_avi_get_attachments( $variation ) {
		$images_data = get_post_meta( $variation->ID, 'wd_additional_variation_images_data', true );

		if ( ! $images_data ) {
			return array();
		}

		return array_filter( explode( ',', $images_data ) );
	}
}

if ( ! function_exists( 'woodmart_avi_admin_html' ) ) {
	function woodmart_avi_admin_html( $loop, $variation_data, $variation ) {
		if ( ! woodmart_get_opt( 'variation_gallery' ) ) {
			return;
		}

		?>
		<div class="woodmart-variation-gallery-wrapper">
			<h4>
				<?php esc_html_e( 'Variation Image Gallery', 'woodmart' ); ?>
			</h4>
			
			<ul class="woodmart-variation-gallery-images">
				<?php foreach ( woodmart_avi_get_attachments_data( $variation ) as $attachment ) : ?>
					<li class="image" data-attachment_id="<?php echo esc_attr( $attachment['id'] ); ?>">
						<img src="<?php echo esc_attr( $attachment['url'][0] ); ?>"
							 width="<?php echo esc_attr( $attachment['url'][1] ); ?>"
							 height="<?php echo esc_attr( $attachment['url'][2] ); ?>" alt="variation image">
						
						<a href="#" class="delete woodmart-remove-variation-gallery-image">
							<span class="dashicons dashicons-dismiss"></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			
			<input type="hidden" class="variation-gallery-ids"
				   name="wd_additional_variation_images[<?php echo esc_attr( $variation->ID ); ?>]"
				   value="<?php echo esc_attr( implode( ',', woodmart_avi_get_attachments( $variation ) ) ); ?>">
			
			<a href="#" class="button woodmart-add-variation-gallery-image">
				<?php esc_html_e( 'Add image', 'woodmart' ); ?>
			</a>
		</div>
		<?php
	}

	add_action( 'woocommerce_variation_options', 'woodmart_avi_admin_html', 10, 3 );
}

function woodmart_avi_update_available_variation( $available_variation ) {
	if ( ! woodmart_get_opt( 'variation_gallery' ) ) {
		return $available_variation;
	}

	$product_id          = get_the_ID();
	$default_images_data = woodmart_avi_get_default_data( $product_id );
	$variation_id        = $available_variation['variation_id'];
	$images_data         = get_post_meta( $variation_id, 'wd_additional_variation_images_data', true );
	$ids                 = array_filter( explode( ',', $images_data ) );

	if ( has_post_thumbnail( $variation_id ) ) {
		$available_variation['additional_variation_images'][] = woodmart_avi_get_image_data( get_post_thumbnail_id( $variation_id ), true );
	}

	foreach ( $ids as $id ) {
		$available_variation['additional_variation_images'][] = woodmart_avi_get_image_data( $id );
	}

	if ( $default_images_data ) {
		$available_variation['additional_variation_images_default'] = $default_images_data;
	}

	return $available_variation;
}

add_filter( 'woocommerce_available_variation', 'woodmart_avi_update_available_variation', 10 );

function woodmart_avi_get_default_data( $product_id ) {
	$product = wc_get_product( $product_id );

	if ( ! $product ) {
		return '';
	}

	$default_image_ids = $product->get_gallery_image_ids();
	$images            = array();

	if ( has_post_thumbnail( $product_id ) ) {
		$images[] = woodmart_avi_get_image_data( get_post_thumbnail_id( $product_id ), true );
	}

	if ( $default_image_ids && is_array( $default_image_ids ) ) {
		foreach ( $default_image_ids as $id ) {
			$images[] = woodmart_avi_get_image_data( $id );
		}
	}

	return $images;
}

if ( ! function_exists( 'woodmart_avi_get_image_data' ) ) {
	function woodmart_avi_get_image_data( $attachment_id, $main_image = false ) {
		woodmart_lazy_loading_deinit( true );

		$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
		$thumbnail_size    = apply_filters(
			'woocommerce_gallery_thumbnail_size',
			array(
				$gallery_thumbnail['width'],
				$gallery_thumbnail['height'],
			)
		);
		$image_size        = 'woocommerce_single';
		$full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
		$thumbnail_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
		$full_src          = wp_get_attachment_image_src( $attachment_id, $full_size );
		$image_src         = wp_get_attachment_image_src( $attachment_id, $image_size );
		$class             = esc_attr( $main_image ? 'wp-post-image' : '' );

		$output = array(
			'width'                   => $image_src[1],
			'height'                  => $image_src[2],
			'src'                     => $image_src[0],
			'full_src'                => $full_src[0],
			'thumbnail_src'           => $thumbnail_src[0],
			'class'                   => apply_filters( 'woodmart_single_product_gallery_image_class', $class ),
			'alt'                     => trim( wp_strip_all_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) ),
			'title'                   => _wp_specialchars( get_post_field( 'post_title', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
			'data_caption'            => _wp_specialchars( get_post_field( 'post_excerpt', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
			'data_src'                => esc_url( $full_src[0] ),
			'data_large_image'        => esc_url( $full_src[0] ),
			'data_large_image_width'  => esc_attr( $full_src[1] ),
			'data_large_image_height' => esc_attr( $full_src[2] ),
		);

		$image_meta = wp_get_attachment_metadata( $attachment_id );

		if ( is_array( $image_meta ) ) {
			$size_array = array( absint( $image_src[1] ), absint( $image_src[2] ) );
			$srcset     = wp_calculate_image_srcset( $size_array, $image_src[0], $image_meta, $attachment_id );
			$sizes      = wp_calculate_image_sizes( $size_array, $image_src[0], $image_meta, $attachment_id );

			if ( $srcset && ( $sizes || ! empty( $attr['sizes'] ) ) ) {
				$output['srcset'] = $srcset;

				if ( empty( $attr['sizes'] ) ) {
					$output['sizes'] = $sizes;
				}
			}
		}

		woodmart_lazy_loading_init();

		return $output;
	}
}
