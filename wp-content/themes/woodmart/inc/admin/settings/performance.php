<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

use XTS\Options;

/**
 * Performance.
 */
Options::add_section(
	array(
		'id'       => 'performance',
		'name'     => esc_html__( 'Performance', 'woodmart' ),
		'priority' => 150,
		'icon'     => 'dashicons dashicons-performance',
	)
);

Options::add_field(
	array(
		'id'          => 'mobile_optimization',
		'name'        => esc_html__( 'Mobile DOM optimization (experimental)', 'woodmart' ),
		'description' => esc_html__( 'You can reduce the number of DOM elements on mobile devices. This option currently removes all HTML tags from the desktop header version on mobile devices.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'performance',
		'default'     => false,
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'old_elements_classes',
		'name'        => esc_html__( 'Deprecated CSS classes from v5.x', 'woodmart' ),
		'description' => esc_html__( 'Enable this option only if you have a lot of custom CSS written for 5.x version. If you don\'t have any custom CSS at all then just disable this option.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'performance',
		'default'     => true,
		'priority'    => 20,
	)
);

/**
 * CSS.
 */
Options::add_section(
	array(
		'id'       => 'performance_css',
		'name'     => esc_html__( 'CSS', 'woodmart' ),
		'parent'   => 'performance',
		'priority' => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'combined_css',
		'name'        => esc_html__( 'Combine CSS files', 'woodmart' ),
		'description' => esc_html__( 'Load one CSS file that contains all theme styles. Not recommended if you want to reduce your page weight.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'performance_css',
		'default'     => false,
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'minified_css',
		'name'        => esc_html__( 'Include minified CSS', 'woodmart' ),
		'description' => esc_html__( 'Minified versions of CSS files will be loaded. Works well with all caching and optimizations plugins.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'performance_css',
		'default'     => '1',
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'light_bootstrap_version',
		'name'        => esc_html__( 'Light bootstrap version', 'woodmart' ),
		'description' => esc_html__( 'Clean bootstrap CSS from code that are not used by the theme.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'performance_css',
		'default'     => false,
		'priority'    => 30,
	)
);

Options::add_field(
	array(
		'id'          => 'disable_gutenberg_css',
		'name'        => esc_html__( 'Disable Gutenberg styles', 'woodmart' ),
		'description' => esc_html__( 'If you are not using Gutenberg elements you will not need these files to be loaded.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'performance_css',
		'default'     => false,
		'priority'    => 40,
	)
);

$config_styles  = woodmart_get_config( 'css-files' );
$styles_options = array();
foreach ( $config_styles as $key => $styles ) {
	foreach ( $styles as $style ) {
		if ( isset( $styles_options[ $style['name'] ] ) ) {
			continue;
		}

		$styles_options[ $key ] = array(
			'name'  => $style['title'],
			'value' => $key,
		);
	}
}

asort( $styles_options );

Options::add_field(
	array(
		'id'          => 'styles_always_use',
		'name'        => esc_html__( 'Styles always load', 'woodmart' ),
		'description' => esc_html__( 'You can manually load some styles on all pages.', 'woodmart' ),
		'section'     => 'performance_css',
		'type'        => 'select',
		'multiple'    => true,
		'select2'     => true,
		'options'     => $styles_options,
		'default'     => array(),
		'priority'    => 50,
	)
);

/**
 * JS
 */
Options::add_section(
	array(
		'id'       => 'performance_js',
		'name'     => esc_html__( 'JS', 'woodmart' ),
		'parent'   => 'performance',
		'priority' => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'combined_js',
		'name'        => esc_html__( 'Combine JS files', 'woodmart' ),
		'description' => esc_html__( 'Load one JS file that contains all theme scripts and library initializations.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'performance_js',
		'default'     => false,
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'minified_js',
		'name'        => esc_html__( 'Include minified JS', 'woodmart' ),
		'description' => esc_html__( 'Minified versions of JS files will be loaded. Works well with all caching and optimizations plugins.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'performance_js',
		'default'     => '1',
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'disable_owl_mobile_devices',
		'name'        => esc_html__( 'Disable OWL Carousel script on mobile devices', 'woodmart' ),
		'description' => esc_html__( 'Using native browser\'s scrolling feature on mobile devices may improve your page loading and performance on some devices. Desktop will be handled with OWL Carousel JS library.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'performance_js',
		'default'     => false,
		'priority'    => 40,
	)
);

Options::add_field(
	array(
		'id'          => 'advanced_js',
		'name'        => esc_html__( 'Advanced scripts controls', 'woodmart' ),
		'group'       => esc_html__( 'Advanced', 'woodmart' ),
		'description' => esc_html__( 'Our theme is designed in the way to load only scripts and libraries that are required on a particular page. But if you need to load some particular script for some reason globally, you can use the following set of options.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'performance_js',
		'default'     => false,
		'priority'    => 50,
	)
);

$config_libraries = woodmart_get_config( 'js-libraries' );
foreach ( $config_libraries as $key => $libraries ) {
	foreach ( $libraries as $library ) {
		Options::add_field(
			array(
				'id'       => $library['name'] . '_library',
				'section'  => 'performance_js',
				'name'     => ucfirst( $library['title'] ) . ' library',
				'group'    => esc_html__( 'Advanced', 'woodmart' ),
				'type'     => 'buttons',
				'options'  => array(
					'always'   => array(
						'name'  => esc_html__( 'Always load', 'woodmart' ),
						'value' => 'always',
					),
					'required' => array(
						'name'  => esc_html__( 'On demand', 'woodmart' ),
						'value' => 'required',
					),
					'not_use'  => array(
						'name'  => esc_html__( 'Never load', 'woodmart' ),
						'value' => 'not_use',
					),
				),
				'requires' => array(
					array(
						'key'     => 'advanced_js',
						'compare' => 'equals',
						'value'   => true,
					),
				),
				'default'  => 'required',
				'priority' => 60,
			)
		);
	}
}

$config_scripts  = woodmart_get_config( 'js-scripts' );
$scripts_options = array();
foreach ( $config_scripts as $key => $scripts ) {
	foreach ( $scripts as $script ) {
		if ( isset( $scripts_options[ $script['name'] ] ) ) {
			continue;
		}

		$scripts_options[ $key ] = array(
			'name'  => $script['title'],
			'value' => $key,
		);
	}
}

asort( $scripts_options );

Options::add_field(
	array(
		'id'          => 'scripts_always_use',
		'name'        => esc_html__( 'Scripts always load', 'woodmart' ),
		'description' => esc_html__( 'You can manually load some initialization scripts on all pages.', 'woodmart' ),
		'group'       => esc_html__( 'Advanced', 'woodmart' ),
		'section'     => 'performance_js',
		'type'        => 'select',
		'multiple'    => true,
		'select2'     => true,
		'options'     => $scripts_options,
		'default'     => array(),
		'requires'    => array(
			array(
				'key'     => 'advanced_js',
				'compare' => 'equals',
				'value'   => true,
			),
		),
		'priority'    => 70,
	)
);

Options::add_field(
	array(
		'id'          => 'scripts_not_use',
		'name'        => esc_html__( 'Scripts never load', 'woodmart' ),
		'description' => esc_html__( 'You can manually unload some initialization scripts on all pages.', 'woodmart' ),
		'group'       => esc_html__( 'Advanced', 'woodmart' ),
		'section'     => 'performance_js',
		'type'        => 'select',
		'multiple'    => true,
		'select2'     => true,
		'options'     => $scripts_options,
		'default'     => array(),
		'requires'    => array(
			array(
				'key'     => 'advanced_js',
				'compare' => 'equals',
				'value'   => true,
			),
		),
		'priority'    => 80,
	)
);

Options::add_field(
	array(
		'id'          => 'dequeue_scripts',
		'type'        => 'text_input',
		'section'     => 'performance_js',
		'name'        => esc_html__( 'Dequeue scripts', 'woodmart' ),
		'description' => esc_html__( 'You can manually disable JS files from being loaded using their keys. Write their case separated with a comma. For example: woodmart-theme,elementor-frontend', 'woodmart' ),
		'group'       => esc_html__( 'Advanced', 'woodmart' ),
		'requires'    => array(
			array(
				'key'     => 'advanced_js',
				'compare' => 'equals',
				'value'   => true,
			),
		),
		'priority'    => 90,
	)
);

/**
 * Lazy loading
 */
Options::add_section(
	array(
		'id'       => 'performance_lazy_loading',
		'name'     => esc_html__( 'Lazy loading', 'woodmart' ),
		'parent'   => 'performance',
		'priority' => 30,
	)
);

Options::add_field(
	array(
		'id'          => 'lazy_loading',
		'name'        => esc_html__( 'Lazy loading for images', 'woodmart' ),
		'description' => esc_html__( 'Enable this option to optimize your images loading on the website. They will be loaded only when user will scroll the page.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'performance_lazy_loading',
		'default'     => false,
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'lazy_loading_offset',
		'name'        => esc_html__( 'Offset', 'woodmart' ),
		'description' => esc_html__( 'Start load images X pixels before the page is scrolled to the item', 'woodmart' ),
		'type'        => 'range',
		'section'     => 'performance_lazy_loading',
		'default'     => 0,
		'min'         => 0,
		'step'        => 10,
		'max'         => 1000,
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'lazy_effect',
		'name'        => esc_html__( 'Appearance effect', 'woodmart' ),
		'description' => esc_html__( 'When enabled, your images will be replaced with their blurred small previews. And when the visitor will scroll the page to that image, it will be replaced with an original image.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'performance_lazy_loading',
		'default'     => 'fade',
		'options'     => array(
			'fade' => array(
				'name'  => esc_html__( 'Fade', 'woodmart' ),
				'value' => 'fade',
			),
			'blur' => array(
				'name'  => esc_html__( 'Blur', 'woodmart' ),
				'value' => 'blur',
			),
			'none' => array(
				'name'  => esc_html__( 'None', 'woodmart' ),
				'value' => 'none',
			),
		),
		'priority'    => 30,
	)
);

Options::add_field(
	array(
		'id'          => 'lazy_generate_previews',
		'name'        => esc_html__( 'Generate previews', 'woodmart' ),
		'description' => esc_html__( 'Create placeholders previews as miniatures from the original images.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'performance_lazy_loading',
		'default'     => '1',
		'priority'    => 40,
	)
);

Options::add_field(
	array(
		'id'          => 'lazy_base_64',
		'name'        => esc_html__( 'Base 64 encode for placeholders', 'woodmart' ),
		'description' => esc_html__( 'This option allows you to decrease a number of HTTP requests replacing images with base 64 encoded sources.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'performance_lazy_loading',
		'default'     => '1',
		'priority'    => 50,
	)
);

Options::add_field(
	array(
		'id'          => 'lazy_proprtion_size',
		'name'        => esc_html__( 'Proportional placeholders size', 'woodmart' ),
		'description' => esc_html__( 'Will generate proportional image size for the placeholder based on original image size.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'performance_lazy_loading',
		'default'     => '1',
		'priority'    => 60,
	)
);

Options::add_field(
	array(
		'id'          => 'lazy_custom_placeholder',
		'name'        => esc_html__( 'Upload custom placeholder image', 'woodmart' ),
		'description' => esc_html__( 'Add your custom image placeholder that will be used before the original image will be loaded.', 'woodmart' ),
		'type'        => 'upload',
		'section'     => 'performance_lazy_loading',
		'priority'    => 70,
	)
);

/**
 * Plugins
 */
Options::add_section(
	array(
		'id'       => 'plugins_section',
		'name'     => esc_html__( 'Plugins', 'woodmart' ),
		'parent'   => 'performance',
		'priority' => 40,
	)
);

Options::add_field(
	array(
		'id'          => 'cf7_js',
		'name'        => esc_html__( 'Load "Contact form 7" JS files', 'woodmart' ),
		'description' => esc_html__( 'You can enable/disable this option globally. If you want to load them on the particular page only, you can create a special Theme Settings preset for this and add a condition for that page.', 'woodmart' ),
		'group'       => esc_html__( 'Contact form 7', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'plugins_section',
		'default'     => true,
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'elementor_optimized_css',
		'type'        => 'switcher',
		'section'     => 'plugins_section',
		'name'        => esc_html__( 'Load Elementor optimized CSS', 'woodmart' ),
		'description' => esc_html__( 'Load only theme-required styles for Elementor. Don\'t use it if you are using most of the standard Elementor\'s widgets.', 'woodmart' ),
		'group'       => esc_html__( 'Elementor', 'woodmart' ),
		'default'     => '0',
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'       => 'elementor_animations',
		'type'     => 'switcher',
		'section'  => 'plugins_section',
		'name'     => esc_html__( 'Load Elementor animations CSS file', 'woodmart' ),
		'group'    => esc_html__( 'Elementor', 'woodmart' ),
		'default'  => '1',
		'priority' => 30,
	)
);

Options::add_field(
	array(
		'id'       => 'elementor_icons',
		'type'     => 'switcher',
		'section'  => 'plugins_section',
		'name'     => esc_html__( 'Load Elementor icons CSS file', 'woodmart' ),
		'group'    => esc_html__( 'Elementor', 'woodmart' ),
		'woodmart',
		'default'  => '1',
		'priority' => 40,
	)
);

Options::add_field(
	array(
		'id'          => 'elementor_dialog_library',
		'type'        => 'switcher',
		'section'     => 'plugins_section',
		'name'        => esc_html__( 'Elementor dialog.js library', 'woodmart' ),
		'description' => esc_html__( 'Turn it off if you never use it to improve the performance.', 'woodmart' ),
		'group'       => esc_html__( 'Elementor', 'woodmart' ),
		'default'     => true,
		'priority'    => 50,
	)
);

Options::add_field(
	array(
		'id'          => 'elementor_frontend',
		'type'        => 'switcher',
		'section'     => 'plugins_section',
		'name'        => esc_html__( 'Elementor frontend', 'woodmart' ),
		'description' => esc_html__( 'Disable Elementor\'s JS files if you are not using most of their widgets.', 'woodmart' ),
		'group'       => esc_html__( 'Elementor', 'woodmart' ),
		'default'     => '1',
		'priority'    => 60,
	)
);

Options::add_field(
	array(
		'id'       => 'swiper_library',
		'section'  => 'plugins_section',
		'name'     => esc_html__( 'Swiper library', 'woodmart' ),
		'group'    => esc_html__( 'Elementor', 'woodmart' ),
		'type'     => 'buttons',
		'options'  => array(
			'always'   => array(
				'name'  => esc_html__( 'Always load', 'woodmart' ),
				'value' => 'always',
			),
			'required' => array(
				'name'  => esc_html__( 'On demand', 'woodmart' ),
				'value' => 'required',
			),
			'not_use'  => array(
				'name'  => esc_html__( 'Never load', 'woodmart' ),
				'value' => 'not_use',
			),
		),
		'requires' => array(
			array(
				'key'     => 'elementor_frontend',
				'compare' => 'equals',
				'value'   => '0',
			),
		),
		'default'  => 'always',
		'priority' => 70,
	)
);

Options::add_field(
	array(
		'id'       => 'el_waypoints_library',
		'section'  => 'plugins_section',
		'name'     => esc_html__( 'Waypoints library', 'woodmart' ),
		'group'    => esc_html__( 'Elementor', 'woodmart' ),
		'type'     => 'buttons',
		'options'  => array(
			'always'   => array(
				'name'  => esc_html__( 'Always load', 'woodmart' ),
				'value' => 'always',
			),
			'required' => array(
				'name'  => esc_html__( 'On demand', 'woodmart' ),
				'value' => 'required',
			),
			'not_use'  => array(
				'name'  => esc_html__( 'Never load', 'woodmart' ),
				'value' => 'not_use',
			),
		),
		'requires' => array(
			array(
				'key'     => 'elementor_frontend',
				'compare' => 'equals',
				'value'   => '0',
			),
		),
		'default'  => 'always',
		'priority' => 80,
	)
);


/**
 * Fonts
 */
Options::add_section(
	array(
		'id'       => 'fonts_section',
		'name'     => esc_html__( 'Fonts & Icons', 'woodmart' ),
		'parent'   => 'performance',
		'priority' => 21,
	)
);

Options::add_field(
	array(
		'id'          => 'google_font_display',
		'name'        => esc_html__( '"font-display" for Google Fonts', 'woodmart' ),
		'description' => 'You can specify "font-display" property for fonts loaded from Google. Read more information <a href="https://developers.google.com/web/updates/2016/02/font-display">here</a>',
		'type'        => 'select',
		'section'     => 'fonts_section',
		'default'     => 'disable',
		'options'     => array(
			'disable'  => array(
				'name'  => esc_html__( 'Disable', 'woodmart' ),
				'value' => 'disable',
			),
			'block'    => array(
				'name'  => esc_html__( 'Block', 'woodmart' ),
				'value' => 'block',
			),
			'swap'     => array(
				'name'  => esc_html__( 'Swap', 'woodmart' ),
				'value' => 'swap',
			),
			'fallback' => array(
				'name'  => esc_html__( 'Fallback', 'woodmart' ),
				'value' => 'fallback',
			),
			'optional' => array(
				'name'  => esc_html__( 'Optional', 'woodmart' ),
				'value' => 'optional',
			),
		),
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'          => 'icons_font_display',
		'name'        => esc_html__( '"font-display" for icon fonts', 'woodmart' ),
		'description' => 'You can specify "font-display" property for fonts used for icons in our theme including Font Awesome. Read more information <a href="https://developers.google.com/web/updates/2016/02/font-display">here</a>',
		'type'        => 'select',
		'section'     => 'fonts_section',
		'default'     => 'disable',
		'options'     => array(
			'disable'  => array(
				'name'  => esc_html__( 'Disable', 'woodmart' ),
				'value' => 'disable',
			),
			'block'    => array(
				'name'  => esc_html__( 'Block', 'woodmart' ),
				'value' => 'block',
			),
			'swap'     => array(
				'name'  => esc_html__( 'Swap', 'woodmart' ),
				'value' => 'swap',
			),
			'fallback' => array(
				'name'  => esc_html__( 'Fallback', 'woodmart' ),
				'value' => 'fallback',
			),
			'optional' => array(
				'name'  => esc_html__( 'Optional', 'woodmart' ),
				'value' => 'optional',
			),
		),
		'priority'    => 20,
	)
);

Options::add_field(
	array(
		'id'          => 'font_awesome_css',
		'name'        => esc_html__( 'Font Awesome library' ),
		'description' => esc_html__( 'You can force Font Awesome 5 library to be loaded on all pages.', 'woodmart' ),
		'type'        => 'buttons',
		'section'     => 'fonts_section',
		'options'     => array(
			'always'  => array(
				'name'  => esc_html__( 'Always use', 'woodmart' ),
				'value' => 'always',
			),
			'not_use' => array(
				'name'  => esc_html__( 'Don\'t use', 'woodmart' ),
				'value' => 'not_use',
			),
		),
		'default'     => 'not_use',
		'priority'    => 30,
	)
);

Options::add_field(
	array(
		'id'          => 'font_icon_woff_preload',
		'name'        => esc_html__( 'Preload key request for "woodmart-font.woff"', 'woodmart' ),
		'description' => esc_html__( 'Enable this option if you see this warning in Google Pagespeed report.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'fonts_section',
		'default'     => false,
		'priority'    => 40,
	)
);

Options::add_field(
	array(
		'id'          => 'font_icon_woff2_preload',
		'name'        => esc_html__( 'Preload key request for "woodmart-font.woff2"', 'woodmart' ),
		'description' => esc_html__( 'Enable this option if you see this warning in Google Pagespeed report.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'fonts_section',
		'default'     => false,
		'priority'    => 50,
	)
);

/**
 * Preloader
 */
Options::add_section(
	array(
		'id'       => 'preloader_section',
		'name'     => esc_html__( 'Preloader', 'woodmart' ),
		'parent'   => 'performance',
		'priority' => 60,
	)
);

Options::add_field(
	array(
		'id'          => 'preloader',
		'name'        => esc_html__( 'Preloader (beta)', 'woodmart' ),
		'description' => esc_html__( 'Enable preloader animation while loading your website content. Useful when you move all the CSS to the footer.', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'preloader_section',
		'default'     => false,
		'priority'    => 10,
	)
);

Options::add_field(
	array(
		'id'       => 'preloader_image',
		'name'     => esc_html__( 'Animated image', 'woodmart' ),
		'type'     => 'upload',
		'section'  => 'preloader_section',
		'priority' => 20,
	)
);

Options::add_field(
	array(
		'id'       => 'preloader_background_color',
		'name'     => esc_html__( 'Background for loader screen', 'woodmart' ),
		'type'     => 'color',
		'default'  => array(
			'idle' => '#ffffff',
		),
		'section'  => 'preloader_section',
		'priority' => 30,
	)
);
