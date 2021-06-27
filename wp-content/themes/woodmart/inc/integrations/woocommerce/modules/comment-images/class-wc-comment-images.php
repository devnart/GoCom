<?php
/**
 * Comment images class.
 *
 * @package xts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}

use XTS\Metaboxes;
use XTS\Options;

/**
 * Comment images class.
 *
 * @since 1.0.0
 */
class WC_Comment_Images {
	/**
	 * The meta key of the attachment ID for comment meta.
	 *
	 * @var string $image_meta_key The attachment ID meta key.
	 */
	private $image_meta_key = '_woodmart_image_id';

	/**
	 * The name of the upload field used in the commenting form.
	 *
	 * @var string $upload_field_name The name of the upload input.
	 */
	private $upload_field_name = 'woodmart_image';

	/**
	 * Class basic constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Set up all actions.
	 *
	 * @since 1.0.0
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'add_options' ), 10 );
		add_action( 'init', array( $this, 'add_metaboxes' ), 20 );
		add_action( 'comment_form_submit_field', array( $this, 'add_image_field' ), 10 );
		add_filter( 'preprocess_comment', array( $this, 'check_images' ), 10 );
		add_action( 'comment_post', array( $this, 'save_image' ), 10, 3 );
		add_filter( 'comment_text', array( $this, 'display_images' ), 40 );
		add_action( 'delete_comment', array( $this, 'delete_image' ), 10 );
	}

	/**
	 * Enables filtering of the standard list of allowed mime types and file extensions.
	 *
	 * @since 1.1.0
	 */
	public function enable_filter_upload() {
		add_filter( 'upload_mimes', array( $this, 'filter_upload_mimes' ), 200 );
	}

	/**
	 * Disables filtering of the standard list of allowed mime types and file extensions.
	 *
	 * @since 1.1.0
	 */
	public function disable_filter_upload() {
		remove_filter( 'upload_mimes', array( $this, 'filter_upload_mimes' ), 200 );
	}

	/**
	 * Filters a standard list of allowed mime types and file extensions.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function filter_upload_mimes() {
		$mimes = array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'png'          => 'image/png',
		);

		return apply_filters( 'woodmart_comment_images_upload_mimes', $mimes );
	}

	/**
	 * Gets the meta key of the attachment ID for comment meta.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_images_meta_key() {
		return $this->image_meta_key;
	}

	/**
	 * Gets the name of the upload field used in the commenting form.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_upload_field_name() {
		return $this->upload_field_name;
	}

	/**
	 * Gets max upload file size.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $with_format Optional.
	 *
	 * @return integer|string
	 */
	public function get_max_upload_size( $with_format = false ) {
		$max_upload_size = (int) woodmart_get_opt( 'single_product_comment_images_upload_size', '1' ) * MB_IN_BYTES;

		if ( $with_format ) {
			return size_format( $max_upload_size );
		}

		return $max_upload_size;
	}

	/**
	 * Add metaboxes
	 *
	 * @since 1.0.0
	 */
	public function add_metaboxes() {
		if ( ! woodmart_get_opt( 'single_product_comment_images', '1' ) ) {
			return;
		}

		$ids = '';
		if ( isset( $_GET['c'] ) ) { // phpcs:ignore
			$ids = $this->get_image_ids_meta( $_GET['c'] ); // phpcs:ignore
		}

		$metaboxes = Metaboxes::add_metabox(
			array(
				'id'     => 'woodmart_comment_images_metabox',
				'title'  => esc_html__( 'Review images metabox', 'woodmart' ),
				'object' => array( 'comment' ),
			)
		);

		$metaboxes->add_section(
			array(
				'id'       => 'general',
				'name'     => esc_html__( 'General', 'woodmart' ),
				'icon'     => WOODMART_ASSETS . '/assets/images/dashboard-icons/settings.svg',
				'priority' => 10,
			)
		);

		$metaboxes->add_field(
			array(
				'id'          => '_woodmart_image_id',
				'type'        => 'upload_list',
				'section'     => 'general',
				'tags'        => 'some tags for search',
				'name'        => esc_html__( 'Upload list', 'woodmart' ),
				'group'       => esc_html__( 'Review images', 'woodmart' ),
				'default'     => $ids,
				'priority'    => 10,
			)
		);
	}

	/**
	 * Add options
	 *
	 * @since 1.0.0
	 */
	public function add_options() {
		Options::add_section(
			array(
				'id'       => 'single_product_comments_section',
				'name'     => esc_html__( 'Reviews', 'woodmart' ),
				'parent'   => 'product',
				'priority' => 50,
				'icon'     => WOODMART_ASSETS . '/assets/images/dashboard-icons/settings.svg',
			)
		);

		Options::add_field(
			array(
				'id'          => 'single_product_comment_images',
				'type'        => 'switcher',
				'section'     => 'single_product_comments_section',
				'tags'        => 'some tags for search',
				'name'        => esc_html__( 'Review images', 'woodmart' ),
				'description' => esc_html__( 'Allow customers to upload images to their reviews for products', 'woodmart' ),
				'default'     => false,
				'priority'    => 10,
			)
		);

		Options::add_field(
			array(
				'id'          => 'single_product_comment_images_count',
				'type'        => 'range',
				'section'     => 'single_product_comments_section',
				'tags'        => 'some tags for search',
				'name'        => esc_html__( 'Images count in one review', 'woodmart' ),
				'description' => esc_html__( 'How many images customers can add to each review', 'woodmart' ),
				'min'         => 1,
				'max'         => 20,
				'step'        => 1,
				'default'     => 3,
				'priority'    => 20,
			)
		);

		Options::add_field(
			array(
				'id'          => 'single_product_comment_images_upload_size',
				'type'        => 'text_input',
				'section'     => 'single_product_comments_section',
				'tags'        => 'some tags for search',
				'name'        => esc_html__( 'Maximum upload file size', 'woodmart' ),
				'description' => esc_html__( 'Set the value in megabytes. Currently your server allows you to upload files up to 64 MB.', 'woodmart' ),
				'default'     => '1',
				'priority'    => 30,
			)
		);

		Options::add_field(
			array(
				'id'          => 'single_product_comment_images_required',
				'type'        => 'switcher',
				'section'     => 'single_product_comments_section',
				'tags'        => 'some tags for search',
				'name'        => esc_html__( 'Is images required?', 'woodmart' ),
				'description' => esc_html__( 'If checked, the user will not be able to post a comment without attaching an image.', 'woodmart' ),
				'default'     => '0',
				'priority'    => 40,
			)
		);
	}

	/**
	 * Checks if a comment has an attachment.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $comment_id Optional. The comment ID.
	 *
	 * @return bool
	 */
	public function has_images( $comment_id = 0 ) {
		if ( ! $comment_id ) {
			$comment_id = $this->get_comment_ID();
		}

		$attachment_ids = $this->get_image_ids_array( $comment_id );

		if ( ! $attachment_ids ) {
			return false;
		}

		return true;
	}

	/**
	 * Gets an assigned attachment ID.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $comment_id Optional. The comment ID.
	 *
	 * @return string
	 */
	public function get_image_ids_meta( $comment_id = 0 ) {
		$meta_key = $this->get_images_meta_key();

		if ( ! $comment_id ) {
			$comment_id = $this->get_comment_ID();
		}

		return get_comment_meta( $comment_id, $meta_key, true );
	}

	/**
	 * Gets an assigned attachment ID.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $comment_id Optional. The comment ID.
	 *
	 * @return array
	 */
	public function get_image_ids_array( $comment_id = 0 ) {
		$ids = explode( ',', $this->get_image_ids_meta( $comment_id ) );

		return $ids;
	}

	/**
	 * Retrieve the comment id of the current comment.
	 *
	 * @since 1.5.0
	 *
	 * @return int The comment ID.
	 */
	public function get_comment_ID() {
		$comment = get_comment();

		if ( ! $comment ) {
			return '';
		}

		return $comment->comment_ID;
	}


	/**
	 * Displays an assigned attachment.
	 *
	 * @since 1.0.0
	 *
	 * @param string $comment_content Text of the comment.
	 *
	 * @return string
	 */
	public function display_images( $comment_content ) {
		if ( ! $this->has_images() || is_admin() || ! woodmart_get_opt( 'single_product_comment_images', '1' ) || ! is_singular( 'product' ) ) {
			return $comment_content;
		}

		ob_start();

		echo woodmart_images_gallery_shortcode(
			array(
				'ids'      => $this->get_image_ids_meta(),
				'columns'  => 5,
				'img_size' => 'thumbnail',
				'spacing'  => 10,
			)
		);

		$image = ob_get_clean();

		return $comment_content . $image;
	}

	/**
	 * Deletes an assigned attachment.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $comment_id The comment ID.
	 */
	public function delete_image( $comment_id ) {
		if ( ! $this->has_images( $comment_id ) ) {
			return;
		}

		$image_ids = $this->get_image_ids_array( $comment_id );
		$meta_key  = $this->get_images_meta_key();

		foreach ( $image_ids as $id ) {
			wp_delete_attachment( $id, true );
		}

		delete_comment_meta( $comment_id, $meta_key );
	}

	/**
	 * Saves attachment after comment is posted.
	 *
	 * @since 1.0.0
	 *
	 * @param integer        $comment_id       The comment ID.
	 * @param integer|string $comment_approved 1 if the comment is approved, 0 if not, 'spam' if spam.
	 * @param array          $comment          Comment data.
	 */
	public function save_image( $comment_id, $comment_approved, $comment ) {
		if ( ! is_user_logged_in() || ! woodmart_get_opt( 'single_product_comment_images', '1' ) ) {
			return;
		}

		$field_name = $this->get_upload_field_name();

		if ( ! function_exists( 'media_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
		}
		
		$image_ids = '';

		if ( $_FILES ) {
			$files = $_FILES[ $field_name ]; // phpcs:ignore
			foreach ( $files['name'] as $key => $value ) {
				if ( $files['name'][ $key ] ) {
					$file = array(
						'name'     => $files['name'][ $key ],
						'type'     => $files['type'][ $key ],
						'tmp_name' => $files['tmp_name'][ $key ],
						'error'    => $files['error'][ $key ],
						'size'     => $files['size'][ $key ],
					);

					$_FILES = array( $field_name => $file );

					add_filter( 'intermediate_image_sizes', array( $this, 'get_image_sizes' ), 10 );
					$attachment_id = media_handle_upload( $field_name, $comment['comment_post_ID'] );
					remove_filter( 'intermediate_image_sizes', array( $this, 'get_image_sizes' ), 10 );

					if ( ! is_wp_error( $attachment_id ) ) {
						$image_ids .= $attachment_id . ',';
					}
				}
			}

			$this->assign_images( $comment_id, $image_ids );
		}
	}

	/**
	 * Assigns an attachment for the comment.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_image_sizes() {
		$sizes = array( 'thumbnail' );

		return $sizes;
	}

	/**
	 * Assigns an attachment for the comment.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $comment_id The comment ID.
	 * @param string  $image_ids  The attachment IDs.
	 *
	 * @return integer|bool
	 */
	public function assign_images( $comment_id, $image_ids ) {
		$meta_key = $this->get_images_meta_key();

		return update_comment_meta( $comment_id, $meta_key, $image_ids );
	}

	/**
	 * Checks the attachment before posting a comment.
	 *
	 * @since 1.0.0
	 *
	 * @param array $comment_data Comment data.
	 *
	 * @return array
	 */
	public function check_images( $comment_data ) {
		if ( ! is_user_logged_in() || ! woodmart_get_opt( 'single_product_comment_images', '1' ) ) {
			return $comment_data;
		}

		$field_name   = $this->get_upload_field_name();
		$images_count = woodmart_get_opt( 'single_product_comment_images_count', '3' );

		if ( ! isset( $_FILES[ $field_name ] ) ) {
			return $comment_data;
		}

		$attachments = $_FILES[ $field_name ]; // phpcs:ignore
		$names       = $attachments['name'];

		if ( is_array( $names ) && count( $names ) > $images_count ) {
			wp_die( sprintf( esc_html__( 'You can upload up to %s images to your review.', 'woodmart' ), $images_count ) ); // phpcs:ignore
		}

		foreach ( $attachments['size'] as $size ) {
			if ( $size > $this->get_max_upload_size() ) {
				wp_die( sprintf( esc_html__( 'The maximum upload file size: %s.', 'woodmart' ), $this->get_max_upload_size( true ) ) ); // phpcs:ignore
			}
		}

		if ( is_array( $names ) && isset( $names[0] ) && ! $names[0] && woodmart_get_opt( 'single_product_comment_images_required' ) ) {
			wp_die( esc_html__( 'Image is required.', 'woodmart' ) );
		}

		if ( woodmart_get_opt( 'single_product_comment_images_required' ) ) {
			$this->enable_filter_upload();
			foreach ( $names as $name ) {
				$filetype = wp_check_filetype( $name );

				if ( ! $filetype['ext'] ) {
					wp_die( sprintf( esc_html__( 'You are allowed to upload images only in %s formats.', 'woodmart' ), apply_filters( 'woodmart_comment_images_upload_mimes', 'png, jpeg' ) ) ); // phpcs:ignore
				}
			}
			$this->disable_filter_upload();
		}

		return $comment_data;
	}

	/**
	 * Gets the meta key of the attachment ID for comment meta.
	 *
	 * @since 1.0.0
	 *
	 * @param string $submit_field Default html.
	 *
	 * @return string
	 */
	public function add_image_field( $submit_field ) {
		if ( ! woodmart_get_opt( 'single_product_comment_images', '1' ) || ! is_singular( 'product' ) ) {
			return $submit_field;
		}

		if ( ! is_user_logged_in() ) {
			return '<p class="comment-form-img-message">' . esc_html__( 'You have to be logged in to be able to add photos to your review.', 'woodmart' ) . '</p>' . $submit_field;
		}

		$name            = $this->get_upload_field_name();
		$max_upload_size = $this->get_max_upload_size( true );
		$required        = woodmart_get_opt( 'single_product_comment_images_required' );

		woodmart_enqueue_js_script( 'btns-tooltips' );
		woodmart_enqueue_js_script( 'comment-image' );

		ob_start();

		?>
		<div class="comment-form-images">
			<div class="wd-add-img-btn-wrapper">
				<label for="wd-add-img-btn">
					<?php esc_html_e( 'Click to add images', 'woodmart' ); ?>
					<?php if ( $required ) : ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>

				<input id="wd-add-img-btn" name="<?php echo esc_attr( $name ); ?>[]" type="file" multiple <?php echo $required ? 'required' : ''; ?> />

				<div class="wd-add-img-msg woodmart-css-tooltip wd-tltp-top">
					<div class="wd-add-img-msg-text">
						<?php printf( esc_html__( 'The maximum file size is %s and you can upload up to %s images.', 'woodmart' ), $max_upload_size, woodmart_get_opt( 'single_product_comment_images_count' ) ); // phpcs:ignore ?>
					</div>
				</div>

				<div class="wd-add-img-count"></div>
			</div>
		</div>
		<?php

		$file_field = ob_get_clean();

		return $file_field . $submit_field;
	}
}

new WC_Comment_Images();
