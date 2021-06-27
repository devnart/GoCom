<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}
use XTS\Options;

/**
 * General
 */
Options::add_section(
	array(
		'id'       => 'general_section',
		'name'     => esc_html__( 'General', 'woodmart' ),
		'priority' => 10,
		'icon'     => 'dashicons dashicons-admin-home',
	)
);

/**
 * General
 */
Options::add_field(
	array(
		'id'           => 'default_header',
		'name'         => esc_html__( 'Header', 'woodmart' ),
		'description'  => esc_html__( 'Set your default header for all pages from the list of all headers created with our Header Builder.', 'woodmart' ),
		'type'         => 'select',
		'section'      => 'general_section',
		'empty_option' => true,
		'select2'      => true,
		'options'      => woodmart_get_headers_array(),
		'priority'     => 8,
	)
);

Options::add_field(
	array(
		'id'       => 'page_builder',
		'name'     => esc_html__( 'Page builder' ),
		'type'     => 'buttons',
		'section'  => 'general_section',
		'options'  => array(
			'wpb'       => array(
				'name'  => esc_html__( 'WPB', 'woodmart' ),
				'value' => 'wpb',
			),
			'elementor' => array(
				'name'  => esc_html__( 'Elementor', 'woodmart' ),
				'value' => 'elementor',
			),
		),
		'default'  => 'wpb',
		'priority' => 9,
	)
);

Options::add_field(
	array(
		'id'          => 'favicon',
		'name'        => esc_html__( 'Favicon image (deprecated)', 'woodmart' ),
		'description' => esc_html__( 'You need to upload favicon image using WordPress core option in Appearance -> Customize -> Site identity -> Site icon.', 'woodmart' ),
		'type'        => 'upload',
		'section'     => 'general_section',
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'favicon_retina',
		'name'        => esc_html__( 'Favicon retina image (deprecated)', 'woodmart' ),
		'description' => esc_html__( 'You need to upload favicon image using WordPress core option in Appearance -> Customize -> Site identity -> Site icon.', 'woodmart' ),
		'type'        => 'upload',
		'section'     => 'general_section',
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'       => 'page_comments',
		'name'     => esc_html__( 'Show comments on page', 'woodmart' ),
		'type'     => 'switcher',
		'section'  => 'general_section',
		'default'  => '1',
		'priority' => 30,
	)
);

Options::add_field(
	array(
		'id'          => 'google_map_api_key',
		'name'        => esc_html__( 'Google map API key', 'woodmart' ),
		'type'        => 'text_input',
		'description' => wp_kses(
			__( 'Obtain API key <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">here</a> to use our Google Map VC element.', 'woodmart' ),
			'default'
		),
		'section'     => 'general_section',
		'tags'        => 'google api key',
		'priority'    => 40,
	)
);

Options::add_field(
	array(
		'id'           => 'custom_404_page',
		'name'         => esc_html__( 'Custom 404 page', 'woodmart' ),
		'type'         => 'select',
		'description'  => esc_html__( 'You can make your custom 404 page', 'woodmart' ),
		'section'      => 'general_section',
		'options'      => woodmart_get_pages( true ),
		'empty_option' => true,
		'priority'     => 40,
	)
);

Options::add_field(
	array(
		'id'       => 'sticky_notifications',
		'name'     => esc_html__( 'Sticky notifications (deprecated)', 'woodmart' ),
		'type'     => 'switcher',
		'section'  => 'general_section',
		'default'  => '0',
		'priority' => 50,
	)
);

Options::add_field(
	array(
		'id'          => 'negative_gap',
		'name'        => esc_html__( 'Elementor sections negative margin', 'woodmart' ),
		'description' => esc_html__( 'Add a negative margin to each Elementor section to align the content with your website container.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'general_section',
		'options'     => array(
			'enabled'  => array(
				'name'  => esc_html__( 'Enabled', 'woodmart' ),
				'value' => 'enabled',
			),
			'disabled' => array(
				'name'  => esc_html__( 'Disabled', 'woodmart' ),
				'value' => 'disabled',
			),
		),
		'requires'    => array(
			array(
				'key'     => 'page_builder',
				'compare' => 'equals',
				'value'   => 'elementor',
			),
		),
		'default'     => 'enabled',
		'priority'    => 51,
	)
);

Options::add_field(
	array(
		'id'          => 'enqueue_posts_results',
		'name'        => esc_html__( 'Display results from blog', 'woodmart' ),
		'description' => esc_html__( 'Enable search for two post types.', 'woodmart' ),
		'group'       => esc_html__( 'Search results', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'general_section',
		'default'     => false,
		'priority'    => 52,
	)
);

Options::add_field(
	array(
		'id'       => 'search_posts_results_column',
		'name'     => esc_html__( 'Number of columns for blog results', 'woodmart' ),
		'group'    => esc_html__( 'Search results', 'woodmart' ),
		'type'     => 'range',
		'section'  => 'general_section',
		'default'  => 2,
		'min'      => 2,
		'step'     => 1,
		'max'      => 6,
		'requires' => array(
			array(
				'key'     => 'enqueue_posts_results',
				'compare' => 'equals',
				'value'   => true,
			),
		),
		'priority' => 53,
	)
);

/**
 * Instagram.
 */
Options::add_section(
	array(
		'id'       => 'instagram_section',
		'parent'   => 'general_section',
		'name'     => esc_html__( 'Instagram API', 'woodmart' ),
		'priority' => 30,
	)
);

Options::add_field(
	array(
		'id'          => 'insta_token',
		'name'        => esc_html__( 'Connect instagram account', 'woodmart' ),
		'description' => 'To get this data, follow the instructions in our documentation <a href="https://xtemos.com/docs/woodmart/faq-guides/setup-instagram-api/" target="_blank">here</a>.',
		'type'        => 'instagram_api',
		'section'     => 'instagram_section',
		'priority'    => 10,
	)
);

/**
 * Mobile bottom navbar.
 */
Options::add_section(
	array(
		'id'       => 'general_navbar_section',
		'parent'   => 'general_section',
		'name'     => esc_html__( 'Mobile bottom navbar', 'woodmart' ),
		'priority' => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'sticky_toolbar',
		'name'        => esc_html__( 'Enable Sticky navbar', 'woodmart' ),
		'description' => esc_html__( 'Sticky navigation toolbar will be shown at the bottom on mobile devices.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'general_navbar_section',
		'default'     => false,
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'sticky_toolbar_label',
		'name'        => esc_html__( 'Navbar labels', 'woodmart' ),
		'description' => esc_html__( 'Show/hide labels under icons in the mobile navbar.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'general_navbar_section',
		'default'     => '1',
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'sticky_toolbar_fields',
		'name'        => esc_html__( 'Select buttons', 'woodmart' ),
		'description' => esc_html__( 'Choose which buttons will be used for sticky navbar.', 'woodmart' ),
		'type'        => 'select',
		'multiple'    => true,
		'select2'     => true,
		'section'     => 'general_navbar_section',
		'options'     => woodmart_get_sticky_toolbar_fields( true ),
		'default'     => array(
			'shop',
			'sidebar',
			'wishlist',
			'cart',
			'account',
		),
		'priority'    => 30,
	)
);


Options::add_field(
	array(
		'id'       => 'link_1_url',
		'name'     => esc_html__( 'Custom button URL', 'woodmart' ),
		'group'    => esc_html__( 'Custom button [1]', 'woodmart' ),
		'type'     => 'text_input',
		'section'  => 'general_navbar_section',
		'priority' => 40,
	)
);

Options::add_field(
	array(
		'id'       => 'link_1_text',
		'name'     => esc_html__( 'Custom button text', 'woodmart' ),
		'group'    => esc_html__( 'Custom button [1]', 'woodmart' ),
		'type'     => 'text_input',
		'section'  => 'general_navbar_section',
		'priority' => 41,
	)
);

Options::add_field(
	array(
		'id'       => 'link_1_icon',
		'name'     => esc_html__( 'Custom button icon', 'woodmart' ),
		'group'    => esc_html__( 'Custom button [1]', 'woodmart' ),
		'type'     => 'upload',
		'section'  => 'general_navbar_section',
		'priority' => 50,
	)
);

Options::add_field(
	array(
		'id'       => 'link_2_url',
		'name'     => esc_html__( 'Custom button URL', 'woodmart' ),
		'group'    => esc_html__( 'Custom button [2]', 'woodmart' ),
		'type'     => 'text_input',
		'section'  => 'general_navbar_section',
		'priority' => 60,
	)
);

Options::add_field(
	array(
		'id'       => 'link_2_text',
		'name'     => esc_html__( 'Custom button text', 'woodmart' ),
		'group'    => esc_html__( 'Custom button [2]', 'woodmart' ),
		'type'     => 'text_input',
		'section'  => 'general_navbar_section',
		'priority' => 61,
	)
);

Options::add_field(
	array(
		'id'       => 'link_2_icon',
		'name'     => esc_html__( 'Custom button icon', 'woodmart' ),
		'group'    => esc_html__( 'Custom button [2]', 'woodmart' ),
		'type'     => 'upload',
		'section'  => 'general_navbar_section',
		'priority' => 70,
	)
);

Options::add_field(
	array(
		'id'       => 'link_3_url',
		'name'     => esc_html__( 'Custom button URL', 'woodmart' ),
		'group'    => esc_html__( 'Custom button [3]', 'woodmart' ),
		'type'     => 'text_input',
		'section'  => 'general_navbar_section',
		'priority' => 80,
	)
);

Options::add_field(
	array(
		'id'       => 'link_3_text',
		'name'     => esc_html__( 'Custom button text', 'woodmart' ),
		'group'    => esc_html__( 'Custom button [3]', 'woodmart' ),
		'type'     => 'text_input',
		'section'  => 'general_navbar_section',
		'priority' => 81,
	)
);


Options::add_field(
	array(
		'id'       => 'link_3_icon',
		'name'     => esc_html__( 'Custom button icon', 'woodmart' ),
		'group'    => esc_html__( 'Custom button [3]', 'woodmart' ),
		'type'     => 'upload',
		'section'  => 'general_navbar_section',
		'priority' => 90,
	)
);

/**
 * Age verify.
 */
Options::add_section(
	array(
		'id'       => 'age_verify_section',
		'parent'   => 'general_section',
		'name'     => esc_html__( 'Age verify popup', 'woodmart' ),
		'priority' => 40,
	)
);

Options::add_field(
	array(
		'id'       => 'age_verify',
		'name'     => esc_html__( 'Enable age verification popup', 'woodmart' ),
		'type'     => 'switcher',
		'section'  => 'age_verify_section',
		'default'  => false,
		'priority' => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'age_verify_text',
		'name'        => esc_html__( 'Popup message', 'woodmart' ),
		'description' => esc_html__( 'Write a message warning your visitors about age restriction on your website', 'woodmart' ),
		'default'     => '<h4 class="text-center">Are you over 18?</h4>
<p class="text-center">You must be 18 years of age or older to view page. Please verify your age to enter.</p>',
		'type'        => 'textarea',
		'wysiwyg'     => true,
		'section'     => 'age_verify_section',
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'age_verify_text_error',
		'name'        => esc_html__( 'Error message', 'woodmart' ),
		'description' => esc_html__( 'This message will be displayed when the visitor don\'t verify his age.', 'woodmart' ),
		'default'     => '<h4 class="text-center">Access forbidden</h4>
<p class="text-center">Your access is restricted because of your age.</p>',
		'type'        => 'textarea',
		'wysiwyg'     => true,
		'section'     => 'age_verify_section',
		'priority'    => 30,
	)
);

Options::add_field(
	array(
		'id'       => 'age_verify_color_scheme',
		'name'     => esc_html__( 'Text color scheme', 'woodmart' ),
		'type'     => 'buttons',
		'section'  => 'age_verify_section',
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
		'priority' => 40,
	)
);

Options::add_field(
	array(
		'id'       => 'age_verify_background',
		'name'     => esc_html__( 'Background', 'woodmart' ),
		'type'     => 'background',
		'section'  => 'age_verify_section',
		'selector' => '.wd-age-verify',
		'priority' => 50,
	)
);

Options::add_field(
	array(
		'id'       => 'age_verify_width',
		'name'     => esc_html__( 'Width', 'woodmart' ),
		'type'     => 'range',
		'section'  => 'age_verify_section',
		'default'  => 500,
		'min'      => 400,
		'step'     => 10,
		'max'      => 1000,
		'priority' => 60,
	)
);
