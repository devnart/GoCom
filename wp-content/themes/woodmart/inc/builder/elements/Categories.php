<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 *	Get categories dropdown vertical menu
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_Categories' ) ) {
	class WOODMART_HB_Categories extends WOODMART_HB_Element {

		public function __construct() {
			parent::__construct();
			$this->template_name = 'categories';
		}

		public function map() {
			$options = $this->get_menu_options();
			$first = reset($options);
			
			$this->args = array(
				'type' => 'categories',
				'title' => esc_html__( 'Categories', 'woodmart' ),
				'text' => esc_html__( 'Categories dropdown', 'woodmart' ),
				'icon' => WOODMART_ASSETS_IMAGES . '/header-builder/icons/hb-ico-category.svg',
				'editable' => true,
				'container' => false,
				'edit_on_create' => true,
				'drag_target_for' => array(),
				'drag_source' => 'content_element',
				'removable' => true,
				'addable' => true,
				'desktop' => true,
				'params' => array(
					'menu_id' => array(
						'id' => 'menu_id',
						'title' => esc_html__( 'Choose menu', 'woodmart' ),
						'type' => 'select',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'value' => isset( $first['value'] ) ? $first['value'] : '',
						'options' => $options,
						'description' => esc_html__( 'Choose which menu to display in the header as a categories dropdown.', 'woodmart' ),
					),
					'more_cat_button' => array(
						'id' => 'more_cat_button',
						'title' => esc_html__( 'Limit categories', 'woodmart' ),
						'type' => 'switcher',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'value' => false,
						'description' => esc_html__( 'Display a certain number of categories and "show more" button', 'woodmart' ),
					),
					'more_cat_button_count' => array(
						'id' => 'more_cat_button_count',
						'title' => esc_html__( 'Number of categories', 'woodmart' ),
						'description' => esc_html__( 'Specify the number of categories to be shown initially', 'woodmart' ),
						'type' => 'slider',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'from' => 1,
						'to'=> 100,
						'value' => 5,
						'units' => '',
						'requires' => array(
							'more_cat_button' => array(
								'comparison' => 'equal',
								'value' => true
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
					'color_scheme' => array(
						'id' => 'color_scheme',
						'title' => esc_html__( 'Text color scheme', 'woodmart' ),
						'type' => 'selector',
						'tab' => esc_html__( 'Colors', 'woodmart' ),
						'value' => 'light',
						'options' => array(
							'dark' => array(
								'value' => 'dark',
								'label' => esc_html__( 'Dark', 'woodmart' ),
							),
							'light' => array(
								'value' => 'light',
								'label' => esc_html__( 'Light', 'woodmart' ),
							),
						),
						'description' => esc_html__( 'Select different text color scheme depending on your header background.', 'woodmart' ),
					),
					'background' => array(
						'id' => 'background',
						'title' => esc_html__( 'Background settings', 'woodmart' ),
						'type' => 'bg',
						'tab' => esc_html__( 'Colors', 'woodmart' ),
						'value' => '',
						'description' => ''
					),
					'border' => array(
						'id' => 'border',
						'title' => esc_html__( 'Border', 'woodmart' ),
						'type' => 'border',
						'sides' => array( 'bottom', 'top', 'left', 'right' ),
						'tab' => esc_html__( 'Colors', 'woodmart' ),
						'colorpicker_top' => true,
						'container' => false,
						'value' => '',
						'description' => esc_html__( 'Border settings for this element.', 'woodmart' ),
					),
				)
			);
		}

	}

}
