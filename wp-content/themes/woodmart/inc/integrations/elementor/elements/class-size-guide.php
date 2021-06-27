<?php
/**
 * Shape map
 *
 * @package xts
 */

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * Elementor widget that inserts an embeddable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Size_Guide extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_size_guide';
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
		return esc_html__( 'Size guide', 'woodmart' );
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
		return 'wd-icon-size-guide';
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
	 *
	 * @access protected
	 */
	protected function _register_controls() {
		/**
		 * Content tab
		 */

		/**
		 *
		 * General settings
		 */
		$this->start_controls_section(
			'general_content_section',
			[
				'label' => esc_html__( 'General', 'woodmart' ),
			]
		);

		$this->add_control(
			'size_guide_id',
			[
				'label'       => esc_html__( 'Select size guide', 'woodmart' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'options'     => woodmart_get_size_guides_array( 'elementor' ),
				'default'     => '0',
			]
		);

		$this->add_control(
			'title',
			[
				'label'        => esc_html__( 'Title', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '1',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
			]
		);

		$this->add_control(
			'description',
			[
				'label'        => esc_html__( 'Description', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '1',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
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
		$default_args = array(
			'size_guide_id' => '0',
			'title'         => '1',
			'description'   => '1',
		);

		$element_args = wp_parse_args( $this->get_settings_for_display(), $default_args );

		if ( ! $element_args['size_guide_id'] ) {
			return;
		}

		$id = apply_filters( 'wpml_object_id', $element_args['size_guide_id'], 'woodmart_size_guide', true );

		echo do_shortcode( '[woodmart_size_guide id="' . $id . '" title="' . $element_args['title'] . '" description="' . $element_args['description'] . '"]' );
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Size_Guide() );
