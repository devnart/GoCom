<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * Register all of the default WordPress widgets on startup.
 *
 * Calls 'woodmart_widgets_init' action after all of the WordPress widgets have been
 * registered.
 *
 * @since 2.2.0
 */

include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/wph-widget-class.php');

include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-widget-price-filter.php');
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-widget-layered-nav.php');
	
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-wp-nav-menu-widget.php');
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-widget-search.php');
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-widget-sorting.php');
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-user-panel-widget.php');
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-author-area-widget.php');
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-banner-widget.php');
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-instagram-widget.php');
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-static-block-widget.php');
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-widget-recent-posts.php');
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-widget-twitter.php');
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-widget-stock-status.php');



