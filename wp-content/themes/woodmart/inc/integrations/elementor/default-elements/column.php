<?php
/**
 * Elementor column custom controls
 *
 * @package xts
 */

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'woodmart_column_before_render' ) ) {
	/**
	 * Column before render.
	 *
	 * @since 1.0.0
	 *
	 * @param object $widget Element.
	 */
	function woodmart_column_before_render( $widget ) {
		$settings = $widget->get_settings_for_display();

		if ( isset( $settings['column_sticky'] ) && $settings['column_sticky'] ) {
			woodmart_enqueue_js_library( 'sticky-kit' );
			woodmart_enqueue_js_script( 'sticky-column' );
		}

		if ( isset( $settings['column_parallax'] ) && $settings['column_parallax'] ) {
			woodmart_enqueue_js_library( 'parallax-scroll-bundle' );
		}
	}

	add_action( 'elementor/frontend/column/before_render', 'woodmart_column_before_render', 10 );
}

if ( ! function_exists( 'woodmart_add_column_color_scheme_control' ) ) {
	/**
	 * Column custom controls
	 *
	 * @since 1.0.0
	 *
	 * @param object $element The control.
	 */
	function woodmart_add_column_color_scheme_control( $element ) {
		$element->start_controls_section(
			'wd_extra_style',
			[
				'label' => esc_html__( '[XTemos] Extra', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		/**
		 * Color scheme.
		 */
		$element->add_control(
			'wd_color_scheme',
			[
				'label'        => esc_html__( 'Color Scheme', 'woodmart' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					''      => esc_html__( 'Inherit', 'woodmart' ),
					'light' => esc_html__( 'Light', 'woodmart' ),
					'dark'  => esc_html__( 'Dark', 'woodmart' ),
				],
				'default'      => '',
				'render_type'  => 'template',
				'prefix_class' => 'color-scheme-',
			]
		);

		$element->end_controls_section();
	}

	add_action( 'elementor/element/column/section_style/after_section_end', 'woodmart_add_column_color_scheme_control' );
}

if ( ! function_exists( 'woodmart_add_column_custom_controls' ) ) {
	/**
	 * Column custom controls
	 *
	 * @since 1.0.0
	 *
	 * @param object $element The control.
	 */
	function woodmart_add_column_custom_controls( $element ) {
		$element->start_controls_section(
			'wd_extra',
			[
				'label' => esc_html__( '[XTemos] Extra', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		/**
		 * Sticky column
		 */
		$element->add_control(
			'column_sticky',
			[
				'label'        => esc_html__( 'Sticky column', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'sticky-column',
				'prefix_class' => 'wd-elementor-',
				'render_type'  => 'template',
			]
		);

		$element->add_control(
			'column_sticky_offset',
			[
				'label'        => esc_html__( 'Sticky column offset (px)', 'woodmart' ),
				'type'         => Controls_Manager::TEXT,
				'default'      => 50,
				'render_type'  => 'template',
				'prefix_class' => 'wd_sticky_offset_',
				'condition'    => [
					'column_sticky' => [ 'sticky-column' ],
				],
			]
		);

		$element->add_control(
			'column_sticky_hr',
			[
				'type'  => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		/**
		 * Column parallax on scroll
		 */
		$element->add_control(
			'column_parallax',
			[
				'label'        => esc_html__( 'Parallax on scroll', 'woodmart' ),
				'description'  => esc_html__( 'Smooth element movement when you scroll the page to create beautiful parallax effect.', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'parallax-on-scroll',
				'prefix_class' => 'wd-',
				'render_type'  => 'template',
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
					'column_parallax' => [ 'parallax-on-scroll' ],
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
					'column_parallax' => [ 'parallax-on-scroll' ],
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
					'column_parallax' => [ 'parallax-on-scroll' ],
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
					'column_parallax' => [ 'parallax-on-scroll' ],
				],
			]
		);

		$element->end_controls_section();
	}

	add_action( 'elementor/element/column/section_advanced/after_section_end', 'woodmart_add_column_custom_controls' );
}
