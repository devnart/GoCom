<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Simple vertical line
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_Divider' ) ) {
	class WOODMART_HB_Divider extends WOODMART_HB_Element {

		public function __construct() {
			parent::__construct();
			$this->template_name = 'divider';
		}

		public function map() {
			$this->args = array(
				'type' => 'divider',
				'title' => esc_html__( 'Divider', 'woodmart' ),
				'text' => esc_html__( 'Simple vertical line', 'woodmart' ),
				'icon' => WOODMART_ASSETS_IMAGES . '/header-builder/icons/hb-ico-vertical-line.svg',
				'editable' => true,
				'container' => false,
				'edit_on_create' => true,
				'drag_target_for' => array(),
				'drag_source' => 'content_element',
				'removable' => true,
				'addable' => true,
				'params' => array(
					'full_height' => array(
						'id' => 'full_height',
						'title' => esc_html__( 'Full height', 'woodmart' ),
						'type' => 'switcher',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'value' => false,
						'description' => esc_html__( 'Mark this option if you want to show this divider line on the full height for this row.', 'woodmart' ),
					),
					'css_class' => array(
						'id' => 'css_class',
						'title' => esc_html__( 'Additional CSS class', 'woodmart' ),
						'type' => 'text',
						'tab' => esc_html__( 'Styles', 'woodmart' ),
						'value' => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' ),
					),
				)
			);
		}

	}

}
