<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}
use XTS\Options;

/**
 * Blog
 */
Options::add_section(
	array(
		'id'       => 'blog',
		'name'     => esc_html__( 'Blog', 'woodmart' ),
		'priority' => 70,
		'icon'     => 'dashicons dashicons-welcome-write-blog',
	)
);

Options::add_field(
	array(
		'id'          => 'blog_layout',
		'name'        => esc_html__( 'Blog Layout', 'woodmart' ),
		'description' => esc_html__( 'Select main content and sidebar alignment for blog pages.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'blog',
		'options'     => array(
			'full-width'    => array(
				'name'  => esc_html__( '1 Column', 'woodmart' ),
				'value' => 'full-width',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/sidebar-layout/none.png',
			),
			'sidebar-left'  => array(
				'name'  => esc_html__( '2 Columns Left', 'woodmart' ),
				'value' => 'sidebar-left',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/sidebar-layout/left.png',
			),
			'sidebar-right' => array(
				'name'  => esc_html__( '2 Columns Right', 'woodmart' ),
				'value' => 'sidebar-right',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/sidebar-layout/right.png',
			),
		),
		'default'     => 'sidebar-right',
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'blog_sidebar_width',
		'name'        => esc_html__( 'Blog Sidebar size', 'woodmart' ),
		'description' => esc_html__( 'You can set different sizes for your blog pages sidebar', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'blog',
		'options'     => array(
			2 => array(
				'name'  => esc_html__( 'Small', 'woodmart' ),
				'value' => 2,
			),
			3 => array(
				'name'  => esc_html__( 'Medium', 'woodmart' ),
				'value' => 2,
			),
			4 => array(
				'name'  => esc_html__( 'Large', 'woodmart' ),
				'value' => 2,
			),
		),
		'default'     => 3,
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'blog_design',
		'name'        => esc_html__( 'Blog Design', 'woodmart' ),
		'description' => esc_html__( 'You can use different design for your blog styled for the theme.', 'woodmart' ),
		'type'        => 'select',
		'section'     => 'blog',
		'options'     => array(
			'default'      => array(
				'name'  => esc_html__( 'Default', 'woodmart' ),
				'value' => 'default',
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
				'value' => 'masonry',
			),
			'mask'         => array(
				'name'  => esc_html__( 'Mask on image', 'woodmart' ),
				'value' => 'mask',
			),
		),
		'default'     => 'masonry',
		'priority'    => 30,
	)
);

Options::add_field(
	array(
		'id'          => 'blog_columns',
		'name'        => esc_html__( 'Blog items columns', 'woodmart' ),
		'description' => esc_html__( 'For masonry grid design', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'blog',
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
		),
		'default'     => 3,
		'priority'    => 40,
		'requires'    => array(
			array(
				'key'     => 'blog_design',
				'compare' => 'equals',
				'value'   => array( 'masonry', 'mask' ),
			),
		),
	)
);


Options::add_field(
	array(
		'id'          => 'blog_spacing',
		'name'        => esc_html__( 'Space between posts', 'woodmart' ),
		'description' => esc_html__( 'You can set different spacing between posts on blog page', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'blog',
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
		'default'     => 20,
		'priority'    => 41,
	)
);

Options::add_field(
	array(
		'id'          => 'blog_style',
		'name'        => esc_html__( 'Blog Style', 'woodmart' ),
		'description' => esc_html__( 'You can use flat style or add a shadow to your blog posts.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'blog',
		'options'     => array(
			'flat'   => array(
				'name'  => esc_html__( 'Flat', 'woodmart' ),
				'value' => 'flat',
			),
			'shadow' => array(
				'name'  => esc_html__( 'With shadow', 'woodmart' ),
				'value' => 'shadow',
			),
		),
		'default'     => 'shadow',
		'priority'    => 50,
	)
);

Options::add_field(
	array(
		'id'          => 'blog_excerpt',
		'name'        => esc_html__( 'Posts excerpt', 'woodmart' ),
		'description' => esc_html__( 'If you will set this option to "Excerpt" then you are able to set custom excerpt for each post or it will be cutted from the post content. If you choose "Full content" then all content will be shown, or you can also add "Read more button" while editing the post and by doing this cut your excerpt length as you need.', 'woodmart' ),
		'group'       => esc_html__( 'Blog post options', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'blog',
		'options'     => array(
			'excerpt' => array(
				'name'  => esc_html__( 'Excerpt', 'woodmart' ),
				'value' => 'excerpt',
			),
			'full'    => array(
				'name'  => esc_html__( 'Full content', 'woodmart' ),
				'value' => 'full',
			),
		),
		'default'     => 'full',
		'priority'    => 60,
	)
);

Options::add_field(
	array(
		'id'          => 'blog_words_or_letters',
		'name'        => esc_html__( 'Excerpt length by words or letters', 'woodmart' ),
		'description' => esc_html__( 'Limit your excerpt text for posts by words or letters.', 'woodmart' ),
		'group'       => esc_html__( 'Blog post options', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'blog',
		'options'     => array(
			'word'   => array(
				'name'  => esc_html__( 'Word', 'woodmart' ),
				'value' => 'word',
			),
			'letter' => array(
				'name'  => esc_html__( 'Letters', 'woodmart' ),
				'value' => 'letter',
			),
		),
		'requires'    => array(
			array(
				'key'     => 'blog_excerpt',
				'compare' => 'equals',
				'value'   => array( 'excerpt' ),
			),
		),
		'default'     => 'letter',
		'priority'    => 70,
	)
);

Options::add_field(
	array(
		'id'          => 'blog_excerpt_length',
		'name'        => esc_html__( 'Excerpt length', 'woodmart' ),
		'description' => esc_html__( 'Number of words or letters that will be displayed for each post if you use "Excerpt" mode and don\'t set custom excerpt for each post.', 'woodmart' ),
		'group'       => esc_html__( 'Blog post options', 'woodmart' ),
		'type'        => 'text_input',
		'section'     => 'blog',
		'requires'    => array(
			array(
				'key'     => 'blog_excerpt',
				'compare' => 'equals',
				'value'   => array( 'excerpt' ),
			),
		),
		'default'     => 135,
		'priority'    => 80,
	)
);

Options::add_field(
	array(
		'id'          => 'blog_pagination',
		'name'        => esc_html__( 'Blog pagination', 'woodmart' ),
		'description' => esc_html__( 'Choose a type for the pagination on your blog page.', 'woodmart' ),
		'group'       => esc_html__( 'Blog post options', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'blog',
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
		'priority'    => 90,
	)
);

Options::add_field(
	array(
		'id'          => 'single_post_design',
		'name'        => esc_html__( 'Single post design', 'woodmart' ),
		'description' => esc_html__( 'You can use different design for your single post page.', 'woodmart' ),
		'group'       => esc_html__( 'Single blog post options', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'blog',
		'options'     => array(
			'default'     => array(
				'name'  => esc_html__( 'Default', 'woodmart' ),
				'value' => 'default',
			),
			'large_image' => array(
				'name'  => esc_html__( 'Large image', 'woodmart' ),
				'value' => 'large_image',
			),
		),
		'default'     => 'default',
		'priority'    => 100,
	)
);

Options::add_field(
	array(
		'id'          => 'single_post_header',
		'name'        => esc_html__( 'Single post header', 'woodmart' ),
		'description' => esc_html__( 'You can use different header for your single post page.', 'woodmart' ),
		'group'       => esc_html__( 'Single blog post options', 'woodmart' ),
		'type'        => 'select',
		'section'     => 'blog',
		'options'     => woodmart_get_whb_headers_array( true, true ),
		'default'     => 'none',
		'priority'    => 110,
	)
);

Options::add_field(
	array(
		'id'          => 'single_post_justified_gallery',
		'name'        => esc_html__( 'Justify gallery', 'woodmart' ),
		'description' => esc_html__( 'This option will replace standard WordPress gallery with "Justified gallery" JS library. Note that you will need to enable its styles in our CSS generator as well.', 'woodmart' ),
		'group'       => esc_html__( 'Single blog post options', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'blog',
		'default'     => '0',
		'priority'    => 111,
	)
);

Options::add_field(
	array(
		'id'          => 'blog_share',
		'name'        => esc_html__( 'Share buttons', 'woodmart' ),
		'description' => esc_html__( 'Display share icons on single post page', 'woodmart' ),
		'group'       => esc_html__( 'Single blog post options', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'blog',
		'default'     => '1',
		'priority'    => 120,
	)
);

Options::add_field(
	array(
		'id'          => 'blog_navigation',
		'name'        => esc_html__( 'Posts navigation', 'woodmart' ),
		'description' => esc_html__( 'Next and previous posts links on single post page', 'woodmart' ),
		'group'       => esc_html__( 'Single blog post options', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'blog',
		'default'     => '1',
		'priority'    => 130,
	)
);

Options::add_field(
	array(
		'id'          => 'blog_author_bio',
		'name'        => esc_html__( 'Author bio', 'woodmart' ),
		'description' => esc_html__( 'Display information about the post author', 'woodmart' ),
		'group'       => esc_html__( 'Single blog post options', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'blog',
		'default'     => '1',
		'priority'    => 140,
	)
);

Options::add_field(
	array(
		'id'          => 'blog_related_posts',
		'name'        => esc_html__( 'Related posts', 'woodmart' ),
		'description' => esc_html__( 'Show related posts on single post page( by tags )', 'woodmart' ),
		'group'       => esc_html__( 'Single blog post options', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'blog',
		'default'     => '1',
		'priority'    => 150,
	)
);
