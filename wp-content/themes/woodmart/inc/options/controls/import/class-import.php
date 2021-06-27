<?php
/**
 * Import/export options control.
 *
 * @package xts
 */

namespace XTS\Options\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

use XTS\Options\Field;

/**
 * Import and export theme options control class.
 */
class Import extends Field {
	/**
	 * Contruct the object.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $args     Field args array.
	 * @param arary  $options  Options from the database.
	 * @param string $type     Field type.
	 * @param string $object   Post or term.
	 */
	public function __construct( $args, $options, $type = 'options', $object = 'post' ) {
		parent::__construct( $args, $options, $type, $object );

		add_action( 'wp_ajax_xts_download_export_options', array( $this, 'download_options_export' ), 10 );
		add_action( 'wp_ajax_nopriv_xts_download_export_options', array( $this, 'download_options_export' ), 10 );
	}

	/**
	 * Displays the field control HTML.
	 *
	 * @since 1.0.0
	 *
	 * @return void.
	 */
	public function render_control() {
		?>
			<textarea class="xts-import-area" name="<?php echo esc_attr( $this->get_input_name() ); ?>"></textarea>
			<button class="xts-save-options-btn xts-btn xts-btn-primary" type="submit" name="xts-<?php echo esc_attr( $this->opt_name ); ?>-options[import-btn]" value="1"><?php esc_html_e( 'Import', 'woodmart' ); ?></button>
			<a class="xts-save-options-btn xts-btn xts-btn-primary" href="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>?action=xts_download_export_options&secret=<?php echo esc_attr( $this->generete_secret() ); ?>"><?php esc_html_e( 'Export options', 'woodmart' ); ?></a>
		<?php
	}

	/**
	 * Displays the field control HTML.
	 *
	 * @since 1.0.0
	 *
	 * @param string $subkey Subkey value.
	 */
	public function get_field_value( $subkey = false ) {
		return $this->get_options_json();
	}

	/**
	 * Download options export.
	 *
	 * @since 1.0.0
	 */
	public function download_options_export() {
		if ( ! isset( $_GET['secret'] ) || $_GET['secret'] != $this->generete_secret() ) {
			wp_die( 'Something wrong with the key.' );
			exit;
		}

		$json = $this->get_options_json();

		header( 'Content-Description: File Transfer' );
		header( 'Content-type: application/txt' );
		header( 'Content-Disposition: attachment; filename="xts_options_backup_' . date( 'd-m-Y' ) . '.json"' );
		header( 'Content-Transfer-Encoding: binary' );
		header( 'Expires: 0' );
		header( 'Cache-Control: must-revalidate' );
		header( 'Pragma: public' );

		echo $json; // phpcs:ignore

		wp_die();
	}

	/**
	 * Generate secret key.
	 *
	 * @since 1.0.0
	 */
	public function generete_secret() {
		return md5( md5( AUTH_KEY . SECURE_AUTH_KEY ) . '-woodmart' );
	}

	/**
	 * Get options json.
	 *
	 * @since 1.0.0
	 */
	public function get_options_json() {
		$options = $this->options;

		if ( isset( $options['last_message'] ) ) {
			unset( $options['last_message'] );
		}

		if ( isset( $options['last_tab'] ) ) {
			unset( $options['last_tab'] );
		}

		return wp_json_encode( $options );
	}
}


