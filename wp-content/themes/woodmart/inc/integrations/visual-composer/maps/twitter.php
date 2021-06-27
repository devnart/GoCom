<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
* Twitter element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_vc_map_twitter' ) ) {
	function woodmart_vc_map_twitter() {
		if ( ! shortcode_exists( 'woodmart_twitter' ) ) {
			return;
		}

		vc_map( array(
			'name' => esc_html__( 'Twitter', 'woodmart' ),
			'base' => 'woodmart_twitter',
			'category' => esc_html__( 'Theme elements', 'woodmart' ),
			'description' => esc_html__( 'Shows tweets from any twitter account', 'woodmart' ),
        	'icon' => WOODMART_ASSETS . '/images/vc-icon/twitter.svg',
			'params' => array(
				/**
				* Widget settings
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Widget settings', 'woodmart' ),
					'param_name' => 'widget_divider',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Twitter Name (without @ symbol)', 'woodmart' ),
					'param_name' => 'name',
					'value' => 'Twitter',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Number of Tweets', 'woodmart' ),
					'param_name' => 'num_tweets',
					'value' => 5,
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Size of Avatar', 'woodmart' ),
					'param_name' => 'avatar_size',
					'hint' => esc_html__( 'Default: 48px', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Show your avatar image', 'woodmart' ),
					'param_name' => 'show_avatar',
					'true_state' => 1,
					'false_state' => 0,
					'default' => 0,
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'woodmart_switch',
					'heading' => esc_html__( 'Exclude Replies', 'woodmart' ),
					'param_name' => 'exclude_replies',
					'true_state' => 1,
					'false_state' => 0,
					'default' => 0,
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				/**
				* Access settings
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Access settings', 'woodmart' ),
					'param_name' => 'access_divider',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Consumer Key', 'woodmart' ),
					'param_name' => 'consumer_key',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Consumer Secret', 'woodmart' ),
					'param_name' => 'consumer_secret',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Access Token', 'woodmart' ),
					'param_name' => 'access_token',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Access Token Secret', 'woodmart' ),
					'param_name' => 'accesstoken_secret',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				/**
				* Extra
				*/
				array(
					'type' => 'woodmart_title_divider',
					'holder' => 'div',
					'title' => esc_html__( 'Extra options', 'woodmart' ),
					'param_name' => 'extra_divider'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'woodmart' ),
					'param_name' => 'el_class',
					'hint' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' )
				),
			)
		) );
	}
	add_action( 'vc_before_init', 'woodmart_vc_map_twitter' );
}
