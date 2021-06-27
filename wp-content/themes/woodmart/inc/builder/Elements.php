<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Include all elements classes and create their objects. AJAX handlers.
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_Elements' ) ) {
	class WOODMART_HB_Elements {

		public $elements = array(
			'Root',
			'Row',
			'Column',
			'Logo',
			'Mainmenu',
			'Menu',
			'Burger',
			'Cart',
			'Wishlist',
			'Compare',
			'Search',
			'Mobilesearch',
			'Account',
			'Categories',
			'Divider',
			'Space',
			'Text',
			'HTMLBlock',
			'Button',
			'Infobox',
			'Social'
		);

		public $elements_classes = array();

		public function __construct() {
			$this->_init();
		}

		private function _init() {
			add_action('init', array($this, 'include_files'));
			add_action('init', array($this, 'ajax_actions'));
		}

		public function include_files() {

			require_once WOODMART_HB_DIR . 'elements/abstract/Element.php';

			foreach ($this->elements as $class) {
				$path = WOODMART_HB_DIR . 'elements/' . $class . '.php';

				if( file_exists( $path ) ) {
					require_once $path;
					$class_name = 'WOODMART_HB_' . $class;
					$this->elements_classes[ $class ] = new $class_name();
				}
			}
		}

		public function ajax_actions() {
			add_action( 'wp_ajax_woodmart_get_builder_elements', array($this, 'get_elements_ajax') );
		}

		public function get_elements_ajax() {
			check_ajax_referer( 'woodmart-get-builder-elements-nonce', 'security' );

			$elements = array();

			foreach ($this->elements_classes as $el => $class) {
				$args = $class->get_args();
				if( $args['addable'] ) $elements[] = $class->get_args();
			}

			echo json_encode($elements);

			wp_die();
		}
	}

}
