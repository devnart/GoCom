<?php

if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}
use XTS\Options;

/**
 * Styles and colors.
 */
Options::add_section(
	array(
		'id'       => 'colors_section',
		'name'     => esc_html__( 'Styles and colors', 'woodmart' ),
		'priority' => 60,
		'icon'     => 'dashicons dashicons-admin-customizer',
	)
);

Options::add_field(
	array(
		'id'           => 'primary-color',
		'name'         => esc_html__( 'Primary color', 'woodmart' ),
		'description'  => esc_html__( 'Pick a background color for the theme buttons and other colored elements.', 'woodmart' ),
		'type'         => 'color',
		'section'      => 'colors_section',
		'selector_var' => '--wd-primary-color',
		'default'      => array( 'idle' => '#83b735' ),
		'priority'     => 10,
	)
);

Options::add_field(
	array(
		'id'           => 'secondary-color',
		'name'         => esc_html__( 'Secondary color', 'woodmart' ),
		'description'  => esc_html__( 'Color for secondary elements on the website.', 'woodmart' ),
		'type'         => 'color',
		'section'      => 'colors_section',
		'selector_var' => '--wd-alternative-color',
		'default'      => array( 'idle' => '#fbbc34' ),
		'priority'     => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'android_browser_bar_color',
		'name'        => esc_html__( 'Android browser bar color', 'woodmart' ),
		'description' => wp_kses( __( 'Define color for the browser top bar on Android devices. <a href="https://developers.google.com/web/fundamentals/design-and-ux/browser-customization/#color_browser_elements">[Read more]</a>', 'woodmart' ), 'default' ),
		'type'        => 'color',
		'section'     => 'colors_section',
		'default'     => array(),
		'priority'    => 30,
	)
);

Options::add_field(
	array(
		'id'                 => 'link-color',
		'name'               => esc_html__( 'Links color', 'woodmart' ),
		'description'        => esc_html__( 'Set the color for links on your pages, posts and products content.', 'woodmart' ),
		'group'              => esc_html__( 'Website links color', 'woodmart' ),
		'type'               => 'color',
		'section'            => 'colors_section',
		'selector_var'       => '--wd-link-color',
		'selector_hover_var' => '--wd-link-color-hover',
		'default'            => array(
			'idle'  => '#333333',
			'hover' => '#242424',
		),
		'priority'           => 50,
	)
);

Options::add_field(
	array(
		'id'          => 'dark_version',
		'name'        => esc_html__( 'Dark theme', 'woodmart' ),
		'description' => esc_html__( 'Turn your website color to dark scheme', 'woodmart' ),
		'group'       => esc_html__( 'Website dark theme', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'colors_section',
		'default'     => false,
		'priority'    => 60,
	)
);

Options::add_section(
	array(
		'id'       => 'pages_bg_section',
		'parent'   => 'colors_section',
		'name'     => esc_html__( 'Pages background', 'woodmart' ),
		'priority' => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'body-background',
		'name'        => esc_html__( 'Site background', 'woodmart' ),
		'description' => esc_html__( 'Set background image or color for body. Only for BOXED layout.', 'woodmart' ),
		'type'        => 'background',
		'default'     => array(),
		'section'     => 'pages_bg_section',
		'selector'    => 'body',
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'       => 'pages-background',
		'name'     => esc_html__( 'Wrapper background for ALL pages', 'woodmart' ),
		'type'     => 'background',
		'default'  => array(),
		'section'  => 'pages_bg_section',
		'selector' => '.page .main-page-wrapper',
		'priority' => 20,
	)
);

Options::add_field(
	array(
		'id'       => 'shop-background',
		'name'     => esc_html__( 'Background for SHOP pages', 'woodmart' ),
		'type'     => 'background',
		'default'  => array(),
		'section'  => 'pages_bg_section',
		'selector' => '.woodmart-archive-shop .main-page-wrapper',
		'priority' => 30,
	)
);

Options::add_field(
	array(
		'id'          => 'product-background',
		'name'        => esc_html__( 'Single product background', 'woodmart' ),
		'description' => esc_html__( 'Set background for your products page. You can also specify different background for particular products while editing it.', 'woodmart' ),
		'type'        => 'background',
		'default'     => array(),
		'section'     => 'pages_bg_section',
		'selector'    => '.single-product .main-page-wrapper',
		'priority'    => 40,
	)
);

Options::add_field(
	array(
		'id'       => 'blog-background',
		'name'     => esc_html__( 'Background for BLOG', 'woodmart' ),
		'type'     => 'background',
		'default'  => array(),
		'section'  => 'pages_bg_section',
		'selector' => '.woodmart-archive-blog .main-page-wrapper',
		'priority' => 50,
	)
);

Options::add_field(
	array(
		'id'       => 'blog-post-background',
		'name'     => esc_html__( 'Background for BLOG single post', 'woodmart' ),
		'type'     => 'background',
		'default'  => array(),
		'section'  => 'pages_bg_section',
		'selector' => '.single-post .main-page-wrapper',
		'priority' => 60,
	)
);

Options::add_field(
	array(
		'id'       => 'portfolio-background',
		'name'     => esc_html__( 'Background for PORTFOLIO', 'woodmart' ),
		'type'     => 'background',
		'default'  => array(),
		'section'  => 'pages_bg_section',
		'selector' => '.woodmart-archive-portfolio .main-page-wrapper',
		'priority' => 70,
	)
);

Options::add_field(
	array(
		'id'       => 'portfolio-project-background',
		'name'     => esc_html__( 'Background for PORTFOLIO project', 'woodmart' ),
		'type'     => 'background',
		'default'  => array(),
		'section'  => 'pages_bg_section',
		'selector' => '.single-portfolio .main-page-wrapper',
		'priority' => 80,
	)
);

Options::add_section(
	array(
		'id'       => 'buttons_section',
		'parent'   => 'colors_section',
		'name'     => esc_html__( 'Buttons', 'woodmart' ),
		'priority' => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'btns_default_style',
		'name'        => esc_html__( 'Default buttons styles', 'woodmart' ),
		'description' => esc_html__( 'Almost all standard buttons through the site', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'buttons_section',
		'options'     => array(
			'flat'         => array(
				'name'  => esc_html__( 'Flat', 'woodmart' ),
				'value' => 'flat',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/buttons/flat.jpg',
			),
			'3d'           => array(
				'name'  => esc_html__( '3D', 'woodmart' ),
				'value' => '3d',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/buttons/3d.jpg',
			),
			'rounded'      => array(
				'name'  => esc_html__( 'Circle', 'woodmart' ),
				'value' => 'rounded',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/buttons/circle.jpg',
			),
			'semi-rounded' => array(
				'name'  => esc_html__( 'Rounded', 'woodmart' ),
				'value' => 'semi-rounded',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/buttons/semi-rounded.jpg',
			),
		),
		'default'     => 'flat',
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'btns_shop_style',
		'name'        => esc_html__( 'WooCommerce buttons styles', 'woodmart' ),
		'description' => esc_html__( 'Shopping buttons like "Add to cart", "Checkout", "Login", "Register" etc.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'buttons_section',
		'options'     => array(
			'flat'         => array(
				'name'  => esc_html__( 'Flat', 'woodmart' ),
				'value' => 'flat',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/buttons/flat.jpg',
			),
			'3d'           => array(
				'name'  => esc_html__( '3D', 'woodmart' ),
				'value' => '3d',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/buttons/3d.jpg',
			),
			'rounded'      => array(
				'name'  => esc_html__( 'Circle', 'woodmart' ),
				'value' => 'rounded',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/buttons/circle.jpg',
			),
			'semi-rounded' => array(
				'name'  => esc_html__( 'Rounded', 'woodmart' ),
				'value' => 'semi-rounded',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/buttons/semi-rounded.jpg',
			),
		),
		'default'     => '3d',
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'btns_accent_style',
		'name'        => esc_html__( 'Accent buttons styles', 'woodmart' ),
		'description' => esc_html__( '"Call to action" buttons', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'buttons_section',
		'options'     => array(
			'flat'         => array(
				'name'  => esc_html__( 'Flat', 'woodmart' ),
				'value' => 'flat',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/buttons/flat.jpg',
			),
			'3d'           => array(
				'name'  => esc_html__( '3D', 'woodmart' ),
				'value' => '3d',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/buttons/3d.jpg',
			),
			'rounded'      => array(
				'name'  => esc_html__( 'Circle', 'woodmart' ),
				'value' => 'rounded',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/buttons/circle.jpg',
			),
			'semi-rounded' => array(
				'name'  => esc_html__( 'Rounded', 'woodmart' ),
				'value' => 'semi-rounded',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/buttons/semi-rounded.jpg',
			),
		),
		'default'     => 'flat',
		'priority'    => 30,
	)
);

Options::add_field(
	array(
		'id'           => 'btns_default_bg',
		'name'         => esc_html__( '[Default] Background for buttons', 'woodmart' ),
		'group'        => esc_html__( 'Default buttons color', 'woodmart' ),
		'type'         => 'color',
		'section'      => 'buttons_section',
		'selector_var' => '--btn-default-bgcolor',
		'default'      => array(
			'idle' => '#f7f7f7',
		),
		'priority'     => 40,
	)
);

Options::add_field(
	array(
		'id'       => 'btns_default_color_scheme',
		'name'     => esc_html__( '[Default] Text color scheme', 'woodmart' ),
		'group'    => esc_html__( 'Default buttons color', 'woodmart' ),
		'type'     => 'buttons',
		'section'  => 'buttons_section',
		'options'  => array(
			'dark'  => array(
				'name'  => esc_html__( 'Dark', 'woodmart' ),
				'value' => 'dark',
			),
			'light' => array(
				'name'  => esc_html__( 'Light', 'woodmart' ),
				'value' => 'light',
			),
		),
		'default'  => 'dark',
		'priority' => 50,
	)
);

Options::add_field(
	array(
		'id'           => 'btns_default_bg_hover',
		'name'         => esc_html__( '[Default hover] Background on hover', 'woodmart' ),
		'group'        => esc_html__( 'Default buttons color', 'woodmart' ),
		'type'         => 'color',
		'section'      => 'buttons_section',
		'selector_var' => '--btn-default-bgcolor-hover',
		'default'      => array(
			'idle' => '#efefef',
		),
		'tags'         => 'buttons background button color buttons color',
		'priority'     => 60,
	)
);

Options::add_field(
	array(
		'id'       => 'btns_default_color_scheme_hover',
		'name'     => esc_html__( '[Default hover] Text color scheme on hover', 'woodmart' ),
		'group'    => esc_html__( 'Default buttons color', 'woodmart' ),
		'type'     => 'buttons',
		'section'  => 'buttons_section',
		'options'  => array(
			'dark'  => array(
				'name'  => esc_html__( 'Dark', 'woodmart' ),
				'value' => 'dark',
			),
			'light' => array(
				'name'  => esc_html__( 'Light', 'woodmart' ),
				'value' => 'light',
			),
		),
		'default'  => 'dark',
		'priority' => 70,
	)
);

Options::add_field(
	array(
		'id'           => 'btns_shop_bg',
		'name'         => esc_html__( '[Shop] Background for buttons', 'woodmart' ),
		'group'        => esc_html__( 'Shop buttons color', 'woodmart' ),
		'type'         => 'color',
		'section'      => 'buttons_section',
		'selector_var' => '--btn-shop-bgcolor',
		'default'      => array(
			'idle' => '#83b735',
		),
		'priority'     => 80,
	)
);

Options::add_field(
	array(
		'id'       => 'btns_shop_color_scheme',
		'name'     => esc_html__( '[Shop] Text color scheme', 'woodmart' ),
		'group'    => esc_html__( 'Shop buttons color', 'woodmart' ),
		'type'     => 'buttons',
		'section'  => 'buttons_section',
		'options'  => array(
			'dark'  => array(
				'name'  => esc_html__( 'Dark', 'woodmart' ),
				'value' => 'dark',
			),
			'light' => array(
				'name'  => esc_html__( 'Light', 'woodmart' ),
				'value' => 'light',
			),
		),
		'default'  => 'light',
		'priority' => 90,
	)
);

Options::add_field(
	array(
		'id'           => 'btns_shop_bg_hover',
		'name'         => esc_html__( '[Shop hover] Background on hover', 'woodmart' ),
		'group'        => esc_html__( 'Shop buttons color', 'woodmart' ),
		'type'         => 'color',
		'section'      => 'buttons_section',
		'selector_var' => '--btn-shop-bgcolor-hover',
		'default'      => array(
			'idle' => '#74a32f',
		),
		'priority'     => 100,
	)
);

Options::add_field(
	array(
		'id'       => 'btns_shop_color_scheme_hover',
		'name'     => esc_html__( '[Shop hover] Text color scheme on hover', 'woodmart' ),
		'group'    => esc_html__( 'Shop buttons color', 'woodmart' ),
		'type'     => 'buttons',
		'section'  => 'buttons_section',
		'options'  => array(
			'dark'  => array(
				'name'  => esc_html__( 'Dark', 'woodmart' ),
				'value' => 'dark',
			),
			'light' => array(
				'name'  => esc_html__( 'Light', 'woodmart' ),
				'value' => 'light',
			),
		),
		'default'  => 'light',
		'priority' => 110,
	)
);


Options::add_field(
	array(
		'id'           => 'btns_accent_bg',
		'name'         => esc_html__( '[Accent] Background for buttons', 'woodmart' ),
		'group'        => esc_html__( 'Accent buttons color', 'woodmart' ),
		'type'         => 'color',
		'section'      => 'buttons_section',
		'selector_var' => '--btn-accent-bgcolor',
		'default'      => array(
			'idle' => '#83b735',
		),
		'priority'     => 120,
	)
);

Options::add_field(
	array(
		'id'       => 'btns_accent_color_scheme',
		'name'     => esc_html__( '[Accent] Text color scheme', 'woodmart' ),
		'group'    => esc_html__( 'Accent buttons color', 'woodmart' ),
		'type'     => 'buttons',
		'section'  => 'buttons_section',
		'options'  => array(
			'dark'  => array(
				'name'  => esc_html__( 'Dark', 'woodmart' ),
				'value' => 'dark',
			),
			'light' => array(
				'name'  => esc_html__( 'Light', 'woodmart' ),
				'value' => 'light',
			),
		),
		'default'  => 'light',
		'priority' => 130,
	)
);

Options::add_field(
	array(
		'id'           => 'btns_accent_bg_hover',
		'name'         => esc_html__( '[Accent hover] Background on hover', 'woodmart' ),
		'group'        => esc_html__( 'Accent buttons color', 'woodmart' ),
		'type'         => 'color',
		'section'      => 'buttons_section',
		'selector_var' => '--btn-accent-bgcolor-hover',
		'default'      => array(
			'idle' => '#74a32f',
		),
		'priority'     => 140,
	)
);

Options::add_field(
	array(
		'id'       => 'btns_accent_color_scheme_hover',
		'name'     => esc_html__( '[Accent hover] Text color scheme on hover', 'woodmart' ),
		'group'    => esc_html__( 'Accent buttons color', 'woodmart' ),
		'type'     => 'buttons',
		'section'  => 'buttons_section',
		'options'  => array(
			'dark'  => array(
				'name'  => esc_html__( 'Dark', 'woodmart' ),
				'value' => 'dark',
			),
			'light' => array(
				'name'  => esc_html__( 'Light', 'woodmart' ),
				'value' => 'light',
			),
		),
		'default'  => 'light',
		'priority' => 150,
	)
);

Options::add_section(
	array(
		'id'       => 'forms_section',
		'parent'   => 'colors_section',
		'name'     => esc_html__( 'Forms style', 'woodmart' ),
		'priority' => 30,
	)
);

Options::add_field(
	array(
		'id'          => 'form_fields_style',
		'name'        => esc_html__( 'Form fields style', 'woodmart' ),
		'description' => esc_html__( 'Choose your form style', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'forms_section',
		'options'     => array(
			'rounded'      => array(
				'name'  => esc_html__( 'Circle', 'woodmart' ),
				'value' => 'rounded',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/form-style/circle.jpg',
			),
			'semi-rounded' => array(
				'name'  => esc_html__( 'Round', 'woodmart' ),
				'value' => 'semi-rounded',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/form-style/semi-rounded.jpg',
			),
			'square'       => array(
				'name'  => esc_html__( 'Square', 'woodmart' ),
				'value' => 'square',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/form-style/square.jpg',
			),
			'underlined'   => array(
				'name'  => esc_html__( 'Underlined', 'woodmart' ),
				'value' => 'underlined',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/form-style/underlined.jpg',
			),
		),
		'default'     => 'square',
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'form_border_width',
		'name'        => esc_html__( 'Form border width', 'woodmart' ),
		'description' => esc_html__( 'Choose your form border width', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'forms_section',
		'options'     => array(
			1 => array(
				'name'  => 1,
				'value' => 1,
			),
			2 => array(
				'name'  => 2,
				'value' => 2,
			),
		),
		'default'     => 2,
		'priority'    => 20,
	)
);

Options::add_section(
	array(
		'id'       => 'notices_section',
		'parent'   => 'colors_section',
		'name'     => esc_html__( 'Notices', 'woodmart' ),
		'priority' => 40,
	)
);

Options::add_field(
	array(
		'id'           => 'success_notice_bg_color',
		'name'         => esc_html__( 'Success notice background color', 'woodmart' ),
		'group'        => esc_html__( 'Success', 'woodmart' ),
		'type'         => 'color',
		'section'      => 'notices_section',
		'selector_var' => '--notices-success-bg',
		'default'      => array( 'idle' => '#459647' ),
		'priority'     => 10,
	)
);

Options::add_field(
	array(
		'id'           => 'success_notice_test_color',
		'name'         => esc_html__( 'Success notice text color', 'woodmart' ),
		'group'        => esc_html__( 'Success', 'woodmart' ),
		'type'         => 'color',
		'section'      => 'notices_section',
		'selector_var' => '--notices-success-color',
		'default'      => array( 'idle' => '#fff' ),
		'priority'     => 20,
	)
);

Options::add_field(
	array(
		'id'           => 'warning_notice_bg_color',
		'name'         => esc_html__( 'Warning notice background color', 'woodmart' ),
		'group'        => esc_html__( 'Warning', 'woodmart' ),
		'type'         => 'color',
		'section'      => 'notices_section',
		'selector_var' => '--notices-warning-bg',
		'default'      => array( 'idle' => '#E0B252' ),
		'priority'     => 30,
	)
);

Options::add_field(
	array(
		'id'           => 'warning_notice_test_color',
		'name'         => esc_html__( 'Warning notice text color', 'woodmart' ),
		'group'        => esc_html__( 'Warning', 'woodmart' ),
		'type'         => 'color',
		'section'      => 'notices_section',
		'selector_var' => '--notices-warning-color',
		'default'      => array( 'idle' => '#fff' ),
		'priority'     => 40,
	)
);
