<?php
/**
 * Page metaboxes
 *
 * @package xts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

use XTS\Metaboxes;

if ( ! function_exists( 'woodmart_register_page_metaboxes' ) ) {
	/**
	 * Register page metaboxes
	 *
	 * @since 1.0.0
	 */
	function woodmart_register_page_metaboxes() {
		global $woodmart_transfer_options, $woodmart_prefix;

		$woodmart_prefix = '_woodmart_';

		$page_metabox = Metaboxes::add_metabox(
			array(
				'id'         => 'xts_page_metaboxes',
				'title'      => esc_html__( 'Page Setting (custom metabox from theme)', 'woodmart' ),
				'post_types' => array( 'page', 'post', 'portfolio' ),
			)
		);

		$page_metabox->add_section(
			array(
				'id'       => 'general',
				'name'     => esc_html__( 'General', 'woodmart' ),
				'priority' => 10,
				'icon'     => WOODMART_ASSETS . '/assets/images/dashboard-icons/settings.svg',
			)
		);

		$page_metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'whb_header',
				'name'        => esc_html__( 'Custom header for this page', 'woodmart' ),
				'description' => esc_html__( 'If you are using our header builder for your header configuration you can select different layout from the list for this particular page.', 'woodmart' ),
				'group'       => esc_html__( 'Header options', 'woodmart' ),
				'type'        => 'select',
				'section'     => 'general',
				'options'     => woodmart_get_whb_headers_array( true, true ),
				'default'     => 'inherit',
				'priority'    => 10,
			)
		);

		$page_metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'open_categories',
				'type'        => 'checkbox',
				'name'        => esc_html__( 'Open categories menu', 'woodmart' ),
				'description' => esc_html__( 'Always shows categories navigation on this page', 'woodmart' ),
				'group'       => esc_html__( 'Header options', 'woodmart' ),
				'section'     => 'general',
				'priority'    => 20,
			)
		);

		$page_metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'title_off',
				'type'        => 'checkbox',
				'name'        => esc_html__( 'Disable Page title', 'woodmart' ),
				'description' => esc_html__( 'You can hide page heading for this page', 'woodmart' ),
				'group'       => esc_html__( 'Page title options', 'woodmart' ),
				'section'     => 'general',
				'priority'    => 30,
			)
		);

		$page_metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'title_image',
				'type'        => 'upload',
				'name'        => esc_html__( 'Image for page title', 'woodmart' ),
				'description' => esc_html__( 'Upload an image', 'woodmart' ),
				'group'       => esc_html__( 'Page title options', 'woodmart' ),
				'section'     => 'general',
				'data_type'   => 'url',
				'priority'    => 40,
			)
		);

		$page_metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'title_bg_color',
				'type'        => 'color',
				'name'        => esc_html__( 'Page title background color', 'woodmart' ),
				'description' => esc_html__( 'Сhoose a color', 'woodmart' ),
				'group'       => esc_html__( 'Page title options', 'woodmart' ),
				'section'     => 'general',
				'data_type'   => 'hex',
				'priority'    => 50,
			)
		);

		$page_metabox->add_field(
			array(
				'id'       => $woodmart_prefix . 'title_color',
				'name'     => esc_html__( 'Text color for title', 'woodmart' ),
				'group'    => esc_html__( 'Page title options', 'woodmart' ),
				'type'     => 'buttons',
				'section'  => 'general',
				'options'  => array(
					'default' => array(
						'name'  => esc_html__( 'Inherit', 'woodmart' ),
						'value' => 'default',
					),
					'light'   => array(
						'name'  => esc_html__( 'Light', 'woodmart' ),
						'value' => 'light',
					),
					'dark'    => array(
						'name'  => esc_html__( 'Dark', 'woodmart' ),
						'value' => 'dark',
					),
				),
				'default'  => 'default',
				'priority' => 60,
			)
		);

		$page_metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'page-title-size',
				'name'        => esc_html__( 'Page title size' ),
				'description' => esc_html__( 'You can set different sizes for your pages titles', 'woodmart' ),
				'group'       => esc_html__( 'Page title options', 'woodmart' ),
				'type'        => 'buttons',
				'section'     => 'general',
				'options'     => array(
					'inherit' => array(
						'name'  => esc_html__( 'Inherit', 'woodmart' ),
						'value' => 'inherit',
					),
					'default' => array(
						'name'  => esc_html__( 'Default', 'woodmart' ),
						'value' => 'default',
					),
					'small'   => array(
						'name'  => esc_html__( 'Small', 'woodmart' ),
						'value' => 'small',
					),
					'large'   => array(
						'name'  => esc_html__( 'Large', 'woodmart' ),
						'value' => 'large',
					),
				),
				'default'     => 'inherit',
				'priority'    => 70,
			)
		);

		$page_metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'main_layout',
				'name'        => esc_html__( 'Sidebar position', 'woodmart' ),
				'description' => esc_html__( 'Select main content and sidebar alignment.', 'woodmart' ),
				'group'       => esc_html__( 'Sidebar options', 'woodmart' ),
				'type'        => 'buttons',
				'section'     => 'general',
				'options'     => array(
					'default'       => array(
						'name'  => esc_html__( 'Inherit', 'woodmart' ),
						'value' => 'default',
					),
					'full-width'    => array(
						'name'  => esc_html__( 'Without', 'woodmart' ),
						'value' => 'full-width',
					),
					'sidebar-left'  => array(
						'name'  => esc_html__( 'Left', 'woodmart' ),
						'value' => 'sidebar-left',
					),
					'sidebar-right' => array(
						'name'  => esc_html__( 'Right', 'woodmart' ),
						'value' => 'sidebar-right',
					),
				),
				'default'     => 'default',
				'priority'    => 80,
			)
		);

		$page_metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'sidebar_width',
				'name'        => esc_html__( 'Sidebar size', 'woodmart' ),
				'description' => esc_html__( 'You can set different sizes for your pages sidebar', 'woodmart' ),
				'group'       => esc_html__( 'Sidebar options', 'woodmart' ),
				'type'        => 'buttons',
				'section'     => 'general',
				'options'     => array(
					'default' => array(
						'name'  => esc_html__( 'Inherit', 'woodmart' ),
						'value' => 'default',
					),
					2         => array(
						'name'  => esc_html__( 'Small', 'woodmart' ),
						'value' => 2,
					),
					3         => array(
						'name'  => esc_html__( 'Medium', 'woodmart' ),
						'value' => 3,
					),
					4         => array(
						'name'  => esc_html__( 'Large', 'woodmart' ),
						'value' => 4,
					),
				),
				'default'     => 'default',
				'priority'    => 90,
			)
		);

		$woodmart_transfer_options[] = 'page-title-size';
		$woodmart_transfer_options[] = 'main_layout';
		$woodmart_transfer_options[] = 'sidebar_width';

		$page_metabox->add_field(
			array(
				'id'       => $woodmart_prefix . 'custom_sidebar',
				'name'     => esc_html__( 'Custom sidebar for this page', 'woodmart' ),
				'group'    => esc_html__( 'Sidebar options', 'woodmart' ),
				'type'     => 'select',
				'section'  => 'general',
				'options'  => woodmart_get_sidebars_array( true ),
				'priority' => 100,
			)
		);

		$page_metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'footer_off',
				'type'        => 'checkbox',
				'name'        => esc_html__( 'Disable footer', 'woodmart' ),
				'description' => esc_html__( 'You can disable footer for this page', 'woodmart' ),
				'group'       => esc_html__( 'Footer options', 'woodmart' ),
				'section'     => 'general',
				'priority'    => 110,
			)
		);

		$page_metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'prefooter_off',
				'type'        => 'checkbox',
				'name'        => esc_html__( 'Disable prefooter', 'woodmart' ),
				'description' => esc_html__( 'You can disable prefooter for this page', 'woodmart' ),
				'group'       => esc_html__( 'Footer options', 'woodmart' ),
				'section'     => 'general',
				'priority'    => 120,
			)
		);

		$page_metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'copyrights_off',
				'type'        => 'checkbox',
				'name'        => esc_html__( 'Disable copyrights', 'woodmart' ),
				'description' => esc_html__( 'You can disable copyrights for this page', 'woodmart' ),
				'group'       => esc_html__( 'Footer options', 'woodmart' ),
				'section'     => 'general',
				'priority'    => 130,
			)
		);
	}

	add_action( 'init', 'woodmart_register_page_metaboxes', 100 );
}


$post_category_metabox = Metaboxes::add_metabox(
	array(
		'id'         => 'xts_post_category_metaboxes',
		'title'      => esc_html__( 'Extra options from theme', 'woodmart' ),
		'object'     => 'term',
		'taxonomies' => array( 'category' ),
	)
);

$post_category_metabox->add_section(
	array(
		'id'       => 'general',
		'name'     => esc_html__( 'General', 'woodmart' ),
		'icon'     => WOODMART_ASSETS . '/assets/images/dashboard-icons/settings.svg',
		'priority' => 10,
	)
);

$post_category_metabox->add_field(
	array(
		'id'          => '_woodmart_blog_design',
		'name'        => esc_html__( 'Blog Design', 'woodmart' ),
		'description' => esc_html__( 'You can use different design for your blog styled for the theme.', 'woodmart' ),
		'type'        => 'select',
		'section'     => 'general',
		'options'     => array(
			'inherit'      => array(
				'name'  => esc_html__( 'Inherit', 'woodmart' ),
				'value' => 'inherit',
			),
			'default'      => array(
				'name'  => esc_html__( 'Default', 'woodmart' ),
				'value' => 'Default',
			),
			'default-alt'  => array(
				'name'  => esc_html__( 'Default alternative', 'woodmart' ),
				'value' => 'default-alt',
			),
			'small-images' => array(
				'name'  => esc_html__( 'Small images', 'woodmart' ),
				'value' => 'small-images',
			),
			'chess'        => array(
				'name'  => esc_html__( 'Chess', 'woodmart' ),
				'value' => 'chess',
			),
			'masonry'      => array(
				'name'  => esc_html__( 'Masonry grid', 'woodmart' ),
				'value' => 'default',
			),
			'mask'         => array(
				'name'  => esc_html__( 'Mask on image', 'woodmart' ),
				'value' => 'mask',
			),
		),
		'priority'    => 10,
	)
);
