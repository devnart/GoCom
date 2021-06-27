<?php
/**
 * Mailchimp map
 *
 * @package xts
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
class Mailchimp extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_mailchimp';
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
		return esc_html__( 'Mailchimp', 'woodmart' );
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
		return 'wd-icon-mailchimp';
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
	 * Get mailchimp forms.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Mailchimp forms.
	 */
	public function get_mailchimp_forms() {
		$forms = get_posts(
			[
				'post_type'   => 'mc4wp-form',
				'numberposts' => -1,
			]
		);

		$mailchimp_forms = [];

		if ( $forms ) {
			foreach ( $forms as $form ) {
				$mailchimp_forms[ $form->ID ] = $form->post_title;
			}
		}

		return $mailchimp_forms;
	}

	/**
	 * Register the widget controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		/**
		 * Content tab
		 */

		/**
		 * General settings
		 */
		$this->start_controls_section(
			'general_content_section',
			[
				'label' => esc_html__( 'General', 'woodmart' ),
			]
		);

		$this->add_control(
			'form_id',
			[
				'label'       => esc_html__( 'Select mailchimp', 'woodmart' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'options'     => $this->get_mailchimp_forms(),
				'default'     => '0',
			]
		);

		$this->end_controls_section();

		/**
		 * Style tab
		 */

		/**
		 * General settings
		 */
		$this->start_controls_section(
			'general_style_section',
			[
				'label' => esc_html__( 'General', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'color_scheme',
			[
				'label'   => esc_html__( 'Color Scheme', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'inherit' => esc_html__( 'Inherit', 'woodmart' ),
					'light'   => esc_html__( 'Light', 'woodmart' ),
					'dark'    => esc_html__( 'Dark', 'woodmart' ),
				],
				'default' => 'inherit',
			]
		);

		$this->add_control(
			'alignment',
			[
				'label'   => esc_html__( 'Content alignment', 'woodmart' ),
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
		
		$this->add_responsive_control(
			'content_width',
			[
				'label'          => esc_html__( 'Content width', 'woodmart' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units'     => [ 'px', '%' ],
				'range'          => [
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .mc4wp-form-fields' => 'max-width: {{SIZE}}{{UNIT}};',
				],
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
		$default_settings = array(
			'form_id'      => '0',
			'color_scheme' => 'inherit',
			'alignment'    => 'center',
		);

		$settings = wp_parse_args( $this->get_settings_for_display(), $default_settings );

		$form_classes = '';

		// Form classes.
		if ( 'inherit' !== $settings['color_scheme'] ) {
			$form_classes .= ' color-scheme-' . $settings['color_scheme'];
		}
		$form_classes .= ' text-' . $settings['alignment'];

		if ( ! $settings['form_id'] || ! defined( 'MC4WP_VERSION' ) ) {
			return;
		}

		echo do_shortcode( '[mc4wp_form id="' . esc_attr( $settings['form_id'] ) . '" element_class="' . esc_attr( $form_classes ) . '"]' );
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Mailchimp() );

