<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* ------------------------------------------------------------------------------------------------
* AJAX search shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_ajax_search' ) ) {
	function woodmart_ajax_search( $atts ) {
		extract( shortcode_atts( array(
			'number' 	 => 3,
			'price' 	 => 1,
			'thumbnail'  => 1,
			'category' 	 => 1,
			'search_post_type' 	 => 'product',
			'woodmart_color_scheme' => 'dark',
			'el_class' 	 => '',
			'css' 	 => '',
		), $atts) );

		$class = 'wd-color-'. $woodmart_color_scheme;
		$class .= ' ' . $el_class;
		if( function_exists( 'vc_shortcode_custom_css_class' ) ) $class .= ' ' . vc_shortcode_custom_css_class( $css );

		$class .= woodmart_get_old_classes( ' woodmart-vc-ajax-search' );

		ob_start();
		?>
			<div class="wd-el-search woodmart-ajax-search <?php echo esc_attr( $class ); ?>">
				<?php
					woodmart_search_form( array(
						'ajax' => true,
						'post_type' => $search_post_type,
						'count' => $number,
						'thumbnail' => $thumbnail,
						'price' => $price,
						'show_categories' => $category
					) );
				?>
			</div>
		<?php

		return ob_get_clean();
	}
}
