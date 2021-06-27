<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* ------------------------------------------------------------------------------------------------
* Extra menu (part of the mega menu)
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_shortcode_extra_menu' ) ) {
	function woodmart_shortcode_extra_menu($atts = array(), $content = null) {
		$output = $class = $liclass = $label_out = '';
		extract(shortcode_atts( array(
			'link' => '',
			'title' => '',
			'label' => 'primary',
			'label_text' => '',
			'css_animation' => 'none',
			'el_class' => ''
		), $atts ));

		if ( woodmart_get_menu_label_tag( $label, $label_text ) ) {
			$liclass .= woodmart_get_menu_label_class( $label );
		}

		if ( $el_class ) {
			$class .= ' ' . $el_class;
		}
		$class .= ' mega-menu-list';
		$class .= woodmart_get_css_animation( $css_animation );
		$class .= woodmart_get_old_classes( ' sub-menu' );

		ob_start(); ?>

			<ul class="wd-sub-menu<?php echo esc_attr( $class ); ?>" >
				<li class="<?php echo esc_attr( $liclass ); ?>"><a <?php echo woodmart_get_link_attributes( $link ); ?>><span class="nav-link-text"><?php echo wp_kses( $title, woodmart_get_allowed_html() ); ?></span><?php echo woodmart_get_menu_label_tag( $label, $label_text ); ?></a>
					<ul class="sub-sub-menu">
						<?php echo do_shortcode( $content ); ?>
					</ul>
				</li>
			</ul>

		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}


if( ! function_exists( 'woodmart_shortcode_extra_menu_list' ) ) {
	function woodmart_shortcode_extra_menu_list($atts, $content) {
		if( ! function_exists( 'wpb_getImageBySize' ) ) return;
		$output = $class = $label_out = '';
		extract(shortcode_atts( array(
			'link' => '',
			'title' => '',
			'label' => 'primary',
			'label_text' => '',
			'el_class' => ''
		), $atts ));

		if ( woodmart_get_menu_label_tag( $label, $label_text ) ) {
			$class .= woodmart_get_menu_label_class( $label );
		}

		if ( $el_class ) {
			$class .= ' ' . $el_class;
		}

		ob_start(); ?>

			<li class="<?php echo esc_attr( $class ); ?>"><a <?php echo woodmart_get_link_attributes( $link ); ?>><?php echo wp_kses( $title, woodmart_get_allowed_html() ); ?><?php echo woodmart_get_menu_label_tag( $label, $label_text ); ?></a></li>

		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}

if( ! function_exists( 'woodmart_get_menu_label_tag' ) ) {
	function woodmart_get_menu_label_tag( $label, $label_text ) {
		if( empty( $label_text ) ) return '';
		$label_out = '<span class="menu-label menu-label-' . $label . '">' . esc_attr( $label_text ) . '</span>';
		return $label_out;
	}
}


if( ! function_exists( 'woodmart_get_menu_label_class' ) ) {
	function woodmart_get_menu_label_class( $label ) {
		$class = '';
		$class .= ' item-with-label';
		$class .= ' item-label-' . $label;
		return $class;
	}
}

