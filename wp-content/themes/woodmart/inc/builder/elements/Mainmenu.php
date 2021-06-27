<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 *	Main navigtaion menu
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_Mainmenu' ) ) {
	class WOODMART_HB_Mainmenu extends WOODMART_HB_Element {

		public function __construct() {
			parent::__construct();
			$this->template_name = 'main-menu';
		}

		public function map() {
			$this->args = array(
				'type' => 'mainmenu',
				'title' => esc_html__( 'Main menu', 'woodmart' ),
				'text' => esc_html__( 'Main navigation', 'woodmart' ),
				'icon' => WOODMART_ASSETS_IMAGES . '/header-builder/icons/hb-ico-menu.svg',
				'editable' => true,
				'container' => false,
				'drg' => false,
				'drag_target_for' => array(),
				'drag_source' => 'content_element',
				'edit_on_create' => true,
				'removable' => true,
				'desktop' => true,
				'addable' => true,
				'params' => array(
					'menu_style' => array(
						'id' => 'menu_style',
						'title' => esc_html__( 'Style', 'woodmart' ),
						'type' => 'selector',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'value' => 'default',
						'options' => array(
							'default' => array(
								'value' => 'default',
								'label' => esc_html__( 'Default', 'woodmart' ),
							),
							'underline' => array(
								'value' => 'underline',
								'label' => esc_html__( 'Underline', 'woodmart' ),
							),
							'bordered' => array(
								'value' => 'bordered',
								'label' => esc_html__( 'Bordered', 'woodmart' ),
							),
							'separated' => array(
								'value' => 'separated',
								'label' => esc_html__( 'Separated', 'woodmart' ),
							),
						),
						'description' => esc_html__( 'You can change menu style in the header.', 'woodmart' ),
					),
					'menu_align' => array(
						'id' => 'menu_align',
						'title' => esc_html__( 'Menu align', 'woodmart' ),
						'type' => 'selector',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'value' => 'left',
						'options' => array(
							'left' => array(
								'value' => 'left',
								'label' => esc_html__( 'Left', 'woodmart' ),
							),
							'center' => array(
								'value' => 'center',
								'label' => esc_html__( 'Center', 'woodmart' ),
							),
							'right' => array(
								'value' => 'right',
								'label' => esc_html__( 'Right', 'woodmart' ),
							),
						),
						'description' => esc_html__( 'Set the menu items text align.', 'woodmart' ),
					),
					'full_screen' => array(
						'id' => 'full_screen',
						'title' => esc_html__( 'Full screen menu', 'woodmart' ),
						'type' => 'switcher',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'value' => false,
						'description' => esc_html__( 'Enable to show your menu in full screen style on burger icon click.', 'woodmart' ),
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
								'image' => WOODMART_ASSETS_IMAGES . '/header-builder/default-icons/burger-default.jpg',
							),
							'custom' => array(
								'value' => 'custom',
								'label' => esc_html__( 'Custom', 'woodmart' ),
								'image' => WOODMART_ASSETS_IMAGES . '/header-builder/settings.jpg',
							),
						),
						'requires' => array(
							'full_screen' => array(
								'comparison' => 'equal',
								'value' => true
							)
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
