<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 *	Compare icon in the header elements
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_Compare' ) ) {
	class WOODMART_HB_Compare extends WOODMART_HB_Element {

		public function __construct() {
			parent::__construct();
			$this->template_name = 'compare';
		}

		public function map() {
			$this->args = array(
				'type' => 'compare',
				'title' => esc_html__( 'Compare', 'woodmart' ),
				'text' => esc_html__( 'Compare icon', 'woodmart' ),
				'icon' => WOODMART_ASSETS_IMAGES . '/header-builder/icons/hb-ico-compare.svg',
				'editable' => true,
				'container' => false,
				'edit_on_create' => true,
				'drag_target_for' => array(),
				'drag_source' => 'content_element',
				'removable' => true,
				'addable' => true,
				'params' => array(
					'design' => array(
						'id' => 'design',
						'title' => esc_html__( 'Style', 'woodmart' ),
						'type' => 'selector',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'value' => 'icon',
						'options' => array(
							'icon' => array(
								'value' => 'icon',
								'label' => esc_html__( 'Icon', 'woodmart' ),
							),
							'text' => array(
								'value' => 'text',
								'label' => esc_html__( 'Icon with text', 'woodmart' ),
							)
						),
						'description' => esc_html__( 'You can show the icon only or display "Compare" text too.', 'woodmart' ),
					),
					'hide_product_count' => array(
						'id' => 'hide_product_count',
						'title' => esc_html__( 'Hide product count label', 'woodmart' ),
						'type' => 'switcher',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'value' => false,
						'description' => esc_html__( 'Mark this option if you want to hide product count label.', 'woodmart' ),
					),
					'icon_type' => array(
						'id' => 'icon_type',
						'title' => esc_html__( 'Icon type', 'woodmart' ),
						'type' => 'selector',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'value' => 'default',
						'options' => array(
							'default' => array(
								'value' => 'default',
								'label' => esc_html__( 'Default', 'woodmart' ),
								'image' => WOODMART_ASSETS_IMAGES . '/header-builder/default-icons/compare-default.jpg',
							),
							'custom' => array(
								'value' => 'custom',
								'label' => esc_html__( 'Custom', 'woodmart' ),
								'image' => WOODMART_ASSETS_IMAGES . '/header-builder/settings.jpg',
							),
						),
					),
					'custom_icon' => array(
						'id' => 'custom_icon',
						'title' => esc_html__( 'Custom icon', 'woodmart' ),
						'type' => 'image',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'value' => '',
						'description' => '',
						'requires' => array(
							'icon_type' => array(
								'comparison' => 'equal',
								'value' => 'custom'
							)
						),
					),
				)
			);
		}

	}

}
