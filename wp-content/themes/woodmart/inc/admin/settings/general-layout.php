<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}
use XTS\Options;

/**
 * General
 */
Options::add_section(
	array(
		'id'       => 'general_layout_section',
		'name'     => esc_html__( 'General layout', 'woodmart' ),
		'priority' => 20,
		'icon'     => 'dashicons dashicons-layout',
	)
);

Options::add_field(
	array(
		'id'          => 'site_width',
		'name'        => esc_html__( 'Site width', 'woodmart' ),
		'description' => esc_html__( 'You can make your content wrapper boxed or full width', 'woodmart' ),
		'type'        => 'select',
		'section'     => 'general_layout_section',
		'options'     => array(
			'full-width'         => array(
				'name'  => esc_html__( 'Full width', 'woodmart' ),
				'value' => 'full-width',
			),
			'boxed'              => array(
				'name'  => esc_html__( 'Boxed (with hidden overflow)', 'woodmart' ),
				'value' => 'boxed',
			),
			'boxed-2'            => array(
				'name'  => esc_html__( 'Boxed', 'woodmart' ),
				'value' => 'boxed-2',
			),
			'full-width-content' => array(
				'name'  => esc_html__( 'Content full width', 'woodmart' ),
				'value' => 'full-width-content',
			),
			'wide'               => array(
				'name'  => esc_html__( 'Wide (1600 px)', 'woodmart' ),
				'value' => 'wide',
			),
			'custom'             => array(
				'name'  => esc_html__( 'Custom', 'woodmart' ),
				'value' => 'custom',
			),
		),
		'default'     => 'full-width',
		'tags'        => 'boxed full width wide',
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'site_custom_width',
		'name'        => esc_html__( 'Custom width in pixels', 'woodmart' ),
		'description' => esc_html__( 'Specify your custom website container width in pixels.', 'woodmart' ),
		'type'        => 'range',
		'section'     => 'general_layout_section',
		'default'     => 1222,
		'min'         => 1025,
		'max'         => 1920,
		'step'        => 1,
		'priority'    => 20,
		'requires'    => array(
			array(
				'key'     => 'site_width',
				'compare' => 'equals',
				'value'   => 'custom',
			),
		)
	)
);

Options::add_field(
	array(
		'id'          => 'main_layout',
		'name'        => esc_html__( 'Sidebar position', 'woodmart' ),
		'description' => esc_html__( 'Select main content and sidebar alignment.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'general_layout_section',
		'options'     => array(
			'full-width'    => array(
				'name'  => esc_html__( 'Without', 'woodmart' ),
				'value' => 'full-width',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/sidebar-layout/none.png',
			),
			'sidebar-left'  => array(
				'name'  => esc_html__( 'Left', 'woodmart' ),
				'value' => 'sidebar-left',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/sidebar-layout/left.png',
			),
			'sidebar-right' => array(
				'name'  => esc_html__( 'Right', 'woodmart' ),
				'value' => 'sidebar-right',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/sidebar-layout/right.png',
			),
		),
		'default'     => 'sidebar-right',
		'tags'        => 'sidebar left sidebar right',
		'priority'    => 30,
	)
);

Options::add_field(
	array(
		'id'          => 'hide_main_sidebar_mobile',
		'section'     => 'general_layout_section',
		'name'        => esc_html__( 'Off canvas sidebar for mobile', 'woodmart' ),
		'description' => esc_html__( 'You can can hide sidebar and show nicely on button click on the page.', 'woodmart' ),
		'type'        => 'switcher',
		'default'     => '1',
		'priority'    => 40,
	)
);

Options::add_field(
	array(
		'id'          => 'sidebar_width',
		'name'        => esc_html__( 'Sidebar size', 'woodmart' ),
		'description' => esc_html__( 'You can set different sizes for your pages sidebar', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'general_layout_section',
		'options'     => array(
			2  => array(
				'name'  => esc_html__( 'Small', 'woodmart' ),
				'value' => 2,
			),
			3  => array(
				'name'  => esc_html__( 'Medium', 'woodmart' ),
				'value' => 3,
			),
			4  => array(
				'name'  => esc_html__( 'Large', 'woodmart' ),
				'value' => 4,
			),
		),
		'default'     => 3,
		'tags'        => 'small sidebar large sidebar',
		'priority'    => 50,
	)
);
