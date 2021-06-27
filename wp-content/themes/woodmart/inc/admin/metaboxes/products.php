<?php
/**
 * Product metaboxes
 *
 * @package xts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

use XTS\Metaboxes;

if ( ! function_exists( 'woodmart_register_product_metaboxes' ) ) {
	/**
	 * Register page metaboxes
	 *
	 * @since 1.0.0
	 */
	function woodmart_register_product_metaboxes() {
		global $woodmart_transfer_options, $woodmart_prefix;

		$woodmart_prefix = '_woodmart_';

		$product_metabox = Metaboxes::add_metabox(
			array(
				'id'         => 'xts_product_metaboxes',
				'title'      => esc_html__( 'Product Setting (custom metabox from theme)', 'woodmart' ),
				'post_types' => array( 'product' ),
			)
		);

		$product_metabox->add_section(
			array(
				'id'       => 'general',
				'name'     => esc_html__( 'General', 'woodmart' ),
				'priority' => 10,
				'icon'     => WOODMART_ASSETS . '/assets/images/dashboard-icons/settings.svg',
			)
		);
		
		$product_metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'whb_header',
				'name'        => esc_html__( 'Custom header for this product', 'woodmart' ),
				'description' => esc_html__( 'If you are using our header builder for your header configuration you can select different layout from the list for this particular product.', 'woodmart' ),
				'group'       => esc_html__( 'Header options', 'woodmart' ),
				'type'        => 'select',
				'section'     => 'general',
				'options'     => woodmart_get_whb_headers_array( true, true ),
				'default'     => 'inherit',
				'priority'    => 9,
			)
		);

		$product_metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'product_design',
				'name'        => esc_html__( 'Product page design', 'woodmart' ),
				'description' => esc_html__( 'Choose between different predefined designs', 'woodmart' ),
				'group'       => esc_html__( 'Product design & color options', 'woodmart' ),
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
					'alt'     => array(
						'name'  => esc_html__( 'Centered', 'woodmart' ),
						'value' => 'default',
					),
				),
				'default'     => 'inherit',
				'priority'    => 10,
			)
		);

		$product_metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'single_product_style',
				'name'        => esc_html__( 'Product image width', 'woodmart' ),
				'description' => esc_html__( 'You can choose different page layout depending on the product image size you need', 'woodmart' ),
				'group'       => esc_html__( 'Product design & color options', 'woodmart' ),
				'type'        => 'select',
				'section'     => 'general',
				'options'     => array(
					'inherit' => array(
						'name'  => esc_html__( 'Inherit', 'woodmart' ),
						'value' => 'inherit',
					),
					1         => array(
						'name'  => esc_html__( 'Small image', 'woodmart' ),
						'value' => 1,
					),
					2         => array(
						'name'  => esc_html__( 'Medium', 'woodmart' ),
						'value' => 2,
					),
					3         => array(
						'name'  => esc_html__( 'Large', 'woodmart' ),
						'value' => 3,
					),
					4         => array(
						'name'  => esc_html__( 'Full width (container)', 'woodmart' ),
						'value' => 4,
					),
					5         => array(
						'name'  => esc_html__( 'Full width (window)', 'woodmart' ),
						'value' => 5,
					),
				),
				'default'     => 'inherit',
				'priority'    => 20,
			)
		);

		$product_metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'thums_position',
				'name'        => esc_html__( 'Thumbnails position', 'woodmart' ),
				'description' => esc_html__( 'Use vertical or horizontal position for thumbnails', 'woodmart' ),
				'group'       => esc_html__( 'Product design & color options', 'woodmart' ),
				'type'        => 'select',
				'section'     => 'general',
				'options'     => array(
					'inherit'         => array(
						'name'  => esc_html__( 'Inherit', 'woodmart' ),
						'value' => 'inherit',
					),
					'left'            => array(
						'name'  => esc_html__( 'Left (vertical position)', 'woodmart' ),
						'value' => 'left',
					),
					'bottom'          => array(
						'name'  => esc_html__( 'Bottom (horizontal carousel)', 'woodmart' ),
						'value' => 'bottom',
					),
					'bottom_column'   => array(
						'name'  => esc_html__( 'Bottom (1 column)', 'woodmart' ),
						'value' => 'left',
					),
					'bottom_grid'     => array(
						'name'  => esc_html__( 'Bottom (2 columns)', 'woodmart' ),
						'value' => 'left',
					),
					'bottom_combined' => array(
						'name'  => esc_html__( 'Combined grid', 'woodmart' ),
						'value' => 'bottom_combined',
					),
					'centered'        => array(
						'name'  => esc_html__( 'Centered', 'woodmart' ),
						'value' => 'centered',
					),
					'without'         => array(
						'name'  => esc_html__( 'Without', 'woodmart' ),
						'value' => 'without',
					),
				),
				'default'     => 'inherit',
				'priority'    => 30,
			)
		);

		$product_metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'product-background',
				'name'        => esc_html__( 'Single product background', 'woodmart' ),
				'description' => esc_html__( 'Set background for your products page. You can also specify different background for particular products while editing it.', 'woodmart' ),
				'group'       => esc_html__( 'Product design & color options', 'woodmart' ),
				'type'        => 'color',
				'section'     => 'general',
				'data_type'   => 'hex',
				'priority'    => 40,
			)
		);

		$product_metabox->add_field(
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
				'priority'    => 50,
			)
		);

		$product_metabox->add_field(
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
				'priority'    => 60,
			)
		);

		$product_metabox->add_field(
			array(
				'id'       => $woodmart_prefix . 'custom_sidebar',
				'name'     => esc_html__( 'Custom sidebar for this page', 'woodmart' ),
				'group'    => esc_html__( 'Sidebar options', 'woodmart' ),
				'type'     => 'select',
				'section'  => 'general',
				'options'  => woodmart_get_sidebars_array( true ),
				'priority' => 70,
			)
		);

		$blocks = woodmart_get_static_blocks_array( true );

		$blocks[0] = array(
			'name'  => 'none',
			'value' => 'none',
		);

		ksort( $blocks );

		$product_metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'extra_content',
				'name'        => esc_html__( 'Extra content block', 'woodmart' ),
				'description' => esc_html__( 'You can create some extra content with WPBakery Page Builder (in Admin panel / HTML Blocks / Add new) and add it to this product', 'woodmart' ),
				'group'       => esc_html__( 'Extra content options', 'woodmart' ),
				'type'        => 'select',
				'section'     => 'general',
				'options'     => $blocks,
				'priority'    => 80,
			)
		);

		$product_metabox->add_field(
			array(
				'id'       => $woodmart_prefix . 'extra_position',
				'name'     => esc_html__( 'Extra content position', 'woodmart' ),
				'group'    => esc_html__( 'Extra content options', 'woodmart' ),
				'type'     => 'buttons',
				'section'  => 'general',
				'options'  => array(
					'after'     => array(
						'name'  => esc_html__( 'After content', 'woodmart' ),
						'value' => 'after',
					),
					'before'    => array(
						'name'  => esc_html__( 'Before content', 'woodmart' ),
						'value' => 'before',
					),
					'prefooter' => array(
						'name'  => esc_html__( 'Prefooter', 'woodmart' ),
						'value' => 'prefooter',
					),
				),
				'default'  => 'after',
				'priority' => 90,
			)
		);

		$product_metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'hide_tabs_titles',
				'type'        => 'checkbox',
				'name'        => esc_html__( 'Hide tabs headings', 'woodmart' ),
				'description' => esc_html__( 'Description and Additional information', 'woodmart' ),
				'group'       => esc_html__( 'Product tab options', 'woodmart' ),
				'section'     => 'general',
				'priority'    => 100,
			)
		);

		$product_metabox->add_field(
			array(
				'id'       => $woodmart_prefix . 'product_custom_tab_title',
				'type'     => 'text_input',
				'name'     => esc_html__( 'Custom tab title', 'woodmart' ),
				'group'    => esc_html__( 'Product tab options', 'woodmart' ),
				'section'  => 'general',
				'priority' => 110,
			)
		);

		$product_metabox->add_field(
			array(
				'id'       => $woodmart_prefix . 'product_custom_tab_content',
				'type'     => 'textarea',
				'wysiwyg'  => true,
				'name'     => esc_html__( 'Custom tab content', 'woodmart' ),
				'group'    => esc_html__( 'Product tab options', 'woodmart' ),
				'section'  => 'general',
				'priority' => 120,
			)
		);

		$product_metabox->add_field(
			array(
				'id'       => $woodmart_prefix . 'new_label_date',
				'type'     => 'text_input',
				'name'     => esc_html__( 'Mark product as "New" till date', 'woodmart' ),
				'description' => esc_html__( 'Specify the end date when the "New" status will be retired. NOTE: "Permanent "New" label" option should be disabled if you use the exact date.', 'woodmart' ),
				'group'    => esc_html__( 'Product extra options', 'woodmart' ),
				'section'  => 'general',
				'datepicker' => true,
				'priority' => 130,
			)
		);

		$product_metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'new_label',
				'type'        => 'checkbox',
				'name'        => esc_html__( 'Permanent "New" label', 'woodmart' ),
				'description' => esc_html__( 'Enable this option to make your product have "New" status forever.', 'woodmart' ),
				'group'       => esc_html__( 'Product extra options', 'woodmart' ),
				'section'     => 'general',
				'priority'    => 131,
			)
		);

		$product_metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'related_off',
				'type'        => 'checkbox',
				'name'        => esc_html__( 'Hide related products', 'woodmart' ),
				'description' => esc_html__( 'You can hide related products on this page', 'woodmart' ),
				'group'       => esc_html__( 'Product extra options', 'woodmart' ),
				'section'     => 'general',
				'priority'    => 140,
			)
		);

		$taxonomies_list = array(
			'' => array(
				'name'  => 'Select',
				'value' => '',
			),
		);
		$taxonomies      = get_taxonomies();
		foreach ( $taxonomies as $taxonomy ) {
			$taxonomies_list[ $taxonomy ] = array(
				'name'  => $taxonomy,
				'value' => $taxonomy,
			);
		}

		$product_metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'swatches_attribute',
				'type'        => 'select',
				'name'        => esc_html__( 'Grid swatch attribute to display', 'woodmart' ),
				'description' => esc_html__( 'Choose attribute that will be shown on products grid for this particular product', 'woodmart' ),
				'group'       => esc_html__( 'Product extra options', 'woodmart' ),
				'section'     => 'general',
				'options'     => $taxonomies_list,
				'priority'    => 150,
			)
		);

		$product_metabox->add_field(
			array(
				'id'       => $woodmart_prefix . 'product_video',
				'type'     => 'text_input',
				'name'     => esc_html__( 'Product video URL', 'woodmart' ),
				'description' => esc_html__( 'URL example: https://www.youtube.com/watch?v=LXb3EKWsInQ', 'woodmart' ),
				'group'    => esc_html__( 'Product extra options', 'woodmart' ),
				'section'  => 'general',
				'priority' => 160,
			)
		);

		$product_metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'product_hashtag',
				'type'        => 'text_input',
				'name'        => esc_html__( 'Instagram product hashtag (deprecated)', 'woodmart' ),
				'description' => wp_kses( __( 'Insert tag that will be used to display images from instagram from your customers. For example: <strong>#nike_rush_run</strong>', 'woodmart' ), 'default' ),
				'group'       => esc_html__( 'Product extra options', 'woodmart' ),
				'section'     => 'general',
				'priority'    => 170,
			)
		);

		$woodmart_local_transfer_options = array(
			'product_design',
			'single_product_style',
			'thums_position',
			'product-background',
			'main_layout',
			'sidebar_width',
		);
		
		$woodmart_transfer_options = array_merge( $woodmart_transfer_options, $woodmart_local_transfer_options );
	}

	add_action( 'init', 'woodmart_register_product_metaboxes', 100 );
}

$product_attribute_metabox = Metaboxes::add_metabox(
	array(
		'id'         => 'xts_product_attribute_metaboxes',
		'title'      => esc_html__( 'Extra options from theme', 'woodmart' ),
		'object'     => 'term',
		'taxonomies' => array( 'product_cat' ),
	)
);

$product_attribute_metabox->add_section(
	array(
		'id'       => 'general',
		'name'     => esc_html__( 'General', 'woodmart' ),
		'icon'     => WOODMART_ASSETS . '/assets/images/dashboard-icons/settings.svg',
		'priority' => 10,
	)
);

$product_attribute_metabox->add_field(
	array(
		'id'          => 'title_image',
		'name'        => esc_html__( 'Image for category heading', 'woodmart' ),
		'description' => esc_html__( 'Upload an image', 'woodmart' ),
		'type'        => 'upload',
		'section'     => 'general',
		'data_type'   => 'url',
		'priority'    => 10,
	)
);

$product_attribute_metabox->add_field(
	array(
		'id'          => 'category_icon',
		'name'        => esc_html__( 'Image (icon) for categories navigation on the shop page', 'woodmart' ),
		'description' => esc_html__( 'Upload an image', 'woodmart' ),
		'type'        => 'upload',
		'section'     => 'general',
		'data_type'   => 'url',
		'priority'    => 20,
	)
);

$product_attribute_metabox->add_field(
	array(
		'id'          => 'category_icon_alt',
		'name'        => esc_html__( 'Icon to display in the main menu (or any other menu through the site)', 'woodmart' ),
		'description' => esc_html__( 'Upload an image', 'woodmart' ),
		'type'        => 'upload',
		'section'     => 'general',
		'data_type'   => 'url',
		'priority'    => 30,
	)
);
