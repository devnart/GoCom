<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}
use XTS\Options;

/**
 * General
 */
Options::add_section(
	array(
		'id'       => 'footer_section',
		'name'     => esc_html__( 'Footer', 'woodmart' ),
		'priority' => 40,
		'icon'     => 'dashicons dashicons-editor-kitchensink',
	)
);

Options::add_field(
	array(
		'id'          => 'disable_footer',
		'section'     => 'footer_section',
		'name'        => esc_html__( 'Footer', 'woodmart' ),
		'description' => esc_html__( 'Enable/disable footer on your website.', 'woodmart' ),
		'type'        => 'switcher',
		'default'     => '1',
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'footer-layout',
		'name'        => esc_html__( 'Footer layout', 'woodmart' ),
		'description' => esc_html__( 'Choose your footer layout. Depending on columns number you will have different number of widget areas for footer in Appearance->Widgets', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'footer_section',
		'options'     => array(
			1  => array(
				'name'  => esc_html__( 'Single Column', 'woodmart' ),
				'value' => 1,
				'image' => WOODMART_ASSETS_IMAGES . '/settings/footer-1.png',
			),
			2  => array(
				'name'  => esc_html__( 'Two Columns', 'woodmart' ),
				'value' => 2,
				'image' => WOODMART_ASSETS_IMAGES . '/settings/footer-2.png',
			),
			3  => array(
				'name'  => esc_html__( 'Three Columns', 'woodmart' ),
				'value' => 3,
				'image' => WOODMART_ASSETS_IMAGES . '/settings/footer-3.png',
			),
			4  => array(
				'name'  => esc_html__( 'Four Columns', 'woodmart' ),
				'value' => 4,
				'image' => WOODMART_ASSETS_IMAGES . '/settings/footer-4.png',
			),
			5  => array(
				'name'  => esc_html__( 'Six Columns', 'woodmart' ),
				'value' => 5,
				'image' => WOODMART_ASSETS_IMAGES . '/settings/footer-5.png',
			),
			6  => array(
				'name'  => esc_html__( '1/4 + 1/2 + 1/4', 'woodmart' ),
				'value' => 6,
				'image' => WOODMART_ASSETS_IMAGES . '/settings/footer-6.png',
			),
			7  => array(
				'name'  => esc_html__( '1/2 + 1/4 + 1/4', 'woodmart' ),
				'value' => 7,
				'image' => WOODMART_ASSETS_IMAGES . '/settings/footer-7.png',
			),
			8  => array(
				'name'  => esc_html__( '1/4 + 1/4 + 1/2', 'woodmart' ),
				'value' => 8,
				'image' => WOODMART_ASSETS_IMAGES . '/settings/footer-8.png',
			),
			9  => array(
				'name'  => esc_html__( 'Two rows', 'woodmart' ),
				'value' => 9,
				'image' => WOODMART_ASSETS_IMAGES . '/settings/footer-9.png',
			),
			10 => array(
				'name'  => esc_html__( 'Two rows', 'woodmart' ),
				'value' => 10,
				'image' => WOODMART_ASSETS_IMAGES . '/settings/footer-10.png',
			),
			11 => array(
				'name'  => esc_html__( 'Two rows', 'woodmart' ),
				'value' => 11,
				'image' => WOODMART_ASSETS_IMAGES . '/settings/footer-11.png',
			),
			12 => array(
				'name'  => esc_html__( 'Two rows', 'woodmart' ),
				'value' => 12,
				'image' => WOODMART_ASSETS_IMAGES . '/settings/footer-12.png',
			),
			13 => array(
				'name'  => esc_html__( 'Five columns', 'woodmart' ),
				'value' => 13,
				'image' => WOODMART_ASSETS_IMAGES . '/settings/footer-13.png',
			),
		),
		'default'     => 13,
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'scroll_top_btn',
		'section'     => 'footer_section',
		'name'        => esc_html__( 'Scroll to top button', 'woodmart' ),
		'description' => esc_html__( 'This button moves you to the top of the page when you click it.', 'woodmart' ),
		'type'        => 'switcher',
		'default'     => '1',
		'priority'    => 30,
	)
);

Options::add_field(
	array(
		'id'          => 'sticky_footer',
		'section'     => 'footer_section',
		'name'        => esc_html__( 'Sticky footer', 'woodmart' ),
		'description' => esc_html__( 'The footer will be displayed behind the content of the page and will be visible when user scrolls to the bottom on the page.', 'woodmart' ),
		'type'        => 'switcher',
		'default'     => false,
		'priority'    => 40,
	)
);

Options::add_field(
	array(
		'id'          => 'collapse_footer_widgets',
		'section'     => 'footer_section',
		'name'        => esc_html__( 'Collapse widgets on mobile', 'woodmart' ),
		'description' => esc_html__( 'Widgets added to the footer will be collapsed by default and opened when you click on their titles.', 'woodmart' ),
		'type'        => 'switcher',
		'default'     => false,
		'priority'    => 50,
	)
);

Options::add_field(
	array(
		'id'          => 'footer-style',
		'name'        => esc_html__( 'Footer text color', 'woodmart' ),
		'description' => esc_html__( 'Choose your footer color scheme', 'woodmart' ),
		'group'       => esc_html__( 'Footer color scheme options', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'footer_section',
		'options'     => array(
			'dark'  => array(
				'name'  => esc_html__( 'Dark', 'woodmart' ),
				'value' => 'dark',
			),
			'light' => array(
				'name'  => esc_html__( 'Light', 'woodmart' ),
				'value' => 'light',
			),
		),
		'default'     => 'dark',
		'priority'    => 60,
	)
);

Options::add_field(
	array(
		'id'          => 'footer-bar-bg',
		'name'        => esc_html__( 'Footer background', 'woodmart' ),
		'description' => esc_html__( 'You can set your footer section background color or upload some graphic.', 'woodmart' ),
		'group'       => esc_html__( 'Footer color scheme options', 'woodmart' ),
		'type'        => 'background',
		'default'     => array(
			'color' => '#ffffff',
		),
		'section'     => 'footer_section',
		'selector'    => '.footer-container',
		'tags'        => 'footer color',
		'priority'    => 70,
	)
);

Options::add_field(
	array(
		'id'          => 'disable_copyrights',
		'section'     => 'footer_section',
		'name'        => esc_html__( 'Copyrights', 'woodmart' ),
		'description' => esc_html__( 'Turn on/off a section with your copyrights under the footer.', 'woodmart' ),
		'group'       => esc_html__( 'Footer copyrights', 'woodmart' ),
		'type'        => 'switcher',
		'default'     => '1',
		'priority'    => 80,
	)
);

Options::add_field(
	array(
		'id'          => 'copyrights-layout',
		'name'        => esc_html__( 'Copyrights layout', 'woodmart' ),
		'description' => esc_html__( 'Set different copyrights section layout.', 'woodmart' ),
		'group'       => esc_html__( 'Footer copyrights', 'woodmart' ),
		'type'        => 'select',
		'section'     => 'footer_section',
		'options'     => array(
			'two-columns' => array(
				'name'  => esc_html__( 'Two columns', 'woodmart' ),
				'value' => 'two-columns',
			),
			'centered'    => array(
				'name'  => esc_html__( 'Centered', 'woodmart' ),
				'value' => 'centered',
			),
		),
		'default'     => 'two-columns',
		'priority'    => 90,
	)
);

Options::add_field(
	array(
		'id'          => 'copyrights',
		'name'        => esc_html__( 'Copyrights text', 'woodmart' ),
		'group'       => esc_html__( 'Footer copyrights', 'woodmart' ),
		'type'        => 'textarea',
		'wysiwyg'     => false,
		'description' => esc_html__( 'Place here text you want to see in the copyrights area. You can use shortocdes. Ex.: [social_buttons]', 'woodmart' ),
		'default'     => '<small><a href="http://woodmart.xtemos.com"><strong>WOODMART</strong></a> <i class="fa fa-copyright"></i>  2019 CREATED BY <a href="http://xtemos.com"><strong>XTEMOS STUDIO</strong></a>. PREMIUM E-COMMERCE SOLUTIONS.</small>',
		'section'     => 'footer_section',
		'priority'    => 100,
	)
);

Options::add_field(
	array(
		'id'          => 'copyrights2',
		'name'        => esc_html__( 'Text next to copyrights', 'woodmart' ),
		'group'       => esc_html__( 'Footer copyrights', 'woodmart' ),
		'type'        => 'textarea',
		'wysiwyg'     => false,
		'description' => esc_html__( 'You can use shortcodes. Ex.: [social_buttons] or place an HTML Block built with WPBakery Page Builder builder there like [html_block id="258"]', 'woodmart' ),
		'default'     => '<img src="' . WOODMART_IMAGES . '/payments.png" alt="payments">',
		'section'     => 'footer_section',
		'priority'    => 120,
	)
);

Options::add_field(
	array(
		'id'          => 'prefooter_area',
		'name'        => esc_html__( 'HTML before footer', 'woodmart' ),
		'type'        => 'textarea',
		'wysiwyg'     => false,
		'description' => esc_html__( 'This is the text before footer field, again good for additional info. You can place here any shortcode, for ex.: [html_block id=""]', 'woodmart' ),
		'group'       => esc_html__( 'Prefooter area', 'woodmart' ),
		'default'     => '[html_block id="258"]',
		'section'     => 'footer_section',
		'tags'        => 'prefooter',
		'priority'    => 130,
	)
);


