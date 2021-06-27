<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Backend class that enques main scripts and CSS.
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_Backend' ) ) {
	class WOODMART_HB_Backend {

		private static $_instance = null;

		private $_builder = null;

		public function __construct() {

			$this->_builder = WOODMART_Header_Builder::get_instance();

			if( isset( $_GET['tab'] ) && $_GET['tab'] === 'builder' ) $this->init();

		}

		protected function __clone() {}

		static public function get_instance() {

			if( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function init() {
			$this->_add_actions();
		}

		private function _add_actions() {
			add_action( 'admin_enqueue_scripts', array( $this, 'scripts'), 50 );
		}

		public function scripts() {

			$dev = apply_filters( 'whb_debug_mode', false );

			$assets_path = ( $dev ) ? WOODMART_HEADER_BUILDER . '/public' :  WOODMART_ASSETS ;

			wp_register_script( 'woodmart-admin-builder', $assets_path . '/js/builder.js', array(), '', true );
			wp_enqueue_style( 'woodmart-admin-builder', $assets_path . '/css/builder.css');

			wp_localize_script( 'woodmart-admin-builder', 'headerBuilder', array(
				'sceleton' => $this->_builder->factory->get_header(false)->get_structure(),
				'settings' => $this->_builder->factory->get_header(false)->get_settings(),
				'name' => WOODMART_HB_DEFAULT_NAME,
				'id' => WOODMART_HB_DEFAULT_ID,
				'headersList' => $this->_builder->list->get_all(),
				'headersExamples' => $this->_builder->list->get_examples(),
				'defaultHeader' => $this->_builder->manager->get_default_header(),
				'texts' => array(
					'managerTitle' => woodmart_get_opt( 'white_label' ) ? __('XTemos Headers Manager', 'woodmart') : 'Headers Manager',
					'description' => __('Here you can manage your header layouts, create new ones, import and export. You can set which header to use for all pages by default.', 'woodmart'),
					'createNew' => __('Create a new header', 'woodmart'),
					'import' => __('Import', 'woodmart'),
					'remove' => __('Remove header', 'woodmart'),
					'makeDefault' => __('Make default header', 'woodmart'),
					'headerSettings' => __('Header settings', 'woodmart'),
					'delete' => __('Delete', 'woodmart'),
					'Make it default' => __('Make it default', 'woodmart'),
					'Import new header' => __('Import new header', 'woodmart'),
					'Import' => __('Import', 'woodmart'),
					'JSON code for import is not valid!' => __('JSON code for import is not valid!', 'woodmart'),
					'Paste your JSON header export data here and click "Import"' => __('Paste your JSON header export data here and click "Import"', 'woodmart'),
					'Are you sure you want to remove this header?' => __('Are you sure you want to remove this header?', 'woodmart'),
					'Press OK to make this header default for all pages, Cancel to leave.' => __('Press OK to make this header default for all pages, Cancel to leave.', 'woodmart'),
					'Choose which layout you want to use as a base for your new header.' => __('Choose which layout you want to use as a base for your new header.', 'woodmart'),
					'Examples library' => __('Examples library', 'woodmart'),
					'User headers' => __('User headers', 'woodmart'),
					'Background image repeat' => __('Background image repeat', 'woodmart'),
					'Inherit' => __('Inherit', 'woodmart'),
					'No repeat' => __('No repeat', 'woodmart'),
					'Repeat All' => __('Repeat All', 'woodmart'),
					'Repeat horizontally' => __('Repeat horizontally', 'woodmart'),
					'Repeat vertically' => __('Repeat vertically', 'woodmart'),
					'Background image size' => __('Background image size', 'woodmart'),
					'Inherit' => __('Inherit', 'woodmart'),
					'Cover' => __('Cover', 'woodmart'),
					'Contain' => __('Contain', 'woodmart'),
					'Background image attachment' => __('Background image attachment', 'woodmart'),
					'Fixed' => __('Fixed', 'woodmart'),
					'Scroll' => __('Scroll', 'woodmart'),
					'Background image position' => __('Background image position', 'woodmart'),
					'Left top' => __('Left top', 'woodmart'),
					'Left center' => __('Left center', 'woodmart'),
					'Left bottom' => __('Left bottom', 'woodmart'),
					'Center top' => __('Center top', 'woodmart'),
					'Center center' => __('Center center', 'woodmart'),
					'Center bottom' => __('Center bottom', 'woodmart'),
					'Right top' => __('Right top', 'woodmart'),
					'Right center' => __('Right center', 'woodmart'),
					'Right bottom' => __('Right bottom', 'woodmart'),
					'Preview' => __('Preview', 'woodmart'),
					'Width' => __('Width', 'woodmart'),
					'Style' => __('Style', 'woodmart'),
					'Container' => __('Container', 'woodmart'),
					'fullwidth' => __('fullwidth', 'woodmart'),
					'boxed' => __('boxed', 'woodmart'),
					'Upload an image' => __('Upload an image', 'woodmart'),
					'Upload' => __('Upload', 'woodmart'),
					'Open in new window' => __('Open in new window', 'woodmart'),
					'Add element to this section' => __('Add element to this section', 'woodmart'),
					'Are you sure you want to delete this element?' => __('Are you sure you want to delete this element?', 'woodmart'),
					'Edit settings' => __('Edit settings', 'woodmart'),
					'Export this header structure' => __('Export this header structure', 'woodmart'),
					'importDescription' => __('Copy the code from the following text area and save it. You will be
						able to import it later with our import function in the headers
						manager.', 'woodmart'),
					'Save header' => __('Save header', 'woodmart'),
					'Back to headers list' => __('Back to headers list', 'woodmart'),
					'Edit' => __('Edit', 'woodmart'),
					'Clone' => __('Clone', 'woodmart'),
					'Remove' => __('Remove', 'woodmart'),
					'Add element' => __('Add element', 'woodmart'),
					'Loading, please wait...' => __('Loading, please wait...', 'woodmart'),
					'Close' => __('Close', 'woodmart'),
					'Save' => __('Save', 'woodmart'),
					'Header settings' => __('Header settings', 'woodmart'),
					'Export header' => __('Export header', 'woodmart'),
					'Desktop layout' => __('Desktop layout', 'woodmart'),
					'Mobile layout' => __('Mobile layout', 'woodmart'),
					'Header is successfully saved.' => __('Header is successfully saved.', 'woodmart'),
					'Default header for all pages is changed.' => __('Default header for all pages is changed.', 'woodmart'),
					'Configure' => __('Configure', 'woodmart'),
					'settings' => __('settings', 'woodmart'),
				)
			) );

			wp_enqueue_script( 'woodmart-admin-builder' );

			wp_enqueue_editor();
			wp_enqueue_media();

		}


	}


	$_GLOBALS['woodmart_hb_backend'] = WOODMART_HB_Backend::get_instance();
}
