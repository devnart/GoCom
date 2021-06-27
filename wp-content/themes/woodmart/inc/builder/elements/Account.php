<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Account links in the header. Login / register, my account, logout.
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_Account' ) ) {
	class WOODMART_HB_Account extends WOODMART_HB_Element {

		public function __construct() {
			parent::__construct();
			$this->template_name = 'account';
		}

		public function map() {
			$this->args = array(
				'type' => 'account',
				'title' => esc_html__( 'Account', 'woodmart' ), 
				'text' => esc_html__( 'Login/register links', 'woodmart' ), 
				'icon' => WOODMART_ASSETS_IMAGES . '/header-builder/icons/hb-ico-account.svg',
				'editable' => true,
				'container' => false,
				'edit_on_create' => true,
				'drag_target_for' => array(),
				'drag_source' => 'content_element',
				'removable' => true,
				'addable' => true,
				'params' => array(
					'display' => array(
						'id' => 'display',
						'title' => esc_html__( 'Display', 'woodmart' ), 
						'type' => 'selector',
						'tab' => esc_html__( 'General', 'woodmart' ), 
						'value' => 'text',
						'options' => array(
							'icon' => array(
								'value' => 'icon',
								'label' => esc_html__( 'Icon', 'woodmart' ),
							),
							'text' => array(
								'value' => 'text',
								'label' => esc_html__( 'Only text', 'woodmart' ),
							),
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
								'image' => WOODMART_ASSETS_IMAGES . '/header-builder/default-icons/account-default.jpg',
							),
							'custom' => array(
								'value' => 'custom',
								'label' => esc_html__( 'Custom', 'woodmart' ),
								'image' => WOODMART_ASSETS_IMAGES . '/header-builder/settings.jpg',
							),
						),
						'requires' => array(
							'display' => array(
								'comparison' => 'equal',
								'value' => 'icon'
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
					'with_username' => array(
						'id' => 'with_username',
						'title' => esc_html__( 'Show username', 'woodmart' ), 
						'type' => 'switcher',
						'tab' => esc_html__( 'General', 'woodmart' ), 
						'value' => false,
						'description' => esc_html__( 'Display username when user is logged in.', 'woodmart' ), 
					),
					'login_dropdown' => array(
						'id' => 'login_dropdown',
						'title' => esc_html__( 'Show login form', 'woodmart' ), 
						'type' => 'switcher',
						'tab' => esc_html__( 'General', 'woodmart' ), 
						'value' => true,
						'description' => esc_html__( 'Display login form dropdown on hover when user is not logged in.', 'woodmart' ), 
					),
					'form_display' => array(
						'id' => 'form_display',
						'title' => esc_html__( 'Form display', 'woodmart' ), 
						'type' => 'selector',
						'tab' => esc_html__( 'General', 'woodmart' ), 
						'value' => 'dropdown',
						'options' => array(
							'side' => array(
								'value' => 'side',
								'label' => esc_html__( 'Sidebar', 'woodmart' ), 
							),
							'dropdown' => array(
								'value' => 'dropdown',
								'label' => esc_html__( 'Dropdown', 'woodmart' ), 
							),
						),
						'requires' => array(
							'login_dropdown' => array(
								'comparison' => 'equal',
								'value' => true
							)
						),
					),
				)
			);
		}

	}

}
