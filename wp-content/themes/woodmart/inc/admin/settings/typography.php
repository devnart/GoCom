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
		'id'       => 'typography_section',
		'name'     => esc_html__( 'Typography', 'woodmart' ),
		'priority' => 50,
		'icon'     => 'dashicons dashicons-editor-textcolor',
	)
);

Options::add_field(
	array(
		'id'           => 'text-font',
		'type'         => 'typography',
		'section'      => 'typography_section',
		'name'         => esc_html__( 'Text font', 'woodmart' ),
		'description'  => esc_html__( 'Set you typography options for body, paragraphs.', 'woodmart' ),
		'selector_var' => array(
			'font-family' => '--wd-text-font',
			'font-size'   => '--wd-text-font-size',
			'font-weight' => '--wd-text-font-weight',
			'color'       => '--wd-text-color',
		),
		'default'      => array(
			array(
				'font-family' => 'Lato',
				'color'       => '#777777',
				'font-size'   => '14',
				'font-weight' => '400',
			),
		),
		'line-height'  => false,
		'tags'         => 'typography',
		'priority'     => 10,
	)
);

Options::add_field(
	array(
		'id'             => 'primary-font',
		'type'           => 'typography',
		'section'        => 'typography_section',
		'name'           => esc_html__( 'Title font', 'woodmart' ),
		'description'    => esc_html__( 'Set you typography options for titles, post names.', 'woodmart' ),
		'selector_var'   => array(
			'font-family'    => '--wd-title-font',
			'font-weight'    => '--wd-title-font-weight',
			'text-transform' => '--wd-title-transform',
			'color'          => '--wd-title-color',
		),
		'default'        => array(
			array(
				'font-family' => 'Poppins',
				'font-weight' => '600',
				'color'       => '#242424',
			),
		),
		'line-height'    => false,
		'text-transform' => true,
		'font-size'      => false,
		'tags'           => 'typography',
		'priority'       => 10,
	)
);

Options::add_field(
	array(
		'id'             => 'post-titles-font',
		'type'           => 'typography',
		'section'        => 'typography_section',
		'name'           => esc_html__( 'Entities names font', 'woodmart' ),
		'description'    => esc_html__( 'Titles for posts, products, categories and pages', 'woodmart' ),
		'selector_var'   => array(
			'font-family'    => '--wd-entities-title-font',
			'font-weight'    => '--wd-entities-title-font-weight',
			'color'          => '--wd-entities-title-color',
			'color-hover'    => '--wd-entities-title-color-hover',
			'text-transform' => '--wd-entities-title-transform',
		),
		'default'        => array(
			array(
				'font-family' => 'Poppins',
				'font-weight' => '500',
				'color'       => '#333333',
				'hover'       => array(
					'color' => 'rgb(51 51 51 / 65%)',
				),
			),
		),
		'color-hover'    => true,
		'line-height'    => false,
		'text-transform' => true,
		'font-size'      => false,
		'tags'           => 'typography',
		'priority'       => 20,
	)
);

Options::add_field(
	array(
		'id'           => 'secondary-font',
		'type'         => 'typography',
		'section'      => 'typography_section',
		'name'         => esc_html__( 'Secondary font', 'woodmart' ),
		'description'  => esc_html__( 'Use for secondary titles (use CSS class "font-alt" or "title-alt")', 'woodmart' ),
		'selector_var' => array(
			'font-family' => '--wd-alternative-font',
		),
		'default'      => array(
			array(
				'font-family' => 'Lato',
				'font-weight' => '400',
			),
		),
		'line-height'  => false,
		'font-size'    => false,
		'color'        => false,
		'tags'         => 'typography',
		'priority'     => 30,
	)
);

Options::add_field(
	array(
		'id'             => 'widget-titles-font',
		'type'           => 'typography',
		'section'        => 'typography_section',
		'name'           => esc_html__( 'Widget titles font', 'woodmart' ),
		'description'    => esc_html__( 'Typography options for titles for widgets in your sidebars.', 'woodmart' ),
		'selector_var'   => array(
			'font-family'    => '--wd-widget-title-font',
			'font-weight'    => '--wd-widget-title-font-weight',
			'font-size'      => '--wd-widget-title-font-size',
			'text-transform' => '--wd-widget-title-transform',
			'color'          => '--wd-widget-title-color',
		),
		'default'        => array(
			array(
				'font-family'    => 'Poppins',
				'font-weight'    => '600',
				'font-size'      => '16',
				'color'          => '#333',
				'text-transform' => 'uppercase',
			),
		),
		'text-transform' => true,
		'line-height'    => false,
		'tags'           => 'typography',
		'priority'       => 40,
	)
);

Options::add_field(
	array(
		'id'             => 'navigation-font',
		'type'           => 'typography',
		'section'        => 'typography_section',
		'name'           => esc_html__( 'Header font', 'woodmart' ),
		'description'    => esc_html__( 'This option will change typography for your header.', 'woodmart' ),
		'selector_var'   => array(
			'font-family'    => '--wd-header-el-font',
			'font-weight'    => '--wd-header-el-font-weight',
			'font-size'      => '--wd-header-el-font-size',
			'text-transform' => '--wd-header-el-transform',
		),
		'default'        => array(
			array(
				'font-family'    => 'Lato',
				'font-weight'    => '700',
				'font-size'      => '13',
				'text-transform' => 'uppercase',
			),
		),
		'text-transform' => true,
		'line-height'    => false,
		'color'          => false,
		'tags'           => 'typography',
		'priority'       => 50,
	)
);

/**
 * Typekit fonts.
 */
Options::add_section(
	array(
		'id'       => 'typekit_section',
		'parent'   => 'typography_section',
		'name'     => esc_html__( 'Typekit Fonts', 'woodmart' ),
		'priority' => 10,
	)
);

Options::add_field(
	array(
		'id'       => 'typekit_notice',
		'type'     => 'notice',
		'style'    => 'info',
		'name'     => '',
		'content'  => wp_kses(
			__( 'To use your Typekit font, you need to create an account on the <a href="https://typekit.com/" target="_blank"><u>service</u></a> and obtain your key ID here. Then, you need to enter all custom fonts you will use separated with coma. After this, save Theme Settings and reload this page to be able to select your fonts in the list under the Theme Settings -> Typography section.', 'woodmart' ),
			array(
				'a'      => array(
					'href'   => true,
					'target' => true,
				),
				'br'     => array(),
				'strong' => array(),
				'u'      => array(),
			)
		),
		'section'  => 'typekit_section',
		'priority' => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'typekit_id',
		'name'        => esc_html__( 'Typekit Kit ID', 'woodmart' ),
		'description' => esc_html__( 'Enter your ', 'woodmart' ) . '<a target="_blank" href="https://typekit.com/account/kits">Typekit Kit ID</a>.',
		'type'        => 'text_input',
		'section'     => 'typekit_section',
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'typekit_fonts',
		'name'        => esc_html__( 'Typekit Typekit Font Face', 'woodmart' ),
		'description' => esc_html__( 'Example: futura-pt, lato', 'woodmart' ),
		'type'        => 'text_input',
		'section'     => 'typekit_section',
		'priority'    => 30,
	)
);


/**
 * Custom Fonts.
 */
Options::add_section(
	array(
		'id'       => 'custom_fonts_section',
		'parent'   => 'typography_section',
		'name'     => esc_html__( 'Custom Fonts', 'woodmart' ),
		'priority' => 20,
	)
);

Options::add_field(
	array(
		'id'       => 'multi_custom_fonts_notice',
		'type'     => 'notice',
		'style'    => 'info',
		'name'     => '',
		'content'  => wp_kses(
			__(
				'In this section you can upload your custom fonts files. To ensure the best compatibility in all browsers you would better upload your fonts in all available formats. 
<br><strong>IMPORTANT NOTE</strong>: After uploading all files and entering the font name, you will have to save Theme Settings and <strong>RELOAD</strong> this page. Then, you will be able to go to Theme Settings -> Typography and select the custom font from the list. Find more information in our documentation <a href="https://xtemos.com/docs/woodmart/faq-guides/upload-custom-fonts/" target="_blank">here</a>.',
				'woodmart'
			),
			array(
				'a'      => array(
					'href'   => true,
					'target' => true,
				),
				'br'     => array(),
				'strong' => array(),
			)
		),
		'section'  => 'custom_fonts_section',
		'priority' => 10,
	)
);

Options::add_field(
	array(
		'id'       => 'multi_custom_fonts',
		'type'     => 'custom_fonts',
		'section'  => 'custom_fonts_section',
		'name'     => esc_html__( 'Advanced typography', 'woodmart' ),
		'fonts'    => apply_filters( 'woodmart_custom_fonts_type', array( 'woff', 'woff2' ) ),
		'priority' => 20,
	)
);

/**
 * Advanced typography.
 */
Options::add_section(
	array(
		'id'       => 'advanced_typography_section',
		'parent'   => 'typography_section',
		'name'     => esc_html__( 'Advanced typography', 'woodmart' ),
		'priority' => 30,
	)
);

$selectors = woodmart_get_config( 'typography-selectors' );

Options::add_field(
	array(
		'id'             => 'advanced_typography',
		'type'           => 'typography',
		'section'        => 'advanced_typography_section',
		'name'           => esc_html__( 'Advanced typography', 'woodmart' ),
		'selectors'      => $selectors,
		'color-hover'    => true,
		'text-transform' => true,
		'priority'       => 10,
	)
);
