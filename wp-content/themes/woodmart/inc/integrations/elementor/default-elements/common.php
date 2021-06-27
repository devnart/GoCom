<?php
/**
 * Elementor common custom controls
 *
 * @package xts
 */

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'woodmart_common_before_render' ) ) {
	/**
	 * Common before render.
	 *
	 * @since 1.0.0
	 *
	 * @param object $widget Element.
	 */
	function woodmart_common_before_render( $widget ) {
		$settings = $widget->get_settings_for_display();

		if ( isset( $settings['element_parallax'] ) && $settings['element_parallax'] ) {
			woodmart_enqueue_js_library( 'parallax-scroll-bundle' );
		}
	}

	add_action( 'elementor/frontend/widget/before_render', 'woodmart_common_before_render', 10 );
}

if ( ! function_exists( 'woodmart_add_element_custom_controls' ) ) {
	/**
	 * Elements custom controls
	 *
	 * @since 1.0.0
	 *
	 * @param object $element The control.
	 */
	function woodmart_add_elements_custom_controls( $element ) {
		$element->start_controls_section(
			'wd_extra',
			[
				'label' => esc_html__( '[XTemos] Extra', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		/**
		 * Element parallax on scroll
		 */
		$element->add_control(
			'element_parallax',
			[
				'label'        => esc_html__( 'Parallax on scroll', 'woodmart' ),
				'description'  => esc_html__( 'Smooth element movement when you scroll the page to create beautiful parallax effect.', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'parallax-on-scroll',
				'render_type'  => 'template',
				'prefix_class' => 'wd-',
			]
		);

		$element->add_control(
			'scroll_x',
			[
				'label'        => esc_html__( 'X axis translation', 'woodmart' ),
				'description'  => esc_html__( 'Recommended -200 to 200', 'woodmart' ),
				'type'         => Controls_Manager::TEXT,
				'default'      => 0,
				'render_type'  => 'template',
				'prefix_class' => 'wd_scroll_x_',
				'condition'    => [
					'element_parallax' => [ 'parallax-on-scroll' ],
				],
			]
		);

		$element->add_control(
			'scroll_y',
			[
				'label'        => esc_html__( 'Y axis translation', 'woodmart' ),
				'description'  => esc_html__( 'Recommended -200 to 200', 'woodmart' ),
				'type'         => Controls_Manager::TEXT,
				'default'      => - 80,
				'render_type'  => 'template',
				'prefix_class' => 'wd_scroll_y_',
				'condition'    => [
					'element_parallax' => [ 'parallax-on-scroll' ],
				],
			]
		);

		$element->add_control(
			'scroll_z',
			[
				'label'        => esc_html__( 'Z axis translation', 'woodmart' ),
				'description'  => esc_html__( 'Recommended -200 to 200', 'woodmart' ),
				'type'         => Controls_Manager::TEXT,
				'default'      => 0,
				'render_type'  => 'template',
				'prefix_class' => 'wd_scroll_z_',
				'condition'    => [
					'element_parallax' => [ 'parallax-on-scroll' ],
				],
			]
		);

		$element->add_control(
			'scroll_smoothness',
			[
				'label'        => esc_html__( 'Parallax smoothness', 'woodmart' ),
				'description'  => esc_html__( 'Define the parallax smoothness on mouse scroll. By default - 30', 'woodmart' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					'10'  => '10',
					'20'  => '20',
					'30'  => '30',
					'40'  => '40',
					'50'  => '50',
					'60'  => '60',
					'70'  => '70',
					'80'  => '80',
					'90'  => '90',
					'100' => '100',
				],
				'default'      => '30',
				'render_type'  => 'template',
				'prefix_class' => 'wd_scroll_smoothness_',
				'condition'    => [
					'element_parallax' => [ 'parallax-on-scroll' ],
				],
			]
		);

		$element->end_controls_section();
	}

	add_action( 'elementor/element/common/_section_style/after_section_end', 'woodmart_add_elements_custom_controls' );
}
