<?php
/**
 * Custom download API route
 *
 * @since 3.0
 * @author dcooney
 * @package InstantImages
 */

add_action(
	'rest_api_init',
	function () {
		$my_namespace = 'instant-images';
		$my_endpoint  = '/download';
		register_rest_route(
			$my_namespace,
			$my_endpoint,
			array(
				'methods'             => 'POST',
				'callback'            => 'instant_images_download',
				'permission_callback' => function () {
					return InstantImages::instant_img_has_access();
				},
			)
		);
	}
);

/**
 * Resize Image and run through media uploader process.
 *
 * @param WP_REST_Request $request Rest request object.
 * @return $response
 * @since 3.0
 * @author dcooney
 * @package InstantImages
 */
function instant_images_download( WP_REST_Request $request ) {

	if ( ! InstantImages::instant_img_has_access() ) {
		// Exit if not allowed.
		$response = array(
			'success'    => false,
			'msg'        => __( 'You do not have sufficient access to upload images with Instant Images.', 'instant-images' ),
			'id'         => '',
			'attachment' => '',
			'url'        => '',
		);
		wp_send_json( $response );

	}

	// Global settings.
	$options    = get_option( 'instant_img_settings' );
	$max_width  = isset( $options['unsplash_download_w'] ) ? $options['unsplash_download_w'] : 1600; // width.
	$max_height = isset( $options['unsplash_download_h'] ) ? $options['unsplash_download_h'] : 1200; // height.

	// Core WP includes.
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	// Get JSON Data.
	$data = json_decode( $request->get_body(), true ); // Get contents of request body.

	if ( $data ) {

		$id        = $data['id']; // Image ID.
		$image_url = $data['image_url'] . '&fit=clip&w=' . $max_width . '&h=' . $max_height; // Image URL.
		$filename  = sanitize_text_field( $data['filename'] ); // The filename.
		$title     = sanitize_text_field( $data['title'] ); // Title.
		$alt       = sanitize_text_field( $data['alt'] ); // Alt text.
		$caption   = sanitize_text_field( $data['caption'] ); // Caption text.
		$cfilename = sanitize_title( $data['custom_filename'] ); // Custom filename.
		$parent_id = ( $data['parent_id'] ) ? sanitize_title( $data['parent_id'] ) : 0; // Parent post ID.
		$name      = ( ! empty( $cfilename ) ) ? $cfilename . '.jpg' : $filename; // Actual filename.

		// Check if remote file exists.
		if ( ! instant_images_remote_file_exists( $image_url ) ) {
			// Errorhandling.
			$response = array(
				'success'    => false,
				'msg'        => __( 'Image does not exist or there was an error accessing the remote file.', 'instant-images' ),
				'id'         => $id,
				'attachment' => '',
				'admin_url'  => admin_url(),
			);
			wp_send_json( $response );
		}

		// Send request to `wp_remote_get`.
		$response = wp_remote_get( $image_url );
		if ( is_wp_error( $response ) ) {
			return new WP_Error( 100, __( 'Image download failed, please try again. Errors:', 'instant-images' ) . PHP_EOL . $response->get_error_message() );
		}

		// Get Headers.
		$type = wp_remote_retrieve_header( $response, 'content-type' );
		if ( ! $type ) {
			return new WP_Error( 100, __( 'Image type could not be determined', 'instant-images' ) );
		}

		// Upload remote file.
		$mirror = wp_upload_bits( $name, null, wp_remote_retrieve_body( $response ) );

		// Build Attachment Data Array.
		$attachment = array(
			'post_title'     => $title,
			'post_excerpt'   => $caption,
			'post_content'   => '',
			'post_status'    => 'inherit',
			'post_mime_type' => $type,
		);

		// Insert as attachment.
		$image_id = wp_insert_attachment( $attachment, $mirror['file'], $parent_id );

		// Add Alt Text as Post Meta.
		update_post_meta( $image_id, '_wp_attachment_image_alt', $alt );

		// Generate Metadata.
		$attach_data = wp_generate_attachment_metadata( $image_id, $mirror['file'] );
		wp_update_attachment_metadata( $image_id, $attach_data );

		/**
		 * Instant Images Core Hook
		 * Fired after a successful image upload to media library.
		 *
		 * @since 4.4.0
		 */
		do_action(
			'instant_images_after_upload',
			array(
				'filename'       => $name,
				'unsplash_id'    => $id,
				'attachment_id'  => $image_id,
				'attachment_url' => wp_get_attachment_url( $image_id ),
			)
		);

		// Resize original image to max size (set in Instant Images settings).
		// @deprecated in .
		// instant_images_resize_download( $name ); .

		// Success.
		$response = array(
			'success'    => true,
			'msg'        => __( 'Image successfully uploaded to the media library!', 'instant-images' ),
			'id'         => $id,
			'admin_url'  => admin_url(),
			'attachment' => array(
				'id'      => $image_id,
				'url'     => wp_get_attachment_url( $image_id ),
				'alt'     => $alt,
				'caption' => $caption,
			),
		);

		wp_send_json( $response );

	} else {

		$response = array(
			'success'    => false,
			'msg'        => __( 'There was an error getting image details from the request, please try again.', 'instant-images' ),
			'id'         => '',
			'attachment' => '',
			'url'        => '',
		);

		wp_send_json( $response );

	}
}


/**
 * Check if a remote image file exists.
 *
 * @param string $url The url to the remote image.
 * @return bool Whether the remote image exists.
 * @since 3.0
 * @author dcooney
 * @package InstantImages
 */
function instant_images_remote_file_exists( $url ) {
	$response = wp_remote_head( $url );
	return 200 === wp_remote_retrieve_response_code( $response );
}



/**
 * Resize original image to max size (set in Instant Images settings)
 *
 * @param string $filename the image filename.
 * @since 3.0
 * @author dcooney
 * @package InstantImages
 */
function instant_images_resize_download( $filename ) {

	$options    = get_option( 'instant_img_settings' );
	$download_w = isset( $options['unsplash_download_w'] ) ? $options['unsplash_download_w'] : 1600; // width.
	$download_h = isset( $options['unsplash_download_h'] ) ? $options['unsplash_download_h'] : 1200; // height.

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$uploads_dir    = wp_upload_dir();
	$original_image = wp_get_image_editor( $uploads_dir['path'] . '/' . $filename );
	if ( ! is_wp_error( $original_image ) ) {
		$original_image->resize( $download_w, $download_h, false );
		$original_image->save( $uploads_dir['path'] . '/' . $filename );
	}
}
