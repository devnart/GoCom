<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );}

/**
* ------------------------------------------------------------------------------------------------
* List shortcode
* ------------------------------------------------------------------------------------------------
*/

if ( ! function_exists( 'woodmart_list_shortcode' ) ) {
	function woodmart_list_shortcode( $atts ) {
		extract(
			shortcode_atts(
				array(
					'icon_fontawesome' => 'fa fa-adjust',
					'icon_openiconic'  => 'vc-oi vc-oi-dial',
					'icon_typicons'    => 'typcn typcn-adjust-brightness',
					'icon_entypo'      => 'entypo-icon entypo-icon-note',
					'icon_linecons'    => 'vc_li vc_li-heart',
					'icon_monosocial'  => 'vc-mono vc-mono-fivehundredpx',
					'icon_material'    => 'vc-material vc-material-cake',
					'icon_library'     => 'fontawesome',
					'icons_color'      => '#333333',
					'icons_bg_color'   => '#f4f4f4',

					'image'            => '',
					'img_size'         => '25x25',

					'color_scheme'     => '',
					'size'             => 'default',

					'list'             => '',
					'list_type'        => 'icon',
					'list_style'       => 'default',

					'el_class'         => '',
					'css_animation'    => 'none',
					'css'              => '',

					'woodmart_css_id'  => '',
				),
				$atts
			)
		);

		if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
			vc_icon_element_fonts_enqueue( $icon_library );
		}

		$list_items = $img = '';

		if ( function_exists( 'vc_param_group_parse_atts' ) ) {
			$list_items = vc_param_group_parse_atts( $list );
		}

		if ( empty( $list_items ) ) {
			return;
		}

		if ( ! $woodmart_css_id ) {
			$woodmart_css_id = uniqid();
		}
		$list_id = 'wd-' . $woodmart_css_id;

		$icon_class = 'list-icon';
		if ( $list_type == 'icon' ) {
			$icon_class .= ' ' . ${'icon_' . $icon_library};
		}

		$list_class  = 'wd-list wd-wpb';
		$list_class .= ' color-scheme-' . $color_scheme;
		$list_class .= ' ' . woodmart_get_new_size_classes( 'list', $size, 'text' );
		$list_class .= ' wd-list-type-' . $list_type;
		$list_class .= ' wd-list-style-' . $list_style;
		$list_class .= woodmart_get_css_animation( $css_animation );
		$list_class .= ( $el_class ) ? ' ' . $el_class : '';

		if ( $list_style == 'rounded' || $list_style == 'square' ) {
			$list_class .= ' wd-list-shape-icon';
		}
		if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$list_class .= ' ' . vc_shortcode_custom_css_class( $css );
		}

		woodmart_enqueue_inline_style( 'list' );

		ob_start();
		?>

		<div class="<?php echo esc_attr( $list_class ); ?>" id="<?php echo esc_attr( $list_id ); ?>">
			<ul>
				<?php foreach ( $list_items as $item ) : ?>
					<?php
					if ( ! $item ) {
						continue;}
					?>
					<li>
						<?php if ( $list_type != 'without' && $list_type != 'image' ) : ?>
							<div class="<?php echo esc_attr( $icon_class ); ?>"></div>
						<?php elseif ( $list_type == 'image' ) : ?>
							<div class="<?php echo esc_attr( $icon_class ); ?>">
								<?php if ( function_exists( 'wpb_getImageBySize' ) ) : ?>
									<?php
									echo wpb_getImageBySize(
										array(
											'attach_id'  => $image,
											'thumb_size' => $img_size,
											'class'      => 'list-image',
										)
									)['thumbnail'];
									?>
								<?php endif; ?>
							</div>
						<?php endif ?>
						<span class="list-content"><?php echo do_shortcode( $item['list-content'] ); ?></span>
					</li>
				<?php endforeach ?>
			</ul>
			<?php
			if ( ( $icons_color && ! woodmart_is_css_encode( $icons_color ) ) || ( $icons_bg_color && ! woodmart_is_css_encode( $icons_bg_color ) ) ) {
				$css = '#' . esc_attr( $list_id ) . ' .list-icon {';
				$css .= 'color: ' . esc_attr( $icons_color ) . ';';
				$css .= '}';

				if ( $list_style == 'rounded' || $list_style == 'square' ) {
					$css .= '#' . esc_attr( $list_id ) . ' .list-icon {';
					$css .= 'background-color: ' . esc_attr( $icons_bg_color  ) . ';';
					$css .= '}';
				}

				wp_add_inline_style( 'woodmart-inline-css', $css );
			}
			?>
		</div>
		
		<?php

		return ob_get_clean();
	}
}
