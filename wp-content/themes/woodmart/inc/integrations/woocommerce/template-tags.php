<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

if ( ! function_exists( 'woodmart_font_icon_preload' ) ) {
	function woodmart_font_icon_preload() {
		$version = woodmart_get_theme_info( 'Version' );
		?>
			<?php if ( woodmart_get_opt( 'font_icon_woff_preload' ) ) : ?>
				<link rel="preload" as="font" href="<?php echo esc_url( WOODMART_THEME_DIR ); ?>/fonts/woodmart-font.woff?v=<?php echo esc_attr( $version ); ?>" type="font/woff" crossorigin>
			<?php endif; ?>

			<?php if ( woodmart_get_opt( 'font_icon_woff2_preload' ) ) : ?>
				<link rel="preload" as="font" href="<?php echo esc_url( WOODMART_THEME_DIR ); ?>/fonts/woodmart-font.woff2?v=<?php echo esc_attr( $version ); ?>" type="font/woff2" crossorigin>
			<?php endif; ?>
		<?php
	}

	add_action( 'wp_head', 'woodmart_font_icon_preload' );
}


/**
 * ------------------------------------------------------------------------------------------------
 * Custom function for product title
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woocommerce_template_loop_product_title' ) ) {
	function woocommerce_template_loop_product_title() {
		echo '<h3 class="wd-entities-title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h3>';
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Checkout steps in page title
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_checkout_steps' ) ) {
	function woodmart_checkout_steps() {

		?>
			<ul class="wd-checkout-steps<?php echo woodmart_get_old_classes( ' woodmart-checkout-steps' ); ?>">
				<li class="step-cart <?php echo (is_cart()) ? 'step-active' : 'step-inactive'; ?>">
					<a href="<?php echo esc_url( wc_get_cart_url() ); ?>">
						<span><?php esc_html_e('Shopping cart', 'woodmart'); ?></span>
					</a>
				</li>
				<li class="step-checkout <?php echo (is_checkout() && ! is_order_received_page()) ? 'step-active' : 'step-inactive'; ?>">
					<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>">
						<span><?php esc_html_e('Checkout', 'woodmart'); ?></span>
					</a>
				</li>
				<li class="step-complete <?php echo (is_order_received_page()) ? 'step-active' : 'step-inactive'; ?>">
					<span><?php esc_html_e('Order complete', 'woodmart'); ?></span>
				</li>
			</ul>
		<?php
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Custom thumbnail for category (wide items)
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_category_thumb_double_size' ) ) {
	function woodmart_category_thumb_double_size( $category ) {
		$small_thumbnail_size  	= apply_filters( 'subcategory_archive_thumbnail_size', 'woocommerce_thumbnail' );
		$dimensions    			= wc_get_image_size( $small_thumbnail_size );
		$thumbnail_id  			= get_term_meta( $category->term_id, 'thumbnail_id', true  );
		$attr_height 			= '';
		
		if( woodmart_loop_prop( 'double_size' ) ) {
			$small_thumbnail_size = 'woodmart_shop_catalog_x2';
			$dimensions['width'] *= 2;
			if ( $dimensions['height'] ) {
				$dimensions['height'] *= 2;
			}

			$attr_height = 'height="' . esc_attr( $dimensions['height'] ) . '"';
		}
		
		if ( $thumbnail_id ) {
			$image = wp_get_attachment_image_src( $thumbnail_id, $small_thumbnail_size  );
			$image = $image[0];
		} else {
			$image = wc_placeholder_img_src();
		}
		
		if ( $image ) {
			// Prevent esc_url from breaking spaces in urls for image embeds
			// Ref: https://core.trac.wordpress.org/ticket/23605
			$image = str_replace( ' ', '%20', $image );
			
			echo apply_filters('woodmart_attachment', '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" ' . $attr_height . ' />', $thumbnail_id, $small_thumbnail_size );
		}
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Product deal countdown
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_product_sale_countdown' ) ) {
	function woodmart_product_sale_countdown() {
		global $product;
        $sale_date_end = get_post_meta( $product->get_id(), '_sale_price_dates_to', true );
		$sale_date_start = get_post_meta( $product->get_id(), '_sale_price_dates_from', true );

		if ( ( apply_filters( 'woodmart_sale_countdown_variable', false ) || woodmart_get_opt( 'sale_countdown_variable' ) ) && $product->get_type() == 'variable' ) {
			// Variations cache
			$cache          = apply_filters( 'woodmart_countdown_variable_cache', true );
			$transient_name = 'woodmart_countdown_variable_cache_' . $product->get_id();
			$available_variations = array();
			
			if ( $cache ) {
				$available_variations = get_transient( $transient_name );
			}
			
			if ( ! $available_variations ) {
				$available_variations = $product->get_available_variations();
				if ( $cache ) {
					set_transient( $transient_name, $available_variations, apply_filters( 'woodmart_countdown_variable_cache_time', WEEK_IN_SECONDS ) );
				}
			}
			
			if ( $available_variations ) {
				$sale_date_end = get_post_meta( $available_variations[0]['variation_id'], '_sale_price_dates_to', true );
				$sale_date_start = get_post_meta( $available_variations[0]['variation_id'], '_sale_price_dates_from', true );
			}
		}

		$curent_date = strtotime( date( 'Y-m-d H:i:s' ) );

		if ( $sale_date_end < $curent_date || $curent_date < $sale_date_start ) return;

        $timezone = 'GMT';

        if ( apply_filters( 'woodmart_wp_timezone', false ) ) $timezone = wc_timezone_string();

		woodmart_enqueue_js_library( 'countdown-bundle' );
		woodmart_enqueue_js_script( 'countdown-element' );
		woodmart_enqueue_inline_style( 'countdown' );
       
		echo '<div class="wd-product-countdown wd-timer' . woodmart_get_old_classes( ' woodmart-product-countdown woodmart-timer' ) . '" data-end-date="' . esc_attr( date( 'Y-m-d H:i:s', $sale_date_end ) ) . '" data-timezone="' . $timezone . '"></div>';
	}
}

if ( ! function_exists( 'woodmart_clear_countdown_variable_cache' ) ) {
	function woodmart_clear_countdown_variable_cache( $post_id ) {
		if ( ! apply_filters( 'woodmart_countdown_variable_cache', true ) ) {
			return;
		}
		
		$transient_name = 'woodmart_countdown_variable_cache_' . $post_id;
		
		delete_transient( $transient_name );
	}
	
	add_action( 'save_post', 'woodmart_clear_countdown_variable_cache' );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Hover image
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_hover_image' ) ) {
	function woodmart_hover_image() {
		global $product;
	
		$attachment_ids = $product->get_gallery_image_ids();

		$hover_image = '';

		if ( ! empty( $attachment_ids[0] ) ) {
			$hover_image = woodmart_get_product_thumbnail( 'woocommerce_thumbnail', $attachment_ids[0] );
		}

		if( $hover_image != '' && woodmart_get_opt( 'hover_image' ) ): ?>
			<div class="hover-img">
				<a href="<?php echo esc_url( get_permalink() ); ?>">
					<?php echo woodmart_get_product_thumbnail( 'woocommerce_thumbnail', $attachment_ids[0] ); ?>
				</a>
			</div>
		<?php endif;

	}
}


if( ! function_exists( 'woodmart_products_nav' ) ) {
	function woodmart_products_nav() {
		$in_same_term = apply_filters( 'woodmart_get_prev_product_same_term', false );
		$next = woodmart_get_next_product( $in_same_term );
		$prev = woodmart_get_previous_product( $in_same_term );

		?>
			<div class="wd-products-nav<?php echo woodmart_get_old_classes( ' woodmart-products-nav' ); ?>">
				<?php if ( $prev ): ?>
				<div class="product-btn product-prev">
					<a href="<?php echo esc_url( $prev->get_permalink() ); ?>"><?php esc_html_e('Previous product', 'woodmart'); ?><span class="product-btn-icon"></span></a>
					<div class="wrapper-short">
						<div class="product-short">
							<div class="product-short-image">
								<a href="<?php echo esc_url( $prev->get_permalink() ); ?>" class="product-thumb">
									<?php echo apply_filters( 'woodmart_products_nav_image', $prev->get_image() );?>
								</a>
							</div>
							<div class="product-short-description">
								<a href="<?php echo esc_url( $prev->get_permalink() ); ?>" class="wd-entities-title">
									<?php echo $prev->get_title(); ?>
								</a>
								<span class="price">
									<?php echo wp_kses_post( $prev->get_price_html() ); ?>
								</span>
							</div>
						</div>
					</div>
				</div>
				<?php endif ?>

				<a href="<?php echo apply_filters( 'woodmart_single_product_back_btn_url', get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="wd-back-btn<?php echo woodmart_get_old_classes( ' woodmart-back-btn' ); ?>">
					<span>
						<?php esc_html_e('Back to products', 'woodmart') ?>
					</span>
				</a>

				<?php if ( $next ): ?>
				<div class="product-btn product-next">
					<a href="<?php echo esc_url( $next->get_permalink() ); ?>"><?php esc_html_e('Next product', 'woodmart'); ?><span class="product-btn-icon"></span></a>
					<div class="wrapper-short">
						<div class="product-short">
							<div class="product-short-image">
								<a href="<?php echo esc_url( $next->get_permalink() ); ?>" class="product-thumb">
									<?php echo apply_filters( 'woodmart_products_nav_image', $next->get_image() ); ?>
								</a>
							</div>
							<div class="product-short-description">
								<a href="<?php echo esc_url( $next->get_permalink() ); ?>" class="wd-entities-title">
									<?php echo $next->get_title(); ?>
								</a>
								<span class="price">
									<?php echo wp_kses_post( $next->get_price_html() ); ?>
								</span>
							</div>
						</div>
					</div>
				</div>
				<?php endif ?>
			</div>
		<?php
	}
}


add_action('woocommerce_before_main_content', 'woodmart_woo_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'woodmart_woo_wrapper_end', 10);

if( ! function_exists( 'woodmart_woo_wrapper_start' ) ) {
	function woodmart_woo_wrapper_start() {
		$content_class = woodmart_get_content_class();
		$breadcrumbs_position = woodmart_get_opt( 'single_breadcrumbs_position' );
		$product_design = woodmart_get_opt( 'product_design' );

		if ( is_singular( 'product' ) ) {
			$content_class = 'col-12';
			if ( $breadcrumbs_position == 'default' && $product_design == 'default' ) {
				$breadcrumbs_position = 'summary';
			} elseif ( $breadcrumbs_position == 'default' && $product_design == 'alt' ) {
				$breadcrumbs_position = 'below_header';
			}
			$content_class .= ' breadcrumbs-location-' . $breadcrumbs_position;
		} else {
			$content_class .= ' description-area-' . woodmart_get_opt( 'cat_desc_position' );
		}

        if ( have_posts() ) {
			$content_class .= ' content-with-products';
        } else {
			$content_class .= ' content-without-products';
		}

		echo '<div class="site-content shop-content-area ' . $content_class . '" role="main">';
	}
}


if( ! function_exists( 'woodmart_woo_wrapper_end' ) ) {
	function woodmart_woo_wrapper_end() {
		echo '</div>';
	}
}


/**
 * ------------------------------------------------------------------------------------------------
 * My account sidebar
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_before_my_account_navigation' ) ) {
	function woodmart_before_my_account_navigation() {
		echo '<div class="wd-my-account-sidebar' . woodmart_get_old_classes( ' woodmart-my-account-sidebar' ) . '">';
		if(!function_exists('woodmart_my_account_title')) {
			?>
				<h3 class="woocommerce-MyAccount-title entry-title">
					<?php echo get_the_title( wc_get_page_id( 'myaccount' ) ); ?>
				</h3>
			<?php
		}
	}

	add_action( 'woocommerce_account_navigation', 'woodmart_before_my_account_navigation', 5 );
}

if( ! function_exists( 'woodmart_after_my_account_navigation' ) ) {
	function woodmart_after_my_account_navigation() {
		$sidebar_name = 'sidebar-my-account';
		if ( is_active_sidebar( $sidebar_name ) ) : ?>
			<aside class="sidebar-container" role="complementary">
				<div class="widget-area">
					<?php dynamic_sidebar( $sidebar_name ); ?>
				</div><!-- .widget-area -->
			</aside><!-- .sidebar-container -->
		<?php endif;
		echo '</div><!-- .wd-my-account-sidebar -->';
	}

	add_action( 'woocommerce_account_navigation', 'woodmart_after_my_account_navigation', 30 );
}


/**
 * ------------------------------------------------------------------------------------------------
 * Extra content block
 * ------------------------------------------------------------------------------------------------
 */


if( ! function_exists( 'woodmart_product_extra_content' ) ) {
	function woodmart_product_extra_content( $tabs ) { 
		$extra_block = get_post_meta(get_the_ID(),  '_woodmart_extra_content', true );
		echo '<div class="product-extra-content">' . woodmart_html_block_shortcode( array( 'id' => $extra_block ) ) . '</div>';
	}
}
	
/**
 * ------------------------------------------------------------------------------------------------
 * Product video, zoom buttons
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_product_video_button' ) ) {
	function woodmart_product_video_button() {
		$video_url = get_post_meta(get_the_ID(),  '_woodmart_product_video', true );

		woodmart_enqueue_js_library( 'magnific' );
		woodmart_enqueue_js_script( 'product-video' );
		woodmart_enqueue_inline_style( 'mfp-popup' );
		?>
			<div class="product-video-button wd-gallery-btn">
				<a href="<?php echo esc_url( $video_url ); ?>"><span><?php esc_html_e('Watch video', 'woodmart'); ?></span></a>
			</div>
		<?php
	}
}

if( ! function_exists( 'woodmart_product_zoom_button' ) ) {
	function woodmart_product_zoom_button() {
		woodmart_enqueue_js_library( 'photoswipe-bundle' );
		woodmart_enqueue_inline_style( 'photoswipe' );
		woodmart_enqueue_js_script( 'product-images' );
		?>
			<div class="wd-show-product-gallery-wrap  wd-gallery-btn"><a href="#" rel="noffollow" class="woodmart-show-product-gallery"><span><?php esc_html_e('Click to enlarge', 'woodmart'); ?></span></a></div>
		<?php
	}
}

if( ! function_exists( 'woodmart_additional_galleries_open' ) ) {
	function woodmart_additional_galleries_open() {
		?>
			<div class="product-additional-galleries">
		<?php
	}
}

if( ! function_exists( 'woodmart_additional_galleries_close' ) ) {
	function woodmart_additional_galleries_close() {
		?>
			</div>
		<?php
	}
}


/**
 * ------------------------------------------------------------------------------------------------
 * Single product share buttons
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_product_share_buttons' ) ) {
	function woodmart_product_share_buttons() {
		$type = woodmart_get_opt( 'product_share_type' );
		?>
			<?php if ( woodmart_is_social_link_enable( $type ) ): ?>
				<div class="product-share">
					<span class="share-title"><?php echo 'share' === $type ? esc_html__('Share', 'woodmart') :  esc_html__('Follow', 'woodmart'); ?></span>
					<?php echo woodmart_shortcode_social( array( 'type' => $type, 'size' => 'small' ) ); ?>
				</div>
			<?php endif ?>
		<?php
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Instagram by hashtag for products
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_product_instagram' ) ) {
	function woodmart_product_instagram() {
		$hashtag = get_post_meta(get_the_ID(),  '_woodmart_product_hashtag', true );
		if( empty( $hashtag ) ) return;
		?>
			<div class="wd-product-instagram">
				<p class="product-instagram-intro"><?php printf( wp_kses( __('Tag your photos with <span>%s</span> on Instagram.', 'woodmart') , array('span' => array())), $hashtag ); ?></p>
				<?php echo woodmart_shortcode_instagram( array(
					'username' => esc_html( $hashtag ),
					'number' => 8,
					'size' => 'medium',
					'target' => '_blank',
					'spacing' => 0,
					'rounded' => 0,
					'per_row' => 4,
					'data_source' => 'ajax'
				) ); ?>
			</div>
		<?php
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Filters buttons
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_filter_buttons' ) ) {
	function woodmart_filter_buttons() {
		$filters_type = woodmart_get_opt( 'shop_filters_type' ) ? woodmart_get_opt( 'shop_filters_type' ) : 'widgets';
		$always_open = woodmart_get_opt( 'shop_filters_always_open' );

		if ( ( ! woocommerce_products_will_display() && $filters_type == 'widgets' ) || $always_open || ( $filters_type == 'content' && ! woodmart_get_opt( 'shop_filters_content' ) ) ) return;

		?>
			<div class="wd-filter-buttons wd-action-btn wd-style-text wd-filter-icon<?php echo woodmart_get_old_classes( ' woodmart-filter-buttons' ); ?>">
				<a href="#" rel="noffollow" class="open-filters"><?php esc_html_e('Filters', 'woodmart'); ?></a>
			</div>
		<?php
	}
}

if( ! function_exists( 'woodmart_sorting_widget' ) ) {
	function woodmart_sorting_widget() {
		$filter_widget_class = woodmart_get_widget_column_class( 'filters-area' );
		the_widget( 'WOODMART_Widget_Sorting', array( 'title' => esc_html__('Sort by', 'woodmart') ), array(							
			'before_widget' => '<div id="WOODMART_Widget_Sorting" class="wd-widget widget filter-widget ' . esc_attr( $filter_widget_class ) . ' woodmart-woocommerce-sort-by">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5 class="widget-title">',
			'after_title'   => '</h5>') 
		);
	}
}

if( ! function_exists( 'woodmart_price_widget' ) ) {
	function woodmart_price_widget() {
		$filter_widget_class = woodmart_get_widget_column_class( 'filters-area' );
		the_widget( 'WOODMART_Widget_Price_Filter', array( 'title' => esc_html__('Price filter', 'woodmart') ), array(							
			'before_widget' => '<div id="WOODMART_Widget_Price_Filter" class="wd-widget widget filter-widget ' . esc_attr( $filter_widget_class ) . ' woodmart-price-filter">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5 class="widget-title">',
			'after_title'   => '</h5>') 
		);
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Empty cart text
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_empty_cart_text' ) ) {
	add_action( 'woocommerce_cart_is_empty', 'woodmart_empty_cart_text', 20 );

	function woodmart_empty_cart_text() {
		$empty_cart_text = woodmart_get_opt( 'empty_cart_text' );

		if( ! empty( $empty_cart_text ) ) {
			?>
				<div class="wd-empty-page-text"><?php echo wp_kses( $empty_cart_text, array('p' => array(), 'h1' => array(), 'h2' => array(), 'h3' => array(), 'strong' => array(), 'em' => array(), 'span' => array(), 'div' => array() , 'br' => array()) ); ?></div>
			<?php
		}
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Wrap shop tables with divs
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_open_table_wrapper_div' ) ) {
	function woodmart_open_table_wrapper_div() {
		echo '<div class="wd-table-wrapper">';
	}
	add_action( 'woocommerce_checkout_order_review', 'woodmart_open_table_wrapper_div', 7 );
}


if( ! function_exists( 'woodmart_close_table_wrapper_div' ) ) {
	function woodmart_close_table_wrapper_div() {
		echo '</div><!-- .wd-table-wrapper -->';
	}
	add_action( 'woocommerce_checkout_order_review', 'woodmart_close_table_wrapper_div', 13 );
}

// **********************************************************************// 
// ! Items per page select on the shop page
// **********************************************************************// 

if( ! function_exists( 'woodmart_show_sidebar_btn' ) ) {
	
	add_action( 'woocommerce_before_shop_loop', 'woodmart_show_sidebar_btn', 25 );

	function woodmart_show_sidebar_btn() {
		if ( wc_get_loop_prop( 'is_shortcode' ) || ! wc_get_loop_prop( 'is_paginated' ) || ( ! woodmart_get_opt( 'shop_hide_sidebar' ) && ! woodmart_get_opt( 'shop_hide_sidebar_tablet' ) && ! woodmart_get_opt( 'shop_hide_sidebar_desktop' ) && ! woodmart_get_opt( 'hide_main_sidebar_mobile' ) ) ) {
			return;
		}

		woodmart_enqueue_js_script( 'hidden-sidebar' );

		?>
			<div class="wd-show-sidebar-btn wd-action-btn wd-style-text wd-menu-icon<?php echo woodmart_get_old_classes( ' woodmart-show-sidebar-btn' ); ?>">
				<a href="#" rel="noffollow"><?php esc_html_e('Show sidebar', 'woodmart'); ?></span></a>
			</div>
		<?php

	}
}

if ( ! function_exists( 'woodmart_products_per_page_select' ) ) {
	function woodmart_products_per_page_select( $is_element = false ) {
		if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() || ! woodmart_get_opt( 'per_page_links' ) ) {
			return;
		}

		$per_page_options          = woodmart_get_opt( 'per_page_options' );
		$products_per_page_options = ( ! empty( $per_page_options ) ) ? explode( ',', $per_page_options ) : array( 12, 24, 36, -1 );

		?>

		<div class="wd-products-per-page<?php echo woodmart_get_old_classes( ' woodmart-products-per-page' ); ?>">
			<span class="per-page-title">
				<?php esc_html_e( 'Show', 'woodmart' ); ?>
			</span>

			<?php foreach ( $products_per_page_options as $key => $value ) : ?>
				<?php
				$classes = '';
				$args    = array(
					'per_page' => $value,
				);

				if ( $is_element ) {
					$args['shortcode'] = true;
				}

				$link = add_query_arg( $args, woodmart_shop_page_link( true ) );

				if ( ( (int) woodmart_get_products_per_page() === (int) $value && ! $is_element ) || ( $is_element && isset( $_GET['per_page'] ) && $_GET['per_page'] === $value ) ) { // phpcs:ignore
					$classes .= ' current-variation';
				}
				?>
				<a rel="nofollow noopener" href="<?php echo esc_url( $link ); ?>" class="per-page-variation<?php echo esc_attr( $classes ); ?>">
					<span>
						<?php
						$text = '%s';
						esc_html( printf( $text, $value == -1 ? esc_html__( 'All', 'woodmart' ) : $value ) );
						?>
					</span>
				</a>
				<span class="per-page-border"></span>
			<?php endforeach; ?>
		</div>
		<?php
	}

	add_action( 'woocommerce_before_shop_loop', 'woodmart_products_per_page_select', 25 );
}

if ( ! function_exists( 'woodmart_products_view_select' ) ) {
	function woodmart_products_view_select( $is_element = false ) {
		if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
			return;
		}

		$shop_view                = woodmart_get_opt( 'shop_view' );
		$per_row_selector         = woodmart_get_opt( 'per_row_columns_selector' );
		$per_row_options          = woodmart_get_opt( 'products_columns_variations' );
		$current_shop_view        = woodmart_get_shop_view();
		$current_per_row          = woodmart_get_products_columns_per_row();
		$products_per_row_options = ( ! empty( $per_row_options ) ) ? array_unique( $per_row_options ) : array( 2, 3, 4 );

		if ( 'list' === $shop_view || ( 'grid' === $shop_view && ! $per_row_selector ) || ( 'grid' === $shop_view && ! $per_row_options ) ) {
			return;
		}

		?>
		<div class="wd-products-shop-view<?php echo woodmart_get_old_classes( ' woodmart-products-shop-view' ); ?> <?php echo esc_attr( 'products-view-' . $shop_view ); ?>">
			<?php if ( 'grid' !== $shop_view ) : ?>
				<?php
				$classes = '';
				$args    = array(
					'shop_view' => 'list',
				);

				if ( $is_element ) {
					$args['shortcode'] = true;
				}

				if ( ( 'list' === $current_shop_view && ! $is_element ) || ( $is_element && isset( $_GET['shop_view'] ) && 'list' === $_GET['shop_view'] ) ) { // phpcs:ignore
					$classes .= ' current-variation';
				}

				$link = add_query_arg( $args, woodmart_shop_page_link( true ) );
				?>

				<a rel="nofollow noopener" href="<?php echo esc_url( $link ); ?>" class="shop-view per-row-list<?php echo esc_attr( $classes ); ?>">
					<?php echo woodmart_get_svg_content( 'list-style' ); // phpcs:ignore ?>
				</a>
			<?php endif ?>

			<?php if ( $per_row_selector && $per_row_options ) : ?>
				<?php foreach ( $products_per_row_options as $key => $value ) : ?>
					<?php
					if ( 0 === $value ) {
						continue;
					}

					$classes = '';
					$args    = array(
						'shop_view' => 'grid',
						'per_row'   => $value,
					);

					if ( $is_element ) {
						$args['shortcode'] = true;
					}

					if ( 'list' !== $current_shop_view && ( (int) $value === (int) $current_per_row && ! $is_element ) || ( $is_element && isset( $_GET['per_row'] ) && $_GET['per_row'] === $value ) ) { // phpcs:ignore
						$classes .= ' current-variation';
					}

					$classes .= ' per-row-' . $value;

					$link = add_query_arg( $args, woodmart_shop_page_link( true ) );
					?>

					<a rel="nofollow noopener" href="<?php echo esc_url( $link ); ?>" class="shop-view<?php echo esc_attr( $classes ); ?>">
						<?php echo woodmart_get_svg_content( 'column-' . $value ); // phpcs:ignore ?>
					</a>
				<?php endforeach; ?>
			<?php endif ?>
		</div>
		<?php
	}

	add_action( 'woocommerce_before_shop_loop', 'woodmart_products_view_select', 27 );
}


/**
 * ------------------------------------------------------------------------------------------------
 * Display categories menu
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_product_categories_nav' ) ) {
	function woodmart_product_categories_nav() {
		global $wp_query, $post;

		$show_subcategories = woodmart_get_opt( 'shop_categories_ancestors' );
		$show_categories_neighbors = woodmart_get_opt( 'show_categories_neighbors' );

		$list_args = array(  
			'taxonomy' => 'product_cat', 
			'hide_empty' => woodmart_get_opt( 'shop_page_title_hide_empty_categories', false ),
		);

		$order_by = apply_filters( 'woodmart_product_categories_nav_order_by', 'menu_order' );
		$order = apply_filters( 'woodmart_product_categories_nav_order', 'asc' );

		if ( 'menu_order' === $order_by ) {
			$list_args['menu_order'] = false;
			$list_args['menu_order'] = $order;
		} else {
			$list_args['order'] = $order;
			$list_args['orderby'] = $order_by;
		}

		// Setup Current Category
		$current_cat   = false;
		$cat_ancestors = array();

		if ( is_tax( 'product_cat' ) ) {
			$current_cat   = $wp_query->queried_object;
			$cat_ancestors = get_ancestors( $current_cat->term_id, 'product_cat' );
		}

		$list_args['depth']            = 5;
		$list_args['child_of']         = 0;
		$list_args['title_li']         = '';
		$list_args['hierarchical']     = 1;
		$list_args['show_count']       = woodmart_get_opt( 'shop_products_count' );
		$list_args['walker'] = new WOODMART_Walker_Category();

		$class = ( woodmart_get_opt( 'shop_products_count' ) ) ? 'has-product-count' : 'hasno-product-count';
		$class .= woodmart_get_old_classes( ' woodmart-product-categories' );

		if ( woodmart_get_opt( 'shop_page_title_categories_exclude' ) ) {
			$list_args['exclude'] = woodmart_get_opt( 'shop_page_title_categories_exclude' );
		}

		if ( woodmart_is_shop_on_front() ) {
			$shop_link = home_url();
		} else {
			$shop_link = get_post_type_archive_link( 'product' );
		}

		include_once( WC()->plugin_path() . '/includes/walkers/class-product-cat-list-walker.php' );
		
		if( is_object( $current_cat ) && !get_term_children( $current_cat->term_id, 'product_cat' ) && $show_subcategories && !$show_categories_neighbors ) {
			return;
		}

		woodmart_enqueue_js_script( 'categories-menu' );

		echo '<div class="wd-btn-show-cat wd-action-btn wd-style-text wd-menu-icon' . woodmart_get_old_classes( ' woodmart-show-categories' ) . '"><a href="#" rel="noffollow">' . esc_html__('Categories', 'woodmart') . '</a></div>';

		echo '<ul class="wd-nav-product-cat wd-nav wd-style-underline ' . esc_attr( $class ). '">';
		
		echo '<li class="cat-link shop-all-link"><a class="category-nav-link" href="' . esc_url( $shop_link ) . '">
				<span class="nav-link-summary' . woodmart_get_old_classes( ' category-summary' ) . '">
					<span class="nav-link-text' . woodmart_get_old_classes( ' category-name' ) . '">' . esc_html__('All', 'woodmart') . '</span>
					<span class="nav-link-count' . woodmart_get_old_classes( ' category-products-count' ) . '">' . esc_html__('products', 'woodmart') . '</span>
				</span>
		</a></li>';

		if( $show_subcategories ) {
			woodmart_show_category_ancestors();
		} else {
			wp_list_categories( $list_args );
		}

		echo '</ul>';
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Display ancestors of current category
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_show_category_ancestors' )) {
	function woodmart_show_category_ancestors() {
		global $wp_query, $post;

		$current_cat   = false;
		$list_args = array();

		$show_categories_neighbors = woodmart_get_opt( 'show_categories_neighbors' );

		if ( is_tax('product_cat') ) {
			$current_cat   = $wp_query->queried_object;
		}

		$list_args = array( 'taxonomy' => 'product_cat', 'hide_empty' => woodmart_get_opt( 'shop_page_title_hide_empty_categories', false ) );

		// Show Siblings and Children Only
		if ( $current_cat ) {

			// Direct children are wanted
			$include = get_terms(
				'product_cat',
				array(
					'fields'       => 'ids',
					'parent'       => $current_cat->term_id,
					'hierarchical' => true,
					'hide_empty'   => false
				)
			);

			$list_args['include'] = implode( ',', $include );

			if ( empty( $include ) && !$show_categories_neighbors ) {
				return;
			}

			if ( $show_categories_neighbors ) {
				if ( get_term_children( $current_cat->term_id, 'product_cat' ) ) {
					$list_args['child_of'] = $current_cat->term_id;
				}elseif( $current_cat->parent != 0 ){
					$list_args['child_of'] = $current_cat->parent;
				}
			}
		}

		$list_args['depth']                      = 1;
		$list_args['hierarchical']               = 1;
		$list_args['title_li']                   = '';
		$list_args['pad_counts']                 = 1;
		$list_args['show_option_none']           = esc_html__('No product categories exist.', 'woodmart' );
		$list_args['current_category']           = ( $current_cat ) ? $current_cat->term_id : '';
		$list_args['show_count']       			 = woodmart_get_opt( 'shop_products_count' );
		$list_args['walker']					 = new WOODMART_Walker_Category();
		
		$order_by = apply_filters( 'woodmart_product_categories_nav_order_by', 'menu_order' );
		$order = apply_filters( 'woodmart_product_categories_nav_order', 'asc' );
		
		if ( 'menu_order' === $order_by ) {
			$list_args['menu_order'] = false;
			$list_args['menu_order'] = $order;
		} else {
			$list_args['order'] = $order;
			$list_args['orderby'] = $order_by;
		}

		wp_list_categories( $list_args );
	}
}

if( ! class_exists( 'WOODMART_Walker_Category' ) ) {
	class WOODMART_Walker_Category extends Walker_Category {
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			if ( 'list' !== $args['style'] ) {
				return;
			}

			$indent  = str_repeat( "\t", $depth );
			$output .= $indent . '<ul class="children wd-dropdown wd-dropdown-menu wd-design-default wd-sub-menu' . woodmart_get_old_classes( ' sub-menu' ) . '">';
		}

		public function end_lvl( &$output, $depth = 0, $args = array() ) {
			if ( 'list' !== $args['style'] ) {
				return;
			}

			$indent  = str_repeat( "\t", $depth );
			$output .= $indent . '</ul>';
		}

		public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
			/** This filter is documented in wp-includes/category-template.php */
			$cat_name = apply_filters(
				'list_cats',
				esc_attr( $category->name ),
				$category
			);

			// Don't generate an element if the category name is empty.
			if ( ! $cat_name ) {
				return;
			}

			$link = '<a class="category-nav-link" href="' . esc_url( get_term_link( $category ) ) . '" ';

			$link .= '>';


			$icon_url = get_term_meta( $category->term_id, 'category_icon', true );

			if( ! empty( $icon_url ) ) {
				$link .= '<img src="' . esc_url( $icon_url ) . '" alt="' . esc_attr( $category->cat_name ) . '" class="wd-nav-img' . woodmart_get_old_classes( ' category-icon' ) . '" />';
			}

			$link .= '<span class="nav-link-summary' . woodmart_get_old_classes( ' category-summary' ) . '">';
			$link .= '<span class="nav-link-text' . woodmart_get_old_classes( ' category-name' ) . '">' . $cat_name . '</span>';

			if ( ! empty( $args['show_count'] ) ) {
				$link .= '<span class="nav-link-count' . woodmart_get_old_classes( ' category-products-count' ) . '">' . number_format_i18n( $category->count ) . ' ' . _n( 'product', 'products', $category->count, 'woodmart' ) . '</span>';
			}

			$link .= '</span>';
			$link .= '</a>';
			
			if ( 'list' == $args['style'] ) {
				$default_cat = get_option( 'default_product_cat' );
				$output .= "\t<li";
				$css_classes = array(
					'cat-item',
					'cat-item-' . $category->term_id,
					( $category->term_id == $default_cat && apply_filters( 'woodmart_wc_default_product_cat', false ) ? 'wc-default-cat wd-display-none' : '')
				);

				if ( $args['walker']->has_children ) {
					$css_classes[] = 'wd-event-hover';
				}

				if ( ! empty( $args['current_category'] ) ) {
					// 'current_category' can be an array, so we use `get_terms()`.
					$_current_terms = get_terms( $category->taxonomy, array(
						'include' => $args['current_category'],
						'hide_empty' => false,
					) );

					foreach ( $_current_terms as $_current_term ) {
						if ( $category->term_id == $_current_term->term_id ) {
							$css_classes[] = 'wd-active';
						} elseif ( $category->term_id == $_current_term->parent ) {
							$css_classes[] = 'current-cat-parent';
						}
						while ( $_current_term->parent ) {
							if ( $category->term_id == $_current_term->parent ) {
								$css_classes[] =  'current-cat-ancestor';
								break;
							}
							$_current_term = get_term( $_current_term->parent, $category->taxonomy );
						}
					}
				}

				/**
				 * Filter the list of CSS classes to include with each category in the list.
				 *
				 * @since 4.2.0
				 *
				 * @see wp_list_categories()
				 *
				 * @param array  $css_classes An array of CSS classes to be applied to each list item.
				 * @param object $category    Category data object.
				 * @param int    $depth       Depth of page, used for padding.
				 * @param array  $args        An array of wp_list_categories() arguments.
				 */
				$css_classes = implode( ' ', apply_filters( 'category_css_class', $css_classes, $category, $depth, $args ) );

				$output .=  ' class="' . $css_classes . '"';
				$output .= ">$link\n";
			} elseif ( isset( $args['separator'] ) ) {
				$output .= "\t$link" . $args['separator'] . "\n";
			} else {
				$output .= "\t$link<br />\n";
			}
		}
	}
}

if ( ! class_exists( 'WOODMART_WC_Product_Cat_List_Walker' ) && function_exists('WC') ) :

include_once( WC()->plugin_path() . '/includes/walkers/class-product-cat-list-walker.php' );

class WOODMART_WC_Product_Cat_List_Walker extends WC_Product_Cat_List_Walker {

	/**
	 * Start the element output.
	 *
	 * @see Walker::start_el()
	 * @since 2.1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of category in reference to parents.
	 * @param integer $current_object_id
	 */
	public function start_el( &$output, $cat, $depth = 0, $args = array(), $current_object_id = 0 ) {
		$output .= '<li class="cat-item cat-item-' . $cat->term_id;

		if ( $args['current_category'] == $cat->term_id ) {
			$output .= ' current-cat';
		}

		if ( $args['has_children'] && $args['hierarchical'] ) {
			$output .= ' cat-parent';
		}

		if ( $args['current_category_ancestors'] && $args['current_category'] && in_array( $cat->term_id, $args['current_category_ancestors'] ) ) {
			$output .= ' current-cat-parent';
		}

		$output .=  '"><a href="' . get_term_link( (int) $cat->term_id, $this->tree_type ) . '">' . $cat->name . '</a>';

		if ( $args['show_count'] ) {
			$output .= ' <span class="count">' . $cat->count . '</span>';
		}
	}
}

endif;

/**
 * ------------------------------------------------------------------------------------------------
 * Show product categories
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_product_categories' ) ) {
	function woodmart_product_categories() {
		global $product;

		if ( ! woodmart_get_opt( 'categories_under_title' ) ) {
			return;
		}

		$terms  = get_the_terms( $product->get_id(), 'product_cat' );
		$parent = array();
		$child  = array();
		$links  = array();

		foreach ( $terms as $term ) {
			if ( ! $term->parent ) {
				$parent[ $term->term_id ] = $term->name;
			}
		}

		foreach ( $terms as $term ) {
			if ( $term->parent ) {
				unset( $parent[ $term->parent ] );
				$child[ $term->parent ]  = get_term( $term->parent )->name;
				$child[ $term->term_id ] = $term->name;
			}
		}

		$terms = $child + $parent;

		foreach ( $terms as $key => $value ) {
			$links[] = '<a href="' . esc_url( get_term_link( $key ) ) . '" rel="tag">' . esc_html( $value ) . '</a>';
		}

		?>
		<div class="wd-product-cats<?php echo woodmart_get_old_classes( ' woodmart-product-cats' ); ?>">
			<?php echo implode( ', ', $links ); // phpcs:ignore ?>
		</div>
		<?php
	}
}


if( ! function_exists( 'woodmart_view_product_button' ) ) {
	function woodmart_view_product_button() {
		echo '<a href="' . get_permalink() . '" class="view-details-btn">' . esc_html__('View details', 'woodmart') . '</a>';
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Function returns numbers of items in the cart. Filter woocommerce fragments
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_cart_data' ) ) {
	add_filter('woocommerce_add_to_cart_fragments', 'woodmart_cart_data', 30);
	function woodmart_cart_data( $array ) {
		ob_start();
		woodmart_cart_count();
		$count = ob_get_clean();
		
		ob_start();
		woodmart_cart_subtotal();
		$subtotal = ob_get_clean();
		
		$array['span.wd-cart-number'] = $count;
		$array['span.wd-cart-subtotal'] = $subtotal;
		
		return $array;
	}
}

if( ! function_exists( 'woodmart_cart_count' ) ) {
	function woodmart_cart_count() {
		$count = WC()->cart->get_cart_contents_count();
		?>
			<span class="wd-cart-number<?php echo woodmart_get_old_classes( ' woodmart-cart-number' ); ?>"><?php echo esc_html($count); ?> <span><?php echo esc_html( _n( 'item', 'items', $count, 'woodmart' ) ); ?></span></span>
		<?php
	}
}

if( ! function_exists( 'woodmart_cart_subtotal' ) ) {
	function woodmart_cart_subtotal() {
		?>
			<span class="wd-cart-subtotal<?php echo woodmart_get_old_classes( ' woodmart-cart-subtotal' ); ?>"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
		<?php
	}
}


/**
 * ------------------------------------------------------------------------------------------------
 * Woodmart product label
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_product_label' ) ) {
	function woodmart_product_label() {
		global $product;

		$output = array();

		$product_attributes = woodmart_get_product_attributes_label();
		$percentage_label = woodmart_get_opt( 'percentage_label' );

		if ( $product->is_on_sale() ) {

			$percentage = '';

			if ( $product->get_type() == 'variable' && $percentage_label ) {

				$available_variations = $product->get_variation_prices();
				$max_percentage = 0;

				foreach( $available_variations['regular_price'] as $key => $regular_price ) {
					$sale_price = $available_variations['sale_price'][$key];

					if ( $sale_price < $regular_price ) {
						$percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );

						if ( $percentage > $max_percentage ) {
							$max_percentage = $percentage;
						}
					}
				}

				$percentage = $max_percentage;
			} elseif ( ( $product->get_type() == 'simple' || $product->get_type() == 'external' || $product->get_type() == 'variation' ) && $percentage_label ) {
				$percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
			}

			if ( $percentage ) {
				$output[] = '<span class="onsale product-label">-' . $percentage . '%' . '</span>';
			}else{
				$output[] = '<span class="onsale product-label">' . esc_html__( 'Sale', 'woodmart' ) . '</span>';
			}
		}
		
		if( !$product->is_in_stock() ){
			$output[] = '<span class="out-of-stock product-label">' . esc_html__( 'Sold out', 'woodmart' ) . '</span>';
		}

		if ( $product->is_featured() && woodmart_get_opt( 'hot_label' ) ) {
			$output[] = '<span class="featured product-label">' . esc_html__( 'Hot', 'woodmart' ) . '</span>';
		}
		
		if ( woodmart_get_opt( 'new_label' ) && woodmart_is_new_label_needed( get_the_ID() ) ) {
			$output[] = '<span class="new product-label">' . esc_html__( 'New', 'woodmart' ) . '</span>';
		}
		
		if ( $product_attributes ) {
			foreach ( $product_attributes as $attribute ) {
				$output[] = $attribute;
			}
		}
		
		if ( $output ) {
			echo '<div class="product-labels labels-' . woodmart_get_opt( 'label_shape' ) . '">' . implode( '', $output ) . '</div>';
		}
	}
}
add_filter( 'woocommerce_sale_flash', 'woodmart_product_label', 10 );

/**
 * ------------------------------------------------------------------------------------------------
 * Woodmart my account links
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_my_account_links' ) ) {
	function woodmart_my_account_links() {
		if ( !woodmart_get_opt( 'my_account_links' ) ) return;
		?>
		<div class="wd-my-account-links<?php echo woodmart_get_old_classes( ' woodmart-my-account-links' ); ?>">
			<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
				<div class="<?php echo esc_attr( $endpoint ); ?>-link">
					<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
	add_action( 'woocommerce_account_dashboard', 'woodmart_my_account_links', 10 );
}

/**
 * ------------------------------------------------------------------------------------------------
 * My account wrapper
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_my_account_wrapp_start' ) ) {
	function woodmart_my_account_wrapp_start(){
		echo '<div class="woocommerce-my-account-wrapper">';
	}
	add_action( 'woocommerce_account_navigation', 'woodmart_my_account_wrapp_start', 1 );
}

if( ! function_exists( 'woodmart_my_account_wrapp_end' ) ) {
	function woodmart_my_account_wrapp_end(){
		echo '</div><!-- .woocommerce-my-account-wrapper -->';
	}
	add_action( 'woocommerce_account_content', 'woodmart_my_account_wrapp_end', 10000 );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Mini cart buttons
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_mini_cart_view_cart_btn' ) ) {
	function woodmart_mini_cart_view_cart_btn(){
		echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="button btn-cart wc-forward">' . esc_html__( 'View cart', 'woocommerce' ) . '</a>';
	}
	remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
	add_action( 'woocommerce_widget_shopping_cart_buttons', 'woodmart_mini_cart_view_cart_btn', 10 );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Attribute on product element
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_get_product_attributes_label' ) ) {
	function woodmart_get_product_attributes_label(){
		global $product;
		$attributes = $product->get_attributes();
		$output = array();
		foreach ( $attributes as $attribute ) {
			if ( !isset( $attribute['name'] ) ) continue;
		    $show_attr_on_product = woodmart_wc_get_attribute_term( $attribute['name'], 'show_on_product' );
			if ( $show_attr_on_product == 'on' ) {
				$terms = wc_get_product_terms( $product->get_id(), $attribute['name'], array( 'fields' => 'all' ) );
				foreach ( $terms as $term ) {
					$content = esc_attr( $term->name );
					$classes = 'label-term-' . $term->slug;
					$classes .= ' label-attribute-' . $attribute['name'];
					
					$image = get_term_meta( $term->term_id, 'image', true );
					if ( $image ) {
						$classes .= ' label-with-img';
						$content = '<img src="' . esc_url( $image ) . '" title="' . esc_attr( $term->slug ) . '" alt="' . esc_attr( $term->slug ) . '" />';
					}
					
					$output[] = '<span class="attribute-label product-label ' . esc_attr( $classes ) . '">'. $content .'</span>';
				}
			}
		}
		return $output;
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Before add to cart text area
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_before_add_to_cart_area' ) ) {
	function woodmart_before_add_to_cart_area(){
		$content = woodmart_get_opt( 'content_before_add_to_cart' );
		if ( empty( $content ) ) return;
		echo '<div class="wd-before-add-to-cart">' . do_shortcode( $content ) . '</div>';
	}
	add_action( 'woocommerce_single_product_summary', 'woodmart_before_add_to_cart_area', 25 );
}

/**
 * ------------------------------------------------------------------------------------------------
 * After add to cart text area
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_after_add_to_cart_area' ) ) {
	function woodmart_after_add_to_cart_area(){
		$content = woodmart_get_opt( 'content_after_add_to_cart' );
		if ( empty( $content ) ) return;
		echo '<div class="wd-after-add-to-cart">' . do_shortcode( $content ) . '</div>';
	}
		add_action( 'woocommerce_single_product_summary', 'woodmart_after_add_to_cart_area', 31 );
}


/**
 * ------------------------------------------------------------------------------------------------
 * Clear all filters button
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_clear_filters_btn' ) ) {
	function woodmart_clear_filters_btn() {
		if ( ! woodmart_woocommerce_installed() ) {
			return;
		}

		$url = woodmart_shop_page_link();
		$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
		
		$min_price = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '';
		$max_price = isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : '';

		if ( 0 < count( $_chosen_attributes ) || $min_price || $max_price ) {
			$reset_url = strtok( $url, '?' );
			if ( isset( $_GET['post_type'] ) ) $reset_url = add_query_arg( 'post_type', wc_clean( wp_unslash( $_GET['post_type'] ) ), $reset_url );
			?>
				<div class="wd-clear-filters wd-action-btn wd-style-text wd-cross-icon">
					<a href="<?php echo esc_url( $reset_url ); ?>"><?php echo esc_html__( 'Clear filters', 'woodmart' ); ?></a>
				</div>
			<?php
		}
	}
	add_action( 'woodmart_before_active_filters_widgets', 'woodmart_clear_filters_btn' );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Print shop page css from vc elements
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_sticky_single_add_to_cart' ) ) {
	function woodmart_sticky_single_add_to_cart() {
		global $product;

		if ( ! woodmart_woocommerce_installed() || ! is_product() || ! woodmart_get_opt( 'single_sticky_add_to_cart' ) ) return;

		woodmart_enqueue_js_script( 'sticky-add-to-cart' );
		woodmart_enqueue_inline_style( 'sticky-add-to-cart' );

		?>
			<div class="wd-sticky-btn<?php echo woodmart_get_old_classes( ' woodmart-sticky-btn' ); ?> <?php echo ( woodmart_get_opt( 'mobile_single_sticky_add_to_cart' ) ) ? 'mobile-on' : 'mobile-off' ?>">
				<div class="wd-sticky-btn-container container<?php echo woodmart_get_old_classes( ' woodmart-sticky-btn-container' ); ?>">
					<div class="wd-sticky-btn-content<?php echo woodmart_get_old_classes( ' woodmart-sticky-btn-content' ); ?>">
						<div class="wd-sticky-btn-thumbnail<?php echo woodmart_get_old_classes( ' woodmart-sticky-btn-thumbnail' ); ?>">
							<?php echo woocommerce_get_product_thumbnail(); ?>	
						</div>
						<div class="wd-sticky-btn-info<?php echo woodmart_get_old_classes( ' woodmart-sticky-btn-info' ); ?>">
							<h4 class="wd-entities-title"><?php the_title(); ?></h4>
							<?php echo wc_get_rating_html( $product->get_average_rating() ); ?>							
						</div>
					</div>
					<div class="wd-sticky-btn-cart<?php echo woodmart_get_old_classes( ' woodmart-sticky-btn-cart' ); ?>">
						<span class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
						<?php if ( $product->is_type( 'simple' ) ): ?>
							<?php woocommerce_simple_add_to_cart(); ?>
						<?php else: ?>
							<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="wd-sticky-add-to-cart button alt<?php echo woodmart_get_old_classes( ' woodmart-sticky-add-to-cart' ); ?>">
								<?php echo true == $product->is_type( 'variable' ) ? esc_html__( 'Select options', 'woodmart' ) : $product->single_add_to_cart_text(); ?>
							</a>
						<?php endif; ?>
						<?php if ( woodmart_get_opt( 'compare' ) ) : ?>
							<?php woodmart_add_to_compare_loop_btn(); ?>
						<?php endif; ?>

						<?php do_action( 'woodmart_sticky_atc_actions' ); ?>
					</div>

				</div>
			</div>
		<?php
	}
	add_action( 'woodmart_before_wp_footer', 'woodmart_sticky_single_add_to_cart', 999 );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Print shop page css from vc elements
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_shop_vc_css' ) ) {
	function woodmart_shop_vc_css() {
		if ( ! function_exists( 'wc_get_page_id' ) ) return;
		$shop_custom_css = get_post_meta( wc_get_page_id( 'shop' ), '_wpb_shortcodes_custom_css', true );
		if ( ! empty( $shop_custom_css ) ) {
			echo '<style data-type="vc_shortcodes-custom-css">' . $shop_custom_css . '</style>';
		}
	}
	add_action( 'wp_head', 'woodmart_shop_vc_css', 1000 );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Sticky sidebar button
 * ------------------------------------------------------------------------------------------------
 */
if ( ! function_exists( 'woodmart_sticky_sidebar_button' ) ) {
	function woodmart_sticky_sidebar_button( $echo = true, $toolbar = false ) {
		$sidebar_class = woodmart_get_sidebar_class();
		$sticky_toolbar_fields = woodmart_get_opt( 'sticky_toolbar_fields' ) ? woodmart_get_opt( 'sticky_toolbar_fields' ) : array();
		$sticky_toolbar = woodmart_get_opt( 'sticky_toolbar' );
		$sticky_filter_button = $toolbar ? true : woodmart_get_opt( 'sticky_filter_button' );

		$classes = $toolbar ? ' wd-tools-element wd-on-toolbar' . woodmart_get_old_classes( '  sticky-toolbar' ) : ' wd-action-btn wd-style-icon';
		$label_classes = $toolbar ? ' wd-toolbar-label' : '';

		if ( strstr( $sidebar_class, 'col-lg-0' ) || woodmart_maintenance_page() || is_singular( 'woodmart_slide' ) || is_singular( 'cms_block' ) || ( $sticky_toolbar && in_array( 'sidebar', $sticky_toolbar_fields ) && ! $toolbar ) ) {
			return;
		}

		woodmart_enqueue_js_script( 'hidden-sidebar' );
		woodmart_enqueue_js_script( 'sticky-sidebar-btn' );

		$classes .= woodmart_get_old_classes( ' woodmart-sticky-sidebar-opener' );

		?>

		<?php if ( ( woodmart_woocommerce_installed() && ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) && $sticky_filter_button && ( woodmart_get_opt( 'shop_hide_sidebar' ) || woodmart_get_opt( 'shop_hide_sidebar_tablet' ) ) ) ) :?>
			<?php $classes .= woodmart_get_old_classes( ' shop-sidebar-opener' ); ?>
			<div class="wd-sidebar-opener wd-on-shop<?php echo esc_attr( $classes ); ?>">
				<a href="#" rel="noffollow">
					<span class="<?php echo esc_attr( $label_classes ); ?>">
						<?php esc_html_e( 'Filters', 'woodmart' ); ?>
					</span>
				</a>
			</div>
		<?php elseif ( woodmart_get_opt( 'hide_main_sidebar_mobile' ) && ( ! woodmart_woocommerce_installed() || ( ! is_shop() && ! is_product_category() && ! is_product_tag() && ! is_product_taxonomy() && ! is_404() ) ) && ! is_singular( 'portfolio' ) && ! woodmart_is_portfolio_archive() ) : ?>
			<div class="wd-sidebar-opener<?php echo esc_attr( $classes ); ?>">
				<a href="#" rel="noffollow">
					<span class="<?php echo esc_attr( $label_classes ); ?>">
						<?php esc_html_e( 'Sidebar', 'woodmart' ); ?>
					</span>
				</a>
			</div>
		<?php endif; ?>

		<?php
	}
	
	add_action( 'woodmart_before_wp_footer', 'woodmart_sticky_sidebar_button', 200 );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Login to see add to cart and prices
 * ------------------------------------------------------------------------------------------------
 */
if ( ! function_exists( 'woodmart_hide_price_not_logged_in' ) ) {
	function woodmart_hide_price_not_logged_in() {
		if ( ! is_user_logged_in() && woodmart_get_opt( 'login_prices' ) ) {
			add_filter( 'woocommerce_get_price_html', 'woodmart_print_login_to_see' );  
			add_filter( 'woocommerce_loop_add_to_cart_link', '__return_false' );  

			//Add to cart btns
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
        	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		}
	}

	add_action( 'init', 'woodmart_hide_price_not_logged_in', 200 );
}

if ( ! function_exists( 'woodmart_print_login_to_see' ) ) {
	function woodmart_print_login_to_see() {
		$settings = whb_get_settings();
		$login_side = isset( $settings['account'] ) && $settings['account']['login_dropdown'] && $settings['account']['form_display'] == 'side';
		$classes = '';

		if ( ! is_user_logged_in() && $login_side ) {
			woodmart_enqueue_js_script( 'login-sidebar' );
			$classes .= ' login-side-opener';
		}

		return '<a href="' . esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ) . '" class="login-to-prices-msg' . esc_attr( $classes ) . '">' . esc_html__( 'Login to see prices', 'woodmart' ) . '</a>';
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Shop page filters and custom content
 * ------------------------------------------------------------------------------------------------
 */
if ( ! function_exists( 'woodmart_shop_filters_area' ) ) {
	function woodmart_shop_filters_area() {
		$filters_type = woodmart_get_opt( 'shop_filters_type' ) ? woodmart_get_opt( 'shop_filters_type' ) : 'widgets';
		$custom_content = woodmart_get_opt( 'shop_filters_content' );
		$always_open = woodmart_get_opt( 'shop_filters_always_open' );
		$filters_opened = ( ( woocommerce_products_will_display() && $filters_type == 'widgets' ) || ( $filters_type == 'content' && $custom_content ) ) && $always_open;
		$classes = $filters_opened ? ' always-open' : '';
		$classes .= $filters_type == 'content' && $custom_content ? ' custom-content' : '';

		woodmart_enqueue_js_script( 'filters-area' );

		if ( woodmart_get_opt( 'shop_filters' ) ) {
			if ( $filters_type == 'content' && ! $custom_content ) return;
			echo '<div class="filters-area' . esc_attr( $classes ) . '">';
				echo '<div class="filters-inner-area align-items-start row">';
					if ( $filters_type == 'widgets' ) {
						do_action( 'woodmart_before_filters_widgets' );
						dynamic_sidebar( 'filters-area' ); 
						do_action( 'woodmart_after_filters_widgets' );
					} else if ( $filters_type == 'content' && $custom_content ) {
						echo '<div class="col-12">';
							echo do_shortcode( '[html_block id="' . esc_attr( $custom_content ) . '"]' );
						echo '</div>';
					}
				echo '</div>';
			echo '</div>';
		}
	}

	add_action( 'woodmart_shop_filters_area', 'woodmart_shop_filters_area', 10 );
}


if( ! function_exists( 'woodmart_get_header_links' ) ) {
	function woodmart_get_header_links( $settings = false ) {
		$links = array();

		if( ! woodmart_woocommerce_installed() ) return $links;

		if ( ! $settings ) {
			$settings = whb_get_settings();
		}

		$dropdowns_classes = '';

		if ( 'light' === whb_get_dropdowns_color() ) {
			$dropdowns_classes .= ' color-scheme-light';
		}

		$dropdowns_classes .= woodmart_get_old_classes( ' menu-item-register sub-menu-dropdown' );

		$login_dropdown = isset( $settings ) && isset( $settings['login_dropdown'] ) && $settings['login_dropdown'] && ( ! $settings['form_display'] || $settings['form_display'] == 'dropdown' );
		$links_with_username = isset( $settings['with_username'] ) && $settings['with_username'];

		$account_link = get_permalink( get_option('woocommerce_myaccount_page_id') );

		$current_user = wp_get_current_user();

		if( is_user_logged_in() ) {
			$links['my-account'] = array(
				'label' => esc_html__('My Account', 'woodmart'),
				'url' => $account_link,
				'dropdown' => '
					<div class="wd-dropdown wd-dropdown-menu' . woodmart_get_old_classes( ' menu-item-my-account' ) . ' wd-dropdown-my-account wd-design-default' . $dropdowns_classes . '">
						' . woodmart_get_my_account_menu() . '
					</div>
				',
				'dropdown_mobile' => woodmart_get_my_account_menu()
			);
			if ( $links_with_username ) {
				$links['my-account']['label'] = sprintf( esc_html__( 'Hello, %s', 'woodmart' ), esc_html( $current_user->display_name ) );
			}
		} else {
			$links['register'] = array(
				'label' => esc_html__('Login / Register', 'woodmart'),
				'url' => $account_link
			);

			if( $login_dropdown ) {
				woodmart_enqueue_js_script( 'login-dropdown' );
				$links['register']['dropdown'] = '
					<div class="wd-dropdown wd-dropdown-register' . $dropdowns_classes . '">
						<div class="login-dropdown-inner">
							<h3 class="login-title"><span>' . esc_html__('Sign in', 'woodmart') . '</span><a class="create-account-link" href="' . esc_url( add_query_arg( 'action', 'register', $account_link ) ) . '">' . esc_html__('Create an Account', 'woodmart') . '</a>' . '</h3>
							' . woodmart_login_form( false, $account_link ) . '
						</div>
					</div>
				';
			}
		}

		return apply_filters( 'woodmart_get_header_links',  $links );
	}
}

// **********************************************************************// 
// ! Add account links to the top bat menu
// **********************************************************************// 

if ( ! function_exists( 'woodmart_topbar_links' ) ) {
	function woodmart_topbar_links ( $items = '', $args = array(), $return = false ) {
    	$is_mobile_menu = ! empty( $args ) && $args->theme_location == 'mobile-menu' ;
		
		$login_side = '';

	    if ( $is_mobile_menu || $return ) {
			$settings = whb_get_settings();

		    $is_wishlist_in_header = isset( $settings['wishlist'] );
			$is_compare_in_header = isset( $settings['compare'] );
			$is_account_in_header = isset( $settings['account'] );
			$login_side = isset( $settings['account'] ) && $settings['account']['login_dropdown'] && $settings['account']['form_display'] == 'side';

	    	if( $is_wishlist_in_header && $is_mobile_menu ) {
		    	// Wishlist item firstly
				$items .= '<li class="menu-item menu-item-wishlist wd-with-icon">';
					$items .= woodmart_header_block_wishlist();
				$items .= '</li>';
			}
			
			if( $is_compare_in_header && $is_mobile_menu ) {
				$items .= '<li class="menu-item menu-item-compare wd-with-icon">';
					$items .= '<a href="' . esc_url( woodmart_get_compare_page_url() ) . '">' . esc_html__( 'Compare', 'woodmart' ) . '</a>';
				$items .= '</li>';
			}
	    	
			$links = woodmart_get_header_links();

			if( $is_account_in_header && $is_mobile_menu ){
				foreach ($links as $key => $link) {
					$classes = '';
					if ( ! is_user_logged_in() && $login_side ) {
						woodmart_enqueue_js_script( 'login-sidebar' );
						$classes .= ' login-side-opener';
					}
					
					if( ! empty( $link['dropdown_mobile'] ) ) $classes .= ' menu-item-has-children';
					
					$items .= '<li class="menu-item ' . $classes . ' menu-item-account wd-with-icon">';
					$items .= '<a href="' . esc_url( $link['url'] ) . '">' . wp_kses( $link['label'], 'default' ) . '</a>';
					if( ! empty( $link['dropdown_mobile'] ) && ! ( ! empty( $args ) && $args->theme_location == 'mobile-menu' && $key == 'register' ) ) {
						$items .= $link['dropdown_mobile'];
					}
					$items .= '</li>';
				}
			}

	    }
	    return $items;
	}

	add_filter( 'wp_nav_menu_items', 'woodmart_topbar_links', 50, 2 );
}

if ( ! function_exists( 'woodmart_header_block_wishlist' ) ) {
	function woodmart_header_block_wishlist() {
		ob_start();
		if ( woodmart_woocommerce_installed() && woodmart_get_opt( 'wishlist' ) ): ?>
			<a href="<?php echo esc_url( woodmart_get_whishlist_page_url() ); ?>" class="woodmart-nav-link">
				<span class="nav-link-text"><?php esc_html_e( 'Wishlist', 'woodmart' ) ?></span>
			</a>
		<?php endif;
		return ob_get_clean();
	}
}
// **********************************************************************// 
// ! My account menu
// **********************************************************************// 

if( ! function_exists( 'woodmart_get_my_account_menu' ) ) {
	function woodmart_get_my_account_menu() {
		$user_info = get_userdata( get_current_user_id() );
		$user_roles = $user_info->roles;
		
		$out = '<ul class="wd-sub-menu' . woodmart_get_old_classes( ' sub-menu' ) . '">';

        foreach ( wc_get_account_menu_items() as $endpoint => $label ) {
            $out .= '<li class="' . wc_get_account_menu_item_classes( $endpoint ) . '"><a href="' . esc_url( wc_get_account_endpoint_url( $endpoint ) ) . '"><span>' . esc_html( $label ) . '</span></a></li>';
        }

		return $out . '</ul>';
	}
}

//Fix mobile menu active class
add_filter( 'woocommerce_account_menu_item_classes', function( $classes, $endpoint ){
	if ( ! is_account_page() && $endpoint == 'dashboard' ) {
		$classes = array_diff( $classes, array( 'is-active' ) );
	}
	return $classes;
}, 10, 2 );

// **********************************************************************// 
// ! Add account links to the top bat menu
// **********************************************************************// 

if( ! function_exists( 'woodmart_add_logout_link' ) ) {
	add_filter( 'wp_nav_menu_items', 'woodmart_add_logout_link', 10, 2 );
	function woodmart_add_logout_link ( $items = '', $args = array(), $return = false ) {
	    if ( ( ! empty( $args ) && $args->theme_location == 'header-account-menu' ) || $return ) {

			$links = array();

			$logout_link = wc_get_account_endpoint_url( 'customer-logout' );

			$links['logout'] = array(
				'label' => esc_html__( 'Logout', 'woodmart' ),
				'url' => $logout_link
			);

			if( ! empty( $links )) {
				foreach ($links as $key => $link) {
					$items .= '<li id="menu-item-' . $key . '" class="menu-item item-event-hover menu-item-' . $key . '">';
					$items .= '<a href="' . esc_url( $link['url'] ) . '">' . esc_attr( $link['label'] ) . '</a>';
					$items .= '</li>';
				}
			}
	    }
	    return $items;
	}
}

// **********************************************************************// 
// ! Login form HTML for top bar menu dropdown
// **********************************************************************// 

if( ! function_exists( 'woodmart_login_form' ) ) {
	function woodmart_login_form( $echo = true, $action = false, $message = false, $hidden = false, $redirect = false ) {
		$vk_app_id         = woodmart_get_opt( 'vk_app_id' );
		$vk_app_secret     = woodmart_get_opt( 'vk_app_secret' );
		$fb_app_id         = woodmart_get_opt( 'fb_app_id' );
		$fb_app_secret     = woodmart_get_opt( 'fb_app_secret' );
		$goo_app_id        = woodmart_get_opt( 'goo_app_id' );
		$goo_app_secret    = woodmart_get_opt( 'goo_app_secret' );
		$style             = woodmart_get_opt( 'alt_social_login_btns_style' ) ? 'wd-style-alt' : '';
		
		ob_start();
		
		?>
			<form method="post" class="login woocommerce-form woocommerce-form-login <?php if ( $hidden ) echo 'hidden-form'; ?>" <?php echo ( ! empty( $action ) ) ? 'action="' . esc_url( $action ) . '"' : ''; ?> <?php if ( $hidden ) echo 'style="display:none;"'; ?>>

				<?php do_action( 'woocommerce_login_form_start' ); ?>

				<?php echo true == $message ? wpautop( wptexturize( $message ) ) : ''; ?>

				<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide form-row-username">
					<label for="username"><?php esc_html_e( 'Username or email', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
				</p>
				<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide form-row-password">
					<label for="password"><?php esc_html_e( 'Password', 'woodmart' ); ?>&nbsp;<span class="required">*</span></label>
					<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
				</p>

				<?php do_action( 'woocommerce_login_form' ); ?>

				<p class="form-row">
					<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
					<?php if ( $redirect ): ?>
						<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ); ?>" />
					<?php endif ?>
					<button type="submit" class="button woocommerce-button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( 'Log in', 'woodmart' ); ?>"><?php esc_html_e( 'Log in', 'woodmart' ); ?></button>
				</p>

				<div class="login-form-footer">
					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="woocommerce-LostPassword lost_password"><?php esc_html_e( 'Lost your password?', 'woodmart' ); ?></a>
					<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
						<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" value="forever" title="<?php esc_html_e( 'Remember me', 'woodmart' ); ?>" aria-label="<?php esc_html_e( 'Remember me', 'woodmart' ); ?>" /> <span><?php esc_html_e( 'Remember me', 'woodmart' ); ?></span>
					</label>
				</div>
				
				<?php if ( class_exists( 'WOODMART_Auth' ) && ( ( ! empty( $fb_app_id ) && ! empty( $fb_app_secret ) ) || ( ! empty( $goo_app_id ) && ! empty( $goo_app_secret ) ) || ( ! empty( $vk_app_id ) && ! empty( $vk_app_secret ) ) ) ): ?>
					<?php woodmart_enqueue_inline_style( 'social-login' ); ?>
					<div class="title wd-login-divider social-login-title<?php echo woodmart_get_old_classes( ' wood-login-divider' ); ?>"><span><?php esc_html_e('Or login with', 'woodmart'); ?></span></div>
					<div class="wd-social-login <?php echo esc_attr( $style ); ?>">
						<?php if ( ! empty( $fb_app_id ) && ! empty( $fb_app_secret ) ): ?>
							<div class="social-login-btn">
								<a href="<?php echo add_query_arg('social_auth', 'facebook', wc_get_page_permalink('myaccount')); ?>" class="login-fb-link btn"><?php esc_html_e( 'Facebook', 'woodmart' ); ?></a>
							</div>
						<?php endif ?>
						<?php if ( ! empty( $goo_app_id ) && ! empty( $goo_app_secret ) ): ?>
							<div class="social-login-btn">
								<a href="<?php echo add_query_arg('social_auth', 'google', wc_get_page_permalink('myaccount')); ?>" class="login-goo-link btn"><?php esc_html_e( 'Google', 'woodmart' ); ?></a>
							</div>
						<?php endif ?>
						<?php if ( ! empty( $vk_app_id ) && ! empty( $vk_app_secret ) ): ?>
							<div class="social-login-btn">
								<a href="<?php echo add_query_arg('social_auth', 'vkontakte', wc_get_page_permalink('myaccount')); ?>" class="login-vk-link btn"><?php esc_html_e( 'VKontakte', 'woodmart' ); ?></a>
							</div>
						<?php endif ?>
					</div>
				<?php endif ?>

				<?php do_action( 'woocommerce_login_form_end' ); ?>

			</form>

		<?php

		if( $echo ) {
			echo ob_get_clean();
		} else {
			return ob_get_clean();
		}
	}
}

// *****************************************************************************// 
// ! OFF Wrappers elements: cart widget, search (full screen), header banner
// *****************************************************************************// 

if ( ! function_exists( 'woodmart_cart_side_widget' ) ) {
	function woodmart_cart_side_widget() {
		if ( ! whb_is_side_cart() || ! woodmart_woocommerce_installed() ) return;

		$wrapper_classes = '';

		if ( 'light' === whb_get_dropdowns_color() ) {
			$wrapper_classes .= ' color-scheme-light';
		}

		$position = is_rtl() ? 'left' : 'right';

		$wrapper_classes .= ' wd-' . $position;

		woodmart_enqueue_js_script( 'cart-widget' );
		?>
			<div class="cart-widget-side wd-side-hidden<?php echo esc_attr( $wrapper_classes ); ?>">
				<div class="widget-heading">
					<span class="title widget-title"><?php esc_html_e( 'Shopping cart', 'woodmart' ); ?></span>
					<div class="close-side-widget wd-action-btn wd-style-text wd-cross-icon">
						<a href="#" rel="noffollow"><?php esc_html_e( 'close', 'woodmart' ); ?></a>
					</div>
				</div>
				<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
			</div>
		<?php
	}

	add_action( 'woodmart_before_wp_footer', 'woodmart_cart_side_widget', 140 );
}

// **********************************************************************// 
// Sidebar login form
// **********************************************************************// 
if( ! function_exists( 'woodmart_sidebar_login_form' ) ) {
	function woodmart_sidebar_login_form() {
		if( ! woodmart_woocommerce_installed() ) return;
		
		$settings = whb_get_settings();
		$login_side = isset( $settings['account'] ) && $settings['account']['login_dropdown'] && $settings['account']['form_display'] == 'side';
		$account_link = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		$page_id         = woodmart_get_the_ID() ? woodmart_get_the_ID() : get_option( 'woocommerce_myaccount_page_id' );
		$redirect_url    = apply_filters( 'woodmart_my_account_side_login_form_redirect', get_permalink( $page_id ) );

		$wrapper_classes = '';

		if ( 'light' === whb_get_dropdowns_color() ) {
			$wrapper_classes .= ' color-scheme-light';
		}

		$position = is_rtl() ? 'left' : 'right';

		$wrapper_classes .= ' wd-' . $position;
		
		if ( ! $login_side || is_user_logged_in() ) return;
		?>
			<div class="login-form-side wd-side-hidden<?php echo esc_attr( $wrapper_classes ); ?>">
				<div class="widget-heading">
					<h3 class="widget-title"><?php esc_html_e( 'Sign in', 'woodmart' ); ?></h3>
					<div class="close-side-widget wd-action-btn wd-style-text wd-cross-icon">
						<a href="#" rel="noffollow"><?php esc_html_e( 'close', 'woodmart' ); ?></a>
					</div>
				</div>

				<?php woocommerce_output_all_notices(); ?>

				<?php woodmart_login_form(true, $redirect_url, false, true, $redirect_url ); ?>

				<div class="create-account-question">
					<p><?php esc_html_e( 'No account yet?', 'woodmart' ); ?></p>
					<a href="<?php echo esc_url( add_query_arg( 'action', 'register', $account_link ) ); ?>" class="btn btn-style-link btn-color-primary create-account-button"><?php esc_html_e( 'Create an Account', 'woodmart' ); ?></a>
				</div>
			</div>
		<?php
	}

	add_action( 'wp_footer', 'woodmart_sidebar_login_form', 160 );
}

// **********************************************************************// 
// ! Products small grid
// **********************************************************************// 

if( ! function_exists( 'woodmart_products_widget_template' )) {
	function woodmart_products_widget_template($upsells, $small_grid = false) {
		global $product;
		echo '<ul class="product_list_widget">';
		foreach ( $upsells as $upsells_poduct )  {
			$product = $upsells_poduct;
			if( $small_grid ) {
				wc_get_template( 'content-widget-small.php' );
			} else {
				wc_get_template( 'content-widget-product.php', array( 'show_rating' => false ) );
			}
		}
		echo '</ul>';
		wp_reset_postdata();
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * My account navigation
 * ------------------------------------------------------------------------------------------------
 */
if ( ! function_exists( 'woodmart_my_account_navigation' ) ) {
	function woodmart_my_account_navigation( $items ) {
		$user_info = get_userdata( get_current_user_id() );
		$user_roles = $user_info && property_exists( $user_info, 'roles' ) ? $user_info->roles : array();

		unset( $items['customer-logout'] );

		if ( class_exists( 'WeDevs_Dokan' ) && apply_filters( 'woodmart_dokan_link', true ) && ( in_array( 'seller', $user_roles ) || in_array( 'administrator', $user_roles ) ) ) {
			$items['dokan'] = esc_html__( 'Vendor dashboard', 'woodmart' );
		}

		$items['customer-logout'] = esc_html__( 'Logout', 'woodmart' );

		return $items;
	}

	add_filter( 'woocommerce_account_menu_items', 'woodmart_my_account_navigation', 15 );
}

if ( ! function_exists( 'woodmart_my_account_navigation_endpoint_url' ) ) {
	function woodmart_my_account_navigation_endpoint_url( $url, $endpoint, $value, $permalink ) {

		if ( 'dokan' === $endpoint && class_exists( 'WeDevs_Dokan' ) ) {
			$url = dokan_get_navigation_url();
		}

		return $url;
	}

	add_filter( 'woocommerce_get_endpoint_url', 'woodmart_my_account_navigation_endpoint_url', 15, 4 );
}

if ( ! function_exists( 'woodmart_wc_empty_cart_message' ) ) {
	/**
	 * Show notice if cart is empty.
	 *
	 * @since 1.0.0
	 */
	function woodmart_wc_empty_cart_message() {
		?>
		<p class="cart-empty wd-empty-page">
			<?php echo wp_kses_post( apply_filters( 'wc_empty_cart_message', __( 'Your cart is currently empty.', 'woocommerce' ) ) ); ?>
		</p>
		<?php
	}

	add_action( 'woocommerce_cart_is_empty', 'woodmart_wc_empty_cart_message', 10 );
}
