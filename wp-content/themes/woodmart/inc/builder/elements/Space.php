<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 *	Empty horizontal space element
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_Space' ) ) {
	class WOODMART_HB_Space extends WOODMART_HB_Element {

		public function __construct() {
			parent::__construct();
			$this->template_name = 'space';
		}

		public function map() {
			$this->args = array(
				'type' => 'space',
				'title' => esc_html__( 'Space', 'woodmart' ),
				'text' => esc_html__( 'Horizontal spacing', 'woodmart' ),
				'icon' => WOODMART_ASSETS_IMAGES . '/header-builder/icons/hb-ico-space.svg',
				'editable' => true,
				'container' => false,
				'edit_on_create' => true,
				'drag_target_for' => array(),
				'drag_source' => 'content_element',
				'removable' => true,
				'addable' => true,
				'params' => array(
					'width' => array(
						'id' => 'width',
						'title' => esc_html__( 'Space width', 'woodmart' ),
						'type' => 'slider',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'from' => 0,
						'to'=> 200,
						'value' => 10,
						'units' => 'px',
						'description' => esc_html__( 'Determine the space width.', 'woodmart' ),
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
