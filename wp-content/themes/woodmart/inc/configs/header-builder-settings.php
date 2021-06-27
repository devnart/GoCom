<?php if ( ! defined("WOODMART_THEME_DIR")) exit("No direct script access allowed");

/**
 * ------------------------------------------------------------------------------------------------
 * Default header builder settings
 * ------------------------------------------------------------------------------------------------
 */

$header_settings = array(
	'overlap' => array(
		'id' => 'overlap',
		'title' => 'Make it overlap',
		'type' => 'switcher',
		'tab' => 'General',
		'value' => false,
		'description' => 'Make the header overlap the content.'
	),
	'boxed' => array(
		'id' => 'boxed',
		'title' => 'Make it boxed',
		'type' => 'switcher',
		'tab' => 'General',
		'value' => false,
		'description' => 'The header will be boxed instead of full width',
		'requires' => array(
			'overlap' => array(
				'comparison' => 'equal',
				'value' => true
			),
	  	),
	),
	'full_width' => array(
		'id' => 'full_width',
		'title' => 'Full width header',
		'type' => 'switcher',
		'tab' => 'General',
		'value' => false,
		'description' => 'Full width layout for the header container.'
	),
	'dropdowns_dark' => array(
		'id' => 'dropdowns_dark',
		'title' => 'Header dropdowns dark',
		'type' => 'switcher',
		'tab' => 'General',
		'value' => false,
		'description' => 'Make all menu, shopping cart, search, mobile menu dropdowns in dark color scheme.'
	),
	'sticky_shadow' => array(
		'id' => 'sticky_shadow',
		'title' => 'Sticky header shadow',
		'type' => 'switcher',
		'tab' => 'Sticky header',
		'value' => true,
		'description' => 'Add a shadow for the header when it is sticked.'
	),
	'hide_on_scroll' => array(
		'id' => 'hide_on_scroll',
		'title' => esc_html__( 'Hide when scrolling down', 'woodmart' ),
		'type' => 'switcher',
		'tab' => 'Sticky header',
		'value' => false,
		'description' => esc_html__( 'Hides the sticky header when you scroll the page down. And shows only when you scroll top.', 'woodmart' ),
	),
	'sticky_effect' => array(
	  'id' => 'sticky_effect',
	  'title' => 'Sticky effect',
	  'type' => 'selector',
	  'tab' => 'Sticky header',
	  'value' => 'stick',
	  'options' => array(
		'stick' => array(
		  'value' => 'stick',
		  'label' => 'Stick on scroll',
		),
		'slide' => array(
		  'value' => 'slide',
		  'label' => 'Slide after scrolled down',
		),
	  ),
	  'description' => 'You can choose between two types of sticky header effects.'
	),
	'sticky_clone' => array(
		'id' => 'sticky_clone',
		'title' => 'Sticky header clone',
		'type' => 'switcher',
		'tab' => 'Sticky header',
		'value' => false,
		'requires' => array(
		  'sticky_effect' => array(
			'comparison' => 'equal',
			'value' => 'slide'
		  )
		),
		'description' => 'Sticky header will clone elements from the header (logo, menu, search and shopping cart widget) and show them in one line.'
	),
	'sticky_height' => array(
	  'id' => 'sticky_height',
	  'title' => 'Sticky header height',
	  'type' => 'slider',
	  'tab' => 'Sticky header',
	  'from' => 0,
	  'to'=> 200,
	  'value' => 50,
	  'units' => 'px',
	  'description' => 'Determine header height for sticky header value in pixels.',
	  'requires' => array(
		'sticky_clone' => array(
		  'comparison' => 'equal',
		  'value' => true
		),
		'sticky_effect' => array(
		  'comparison' => 'equal',
		  'value' => 'slide'
		)
	  ),
	),
);

return apply_filters( 'woodmart_default_header_settings', $header_settings );
