<?php
/**
 * Elementor text editor custom controls
 */

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'woodmart_add_color_scheme_to_text_element' ) ) {
	/**
	 * Add color scheme option to text element
	 *
	 * @since 1.0.0
	 *
	 * @param object $element The control.
	 */
	function woodmart_add_color_scheme_to_text_element( $element ) {
		$element->add_control(
			'wd_color_scheme',
			[
				'label'        => esc_html__( 'Color scheme', 'woodmart' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					'inherit' => esc_html__( 'Inherit', 'woodmart' ),
					'dark'    => esc_html__( 'Dark', 'woodmart' ),
					'light'   => esc_html__( 'Light', 'woodmart' ),
				],
				'default'      => 'inherit',
				'prefix_class' => 'color-scheme-',
				'render_type'  => 'template',
			]
		);
	}

	add_filter( 'elementor/element/text-editor/section_style/after_section_start', 'woodmart_add_color_scheme_to_text_element', 10, 2 );
}

if ( ! function_exists( 'woodmart_add_content_align_to_text_element' ) ) {
	/**
	 * Add content align option to text element
	 *
	 * @since 1.0.0
	 *
	 * @param object $element The control.
	 */
	function woodmart_add_content_align_to_text_element( $element ) {
		$element->add_control(
			'wd_content_align',
			[
				'label'        => esc_html__( 'Content align', 'woodmart' ),
				'type'         => 'wd_buttons',
				'options'      => [
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
				'default'      => 'left',
				'prefix_class' => 'text-',
				'render_type'  => 'template',
			]
		);
	}

	add_filter( 'elementor/element/text-editor/section_style/before_section_end', 'woodmart_add_content_align_to_text_element', 10, 2 );
}

if ( ! function_exists( 'woodmart_add_content_width_to_text_element' ) ) {
	/**
	 * Add content width option to text element
	 *
	 * @since 1.0.0
	 *
	 * @param object $element The control.
	 */
	function woodmart_add_content_width_to_text_element( $element ) {
		$element->add_responsive_control(
			'wd_content_width',
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
					'{{WRAPPER}} .elementor-text-editor' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	add_filter( 'elementor/element/text-editor/section_style/before_section_end', 'woodmart_add_content_width_to_text_element', 20, 2 );
}
