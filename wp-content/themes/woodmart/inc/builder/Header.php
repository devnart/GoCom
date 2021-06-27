<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Class to handle header structure. Save/get to/from the database.
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_Header' ) ) {
	class WOODMART_HB_Header {

		private $_elements;

		private $_id = 'none';

		private $_name = 'none';

		private $_structure;

		private $_settings;

		private $_storage;

		private $_header_options = array();

		private $_structure_elements = array('top-bar', 'general-header', 'header-bottom');

		private $_structure_elements_types = array('logo', 'search', 'cart', 'wishlist', 'account', 'compare', 'burger', 'mainmenu');

		public function __construct( $elements, $id, $new = false ) {
			$this->_elements = $elements;
			$this->_id = ( $id ) ? $id : WOODMART_HB_DEFAULT_ID;

			if( $new ) {
				$this->_create_empty();
			} else {
				$this->_load();
			}
			
			$this->_storage = new WOODMART_Stylesstorage( $this->get_id(), 'option', '', false );
		}

		private function _create_empty() {
			$this->set_settings();
			$this->set_structure();
		}

		private function _load() {
			// Get data from the database
			$data = get_option( 'whb_' . $this->get_id() );

			$name = ( isset( $data['name'] ) ) ? $data['name'] : WOODMART_HB_DEFAULT_NAME;
			$settings = ( isset( $data['settings'] ) ) ? $data['settings'] : array();
			$structure = ( isset( $data['structure'] ) ) ? $data['structure'] : false;

			$this->set_name( $name );
			$this->set_settings( $settings );
			$this->set_structure( $structure );

		}

		public function set_name( $name ) {
			$this->_name = $name;
		}

		public function set_structure( $structure = false ) {
			if( ! $structure ) $structure = woodmart_get_config( 'header-builder-structure' );
			$this->_structure = $structure;
		}

		public function set_settings( $settings = array() ) {
			$this->_settings = $settings;
		}

		public function get_id() {
			return $this->_id;
		}

		public function get_name() {
			return $this->_name;
		}

		public function get_structure() {
			return $this->_validate_structure( $this->_structure );
		}

		public function get_settings() {
			return $this->_validate_settings( $this->_settings );
		}

		private function _validate_structure( $structure ) {
			$structure = $this->_validate_sceleton( $structure );
			$structure = $this->_validate_element( $structure );
			return $structure;
		}

		public function save() {
			$styles = new WOODMART_HB_Styles();

			$this->_storage->write( $styles->get_all_css( $this->get_structure(), $this->get_options() ) );

			update_option( 'whb_' . $this->get_id(), $this->get_raw_data() );
		}

		public function get_raw_data() {
			return array(
				'structure' => $this->_structure,
				'settings' => $this->_settings,
				'name' => $this->get_name(),
				'id' => $this->get_id()
			);
		}

		public function get_data() {
			return array(
				'structure' => $this->get_structure(),
				'settings' => $this->get_settings(),
				'name' => $this->get_name(),
				'id' => $this->get_id()
			);
		}

		private function _set_header_options( $elements ) {
			foreach ($elements as $element => $params) {
				if( ! in_array( $element, array_merge( $this->_structure_elements, $this->_structure_elements_types ) ) ) continue;
				foreach ($params as $key => $param) {
					if( isset( $param['value'] ) )
						$this->_header_options[ $element ][ $key ] = $param['value'];
				}
			}
		}

		public function get_options() {
			$this->_validate_settings( $this->_settings );
			return $this->_transform_settings_to_values( $this->_header_options );
		}

		private function _validate_settings( $settings ) {
			$default_settings = woodmart_get_config('header-builder-settings');

			$settings = $this->_validate_element_params( $settings, $default_settings );

			$this->_header_options = array_merge($settings, $this->_header_options);

			return $settings;
		}

		private function _transform_settings_to_values( $settings ) {
			foreach ($settings as $key => $value) {
				if( isset( $value['value'] ) ) $settings[$key] = $value['value'];
				if( in_array( $key, $this->_structure_elements ) ) {
					if( $value['hide_desktop'] ) $settings[$key]['height'] = 0;
					if( $value['hide_mobile'] ) $settings[$key]['mobile_height'] = 0;
				}

			}
			return $settings;
		}

		private function _validate_sceleton( $structure ) {

			$sceleton = $this->get_header_sceleton();

			$structure_params = $this->_grab_params_from_elements( $structure['content'] );

			$this->_set_header_options( $structure_params );

			$structure_elements = $this->_grab_content_from_elements( $structure['content'] );

			$sceleton = $this->fill_sceleton_with_params( $sceleton, $structure_params );
			$structure = $this->fill_sceleton_with_elements( $sceleton, $structure_elements );

			return $structure;
		}

		private function _grab_params_from_elements( $elements ) {

			$params = array();

			foreach ($elements as $key => $element) {

				if( isset( $element['params'] ) && is_array( $element['params'] ) ) {
					$params[$element['id']] = $element['params'];
				}

				if( in_array( $element['type'], $this->_structure_elements_types ) ) {
					$params[$element['type']] = $element['params'];
				}

				if( isset( $element['content'] ) && is_array( $element['content'] ) ) {
					$params = array_merge( $params, $this->_grab_params_from_elements( $element['content'] ) );
				}
			}

			return $params;
		}

		private function _grab_content_from_elements( $elements, $parent = 'root' ) {

			$structure_elements = array();
			$structure_elements[$parent] = array();

			foreach ($elements as $key => $element) {
				if( isset( $element['content'] ) && is_array( $element['content'] ) ) {
					$structure_elements = array_merge( $structure_elements, $this->_grab_content_from_elements( $element['content'], $element['id'] ) );
				} else {
					$structure_elements[$parent][$element['id']] = $element;
				}
			}

			if( empty( $structure_elements[$parent] ) ) unset( $structure_elements[$parent] );

			return $structure_elements;
		}

		public function get_header_sceleton() {
			return woodmart_get_config('header-sceleton');
		}

		public function fill_sceleton_with_elements( $sceleton, $default_structure ) {
			$sceleton = $this->_fill_element_with_content( $sceleton, $default_structure );

			return $sceleton;
		}

		private function _fill_element_with_content( $element, $structure ) {

			if( empty( $element['content'] ) && isset( $structure[ $element['id'] ] ) ) {
				$element['content'] = $structure[ $element['id'] ];
			} elseif( isset( $element['content'] ) && is_array( $element['content'] ) ) {
				$element['content'] = $this->_fill_elements_with_content( $element['content'], $structure );
			}

			return $element;
		}

		private function _fill_elements_with_content( $elements, $structure ) {
			foreach ($elements as $id => $element) {
				$elements[ $id ] = $this->_fill_element_with_content( $element, $structure );
			}

			return $elements;
		}

		public function fill_sceleton_with_params( $sceleton, $params ) {
			$sceleton = $this->_fill_element_with_params( $sceleton, $params );

			return $sceleton;
		}

		private function _fill_element_with_params( $element, $params ) {

			if( empty( $element['params'] ) && isset( $params[ $element['id'] ] ) ) {
				$element['params'] = $params[ $element['id'] ];
			} elseif( isset( $element['content'] ) && is_array( $element['content'] ) ) {
				$element['content'] = $this->_fill_elements_with_params( $element['content'], $params );
			}

			return $element;
		}

		private function _fill_elements_with_params( $elements, $params ) {
			foreach ($elements as $id => $element) {
				$elements[ $id ] = $this->_fill_element_with_params( $element, $params );
			}

			return $elements;
		}

		private function _validate_elements( $elements ) {
			foreach ($elements as $key => $element ) {
				$elements[$key] = $this->_validate_element( $element );
			}

			return $elements;
		}

		private function _validate_element( $el ) {

			$type = ucfirst($el['type']);

			if( ! isset( $this->_elements->elements_classes[$type] ) ) return $el;

			$elClass = $this->_elements->elements_classes[$type];

			$default = $elClass->get_args();

			$el = $this->_validate_element_args( $el, $default );

			return $el;
		}

		private function _validate_element_args( $args, $default ) {

			foreach( $default as $key => $value ) {
				if( $key == 'params' && isset( $args[ $key ] ) ) {
					$args[ $key ] = $this->_validate_element_params( $args[ $key ], $value );
				} elseif( $key == 'content' && isset( $args[ $key ] ) ) {
					$args[ $key ] = $this->_validate_elements( $args[ $key ] );
				} elseif( ! isset( $args[ $key ] ) ) {
					$args[ $key ] = $value;
				}
			}

			return $args;
		}

		private function _validate_element_params( $params, $default ) {

			$params = wp_parse_args( $params, $default );

			foreach( $params as $key => $value ) {
				if( ! isset( $default[ $key ] ) ) {
					unset( $params[ $key ] );
				} else {
					$params[ $key ] = $this->_validate_param( $params[ $key ], $default[ $key ] ) ;
				}
			}

			return $params;
		}

		private function _validate_param( $args, $default_args ) {
			foreach ($default_args as $key => $value) {
				// Validate image param by ID
				if( $args['type'] == 'image' && ! empty( $args['value'] ) && ! empty( $args['value']['id'] ) ) {
					$attachment = wp_get_attachment_image_src( $args['value']['id'], 'full' );
					if( isset( $attachment[0] ) && ! empty( $attachment[0] ) ) {
						$args['value']['url'] = $attachment[0];
						$args['value']['width'] = $attachment[1];
						$args['value']['height'] = $attachment[2];
					} else {
						$args['value'] = '';
					}
				}

				if ( $args['type'] == 'border' && isset( $default_args['sides'] ) && is_array( $args['value'] ) ) {

					$args['value']['sides'] = $default_args['sides'];
				}
				
				if( $key != 'value' || ( $key == 'value' && ! isset( $args['value'] ) ) ) $args[ $key ] = $value;
			}

			return $args;
		}

	}

}
