<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 *	Shopping cart widget element
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_Cart' ) ) {
	class WOODMART_HB_Cart extends WOODMART_HB_Element {

		public function __construct() {
			parent::__construct();
			$this->template_name = 'cart';
		}

		public function map() {
			$this->args = array(
				'type' => 'cart',
				'title' => esc_html__( 'Cart', 'woodmart' ),
				'text' => esc_html__( 'Shopping widget', 'woodmart' ),
				'icon' => WOODMART_ASSETS_IMAGES . '/header-builder/icons/hb-ico-cart.svg',
				'editable' => true,
				'container' => false,
				'edit_on_create' => true,
				'drag_target_for' => array(),
				'drag_source' => 'content_element',
				'removable' => true,
				'addable' => true,
				'params' => array(
					'position' => array(
						'id' => 'position',
						'title' => esc_html__( 'Position', 'woodmart' ),
						'type' => 'selector',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'value' => 'side',
						'options' => array(
							'side' => array(
								'value' => 'side',
								'label' => esc_html__( 'Hidden sidebar', 'woodmart' ),
							),
							'dropdown' => array(
								'value' => 'dropdown',
								'label' => esc_html__( 'Dropdown', 'woodmart' ),
							),
							'without' => array(
								'value' => 'without',
								'label' => esc_html__( 'Without', 'woodmart' ),
							),
						),
					),
					'style' => array(
						'id' => 'style',
						'title' => esc_html__( 'Style', 'woodmart' ),
						'type' => 'selector',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'value' => '1',
						'options' => array(
							'1' => array(
								'value' => '1',
								'label' => esc_html__( 'First', 'woodmart' ),
								'image' => WOODMART_ASSETS_IMAGES . '/header-builder/cart-icons/first.jpg',
							),
							'2' => array(
								'value' => '2',
								'label' => esc_html__( 'Second', 'woodmart' ),
								'image' => WOODMART_ASSETS_IMAGES . '/header-builder/cart-icons/second.jpg',
							),
							'3' => array(
								'value' => '3',
								'label' => esc_html__( 'Third', 'woodmart' ),
								'image' => WOODMART_ASSETS_IMAGES . '/header-builder/cart-icons/third.jpg',
							),
							'4' => array(
								'value' => '4',
								'label' => esc_html__( 'Fourth', 'woodmart' ),
								'image' => WOODMART_ASSETS_IMAGES . '/header-builder/cart-icons/fourth.jpg',
							),
							'5' => array(
								'value' => '5',
								'label' => esc_html__( 'Fifths', 'woodmart' ),
								'image' => WOODMART_ASSETS_IMAGES . '/header-builder/cart-icons/fifths.jpg',
							),
						),
					),
					'icon_type' => array(
						'id' => 'icon_type',
						'title' => esc_html__( 'Icon type', 'woodmart' ),
						'type' => 'selector',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'value' => 'cart',
						'options' => array(
							'cart' => array(
								'value' => 'cart',
								'label' => esc_html__( 'Cart', 'woodmart' ),
								'image' => WOODMART_ASSETS_IMAGES . '/header-builder/cart-icons/cart.jpg',
							),
							'bag' => array(
								'value' => 'bag',
								'label' => esc_html__( 'Bag', 'woodmart' ),
								'image' => WOODMART_ASSETS_IMAGES . '/header-builder/cart-icons/bag.jpg',
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
