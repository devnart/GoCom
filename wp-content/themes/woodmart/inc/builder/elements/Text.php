<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 *	Simple texteara
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_Text' ) ) {
	class WOODMART_HB_Text extends WOODMART_HB_Element {

		public function __construct() {
			parent::__construct();
			$this->template_name = 'text';
		}

		public function map() {
			$this->args = array(
				'type' => 'text',
				'title' => esc_html__( 'Text/HTML', 'woodmart' ),
				'text' => esc_html__( 'Plain text/HTML', 'woodmart' ),
				'icon' => WOODMART_ASSETS_IMAGES . '/header-builder/icons/hb-ico-texthtml.svg',
				'editable' => true,
				'container' => false,
				'edit_on_create' => true,
				'drag_target_for' => array(),
				'drag_source' => 'content_element',
				'removable' => true,
				'addable' => true,
				'params' => array(
					'content' => array(
						'id' => 'content',
						'title' => esc_html__( 'Text/HTML content', 'woodmart' ),
						'type' => 'editor',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'value' => '',
						'description' => esc_html__( 'Place your text or HTML code with WordPress shortcodes.', 'woodmart' ),
					),
					'inline' => array(
						'id' => 'inline',
						'title' => esc_html__( 'Display inline', 'woodmart' ),
						'type' => 'switcher',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'value' => false,
						'description' => esc_html__( 'The width of the element will depend on its content', 'woodmart' ),
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
