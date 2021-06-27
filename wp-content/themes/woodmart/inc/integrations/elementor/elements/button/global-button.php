<?php
/**
 * Banner global map file.
 *
 * @package xts
 */

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'woodmart_get_button_content_general_map' ) ) {
	/**
	 * Get button map.
	 *
	 * @param  object $element  Element object.
	 * @param  array  $custom_args  Custom args.
	 *
	 * @since 1.0.0
	 */
	function woodmart_get_button_content_general_map( $element, $custom_args = array() ) {
		$default_args = array(
			'link'          => true,
			'smooth_scroll' => true,
			'text'          => 'Read more',
		);

		$args = wp_parse_args( $custom_args, $default_args );

		$element->add_control(
			'text',
			[
				'label'   => esc_html__( 'Text', 'woodmart' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $args['text'],
			]
		);

		if ( $args['link'] ) {
			$element->add_control(
				'link',
				[
					'label'   => esc_html__( 'Link', 'woodmart' ),
					'type'    => Controls_Manager::URL,
					'default' => [
						'url'         => '#',
						'is_external' => false,
						'nofollow'    => false,
					],
				]
			);
		}

		if ( $args['smooth_scroll'] ) {
			$element->add_control(
				'button_smooth_scroll',
				[
					'label'        => esc_html__( 'Smooth scroll', 'woodmart' ),
					'description'  => esc_html__(
						'When you turn on this option you need to specify this button link with a hash symbol. For example #section-id
Then you need to have a section with an ID of "section-id" and this button click will smoothly scroll the page to that section.',
						'woodmart'
					),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'no',
					'label_on'     => esc_html__( 'Yes', 'woodmart' ),
					'label_off'    => esc_html__( 'No', 'woodmart' ),
					'return_value' => 'yes',
					'separator'    => 'before',
				]
			);

			$element->add_control(
				'button_smooth_scroll_time',
				[
					'label'     => esc_html__( 'Smooth scroll time (ms)', 'woodmart' ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => 100,
					'condition' => [
						'button_smooth_scroll' => [ 'yes' ],
					],
				]
			);

			$element->add_control(
				'button_smooth_scroll_offset',
				[
					'label'     => esc_html__( 'Smooth scroll offset (px)', 'woodmart' ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => 100,
					'condition' => [
						'button_smooth_scroll' => [ 'yes' ],
					],
				]
			);
		}
	}
}

if ( ! function_exists( 'woodmart_get_button_style_general_map' ) ) {
	/**
	 * Get button map.
	 *
	 * @param  object $element  Element object.
	 *
	 * @since 1.0.0
	 */
	function woodmart_get_button_style_general_map( $element ) {
		$element->add_control(
			'style',
			[
				'label'   => esc_html__( 'Style', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default'  => esc_html__( 'Default', 'woodmart' ),
					'bordered' => esc_html__( 'Bordered', 'woodmart' ),
					'link'     => esc_html__( 'Link button', 'woodmart' ),
					'3d'       => esc_html__( '3D', 'woodmart' ),
				],
				'default' => 'default',
			]
		);

		$element->add_control(
			'color',
			[
				'label'   => esc_html__( 'Predefined color', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'woodmart' ),
					'primary' => esc_html__( 'Primary', 'woodmart' ),
					'alt'     => esc_html__( 'Alternative', 'woodmart' ),
					'black'   => esc_html__( 'Black', 'woodmart' ),
					'white'   => esc_html__( 'White', 'woodmart' ),
					'custom'  => esc_html__( 'Custom', 'woodmart' ),
				],
				'default' => 'default',
			]
		);

		$element->start_controls_tabs(
			'button_tabs_style',
			[
				'condition' => [
					'color' => [ 'custom' ],
				],
			]
		);

		$element->start_controls_tab(
			'button_tab_normal',
			[
				'label' => esc_html__( 'Normal', 'woodmart' ),
			]
		);

		$element->add_control(
			'bg_color',
			[
				'label'     => esc_html__( 'Background color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wd-button-wrapper a' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				],
			]
		);

		$element->add_control(
			'color_scheme',
			[
				'label'   => esc_html__( 'Text color scheme', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'inherit' => esc_html__( 'Inherit', 'woodmart' ),
					'dark'    => esc_html__( 'Dark', 'woodmart' ),
					'light'   => esc_html__( 'Light', 'woodmart' ),
				],
				'default' => 'inherit',
			]
		);

		$element->end_controls_tab();

		$element->start_controls_tab(
			'button_tab_hover',
			[
				'label' => esc_html__( 'Hover', 'woodmart' ),
			]
		);

		$element->add_control(
			'bg_color_hover',
			[
				'label'     => esc_html__( 'Background color hover', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wd-button-wrapper:hover a' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				],
			]
		);

		$element->add_control(
			'color_scheme_hover',
			[
				'label'   => esc_html__( 'Text color scheme on hover', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'inherit' => esc_html__( 'Inherit', 'woodmart' ),
					'dark'    => esc_html__( 'Dark', 'woodmart' ),
					'light'   => esc_html__( 'Light', 'woodmart' ),
				],
				'default' => 'inherit',
			]
		);

		$element->end_controls_tab();

		$element->end_controls_tabs();

		$element->add_control(
			'size',
			[
				'label'   => esc_html__( 'Predefined size', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default'     => esc_html__( 'Default', 'woodmart' ),
					'extra-small' => esc_html__( 'Extra Small', 'woodmart' ),
					'small'       => esc_html__( 'Small', 'woodmart' ),
					'large'       => esc_html__( 'Large', 'woodmart' ),
					'extra-large' => esc_html__( 'Extra Large', 'woodmart' ),
				],
				'default' => 'default',
			]
		);

		$element->add_control(
			'shape',
			[
				'label'   => esc_html__( 'Shape', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'rectangle'  => esc_html__( 'Rectangle', 'woodmart' ),
					'round'      => esc_html__( 'Circle', 'woodmart' ),
					'semi-round' => esc_html__( 'Round', 'woodmart' ),
				],
				'condition' => [
					'style!' => [ 'link' ],
				],
				'default' => 'rectangle',
			]
		);
	}
}

if ( ! function_exists( 'woodmart_get_button_style_icon_map' ) ) {
	/**
	 * Get button map.
	 *
	 * @param  object $element  Element object.
	 *
	 * @since 1.0.0
	 */
	function woodmart_get_button_style_icon_map( $element ) {
		$element->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'woodmart' ),
				'type'  => Controls_Manager::ICONS,
			]
		);

		$element->add_control(
			'icon_position',
			[
				'label'   => esc_html__( 'Icon position', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'right' => esc_html__( 'Right', 'woodmart' ),
					'left'  => esc_html__( 'Left', 'woodmart' ),
				],
				'default' => 'right',
			]
		);
	}
}

if ( ! function_exists( 'woodmart_get_button_style_layout_map' ) ) {
	/**
	 * Get button map.
	 *
	 * @param  object $element  Element object.
	 *
	 * @since 1.0.0
	 */
	function woodmart_get_button_style_layout_map( $element ) {
		$element->add_control(
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

		$element->add_control(
			'full_width',
			[
				'label'        => esc_html__( 'Full width', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'yes',
			]
		);
	}
}
