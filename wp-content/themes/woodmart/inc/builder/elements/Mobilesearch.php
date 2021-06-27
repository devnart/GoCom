<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 *	Search icon for mobile devices
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_Mobilesearch' ) ) {
	class WOODMART_HB_Mobilesearch extends WOODMART_HB_Element {

		public function __construct() {
			parent::__construct();
			$this->template_name = 'mobile-search';
		}

		public function map() {
			$this->args = array(
				'type' => 'mobilesearch',
				'title' => esc_html__( 'Search', 'woodmart' ),
				'text' => esc_html__( 'Search form', 'woodmart' ),
				'icon' => WOODMART_ASSETS_IMAGES . '/header-builder/icons/hb-ico-search.svg',
				'editable' => true,
				'container' => false,
				'edit_on_create' => true,
				'drag_target_for' => array(),
				'drag_source' => 'content_element',
				'removable' => true,
				'addable' => true,
				'mobile' => true,
				'params' => array(
					'display' => array(
						'id' => 'display',
						'title' => esc_html__( 'Display', 'woodmart' ),
						'type' => 'selector',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'value' => 'icon',
						'options' => array(
							'icon' => array(
								'value' => 'icon',
								'label' => esc_html__( 'Icon', 'woodmart' ),
							),
							'form' => array(
								'value' => 'form',
								'label' => esc_html__( 'Form', 'woodmart' ),
							),
						),
						'description' => esc_html__( 'Display search icon/form in the header in different views.', 'woodmart' ),
					),
					'search_style' => array(
						'id' => 'search_style',
						'title' => esc_html__( 'Search style', 'woodmart' ),
						'type' => 'selector',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'value' => 'default',
						'options' => array(
							'default' => array(
								'value' => 'default',
								'label' => esc_html__( 'Default', 'woodmart' ),
								'image' => WOODMART_ASSETS_IMAGES . '/header-builder/search/default.jpg',
							),
							'with-bg' => array(
								'value' => 'with-bg',
								'label' => esc_html__( 'With background', 'woodmart' ),
								'image' => WOODMART_ASSETS_IMAGES . '/header-builder/search/with-bg.jpg',
							),
						),
						'requires' => array(
							'display' => array(
								'comparison' => 'equal',
								'value' => 'form'
							)
						),
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
								'image' => WOODMART_ASSETS_IMAGES . '/header-builder/default-icons/search-default.jpg',
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
