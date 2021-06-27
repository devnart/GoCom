<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* ------------------------------------------------------------------------------------------------
* Menu price element
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_shortcode_menu_price' )) {
	function woodmart_shortcode_menu_price($atts, $content) {
		if( ! function_exists( 'wpb_getImageBySize' ) ) return;
		$click = $output = $class = '';
		extract(shortcode_atts( array(
			'img_id' => '',
			'img_size' => 'full',
			'title' => '',
			'description' => '',
			'price' => '',
			'link' => '',
			'css_animation' => 'none',
			'el_class' => ''
		), $atts ));


		if( $link != '') {
			$class .= ' cursor-pointer';
		}

		$class .= ' ' . $el_class;
		$class .= woodmart_get_css_animation( $css_animation );
		$class .= woodmart_get_old_classes( ' woodmart-menu-price' );

		$attributes = woodmart_vc_get_link_attr( $link );

		if( $attributes['target'] == ' _blank' ) {
        	$onclick = 'window.open(\''. esc_url( $attributes['url'] ).'\',\'_blank\')';
        } else {
        	$onclick = 'window.location.href=\''. esc_url( $attributes['url'] ).'\'';
        }

		woodmart_enqueue_inline_style( 'menu-price' );

		ob_start(); ?>
			<div class="wd-menu-price wd-wpb <?php echo esc_attr( $class ); ?>" <?php if( ! empty( $link ) ) echo 'onclick="' . $onclick . '"'; ?>>
				<?php if ($img_id): ?>
					<div class="menu-price-image">
						<?php
							echo wpb_getImageBySize( array( 'attach_id' => $img_id, 'thumb_size' => $img_size, 'class' => '' ) )['thumbnail'];
						?>
					</div>
				<?php endif ?>
				<div class="menu-price-desc-wrapp">
					<div class="menu-price-heading">
						<?php if ( ! empty( $title ) ): ?>
							<h3 class="menu-price-title wd-entities-title"><span><?php echo wp_kses( $title, woodmart_get_allowed_html() ); ?></span></h3>
						<?php endif ?>
						<div class="menu-price-price price"><?php echo wp_kses( $price, woodmart_get_allowed_html() ); ?></div>
					</div>
					<?php if ( $description ): ?>
						<div class="menu-price-details"><?php echo do_shortcode( $description ); ?></div>
					<?php endif ?>
				</div>
			</div>
		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
