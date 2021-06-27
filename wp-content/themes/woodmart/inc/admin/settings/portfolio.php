<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}
use XTS\Options;

/**
 * Portfolio
 */
Options::add_section(
	array(
		'id'       => 'portfolio',
		'name'     => esc_html__( 'Portfolio', 'woodmart' ),
		'priority' => 80,
		'icon'     => 'dashicons dashicons-grid-view',
	)
);

Options::add_field(
	array(
		'id'          => 'disable_portfolio',
		'name'        => esc_html__( 'Disable portfolio', 'woodmart' ),
		'description' => esc_html__( 'You can disable the custom post type for portfolio projects completely.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'portfolio',
		'default'     => false,
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'portfolio_item_slug',
		'name'        => esc_html__( 'Portfolio project URL slug', 'woodmart' ),
		'description' => esc_html__( 'IMPORTANT: You need to go to WordPress Settings -> Pemalinks and resave them to apply these settings.', 'woodmart' ),
		'type'        => 'text_input',
		'section'     => 'portfolio',
		'priority'    => 11,
	)
);

Options::add_field(
	array(
		'id'          => 'portfolio_cat_slug',
		'name'        => esc_html__( 'Portfolio category URL slug', 'woodmart' ),
		'description' => esc_html__( 'IMPORTANT: You need to go to WordPress Settings -> Pemalinks and resave them to apply these settings.', 'woodmart' ),
		'type'        => 'text_input',
		'section'     => 'portfolio',
		'priority'    => 12,
	)
);

Options::add_field(
	array(
		'id'          => 'portoflio_style',
		'name'        => esc_html__( 'Portfolio Style', 'woodmart' ),
		'description' => esc_html__( 'You can use different styles for your projects.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'portfolio',
		'options'     => array(
			'hover'         => array(
				'name'  => esc_html__( 'Show text on mouse over', 'woodmart' ),
				'value' => 'hover',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/portfolio/hover.jpg',
			),
			'hover-inverse' => array(
				'name'  => esc_html__( 'Alternative', 'woodmart' ),
				'value' => 'hover-inverse',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/portfolio/hover-inverse.jpg',
			),
			'text-shown'    => array(
				'name'  => esc_html__( 'Text under image', 'woodmart' ),
				'value' => 'text-shown',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/portfolio/text-shown.jpg',
			),
			'parallax'      => array(
				'name'  => esc_html__( 'Mouse move parallax', 'woodmart' ),
				'value' => 'parallax',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/portfolio/hover.jpg',
			),
		),
		'default'     => 'hover',
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'portoflio_image_size',
		'name'        => esc_html__( 'Images size', 'woodmart' ),
		'description' => esc_html__( 'Enter image size. Example: \'thumbnail\', \'medium\', \'large\', \'full\' or other sizes defined by current theme.', 'woodmart' ),
		'type'        => 'text_input',
		'section'     => 'portfolio',
		'default'     => 'large',
		'priority'    => 21,
	)
);

Options::add_field(
	array(
		'id'          => 'portoflio_filters',
		'name'        => esc_html__( 'Show categories filters', 'woodmart' ),
		'description' => esc_html__( 'Display categories list that allows you to filter your portfolio projects with animation on click.', 'woodmart' ),
		'group'       => esc_html__( 'Project options', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'portfolio',
		'default'     => '1',
		'priority'    => 30,
	)
);

Options::add_field(
	array(
		'id'          => 'portfolio_filters_type',
		'type'        => 'buttons',
		'name'        => esc_html__( 'Categories filters', 'woodmart' ),
		'description' => esc_html__( 'You can switch between links that will lead to project categories and masonry filters within one page only. Or turn off the filters completely.', 'woodmart' ),
		'group'       => esc_html__( 'Project options', 'woodmart' ),
		'section'     => 'portfolio',
		'options'     => array(
			'links'   => array(
				'name'  => esc_html__( 'Links', 'woodmart' ),
				'value' => 'links',
			),
			'masonry' => array(
				'name'  => esc_html__( 'Masonry', 'woodmart' ),
				'value' => 'masonry',
			),
		),
		'requires'    => array(
			array(
				'key'     => 'portoflio_filters',
				'compare' => 'equals',
				'value'   => '1',
			),
		),
		'default'     => 'links',
		'priority'    => 31,
	)
);

Options::add_field(
	array(
		'id'          => 'ajax_portfolio',
		'type'        => 'switcher',
		'name'        => esc_html__( 'AJAX portfolio', 'woodmart' ),
		'description' => esc_html__( 'Use AJAX functionality for portfolio categories links.', 'woodmart' ),
		'group'       => esc_html__( 'Project options', 'woodmart' ),
		'section'     => 'portfolio',
		'requires'    => array(
			array(
				'key'     => 'portfolio_filters_type',
				'compare' => 'equals',
				'value'   => 'links',
			),
		),
		'default'     => '1',
		'priority'    => 32,
	)
);

Options::add_field(
	array(
		'id'          => 'portfolio_full_width',
		'name'        => esc_html__( 'Full Width portfolio', 'woodmart' ),
		'description' => esc_html__( 'Makes container 100% width of the page', 'woodmart' ),
		'group'       => esc_html__( 'Project options', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'portfolio',
		'default'     => false,
		'priority'    => 40,
	)
);

Options::add_field(
	array(
		'id'          => 'projects_columns',
		'name'        => esc_html__( 'Projects columns', 'woodmart' ),
		'description' => esc_html__( 'How many projects you want to show per row', 'woodmart' ),
		'group'       => esc_html__( 'Project options', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'portfolio',
		'options'     => array(
			2 => array(
				'name'  => '2',
				'value' => 2,
			),
			3 => array(
				'name'  => '3',
				'value' => 3,
			),
			4 => array(
				'name'  => '4',
				'value' => 4,
			),
			5 => array(
				'name'  => '5',
				'value' => 5,
			),
			6 => array(
				'name'  => '6',
				'value' => 6,
			),
		),
		'default'     => 3,
		'priority'    => 50,
	)
);

Options::add_field(
	array(
		'id'          => 'portfolio_spacing',
		'name'        => esc_html__( 'Space between projects', 'woodmart' ),
		'description' => esc_html__( 'You can set different spacing between blocks on portfolio page', 'woodmart' ),
		'group'       => esc_html__( 'Project options', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'portfolio',
		'options'     => array(
			0  => array(
				'name'  => '0',
				'value' => 0,
			),
			2  => array(
				'name'  => '2',
				'value' => 2,
			),
			6  => array(
				'name'  => '5',
				'value' => 6,
			),
			10 => array(
				'name'  => '10',
				'value' => 10,
			),
			20 => array(
				'name'  => '20',
				'value' => 20,
			),
			30 => array(
				'name'  => '30',
				'value' => 30,
			),
		),
		'default'     => 30,
		'priority'    => 60,
	)
);

Options::add_field(
	array(
		'id'          => 'portoflio_per_page',
		'name'        => esc_html__( 'Items per page', 'woodmart' ),
		'description' => esc_html__( 'Number of portfolio projects that will be displayed on one page.', 'woodmart' ),
		'group'       => esc_html__( 'Project options', 'woodmart' ),
		'type'        => 'text_input',
		'section'     => 'portfolio',
		'default'     => 12,
		'priority'    => 70,
	)
);

Options::add_field(
	array(
		'id'          => 'portfolio_pagination',
		'name'        => esc_html__( 'Portfolio pagination', 'woodmart' ),
		'description' => esc_html__( 'Choose a type for the pagination on your portfolio page.', 'woodmart' ),
		'group'       => esc_html__( 'Project options', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'portfolio',
		'options'     => array(
			'pagination' => array(
				'name'  => esc_html__( 'Pagination links', 'woodmart' ),
				'value' => 'pagination',
			),
			'load_more'  => array(
				'name'  => esc_html__( '"Load more" button', 'woodmart' ),
				'value' => 'load_more',
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
		'id'          => 'portoflio_orderby',
		'name'        => esc_html__( 'Portfolio order by', 'woodmart' ),
		'description' => esc_html__( 'Select a parameter for projects order.', 'woodmart' ),
		'group'       => esc_html__( 'Project options', 'woodmart' ),
		'type'        => 'select',
		'section'     => 'portfolio',
		'options'     => array(
			'date'       => array(
				'name'  => esc_html__( 'Date', 'woodmart' ),
				'value' => 'date',
			),
			'ID'         => array(
				'name'  => esc_html__( 'ID', 'woodmart' ),
				'value' => 'ID',
			),
			'title'      => array(
				'name'  => esc_html__( 'Title', 'woodmart' ),
				'value' => 'title',
			),
			'modified'   => array(
				'name'  => esc_html__( 'Modified', 'woodmart' ),
				'value' => 'modified',
			),
			'menu_order' => array(
				'name'  => esc_html__( 'Menu order', 'woodmart' ),
				'value' => 'menu_order',
			),
		),
		'default'     => 'date',
		'priority'    => 90,
	)
);

Options::add_field(
	array(
		'id'          => 'portoflio_order',
		'name'        => esc_html__( 'Portfolio order', 'woodmart' ),
		'description' => esc_html__( 'Choose ascending or descending order.', 'woodmart' ),
		'group'       => esc_html__( 'Project options', 'woodmart' ),
		'type'        => 'select',
		'section'     => 'portfolio',
		'options'     => array(
			'DESC' => array(
				'name'  => esc_html__( 'DESC', 'woodmart' ),
				'value' => 'DESC',
			),
			'ASC'  => array(
				'name'  => esc_html__( 'ASC', 'woodmart' ),
				'value' => 'ASC',
			),
		),
		'default'     => 'DESC',
		'priority'    => 100,
	)
);

Options::add_field(
	array(
		'id'          => 'portfolio_navigation',
		'name'        => esc_html__( 'Projects navigation', 'woodmart' ),
		'description' => esc_html__( 'Next and previous projects links on single project page', 'woodmart' ),
		'group'       => esc_html__( 'Single project options', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'portfolio',
		'default'     => '1',
		'priority'    => 110,
	)
);

Options::add_field(
	array(
		'id'          => 'portfolio_related',
		'name'        => esc_html__( 'Related Projects', 'woodmart' ),
		'description' => esc_html__( 'Show related projects carousel.', 'woodmart' ),
		'group'       => esc_html__( 'Single project options', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'portfolio',
		'default'     => '1',
		'priority'    => 120,
	)
);

Options::add_field(
	array(
		'id'          => 'single_portfolio_title_in_page_title',
		'name'        => esc_html__( 'Project title in page heading', 'woodmart' ),
		'description' => esc_html__( 'Display project title instead of portfolio page title in page heading', 'woodmart' ),
		'group'       => esc_html__( 'Single project options', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'portfolio',
		'default'     => false,
		'priority'    => 130,
	)
);

Options::add_field(
	array(
		'id'          => 'single_portfolio_header',
		'name'        => esc_html__( 'Single portfolio header', 'woodmart' ),
		'description' => esc_html__( 'You can use different header for your single portfolio page.', 'woodmart' ),
		'group'       => esc_html__( 'Single project options', 'woodmart' ),
		'type'        => 'select',
		'section'     => 'portfolio',
		'options'     => woodmart_get_whb_headers_array( true, true ),
		'default'     => 'none',
		'priority'    => 140,
	)
);