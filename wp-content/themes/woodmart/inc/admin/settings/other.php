<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

use XTS\Options;

/**
 * Performance.
 */
Options::add_section(
	array(
		'id'       => 'other',
		'name'     => esc_html__( 'Other', 'woodmart' ),
		'priority' => 160,
		'icon'     => 'dashicons dashicons-admin-settings',
	)
);

Options::add_field(
	array(
		'id'          => 'woodmart_slider',
		'name'        => esc_html__( 'Enable custom slider', 'woodmart' ),
		'description' => esc_html__( 'If you enable this option, a new post type for sliders will be added to your Dashboard menu. You will be able to create sliders with WPBakery Page Builder and place them on any page on your website.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'other',
		'default'     => '1',
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'allow_upload_svg',
		'name'        => esc_html__( 'Allow SVG upload', 'woodmart' ),
		'description' => wp_kses(
			__( 'Allow SVG uploads as well as SVG format for custom fonts. We suggest you to use <a href="https://ru.wordpress.org/plugins/safe-svg/">this plugin</a> to be sure that all uploaded content is safe. If you will install this plugin, you can disable this option.', 'woodmart' ),
			array(
				'a'      => array(
					'href'   => true,
					'target' => true,
				),
				'br'     => array(),
				'strong' => array(),
			)
		),
		'type'        => 'switcher',
		'section'     => 'other',
		'default'     => '1',
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'rev_slider_inherit_theme_font',
		'name'        => esc_html__( 'Slider Revolution inherit theme font', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'other',
		'default'     => '0',
		'priority'    => 50,
	)
);