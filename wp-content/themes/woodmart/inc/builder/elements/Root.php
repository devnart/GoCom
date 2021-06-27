<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Root element. Required for the structure only. Can hold one element only.
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_Root' ) ) {
	class WOODMART_HB_Root extends WOODMART_HB_Element {

		public function __construct() {
			parent::__construct();
			$this->template_name = 'root';
		}

		public function map() {
			$this->args = array(
				'type' => 'root',
				'title' => esc_html__( 'Root', 'woodmart' ),
				'text' => esc_html__( 'Root', 'woodmart' ),
				'editable' => false,
				'container' => false,
				'edit_on_create' => false,
				'drag_target_for' => array(),
				'drag_source' => '',
				'removable' => false,
				'addable' => false,
				'class' => '',
				'it_works' => 'root',
				'content' => array()
			);
		}

	}

}