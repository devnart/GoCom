<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * -------------------------------------------------------------------------------
 * Designs for categories
 * -----------------------------------------------------------------------------
 */

return apply_filters( 'woodmart_get_categories_designs', array(
	'default' => array(
		'title' => esc_html__( 'Default', 'woodmart' ),
		'img' => WOODMART_ASSETS_IMAGES . '/settings/categories/default.jpg',
	),
	'alt' => array(
        'title' => esc_html__( 'Alternative', 'woodmart' ),
        'img' => WOODMART_ASSETS_IMAGES . '/settings/categories/alt.jpg',
	),
	'center' => array(
        'title' => esc_html__( 'Center title', 'woodmart' ),
        'img' => WOODMART_ASSETS_IMAGES . '/settings/categories/center.jpg',
	),
	'replace-title' => array(
        'title' => esc_html__( 'Replace title', 'woodmart' ),
        'img' => WOODMART_ASSETS_IMAGES . '/settings/categories/replace-title.jpg',
    ),
) );