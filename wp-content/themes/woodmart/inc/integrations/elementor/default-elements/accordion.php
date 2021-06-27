<?php
/**
 * Elementor accordion custom controls
 *
 * @package xts
 */

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'woodmart_add_accordion_custom_controls' ) ) {
	/**
	 * Accordion custom controls
	 *
	 * @since 1.0.0
	 *
	 * @param object $element The control.
	 */
	function woodmart_add_accordion_custom_controls( $element ) {
		$element->add_control(
			'wd_theme_style',
			[
				'label'        => esc_html__( 'Theme style', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'theme-style',
				'prefix_class' => 'wd-accordion-',
				'render_type'  => 'template',
			]
		);
	}

	add_action( 'elementor/element/accordion/section_title_style/after_section_start', 'woodmart_add_accordion_custom_controls' );
}
