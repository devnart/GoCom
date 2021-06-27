<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );}

/**
 * ------------------------------------------------------------------------------------------------
 * Products tabs shortcode
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_shortcode_products_tabs' ) ) {
	function woodmart_shortcode_products_tabs( $atts = array(), $content = null ) {
		if ( ! function_exists( 'wpb_getImageBySize' ) ) {
			return;
		}
		$output = $class = $autoplay = $header_classes = '';
		extract(
			shortcode_atts(
				array(
					'title'           => '',
					'image'           => '',
					'img_size'        => '30x30',
					'design'          => 'default',
					'alignment'       => 'center',
					'color'           => '#83b735',

					'woodmart_css_id' => '',
					'el_class'        => '',
				),
				$atts
			)
		);

		$img_id = preg_replace( '/[^\d]/', '', $image );

		if ( ! $woodmart_css_id ) {
			$woodmart_css_id = uniqid();
		}
		$tabs_id = 'wd-' . $woodmart_css_id;

		// Extract tab titles
		preg_match_all( '/products_tab([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );
		$tab_titles = array();

		if ( isset( $matches[1] ) ) {
			$tab_titles = $matches[1];
		}

		$tabs_nav        = '';
		$first_tab_title = '';
		$tabs_nav       .= '<ul class="products-tabs-title">';
		$_i              = 0;
		foreach ( $tab_titles as $tab ) {
			$_i++;
			$tab_atts                       = shortcode_parse_atts( $tab[0] );
			$tab_atts['carousel_js_inline'] = 'yes';
			$encoded_atts                   = json_encode( $tab_atts );
			$icon_output                    = '';

			if ( empty( $tab_atts['icon_size'] ) ) {
				$tab_atts['icon_size'] = '25x25';
			}

			// Tab icon
			if ( isset( $tab_atts['icon'] ) ) {
				$icon_output = woodmart_display_icon( $tab_atts['icon'], $tab_atts['icon_size'], 25 );
			}

			if ( $_i == 1 && isset( $tab_atts['title'] ) ) {
				$first_tab_title = $tab_atts['title'];
			}
			$class = ( $_i == 1 ) ? ' active-tab-title' : '';
			if ( isset( $tab_atts['title'] ) ) {
				$tabs_nav .= '<li data-atts="' . esc_attr( $encoded_atts ) . '" class="' . esc_attr( $class ) . '">' . $icon_output . '<span class="tab-label">' . $tab_atts['title'] . '</span></li>';
			}
		}

		$tabs_nav .= '</ul>';

		$class .= ' tabs-' . $tabs_id;

		$class .= ' tabs-design-' . $design;

		$class .= ' ' . $el_class;

		$class .= woodmart_get_old_classes( ' woodmart-products-tabs' );

		$header_classes .= ' text-' . $alignment;

		woodmart_enqueue_js_script( 'products-tabs' );

		ob_start();
		woodmart_enqueue_inline_style( 'product-tabs' );
		?>
		<div id="<?php echo esc_attr( $tabs_id ); ?>" class="wd-products-tabs<?php echo esc_attr( $class ); ?>">
			<div class="wd-tabs-header<?php echo esc_attr( $header_classes ); ?>">
			<div class="wd-tabs-loader"><span class="wd-loader"></span></div>
				<?php if ( ! empty( $title ) ) : ?>
					<div class="tabs-name title">
						<?php
						if ( $img_id ) {
							echo woodmart_display_icon( $img_id, $img_size, 30 );}
						?>
						<span class="tabs-text"><?php echo wp_kses( $title, woodmart_get_allowed_html() ); ?></span>
					</div>
				<?php endif; ?>
				<div class="tabs-navigation-wrapper">
					<span class="open-title-menu"><?php echo wp_kses( $first_tab_title, woodmart_get_allowed_html() ); ?></span>
					<?php
					echo ! empty( $tabs_nav ) ? $tabs_nav : '';
					?>
				</div>
			</div>
			<?php
			if ( isset( $tab_titles[0][0] ) ) {
				$first_tab_atts = shortcode_parse_atts( $tab_titles[0][0] );
				echo woodmart_shortcode_products_tab( $first_tab_atts );
			}
			?>

			<?php
			if ( $color && ! woodmart_is_css_encode( $color ) ) {
				$css = '.tabs-' . esc_attr( $tabs_id  ) . '.tabs-design-simple .tabs-name {';
				$css .= 'border-color: ' . esc_attr( $color ) . ';';
				$css .= '}';

				$css .= '.tabs-' . esc_attr( $tabs_id  ) . '.tabs-design-default .products-tabs-title .tab-label:after,';
				$css .= '.tabs-' . esc_attr( $tabs_id  ) . '.tabs-design-alt .products-tabs-title .tab-label:after {';
				$css .= 'background-color: ' . esc_attr( $color ) . ';';
				$css .= '}';

				$css .= '.tabs-' . esc_attr( $tabs_id  ) . '.tabs-design-simple .products-tabs-title li.active-tab-title,';
				$css .= '.tabs-' . esc_attr( $tabs_id  ) . '.tabs-design-simple .owl-nav > div:hover,';
				$css .= '.tabs-' . esc_attr( $tabs_id  ) . '.tabs-design-simple .wrap-loading-arrow > div:not(.disabled):hover {';
				$css .= 'color: ' . esc_attr( $color ) . ';';
				$css .= '}';

				wp_add_inline_style( 'woodmart-inline-css', $css );
			}
			?>
		</div>
		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}

if ( ! function_exists( 'woodmart_shortcode_products_tab' ) ) {
	function woodmart_shortcode_products_tab( $atts ) {
		global $wpdb, $post;
		if ( ! function_exists( 'wpb_getImageBySize' ) ) {
			return;
		}
		$output = $class = '';

		$is_ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX );

		$parsed_atts = shortcode_atts(
			array_merge(
				array(
					'title'     => '',
					'icon'      => '',
					'icon_size' => '',
				),
				woodmart_get_default_product_shortcode_atts()
			),
			$atts
		);

		extract( $parsed_atts );

		$parsed_atts['carousel_js_inline'] = 'yes';
		$parsed_atts['force_not_ajax']     = 'yes';

		$class .= woodmart_get_old_classes( ' woodmart-tab-content' );

		ob_start();
		?>
		<?php if ( ! $is_ajax ) : ?>
			<div class="wd-tab-content<?php echo esc_attr( $class ); ?>" >
		
		<?php endif; ?>
		
		<?php
		echo woodmart_shortcode_products( $parsed_atts );
		?>
		<?php if ( ! $is_ajax ) : ?>
			</div>
		<?php endif; ?>
		<?php
		$output = ob_get_clean();

		if ( $is_ajax ) {
			$output = array(
				'html' => $output,
			);
		}

		return $output;
	}
}
