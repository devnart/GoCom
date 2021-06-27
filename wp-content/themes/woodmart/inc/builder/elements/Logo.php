<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Logo image with uploader
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_Logo' ) ) {
	class WOODMART_HB_Logo extends WOODMART_HB_Element {

		public function __construct() {
			parent::__construct();
			$this->template_name = 'logo';
		}

		public function map() {
			$this->args = array(
				'type' => 'logo',
				'title' => esc_html__( 'Logo', 'woodmart' ),
				'icon' => WOODMART_ASSETS_IMAGES . '/header-builder/icons/hb-ico-logo.svg',
				'text' => wp_kses( __( 'Website\'s logo', 'woodmart' ), 'default' ),
				'editable' => true,
				'container' => false,
				'edit_on_create' => true,
				'drag_target_for' => array(),
				'drag_source' => 'content_element',
				'removable' => true,
				'addable' => true,
				'params' => array(
					'image' => array(
						'id' => 'image',
						'title' => esc_html__( 'Logo image', 'woodmart' ),
						'type' => 'image',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'value' => '',
						'description' => ''
					),
					'width' => array(
						'id' => 'width',
						'title' => esc_html__( 'Logo width', 'woodmart' ),
						'type' => 'slider',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'from' => 10,
						'to'=> 500,
						'value' => 150,
						'units' => 'px',
						'description' => esc_html__( 'Determine the logo image width in pixels.', 'woodmart' ),
					),
					'sticky_image' => array(
						'id' => 'sticky_image',
						'title' => esc_html__( 'Logo image for sticky header', 'woodmart' ),
						'type' => 'image',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'value' => '',
						'description' => ''
					),
					'sticky_width' => array(
						'id' => 'sticky_width',
						'title' => esc_html__( 'Sticky header logo width', 'woodmart' ),
						'type' => 'slider',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'from' => 10,
						'to'=> 500,
						'value' => 150,
						'units' => 'px',
						'description' => esc_html__( 'Determine the logo on the sticky header image width in pixels.', 'woodmart' ),
					),
					'width_height' => array(
						'id' => 'width_height',
						'title' => esc_html__( 'Add width and height attributes', 'woodmart' ),
						'type' => 'switcher',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'value' => false,
					),
				)
			);
		}

	}

}
