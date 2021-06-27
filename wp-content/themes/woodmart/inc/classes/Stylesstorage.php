<?php
/**
 * CSS to file
 *
 * @package xts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * CSS to file class
 *
 * @since 1.0.0
 */
class WOODMART_Stylesstorage {
	public $data_name;
	public $storage;
	public $id;
	public $current_theme_version;
	public $current_site_url;

	public $data;
	public $css;
	public $css_version;
	public $site_url;

	public $check_credentials;

	public $is_file_exists = false;
	public $is_css_exists  = false;

	public function __construct( $data_name, $storage = 'option', $id = '', $check_credentials = true ) {
		$this->set_data_name( $data_name );
		$this->storage               = $storage;
		$this->id                    = $id;
		$this->check_credentials     = $check_credentials;
		$this->current_theme_version = woodmart_get_theme_info( 'Version' );
		$this->current_site_url      = get_site_url();

		$this->set_data( 'xts-' . $this->data_name . '-file-data' );
		$this->set_css_data( 'xts-' . $this->data_name . '-css-data' );

		$this->css_version = $this->get_data( 'xts-' . $this->data_name . '-version' );
		$this->site_url    = $this->get_data( 'xts-' . $this->data_name . '-site-url' );

		$this->check_css_status();
	}

	/**
	 * Set data.
	 *
	 * @since 1.0.0
	 *
	 * @param string $data_name Data name.
	 */
	public function set_data( $data_name ) {
		$this->data = $this->get_data( $data_name );
	}

	/**
	 * Set css data.
	 *
	 * @since 1.0.0
	 *
	 * @param string $data_name Data name.
	 */
	public function set_css_data( $data_name ) {
		$this->css = $this->get_data( $data_name );
	}

	/**
	 * Set data name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $data_name Data name.
	 */
	public function set_data_name( $data_name ) {
		$this->data_name = $data_name;
	}

	/**
	 * Check css status.
	 *
	 * @since 1.0.0
	 */
	public function check_css_status() {
		$data_status = $this->get_data( 'xts-' . $this->data_name . '-status' );

		if ( 'valid' === $data_status ) {
			if ( isset( $this->data['path'] ) && file_exists( $this->get_file_path( $this->data['path'] ) ) && apply_filters( 'woodmart_styles_storage_file', true ) && isset( $this->data['theme_version'] ) && version_compare( $this->current_theme_version, $this->data['theme_version'], '==' ) && ( isset( $this->data['site_url'] ) && md5( get_site_url() ) === $this->data['site_url'] ) ) {
				$this->is_file_exists = true;
			}   

			if ( $this->css && apply_filters( 'woodmart_styles_storage_db_css', true ) && version_compare( $this->current_theme_version, $this->css_version, '==' ) && md5( get_site_url() ) === $this->site_url ) {
				$this->is_css_exists = true;
			}
		}
	}

	public function get_file_path( $path ) {
		$uploads = wp_upload_dir();

		return $uploads['basedir'] . $path;
	}

	public function get_file_url( $url ) {
		$uploads = wp_upload_dir();

		return $uploads['baseurl'] . $url;
	}

	/**
	 * Is css exists.
	 *
	 * @since 1.0.0
	 */
	public function is_css_exists() {
		return $this->is_css_exists;
	}

	/**
	 * Print styles.
	 *
	 * @since 1.0.0
	 */
	public function print_styles() {
		if ( $this->is_file_exists ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'file_css' ), 11000 );
		} else {
			add_action( 'wp_head', array( $this, 'inline_css' ), 10000 );
		}
	}

	/**
	 * FIle css.
	 *
	 * @since 1.0.0
	 */
	public function file_css() {
		if ( isset( $this->data['path'] ) && $this->data['path'] ) {
			wp_enqueue_style( 'xts-style-' . $this->data_name, $this->get_file_url( $this->data['path'] ), array(), $this->current_theme_version );
		}
	}

	/**
	 * Inline css.
	 *
	 * @since 1.0.0
	 */
	public function inline_css() {
		if ( $this->css ) {
			?>
			<style data-type="wd-style-<?php echo esc_attr( $this->data_name ); ?>">
				<?php echo $this->css; // phpcs:ignore ?>
			</style>
			<?php
		}
	}

	/**
	 * Reset data.
	 *
	 * @since 1.0.0
	 */
	public function reset_data() {
		$this->update_data( 'xts-' . $this->data_name . '-status', 'invalid' );
		$this->delete_data( 'xts-' . $this->data_name . '-credentials' );
	}

	/**
	 * Write file.
	 *
	 * @since 1.0.0
	 *
	 * @param $css
	 * @param bool $is_frontend
	 */
	public function write( $css, $is_frontend = false ) {
		if ( ! $css ) {
			return;
		}

		$this->css = $css;

		if ( ! $is_frontend ) {
			$this->write_file( $css );
		}

		$this->update_data(
			'xts-' . $this->data_name . '-css-data',
			$css
		);

		$this->update_data( 'xts-' . $this->data_name . '-status', 'valid' );
		$this->update_data( 'xts-' . $this->data_name . '-version', woodmart_get_theme_info( 'Version' ) );
		$this->update_data( 'xts-' . $this->data_name . '-site-url', md5( get_site_url() ) );
	}

	/**
	 * Delete file.
	 */
	public function delete_file() {
		if ( function_exists( 'WP_Filesystem' ) ) {
			WP_Filesystem();
		}

		global $wp_filesystem;

		if ( $this->data && $this->data['path'] ) {
			$wp_filesystem->delete( $this->get_file_path( $this->data['path'] ) );
			$this->delete_data( 'xts-' . $this->data_name . '-file-data' );
		}
	}

	/**
	 * Write file.
	 *
	 * @param $css
	 */
	private function write_file( $css ) {
		if ( function_exists( 'WP_Filesystem' ) ) {
			WP_Filesystem();
		}
		/**
		 * File system
		 *
		 * @var WP_Filesystem_Base $wp_filesystem
		 */
		global $wp_filesystem;

		if ( ( $this->check_credentials && ( function_exists( 'request_filesystem_credentials' ) && ! $this->check_credentials() ) ) || ! $wp_filesystem ) {
			return;
		}

		$this->delete_file();

		$result = $wp_filesystem->put_contents( $this->get_file_path( $this->get_file_info( $this->data_name ) ), $css );

		if ( $result ) {
			$this->update_data(
				'xts-' . $this->data_name . '-file-data',
				array(
					'path'          => $this->get_file_info( $this->data_name ),
					'theme_version' => $this->current_theme_version,
					'site_url'      => md5( get_site_url() ),
				)
			);
		}
	}

	/**
	 * Get data.
	 *
	 * @param string $name Option name.
	 *
	 * @return mixed|string|void
	 */
	private function get_data( $name ) {
		$results = '';

		if ( 'option' === $this->storage ) {
			$results = get_option( $name );
		} elseif ( 'post_meta' === $this->storage && $this->id ) {
			$results = get_post_meta( $this->id, $name );
		}

		return $results;
	}

	/**
	 * Update data.
	 *
	 * @param string $name Option name.
	 * @param mixed  $data      Data.
	 *
	 * @return mixed|string|void
	 */
	private function update_data( $name, $data ) {
		if ( 'option' === $this->storage ) {
			update_option( $name, $data );
		} elseif ( 'post_meta' === $this->storage && $this->id ) {
			update_post_meta( $this->id, $name, $data );
		}
	}

	/**
	 * Delete data.
	 *
	 * @param string $name Option name.
	 *
	 * @return mixed|string|void
	 */
	private function delete_data( $name ) {
		if ( 'option' === $this->storage ) {
			delete_option( $name );
		} elseif ( 'post_meta' === $this->storage && $this->id ) {
			delete_post_meta( $this->id, $name );
		}
	}

	/**
	 * Get file info.
	 *
	 * @since 1.0.0
	 *
	 * @param string $data_name File name.
	 *
	 * @return string
	 */
	public function get_file_info( $data_name ) {
		$uploads = wp_upload_dir();
		return $uploads['subdir'] . '/' . 'xts-' . $data_name . '-' . time() . '.css';
	}

	/**
	 * Check credentials.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function check_credentials() {
		$data_status        = $this->get_data( 'xts-' . $this->data_name . '-status' );
		$credentials_status = $this->get_data( 'xts-' . $this->data_name . '-credentials' );

		if ( ( 'valid' === $data_status || 'requested' === $credentials_status ) && ! $_POST ) {
			return false;
		}

		$this->update_data( 'xts-' . $this->data_name . '-credentials', 'requested' );

		echo '<div class="woodmart-request-credentials">';
		$credentials = request_filesystem_credentials( false, '', false, false, array_keys( $_POST ) ); // phpcs:ignore
		echo '</div>';

		if ( ! $credentials ) {
			return false;
		}

		if ( ! WP_Filesystem( $credentials ) ) {
			return false;
		}

		return true;
	}
}
