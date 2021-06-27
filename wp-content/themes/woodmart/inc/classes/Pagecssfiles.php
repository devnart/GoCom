<?php
if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * Page css files.
 */
class WOODMART_Pagecssfiles {
	/**
	 * Inline enqueue styles.
	 *
	 * @var array
	 */
	private $inline_enqueue_styles = array();
	/**
	 * Inline enqueue styles.
	 *
	 * @var array
	 */
	private $inline_enqueue_styles_mobile = array();
	/**
	 * Options save.
	 *
	 * @var array
	 */
	private $options_save = array(
		'404',
		'search',
		'date',
		'author',
	);
	/**
	 * Theme version.
	 *
	 * @var string
	 */
	private $theme_version;
	/**
	 * Is mobile.
	 *
	 * @var string
	 */
	private $is_mobile;
	/**
	 * Page data.
	 *
	 * @var array
	 */
	private $page_data = array();
	/**
	 * Page css files.
	 *
	 * @var array
	 */
	private $page_css_files = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->theme_version = woodmart_get_theme_info( 'Version' );
		$this->is_mobile     = wp_is_mobile() && woodmart_get_opt( 'mobile_optimization', 0 );
		$this->hooks();
	}

	/**
	 * Hooks.
	 */
	public function hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_page_css_files' ), 10100 );
		add_action( 'wp_footer', array( $this, 'save_page_css_files' ), 10000 );
		add_action( 'save_post', array( $this, 'delete_post_meta' ), 10 );
		add_action( 'saved_term', array( $this, 'delete_term_meta' ), 10 );
		add_action( 'save_post_cms_block', array( $this, 'delete_all_meta' ), 10 );
		add_action( 'save_post_woodmart_slider', array( $this, 'delete_all_meta' ), 10 );
		add_action( 'xts_theme_settings_save', array( $this, 'delete_all_meta' ), 10 );
		add_action( 'activated_plugin', array( $this, 'delete_all_meta' ), 10 );
		add_action( 'deactivated_plugin', array( $this, 'delete_all_meta' ), 10 );
		add_action( 'wp', array( $this, 'set_page_data' ), 10 );
		add_action( 'wp', array( $this, 'set_page_css_files' ), 20 );
	}

	/**
	 * Set page data.
	 */
	public function set_page_data() {
		$this->page_data = $this->get_page_data();
	}

	/**
	 * Set page data.
	 */
	public function set_page_css_files() {
		$this->page_css_files = $this->get_page_css_files();
	}

	/**
	 * Delete all saved meta.
	 */
	public function delete_all_meta() {
		global $wpdb;

		$wpdb->delete( $wpdb->prefix . 'postmeta', array( 'meta_key' => 'wd_page_css_files' ) ); // phpcs:ignore
		$wpdb->delete( $wpdb->prefix . 'postmeta', array( 'meta_key' => 'wd_page_css_files_mobile' ) ); // phpcs:ignore
		$wpdb->delete( $wpdb->prefix . 'termmeta', array( 'meta_key' => 'wd_page_css_files' ) ); // phpcs:ignore

		foreach ( $this->options_save as $option ) {
			delete_option( 'wd_page_css_files_' . $option );
		}
	}

	/**
	 * Delete post meta.
	 *
	 * @param integer $post_id Post id.
	 */
	public function delete_post_meta( $post_id ) {
		delete_post_meta( $post_id, 'wd_page_css_files' );
		delete_post_meta( $post_id, 'wd_page_css_files_mobile' );
	}

	/**
	 * Delete term meta.
	 *
	 * @param integer $term_id Term id.
	 */
	public function delete_term_meta( $term_id ) {
		delete_term_meta( $term_id, 'wd_page_css_files' );
	}

	/**
	 * Get current page data.
	 *
	 * @return array|string[]
	 */
	private function get_page_data() {
		$data = array(
			'type' => '',
			'id'   => '',
		);

		$queried_object = get_queried_object();

		if ( get_the_ID() ) {
			$data = array(
				'type' => 'post',
				'id'   => get_the_ID(),
			);
		}
		if ( is_singular() ) {
			$data = array(
				'type' => 'post',
				'id'   => get_queried_object_id(),
			);
		}
		if ( $queried_object && ( is_tag() || is_category() ) ) {
			$data = array(
				'type' => 'taxonomy',
				'id'   => $queried_object->term_id,
			);
		}
		if ( woodmart_woocommerce_installed() && $queried_object && ( is_product_tag() || is_product_category() || woodmart_is_product_attribute_archive() ) ) {
			$data = array(
				'type' => 'taxonomy',
				'id'   => $queried_object->term_id,
			);
		}
		if ( is_archive() && 'portfolio' === get_post_type() ) {
			$data = array(
				'type' => 'post',
				'id'   => woodmart_tpl2id( 'portfolio.php' ),
			);
		}
		if ( woodmart_woocommerce_installed() && is_shop() ) {
			$data = array(
				'type' => 'post',
				'id'   => get_option( 'woocommerce_shop_page_id' ),
			);
		}
		if ( is_home() ) {
			$data = array(
				'type' => 'post',
				'id'   => get_option( 'page_for_posts' ),
			);
		}
		if ( is_page() ) {
			$data = array(
				'type' => 'post',
				'id'   => get_queried_object_id(),
			);
		}
		if ( is_search() ) {
			$data = array(
				'type' => 'search',
				'id'   => '',
			);
		}
		if ( is_404() ) {
			$data = array(
				'type' => '404',
				'id'   => '',
			);
		}
		if ( is_date() ) {
			$data = array(
				'type' => 'date',
				'id'   => '',
			);
		}
		if ( is_author() ) {
			$data = array(
				'type' => 'author',
				'id'   => '',
			);
		}

		return $data;
	}

	/**
	 * Get page css files.
	 *
	 * @return array|false|mixed|void
	 */
	private function get_page_css_files() {
		$data = $this->page_data;

		if ( woodmart_is_woo_ajax() ) {
			return array();
		}

		$files = array();

		if ( 'post' === $data['type'] ) {
			if ( $this->is_mobile && get_post_meta( $data['id'], '_woodmart_mobile_content', true ) ) {
				$meta = get_post_meta( $data['id'], 'wd_page_css_files_mobile', true );
			} else {
				$meta = get_post_meta( $data['id'], 'wd_page_css_files', true );
			}
		} elseif ( 'taxonomy' === $data['type'] ) {
			$meta = get_term_meta( $data['id'], 'wd_page_css_files', true );
		} elseif ( in_array( $data['type'], array( 'search', '404', 'date', 'author' ), true ) ) {
			$files = get_option( 'wd_page_css_files_' . $data['type'], array() );
		}

		if ( isset( $meta ) && $meta ) {
			$files = $meta;
		}

		return $files;
	}

	/**
	 * Enqueue page css files.
	 */
	public function enqueue_page_css_files() {
		$config     = woodmart_get_config( 'css-files' );
		$version    = woodmart_get_theme_info( 'Version' );
		$page_files = $this->page_css_files;
		$localize   = array();

		if ( woodmart_is_combined_needed( 'combined_css', false ) || ! $page_files ) {
			wp_localize_script(
				'woodmart-theme',
				'woodmart_page_css',
				array()
			);
			return;
		}

		foreach ( $page_files as $slug ) {
			if ( ! isset( $config[ $slug ] ) ) {
				continue;
			}

			foreach ( $config[ $slug ] as $file ) {
				if ( isset( $file['wpb_file'] ) && 'wpb' === woodmart_get_opt( 'page_builder', 'wpb' ) ) {
					$file['file'] = $file['wpb_file'];
				}

				if ( is_rtl() && file_exists( WOODMART_THEMEROOT . $file['file'] . '-rtl.min.css' ) ) {
					$file['file'] = $file['file'] . '-rtl';
				}

				$src = WOODMART_THEME_DIR . $file['file'] . '.min.css';

				$localize[ 'wd-' . $file['name'] . '-css' ] = $src;

				wp_enqueue_style( 'wd-' . $file['name'], $src, array( 'woodmart-style' ), $version );
			}
		}

		wp_localize_script(
			'woodmart-theme',
			'woodmart_page_css',
			$localize
		);
	}

	/**
	 * Enqueue page css files.
	 *
	 * @param string $key File slug.
	 * @param bool   $ignore_combined Ignore combine.
	 */
	public function enqueue_style( $key, $ignore_combined = false ) {
		$config  = woodmart_get_config( 'css-files' );
		$version = woodmart_get_theme_info( 'Version' );

		if ( woodmart_is_combined_needed( 'combined_css', false ) && ! $ignore_combined ) {
			return;
		}

		foreach ( $config[ $key ] as $file ) {
			if ( isset( $file['wpb_file'] ) && 'wpb' === woodmart_get_opt( 'page_builder', 'wpb' ) ) {
				$file['file'] = $file['wpb_file'];
			}

			if ( is_rtl() && file_exists( WOODMART_THEMEROOT . $file['file'] . '-rtl.min.css' ) ) {
				$file['file'] = $file['file'] . '-rtl';
			}

			$src = WOODMART_THEME_DIR . $file['file'] . '.min.css';

			if ( $this->is_mobile ) {
				$this->inline_enqueue_styles_mobile[] = $key;
			} else {
				$this->inline_enqueue_styles[] = $key;
			}

			wp_enqueue_style( 'wd-' . $file['name'], $src, array( 'woodmart-style' ), $version );
		}
	}

	/**
	 * Save page css files.
	 */
	public function save_page_css_files() {
		$data = $this->page_data;

		if ( get_option( 'wd_page_css_files_theme_version' ) !== $this->theme_version ) {
			$this->delete_all_meta();
		}

		if ( $this->page_css_files ) {
			return;
		}

		if ( 'post' === $data['type'] ) {
			if ( $this->is_mobile && get_post_meta( $data['id'], '_woodmart_mobile_content', true ) ) {
				update_post_meta( $data['id'], 'wd_page_css_files_mobile', $this->inline_enqueue_styles_mobile );
			} else {
				update_post_meta( $data['id'], 'wd_page_css_files', $this->inline_enqueue_styles );
			}
		} elseif ( 'taxonomy' === $data['type'] ) {
			update_term_meta( $data['id'], 'wd_page_css_files', $this->inline_enqueue_styles );
		} elseif ( in_array( $data['type'], array( 'search', '404', 'date', 'author' ), true ) ) {
			update_option( 'wd_page_css_files_' . $data['type'], $this->inline_enqueue_styles );
		}

		update_option( 'wd_page_css_files_theme_version', $this->theme_version );
	}

	/**
	 * Enqueue inline style by key.
	 *
	 * @param string $key File slug.
	 */
	public function enqueue_inline_style( $key ) {
		$config     = woodmart_get_config( 'css-files' );
		$page_files = $this->page_css_files;

		if ( ! isset( $config[ $key ] ) || in_array( $key, $page_files, true ) || woodmart_is_combined_needed( 'combined_css', false ) ) {
			return;
		}

		foreach ( $config[ $key ] as $data ) {
			if ( $this->is_mobile ) {
				if ( is_array( $this->inline_enqueue_styles_mobile ) && in_array( $key, $this->inline_enqueue_styles_mobile ) ) { // phpcs:ignore
					continue;
				}
			} else {
				if ( is_array( $this->inline_enqueue_styles ) && in_array( $key, $this->inline_enqueue_styles ) ) { // phpcs:ignore
					continue;
				}
			}

			if ( isset( $data['wpb_file'] ) && 'wpb' === woodmart_get_opt( 'page_builder', 'wpb' ) ) {
				$data['file'] = $data['wpb_file'];
			}

			if ( is_rtl() && file_exists( WOODMART_THEMEROOT . $data['file'] . '-rtl.min.css' ) ) {
				$data['file'] = $data['file'] . '-rtl';
			}

			$src = WOODMART_THEME_DIR . $data['file'] . '.min.css';

			if ( $this->is_mobile ) {
				$this->inline_enqueue_styles_mobile[] = $key;
			} else {
				$this->inline_enqueue_styles[] = $key;
			}

			?>
			<link rel="stylesheet" id="<?php echo esc_attr( 'wd-' . $data['name'] ); ?>-css" href="<?php echo esc_attr( $src ); ?>?ver=<?php echo esc_attr( $this->theme_version ); ?>" type="text/css" media="all" /> <?php // phpcs:ignore ?>
			<?php
		}
	}
}
