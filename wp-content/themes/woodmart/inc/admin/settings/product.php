<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}
use XTS\Options;

/**
 * Product page
 */
Options::add_section(
	array(
		'id'       => 'product',
		'name'     => esc_html__( 'Product page', 'woodmart' ),
		'priority' => 100,
		'icon'     => 'dashicons dashicons-products',
	)
);

Options::add_field(
	array(
		'id'          => 'single_product_layout',
		'name'        => esc_html__( 'Single Product Sidebar', 'woodmart' ),
		'description' => esc_html__( 'Select main content and sidebar alignment for single product pages.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'product',
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
		'default'     => 'full-width',
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'full_height_sidebar',
		'name'        => esc_html__( 'Full height sidebar', 'woodmart' ),
		'description' => esc_html__( 'If you have a lot of widgets added to the sidebar your single product page layout may look incosistent. Try to enable this option in this situation.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product',
		'default'     => false,
		'priority'    => 20,
		'requires'    => array(
			array(
				'key'     => 'single_product_layout',
				'compare' => 'not_equals',
				'value'   => 'full-width',
			),
		),
	)
);


Options::add_field(
	array(
		'id'          => 'single_sidebar_width',
		'name'        => esc_html__( 'Sidebar size', 'woodmart' ),
		'description' => esc_html__( 'You can set different sizes for your single product pages sidebar', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'product',
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
		'default'     => 3,
		'priority'    => 30,
	)
);

Options::add_field(
	array(
		'id'          => 'single_full_width',
		'name'        => esc_html__( 'Full width product page', 'woodmart' ),
		'description' => esc_html__( 'Stretch the single product page\'s content.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product',
		'default'     => false,
		'priority'    => 40,
	)
);



Options::add_field(
	array(
		'id'          => 'single_product_header',
		'name'        => esc_html__( 'Single product header', 'woodmart' ),
		'description' => esc_html__( 'You can use different header for your single product page.', 'woodmart' ),
		'type'        => 'select',
		'section'     => 'product',
		'options'     => woodmart_get_whb_headers_array( true, true ),
		'default'     => 'none',
		'priority'    => 50,
	)
);

Options::add_field(
	array(
		'id'          => 'product_design',
		'name'        => esc_html__( 'Product page design', 'woodmart' ),
		'description' => esc_html__( 'Choose between different predefined designs', 'woodmart' ),
		'group'       => esc_html__( 'Single product page layout and style', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'product',
		'options'     => array(
			'default' => array(
				'name'  => esc_html__( 'Default', 'woodmart' ),
				'value' => 'default',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/product-page/product-page-default.jpg',
			),
			'alt'     => array(
				'name'  => esc_html__( 'Centered', 'woodmart' ),
				'value' => 'default',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/product-page/product-page-alt.jpg',
			),
		),
		'default'     => 'default',
		'priority'    => 60,
	)
);

Options::add_field(
	array(
		'id'          => 'single_product_variations_price',
		'type'        => 'switcher',
		'name'        => esc_html__( 'Remove duplicate price for variable product', 'woodmart' ),
		'description' => esc_html__( 'When you will select any variation, the price on the single product page will be updated with an actual variation price.', 'woodmart' ),
		'group'       => esc_html__( 'Single product page layout and style', 'woodmart' ),
		'section'     => 'product',
		'default'     => '0',
		'priority'    => 61,
	)
);

Options::add_field(
	array(
		'id'          => 'product_sticky',
		'name'        => esc_html__( 'Sticky product', 'woodmart' ),
		'description' => esc_html__( 'If you turn on this option, the section with description will be sticky when you scroll the page. In case when the description is higher than images, the images section will be fixed instead.', 'woodmart' ),
		'group'       => esc_html__( 'Single product page layout and style', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product',
		'default'     => false,
		'priority'    => 70,
	)
);

Options::add_field(
	array(
		'id'          => 'swatches_scroll_top_desktop',
		'name'        => esc_html__( 'Scroll top on variation select [desktop]', 'woodmart' ),
		'description' => esc_html__( 'When you turn on this option and click on some variation with image, the page will be scrolled up to show that variation image in the main product gallery.', 'woodmart' ),
		'group'       => esc_html__( 'Single product page layout and style', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product',
		'default'     => false,
		'priority'    => 80,
	)
);

Options::add_field(
	array(
		'id'          => 'swatches_scroll_top_mobile',
		'name'        => esc_html__( 'Scroll top on variation select [mobile]', 'woodmart' ),
		'description' => esc_html__( 'When you turn on this option and click on some variation with image, the page will be scrolled up to show that variation image in the main product gallery.', 'woodmart' ),
		'group'       => esc_html__( 'Single product page layout and style', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product',
		'default'     => false,
		'priority'    => 90,
	)
);

Options::add_field(
	array(
		'id'          => 'product_summary_shadow',
		'name'        => esc_html__( 'Add shadow to product summary block', 'woodmart' ),
		'description' => esc_html__( 'Useful when you set background color for the single product page to gray for example.', 'woodmart' ),
		'group'       => esc_html__( 'Single product page layout and style', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product',
		'default'     => false,
		'priority'    => 91,
	)
);

Options::add_field(
	array(
		'id'          => 'size_guides',
		'name'        => esc_html__( 'Size guides', 'woodmart' ),
		'group'       => esc_html__( 'Single product page options', 'woodmart' ),
		'description' => wp_kses(
			__( 'Turn on the size guide feature on the website. Read more information about this function in <a href="https://xtemos.com/docs/woodmart/faq-guides/create-size-guide-table/" target="_blank">our documentation</a>.', 'woodmart' ),
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
		'section'     => 'product',
		'default'     => '1',
		'priority'    => 100,
	)
);

Options::add_field(
	array(
		'id'          => 'single_ajax_add_to_cart',
		'name'        => esc_html__( 'AJAX Add to cart', 'woodmart' ),
		'description' => esc_html__( 'Turn on the AJAX add to cart option on the single product page. Will not work with plugins that add some custom fields to the add to cart form.', 'woodmart' ),
		'group'       => esc_html__( 'Single product page options', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product',
		'default'     => '1',
		'priority'    => 110,
	)
);

Options::add_field(
	array(
		'id'          => 'single_sticky_add_to_cart',
		'name'        => esc_html__( 'Sticky add to cart', 'woodmart' ),
		'description' => esc_html__( 'Add to cart section will be displayed at the bottom of your screen when you scroll down the page.', 'woodmart' ),
		'group'       => esc_html__( 'Single product page options', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product',
		'default'     => false,
		'priority'    => 120,
	)
);

Options::add_field(
	array(
		'id'          => 'mobile_single_sticky_add_to_cart',
		'name'        => esc_html__( 'Sticky add to cart on mobile', 'woodmart' ),
		'description' => esc_html__( 'You can leave this option for desktop only or enable it for all devices sizes.', 'woodmart' ),
		'group'       => esc_html__( 'Single product page options', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product',
		'default'     => false,
		'priority'    => 130,
		'requires'    => array(
			array(
				'key'     => 'single_sticky_add_to_cart',
				'compare' => 'equals',
				'value'   => '1',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'ajax_variation_threshold',
		'name'        => esc_html__( 'AJAX variation threshold', 'woodmart' ),
		'description' => esc_html__( 'Increase this value if you noticed a problem with additional variations images function.', 'woodmart' ),
		'group'       => esc_html__( 'Single product page options', 'woodmart' ),
		'type'        => 'range',
		'section'     => 'product',
		'default'     => 30,
		'min'         => 10,
		'max'         => 500,
		'step'        => 1,
		'priority'    => 131,
	)
);


Options::add_field(
	array(
		'id'          => 'content_before_add_to_cart',
		'name'        => esc_html__( 'Before "Add to cart button" text area', 'woodmart' ),
		'description' => esc_html__( 'Place any text, HTML or shortcodes here.', 'woodmart' ),
		'group'       => esc_html__( 'Single product page options', 'woodmart' ),
		'type'        => 'textarea',
		'wysiwyg'     => true,
		'section'     => 'product',
		'priority'    => 140,
	)
);

Options::add_field(
	array(
		'id'          => 'content_after_add_to_cart',
		'name'        => esc_html__( 'After "Add to cart button" text area', 'woodmart' ),
		'description' => esc_html__( 'Place any text, HTML or shortcodes here.', 'woodmart' ),
		'group'       => esc_html__( 'Single product page options', 'woodmart' ),
		'type'        => 'textarea',
		'wysiwyg'     => true,
		'section'     => 'product',
		'priority'    => 150,
	)
);

Options::add_section(
	array(
		'id'       => 'product_images',
		'parent'   => 'product',
		'name'     => esc_html__( 'Images', 'woodmart' ),
		'priority' => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'single_product_style',
		'name'        => esc_html__( 'Product image width', 'woodmart' ),
		'description' => esc_html__( 'You can choose different page layout depending on the product image size you need', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'product_images',
		'options'     => array(
			1 => array(
				'name'  => esc_html__( 'Small image', 'woodmart' ),
				'value' => 1,
				'image' => WOODMART_ASSETS_IMAGES . '/settings/single-product-image-width/small.jpg',
			),
			2 => array(
				'name'  => esc_html__( 'Medium', 'woodmart' ),
				'value' => 2,
				'image' => WOODMART_ASSETS_IMAGES . '/settings/single-product-image-width/medium.jpg',
			),
			3 => array(
				'name'  => esc_html__( 'Large', 'woodmart' ),
				'value' => 3,
				'image' => WOODMART_ASSETS_IMAGES . '/settings/single-product-image-width/large.jpg',
			),
			4 => array(
				'name'  => esc_html__( 'Full width (container)', 'woodmart' ),
				'value' => 4,
				'image' => WOODMART_ASSETS_IMAGES . '/settings/single-product-image-width/fw-container.jpg',
			),
			5 => array(
				'name'  => esc_html__( 'Full width (window)', 'woodmart' ),
				'value' => 5,
				'image' => WOODMART_ASSETS_IMAGES . '/settings/single-product-image-width/fw-window.jpg',
			),
		),
		'default'     => 2,
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'thums_position',
		'name'        => esc_html__( 'Thumbnails position', 'woodmart' ),
		'description' => esc_html__( 'Use vertical or horizontal position for thumbnails', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'product_images',
		'options'     => array(
			'left'            => array(
				'name'  => esc_html__( 'Left (vertical position)', 'woodmart' ),
				'value' => 'left',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/single-product-image/left.jpg',
			),
			'bottom'          => array(
				'name'  => esc_html__( 'Bottom (horizontal carousel)', 'woodmart' ),
				'value' => 'bottom',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/single-product-image/bottom.jpg',
			),
			'bottom_column'   => array(
				'name'  => esc_html__( 'Bottom (1 column)', 'woodmart' ),
				'value' => 'left',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/single-product-image/bottom_column.jpg',
			),
			'bottom_grid'     => array(
				'name'  => esc_html__( 'Bottom (2 columns)', 'woodmart' ),
				'value' => 'left',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/single-product-image/bottom_grid.jpg',
			),
			'bottom_combined' => array(
				'name'  => esc_html__( 'Combined grid', 'woodmart' ),
				'value' => 'bottom_combined',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/single-product-image/bottom_combined.jpg',
			),
			'centered'        => array(
				'name'  => esc_html__( 'Centered', 'woodmart' ),
				'value' => 'centered',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/single-product-image/centered.jpg',
			),
			'without'         => array(
				'name'  => esc_html__( 'Without', 'woodmart' ),
				'value' => 'without',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/single-product-image/without.jpg',
			),
		),
		'default'     => 'bottom',
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'single_product_thumbnails_gallery_image_width',
		'type'        => 'text_input',
		'section'     => 'product_images',
		'name'        => esc_html__( 'Thumbnails image width', 'woodmart' ),
		'description' => esc_html__( 'Example: 350', 'woodmart' ),
		'default'     => 150,
		'priority'    => 21,
	)
);

Options::add_field(
	array(
		'id'          => 'variation_gallery',
		'name'        => esc_html__( 'Additional variations images', 'woodmart' ),
		'description' => esc_html__( 'Add an ability to upload additional images for each variation in variable products.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product_images',
		'default'     => '1',
		'priority'    => 30,
	)
);

Options::add_field(
	array(
		'id'          => 'variation_gallery_storage_method',
		'name'        => esc_html__( 'Data storage method', 'woodmart' ),
		'description' => esc_html__( 'If you have problems with import/export of the additional variations images data, you need to use "Variations products meta" method.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'product_images',
		'options'     => array(
			'old' => array(
				'name'  => esc_html__( 'Parent product meta', 'woodmart' ),
				'value' => 'old',
			),
			'new' => array(
				'name'  => esc_html__( 'Variations products meta', 'woodmart' ),
				'value' => 'new',
			),
		),
		'default'     => 'old',
		'priority'    => 31,
	)
);

Options::add_field(
	array(
		'id'          => 'image_action',
		'name'        => esc_html__( 'Main image click action', 'woodmart' ),
		'description' => esc_html__( 'Enable/disable zoom option or switch to photoswipe popup.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'product_images',
		'options'     => array(
			'zoom'  => array(
				'name'  => esc_html__( 'Zoom', 'woodmart' ),
				'value' => 'zoom',
			),
			'popup' => array(
				'name'  => esc_html__( 'Photoswipe popup', 'woodmart' ),
				'value' => 'popup',
			),
			'none'  => array(
				'name'  => esc_html__( 'None', 'woodmart' ),
				'value' => 'none',
			),
		),
		'default'     => 'zoom',
		'priority'    => 40,
	)
);

Options::add_field(
	array(
		'id'          => 'photoswipe_icon',
		'name'        => esc_html__( 'Show "Zoom image" icon', 'woodmart' ),
		'description' => esc_html__( 'Click to open image in popup and swipe to zoom', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product_images',
		'default'     => '1',
		'priority'    => 50,
	)
);

Options::add_field(
	array(
		'id'          => 'product_slider_auto_height',
		'name'        => esc_html__( 'Main carousel auto height', 'woodmart' ),
		'description' => esc_html__( 'Useful when you have product images with different height.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product_images',
		'default'     => false,
		'priority'    => 60,
	)
);

Options::add_field(
	array(
		'id'          => 'product_images_captions',
		'name'        => esc_html__( 'Images captions on Photo Swipe lightbox', 'woodmart' ),
		'description' => esc_html__( 'Display caption texts below images when you open the photoswipe popup. Captions can be added to your images via the Media library.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product_images',
		'default'     => false,
		'priority'    => 70,
	)
);

Options::add_section(
	array(
		'id'       => 'product_elements',
		'parent'   => 'product',
		'name'     => esc_html__( 'Show / hide elements', 'woodmart' ),
		'priority' => 20,
	)
);

Options::add_field(
	array(
		'id'       => 'product_page_breadcrumbs',
		'name'     => esc_html__( 'Breadcrumbs on product page', 'woodmart' ),
		'type'     => 'switcher',
		'section'  => 'product_elements',
		'default'  => '1',
		'priority' => 9,
	)
);

Options::add_field(
	array(
		'id'          => 'single_breadcrumbs_position',
		'name'        => esc_html__( 'Breadcrumbs position', 'woodmart' ),
		'description' => esc_html__( 'Set different position for breadcrumbs section on your product\'s page.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'product_elements',
		'options'     => array(
			'default'      => array(
				'name'  => esc_html__( 'Default', 'woodmart' ),
				'value' => 'default',
			),
			'below_header' => array(
				'name'  => esc_html__( 'Below header', 'woodmart' ),
				'value' => 'below_header',
			),
			'summary'      => array(
				'name'  => esc_html__( 'Product summary', 'woodmart' ),
				'value' => 'summary',
			),
		),
		'default'     => 'default',
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'products_nav',
		'name'        => esc_html__( 'Products navigation', 'woodmart' ),
		'description' => esc_html__( 'Display next/previous products navigation.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product_elements',
		'default'     => '1',
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'product_short_description',
		'name'        => esc_html__( 'Short description', 'woodmart' ),
		'description' => esc_html__( 'Enable/disable short description text in the product\'s summary block.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product_elements',
		'default'     => '1',
		'priority'    => 30,
	)
);

Options::add_field(
	array(
		'id'          => 'attr_after_short_desc',
		'name'        => esc_html__( 'Show attributes table after short description', 'woodmart' ),
		'description' => esc_html__( 'You can display attributes table instead of short description.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product_elements',
		'default'     => false,
		'priority'    => 40,
	)
);

Options::add_field(
	array(
		'id'          => 'product_show_meta',
		'name'        => esc_html__( 'Show product meta', 'woodmart' ),
		'description' => esc_html__( 'Categories, tags, SKU', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'product_elements',
		'options'     => array(
			'add_to_cart' => array(
				'name'  => esc_html__( 'After "Add to cart" button', 'woodmart' ),
				'value' => 'add_to_cart',
			),
			'after_tabs'  => array(
				'name'  => esc_html__( 'After tabs', 'woodmart' ),
				'value' => 'after_tabs',
			),
			'hide'        => array(
				'name'  => esc_html__( 'Hide', 'woodmart' ),
				'value' => 'hide',
			),
		),
		'default'     => 'add_to_cart',
		'priority'    => 50,
	)
);

Options::add_field(
	array(
		'id'          => 'single_stock_progress_bar',
		'name'        => esc_html__( 'Stock progress bar', 'woodmart' ),
		'description' => esc_html__( 'Display a number of sold and in stock products as a progress bar.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product_elements',
		'default'     => false,
		'priority'    => 60,
	)
);

Options::add_field(
	array(
		'id'          => 'product_countdown',
		'name'        => esc_html__( 'Countdown timer', 'woodmart' ),
		'description' => esc_html__( 'Show timer for products that have scheduled date for the sale price', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product_elements',
		'default'     => false,
		'priority'    => 70,
	)
);

Options::add_field(
	array(
		'id'          => 'sale_countdown_variable',
		'name'        => esc_html__( 'Countdown for variable products', 'woodmart' ),
		'description' => esc_html__( 'Sale end date will be based on the first variation date of the product.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product_elements',
		'default'     => false,
		'priority'    => 80,
	)
);

Options::add_field(
	array(
		'id'          => 'upsells_position',
		'name'        => esc_html__( 'Upsells products position', 'woodmart' ),
		'description' => esc_html__( 'If use "Sidebar" be sure that you have enabled it for the product page layout', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'product_elements',
		'options'     => array(
			'standard' => array(
				'name'  => esc_html__( 'Standard', 'woodmart' ),
				'value' => 'standard',
			),
			'sidebar'  => array(
				'name'  => esc_html__( 'Sidebar', 'woodmart' ),
				'value' => 'sidebar',
			),
			'hide'     => array(
				'name'  => esc_html__( 'Hide', 'woodmart' ),
				'value' => 'hide',
			),
		),
		'default'     => 'standard',
		'priority'    => 90,
	)
);

Options::add_field(
	array(
		'id'          => 'related_products',
		'name'        => esc_html__( 'Show related products', 'woodmart' ),
		'description' => esc_html__( 'Related Products is a section that pulls products from your store that share the same tags or categories as the current product.', 'woodmart' ),
		'group'       => esc_html__( 'Related products options', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product_elements',
		'default'     => '1',
		'priority'    => 100,
	)
);

Options::add_field(
	array(
		'id'          => 'related_product_view',
		'name'        => esc_html__( 'Related product view', 'woodmart' ),
		'description' => esc_html__( 'You can set different view mode for the related products. These settings will be applied for upsells products as well.', 'woodmart' ),
		'group'       => esc_html__( 'Related products options', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'product_elements',
		'options'     => array(
			'grid'   => array(
				'name'  => esc_html__( 'Grid', 'woodmart' ),
				'value' => 'grid',
			),
			'slider' => array(
				'name'  => esc_html__( 'Slider', 'woodmart' ),
				'value' => 'slider',
			),
		),
		'default'     => 'slider',
		'priority'    => 110,
	)
);

Options::add_field(
	array(
		'id'          => 'related_product_count',
		'name'        => esc_html__( 'Related product count', 'woodmart' ),
		'description' => esc_html__( 'The total number of related products to display.', 'woodmart' ),
		'group'       => esc_html__( 'Related products options', 'woodmart' ),
		'type'        => 'text_input',
		'section'     => 'product_elements',
		'default'     => 8,
		'priority'    => 120,
	)
);

Options::add_field(
	array(
		'id'          => 'related_product_columns',
		'name'        => esc_html__( 'Related product columns', 'woodmart' ),
		'description' => esc_html__( 'How many products you want to show per row.', 'woodmart' ),
		'group'       => esc_html__( 'Related products options', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'product_elements',
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
		'default'     => 4,
		'priority'    => 130,
	)
);

Options::add_section(
	array(
		'id'       => 'product_share',
		'parent'   => 'product',
		'name'     => esc_html__( 'Share buttons', 'woodmart' ),
		'priority' => 30,
	)
);

Options::add_field(
	array(
		'id'          => 'product_share',
		'name'        => esc_html__( 'Show share buttons', 'woodmart' ),
		'description' => esc_html__( 'Display share buttons icons on the single product page.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product_share',
		'default'     => '1',
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'product_share_type',
		'name'        => esc_html__( 'Share buttons type', 'woodmart' ),
		'description' => esc_html__( 'You can switch between share and follow buttons on the single product page.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'product_share',
		'options'     => array(
			'share'  => array(
				'name'  => esc_html__( 'Share', 'woodmart' ),
				'value' => 'share',
			),
			'follow' => array(
				'name'  => esc_html__( 'Follow', 'woodmart' ),
				'value' => 'follow',
			),
		),
		'default'     => 'share',
		'priority'    => 20,
	)
);

Options::add_section(
	array(
		'id'       => 'product_tabs',
		'parent'   => 'product',
		'name'     => esc_html__( 'Tabs', 'woodmart' ),
		'priority' => 40,
	)
);

Options::add_field(
	array(
		'id'          => 'product_tabs_layout',
		'name'        => esc_html__( 'Tabs layout', 'woodmart' ),
		'description' => esc_html__( 'Select which style for products tabs do you need.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'product_tabs',
		'options'     => array(
			'tabs'      => array(
				'name'  => esc_html__( 'Tabs', 'woodmart' ),
				'value' => 'tabs',
			),
			'accordion' => array(
				'name'  => esc_html__( 'Accordion', 'woodmart' ),
				'value' => 'accordion',
			),
		),
		'default'     => 'tabs',
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'product_tabs_location',
		'name'        => esc_html__( 'Tabs location', 'woodmart' ),
		'description' => esc_html__( 'Select which style for products tabs do you need.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'product_tabs',
		'options'     => array(
			'standard' => array(
				'name'  => esc_html__( 'Standard', 'woodmart' ),
				'value' => 'standard',
			),
			'summary'  => array(
				'name'  => esc_html__( 'After "Add to cart" button', 'woodmart' ),
				'value' => 'summary',
			),
		),
		'default'     => 'standard',
		'priority'    => 20,
		'requires'    => array(
			array(
				'key'     => 'product_tabs_layout',
				'compare' => 'equals',
				'value'   => 'accordion',
			),
		),
	)
);

Options::add_field(
	array(
		'id'          => 'reviews_location',
		'name'        => esc_html__( 'Reviews location', 'woodmart' ),
		'description' => esc_html__( 'Option for the location of the reviews form and reviews list section.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'product_tabs',
		'options'     => array(
			'tabs'     => array(
				'name'  => esc_html__( 'Tabs', 'woodmart' ),
				'value' => 'tabs',
			),
			'separate' => array(
				'name'  => esc_html__( 'Separate section', 'woodmart' ),
				'value' => 'separate',
			),
		),
		'default'     => 'tabs',
		'priority'    => 30,
	)
);

Options::add_field(
	array(
		'id'          => 'hide_tabs_titles',
		'name'        => esc_html__( 'Hide tabs headings', 'woodmart' ),
		'description' => esc_html__( 'Don\'t show duplicated titles for product tabs.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'product_tabs',
		'default'     => '1',
		'priority'    => 40,
	)
);

Options::add_field(
	array(
		'id'          => 'additional_tab_title',
		'name'        => esc_html__( 'Additional tab title', 'woodmart' ),
		'description' => esc_html__( 'Leave empty to disable custom tab', 'woodmart' ),
		'type'        => 'text_input',
		'default'     => 'Shipping & Delivery',
		'section'     => 'product_tabs',
		'priority'    => 50,
	)
);

Options::add_field(
	array(
		'id'          => 'additional_tab_text',
		'name'        => esc_html__( 'Additional tab content', 'woodmart' ),
		'description' => esc_html__( 'You can use any text, HTML or shortcodes here.', 'woodmart' ),
		'type'        => 'textarea',
		'wysiwyg'     => false,
		'default'     => '',
		'section'     => 'product_tabs',
		'priority'    => 60,
	)
);

