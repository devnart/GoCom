<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Mobile menu burger icon
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_Burger' ) ) {
	class WOODMART_HB_Burger extends WOODMART_HB_Element {

		public function __construct() {
			parent::__construct();
			$this->template_name = 'burger';
		}

		public function map() {
			$options = $this->get_menu_options();
			$first = reset($options);

			$this->args = array(
				'type' => 'burger',
				'title' => esc_html__( 'Mobile menu', 'woodmart' ), 
				'text' => esc_html__( 'Mobile burger icon', 'woodmart' ), 
				'icon' => WOODMART_ASSETS_IMAGES . '/header-builder/icons/hb-ico-mobile-menu.svg',
				'editable' => true,
				'container' => false,
				'edit_on_create' => true,
				'drag_target_for' => array(),
				'drag_source' => 'content_element',
				'removable' => true,
				'addable' => true,
				'params' => array(
					'style' => array(
						'id' => 'style',
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
							),
						),
						'description' => esc_html__( 'You can change the burger icon style.', 'woodmart' ), 
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
					'position' => array(
						'id' => 'position',
						'title' => esc_html__( 'Position', 'woodmart' ), 
						'type' => 'selector',
						'tab' => esc_html__( 'General', 'woodmart' ), 
						'value' => 'left',
						'options' => array(
							'left' => array(
								'value' => 'left',
								'label' => esc_html__( 'Left', 'woodmart' ), 
							),
							'right' => array(
								'value' => 'right',
								'label' => esc_html__( 'Right', 'woodmart' ), 
							),
						),
						'description' => esc_html__( 'Position of the mobile menu sidebar.', 'woodmart' ), 
					),
					'search_form' => array(
						'id' => 'search_form',
						'title' => esc_html__( 'Show search form', 'woodmart' ), 
						'type' => 'switcher',
						'tab' => esc_html__( 'General', 'woodmart' ), 
						'value' => true,
					),
					'categories_menu' => array(
						'id' => 'categories_menu',
						'title' => esc_html__( 'Show categories menu', 'woodmart' ), 
						'type' => 'switcher',
						'tab' => esc_html__( 'General', 'woodmart' ), 
						'value' => false,
					),
					'menu_id' => array(
						'id' => 'menu_id',
						'title' => esc_html__( 'Choose menu', 'woodmart' ), 
						'type' => 'select',
						'tab' => esc_html__( 'General', 'woodmart' ), 
						'value' => isset( $first['value'] ) ? $first['value'] : '',
						'options' => $options,
						'description' => esc_html__( 'Choose which menu to display.', 'woodmart' ), 
						'requires' => array(
							'categories_menu' => array(
								'comparison' => 'equal',
								'value' => true
							)
						),
					),
					'tabs_swap' => array(
						'id' => 'tabs_swap',
						'title' => esc_html__( 'Swap menus', 'woodmart' ),
						'type' => 'switcher',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'value' => false,
					),
				)
			);
		}

	}

}
