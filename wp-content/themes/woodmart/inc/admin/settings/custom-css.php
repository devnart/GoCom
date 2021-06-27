<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}
use XTS\Options;

/**
 * Custom CSS section.
 */
Options::add_section(
	array(
		'id'       => 'custom_css',
		'name'     => esc_html__( 'Custom CSS', 'woodmart' ),
		'priority' => 120,
		'icon'     => 'dashicons dashicons-buddicons-topics',
	)
);


Options::add_field(
	array(
		'id'       => 'custom_css',
		'name'     => esc_html__('Global Custom CSS', 'woodmart'),
		'type'     => 'editor',
		'language' => 'css',
		'section'  => 'custom_css',
		'priority' => 10,
	)
);

Options::add_field(
	array(
		'id'       => 'css_desktop',
		'name'     =>  esc_html__('Custom CSS for desktop', 'woodmart'),
		'type'     => 'editor',
		'language' => 'css',
		'section'  => 'custom_css',
		'priority' => 20,

	)
);

Options::add_field(
	array(
		'id'       => 'css_tablet',
		'name'     => esc_html__('Custom CSS for tablet', 'woodmart'),
		'type'     => 'editor',
		'language' => 'css',
		'section'  => 'custom_css',
		'priority' => 30,

	)
);

Options::add_field(
	array(
		'id'       => 'css_wide_mobile',
		'name'     => esc_html__('Custom CSS for mobile landscape', 'woodmart'),
		'type'     => 'editor',
		'language' => 'css',
		'section'  => 'custom_css',
		'priority' => 40,

	)
);

Options::add_field(
	array(
		'id'       => 'css_mobile',
		'name'     => esc_html__('Custom CSS for mobile', 'woodmart'),
		'type'     => 'editor',
		'language' => 'css',
		'section'  => 'custom_css',
		'priority' => 50,

	)
);
