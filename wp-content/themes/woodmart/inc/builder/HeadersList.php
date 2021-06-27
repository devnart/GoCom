<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Manage headers lists in the database. CRUD
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_HeadersList' ) ) {
	class WOODMART_HB_HeadersList {

		public function set_default( $id ) {
			update_option( 'whb_main_header', $id );
		}

		public function get_default() {

			$id = get_option( 'whb_main_header' );

			if( ! $id ) $id = WOODMART_HB_DEFAULT_ID;

			return $id;
		}

		public function get_all() {
			$default_id = WOODMART_HB_DEFAULT_ID;
			
			$list = array( $default_id => array(
				'id' => WOODMART_HB_DEFAULT_ID,
				'name' => WOODMART_HB_DEFAULT_NAME
			) );

			$header = get_option( 'whb_' . $default_id );

			if( isset( $header['name'] ) ) {
				$list[ $default_id ]['name'] = $header['name'];
			}

			$saved_headers = get_option('whb_saved_headers');

			if( ! empty( $saved_headers ) && is_array( $saved_headers ) ) {
				$list = array_merge( $list, $saved_headers );
			}

			return $list;
		}

		private function _is_exists( $id ) {
			$list = $this->get_all();

			return ( isset($list[$id]) && get_option($this->_option_name . '_' . $id) );
		}

		public function add_header($id = false, $name = false ) {
			$list = $this->get_all();

			$list[$id] = array(
				'id' => $id,
				'name' => $name
			);

			update_option('whb_saved_headers', $list);

			return $list;
		}

		public function remove($id) {
			$list = $this->get_all();
			if(isset( $list[ $id ] ) ) unset($list[$id]);
			update_option('whb_saved_headers', $list);
			return $list;
		}

		public function get_examples() {
			return woodmart_get_config( 'header-examples' );
		}

	}

}
