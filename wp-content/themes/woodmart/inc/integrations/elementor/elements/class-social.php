<?php
/**
 * Social buttons map.
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * Elementor widget that inserts an embeddable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Social extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_social_buttons';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Social buttons', 'woodmart' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'wd-icon-social';
	}

	/**
	 * Get widget categories.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'wd-elements' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		/**
		 * Content tab.
		 */

		/**
		 * General settings.
		 */
		$this->start_controls_section(
			'general_content_section',
			[
				'label' => esc_html__( 'General', 'woodmart' ),
			]
		);

		$this->add_control(
			'type',
			[
				'label'   => esc_html__( 'Type', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'share'  => esc_html__( 'Share', 'woodmart' ),
					'follow' => esc_html__( 'Follow', 'woodmart' ),
				],
				'default' => 'share',
			]
		);

		$this->end_controls_section();

		/**
		 * Style tab.
		 */

		/**
		 * General settings.
		 */
		$this->start_controls_section(
			'general_style_section',
			[
				'label' => esc_html__( 'General', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'size',
			[
				'label'   => esc_html__( 'Size', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default (18px)', 'woodmart' ),
					'small'   => esc_html__( 'Small (14px)', 'woodmart' ),
					'large'   => esc_html__( 'Large (22px)', 'woodmart' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'color',
			[
				'label'   => esc_html__( 'Color', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'dark'  => esc_html__( 'Dark', 'woodmart' ),
					'light' => esc_html__( 'Light', 'woodmart' ),
				],
				'default' => 'dark',
			]
		);

		$this->add_control(
			'align',
			[
				'label'   => esc_html__( 'Align', 'woodmart' ),
				'type'    => 'wd_buttons',
				'options' => [
					'left'   => [
						'title' => esc_html__( 'Left', 'woodmart' ),
						'image' => WOODMART_ASSETS_IMAGES . '/settings/align/left.jpg',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'woodmart' ),
						'image' => WOODMART_ASSETS_IMAGES . '/settings/align/center.jpg',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'woodmart' ),
						'image' => WOODMART_ASSETS_IMAGES . '/settings/align/right.jpg',
					],
				],
				'default' => 'center',
			]
		);

		$this->add_control(
			'style',
			[
				'label'   => esc_html__( 'Style', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default'     => esc_html__( 'Default', 'woodmart' ),
					'simple'      => esc_html__( 'Simple', 'woodmart' ),
					'colored'     => esc_html__( 'Colored', 'woodmart' ),
					'colored-alt' => esc_html__( 'Colored alternative', 'woodmart' ),
					'bordered'    => esc_html__( 'Bordered', 'woodmart' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'form',
			[
				'label'   => esc_html__( 'Form', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'circle' => esc_html__( 'Circle', 'woodmart' ),
					'square' => esc_html__( 'Square', 'woodmart' ),
				],
				'default' => 'circle',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings              = $this->get_settings_for_display();
		$settings['elementor'] = true;
		woodmart_shortcode_social( $settings );
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Social() );
