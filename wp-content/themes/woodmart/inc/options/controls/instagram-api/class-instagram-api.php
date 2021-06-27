<?php
/**
 * Instagram API control.
 *
 * @package xts
 */

namespace XTS\Options\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

use WP_Error;
use XTS\Options\Field;

/**
 * Input type text field control.
 */
class Instagram_Api extends Field {
	/**
	 * Displays the field control HTML.
	 *
	 * @since 1.0.0
	 *
	 * @return void.
	 */
	public function render_control() {
		if ( isset( $_GET['instagram_account_id'] ) && isset( $_GET['instagram_access_token'] ) ) {
			echo '<div class="xts-notice xts-success">Access token generated</div>';
			$this->connect();
		}

		if ( isset( $_GET['instagram_account_disconnect'] ) ) {
			$this->disconnect();
		}

		$instagram_access_token = get_option( 'instagram_access_token' );

		if ( $instagram_access_token ) {
			$this->show_connected_account();
		}

		?>
			<div class="xts-upload-btns">
				<a href="<?php echo esc_url( $this->get_link() ); ?>" class="xts-btn xts-btn-primary">
					<?php esc_html_e( 'Connect', 'woodmart' ); ?>
				</a>

				<?php if ( $instagram_access_token ) : ?>
					<a href="<?php echo $this->get_return_url() . '&instagram_account_disconnect'; ?>" class="xts-btn xts-remove-upload-btn">
						<?php esc_html_e( 'Disconnect', 'woodmart' ); ?>
					</a>
				<?php endif; ?>
			</div>
		<?php
	}

	/**
	 *
	 */
	public function connect() {
		update_option( 'instagram_account_id', $_GET['instagram_account_id'] );
		update_option( 'instagram_access_token', $_GET['instagram_access_token'] );
	}

	/**
	 *
	 */
	public function disconnect() {
		$instagram_account_id = get_option( 'instagram_account_id' );
		delete_option( 'instagram_account_data_' . $instagram_account_id );
		delete_option( 'instagram_account_id' );
		delete_option( 'instagram_access_token' );
	}

	/**
	 * @return string
	 */
	public function get_return_url() {
		return admin_url( 'admin.php' ) . '?page=xtemos_options&tab=instagram_section';
	}

	/**
	 * @return string
	 */
	public function get_link() {
		$app_id        = '420748032186288';
		$base_url      = 'https://www.facebook.com/v5.0/dialog/oauth';
		$redirect_uri  = urlencode( 'https://xtemos.com/instagram.php' );
		$response_type = 'code';
		$scope         = 'manage_pages,instagram_basic,public_profile';
		$return_url    = urlencode( $this->get_return_url() );

		return $base_url . '?response_type=' . $response_type . '&client_id=' . $app_id . '&redirect_uri=' . $redirect_uri . '&scope=' . $scope . '&state=' . $return_url;
	}

	/**
	 *
	 */
	public function show_connected_account() {
		$instagram_account_id   = get_option( 'instagram_account_id' );
		$instagram_account_data = get_option( 'instagram_account_data_' . $instagram_account_id );

		if ( ! $instagram_account_data ) {
			$instagram_account_data = $this->get_connected_account_data();
		}

		if ( ! is_array( $instagram_account_data ) ) {
			return;
		}

		?>
		<div class="xts-instagram-profile">
			<div class="xts-instagram-pic"><img src="<?php echo $instagram_account_data['image']; ?>" alt=""></div>
			<div class="xts-instagram-name"><?php echo $instagram_account_data['name']; ?> : <span>@<?php echo $instagram_account_data['username']; ?></span></div>
		</div>
		<?php
	}

	/**
	 * @return array|void
	 */
	public function get_connected_account_data() {
		$instagram_access_token = get_option( 'instagram_access_token' );
		$instagram_account_id   = get_option( 'instagram_account_id' );

		$account_data = wp_remote_get( 'https://graph.facebook.com/' . $instagram_account_id . '?fields=biography,id,username,website,followers_count,media_count,profile_picture_url,name&access_token=' . $instagram_access_token );
		$data_decoded = json_decode( $account_data['body'] );

		if ( is_object( $data_decoded ) && property_exists( $data_decoded, 'error' ) ) {
			echo 'Get connected account data :' . $data_decoded->error->message;
			return;
		}

		$data = array(
			'image'    => $data_decoded->profile_picture_url,
			'name'     => $data_decoded->name,
			'username' => $data_decoded->username,
		);

		update_option(
			'instagram_account_data_' . $instagram_account_id,
			$data
		);

		return $data;
	}
}


