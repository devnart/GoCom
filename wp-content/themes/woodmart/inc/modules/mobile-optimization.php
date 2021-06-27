<?php

use XTS\Metaboxes;

if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

if ( ! function_exists( 'woodmart_wp_is_mobile' ) ) {
	/**
	 * Filter page content.
	 *
	 * @param boolean $is_mobile Is mobile.
	 *
	 * @return string|void
	 */
	function woodmart_wp_is_mobile( $is_mobile ) {
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && strpos( $_SERVER['HTTP_USER_AGENT'], 'iPad' ) ) { // phpcs:ignore
			$is_mobile = false;
		}

		return $is_mobile;
	}

	add_filter( 'wp_is_mobile', 'woodmart_wp_is_mobile' );
}

if ( ! function_exists( 'woodmart_mobile_optimization_page_metaboxes' ) ) {
	/**
	 * Register page metaboxes.
	 *
	 * @since 1.0.0
	 */
	function woodmart_mobile_optimization_page_metaboxes() {
		global $woodmart_prefix;

		$woodmart_prefix = '_woodmart_';

		$page_mobile_metabox = Metaboxes::add_metabox(
			array(
				'id'         => 'xts_page_mobile_metaboxes',
				'title'      => esc_html__( 'Page mobile optimizations', 'woodmart' ),
				'post_types' => array( 'page' ),
			)
		);

		$page_mobile_metabox->add_section(
			array(
				'id'       => 'general',
				'name'     => esc_html__( 'General', 'woodmart' ),
				'priority' => 10,
				'icon'     => WOODMART_ASSETS . '/assets/images/dashboard-icons/settings.svg',
			)
		);

		$page_mobile_metabox->add_field(
			array(
				'id'           => $woodmart_prefix . 'mobile_content',
				'name'         => esc_html__( 'Mobile version HTML block (experimental)', 'woodmart' ),
				'description'  => esc_html__( 'You can create a separate content that will be displayed on mobile devices to optimize the performance.', 'woodmart' ),
				'group'        => esc_html__( 'Mobile optimizations', 'woodmart' ),
				'type'         => 'select',
				'section'      => 'general',
				'empty_option' => true,
				'options'      => woodmart_get_static_blocks_array( true ),
				'priority'     => 10,
			)
		);
	}

	add_action( 'init', 'woodmart_mobile_optimization_page_metaboxes', 90 );
}
