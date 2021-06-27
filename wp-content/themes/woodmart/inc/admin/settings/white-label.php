<?php
if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}
use XTS\Options;

/**
 * General
 */
Options::add_section(
	array(
		'id'       => 'white_label_section',
		'name'     => esc_html__( 'White label', 'woodmart' ),
		'priority' => 161,
		'icon'     => 'dashicons dashicons-tag',
	)
);

Options::add_field(
	array(
		'id'          => 'white_label',
		'name'        => esc_html__( 'White label', 'woodmart' ),
		'description' => esc_html__( 'Hide most of the "WoodMart" and "Xtemos" attributions.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'white_label_section',
		'default'     => false,
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'white_label_theme_license_tab',
		'name'        => esc_html__( 'Theme license tab', 'woodmart' ),
		'description' => esc_html__( 'You can hide this page from the dashboard.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'white_label_section',
		'default'     => '1',
		'priority'    => 30,
	)
);

Options::add_field(
	array(
		'id'          => 'white_label_theme_name',
		'name'        => esc_html__( 'Theme name', 'woodmart' ),
		'description' => esc_html__( 'Replaces all instances of "Woodmart"', 'woodmart' ),
		'type'        => 'text_input',
		'section'     => 'white_label_section',
		'default'     => '',
		'priority'    => 40,
	)
);

Options::add_field(
	array(
		'id'          => 'white_label_options_logo',
		'name'        => esc_html__( 'Options logo', 'woodmart' ),
		'description' => esc_html__( 'Recommended size: 50x50 (px)', 'woodmart' ),
		'type'        => 'upload',
		'section'     => 'white_label_section',
		'priority'    => 50,
	)
);

Options::add_field(
	array(
		'id'          => 'white_label_sidebar_icon_logo',
		'name'        => esc_html__( 'Sidebar icon logo', 'woodmart' ),
		'description' => esc_html__( 'Recommended size: 20x20 (px)', 'woodmart' ),
		'type'        => 'upload',
		'section'     => 'white_label_section',
		'priority'    => 60,
	)
);

Options::add_field(
	array(
		'id'          => 'dummy_import',
		'name'        => esc_html__( 'Dummy Content tab', 'woodmart' ),
		'group'       => esc_html__( 'Dashboard', 'woodmart' ),
		'description' => esc_html__( 'Disable the dummy content functionality completely.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'white_label_section',
		'default'     => '1',
		'priority'    => 61,
	)
);

Options::add_field(
	array(
		'id'          => 'white_label_dashboard_logo',
		'name'        => esc_html__( 'Logo', 'woodmart' ),
		'group'       => esc_html__( 'Dashboard', 'woodmart' ),
		'description' => esc_html__( 'Recommended size: 200x200 (px) ', 'woodmart' ),
		'type'        => 'upload',
		'section'     => 'white_label_section',
		'priority'    => 70,
	)
);

Options::add_field(
	array(
		'id'          => 'white_label_dashboard_title',
		'name'        => esc_html__( 'Dashboard title', 'woodmart' ),
		'description' => esc_html__( 'Heading displayed in Dashboard -> WoodMart.', 'woodmart' ),
		'group'       => esc_html__( 'Dashboard', 'woodmart' ),
		'type'        => 'text_input',
		'section'     => 'white_label_section',
		'default'     => '',
		'priority'    => 80,
	)
);

Options::add_field(
	array(
		'id'       => 'white_label_dashboard_text',
		'name'     => esc_html__( 'Intro text', 'woodmart' ),
		'group'    => esc_html__( 'Dashboard', 'woodmart' ),
		'type'     => 'textarea',
		'wysiwyg'  => true,
		'section'  => 'white_label_section',
		'default'  => '',
		'priority' => 90,
	)
);

Options::add_field(
	array(
		'id'          => 'white_label_appearance_screenshot',
		'name'        => esc_html__( 'Appearance screenshot', 'woodmart' ),
		'group'       => esc_html__( 'Appearance', 'woodmart' ),
		'description' => esc_html__( 'Recommended size: 1200×900 (px) Theme preview image that will be displayed in Dashboard -> Appearance -> Themes.', 'woodmart' ),
		'type'        => 'upload',
		'section'     => 'white_label_section',
		'priority'    => 100,
	)
);
