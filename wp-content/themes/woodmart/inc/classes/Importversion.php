<?php

if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * WOODMART_Import_Version
 */

use Elementor\Utils;
use XTS\Options;

class WOODMART_Importversion {

	private $_woodmart_versions = array();

	public $response;

	private $_importer;

	private $_file_path;

	private $_version;

	private $_active_widgets;

	private $_widgets_counter = 1;

	private $_process = array();

	private $_sliders = array();

	private $_debug = false;

	private $_builder = 'wpb';

	public $menu_ids = false;

	public $after_import = null;

	private $links = array(
		'uploads' => array(
			'http://dummy.xtemos.com/woodmart-elementor/demos/wp-content/uploads/sites/2/',
			'http://dummy.xtemos.com/woodmart/demos/wp-content/uploads/sites/2/',
			'http://dummy.xtemos.com/woodmart-elementor/wp-content/uploads/',
			'http://dummy.xtemos.com/woodmart/demos/wp-content/uploads/',
			'https://woodmart.xtemos.com/wp-content/uploads/',
		),
		'simple'  => array(
			'http://dummy.xtemos.com/woodmart/',
		),
	);

	public function __construct( $version, $process, $sliders, $builder ) {

		$this->_version = $version;
		$this->_process = $process;
		$this->_sliders = $sliders;
		$this->_builder = $builder;

		$this->_woodmart_versions = woodmart_get_config( 'versions' );

		$this->response = WOODMART_Registry::getInstance()->ajaxresponse;

		$this->_file_path = WOODMART_THEMEROOT . '/inc/dummy-content/';

		// Load importers API
		$this->_load_importers();

	}


	public function run_import() {

		if ( ! $this->_is_valid_version_slug( $this->_version ) ) {

			$this->response->send_fail_msg( 'Wrong version name ' . $this->_version );

		}

		if ( $this->_need_process( 'before' ) ) {
			$this->before_import();
		}

		// Import xml file
		if ( $this->_need_process( 'xml' ) ) {
			if ( $this->_debug ) {
				$this->response->add_msg( 'XML DONE' );
			} else {
				$this->_import_xml();
			}
		}

		// Set up home page
		if ( $this->_need_process( 'home' ) ) {
			if ( $this->_debug ) {
				$this->response->add_msg( 'HOME PAGE' );
			} else {
				$this->_set_home_page();
			}
		}

		// Set up widgets
		if ( $this->_need_process( 'widgets' ) ) {
			if ( $this->_debug ) {
				$this->response->add_msg( 'WIDGETS DONE' );
			} else {
				$this->_set_up_widgets();
			}
		}

		// Import sliders
		if ( $this->_need_process( 'sliders' ) ) {
			if ( $this->_debug ) {
				$this->response->add_msg( 'SLIDERS DONE' );
			} else {
				$this->_import_sliders();
			}
		}

		// Import woodmart slider
		if ( $this->_need_process( 'wood_slider' ) ) {
			if ( $this->_debug ) {
				$this->response->add_msg( 'WOODMART SLIDER DONE' );
			} else {
				$this->_import_wood_slider();
			}
		}

		// Import options
		if ( $this->_need_process( 'options' ) ) {
			if ( $this->_debug ) {
				$this->response->add_msg( 'OPTIONS DONE' );
			} else {
				$this->_import_options();
			}
		}

		// Add page to menu
		if ( $this->_need_process( 'page_menu' ) ) {
			if ( $this->_debug ) {
				$this->response->add_msg( 'page menu DONE' );
			} else {
				$this->add_page_menu();
			}
		}

		// Import header
		if ( $this->_need_process( 'headers' ) ) {
			if ( $this->_debug ) {
				$this->response->add_msg( 'HEADER DONE' );
			} else {
				$this->_import_headers();
			}
		}

		// Import extras
		if ( $this->_need_process( 'extras' ) ) {
			if ( $this->_debug ) {
				$this->response->add_msg( 'EXTRAS DONE' );
			} else {
				$this->_import_extras();
			}
		}

		if ( $this->_need_process( 'after' ) ) {
			$this->after_import();
		}

		$this->replace_db_urls();
	}

	public function sizes_array( $sizes ) {
		return array();
	}

	private function _import_xml() {

		$file_name = ( $this->_builder == 'elementor' ) ? 'content-elementor.xml' : 'content.xml';

		$file = $this->_get_file_to_import( $file_name );

		// Check if XML file exists
		if ( ! $file ) {
			$this->response->send_fail_msg( "File doesn't exist <strong>" . $this->_version . '/' . $file_name . '</strong>' );
		}

		try {

			ob_start();

			// Prevent generating of thumbnails for 8 sizes. Only original
			add_filter( 'intermediate_image_sizes', array( $this, 'sizes_array' ) );

			$this->_importer->fetch_attachments = true;// $this->_import_attachments;

			// Run WP Importer for XML file
			$this->_importer->import( $file );

			$output = ob_get_contents();

			ob_end_clean();

			$this->response->add_msg( $output );

		} catch ( Exception $e ) {
			$this->response->send_fail_msg( 'Error while importing' );
		}
	}


	private function _set_home_page() {

		$home_page_title = 'Home ' . $this->_version;
		$home_page       = get_page_by_title( $home_page_title );
		if ( ! is_null( $home_page ) ) {

			update_option( 'page_on_front', $home_page->ID );
			update_option( 'show_on_front', 'page' );

			$this->response->add_msg( 'Front page set to <strong>"' . $home_page_title . '"</strong>' );
		} else {
			$this->response->add_msg( 'Front page is not changed' );
		}

	}

	public function add_page_menu() {

		if ( ! isset( $this->_woodmart_versions[ $this->_version ]['parent_menu_title'] ) ) {
			return;
		}

		$page_title   = $this->_woodmart_versions[ $this->_version ]['title'];
		$parent_title = $this->_woodmart_versions[ $this->_version ]['parent_menu_title'];

		$this->add_menu_item_by_title( $page_title, false, $parent_title );

		// $this->add_menu_item_by_title($page_title, false, $parent_title, 'menu-right' );
	}

	public function add_menu_item_by_title( $title, $position = false, $parent_title = false, $menu = 'main', $custom_title = false, $meta = array() ) {

		$page = get_page_by_title( $title );

		if ( is_null( $page ) ) {
			return;
		}

		$this->insert_menu_item( $title, $position, $page->ID, $parent_title, $menu, $custom_title, $meta );

		return $page->ID;

	}

	public function insert_menu_item( $page_title, $position = false, $page_id = false, $parent_title = false, $menu = 'main', $custom_title = false, $meta = array() ) {
		if ( ! $this->menu_ids ) {
			$this->set_menu_ids();
		}

		$menu_id = $this->menu_ids[ $menu ];

		$all_items = wp_get_nav_menu_items( $menu_id );

		if ( $custom_title ) {
			$page_title = $custom_title;
		}

		$menu_item = array_filter(
			$all_items,
			function( $item ) use ( $page_title ) {
				return $item->title == $page_title;
			}
		);

		if ( ! empty( $menu_item ) ) {
			return;
		}

		$args = array(
			'menu-item-title'  => $page_title,
			'menu-item-object' => 'page',
			'menu-item-type'   => 'post_type',
			'menu-item-status' => 'publish',
		);

		if ( $position ) {
			$args['menu-item-position'] = $position;
		}

		if ( $page_id ) {
			$args['menu-item-object-id'] = $page_id;
		}

		if ( $parent_title ) {
			$parent_items = array_filter(
				$all_items,
				function( $item ) use ( $parent_title ) {
					return $item->title == $parent_title;
				}
			);

			$parent_item = array_shift( $parent_items );

			$args['menu-item-parent-id'] = $parent_item->ID;
		}

		$menu_item_id = wp_update_nav_menu_item( $menu_id, 0, $args );

		if ( ! empty( $meta ) ) {
			foreach ( $meta as $key => $value ) {
				if ( $key == 'content' ) {
					// Update the post into the database
					wp_update_post(
						array(
							'ID'           => $menu_item_id,
							'post_content' => $value,
						)
					);
				} else {
					add_post_meta( $menu_item_id, '_menu_item_' . $key, $value );
				}
			}
		}
	}


	public function set_menu_ids() {
		global $wpdb;

		$main_menu  = get_term_by( 'name', 'Main navigation', 'nav_menu' );
		$menu_left  = get_term_by( 'name', 'Menu left', 'nav_menu' );
		$menu_right = get_term_by( 'name', 'Menu right', 'nav_menu' );

		$this->menu_ids = array(
			'main'       => $main_menu->term_id,
			'menu-left'  => $menu_left->term_id,
			'menu-right' => $menu_right->term_id,
		);
	}

	private function _set_up_widgets() {

		$widgets = woodmart_get_config( 'widgets-import' );

		$version_widgets_file = $this->_get_file_to_import( 'widgets.json' );

		if ( $version_widgets_file ) {
			$version_widgets = json_decode( $this->_get_local_file_content( $version_widgets_file ), true );

			$widgets = wp_parse_args( $version_widgets, $widgets );
		}

		$widgets = json_decode( $this->links_replace( wp_json_encode( $widgets ) ), true );

		// We don't want to undo user changes, so we look for changes first.
		$this->_active_widgets = get_option( 'sidebars_widgets' );

		$this->_widgets_counter = 1;

		foreach ( $widgets as $area => $params ) {
			if ( $params['flush'] ) {
				$this->_flush_widget_area( $area );
			}

			if ( ! empty( $this->_active_widgets[ $area ] ) && $params['flush'] ) {
				$this->_flush_widget_area( $area );
			} elseif ( ! empty( $this->_active_widgets[ $area ] ) && ! $params['flush'] ) {
				continue;
			}
			foreach ( $params['widgets'] as $widget => $args ) {
				$this->_add_widget( $area, $widget, $args );
			}
		}

		// Now save the $active_widgets array.
		update_option( 'sidebars_widgets', $this->_active_widgets );

		$this->response->add_msg( 'Widgets updated' );

	}

	private function _add_widget( $sidebar, $widget, $options = array() ) {

		$this->_active_widgets[ $sidebar ][] = $widget . '-' . $this->_widgets_counter;

		$widget_content = get_option( 'widget_' . $widget );

		$widget_content[ $this->_widgets_counter ] = $options;

		update_option( 'widget_' . $widget, $widget_content );

		$this->_widgets_counter++;
	}

	private function _flush_widget_area( $area ) {

		unset( $this->_active_widgets[ $area ] );

	}


	private function _import_sliders() {
		if ( ! class_exists( 'RevSliderSlider' ) ) {
			return;
		}
		foreach ( $this->_sliders as $slider_name ) {
			$this->_revolution_import( $slider_name . '.zip' );
		}
	}

	private function _import_wood_slider() {
		for ( $i = 1; $i <= 5; $i++ ) {
			if ( $file = $this->_get_file_to_import( 'slider-' . $i . '.json' ) ) {
				$slider = json_decode( $this->_get_local_file_content( $file ), true );

				$term = wp_insert_term(
					$slider['name'],
					'woodmart_slider',
					array(
						'slug' => $slider['slug'],
					)
				);

				if ( is_wp_error( $term ) ) {
					$id = $term->error_data['term_exists'];
				} else {
					$id = $term['term_id'];
				}

				foreach ( $slider['meta'] as $key => $value ) {
					add_term_meta( $id, $key, $value, true );
				}
			}
		}
	}

	private function _revolution_import( $filename ) {
		if ( ! apply_filters( 'woodmart_old_sliders_import', false ) ) {
			$file = $this->_download_file_to_import( $filename );
		} else {
			$file = $this->_get_file_to_import( $filename );
		}
		if ( ! $file ) {
			return;
		}

		ob_start();

		if ( class_exists( 'RevSliderSliderImport' ) ) {
			$revapi        = new RevSliderSliderImport();
			$slider_result = $revapi->import_slider( true, $file );
		} else {
			$revapi        = new RevSlider();
			$slider_result = $revapi->importSliderFromPost( true, true, $file );
		}

		ob_end_clean();
	}

	private function _download_file_to_import( $filename ) {

		$file = WOODMART_DUMMY_URL . $this->_version . '/' . $filename;

		try {
			$zip_file = download_url( $file );
			if ( is_wp_error( $zip_file ) ) {
				$this->response->add_msg( 'Can\'t download revslider file from the server ' . $file );
				return false;
			}
		} catch ( Exception $e ) {
			$this->response->add_msg( 'Can\'t download revslider file from the server ' . $file );
			return false;
		}

		return $zip_file;
	}


	private function _get_file_to_import( $filename ) {

		$file = $this->_get_version_folder() . $filename;

		// Check if ZIP file exists
		if ( ! file_exists( $file ) ) {
			return false;
		}

		return $file;
	}

	private function _get_version_folder( $version = false ) {
		if ( ! $version ) {
			$version = $this->_version;
		}

		return $this->_file_path . $version . '/';
	}

	// Import extras
	private function _import_extras() {
		try {
			$extra_file = $this->_get_version_folder( 'extras' ) . 'extras.xml';

			ob_start();

			// Run WP Importer for XML file
			if ( file_exists( $extra_file ) ) {
				$this->_importer->import( $extra_file );
			}

			$output = ob_get_contents();

			ob_end_clean();

			$this->response->add_msg( $output );

			$this->add_menu_item_by_title(
				'Shop',
				3,
				false,
				'menu-left',
				false,
				array(
					'block'  => '201',
					'design' => 'full-width',
				)
			);

		} catch ( Exception $e ) {
			$this->response->send_fail_msg( 'Error while importing extras' );
		}

		$this->response->add_msg( 'Extras updated' );
	}

	// Import header
	private function _import_headers() {
		try {
			for ( $i = 1; $i <= 5; $i++ ) {
				if ( $header = $this->_get_file_to_import( 'header-' . $i . '.json' ) ) {
					$default = ( $i == 1 ) ? true : false;
					$this->_create_new_header( $header, $default );
				}
			}
		} catch ( Exception $e ) {
			$this->response->send_fail_msg( 'Error while importing header' );
		}

		$this->response->add_msg( 'Header updated' );
	}

	private function _create_new_header( $file, $default = false ) {
		$builder     = WOODMART_Header_Builder::get_instance();
		$header_data = json_decode( $this->links_replace( $this->_get_local_file_content( $file ), '/' ), true );
		$builder->list->add_header( $header_data['id'], $header_data['name'] );
		$builder->factory->create_new( $header_data['id'], $header_data['name'], $header_data['structure'], $header_data['settings'] );

		if ( $default ) {
			update_option( 'whb_main_header', $header_data['id'] );
		}
	}

	private function _import_options() {
		global $xts_woodmart_options;

		$file = $this->_get_file_to_import( 'options.json' );

		if ( ! $file ) {
			return;
		}

		$new_options_json = $this->links_replace( $this->_get_local_file_content( $file ) );

		$options = XTS\Options::get_instance();

		// Merge new options with new resetting values
		$version_type = $this->_woodmart_versions[ $this->_version ]['type'];
		$new_options  = wp_parse_args( json_decode( $new_options_json ), $this->_get_reset_options( $version_type ) );

		// Set builder to WPB or Elementor.
		$xts_woodmart_options['page_builder']                     = $this->_builder;
		$xts_woodmart_options['variation_gallery_storage_method'] = 'new';
		$xts_woodmart_options['old_elements_classes'] = false;

		// Merge new options with other existed ones
		$new_options = wp_parse_args( $new_options, $xts_woodmart_options );

		$pseudo_post_data = array(
			'import-btn'    => true,
			'import_export' => json_encode( $new_options ),
		);

		$sanitized_options = $options->sanitize_before_save( $pseudo_post_data );

		$options->update_options( $sanitized_options );
		// Dynamic css options
		delete_option( 'xts-theme_settings_default-file-data' );
		delete_option( 'xts-theme_settings_default-css-data' );
		delete_option( 'xts-theme_settings_default-version' );
		delete_option( 'xts-theme_settings_default-site-url' );
		delete_option( 'xts-theme_settings_default-status' );

		$this->response->add_msg( 'Options updated' );
	}

	private function _get_reset_options( $version_type ) {
		$reset_options                              = array();
		( $version_type == 'base' ) ? $version_type = 'version' : '';
		$reset_options_keys                         = woodmart_get_config( 'reset-options-' . $version_type );

		foreach ( $reset_options_keys as $opt ) {
			$reset_options[ $opt ] = $this->_get_default_option_value( $opt );
		}

		return $reset_options;
	}

	private function _get_default_option_value( $key ) {
		$all_fields = Options::get_fields();

		foreach ( $all_fields as $field ) {
			if ( $field->args['id'] === $key ) {
				return ( isset( $field->args['default'] ) ? $field->args['default'] : '' );
			}
		}

		return '';
	}

	private function _get_local_file_content( $file ) {
		ob_start();
		include $file;
		$file_content = ob_get_contents();
		ob_end_clean();
		return $file_content;
	}

	private function _load_importers() {

		// Load Importer API
		require_once ABSPATH . 'wp-admin/includes/import.php';

		if ( ! function_exists( 'WOODMART_Theme_Plugin' ) ) {

			$this->response->send_fail_msg( 'Please install theme core plugin' );

		}

		// $this->_import_attachments = ( ! empty($_GET['import_attachments']) );
		$importerError = false;

		// check if wp_importer, the base importer class is available, otherwise include it
		if ( ! class_exists( 'WP_Importer' ) ) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			if ( file_exists( $class_wp_importer ) ) {
				require_once $class_wp_importer;
			} else {
				$importerError = true;
			}
		}

		$plugin_dir = WOODMART_Theme_Plugin()->plugin_path();

		$path = apply_filters( 'woodmart_require', $plugin_dir . '/importer/wordpress-importer.php' );

		if ( file_exists( $path ) ) {
			require_once $path;
		} else {
			$this->response->send_fail_msg( 'wordpress-importer.php file doesn\'t exist' );
		}

		if ( $importerError !== false ) {
			$this->response->send_fail_msg( 'The Auto importing script could not be loaded. Please use the WordPress importer and import the XML file that is located in your themes folder manually.' );
		}

		if ( class_exists( 'WP_Importer' ) && class_exists( 'WOODCORE_Import' ) ) {

			$this->_importer = new WOODCORE_Import();

		} else {

			$this->response->send_fail_msg( 'Can\'t find WP_Importer or WOODCORE_Import class' );

		}

	}

	private function _is_valid_version_slug( $ver ) {
		if ( in_array( $ver, array_keys( $this->_woodmart_versions ) ) ) {
			return true;
		}
		return false;
	}


	private function _need_process( $process ) {
		return in_array( $process, $this->_process );
	}

	public function before_import() {

		$file = $this->_get_version_folder() . 'after.php';

		if ( ! file_exists( $file ) ) {
			return;
		}
		require $file;

		$base_import_class    = 'WOODMART_Importversion_';
		$version_import_class = $base_import_class . $this->_version;

		if ( ! class_exists( $version_import_class ) ) {
			return;
		}

		$this->after_import = new $version_import_class( false, false, false, false );
		$this->after_import->before();
	}

	public function after_import() {

		if ( $this->after_import == null ) {
			return;
		}

		$this->after_import->after();
	}

	/**
	 * Replace link.
	 *
	 * @since 1.0.0
	 *
	 * @param string $data    Data.
	 * @param string $replace Replace.
	 *
	 * @return string|string[]
	 */
	private function links_replace( $data, $replace = '\/' ) {
		$links = $this->links;

		foreach ( $links as $key => $value ) {
			if ( 'uploads' === $key ) {
				foreach ( $value as $link ) {
					$url_data = wp_upload_dir();
					$data     = str_replace( str_replace( '/', $replace, $link ), str_replace( '/', $replace, $url_data['baseurl'] . '/' ), $data );
				}
			}

			if ( 'simple' === $key ) {
				foreach ( $value as $link ) {
					$data = str_replace( str_replace( '/', $replace, $link ), str_replace( '/', $replace, get_home_url() . '/' ), $data );
				}
			}
		}

		return $data;
	}

	/**
	 * Replace URL.
	 *
	 * @since 1.0.0
	 */
	private function replace_db_urls() {
		$links = $this->links;

		foreach ( $links as $key => $value ) {
			if ( 'uploads' === $key ) {
				foreach ( $value as $link ) {
					$url_data = wp_upload_dir();
					if ( woodmart_is_elementor_installed() ) {
						Utils::replace_urls( $link, $url_data['baseurl'] . '/' );
					} else {
						$this->wpb_replace_urls( $link, $url_data['baseurl'] . '/' );
					}
				}
			}

			if ( 'simple' === $key ) {
				foreach ( $value as $link ) {
					if ( woodmart_is_elementor_installed() ) {
						Utils::replace_urls( $link, get_home_url() . '/' );
					} else {
						$this->wpb_replace_urls( $link, get_home_url() . '/' );
					}
				}
			}
		}
	}

	/**
	 * Replace URLs.
	 *
	 * Replace old URLs to new URLs. This method also updates all the Elementor data.
	 *
	 * @since 2.1.0
	 * @static
	 * @access public
	 *
	 * @param $from
	 * @param $to
	 *
	 * @return string
	 * @throws \Exception
	 */
	private function wpb_replace_urls( $from, $to ) {
		$from = trim( $from );
		$to   = trim( $to );

		if ( $from === $to ) {
			throw new \Exception( __( 'The `from` and `to` URL\'s must be different', 'elementor' ) );
		}

		$is_valid_urls = ( filter_var( $from, FILTER_VALIDATE_URL ) && filter_var( $to, FILTER_VALIDATE_URL ) );
		if ( ! $is_valid_urls ) {
			throw new \Exception( __( 'The `from` and `to` URL\'s must be valid URL\'s', 'elementor' ) );
		}

		global $wpdb;

		// @codingStandardsIgnoreStart cannot use `$wpdb->prepare` because it remove's the backslashes
		$rows_affected = $wpdb->query(
			"UPDATE {$wpdb->postmeta} " .
			"SET `meta_value` = REPLACE(`meta_value`, '" . $from . "', '" . $to . "') " .
			"WHERE `meta_key` = '_wpb_shortcodes_custom_css'" ); // meta_value LIKE '[%' are json formatted
		// @codingStandardsIgnoreEnd

		if ( false === $rows_affected ) {
			throw new \Exception( __( 'An error occurred', 'elementor' ) );
		}

		return sprintf(
		/* translators: %d: Number of rows */
			_n( '%d row affected.', '%d rows affected.', $rows_affected, 'elementor' ),
			$rows_affected
		);
	}
}
