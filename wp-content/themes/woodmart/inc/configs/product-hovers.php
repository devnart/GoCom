<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Products hover effects
 * ------------------------------------------------------------------------------------------------
 */

return apply_filters( 'woodmart_get_product_hovers', array(
    'info-alt' => array(
        'title' => esc_html__( 'Full info on hover', 'woodmart' ),
        'img' => WOODMART_ASSETS_IMAGES . '/settings/hover/info-alt.jpg',
    ),
    'info' => array(
        'title' => esc_html__( 'Full info on image', 'woodmart' ),
        'img' => WOODMART_ASSETS_IMAGES . '/settings/hover/info.jpg',
    ),
    'alt' => array(
        'title' => esc_html__( 'Icons and "add to cart" on hover', 'woodmart' ),
        'img' => WOODMART_ASSETS_IMAGES . '/settings/hover/alt.jpg',
    ),
    'icons' => array(
        'title' => esc_html__( 'Icons on hover', 'woodmart' ),
        'img' => WOODMART_ASSETS_IMAGES . '/settings/hover/icons.jpg',
    ),
    'quick' => array(
        'title' => esc_html__( 'Quick', 'woodmart' ),
        'img' => WOODMART_ASSETS_IMAGES . '/settings/hover/quick.jpg',
    ),
    'button' => array(
        'title' => esc_html__( 'Show button on hover on image', 'woodmart' ),
        'img' => WOODMART_ASSETS_IMAGES . '/settings/hover/button.jpg',
    ),
    'base' => array(
        'title' => esc_html__( 'Show summary on hover', 'woodmart' ),
        'img' => WOODMART_ASSETS_IMAGES . '/settings/hover/base.jpg',
    ),
    'standard' => array(
        'title' => esc_html__( 'Standard button', 'woodmart' ),
        'img' => WOODMART_ASSETS_IMAGES . '/settings/hover/standard.jpg',
    ),
    'tiled' => array(
        'title' => esc_html__( 'Tiled', 'woodmart' ),
        'img' => WOODMART_ASSETS_IMAGES . '/settings/hover/tiled.jpg',
    ),
) );