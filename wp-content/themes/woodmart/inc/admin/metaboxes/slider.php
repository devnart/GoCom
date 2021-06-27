<?php
/**
 * Slider metaboxes
 *
 * @package xts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

use XTS\Metaboxes;

if ( ! function_exists( 'woodmart_register_slider_metaboxes' ) ) {
	/**
	 * Register slider metaboxes
	 *
	 * @since 1.0.0
	 */
	function woodmart_register_slider_metaboxes() {
		$slide_metabox = Metaboxes::add_metabox(
			array(
				'id'         => 'xts_slide_metaboxes',
				'title'      => esc_html__( 'Slide Settings', 'woodmart' ),
				'post_types' => array( 'woodmart_slide' ),
			)
		);

		$slide_metabox->add_section(
			array(
				'id'       => 'general',
				'name'     => esc_html__( 'General', 'woodmart' ),
				'priority' => 10,
				'icon'     => WOODMART_ASSETS . '/assets/images/dashboard-icons/settings.svg',
			)
		);

		$slide_metabox->add_field(
			array(
				'id'        => 'bg_color',
				'name'      => esc_html__( 'Background color', 'woodmart' ),
				'type'      => 'color',
				'section'   => 'general',
				'default'   => '#fefefe',
				'data_type' => 'hex',
				'priority'  => 10,
			)
		);

		$slide_metabox->add_field(
			array(
				'id'        => 'bg_image_tablet',
				'name'      => esc_html__( 'Slide image on tablet', 'woodmart' ),
				'type'      => 'upload',
				'section'   => 'general',
				'data_type' => 'url',
				'priority'  => 20,
			)
		);

		$slide_metabox->add_field(
			array(
				'id'        => 'bg_image_mobile',
				'name'      => esc_html__( 'Slide image on mobile', 'woodmart' ),
				'type'      => 'upload',
				'section'   => 'general',
				'data_type' => 'url',
				'priority'  => 30,
			)
		);

		$slide_metabox->add_field(
			array(
				'id'       => 'vertical_align',
				'name'     => esc_html__( 'Vertical content align', 'woodmart' ),
				'type'     => 'buttons',
				'default'  => 'middle',
				'section'  => 'general',
				'options'  => array(
					'top'    => array(
						'name'  => esc_html__( 'Top', 'woodmart' ),
						'value' => 'top',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/top.jpg',
					),
					'middle' => array(
						'name'  => esc_html__( 'Middle', 'woodmart' ),
						'value' => 'middle',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/middle.jpg',
					),
					'bottom' => array(
						'name'  => esc_html__( 'Bottom', 'woodmart' ),
						'value' => 'bottom',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/bottom.jpg',
					),
				),
				'priority' => 40,
			)
		);

		$slide_metabox->add_field(
			array(
				'id'       => 'horizontal_align',
				'name'     => esc_html__( 'Horizontal content align', 'woodmart' ),
				'type'     => 'buttons',
				'default'  => 'middle',
				'section'  => 'general',
				'options'  => array(
					'left'   => array(
						'name'  => esc_html__( 'Left', 'woodmart' ),
						'value' => 'left',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/left.jpg',
					),
					'center' => array(
						'name'  => esc_html__( 'Center', 'woodmart' ),
						'value' => 'center',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/center.jpg',
					),
					'right'  => array(
						'name'  => esc_html__( 'Right', 'woodmart' ),
						'value' => 'right',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/right.jpg',
					),
				),
				'priority' => 50,
			)
		);

		$slide_metabox->add_field(
			array(
				'id'          => 'content_without_padding',
				'type'        => 'checkbox',
				'name'        => esc_html__( 'Content no space', 'woodmart' ),
				'description' => esc_html__( 'The content block will not have any paddings', 'woodmart' ),
				'section'     => 'general',
				'priority'    => 60,
			)
		);

		$slide_metabox->add_field(
			array(
				'id'          => 'content_full_width',
				'type'        => 'checkbox',
				'name'        => esc_html__( 'Full width content', 'woodmart' ),
				'description' => esc_html__( 'Takes the slider\'s width', 'woodmart' ),
				'section'     => 'general',
				'priority'    => 70,
			)
		);

		$slide_metabox->add_field(
			array(
				'id'          => 'content_width',
				'name'        => esc_html__( 'Content width [on desktop]', 'woodmart' ),
				'description' => esc_html__( 'Set your value in pixels.', 'woodmart' ),
				'type'        => 'range',
				'min'         => '100',
				'max'         => '1200',
				'step'        => '5',
				'default'     => '1200', // start value
				'section'     => 'general',
				'priority'    => 80,
				'class'       => 'xts-option-icon xts-col-4 xts-option-icon-desktop',
				'requires'    => array(
					array(
						'key'     => 'content_full_width',
						'compare' => 'not_equals',
						'value'   => 'on',
					),
				),
			)
		);

		$slide_metabox->add_field(
			array(
				'id'          => 'content_width_tablet',
				'name'        => esc_html__( 'Content width [on tablets]', 'woodmart' ),
				'description' => esc_html__( 'Set your value in pixels.', 'woodmart' ),
				'type'        => 'range',
				'min'         => '100',
				'max'         => '1200',
				'step'        => '5',
				'default'     => '1200', // start value
				'section'     => 'general',
				'priority'    => 90,
				'class'       => 'xts-option-icon xts-col-4 xts-option-icon-tablet',
				'requires'    => array(
					array(
						'key'     => 'content_full_width',
						'compare' => 'not_equals',
						'value'   => 'on',
					),
				),
			)
		);

		$slide_metabox->add_field(
			array(
				'id'          => 'content_width_mobile',
				'name'        => esc_html__( 'Content width [on mobile]', 'woodmart' ),
				'description' => esc_html__( 'Set your value in pixels.', 'woodmart' ),
				'type'        => 'range',
				'min'         => '50',
				'max'         => '800',
				'step'        => '5',
				'default'     => '300', // start value
				'section'     => 'general',
				'priority'    => 100,
				'class'       => 'xts-option-icon xts-col-4 xts-option-icon-mobile',
				'requires'    => array(
					array(
						'key'     => 'content_full_width',
						'compare' => 'not_equals',
						'value'   => 'on',
					),
				),
			)
		);

		$slide_metabox->add_field(
			array(
				'id'          => 'slide_animation',
				'name'        => esc_html__( 'Animation', 'woodmart' ),
				'description' => esc_html__( 'Select a content appearance animation', 'woodmart' ),
				'type'        => 'select',
				'section'     => 'general',
				'options'     => array(
					'none'              => array(
						'name'  => esc_html__( 'None', 'woodmart' ),
						'value' => 'none',
					),
					'slide-from-top'    => array(
						'name'  => esc_html__( 'Slide from top', 'woodmart' ),
						'value' => 'slide-from-top',
					),
					'slide-from-bottom' => array(
						'name'  => esc_html__( 'Slide from bottom', 'woodmart' ),
						'value' => 'slide-from-bottom',
					),
					'slide-from-right'  => array(
						'name'  => esc_html__( 'Slide from right', 'woodmart' ),
						'value' => 'slide-from-right',
					),
					'slide-from-left'   => array(
						'name'  => esc_html__( 'Slide from left', 'woodmart' ),
						'value' => 'slide-from-left',
					),
					'top-flip-x'        => array(
						'name'  => esc_html__( 'Top flip X', 'woodmart' ),
						'value' => 'top-flip-x',
					),
					'bottom-flip-x'     => array(
						'name'  => esc_html__( 'Bottom flip X', 'woodmart' ),
						'value' => 'bottom-flip-x',
					),
					'right-flip-y'      => array(
						'name'  => esc_html__( 'Right flip Y', 'woodmart' ),
						'value' => 'right-flip-y',
					),
					'left-flip-y'       => array(
						'name'  => esc_html__( 'Left flip Y', 'woodmart' ),
						'value' => 'left-flip-y',
					),
					'zoom-in'           => array(
						'name'  => esc_html__( 'Zoom in', 'woodmart' ),
						'value' => 'zoom-in',
					),
				),
				'priority'    => 110,
			)
		);
	}

	add_action( 'init', 'woodmart_register_slider_metaboxes', 100 );
}

$slider_metabox = Metaboxes::add_metabox(
	array(
		'id'         => 'xts_slider_metaboxes',
		'title'      => esc_html__( 'Slide Settings', 'woodmart' ),
		'object'     => 'term',
		'taxonomies' => array( 'woodmart_slider' ),
	)
);

$slider_metabox->add_section(
	array(
		'id'       => 'general',
		'name'     => esc_html__( 'General', 'woodmart' ),
		'priority' => 10,
		'icon'     => WOODMART_ASSETS . '/assets/images/dashboard-icons/settings.svg',
	)
);

$slider_metabox->add_field(
	array(
		'id'          => 'stretch_slider',
		'name'        => esc_html__( 'Stretch slider', 'woodmart' ),
		'description' => esc_html__( 'Make slider full width', 'woodmart' ),
		'type'        => 'checkbox',
		'section'     => 'general',
		'class'       => 'xts-col-6',
		'priority'    => 10,
	)
);

$slider_metabox->add_field(
	array(
		'id'          => 'stretch_content',
		'name'        => esc_html__( 'Full with content', 'woodmart' ),
		'description' => esc_html__( 'Make content full width', 'woodmart' ),
		'type'        => 'checkbox',
		'section'     => 'general',
		'requires'    => array(
			array(
				'key'     => 'stretch_slider',
				'compare' => 'equals',
				'value'   => 'on',
			),
		),
		'class'       => 'xts-col-6',
		'priority'    => 11,
	)
);

$slider_metabox->add_field(
	array(
		'id'          => 'height',
		'name'        => esc_html__( 'Height on desktop', 'woodmart' ),
		'description' => esc_html__( 'Set your value in pixels.', 'woodmart' ),
		'type'        => 'range',
		'min'         => '100',
		'max'         => '1200',
		'step'        => '5',
		'default'     => '500',
		'section'     => 'general',
		'priority'    => 20,
		'class'       => 'xts-option-icon xts-option-icon-desktop',
	)
);

$slider_metabox->add_field(
	array(
		'id'          => 'height_tablet',
		'name'        => esc_html__( 'Height on tablet', 'woodmart' ),
		'description' => esc_html__( 'Set your value in pixels.', 'woodmart' ),
		'type'        => 'range',
		'min'         => '100',
		'max'         => '1200',
		'step'        => '5',
		'default'     => '500',
		'section'     => 'general',
		'priority'    => 30,
		'class'       => 'xts-option-icon xts-option-icon-tablet',
	)
);

$slider_metabox->add_field(
	array(
		'id'          => 'height_mobile',
		'name'        => esc_html__( 'Height on mobile', 'woodmart' ),
		'description' => esc_html__( 'Set your value in pixels.', 'woodmart' ),
		'type'        => 'range',
		'min'         => '100',
		'max'         => '1200',
		'step'        => '5',
		'default'     => '500',
		'section'     => 'general',
		'priority'    => 40,
		'class'       => 'xts-option-icon xts-option-icon-mobile',
	)
);

$slider_metabox->add_field(
	array(
		'id'       => 'arrows_style',
		'name'     => esc_html__( 'Arrows style', 'woodmart' ),
		'type'     => 'buttons',
		'default'  => '1',
		'section'  => 'general',
		'options'  => array(
			'1' => array(
				'name'  => 'Style 1',
				'value' => '1',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/slider-navigation/arrow-style-1.jpg',
			),
			'2' => array(
				'name'  => 'Style 2',
				'value' => '2',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/slider-navigation/arrow-style-2.jpg',
			),
			'3' => array(
				'name'  => 'Style 3',
				'value' => '3',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/slider-navigation/arrow-style-3.jpg',
			),
			'0' => array(
				'name'  => 'Disable',
				'value' => '0',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/slider-navigation/arrow-style-disable.jpg',
			),
		),
		'priority' => 45,
	)
);

$slider_metabox->add_field(
	array(
		'id'       => 'pagination_style',
		'name'     => esc_html__( 'Pagination style', 'woodmart' ),
		'type'     => 'buttons',
		'section'  => 'general',
		'default'  => '1',
		'options'  => array(
			'1' => array(
				'name'  => 'Style 1',
				'value' => '1',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/slider-navigation/pagination-style-1.jpg',
			),
			'2' => array(
				'name'  => 'Style 2',
				'value' => '2',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/slider-navigation/pagination-style-2.jpg',
			),
			'0' => array(
				'name'  => 'Disable',
				'value' => '0',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/slider-navigation/pagination-style-disable.jpg',
			),
		),
		'priority' => 50,
	)
);

$slider_metabox->add_field(
	array(
		'id'       => 'pagination_color',
		'name'     => esc_html__( 'Navigation color scheme', 'woodmart' ),
		'type'     => 'buttons',
		'section'  => 'general',
		'default'  => '1',
		'options'  => array(
			'light' => array(
				'name'  => 'Light',
				'value' => 'light',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/slider-navigation/pagination-color-light.jpg',
			),
			'dark'  => array(
				'name'  => 'Dark',
				'value' => 'dark',
				'image' => WOODMART_ASSETS_IMAGES . '/settings/slider-navigation/pagination-color-dark.jpg',
			),
		),
		'priority' => 50,
	)
);

$slider_metabox->add_field(
	array(
		'id'          => 'autoplay',
		'name'        => esc_html__( 'Enable autoplay', 'woodmart' ),
		'description' => 'Rotate slider images automatically.',
		'type'        => 'checkbox',
		'section'     => 'general',
		'priority'    => 60,
	)
);

$slider_metabox->add_field(
	array(
		'id'          => 'autoplay_speed',
		'name'        => esc_html__( 'Autoplay speed', 'woodmart' ),
		'type'        => 'range',
		'min'         => '1000',
		'max'         => '30000',
		'step'        => '100',
		'default'     => '9000', // start value
		'section'     => 'general',
		'priority'    => 61,
		'requires'    => array(
			array(
				'key'     => 'autoplay',
				'compare' => 'equals',
				'value'   => 'on',
			),
		),
	)
);

$slider_metabox->add_field(
	array(
		'id'          => 'scroll_carousel_init',
		'name'        => esc_html__( 'Init carousel on scroll', 'woodmart' ),
		'description' => esc_html__( 'This option allows you to init carousel script only when visitor scroll the page to the slider. Useful for performance optimization.', 'woodmart' ),
		'type'        => 'checkbox',
		'section'     => 'general',
		'priority'    => 70,
	)
);

$slider_metabox->add_field(
	array(
		'id'       => 'animation',
		'name'     => esc_html__( 'Slide change animation', 'woodmart' ),
		'type'     => 'buttons',
		'section'  => 'general',
		'default'  => 'slide',
		'options'  => array(
			'slide' => array(
				'name'  => 'Slide',
				'value' => 'slide',
			),
			'fade'  => array(
				'name'  => 'Fade',
				'value' => 'fade',
			),
		),
		'priority' => 80,
	)
);
