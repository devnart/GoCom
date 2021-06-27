<?php
/**
 * Products template function
 *
 * @package xts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'woodmart_elementor_products_tabs_template' ) ) {
	function woodmart_elementor_products_tabs_template( $settings ) {
		$default_settings = [
			// General.
			'title'                    => '',
			'image'                    => '',
			'image_custom_dimension'   => '',
			'design'                   => 'default',
			'alignment'                => 'center',
			'tabs_items'               => [],

			// Layout.
			'layout'                   => 'grid',
			'pagination'               => '',
			'items_per_page'           => 12,
			'spacing'                  => woodmart_get_opt( 'products_spacing' ),
			'columns'                  => [ 'size' => 4 ],
			'products_masonry'         => woodmart_get_opt( 'products_masonry' ),
			'products_different_sizes' => woodmart_get_opt( 'products_different_sizes' ),
			'product_quantity'         => woodmart_get_opt( 'product_quantity' ),

			// Design.
			'product_hover'            => woodmart_get_opt( 'products_hover' ),
			'sale_countdown'           => 0,
			'stock_progress_bar'       => 0,
			'highlighted_products'     => 0,
			'products_bordered_grid'   => 0,
			'img_size'                 => 'woocommerce_thumbnail',

			// Extra.
			'elementor'                => true,
		];

		$settings = wp_parse_args( $settings, $default_settings );

		if ( ! $settings['spacing'] ) {
			$settings['spacing'] = woodmart_get_opt( 'products_spacing' );
		}

		$image_output    = '';
		$wrapper_classes = '';
		$header_classes  = '';
		$title_classes = '';
		
		// Title classes.
		if ( woodmart_elementor_is_edit_mode() ) {
			$title_classes .= ' elementor-inline-editing';
		}

		// Header classes.
		$settings['alignment'] = $settings['alignment'] ? $settings['alignment'] : 'center';
		$header_classes .= ' text-' . $settings['alignment'];

		// Wrapper classes.
		$wrapper_classes .= ' tabs-design-' . $settings['design'];

		// Image settings.
		$custom_image_size = isset( $settings['image_custom_dimension']['width'] ) && $settings['image_custom_dimension']['width'] ? $settings['image_custom_dimension'] : [
			'width'  => 128,
			'height' => 128,
		];

		if ( isset( $settings['image']['id'] ) && $settings['image']['id'] ) {
			$image_output = '<span class="img-wrapper">' . woodmart_get_image_html( $settings, 'image' ) . '</span>';

			if ( woodmart_is_svg( woodmart_get_image_url( $settings['image']['id'], 'image', $settings ) ) ) {
				$image_output = '<span class="svg-icon img-wrapper" style="width:' . esc_attr( $custom_image_size['width'] ) . 'px; height:' . esc_attr( $custom_image_size['height'] ) . 'px;">' . woodmart_get_any_svg( woodmart_get_image_url( $settings['image']['id'], 'image', $settings ), rand( 999, 9999 ) ) . '</span>';
			}
		}

		// Tabs settings.
		$first_tab_title = '';

		if ( isset( $settings['tabs_items'][0]['title'] ) ) {
			$first_tab_title = $settings['tabs_items'][0]['title'];
		}

		woodmart_enqueue_js_script( 'products-tabs' );
		woodmart_enqueue_inline_style( 'product-tabs' );

		$wrapper_classes .= woodmart_get_old_classes( ' woodmart-products-tabs' );

		?>
		<div class="wd-products-tabs<?php echo esc_attr( $wrapper_classes ); ?>">
			<div class="wd-tabs-header<?php echo esc_attr( $header_classes ); ?>">
				<div class="wd-tabs-loader"><span class="wd-loader"></span></div>

				<?php if ( $settings['title'] ) : ?>
					<div class="tabs-name title">
						<?php if ( $image_output ) : ?>
							<?php echo $image_output; // phpcs:ignore ?>
						<?php endif; ?>

						<span class="tabs-text<?php echo esc_attr( $title_classes ); ?>" data-elementor-setting-key="title">
							<?php echo wp_kses( $settings['title'], woodmart_get_allowed_html() ); ?>
						</span>
					</div>
				<?php endif; ?>

				<div class="tabs-navigation-wrapper">
					<span class="open-title-menu">
						<?php echo wp_kses( $first_tab_title, woodmart_get_allowed_html() ); ?>
					</span>

					<ul class="products-tabs-title">
						<?php foreach ( $settings['tabs_items'] as $key => $item ) : ?>
							<?php
							$li_classes        = '';
							$icon_output       = '';
							$item['elementor'] = true;
							$encoded_settings  = wp_json_encode( array_intersect_key( $settings + $item, woodmart_get_elementor_products_config() ) );

							if ( 0 === $key ) {
								$li_classes .= ' active-tab-title';
							}

							// Icon settings.
							$custom_icon_size = isset( $item['image_custom_dimension']['width'] ) && $item['image_custom_dimension']['width'] ? $item['image_custom_dimension'] : [
								'width'  => 128,
								'height' => 128,
							];

							if ( isset( $item['image']['id'] ) && $item['image']['id'] ) {
								$icon_output = '<span class="img-wrapper">' . woodmart_get_image_html( $item, 'image' ) . '</span>';

								if ( woodmart_is_svg( woodmart_get_image_url( $item['image']['id'], 'image', $item ) ) ) {
									$icon_output = '<span class="svg-icon img-wrapper" style="width:' . esc_attr( $custom_icon_size['width'] ) . 'px; height:' . esc_attr( $custom_icon_size['height'] ) . 'px;">' . woodmart_get_any_svg( woodmart_get_image_url( $item['image']['id'], 'image', $item ), rand( 999, 9999 ) ) . '</span>';
								}
							}
							?>

							<li data-atts="<?php echo esc_attr( $encoded_settings ); ?>" class="<?php echo esc_attr( $li_classes ); ?>">
								<?php if ( $icon_output ) : ?>
									<?php echo $icon_output; ?>
								<?php endif; ?>

								<span class="tab-label">
									<?php echo esc_html( $item['title'] ); ?>
								</span>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>

			<?php if ( isset( $settings['tabs_items'][0] ) ) : ?>
				<?php echo woodmart_elementor_products_tab_template( $settings + $settings['tabs_items'][0] ); ?>
			<?php endif; ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'woodmart_elementor_products_tab_template' ) ) {
	function woodmart_elementor_products_tab_template( $settings ) {
		$is_ajax = woodmart_is_woo_ajax();

		$settings = wp_parse_args(
			$settings,
			array_merge(
				[
					'title' => '',
					'icon'  => '',
				],
				woodmart_get_elementor_products_config()
			)
		);

		$settings['carousel_js_inline'] = 'yes';
		$settings['force_not_ajax']     = 'yes';

		if ( $is_ajax ) {
			ob_start();
		}

		unset( $settings['title'] );

		?>
		<?php if ( ! $is_ajax ) : ?>
			<div class="wd-tab-content<?php echo woodmart_get_old_classes( ' woodmart-tab-content' ); ?>">
		<?php endif; ?>

		<?php echo woodmart_elementor_products_template( $settings ); ?>

		<?php if ( ! $is_ajax ) : ?>
			</div>
		<?php endif; ?>
		<?php

		if ( $is_ajax ) {
			return [
				'html' => ob_get_clean(),
			];
		}
	}
}
