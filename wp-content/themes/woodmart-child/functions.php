<?php

add_action( 'wp_enqueue_scripts', 'woodmart_child_enqueue_styles', 1000 );

function woodmart_child_enqueue_styles() {
	$version = woodmart_get_theme_info( 'Version' );
	
	if( woodmart_get_opt( 'minified_css' ) ) {
		wp_enqueue_style( 'woodmart-style', get_template_directory_uri() . '/style.min.css', array('bootstrap'), $version );
	} else {
		wp_enqueue_style( 'woodmart-style', get_template_directory_uri() . '/style.css', array('bootstrap'), $version );
	}

    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('bootstrap'), $version );
    
}