<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Handle backend AJAX actions. Creat, load, remove headers from the backend interface with AJAX.
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_Manager' ) ) {
	class WOODMART_HB_Manager {
		private $_factory;

		private $_list;

		public function __construct( $factory, $list ) {
			$this->_factory = $factory;
			$this->_list = $list;
			$this->_ajax_actions();
		}

		private function _ajax_actions() {
			add_action( 'wp_ajax_woodmart_save_header', array($this, 'save_header') );
			add_action( 'wp_ajax_woodmart_load_header', array($this, 'load_header') );
			add_action( 'wp_ajax_woodmart_remove_header', array($this, 'remove_header') );
			add_action( 'wp_ajax_woodmart_set_default_header', array($this, 'set_default_header') );
		}

		public function save_header() {
			check_ajax_referer( 'woodmart-builder-save-header-nonce', 'security' );

			$structure = stripslashes( $_POST['structure'] );
			$settings = stripslashes( $_POST['settings'] );
			
			// If we import a new header we don't have an ID
			$id = ( isset( $_POST['id'] )) ? sanitize_text_field( stripslashes( $_POST['id'] ) ) : $this->_generate_id();
			$name = sanitize_text_field( stripslashes( $_POST['name'] ) );

			$header = $this->_factory->update_header( $id, $name, json_decode($structure, true), json_decode($settings, true) );

			$this->_list->add_header( $id, $name );

			$this->_send_header_data( $header );

		}

		public function load_header() {
			check_ajax_referer( 'woodmart-builder-load-header-nonce', 'security' );

			$id = sanitize_text_field( $_GET['id'] );
			$base = ( isset( $_GET['base'] ) ) ? sanitize_text_field($_GET['base']) : false;

			if( isset( $_GET['initial'] ) && $_GET['initial'] || $base ) {
				$header = $this->_new_header( $base );
			} else {
				$header = $this->_factory->get_header( $id );
			}

			$this->_send_header_data( $header );
		}

		private function _send_header_data( $header ) {
			$data = $header->get_data();

			$data['list'] = $this->_list->get_all();

			echo json_encode($data);

			wp_die();
		}

		private function _new_header( $base = false ) {
			$list = $this->_list->get_all();
			$id = $this->_generate_id();
			$name = 'Header layout (' . (count($list) + 1) . ')';

			if( $base ) {
				$examples = $this->_list->get_examples();

				if( isset( $examples[$base] ) ) {
					$data = json_decode( $this->_get_example_json( $base ), true );
					$structure = $data['structure'];
					$settings = $data['settings'];
					$name = $data['name'];
				} else if( isset( $list[$base] ) ) {
					$data = $this->_factory->get_header( $base );
					$structure = $data->get_structure();
					$settings = $data->get_settings();
				}

				$header = $this->_factory->create_new( $id, $name, $structure, $settings );
			} else {
				$header = $this->_factory->create_new( $id, $name );
			}

			$this->_list->add_header( $id, $name );

			return $header;
		}

		private function _get_example_json( $file ) {
			$file = WOODMART_THEMEROOT . '/inc/builder/examples/' . $file . '.json';
			ob_start();
			include $file;
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}

		private function _generate_id() {
			return 'header_' . rand(100000, 999999);
		}

		public function remove_header() {
			check_ajax_referer( 'woodmart-builder-remove-header-nonce', 'security' );

			$id = sanitize_text_field( stripslashes( $_GET['id'] ) );
			
			delete_option( 'whb_' . $id );
			delete_option( 'xts-' . $id . '-file-data' );
			delete_option( 'xts-' . $id . '-css-data' );
			delete_option( 'xts-' . $id . '-version' );
			delete_option( 'xts-' . $id . '-site-url' );
			delete_option( 'xts-' . $id . '-status' );

			echo json_encode(array(
				'list' => $this->_list->remove($id)
			));

			wp_die();

		}

		public function set_default_header() {
			check_ajax_referer( 'woodmart-builder-set-default-header-nonce', 'security' );

			$id = sanitize_text_field( stripslashes( $_GET['id'] ) );

			update_option( 'whb_main_header', $id );

			$options = get_option( 'xts-woodmart-options' );

			$options['default_header'] = $id;

			update_option( 'xts-woodmart-options', $options );

			echo json_encode(array(
				'default_header' => $id
			));

			wp_die();

		}

		public function get_default_header() {

			$id = get_option( 'whb_main_header' );

			if( ! $id ) $id = WOODMART_HB_DEFAULT_ID;

			return $id;

		}

	}

}
