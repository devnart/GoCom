<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * Notices helper class
 */

class WOODMART_Notices {
	public $notices;
	public $ignore_key = '';

	public function __construct() {
		$this->notices = array();

		add_action( 'admin_init', array( $this, 'nag_ignore' ) );
		add_action( 'admin_notices', array( $this, 'add_notice' ), 50 );
	}
	
	public function add_msg( $msg, $type, $global = false ) {
		$this->notices[] = array(
			'msg' => $msg,
			'type' => $type,
			'global' => $global
		);

		$this->nag_ignore();
	}

	public function get_msgs( $globals = false ) {
		if ( $globals ) {
			return array_filter( $this->notices, function( $v ) {
				return $v['global'];
			} );
		}

		return $this->notices;
	}

	public function clear_msgs( $globals = true ) {
		if ( $globals ) {
			$this->notices = array_filter( $this->notices, function( $v ) {
				return ! $v['global'];
			} );
		} else {
			$this->notices = array();
		}
	}

	public function show_msgs( $globals = false ) {
		$msgs = $this->get_msgs( $globals );
		if ( ! empty( $msgs ) ) {
			foreach ( $msgs as $key => $msg ) {
				if ( ! $globals && $msg['global'] ) {
					continue;
				}
				echo '<div class="woodmart-msg">';
					echo '<p class="woodmart-' . $msg['type'] . '">' . $msg['msg'] . '</p>';
				echo '</div>';
			}
		}

		$this->clear_msgs( $globals );
	}

	public function add_notice() {
		$msgs = $this->get_msgs( true );
		global $current_user;

		$user_id = $current_user->ID;

		if ( ! empty( $msgs ) ) {
			foreach ( $msgs as $key => $msg ) {
				$hash = md5( serialize( $msg['msg'] ) );
				if ( get_user_meta( $user_id, $hash ) ) {
					continue;
				}
				echo '<div class="woodmart-msg updated">';
					echo '<p class="woodmart-msg-' . $msg['type'] . '">' . $msg['msg'] . '</p>';
					echo '<a href="' . esc_url( wp_nonce_url( add_query_arg( 'woodmart-hide-notice', $hash ) ) ) . '">Dismiss Notice</a>';
				echo '</div>';
			}
		}
	}
	
	public function add_error( $msg, $global = false ) {
		$this->add_msg( $msg, 'error', $global );
	}

	public function add_warning( $msg, $global = false ) {
		$this->add_msg( $msg, 'warning', $global );
	}

	public function add_success( $msg, $global = false ) {
		$this->add_msg( $msg, 'success', $global );
	}

	public function nag_ignore() {
		if ( ! isset( $_GET['woodmart-hide-notice'] ) ) {
			return;
		}
		global $current_user;
		$user_id = $current_user->ID;

		$hide_notice = sanitize_text_field( wp_unslash( $_GET['woodmart-hide-notice'] ) );

		//delete_user_meta($user_id, $this->ignore_key);
		/* If user clicks to ignore the notice, add that to their user meta */
		if ( $hide_notice ) {
			add_user_meta( $user_id, $hide_notice, true );
		}
	}
}
