<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* ------------------------------------------------------------------------------------------------
* Twitter element
* ------------------------------------------------------------------------------------------------
*/
if( ! function_exists( 'woodmart_twitter' ) ) {
	function woodmart_twitter( $atts ) {
		extract( shortcode_atts( array(
			'name' 	 => 'Twitter',
			'num_tweets' 	 => 5,
			'cache_time'   	 => 5,
			'consumer_key'    => '',
			'consumer_secret' => '',
			'access_token' => '',
			'accesstoken_secret' => '',
			'show_avatar' => 0,
			'avatar_size' => '',
			'exclude_replies' => false,
			'el_class' 	 => '',
		), $atts) );

		ob_start();
		woodmart_enqueue_inline_style( 'twitter' );
		?>
		<div class="wd-twitter-element wd-twitter-vc-element <?php if ( $el_class ) echo esc_attr( $el_class );?>">
			<?php woodmart_get_twitts( $atts ); ?>
		</div>
		<?php

		return  ob_get_clean();
	}	
}
