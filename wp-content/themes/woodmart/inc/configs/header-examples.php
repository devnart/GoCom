<?php if ( ! defined("WOODMART_THEME_DIR")) exit("No direct script access allowed");

/**
 * ------------------------------------------------------------------------------------------------
 * Header builder premade example layouts
 * ------------------------------------------------------------------------------------------------
 */

$header_examples = array(
    'empty' => array(
        'name' => 'Empty header',
        'preview' =>  WOODMART_ASSETS_IMAGES . '/header-builder/header-examples/header-empty.png'
    ),
    'advanced' => array(
        'name' => 'Advanced',
        'preview' =>  WOODMART_ASSETS_IMAGES . '/header-builder/header-examples/header-advanced.png'
    ),
    'base' => array(
        'name' => 'Base',
        'preview' =>  WOODMART_ASSETS_IMAGES . '/header-builder/header-examples/header-base.png'
    ),
    'double-menu' => array(
        'name' => 'Double menu',
        'preview' =>  WOODMART_ASSETS_IMAGES . '/header-builder/header-examples/header-double-menu.png'
    ),
    'ecommerce' => array(
        'name' => 'eCommerce',
        'preview' =>  WOODMART_ASSETS_IMAGES . '/header-builder/header-examples/header-ecommerce.png'
    ),
    'logo-center' => array(
        'name' => 'Logo center',
        'preview' =>  WOODMART_ASSETS_IMAGES . '/header-builder/header-examples/header-logo-center.png'
    ),
    'menu-topbar' => array(
        'name' => 'Menu in topbar',
        'preview' =>  WOODMART_ASSETS_IMAGES . '/header-builder/header-examples/header-menu-topbar.png'
    ),
    'simplified' => array(
        'name' => 'Simplified',
        'preview' =>  WOODMART_ASSETS_IMAGES . '/header-builder/header-examples/header-simplified.png'
    ),
    'with-categories' => array(
        'name' => 'With categories menu',
        'preview' =>  WOODMART_ASSETS_IMAGES . '/header-builder/header-examples/header-with-categories.png'
    ),
    'base-dark' => array(
        'name' => 'Base Dark',
        'preview' =>  WOODMART_ASSETS_IMAGES . '/header-builder/header-examples/header-base-dark.png'
    ),
);

return apply_filters( 'woodmart_header_examples', $header_examples );