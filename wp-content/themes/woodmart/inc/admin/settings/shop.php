<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

use XTS\Options;

/**
 * Shop
 */
Options::add_section(
	array(
		'id'       => 'shop_section',
		'name'     => esc_html__( 'Shop', 'woodmart' ),
		'priority' => 90,
		'icon'     => 'dashicons dashicons-cart',
	)
);

Options::add_field(
	array(
		'id'       => 'search_by_sku',
		'name'     => esc_html__( 'Search by product SKU', 'woodmart' ),
		'type'     => 'switcher',
		'section'  => 'shop_section',
		'default'  => '1',
		'priority' => 10,
	)
);

Options::add_field(
	array(
		'id'       => 'show_sku_on_ajax',
		'name'     => esc_html__( 'Show SKU on AJAX results', 'woodmart' ),
		'type'     => 'switcher',
		'section'  => 'shop_section',
		'requires' => array(
			array(
				'key'     => 'search_by_sku',
				'compare' => 'equals',
				'value'   => '1',
			),
		),
		'default'  => false,
		'priority' => 11,
	)
);

Options::add_field(
	array(
		'id'          => 'relevanssi_search',
		'name'        => esc_html__( 'Use Relevanssi for AJAX search', 'woodmart' ),
		'description' => 'You will need to install and activate this <a href="https://ru.wordpress.org/plugins/relevanssi/" target="_blank">plugin</a>',
		'type'        => 'switcher',
		'section'     => 'shop_section',
		'default'     => '0',
		'priority'    => 12,
	)
);

Options::add_field(
	array(
		'id'          => 'shop_filters',
		'name'        => esc_html__( 'Shop filters', 'woodmart' ),
		'description' => esc_html__( 'Enable shop filters widget\'s area above the products.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'shop_section',
		'default'     => false,
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'shop_filters_always_open',
		'name'        => esc_html__( 'Shop filters area always opened', 'woodmart' ),
		'description' => esc_html__( 'If you enable this option the shop filters will be always opened on the shop page.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'shop_section',
		'default'     => false,
		'priority'    => 30,
		'requires'    => array(
			array(
				'key'     => 'shop_filters',
				'compare' => 'equals',
				'value'   => '1',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'shop_filters_close',
		'name'        => esc_html__( 'Stop close filters after click', 'woodmart' ),
		'description' => esc_html__( 'This option will prevent filters area from closing when you click on certain filter links.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'shop_section',
		'default'     => false,
		'priority'    => 40,
		'requires'    => array(
			array(
				'key'     => 'shop_filters_always_open',
				'compare' => 'equals',
				'value'   => '0',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'shop_filters_type',
		'name'        => esc_html__( 'Shop filters content type', 'woodmart' ),
		'description' => esc_html__( 'You can use widgets or custom HTML block with our Product filters WPBakery element.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'shop_section',
		'default'     => 'widgets',
		'options'     => array(
			'widgets' => array(
				'name'  => esc_html__( 'Widgets', 'woodmart' ),
				'value' => 'widgets',
			),
			'content' => array(
				'name'  => esc_html__( 'Custom content', 'woodmart' ),
				'value' => 'content',
			),
		),
		'priority'    => 50,
		'requires'    => array(
			array(
				'key'     => 'shop_filters',
				'compare' => 'equals',
				'value'   => '1',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'shop_filters_content',
		'name'        => esc_html__( 'Shop filters custom content', 'woodmart' ),
		'description' => esc_html__( 'You can create an HTML Block in Dashboard -> HTML Blocks and add Product filters WPBakery element there.', 'woodmart' ),
		'type'        => 'select',
		'section'     => 'shop_section',
		'options'     => woodmart_get_static_blocks_array( true ),
		'priority'    => 60,
		'requires'    => array(
			array(
				'key'     => 'shop_filters_type',
				'compare' => 'equals',
				'value'   => 'content',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'ajax_shop',
		'name'        => esc_html__( 'AJAX shop', 'woodmart' ),
		'description' => esc_html__( 'Enable AJAX functionality for filters widgets on shop.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'shop_section',
		'default'     => '1',
		'priority'    => 70,
	)
);

Options::add_field(
	array(
		'id'          => 'ajax_scroll',
		'name'        => esc_html__( 'Scroll to top after AJAX', 'woodmart' ),
		'description' => esc_html__( 'Disable - Enable scroll to top after AJAX.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'shop_section',
		'default'     => '1',
		'priority'    => 80,
	)
);

Options::add_field(
	array(
		'id'       => 'mini_cart_quantity',
		'name'     => esc_html__( 'Quantity input on shopping cart widget', 'woodmart' ),
		'type'     => 'switcher',
		'section'  => 'shop_section',
		'default'  => '0',
		'priority' => 81,
	)
);

Options::add_field(
	array(
		'id'          => 'add_to_cart_action',
		'name'        => esc_html__( 'Action after add to cart', 'woodmart' ),
		'description' => esc_html__( 'Choose between showing informative popup and opening shopping cart widget. Only for shop page.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'shop_section',
		'options'     => array(
			'popup'   => array(
				'name'  => esc_html__( 'Show popup', 'woodmart' ),
				'value' => 'popup',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/add-to-cart-action/popup.jpg',
			),
			'widget'  => array(
				'name'  => esc_html__( 'Display widget', 'woodmart' ),
				'value' => 'widget',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/add-to-cart-action/widget.jpg',
			),
			'nothing' => array(
				'name'  => esc_html__( 'No action', 'woodmart' ),
				'value' => 'nothing',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/add-to-cart-action/nothing.jpg',
			),
		),
		'default'     => 'widget',
		'priority'    => 90,
	)
);

Options::add_field(
	array(
		'id'          => 'add_to_cart_action_timeout',
		'name'        => esc_html__( 'Hide widget automatically', 'woodmart' ),
		'description' => esc_html__( 'After adding to cart the shopping cart widget will be hidden automatically', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'shop_section',
		'default'     => false,
		'priority'    => 100,
		'requires'    => array(
			array(
				'key'     => 'add_to_cart_action',
				'compare' => 'not_equals',
				'value'   => 'nothing',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'add_to_cart_action_timeout_number',
		'name'        => esc_html__( 'Hide widget after', 'woodmart' ),
		'description' => esc_html__( 'Set the number of seconds for the shopping cart widget to be displayed after adding to cart', 'woodmart' ),
		'type'        => 'range',
		'section'     => 'shop_section',
		'default'     => 3,
		'min'         => 2,
		'max'         => 20,
		'step'        => 1,
		'priority'    => 110,
		'requires'    => array(
			array(
				'key'     => 'add_to_cart_action',
				'compare' => 'not_equals',
				'value'   => 'nothing',
			),
			array(
				'key'     => 'add_to_cart_action_timeout',
				'compare' => 'equals',
				'value'   => '1',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'hide_larger_price',
		'name'        => esc_html__( 'Hide "to" price', 'woodmart' ),
		'description' => esc_html__( 'This option will hide a higher price for variable products and leave only a small one.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'shop_section',
		'default'     => false,
		'priority'    => 111,
	)
);

Options::add_field(
	array(
		'id'          => 'quick_shop_variable',
		'name'        => esc_html__( '"Quick Shop" for variable products', 'woodmart' ),
		'description' => esc_html__( 'Allow your users to purchase variable products directly from the shop page.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'shop_section',
		'default'     => '1',
		'priority'    => 120,
	)
);

Options::add_field(
	array(
		'id'          => 'cat_desc_position',
		'name'        => esc_html__( 'Category description position', 'woodmart' ),
		'description' => esc_html__( 'You can change default products category description position and move it below the products.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'shop_section',
		'options'     => array(
			'before' => array(
				'name'  => esc_html__( 'Before product grid', 'woodmart' ),
				'value' => 'before',
			),
			'after'  => array(
				'name'  => esc_html__( 'After product grid', 'woodmart' ),
				'value' => 'after',
			),
		),
		'default'     => 'before',
		'priority'    => 130,
	)
);

Options::add_field(
	array(
		'id'          => 'empty_cart_text',
		'name'        => esc_html__( 'Empty cart text', 'woodmart' ),
		'description' => esc_html__( 'Text will be displayed if user don\'t add any products to cart', 'woodmart' ),
		'type'        => 'textarea',
		'wysiwyg'     => false,
		'section'     => 'shop_section',
		'default'     => 'Before proceed to checkout you must add some products to your shopping cart.<br> You will find a lot of interesting products on our "Shop" page.',
		'priority'    => 140,
	)
);

Options::add_field(
	array(
		'id'       => 'shop_page_breadcrumbs',
		'name'     => esc_html__( 'Breadcrumbs on shop page', 'woodmart' ),
		'type'     => 'switcher',
		'section'  => 'shop_section',
		'default'  => '1',
		'priority' => 150,
	)
);

Options::add_section(
	array(
		'id'       => 'products_grid',
		'parent'   => 'shop_section',
		'name'     => esc_html__( 'Products grid', 'woodmart' ),
		'priority' => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'shop_per_page',
		'name'        => esc_html__( 'Products per page', 'woodmart' ),
		'description' => esc_html__( 'Number of products per page', 'woodmart' ),
		'type'        => 'text_input',
		'section'     => 'products_grid',
		'default'     => 12,
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'per_page_links',
		'name'        => esc_html__( 'Products per page links', 'woodmart' ),
		'description' => esc_html__( 'Allow customers to change number of products per page', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'products_grid',
		'default'     => '1',
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'per_page_options',
		'name'        => esc_html__( 'Products per page variations', 'woodmart' ),
		'description' => esc_html__( 'For ex.: 12,24,36,-1. Use -1 to show all products on the page', 'woodmart' ),
		'type'        => 'text_input',
		'section'     => 'products_grid',
		'default'     => '9,24,36',
		'priority'    => 30,
		'requires'    => array(
			array(
				'key'     => 'per_page_links',
				'compare' => 'equals',
				'value'   => '1',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'shop_view',
		'name'        => __( 'Shop products view', 'woodmart' ),
		'description' => __( 'You can set different view mode for the shop page', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'products_grid',
		'options'     => array(
			'grid'      => array(
				'name'  => esc_html__( 'Grid', 'woodmart' ),
				'value' => 'grid',
			),
			'list'      => array(
				'name'  => esc_html__( 'List', 'woodmart' ),
				'value' => 'list',
			),
			'grid_list' => array(
				'name'  => esc_html__( 'Grid / List', 'woodmart' ),
				'value' => 'grid_list',
			),
			'list_grid' => array(
				'name'  => esc_html__( 'List / Grid', 'woodmart' ),
				'value' => 'list_grid',
			),
		),
		'default'     => 'grid',
		'priority'    => 40,
	)
);

Options::add_field(
	array(
		'id'          => 'products_columns',
		'name'        => esc_html__( 'Products columns', 'woodmart' ),
		'description' => esc_html__( 'How many products you want to show per row', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'products_grid',
		'options'     => array(
			2 => array(
				'name'  => 2,
				'value' => 2,
			),
			3 => array(
				'name'  => 3,
				'value' => 3,
			),
			4 => array(
				'name'  => 4,
				'value' => 4,
			),
			5 => array(
				'name'  => 5,
				'value' => 5,
			),
			6 => array(
				'name'  => 6,
				'value' => 6,
			),
		),
		'default'     => 3,
		'priority'    => 50,
		'requires'    => array(
			array(
				'key'     => 'shop_view',
				'compare' => 'not_equals',
				'value'   => 'list',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'products_columns_mobile',
		'name'        => esc_html__( 'Products columns on mobile', 'woodmart' ),
		'description' => esc_html__( 'How many products you want to show per row on mobile devices', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'products_grid',
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
		'priority'    => 60,
		'requires'    => array(
			array(
				'key'     => 'shop_view',
				'compare' => 'not_equals',
				'value'   => 'list',
			),
		),
	)
);


Options::add_field(
	array(
		'id'          => 'products_spacing',
		'name'        => esc_html__( 'Space between products', 'woodmart' ),
		'description' => esc_html__( 'You can set different spacing between blocks on shop page', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'products_grid',
		'options'     => array(
			0  => array(
				'name'  => 0,
				'value' => 0,
			),
			2  => array(
				'name'  => 2,
				'value' => 2,
			),
			6  => array(
				'name'  => 5,
				'value' => 6,
			),
			10 => array(
				'name'  => 10,
				'value' => 10,
			),
			20 => array(
				'name'  => 20,
				'value' => 20,
			),
			30 => array(
				'name'  => 30,
				'value' => 30,
			),
		),
		'default'     => 30,
		'priority'    => 70,
		'requires'    => array(
			array(
				'key'     => 'shop_view',
				'compare' => 'not_equals',
				'value'   => 'list',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'shop_pagination',
		'name'        => esc_html__( 'Products pagination', 'woodmart' ),
		'description' => esc_html__( 'Choose a type for the pagination on your shop page.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'products_grid',
		'options'     => array(
			'pagination' => array(
				'name'  => esc_html__( 'Pagination', 'woodmart' ),
				'value' => 'pagination',
			),
			'more-btn'   => array(
				'name'  => esc_html__( '"Load more" button', 'woodmart' ),
				'value' => 'more-btn',
			),
			'infinit'    => array(
				'name'  => esc_html__( 'Infinit scrolling', 'woodmart' ),
				'value' => 'infinit',
			),
		),
		'default'     => 'pagination',
		'priority'    => 80,
	)
);

Options::add_field(
	array(
		'id'          => 'per_row_columns_selector',
		'name'        => esc_html__( 'Number of columns selector', 'woodmart' ),
		'description' => esc_html__( 'Allow customers to change number of columns per row', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'products_grid',
		'default'     => '1',
		'priority'    => 90,
		'requires'    => array(
			array(
				'key'     => 'shop_view',
				'compare' => 'not_equals',
				'value'   => 'list',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'products_columns_variations',
		'name'        => esc_html__( 'Available products columns variations', 'woodmart' ),
		'description' => esc_html__( 'What columns users may select to be displayed on the product page', 'woodmart' ),
		'type'        => 'select',
		'multiple'    => true,
		'select2'     => true,
		'section'     => 'products_grid',
		'options'     => array(
			2 => array(
				'name'  => 2,
				'value' => 2,
			),
			3 => array(
				'name'  => 3,
				'value' => 3,
			),
			4 => array(
				'name'  => 4,
				'value' => 4,
			),
			5 => array(
				'name'  => 5,
				'value' => 5,
			),
			6 => array(
				'name'  => 6,
				'value' => 6,
			),
		),
		'default'     => array( 2, 3, 4 ),
		'priority'    => 100,
		'requires'    => array(
			array(
				'key'     => 'per_row_columns_selector',
				'compare' => 'equals',
				'value'   => '1',
			),
		),
	)
);

Options::add_section(
	array(
		'id'       => 'products_styles',
		'parent'   => 'shop_section',
		'name'     => esc_html__( 'Products styles', 'woodmart' ),
		'priority' => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'products_masonry',
		'name'        => esc_html__( 'Masonry grid', 'woodmart' ),
		'description' => esc_html__( 'Useful if your products have different height.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'products_styles',
		'default'     => false,
		'priority'    => 10,
		'requires'    => array(
			array(
				'key'     => 'shop_view',
				'compare' => 'not_equals',
				'value'   => 'list',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'products_different_sizes',
		'name'        => esc_html__( 'Products grid with different sizes', 'woodmart' ),
		'description' => esc_html__( 'In this situation, some of the products will be twice bigger in width than others. Recommended to use with 6 columns grid only.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'products_styles',
		'default'     => false,
		'priority'    => 20,
		'requires'    => array(
			array(
				'key'     => 'shop_view',
				'compare' => 'not_equals',
				'value'   => 'list',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'products_hover',
		'name'        => esc_html__( 'Hover on product', 'woodmart' ),
		'description' => esc_html__( 'Choose one of those hover effects for products', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'products_styles',
		'default'     => 'base',
		'options'     => array(
			'info-alt' => array(
				'name'  => esc_html__( 'Full info on hover', 'woodmart' ),
				'value' => 'info-alt',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/hover/info-alt.jpg',
			),
			'info'     => array(
				'name'  => esc_html__( 'Full info on image', 'woodmart' ),
				'value' => 'info',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/hover/info.jpg',
			),
			'alt'      => array(
				'name'  => esc_html__( 'Icons and "add to cart" on hover', 'woodmart' ),
				'value' => 'alt',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/hover/alt.jpg',
			),
			'icons'    => array(
				'name'  => esc_html__( 'Icons on hover', 'woodmart' ),
				'value' => 'icons',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/hover/icons.jpg',
			),
			'quick'    => array(
				'name'  => esc_html__( 'Quick', 'woodmart' ),
				'value' => 'quick',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/hover/quick.jpg',
			),
			'button'   => array(
				'name'  => esc_html__( 'Show button on hover on image', 'woodmart' ),
				'value' => 'button',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/hover/button.jpg',
			),
			'base'     => array(
				'name'  => esc_html__( 'Show summary on hover', 'woodmart' ),
				'value' => 'base',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/hover/base.jpg',
			),
			'standard' => array(
				'name'  => esc_html__( 'Standard button', 'woodmart' ),
				'value' => 'standard',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/hover/standard.jpg',
			),
			'tiled'    => array(
				'name'  => esc_html__( 'Tiled', 'woodmart' ),
				'value' => 'tiled',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/hover/tiled.jpg',
			),
		),
		'priority'    => 30,
		'requires'    => array(
			array(
				'key'     => 'shop_view',
				'compare' => 'not_equals',
				'value'   => 'list',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'base_hover_mobile_click',
		'name'        => esc_html__( 'Open product on click on mobile', 'woodmart' ),
		'description' => esc_html__( 'If you disable this option, when user click on the product on mobile devices, it will see its description text and add to cart button. The product page will be opened on second click.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'products_styles',
		'default'     => false,
		'priority'    => 40,
		'requires'    => array(
			array(
				'key'     => 'products_hover',
				'compare' => 'equals',
				'value'   => 'base',
			),
		),
	)
);

Options::add_field(
	array(
		'id'       => 'product_quantity',
		'name'     => esc_html__( 'Quantity input on product', 'woodmart' ),
		'type'     => 'switcher',
		'section'  => 'products_styles',
		'default'  => false,
		'priority' => 41,
		'requires' => array(
			array(
				'key'     => 'products_hover',
				'compare' => 'equals',
				'value'   => array( 'standard', 'quick' ),
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'products_bordered_grid',
		'name'        => esc_html__( 'Bordered grid', 'woodmart' ),
		'description' => esc_html__( 'Add borders between the products in your grid', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'products_styles',
		'default'     => false,
		'priority'    => 50,
	)
);

Options::add_field(
	array(
		'id'          => 'hover_image',
		'name'        => esc_html__( 'Hover image', 'woodmart' ),
		'description' => esc_html__( 'Disable - Enable hover image for products on the shop page.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'products_styles',
		'default'     => '1',
		'priority'    => 60,
	)
);

Options::add_field(
	array(
		'id'       => 'base_hover_content',
		'name'     => esc_html__( 'Hover content', 'woodmart' ),
		'type'     => 'buttons',
		'section'  => 'products_styles',
		'options'  => array(
			'excerpt'         => array(
				'name'  => esc_html__( 'Excerpt', 'woodmart' ),
				'value' => 'excerpt',
			),
			'additional_info' => array(
				'name'  => esc_html__( 'Additional information', 'woodmart' ),
				'value' => 'additional_info',
			),
			'none'            => array(
				'name'  => esc_html__( 'None', 'woodmart' ),
				'value' => 'none',
			),
		),
		'default'  => 'excerpt',
		'priority' => 70,
		'requires' => array(
			array(
				'key'     => 'products_hover',
				'compare' => 'equals',
				'value'   => 'base',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'grid_stock_progress_bar',
		'name'        => esc_html__( 'Stock progress bar', 'woodmart' ),
		'description' => esc_html__( 'Display a number of sold and in stock products as a progress bar.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'products_styles',
		'default'     => false,
		'priority'    => 80,
	)
);

Options::add_field(
	array(
		'id'          => 'shop_countdown',
		'name'        => esc_html__( 'Countdown timer', 'woodmart' ),
		'description' => esc_html__( 'Show timer for products that have scheduled date for the sale price', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'products_styles',
		'default'     => false,
		'priority'    => 90,
	)
);

Options::add_field(
	array(
		'id'       => 'categories_under_title',
		'name'     => esc_html__( 'Show product category next to title', 'woodmart' ),
		'type'     => 'switcher',
		'section'  => 'products_styles',
		'default'  => false,
		'priority' => 100,
	)
);

Options::add_field(
	array(
		'id'       => 'brands_under_title',
		'name'     => esc_html__( 'Show product brands next to title', 'woodmart' ),
		'type'     => 'switcher',
		'section'  => 'products_styles',
		'default'  => false,
		'priority' => 110,
	)
);


Options::add_field(
	array(
		'id'       => 'product_title_lines_limit',
		'name'     => esc_html__( 'Product title lines limit', 'woodmart' ),
		'type'     => 'buttons',
		'section'  => 'products_styles',
		'options'  => array(
			'one'  => array(
				'name'  => esc_html__( 'One line', 'woodmart' ),
				'value' => 'one',
			),
			'two'  => array(
				'name'  => esc_html__( 'Two line', 'woodmart' ),
				'value' => 'one',
			),
			'none' => array(
				'name'  => esc_html__( 'None', 'woodmart' ),
				'value' => 'none',
			),
		),
		'default'  => 'none',
		'priority' => 120,
	)
);

Options::add_section(
	array(
		'id'       => 'categories_styles',
		'parent'   => 'shop_section',
		'name'     => esc_html__( 'Сategories styles', 'woodmart' ),
		'priority' => 30,
	)
);

Options::add_field(
	array(
		'id'          => 'categories_design',
		'name'        => esc_html__( 'Categories design', 'woodmart' ),
		'description' => esc_html__( 'Choose one of those designs for categories', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'categories_styles',
		'default'     => 'default',
		'options'     => array(
			'default'       => array(
				'name'  => esc_html__( 'Default', 'woodmart' ),
				'value' => 'default',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/categories/default.jpg',
			),
			'alt'           => array(
				'name'  => esc_html__( 'Alternative', 'woodmart' ),
				'value' => 'alt',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/categories/alt.jpg',
			),
			'center'        => array(
				'name'  => esc_html__( 'Center title', 'woodmart' ),
				'value' => 'center',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/categories/center.jpg',
			),
			'replace-title' => array(
				'name'  => esc_html__( 'Replace title', 'woodmart' ),
				'value' => 'replace-title',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/categories/replace-title.jpg',
			),
		),
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'       => 'categories_with_shadow',
		'name'     => esc_html__( 'Categories with shadow', 'woodmart' ),
		'type'     => 'buttons',
		'section'  => 'categories_styles',
		'options'  => array(
			'enable'  => array(
				'name'  => esc_html__( 'Enable', 'woodmart' ),
				'value' => 'enable',
			),
			'disable' => array(
				'name'  => esc_html__( 'Disable', 'woodmart' ),
				'value' => 'disable',
			),
		),
		'default'  => 'enable',
		'priority' => 20,
		'requires' => array(
			array(
				'key'     => 'categories_design',
				'compare' => 'equals',
				'value'   => array( 'alt', 'default' ),
			),
		),
	)
);

Options::add_field(
	array(
		'id'       => 'hide_categories_product_count',
		'name'     => esc_html__( 'Hide product count on category', 'woodmart' ),
		'type'     => 'switcher',
		'section'  => 'categories_styles',
		'default'  => false,
		'priority' => 30,
	)
);

Options::add_section(
	array(
		'id'       => 'shop_sidebar',
		'parent'   => 'shop_section',
		'name'     => esc_html__( 'Sidebar & Page title', 'woodmart' ),
		'priority' => 40,
	)
);

Options::add_field(
	array(
		'id'          => 'shop_layout',
		'name'        => esc_html__( 'Shop Layout', 'woodmart' ),
		'description' => esc_html__( 'Select main content and sidebar alignment for shop pages.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'shop_sidebar',
		'options'     => array(
			'full-width'    => array(
				'name'  => esc_html__( '1 Column', 'woodmart' ),
				'value' => 'full-width',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/sidebar-layout/none.png',
			),
			'sidebar-left'  => array(
				'name'  => esc_html__( '2 Column Left', 'woodmart' ),
				'value' => 'sidebar-left',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/sidebar-layout/left.png',
			),
			'sidebar-right' => array(
				'name'  => esc_html__( '2 Column Right', 'woodmart' ),
				'value' => 'sidebar-right',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/sidebar-layout/right.png',
			),
		),
		'priority'    => 10,
		'default'     => 'sidebar-left',
	)
);

Options::add_field(
	array(
		'id'          => 'shop_sidebar_width',
		'name'        => esc_html__( 'Sidebar size', 'woodmart' ),
		'description' => esc_html__( 'You can set different sizes for your shop pages sidebar', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'shop_sidebar',
		'options'     => array(
			2 => array(
				'name'  => esc_html__( 'Small', 'woodmart' ),
				'value' => 2,
			),
			3 => array(
				'name'  => esc_html__( 'Medium', 'woodmart' ),
				'value' => 3,
			),
			4 => array(
				'name'  => esc_html__( 'Large', 'woodmart' ),
				'value' => 4,
			),
		),
		'priority'    => 20,
		'default'     => 3,
		'requires'    => array(
			array(
				'key'     => 'shop_layout',
				'compare' => 'not_equals',
				'value'   => 'full-width',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'shop_hide_sidebar',
		'name'        => esc_html__( 'Off canvas sidebar for mobile', 'woodmart' ),
		'description' => esc_html__( 'You can can hide sidebar and show nicely on button click on the shop page.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'shop_sidebar',
		'default'     => '1',
		'priority'    => 30,
		'requires'    => array(
			array(
				'key'     => 'shop_layout',
				'compare' => 'not_equals',
				'value'   => 'full-width',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'shop_hide_sidebar_tablet',
		'name'        => esc_html__( 'Off canvas sidebar for tablet', 'woodmart' ),
		'description' => esc_html__( 'You can can hide sidebar and show nicely on button click on the shop page.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'shop_sidebar',
		'default'     => '1',
		'priority'    => 40,
		'requires'    => array(
			array(
				'key'     => 'shop_layout',
				'compare' => 'not_equals',
				'value'   => 'full-width',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'shop_hide_sidebar_desktop',
		'name'        => esc_html__( 'Off canvas sidebar for desktop', 'woodmart' ),
		'description' => esc_html__( 'You can can hide sidebar and show nicely on button click on the shop page.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'shop_sidebar',
		'default'     => false,
		'priority'    => 50,
		'requires'    => array(
			array(
				'key'     => 'shop_layout',
				'compare' => 'not_equals',
				'value'   => 'full-width',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'sticky_filter_button',
		'name'        => esc_html__( 'Sticky off canvas sidebar button', 'woodmart' ),
		'description' => esc_html__( 'Display the filters button fixed on the screen for mobile and tablet devices.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'shop_sidebar',
		'default'     => false,
		'priority'    => 51,
	)
);

Options::add_field(
	array(
		'id'          => 'shop_title',
		'name'        => esc_html__( 'Shop title', 'woodmart' ),
		'description' => esc_html__( 'Show title for shop page, product categories or tags.', 'woodmart' ),
		'group'       => esc_html__( 'Shop page title options', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'shop_sidebar',
		'default'     => '1',
		'priority'    => 60,
	)
);

Options::add_field(
	array(
		'id'          => 'shop_categories',
		'name'        => esc_html__( 'Categories in page title', 'woodmart' ),
		'description' => esc_html__( 'This categories menu is generated automatically based on all categories in the shop. You are not able to manage this menu as other WordPress menus.', 'woodmart' ),
		'group'       => esc_html__( 'Shop page title options', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'shop_sidebar',
		'default'     => '1',
		'priority'    => 70,
	)
);

Options::add_field(
	array(
		'id'          => 'shop_categories_ancestors',
		'name'        => esc_html__( 'Show current category ancestors', 'woodmart' ),
		'description' => esc_html__( 'If you visit category Man, for example, only man\'s subcategories will be shown in the page title like T-shirts, Coats, Shoes etc.', 'woodmart' ),
		'group'       => esc_html__( 'Shop page title options', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'shop_sidebar',
		'default'     => false,
		'priority'    => 80,
		'requires'    => array(
			array(
				'key'     => 'shop_categories',
				'compare' => 'equals',
				'value'   => true,
			),
		),
	)
);


Options::add_field(
	array(
		'id'          => 'show_categories_neighbors',
		'name'        => esc_html__( 'Show category neighbors if there is no children', 'woodmart' ),
		'description' => esc_html__( 'If the category you visit doesn\'t contain any subcategories, the page title menu will display this category\'s neighbors categories.', 'woodmart' ),
		'group'       => esc_html__( 'Shop page title options', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'shop_sidebar',
		'default'     => false,
		'priority'    => 90,
		'requires'    => array(
			array(
				'key'     => 'shop_categories_ancestors',
				'compare' => 'equals',
				'value'   => true,
			),
		),
	)
);

Options::add_field(
	array(
		'id'       => 'shop_products_count',
		'name'     => esc_html__( 'Show products count for each category', 'woodmart' ),
		'group'    => esc_html__( 'Shop page title options', 'woodmart' ),
		'type'     => 'switcher',
		'section'  => 'shop_sidebar',
		'default'  => '1',
		'priority' => 100,
		'requires' => array(
			array(
				'key'     => 'shop_categories',
				'compare' => 'equals',
				'value'   => true,
			),
		),
	)
);

Options::add_field(
	array(
		'id'       => 'shop_page_title_hide_empty_categories',
		'name'     => esc_html__( 'Hіde empty categories', 'woodmart' ),
		'type'     => 'switcher',
		'section'  => 'shop_sidebar',
		'default'  => false,
		'requires' => array(
			array(
				'key'     => 'shop_categories',
				'compare' => 'equals',
				'value'   => true,
			),
		),
		'priority' => 100,
	)
);

Options::add_field(
	array(
		'id'           => 'shop_page_title_categories_exclude',
		'type'         => 'select',
		'section'      => 'shop_sidebar',
		'name'         => esc_html__( 'Exclude categories', 'woodmart' ),
		'select2'      => true,
		'empty_option' => true,
		'multiple'     => true,
		'requires'     => array(
			array(
				'key'     => 'shop_categories',
				'compare' => 'equals',
				'value'   => true,
			),
			array(
				'key'     => 'shop_categories_ancestors',
				'compare' => 'not_equals',
				'value'   => true,
			),
		),
		'autocomplete' => array(
			'type'   => 'term',
			'value'  => 'product_cat',
			'search' => 'woodmart_get_taxonomies_by_query_autocomplete',
			'render' => 'woodmart_get_taxonomies_by_ids_autocomplete',
		),
		'priority'     => 110,
	)
);

Options::add_section(
	array(
		'id'       => 'attribute_swatches',
		'parent'   => 'shop_section',
		'name'     => esc_html__( 'Attribute swatches', 'woodmart' ),
		'priority' => 50,
	)
);

Options::add_field(
	array(
		'id'           => 'grid_swatches_attribute',
		'name'         => esc_html__( 'Grid swatch attribute to display', 'woodmart' ),
		'description'  => esc_html__( 'Choose attribute that will be shown on products grid', 'woodmart' ),
		'type'         => 'select',
		'section'      => 'attribute_swatches',
		'options'      => woodmart_product_attributes_array(),
		'priority'     => 10,
		'empty_option' => true,
	)
);

Options::add_field(
	array(
		'id'          => 'swatches_use_variation_images',
		'name'        => esc_html__( 'Use images from product variations', 'woodmart' ),
		'description' => esc_html__( 'If enabled swatches buttons will be filled with images choosed for product variations and not with images uploaded to attribute terms.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'attribute_swatches',
		'default'     => false,
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'       => 'swatches_labels_name',
		'name'     => esc_html__( 'Stick selected option name on desktop and tablet', 'woodmart' ),
		'type'     => 'switcher',
		'section'  => 'attribute_swatches',
		'default'  => false,
		'priority' => 30,
	)
);

Options::add_field(
	array(
		'id'       => 'swatches_limit',
		'name'     => esc_html__( 'Limit swatches on grid', 'woodmart' ),
		'type'     => 'switcher',
		'section'  => 'attribute_swatches',
		'default'  => false,
		'priority' => 40,
	)
);

Options::add_field(
	array(
		'id'       => 'swatches_limit_count',
		'name'     => esc_html__( 'Number of visible swatches', 'woodmart' ),
		'type'     => 'range',
		'section'  => 'attribute_swatches',
		'default'  => 5,
		'min'      => 1,
		'step'     => 1,
		'max'      => 20,
		'priority' => 50,
		'requires' => array(
			array(
				'key'     => 'swatches_limit',
				'compare' => 'equals',
				'value'   => true,
			),
		),
	)
);

Options::add_section(
	array(
		'id'       => 'brands_section',
		'parent'   => 'shop_section',
		'name'     => esc_html__( 'Brands', 'woodmart' ),
		'priority' => 60,
	)
);

Options::add_field(
	array(
		'id'           => 'brands_attribute',
		'name'         => esc_html__( 'Brand attribute', 'woodmart' ),
		'description'  => esc_html__( 'If you want to show brand image on your product page select desired attribute here', 'woodmart' ),
		'type'         => 'select',
		'section'      => 'brands_section',
		'options'      => woodmart_product_attributes_array(),
		'priority'     => 10,
		'default'      => 'pa_brand',
		'empty_option' => true,
	)
);

Options::add_field(
	array(
		'id'          => 'product_page_brand',
		'name'        => esc_html__( 'Show brand on the single product page', 'woodmart' ),
		'description' => esc_html__( 'You can disable/enable product\'s brand image on the single page.', 'woodmart' ),
		'group'       => esc_html__( 'Brand on the single product page', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'brands_section',
		'default'     => '1',
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'product_brand_location',
		'name'        => esc_html__( 'Brand position on the product page', 'woodmart' ),
		'description' => esc_html__( 'Select a position of the brand image on the single product page.', 'woodmart' ),
		'group'       => esc_html__( 'Brand on the single product page', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'brands_section',
		'options'     => array(
			'about_title' => array(
				'name'  => esc_html__( 'Above product title', 'woodmart' ),
				'value' => 'about_title',
			),
			'sidebar'     => array(
				'name'  => esc_html__( 'Sidebar', 'woodmart' ),
				'value' => 'sidebar',
			),
		),
		'priority'    => 30,
		'default'     => 'about_title',
	)
);

Options::add_field(
	array(
		'id'          => 'brand_tab',
		'name'        => esc_html__( 'Show tab with brand information', 'woodmart' ),
		'description' => esc_html__( 'If enabled you will see additional tab with brand description on the single product page. Text will be taken from "Description" field for each brand (attribute term).', 'woodmart' ),
		'group'       => esc_html__( 'Brand on the single product page', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'brands_section',
		'default'     => '1',
		'priority'    => 40,
	)
);

Options::add_field(
	array(
		'id'          => 'brand_tab_name',
		'name'        => esc_html__( 'Use brand name for tab title', 'woodmart' ),
		'description' => esc_html__( 'If you enable this option, the tab with brand\'s information will be called like "About Nike".', 'woodmart' ),
		'group'       => esc_html__( 'Brand on the single product page', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'brands_section',
		'default'     => false,
		'priority'    => 50,
	)
);

Options::add_section(
	array(
		'id'       => 'quick_view_section',
		'parent'   => 'shop_section',
		'name'     => esc_html__( 'Quick view', 'woodmart' ),
		'priority' => 70,
	)
);

Options::add_field(
	array(
		'id'          => 'quick_view',
		'name'        => esc_html__( 'Quick view', 'woodmart' ),
		'description' => esc_html__( 'Enable Quick view option. Ability to see the product information with AJAX.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'quick_view_section',
		'default'     => '1',
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'quick_view_variable',
		'name'        => esc_html__( 'Show variations on quick view', 'woodmart' ),
		'description' => esc_html__( 'Enable Quick view option for variable products. Will allow your users to purchase variable products directly from the quick view.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'quick_view_section',
		'default'     => '1',
		'priority'    => 20,
		'requires'    => array(
			array(
				'key'     => 'quick_view',
				'compare' => 'equals',
				'value'   => '1',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'quick_view_layout',
		'name'        => esc_html__( 'Quick view layout', 'woodmart' ),
		'description' => esc_html__( 'Choose between horizontal and vertical layouts for the quick view window.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'quick_view_section',
		'default'     => 'horizontal',
		'options'     => array(
			'horizontal' => array(
				'name'  => esc_html__( 'Horizontal', 'woodmart' ),
				'value' => 'horizontal',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/quick-view-layout/horizontal.jpg',
			),
			'vertical'   => array(
				'name'  => esc_html__( 'Vertical', 'woodmart' ),
				'value' => 'vertical',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/quick-view-layout/vertical.jpg',
			),
		),
		'priority'    => 30,
		'requires'    => array(
			array(
				'key'     => 'quick_view',
				'compare' => 'equals',
				'value'   => true,
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'quickview_width',
		'name'        => esc_html__( 'Quick view width', 'woodmart' ),
		'description' => esc_html__( 'Set width of the quick view in pixels.', 'woodmart' ),
		'type'        => 'range',
		'section'     => 'quick_view_section',
		'default'     => 920,
		'min'         => 400,
		'step'        => 10,
		'max'         => 1200,
		'priority'    => 40,
		'requires'    => array(
			array(
				'key'     => 'quick_view',
				'compare' => 'equals',
				'value'   => true,
			),
		),
	)
);

Options::add_section(
	array(
		'id'       => 'compare',
		'parent'   => 'shop_section',
		'name'     => esc_html__( 'Compare', 'woodmart' ),
		'priority' => 80,
	)
);

Options::add_field(
	array(
		'id'          => 'compare',
		'name'        => esc_html__( 'Enable compare', 'woodmart' ),
		'description' => esc_html__( 'Enable compare functionality built in with the theme. Read more information in our documentation.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'compare',
		'default'     => '1',
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'           => 'compare_page',
		'name'         => esc_html__( 'Compare page', 'woodmart' ),
		'description'  => esc_html__( 'Select a page for compare table. It should contain the shortcode shortcode: [woodmart_compare]', 'woodmart' ),
		'type'         => 'select',
		'section'      => 'compare',
		'options'      => woodmart_get_pages_array(),
		'empty_option' => true,
		'default'      => 265,
		'priority'     => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'compare_on_grid',
		'name'        => esc_html__( 'Show button on product grid', 'woodmart' ),
		'description' => esc_html__( 'Display compare product button on all products grids and lists.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'compare',
		'default'     => '1',
		'priority'    => 30,
	)
);

Options::add_field(
	array(
		'id'          => 'fields_compare',
		'name'        => esc_html__( 'Select fields for compare table', 'woodmart' ),
		'description' => esc_html__( 'Choose which fields should be presented on the product compare page with table.', 'woodmart' ),
		'type'        => 'select',
		'multiple'    => true,
		'select2'     => true,
		'section'     => 'compare',
		'options'     => woodmart_compare_available_fields( true ),
		'default'     => array(
			'description',
			'sku',
			'availability',
		),
		'priority'    => 40,
	)
);

Options::add_field(
	array(
		'id'          => 'empty_compare_text',
		'name'        => esc_html__( 'Empty compare text', 'woodmart' ),
		'description' => esc_html__( 'Text will be displayed if user don\'t add any products to compare', 'woodmart' ),
		'default'     => 'No products added in the compare list. You must add some products to compare them.<br> You will find a lot of interesting products on our "Shop" page.',
		'type'        => 'textarea',
		'wysiwyg'     => false,
		'section'     => 'compare',
		'priority'    => 50,
	)
);

Options::add_section(
	array(
		'id'       => 'catalog_mode',
		'parent'   => 'shop_section',
		'name'     => esc_html__( 'Catalog mode', 'woodmart' ),
		'priority' => 90,
	)
);

Options::add_field(
	array(
		'id'          => 'catalog_mode',
		'name'        => esc_html__( 'Enable catalog mode', 'woodmart' ),
		'description' => esc_html__( 'You can hide all "Add to cart" buttons, cart widget, cart and checkout pages. This will allow you to showcase your products as an online catalog without ability to make a purchase.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'catalog_mode',
		'default'     => false,
		'priority'    => 10,
	)
);

Options::add_section(
	array(
		'id'       => 'login_prices',
		'parent'   => 'shop_section',
		'name'     => esc_html__( 'Login to see prices', 'woodmart' ),
		'priority' => 100,
	)
);

Options::add_field(
	array(
		'id'          => 'login_prices',
		'name'        => esc_html__( 'Login to see add to cart and prices', 'woodmart' ),
		'description' => esc_html__( 'You can restrict shopping functions only for logged in customers.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'login_prices',
		'default'     => false,
		'priority'    => 10,
	)
);

Options::add_section(
	array(
		'id'       => 'cookie',
		'parent'   => 'shop_section',
		'name'     => esc_html__( 'Cookie Law Info', 'woodmart' ),
		'priority' => 110,
	)
);

Options::add_field(
	array(
		'id'          => 'cookies_info',
		'name'        => esc_html__( 'Show cookies info', 'woodmart' ),
		'description' => esc_html__( 'Under EU privacy regulations, websites must make it clear to visitors what information about them is being stored. This specifically includes cookies. Turn on this option and user will see info box at the bottom of the page that your web-site is using cookies.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'cookie',
		'default'     => false,
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'cookies_text',
		'name'        => esc_html__( 'Popup text', 'woodmart' ),
		'description' => esc_html__( 'Place here some information about cookies usage that will be shown in the popup.', 'woodmart' ),
		'type'        => 'textarea',
		'wysiwyg'     => true,
		'section'     => 'cookie',
		'default'     => esc_html__( 'We use cookies to improve your experience on our website. By browsing this website, you agree to our use of cookies.', 'woodmart' ),
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'           => 'cookies_policy_page',
		'name'         => esc_html__( 'Page with details', 'woodmart' ),
		'description'  => esc_html__( 'Choose page that will contain detailed information about your Privacy Policy', 'woodmart' ),
		'type'         => 'select',
		'section'      => 'cookie',
		'options'      => woodmart_get_pages_array(),
		'empty_option' => true,
		'priority'     => 30,
	)
);

Options::add_field(
	array(
		'id'           => 'cookies_version',
		'name'         => esc_html__( 'Cookies version', 'woodmart' ),
		'description'  => esc_html__( 'If you change your cookie policy information you can increase their version to show the popup to all visitors again.', 'woodmart' ),
		'type'         => 'text_input',
		'section'      => 'cookie',
		'empty_option' => true,
		'default'      => 1,
		'priority'     => 40,
	)
);

Options::add_section(
	array(
		'id'       => 'promo_popup_section',
		'parent'   => 'shop_section',
		'name'     => esc_html__( 'Promo popup', 'woodmart' ),
		'priority' => 120,
	)
);

Options::add_field(
	array(
		'id'          => 'promo_popup',
		'name'        => esc_html__( 'Enable promo popup', 'woodmart' ),
		'description' => esc_html__( 'Show promo popup to users when they enter the site.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'promo_popup_section',
		'default'     => false,
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'popup_text',
		'name'        => esc_html__( 'Promo popup text', 'woodmart' ),
		'description' => esc_html__( 'Place here some promo text or use HTML block and place here it\'s shortcode', 'woodmart' ),
		'type'        => 'textarea',
		'wysiwyg'     => true,
		'section'     => 'promo_popup_section',
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'       => 'popup_event',
		'name'     => esc_html__( 'Show popup after', 'woodmart' ),
		'type'     => 'buttons',
		'section'  => 'promo_popup_section',
		'default'  => 'time',
		'options'  => array(
			'time'   => array(
				'name'  => esc_html__( 'some time', 'woodmart' ),
				'value' => 'time',
			),
			'scroll' => array(
				'name'  => esc_html__( 'user scroll', 'woodmart' ),
				'value' => 'scroll',
			),
		),
		'priority' => 30,
	)
);


Options::add_field(
	array(
		'id'           => 'promo_timeout',
		'name'         => esc_html__( 'Popup delay', 'woodmart' ),
		'description'  => esc_html__( 'Show popup after some time (in milliseconds)', 'woodmart' ),
		'type'         => 'text_input',
		'section'      => 'promo_popup_section',
		'empty_option' => true,
		'default'      => '2000',
		'priority'     => 40,
		'requires'     => array(
			array(
				'key'     => 'popup_event',
				'compare' => 'equals',
				'value'   => 'time',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'popup_scroll',
		'name'        => esc_html__( 'Show after user scroll down the page', 'woodmart' ),
		'description' => esc_html__( 'Set the number of pixels users have to scroll down before popup opens', 'woodmart' ),
		'type'        => 'range',
		'section'     => 'promo_popup_section',
		'default'     => 1000,
		'min'         => 100,
		'step'        => 50,
		'max'         => 5000,
		'priority'    => 50,
		'requires'    => array(
			array(
				'key'     => 'popup_event',
				'compare' => 'equals',
				'value'   => 'scroll',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'promo_version',
		'name'        => esc_html__( 'Popup version', 'woodmart' ),
		'description' => esc_html__( 'If you change your promo popup you can increase its version to show the popup to all visitors again.', 'woodmart' ),
		'type'        => 'text_input',
		'section'     => 'promo_popup_section',
		'default'     => 1,
		'priority'    => 60,
	)
);

Options::add_field(
	array(
		'id'          => 'popup_pages',
		'name'        => esc_html__( 'Show after number of pages visited', 'woodmart' ),
		'description' => esc_html__( 'You can choose how much pages user should change before popup will be shown.', 'woodmart' ),
		'type'        => 'range',
		'section'     => 'promo_popup_section',
		'default'     => 0,
		'min'         => 0,
		'step'        => 1,
		'max'         => 10,
		'priority'    => 70,
	)
);


Options::add_field(
	array(
		'id'          => 'popup-background',
		'name'        => esc_html__( 'Popup background', 'woodmart' ),
		'description' => esc_html__( 'Set background image or color for promo popup', 'woodmart' ),
		'type'        => 'background',
		'default'     => array(
			'color'    => '#111111',
			'repeat'   => 'no-repeat',
			'size'     => 'contain',
			'position' => 'left center',
		),
		'section'     => 'promo_popup_section',
		'selector'    => '.wd-popup.wd-promo-popup',
		'priority'    => 80,
	)
);

Options::add_field(
	array(
		'id'          => 'popup_width',
		'name'        => esc_html__( 'Popup width', 'woodmart' ),
		'description' => esc_html__( 'Set width of the promo popup in pixels.', 'woodmart' ),
		'type'        => 'range',
		'section'     => 'promo_popup_section',
		'default'     => 800,
		'min'         => 400,
		'step'        => 10,
		'max'         => 1000,
		'priority'    => 90,
	)
);

Options::add_field(
	array(
		'id'          => 'promo_popup_hide_mobile',
		'name'        => esc_html__( 'Hide for mobile devices', 'woodmart' ),
		'description' => esc_html__( 'You can disable this option for mobile devices completely.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'promo_popup_section',
		'default'     => '1',
		'priority'    => 100,
	)
);

Options::add_section(
	array(
		'id'       => 'header_banner',
		'parent'   => 'shop_section',
		'name'     => esc_html__( 'Header banner', 'woodmart' ),
		'priority' => 130,
	)
);

Options::add_field(
	array(
		'id'          => 'header_banner',
		'name'        => esc_html__( 'Header banner', 'woodmart' ),
		'description' => esc_html__( 'Header banner above the header', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'header_banner',
		'default'     => false,
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'header_banner_link',
		'name'        => esc_html__( 'Banner link', 'woodmart' ),
		'description' => esc_html__( 'The link will be added to the whole banner area.', 'woodmart' ),
		'type'        => 'text_input',
		'section'     => 'header_banner',
		'tags'        => 'header banner text link',
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'header_banner_shortcode',
		'name'        => esc_html__( 'Banner content', 'woodmart' ),
		'description' => esc_html__( 'Place here shortcodes you want to see in the banner above the header. You can use shortcodes. Ex.: [social_buttons] or place an HTML Block built with WPBakery Page Builder builder there like [html_block id="258"]', 'woodmart' ),
		'type'        => 'textarea',
		'wysiwyg'     => true,
		'section'     => 'header_banner',
		'tags'        => 'header banner text content',
		'priority'    => 30,
	)
);

Options::add_field(
	array(
		'id'          => 'header_banner_height',
		'name'        => esc_html__( 'Banner height for desktop', 'woodmart' ),
		'description' => esc_html__( 'The height for the banner area in pixels on desktop devices.', 'woodmart' ),
		'type'        => 'range',
		'section'     => 'header_banner',
		'default'     => 40,
		'min'         => 0,
		'step'        => 1,
		'max'         => 200,
		'priority'    => 40,
	)
);

Options::add_field(
	array(
		'id'          => 'header_banner_mobile_height',
		'name'        => esc_html__( 'Banner height for mobile', 'woodmart' ),
		'description' => esc_html__( 'The height for the banner area in pixels on mobile devices.', 'woodmart' ),
		'type'        => 'range',
		'section'     => 'header_banner',
		'default'     => 40,
		'min'         => 0,
		'step'        => 1,
		'max'         => 200,
		'priority'    => 50,
	)
);

Options::add_field(
	array(
		'id'          => 'header_banner_color',
		'name'        => esc_html__( 'Banner text color', 'woodmart' ),
		'description' => esc_html__( 'Set light or dark text color scheme depending on the banner\'s background color.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'header_banner',
		'default'     => 'light',
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
		'priority'    => 60,
	)
);

Options::add_field(
	array(
		'id'       => 'header_banner_bg',
		'name'     => esc_html__( 'Banner background', 'woodmart' ),
		'type'     => 'background',
		'section'  => 'header_banner',
		'selector' => '.header-banner',
		'priority' => 70,
	)
);

Options::add_field(
	array(
		'id'          => 'header_close_btn',
		'name'        => esc_html__( 'Close button', 'woodmart' ),
		'description' => esc_html__( 'Show close banner button', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'header_banner',
		'default'     => '1',
		'tags'        => 'header banner color background',
		'priority'    => 80,
	)
);

Options::add_field(
	array(
		'id'          => 'header_banner_version',
		'name'        => esc_html__( 'Banner version', 'woodmart' ),
		'description' => esc_html__( 'If you change your banner you can increase their version to show the banner to all visitors again.', 'woodmart' ),
		'type'        => 'text_input',
		'section'     => 'header_banner',
		'default'     => '1',
		'priority'    => 90,
		'requires'    => array(
			array(
				'key'     => 'header_close_btn',
				'compare' => 'equals',
				'value'   => true,
			),
		),
	)
);

Options::add_section(
	array(
		'id'       => 'widgets_section',
		'parent'   => 'shop_section',
		'name'     => esc_html__( 'Widgets', 'woodmart' ),
		'priority' => 140,
	)
);

Options::add_field(
	array(
		'id'          => 'categories_toggle',
		'name'        => esc_html__( 'Toggle function for categories widget', 'woodmart' ),
		'description' => esc_html__( 'Turn it on to enable accordion JS for the WooCommerce Product Categories widget. Useful if you have a lot of categories and subcategories.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'widgets_section',
		'default'     => '1',
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'widgets_scroll',
		'name'        => esc_html__( 'Scroll for filters widgets', 'woodmart' ),
		'description' => esc_html__( 'You can limit your Layered Navigation widgets by height and enable nice scroll for them. Useful if you have a lot of product colors/sizes or other attributes for filters.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'widgets_section',
		'default'     => '1',
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'widget_heights',
		'name'        => esc_html__( 'Height for filters widgets', 'woodmart' ),
		'description' => esc_html__( 'Set widgets height in pixels.', 'woodmart' ),
		'type'        => 'range',
		'section'     => 'widgets_section',
		'default'     => 280,
		'min'         => 100,
		'step'        => 1,
		'max'         => 800,
		'priority'    => 30,
		'requires'    => array(
			array(
				'key'     => 'widgets_scroll',
				'compare' => 'equals',
				'value'   => true,
			),
		),
	)
);

Options::add_section(
	array(
		'id'       => 'product_labels',
		'parent'   => 'shop_section',
		'name'     => esc_html__( 'Product labels', 'woodmart' ),
		'priority' => 150,
	)
);

Options::add_field(
	array(
		'id'       => 'label_shape',
		'name'     => esc_html__( 'Label shape', 'woodmart' ),
		'type'     => 'buttons',
		'section'  => 'product_labels',
		'default'  => 'rounded',
		'options'  => array(
			'rounded'     => array(
				'name'  => esc_html__( 'Rounded', 'woodmart' ),
				'value' => 'rounded',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/product-label/rounded.jpg',
			),
			'rectangular' => array(
				'name'  => esc_html__( 'Rectangular', 'woodmart' ),
				'value' => 'rectangular',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/product-label/rectangular.jpg',
			),
		),
		'priority' => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'percentage_label',
		'name'        => esc_html__( 'Shop sale label in percentage', 'woodmart' ),
		'description' => esc_html__( 'Works with Simple, Variable and External products only.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product_labels',
		'default'     => '1',
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'new_label',
		'name'        => esc_html__( '"New" label on products', 'woodmart' ),
		'description' => esc_html__( 'This label is displayed for products if you enabled this option for particular items.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product_labels',
		'default'     => '1',
		'priority'    => 30,
	)
);

Options::add_field(
	array(
		'id'          => 'new_label_days_after_create',
		'name'        => esc_html__( 'Automatic "New" label period', 'woodmart' ),
		'description' => esc_html__( 'Set a number of days to keep your products marked as "New" after creation.', 'woodmart' ),
		'type'        => 'range',
		'section'     => 'product_labels',
		'default'     => 0,
		'min'         => 0,
		'max'         => 100,
		'step'        => 1,
		'priority'    => 31,
	)
);

Options::add_field(
	array(
		'id'          => 'hot_label',
		'name'        => esc_html__( '"Hot" label on products', 'woodmart' ),
		'description' => esc_html__( 'Your products marked as "Featured" will have a badge with "Hot" label.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product_labels',
		'default'     => '1',
		'priority'    => 40,
	)
);

Options::add_section(
	array(
		'id'       => 'thank_you_page_section',
		'parent'   => 'shop_section',
		'name'     => esc_html__( 'Thank you page', 'woodmart' ),
		'priority' => 160,
	)
);

Options::add_field(
	array(
		'id'          => 'thank_you_page_extra_content',
		'name'        => esc_html__( 'Extra content for "Thank you page"', 'woodmart' ),
		'description' => esc_html__( 'Add any extra content to the order received page', 'woodmart' ),
		'type'        => 'textarea',
		'wysiwyg'     => true,
		'section'     => 'thank_you_page_section',
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'thank_you_page_default_content',
		'name'        => esc_html__( 'Default "Thank you page" content', 'woodmart' ),
		'description' => esc_html__( 'If you use custom extra content you can disable default WooCommerce order details on the thank you page', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'thank_you_page_section',
		'default'     => '1',
		'priority'    => 20,
	)
);
