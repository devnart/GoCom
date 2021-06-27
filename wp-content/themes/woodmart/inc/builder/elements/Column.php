<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Basic structure element - column
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_Column' ) ) {
	class WOODMART_HB_Column extends WOODMART_HB_Element {

		public function __construct() {
			parent::__construct();
			$this->template_name = 'column';
		}

		public function map() {
			$this->args = array(
				'type' => 'column',
				'title' => esc_html__( 'Column', 'woodmart' ),
				'text' => esc_html__( 'Column', 'woodmart' ),
				'editable' => false,
				'container' => true,
				'edit_on_create' => false,
				'drag_target_for' => array('content_element'),
				'drag_source' => '',
				'removable' => false,
				'class' => '',
				'addable' => false,
				'it_works' => 'column',
				'content' => array()
			);
		}

	}

}