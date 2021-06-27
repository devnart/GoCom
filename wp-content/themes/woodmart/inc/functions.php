<?php use Elementor\Plugin;

if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

if ( ! function_exists( 'woodmart_is_data_encode' ) ) {
	function woodmart_is_css_encode( $data ) {
		return strlen( $data ) > 50;
	}
}

if ( ! function_exists( 'woodmart_set_default_header' ) ) {
	/**
	 * Setup default header from theme settings
	 *
	 * @since 1.0.0
	 */
	function woodmart_set_default_header() {
		if ( ! isset( $_GET['settings-updated'] ) || isset( $_GET['preset'] ) ) { // phpcs:ignore
			return;
		}

		$theme_settings_header_id = woodmart_get_opt( 'default_header' );

		if ( $theme_settings_header_id ) {
			update_option( 'whb_main_header', $theme_settings_header_id );
		}
	}

	add_filter( 'init', 'woodmart_set_default_header', 1000 );
}

if( ! function_exists( 'woodmart_enqueue_gallery_script' ) ) {
	function woodmart_enqueue_gallery_script( $html5 ) {
		if ( woodmart_get_opt( 'single_post_justified_gallery' ) ) {
			woodmart_enqueue_js_library( 'magnific' );
			woodmart_enqueue_js_library( 'justified' );
			woodmart_enqueue_inline_style( 'justified' );
			woodmart_enqueue_js_script( 'mfp-popup' );
			woodmart_enqueue_inline_style( 'mfp-popup' );
		}

		return $html5;
	}

	add_filter( 'use_default_gallery_style', 'woodmart_enqueue_gallery_script' );
}

if( ! function_exists( 'woodmart_get_blog_shortcode_ajax' ) ) {
	function woodmart_get_blog_shortcode_ajax() {
		if( ! empty( $_POST['atts'] ) ) {
			$atts = woodmart_clean( $_POST['atts'] );
			$paged = ( empty( $_POST['paged'] ) ) ? 2 : sanitize_text_field( (int) $_POST['paged'] ) + 1;
			$atts['ajax_page'] = $paged;

			if ( isset( $atts['elementor'] ) && $atts['elementor'] ) {
				$data = woodmart_elementor_blog_template( $atts );
			} else {
				$data = woodmart_shortcode_blog( $atts );
			}

			echo json_encode( $data );

			die();
		}
	}
	add_action( 'wp_ajax_woodmart_get_blog_shortcode', 'woodmart_get_blog_shortcode_ajax' );
	add_action( 'wp_ajax_nopriv_woodmart_get_blog_shortcode', 'woodmart_get_blog_shortcode_ajax' );
}

if( ! function_exists( 'woodmart_get_portfolio_shortcode_ajax' ) ) {
	function woodmart_get_portfolio_shortcode_ajax() {
		if( ! empty( $_POST['atts'] ) ) {
			$atts = woodmart_clean( $_POST['atts'] );
			$paged = (empty($_POST['paged'])) ? 2 : sanitize_text_field( (int) $_POST['paged'] ) + 1;
			$atts['ajax_page'] = $paged;

			if ( isset( $atts['elementor'] ) && $atts['elementor'] ) {
				$data = woodmart_elementor_portfolio_template( $atts );
			} else {
				$data = woodmart_shortcode_portfolio( $atts );
			}

			echo json_encode( $data );

			die();
		}
	}

	add_action( 'wp_ajax_woodmart_get_portfolio_shortcode', 'woodmart_get_portfolio_shortcode_ajax' );
	add_action( 'wp_ajax_nopriv_woodmart_get_portfolio_shortcode', 'woodmart_get_portfolio_shortcode_ajax' );
}

if ( ! function_exists( 'woodmart_get_color_value' ) ) {
	function woodmart_get_color_value( $key, $default ) {
		$color = woodmart_get_opt( $key );

		if ( isset( $color['idle'] ) && $color['idle'] ) {
			return $color['idle'];
		} else {
			return $default;
		}
	}
}

if ( ! function_exists( 'woodmart_get_css_animation' ) ) {
	function woodmart_get_css_animation( $css_animation ) {
		$output = '';
		if ( $css_animation && $css_animation != 'none' ) {
			wp_enqueue_style( 'animate-css' );
			wp_enqueue_style( 'vc_animate-css' );

			woodmart_enqueue_js_library( 'waypoints' );
			woodmart_enqueue_js_script( 'animations-offset' );
			$output = ' wpb_animate_when_almost_visible wpb_' . $css_animation . ' ' . $css_animation;
		}
		return $output;
	}
}

if( ! function_exists( 'woodmart_get_user_panel_params' ) ) {
	function woodmart_get_user_panel_params() {
		return apply_filters( 'woodmart_get_user_panel_params', array(
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Title', 'woodmart' ),
				'param_name' => 'title',
			)
		));
	}
}

if( ! function_exists( 'woodmart_vc_get_link_attr' ) ) {
	function woodmart_vc_get_link_attr( $link ) {
		$link = ( '||' === $link ) ? '' : $link;
		$link = woodmart_vc_build_link( $link );
		return $link;
	}
}

if( ! function_exists( 'woodmart_get_link_attributes' ) ) {
	function woodmart_get_link_attributes( $link, $popup = false ) {
		//parse link
		$link = woodmart_vc_get_link_attr( $link );
		$use_link = false;
		if ( isset( $link['url'] ) && strlen( $link['url'] ) > 0 ) {
			$use_link = true;
			$a_href = apply_filters( 'woodmart_extra_menu_url', $link['url'] );
			if ( $popup ) $a_href = $link['url'];
			$a_title = $link['title'];
			$a_target = $link['target'];
		}

		$attributes = array();

		if ( $use_link ) {
			$attributes[] = 'href="' . trim( $a_href ) . '"';
			$attributes[] = 'title="' . esc_attr( trim( $a_title ) ) . '"';
			if ( ! empty( $a_target ) ) {
				$attributes[] = 'target="' . esc_attr( trim( $a_target ) ) . '"';
			}
		}

		$attributes = implode( ' ', $attributes );

		return $attributes;
	}
}

if ( ! function_exists( 'woodmart_get_taxonomies_by_ids_autocomplete' ) ) {
	/**
	 * Autocomplete by taxonomies ids.
	 *
	 * @since 1.0.0
	 *
	 * @param array $ids Posts ids.
	 *
	 * @return array
	 */
	function woodmart_get_taxonomies_by_ids_autocomplete( $ids ) {
		$output = array();

		if ( ! $ids ) {
			return $output;
		}

		if ( ! is_array( $ids ) ) {
			$ids = array( $ids );
		}

		foreach ( $ids as $id ) {
			$term = get_term( $id );

			if ( $term ) {
				$output[ $term->term_id ] = array(
					'name'  => $term->name,
					'value' => $term->term_id,
				);
			}
		}

		return $output;
	}
}

if ( ! function_exists( 'woodmart_get_taxonomies_by_query_autocomplete' ) ) {
	/**
	 * Autocomplete by taxonomies.
	 *
	 * @since 1.0.0
	 */
	function woodmart_get_taxonomies_by_query_autocomplete() {
		$output = array();

		$args = array(
			'number'   => 5,
			'taxonomy' => $_POST['value'], // phpcs:ignore
			'search'   => $_POST['params']['term'], // phpcs:ignore
		);

		$terms = get_terms( $args );

		if ( count( $terms ) > 0 ) { // phpcs:ignore
			foreach ( $terms as $term ) {
				$output[] = array(
					'id'   => $term->term_id,
					'text' => $term->name,
				);
			}
		}

		echo wp_json_encode( $output );
		die();
	}

	add_action( 'wp_ajax_woodmart_get_taxonomies_by_query_autocomplete', 'woodmart_get_taxonomies_by_query_autocomplete' );
	add_action( 'wp_ajax_nopriv_woodmart_get_taxonomies_by_query_autocomplete', 'woodmart_get_taxonomies_by_query_autocomplete' );
}

// **********************************************************************//
// ! Body classes
// **********************************************************************//

if ( ! function_exists( 'xts_get_default_value' ) ) {
	/**
	 * Get default theme settings value
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Value key.
	 *
	 * @return string
	 */
	function xts_get_default_value( $key ) {
		// $default_values = xts_get_config( 'framework-defaults' );
		// $theme_values   = xts_get_config( 'theme-defaults' );

		// if ( $theme_values ) {
		// 	$default_values = wp_parse_args( $theme_values, $default_values );
		// }

		return '';
		return isset( $default_values[ $key ] ) ? $default_values[ $key ] : '';
	}
}

if ( ! function_exists( 'woodmart_product_attributes_array' ) ) {
	function woodmart_product_attributes_array() {

		if ( ! function_exists( 'wc_get_attribute_taxonomies' ) ) {
			return;
		}
		$attributes = array();

		foreach ( wc_get_attribute_taxonomies() as $attribute ) {
			$attributes[ 'pa_' . $attribute->attribute_name ] = array(
				'name' => $attribute->attribute_label, 'value' => 'pa_' . $attribute->attribute_name,
			);
		}

		return $attributes;
	}
}

if ( ! function_exists( 'woodmart_get_pages_array' ) ) {
	/**
	 * Get all pages array
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function woodmart_get_pages_array() {
		$pages = array();

		foreach ( get_pages() as $page ) {
			$pages[ $page->ID ] = array(
				'name'  => $page->post_title,
				'value' => $page->ID,
			);
		}

		return $pages;
	}
}

if( ! function_exists( 'woodmart_body_class' ) ) {
	function woodmart_body_class( $classes ) {

		$page_id = woodmart_page_ID();

		$site_width = woodmart_get_opt( 'site_width' );
		$product_design = woodmart_product_design();
		$product_sticky = woodmart_product_sticky();

		$ajax_shop = woodmart_get_opt( 'ajax_shop' );
		$hide_sidebar_mobile = woodmart_get_opt( 'shop_hide_sidebar' );
		$hide_sidebar_tablet = woodmart_get_opt( 'shop_hide_sidebar_tablet' );
		$hide_sidebar_desktop = woodmart_get_opt( 'shop_hide_sidebar_desktop' );
		$catalog_mode = woodmart_get_opt( 'catalog_mode' );
		$categories_toggle = woodmart_get_opt( 'categories_toggle' );
		$sticky_footer = woodmart_get_opt( 'sticky_footer' );
		$dark = woodmart_get_opt( 'dark_version' );
		$form_fields_style = ( woodmart_get_opt( 'form_fields_style' ) ) ? woodmart_get_opt( 'form_fields_style' ) : 'square';
		$form_border_width = woodmart_get_opt( 'form_border_width' );
		$single_post_design = woodmart_get_opt( 'single_post_design' );
		$main_sidebar_mobile = woodmart_get_opt( 'hide_main_sidebar_mobile' );

		if ( $single_post_design == 'large_image' && is_single() ) {
			$classes[] = 'single-post-large-image';
		}

		$classes[] = 'wrapper-' . $site_width;

		if ( 'underlined' === $form_fields_style ) {
			$classes[] = 'form-style-' . $form_fields_style;
		} else {
			$classes[] = woodmart_get_old_classes( ' form-style-' . $form_fields_style );
		}

		$classes[] = woodmart_get_old_classes( ' form-border-width-' . $form_border_width );

		if( is_singular( 'product' ) ) {
			$classes[] = 'woodmart-product-design-' . $product_design;
			if( $product_sticky ) {
				$classes[] = 'woodmart-product-sticky-on';
				wp_enqueue_script( 'imagesloaded' );
				woodmart_enqueue_js_library( 'sticky-kit' );
				woodmart_enqueue_js_script( 'sticky-details' );
			}
		}

		if ( woodmart_woocommerce_installed() && ( is_shop() || is_product_category() ) && ( $hide_sidebar_desktop && $sticky_footer ) ) {
			$classes[] = 'no-sticky-footer';
		}elseif( $sticky_footer ){
			wp_enqueue_script( 'imagesloaded' );
			woodmart_enqueue_js_script( 'sticky-footer' );
			$classes[] = 'sticky-footer-on';
		}

		if ( $dark ) {
			$classes[] = 'global-color-scheme-light';
		}

		if ( $catalog_mode ) {
			$classes[] = 'catalog-mode-on';
		}

		if ( $categories_toggle ) {
			$classes[] = 'categories-accordion-on';
		}

		if( woodmart_is_blog_archive() ) {
			$classes[] = 'woodmart-archive-blog';
		} else if( woodmart_is_shop_archive() ) {
			$classes[] = 'woodmart-archive-shop';
		} else if( woodmart_is_portfolio_archive() ) {
			$classes[] = 'woodmart-archive-portfolio';
		}

		//Header banner
		if ( ! woodmart_get_opt( 'header_close_btn' ) && woodmart_get_opt( 'header_banner' ) && ! woodmart_maintenance_page() ) {
			$classes[] = 'header-banner-display';
		}
		if ( woodmart_get_opt( 'header_banner' ) && ! woodmart_maintenance_page() ) {
			$classes[] = 'header-banner-enabled';
		}

		if ( $ajax_shop ) {
			$classes[] = 'woodmart-ajax-shop-on';
		}

		if( $hide_sidebar_mobile && ( woodmart_woocommerce_installed() && ( is_shop() || is_product_category() || is_product_tag() || woodmart_is_product_attribute_archive() ) ) || $main_sidebar_mobile && ( ! woodmart_woocommerce_installed() || ( ! is_shop() && ! is_product_category() && ! is_product_tag() && ! woodmart_is_product_attribute_archive() ) ) ) {
			$classes[] = 'offcanvas-sidebar-mobile';
		}

		if( $hide_sidebar_tablet ) {
			$classes[] = 'offcanvas-sidebar-tablet';
		}

		if( $hide_sidebar_desktop ) {
			$classes[] = 'offcanvas-sidebar-desktop';
		}

		if ( ! is_user_logged_in() && woodmart_get_opt( 'login_prices' ) ) {
			$classes[] = 'login-see-prices';
		}

		if ( woodmart_get_opt( 'sticky_notifications' ) ) {
			$classes[] = 'notifications-sticky';
		}

		if ( woodmart_get_opt( 'sticky_toolbar' ) ) {
			$classes[] = 'sticky-toolbar-on';
		}
		if ( woodmart_get_opt( 'hide_larger_price' ) ) {
			$classes[] = 'hide-larger-price';
		}

		$classes = array_merge( $classes, woodmart_get_header_body_classes() );

		return $classes;
	}

	add_filter('body_class', 'woodmart_body_class');
}



/**
 * ------------------------------------------------------------------------------------------------
 * Get header body classes
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_get_header_body_classes' ) ) {
	function woodmart_get_header_body_classes() {
		$classes = array();
		$settings = whb_get_settings();
		if ( isset( $settings['overlap'] ) && $settings['overlap'] ) {
			$classes[] = 'wd-header-overlap';
			$classes[] = woodmart_get_old_classes( 'woodmart-header-overcontent' );
		}
		if ( 'light' === whb_get_dropdowns_color() ) {
			$classes[] = 'dropdowns-color-light';
		}
		return $classes;
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Filter wp_title
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_wp_title' ) ) {
	function woodmart_wp_title( $title, $sep ) {
		global $paged, $page;

		if ( is_feed() )
			return $title;

		// Add the site name.
		$title .= get_bloginfo( 'name' );

		// Add the site description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			$title = "$title $sep $site_description";

		// Add a page number if necessary.
		if ( $paged >= 2 || $page >= 2 )
			$title = "$title $sep " . sprintf( esc_html__( 'Page %s', 'woodmart' ), max( $paged, $page ) );

		return $title;
	}
	add_filter( 'wp_title', 'woodmart_wp_title', 10, 2 );

}

/**
 * ------------------------------------------------------------------------------------------------
 * Get predefined footer configuration by index
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_get_footer_config' ) ) {
	function woodmart_get_footer_config( $index ) {

		if( $index > 20 || $index < 1) {
			$index = 1;
		}

		$configs = apply_filters( 'woodmart_footer_configs_array', array(
			1 => array(
				'cols' => array(
					'col-12'
				),

			),
			2 => array(
				'cols' => array(
					'col-12 col-sm-6',
					'col-12 col-sm-6',
				),
			),
			3 => array(
				'cols' => array(
					'col-12 col-sm-4',
					'col-12 col-sm-4',
					'col-12 col-sm-4',
				),
			),
			4 => array(
				'cols' => array(
					'col-12 col-sm-6 col-lg-3',
					'col-12 col-sm-6 col-lg-3',
					'col-12 col-sm-6 col-lg-3',
					'col-12 col-sm-6 col-lg-3',
				),
			),
			5 => array(
				'cols' => array(
					'col-12 col-sm-6 col-md-4 col-lg-2',
					'col-12 col-sm-6 col-md-4 col-lg-2',
					'col-12 col-sm-6 col-md-4 col-lg-2',
					'col-12 col-sm-6 col-md-4 col-lg-2',
					'col-12 col-sm-6 col-md-4 col-lg-2',
					'col-12 col-sm-6 col-md-4 col-lg-2',
				),
			),
			6 => array(
				'cols' => array(
					'col-12 col-sm-4 col-lg-3',
					'col-12 col-sm-4 col-lg-6',
					'col-12 col-sm-4 col-lg-3',
				),
			),
			7 => array(
				'cols' => array(
					'col-12 col-sm-4 col-lg-6',
					'col-12 col-sm-4 col-lg-3',
					'col-12 col-sm-4 col-lg-3',
				),
			),
			8 => array(
				'cols' => array(
					'col-12 col-sm-4 col-lg-3',
					'col-12 col-sm-4 col-lg-3',
					'col-12 col-sm-4 col-lg-6',
				),
			),
			9 => array(
				'cols' => array(
					'col-12',
					'col-12 col-sm-6 col-lg-3',
					'col-12 col-sm-6 col-lg-3',
					'col-12 col-sm-6 col-lg-3',
					'col-12 col-sm-6 col-lg-3',
				),
			),
			10 => array(
				'cols' => array(
					'col-12 col-md-6',
					'col-12 col-md-6',
					'col-12 col-sm-6 col-lg-3',
					'col-12 col-sm-6 col-lg-3',
					'col-12 col-sm-6 col-lg-3',
					'col-12 col-sm-6 col-lg-3',
				),
			),
			11 => array(
				'cols' => array(
					'col-12 col-md-6',
					'col-12 col-md-6',
					'col-12 col-sm-6 col-md-3 col-lg-2',
					'col-12 col-sm-6 col-md-3 col-lg-2',
					'col-12 col-sm-6 col-md-3 col-lg-2',
					'col-12 col-sm-6 col-md-3 col-lg-2',
					'col-12 col-lg-4',
				),
			),
			12 => array(
				'cols' => array(
					'col-12',
					'col-12 col-sm-6 col-md-3 col-lg-2',
					'col-12 col-sm-6 col-md-3 col-lg-2',
					'col-12 col-sm-6 col-md-3 col-lg-2',
					'col-12 col-sm-6 col-md-3 col-lg-2',
					'col-12 col-lg-4',
				),
			),
			13 => array(
				'cols' => array(
					'col-12 col-sm-6 col-lg-3',
					'col-12 col-sm-6 col-lg-3',
					'col-12 col-sm-4 col-lg-2',
					'col-12 col-sm-4 col-lg-2',
					'col-12 col-sm-4 col-lg-2',
				),
			),
		) );

		return (isset( $configs[$index] )) ? $configs[$index] : array();
	}
}


// **********************************************************************//
// ! Theme 3d plugins
// **********************************************************************//


if(!defined('YITH_REFER_ID')) {
	define('YITH_REFER_ID', '1040314');
}


if( ! function_exists( 'woodmart_3d_plugins' )) {
	function woodmart_3d_plugins() {
		if( function_exists( 'set_revslider_as_theme' ) ){
			set_revslider_as_theme();
		}
	}

	add_action( 'init', 'woodmart_3d_plugins' );
}

if( ! function_exists( 'woodmart_vcSetAsTheme' ) ) {

	function woodmart_vcSetAsTheme() {
		if( function_exists( 'vc_set_as_theme' ) ){
			vc_set_as_theme();
		}
	}

	add_action( 'vc_before_init', 'woodmart_vcSetAsTheme' );
}


// **********************************************************************//
// ! Obtain real page ID (shop page, blog, portfolio or simple page)
// **********************************************************************//

/**
 * This function is called once when initializing WOODMART_Layout object
 * then you can use function woodmart_page_ID to get current page id
 */
if ( ! function_exists( 'woodmart_get_the_ID' ) ) {
	function woodmart_get_the_ID( $args = array() ) {
		global $post;

		$page_id = 0;

		$page_for_posts    = get_option( 'page_for_posts' );
		$page_for_shop     = get_option( 'woocommerce_shop_page_id' );
		$page_for_projects = woodmart_tpl2id( 'portfolio.php' );
		$custom_404_id     = woodmart_get_opt( 'custom_404_page' );

		if ( isset( $post->ID ) ) {
			$page_id = $post->ID;
		}

		if ( isset( $post->ID ) && ( is_singular( 'page' ) || is_singular( 'post' ) ) ) {
			$page_id = $post->ID;
		} elseif ( is_home() || is_singular( 'post' ) || is_search() || is_tag() || is_category() || is_date() || is_author() ) {
			$page_id = $page_for_posts;
		} elseif ( is_archive() && get_post_type() === 'portfolio' ) {
			$page_id = $page_for_projects;
		}

		if( woodmart_woocommerce_installed() && function_exists( 'is_shop' )  ) {
			if( isset( $args['singulars'] ) && in_array( 'product', $args['singulars']) && is_singular( "product" ) ) {
				// keep post id
			} else if( is_shop() || is_product_category() || is_product_tag() || woodmart_is_product_attribute_archive() ) {
				$page_id = $page_for_shop;
			}
		}

		if( is_404() && ( $custom_404_id != 'default' || ! empty( $custom_404_id ) ) ) $page_id = $custom_404_id;

		return $page_id;
	}
}



// **********************************************************************//
// ! Function to get HTML block content
// **********************************************************************//

if( ! function_exists( 'woodmart_get_html_block' ) ) {
	function woodmart_get_html_block($id) {
		$id = apply_filters( 'wpml_object_id', $id, 'cms_block', true );
		$post = get_post( $id );
		$content = '';
		if ( ! $post || $post->post_type != 'cms_block' ) return;

		if ( woodmart_is_elementor_installed() && Plugin::$instance->db->is_built_with_elementor( $id ) ) {
			$content .= woodmart_elementor_get_content( $id );
		} else {
			$content .= do_shortcode( $post->post_content );

			$shortcodes_custom_css = get_post_meta( $id, '_wpb_shortcodes_custom_css', true );
			$woodmart_shortcodes_custom_css = get_post_meta( $id, 'woodmart_shortcodes_custom_css', true );

			$content .= '<style data-type="vc_shortcodes-custom-css">';
			if ( ! empty( $shortcodes_custom_css ) ) {
				$content .= $shortcodes_custom_css;
			}

			if ( ! empty( $woodmart_shortcodes_custom_css ) ) {
				$content .= $woodmart_shortcodes_custom_css;
			}
			$content .= '</style>';
		}

		return $content;
	}

}

if( ! function_exists( 'woodmart_get_static_blocks_array' ) ) {
	function woodmart_get_static_blocks_array( $new = false ) {
		$args = array( 'posts_per_page' => 500, 'post_type' => 'cms_block' );
		$blocks_posts = get_posts( $args );
		$array = array();
		foreach ( $blocks_posts as $post ) :
			if ( $new ) {
				$array[ $post->ID ] = array(
					'name' => $post->post_title,
					'value' => $post->ID,
				);
			} else {
				$array[ $post->post_title ] = $post->ID;
			}
		endforeach;
		return $array;
	}
}

if ( ! function_exists( 'woodmart_get_elementor_html_blocks_array' ) ) {
	/**
	 * Function to get array of HTML Blocks
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function woodmart_get_elementor_html_blocks_array() {
		$output = array();

		$posts = get_posts(
			array(
				'posts_per_page' => 500, // phpcs:ignore
				'post_type'      => 'cms_block',
			)
		);

		$output['0'] = esc_html__( 'Select', 'woodmart' );

		foreach ( $posts as $post ) {
			$output[ $post->ID ] = $post->post_title;
		}

		return $output;
	}
}

// **********************************************************************//
// ! Set excerpt length and more btn
// **********************************************************************//

add_filter( 'excerpt_length', 'woodmart_excerpt_length', 999 );

if( ! function_exists( 'woodmart_excerpt_length' ) ) {
	function woodmart_excerpt_length( $length ) {
		return 20;
	}
}

add_filter('excerpt_more', 'woodmart_new_excerpt_more');

if( ! function_exists( 'woodmart_new_excerpt_more' ) ) {
	function woodmart_new_excerpt_more( $more ) {
		return '';
	}
}

// **********************************************************************//
// ! Add scroll to top buttom
// **********************************************************************//

add_action( 'woodmart_before_wp_footer', 'woodmart_scroll_top_btn' );

if( ! function_exists( 'woodmart_scroll_top_btn' ) ) {
	function woodmart_scroll_top_btn( $more ) {
		if( !woodmart_get_opt( 'scroll_top_btn' ) ) return;

		woodmart_enqueue_js_script( 'scroll-top' );
		woodmart_enqueue_inline_style( 'scroll-top' );
		?>
			<a href="#" class="scrollToTop"></a>
		<?php
	}
}


// **********************************************************************//
// ! Return related posts args array
// **********************************************************************//

if( ! function_exists( 'woodmart_get_related_posts_args' ) ) {
	function woodmart_get_related_posts_args( $post_id ) {
		$taxs = wp_get_post_tags( $post_id );
		$args = array();
		if ( $taxs ) {
			$tax_ids = array();
			foreach( $taxs as $individual_tax ) $tax_ids[] = $individual_tax->term_id;

			$args = array(
				'tag__in'               => $tax_ids,
				'post__not_in'          => array( $post_id ),
				'showposts'             => 12,
				'ignore_sticky_posts'   => 1
			);

		}

		return $args;
	}
}

if( ! function_exists( 'woodmart_get_related_projects_args' ) ) {
	function woodmart_get_related_projects_args( $post_id ) {
		$taxs = wp_get_post_terms( $post_id, 'project-cat' );
		$args = array();
		if ( $taxs ) {
			$tax_ids = array();
			foreach( $taxs as $individual_tax ) $tax_ids[] = $individual_tax->term_id;

			$args = array(
				'post_type' => 'portfolio',
				'post__not_in' => array( $post_id ),
				'tax_query' => array(
					array(
						'taxonomy' => 'project-cat',
						'terms' => $tax_ids,
						'include_children' => false
					),
				)
			);
		}

		return $args;
	}
}

// **********************************************************************//
// ! Navigation walker
// **********************************************************************//

if ( ! class_exists( 'WOODMART_Mega_Menu_Walker' ) ) {
	class WOODMART_Mega_Menu_Walker extends Walker_Nav_Menu {
		/**
		 * Design.
		 *
		 * @var string
		 */
		private $color_scheme;

		/**
		 * Design.
		 *
		 * @var string
		 */
		private $design = 'default';

		/**
		 * WOODMART_Mega_Menu_Walker constructor.
		 */
		public function __construct() {
			$this->color_scheme = whb_get_dropdowns_color();
		}

		/**
		 * Starts the list before the elements are added.
		 *
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param mixed  $args   An array of arguments. @see wp_nav_menu().
		 *
		 * @see   Walker::start_lvl()
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			$indent        = str_repeat( "\t", $depth );
			$is_nav_mobile = strstr( $args->menu_class, 'wd-nav-mobile' );
			$is_nav_fs     = strstr( $args->menu_class, 'wd-nav-fs' );
			$classes       = '';
			if ( 0 === $depth && ! $is_nav_mobile ) {
				if ( 'default' !== $this->color_scheme ) {
					$classes .= ' color-scheme-' . $this->color_scheme;
				}

				$classes .= ' wd-design-' . $this->design;

				if ( ! $is_nav_fs ) {
					$classes .= ' wd-dropdown-menu wd-dropdown';
				} else {
					$classes .= ' wd-dropdown-fs-menu';
				}

				$classes .= woodmart_get_old_classes( ' sub-menu-dropdown' );

				$output .= $indent . '<div class="' . trim( $classes ) . '">';
				$output .= $indent . '<div class="container">';
			}

			if ( 0 === $depth ) {
				if ( ( 'full-width' === $this->design || 'sized' === $this->design ) && ! $is_nav_mobile ) {
					$sub_menu_class  = 'wd-sub-menu row';
					$sub_menu_class .= woodmart_get_old_classes( ' sub-menu' );
				} else {
					$sub_menu_class  = 'wd-sub-menu';
					$sub_menu_class .= woodmart_get_old_classes( ' sub-menu' );
				}
			} else {
				if ( 'default' === $this->design && ! $is_nav_mobile && ! $is_nav_fs ) {
					$sub_menu_class = 'sub-sub-menu wd-dropdown';
				} elseif ( 'default' === $this->design && $is_nav_fs ) {
					$sub_menu_class = 'sub-sub-menu wd-dropdown-fs-menu';
				} else {
					$sub_menu_class = 'sub-sub-menu';
				}
			}

			if ( ! $is_nav_mobile && 0 === $depth ) {
				$sub_menu_class .= ' color-scheme-' . $this->color_scheme;
			}

			$output .= "\n$indent<ul class=\"$sub_menu_class\">\n";

			if ( 'light' === $this->color_scheme || 'dark' === $this->color_scheme ) {
				$this->color_scheme = whb_get_dropdowns_color();
			}
		}

		/**
		 * Ends the list of after the elements are added.
		 *
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param mixed  $args   An array of arguments. @see wp_nav_menu().
		 *
		 * @see   Walker::end_lvl()
		 */
		public function end_lvl( &$output, $depth = 0, $args = array() ) {
			$indent  = str_repeat( "\t", $depth );
			$output .= "$indent</ul>\n";

			if ( 0 === $depth && ! strstr( $args->menu_class, 'wd-nav-mobile' ) ) {
				$output .= "$indent</div>\n";
				$output .= "$indent</div>\n";
			}
		}

		/**
		 * Start the element output.
		 *
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item   Menu item data object.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param mixed  $args   An array of arguments. @see wp_nav_menu().
		 * @param int    $id     Current item ID.
		 *
		 * @see   Walker::start_el()
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			$indent        = $depth ? str_repeat( "\t", $depth ) : '';
			$classes       = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[]     = 'menu-item-' . $item->ID;
			$classes[]     = 'item-level-' . $depth;
			$label_out     = '';
			$design        = get_post_meta( $item->ID, '_menu_item_design', true );
			$width         = get_post_meta( $item->ID, '_menu_item_width', true );
			$height        = get_post_meta( $item->ID, '_menu_item_height', true );
			$icon          = get_post_meta( $item->ID, '_menu_item_icon', true );
			$event         = get_post_meta( $item->ID, '_menu_item_event', true );
			$label         = get_post_meta( $item->ID, '_menu_item_label', true );
			$label_text    = get_post_meta( $item->ID, '_menu_item_label-text', true );
			$block         = get_post_meta( $item->ID, '_menu_item_block', true );
			$dropdown_ajax = get_post_meta( $item->ID, '_menu_item_dropdown-ajax', true );
			$opanchor      = get_post_meta( $item->ID, '_menu_item_opanchor', true );
			$color_scheme  = get_post_meta( $item->ID, '_menu_item_colorscheme', true );
			$is_nav_mobile = strstr( $args->menu_class, 'wd-nav-mobile' );
			$is_nav_fs     = strstr( $args->menu_class, 'wd-nav-fs' );

			if ( 'light' === $color_scheme ) {
				$this->color_scheme = 'light';
			} elseif ( 'dark' === $color_scheme ) {
				$this->color_scheme = 'dark';
			}

			if ( 0 === $depth && $design ) {
				$this->design = $design;
			}

			if ( ! $design ) {
				$design = 'default';
			}

			if ( 0 !== $depth && $args->walker->has_children && 'default' === $this->design && ! $is_nav_mobile && ! $is_nav_fs ) {
				$classes[] = 'wd-event-hover';
			}

			if ( ! is_object( $args ) ) {
				return;
			}

			if ( 0 === $depth && ! $is_nav_mobile ) {
				$classes[] = woodmart_get_old_classes( 'menu-item-design-' . $design );
				if ( 'sized' === $design || 'full-width' === $design ) {
					$classes[] = 'menu-mega-dropdown';
				} else {
					$classes[] = 'menu-simple-dropdown';
				}

				$event = empty( $event ) ? 'hover' : $event;

				if ( ! $is_nav_fs ) {
					$classes[] = 'wd-event-' . $event;
				}
			}

			if ( ( 'full-width' === $this->design || 'sized' === $this->design ) && 1 === $depth && ! $is_nav_mobile ) {
				$classes[] .= 'col-auto';
			}

			if ( $block && $is_nav_mobile && 'default' !== $design ) {
				$classes[] = 'menu-item-has-block';
			}

			if ( 'enable' === $opanchor ) {
				woodmart_enqueue_js_library( 'waypoints' );
				woodmart_enqueue_js_script( 'one-page-menu' );
				$classes[] = 'onepage-link';
				$key       = array_search( 'current-menu-item', $classes );
				if ( false !== $key ) {
					unset( $classes[ $key ] );
				}
			}

			if ( ! empty( $label ) ) {
				$classes[] = 'item-with-label';
				$classes[] = 'item-label-' . $label;
				$label_out = '<span class="menu-label menu-label-' . $label . '">' . esc_attr( $label_text ) . '</span>';
			}

			woodmart_enqueue_js_script( 'menu-offsets' );
			woodmart_enqueue_js_script( 'menu-setup' );

			if ( ! empty( $block ) && 'default' !== $design && ! $args->walker->has_children ) {
				$classes[] = 'menu-item-has-children';
			}

			if ( 'yes' === $dropdown_ajax ) {
				woodmart_enqueue_js_script( 'menu-dropdowns-ajax' );
				$classes[] = 'dropdown-load-ajax';
			}

			if ( $height && 'sized' === $design ) {
				$classes[] = 'dropdown-with-height';
			}

			/**
			 * Filter the CSS class(es) applied to a menu item's list item element.
			 *
			 * @since 3.0.0
			 * @since 4.1.0 The `$depth` parameter was added.
			 *
			 * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
			 * @param object $item    The current menu item.
			 * @param array  $args    An array of {@see wp_nav_menu()} arguments.
			 * @param int    $depth   Depth of menu item. Used for padding.
			 */
			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			/**
			 * Filter the ID applied to a menu item's list item element.
			 *
			 * @since 3.0.1
			 * @since 4.1.0 The `$depth` parameter was added.
			 *
			 * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
			 * @param object $item    The current menu item.
			 * @param array  $args    An array of {@see wp_nav_menu()} arguments.
			 * @param int    $depth   Depth of menu item. Used for padding.
			 */
			$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li' . $id . $class_names . '>';

			$atts           = array();
			$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
			$atts['target'] = ! empty( $item->target ) ? $item->target : '';
			$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
			$atts['href']   = ! empty( $item->url ) ? $item->url : '';

			/**
			 * Filter the HTML attributes applied to a menu item's anchor element.
			 *
			 * @since 3.6.0
			 * @since 4.1.0 The `$depth` parameter was added.
			 *
			 * @param array  $atts   {
			 *                       The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
			 *
			 * @type string  $title  Title attribute.
			 * @type string  $target Target attribute.
			 * @type string  $rel    The rel attribute.
			 * @type string  $href   The href attribute.
			 * }
			 *
			 * @param object $item   The current menu item.
			 * @param array  $args   An array of {@see wp_nav_menu()} arguments.
			 * @param int    $depth  Depth of menu item. Used for padding.
			 */
			$atts          = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
			$atts['class'] = 'woodmart-nav-link';

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value       = 'href' === $attr ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$icon_url = '';

			if ( 'product_cat' === $item->object ) {
				$icon_url = get_term_meta( $item->object_id, 'category_icon_alt', true );
			}

			$item_output  = $args->before;
			$item_output .= '<a' . $attributes . '>';
			if ( $icon ) {
				if ( 'wpb' === woodmart_get_opt( 'page_builder', 'wpb' ) ) {
					wp_enqueue_style( 'vc_font_awesome_5' );
					wp_enqueue_style( 'vc_font_awesome_5_shims' );
				} else {
					wp_enqueue_style( 'elementor-icons-fa-solid' );
					wp_enqueue_style( 'elementor-icons-fa-brands' );
					wp_enqueue_style( 'elementor-icons-fa-regular' );
				}
				$item_output .= '<span class="wd-nav-icon fa fa-' . $icon . '"></span>';
			}

			$icon_attrs = apply_filters( 'woodmart_megamenu_icon_attrs', false );

			if ( ! empty( $icon_url ) ) {
				$item_output .= '<img src="' . esc_url( $icon_url ) . '" alt="' . esc_attr( $item->title ) . '" ' . $icon_attrs . ' class="wd-nav-img' . woodmart_get_old_classes( ' category-icon' ) . '" />';
			}
			/** This filter is documented in wp-includes/post-template.php */
			if ( 0 === $depth ) {
				$item_output .= '<span class="nav-link-text">' . $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after . '</span>';
			} else {
				$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			}

			$item_output .= $label_out;
			$item_output .= '</a>';
			$item_output .= $args->after;

			$styles = '';

			if ( 0 === $depth && ! $is_nav_mobile ) {
				if ( has_post_thumbnail( $item->ID ) ) {
					$post_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $item->ID ), 'full' );

					if ( 'yes' === $dropdown_ajax ) {
						$styles .= '.dropdowns-loaded ';
					}

					$styles .= '.menu-item-' . $item->ID . ' > .wd-dropdown-menu {';
					$styles .= 'background-image: url(' . $post_thumbnail[0] . '); ';
					$styles .= '}';
				}

				if ( $block && ! $args->walker->has_children && 'default' !== $design ) {
					$classes = '';

					if ( ! $is_nav_fs ) {
						$classes .= ' wd-dropdown-menu wd-dropdown';
					} else {
						$classes .= ' wd-dropdown-fs-menu';
					}

					$classes .= ' wd-design-' . $design;
					$classes .= ' color-scheme-' . $this->color_scheme;
					$classes .= woodmart_get_old_classes( ' sub-menu-dropdown' );

					$item_output .= "\n$indent<div class=\"" . trim( $classes ) . "\">\n";
					$item_output .= "\n$indent<div class=\"container\">\n";
					if ( 'yes' === $dropdown_ajax ) {
						$item_output .= '<div class="dropdown-html-placeholder wd-fill" data-id="' . $block . '"></div>';
					} else {
						$item_output .= woodmart_html_block_shortcode( array( 'id' => $block ) );
					}
					$item_output .= "\n$indent</div>\n";
					$item_output .= "\n$indent</div>\n";

					if ( 'light' === $this->color_scheme || 'dark' === $this->color_scheme ) {
						$this->color_scheme = whb_get_dropdowns_color();
					}
				}
			}

			if ( 'sized' === $design && ! empty( $height ) && ! empty( $width ) && ! $is_nav_mobile ) {
				$styles .= '.menu-item-' . $item->ID . ' .wd-design-sized {';
				$styles .= 'min-height: ' . $height . 'px; ';
				$styles .= 'width: ' . $width . 'px; ';
				$styles .= '}';
			}

			if ( $styles && ! $is_nav_mobile ) {
				$item_output .= '<style>';
				$item_output .= $styles;
				$item_output .= '</style>';
			}

			/**
			 * Filter a menu item's starting output.
			 *
			 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
			 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
			 * no filter for modifying the opening and closing `<li>` for a menu item.
			 *
			 * @since 3.0.0
			 *
			 * @param string $item_output The menu item's starting HTML output.
			 * @param object $item        Menu item data object.
			 * @param int    $depth       Depth of menu item. Used for padding.
			 * @param array  $args        An array of {@see wp_nav_menu()} arguments.
			 */
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}
}

// **********************************************************************//
// ! Load menu drodpwns with AJAX actions
// **********************************************************************//

if( ! function_exists('woodmart_load_html_dropdowns_action') ) {
	function woodmart_load_html_dropdowns_action() {
		$response = array(
			'status' => 'error',
			'message' => 'Can\'t load HTML blocks with AJAX',
			'data' => array(),
		);

		if( class_exists('WPBMap') )
			WPBMap::addAllMappedShortcodes();

		if( isset( $_POST['ids'] ) && is_array( $_POST['ids'] ) ) {
			$ids = woodmart_clean( $_POST['ids'] );
			foreach ($ids as $id) {
				$id = (int) $id;
				$content = woodmart_get_html_block($id);
				if( ! $content ) continue;

				$response['status'] = 'success';
				$response['message'] = 'At least one HTML block loaded';
				$response['data'][$id] = $content;
			}
		}

		echo json_encode($response);

		die();
	}
	add_action( 'wp_ajax_woodmart_load_html_dropdowns', 'woodmart_load_html_dropdowns_action' );
	add_action( 'wp_ajax_nopriv_woodmart_load_html_dropdowns', 'woodmart_load_html_dropdowns_action' );
}

// **********************************************************************//
// ! // Deletes first gallery shortcode and returns content (http://stackoverflow.com/questions/17224100/wordpress-remove-shortcode-and-save-for-use-elsewhere)
// **********************************************************************//

if( ! function_exists( 'woodmart_strip_shortcode_gallery' ) ) {
	function  woodmart_strip_shortcode_gallery( $content ) {
		preg_match_all( '/'. get_shortcode_regex() .'/s', $content, $matches, PREG_SET_ORDER );
		if ( ! empty( $matches ) ) {
			foreach ( $matches as $shortcode ) {
				if ( 'gallery' === $shortcode[2] ) {
					$pos = strpos( $content, $shortcode[0] );
					if ($pos !== false)
						return substr_replace( $content, '', $pos, strlen($shortcode[0]) );
				}
			}
		}
		return $content;
	}
}


// **********************************************************************//
// ! Get exceprt from post content
// **********************************************************************//

if( ! function_exists( 'woodmart_excerpt_from_content' ) ) {
	function woodmart_excerpt_from_content($post_content, $limit, $shortcodes = '') {
		// Strip shortcodes and HTML tags
		if ( empty( $shortcodes )) {
			$post_content = preg_replace("/\[caption(.*)\[\/caption\]/i", '', $post_content);
			$post_content = preg_replace('`\[[^\]]*\]`','',$post_content);
		}

		$post_content = stripslashes( wp_filter_nohtml_kses( $post_content ) );

		if ( woodmart_get_opt( 'blog_words_or_letters' ) == 'letter' ) {
			$excerpt = mb_substr( $post_content, 0, $limit );
			if ( mb_strlen( $excerpt ) >= $limit ) {
				$excerpt .= '...';
			}
		}else{
			$limit++;
			$excerpt = explode(' ', $post_content, $limit);
			if ( count( $excerpt) >= $limit ) {
				array_pop( $excerpt );
				$excerpt = implode( " ", $excerpt ) . '...';
			} else {
				$excerpt = implode( " ", $excerpt );
			}
		}

		$excerpt = strip_tags( $excerpt );

		if ( trim( $excerpt ) == '...' ) {
			return '';
		}

		return $excerpt;
	}
}

// **********************************************************************//
// ! Get portfolio taxonomies dropdown
// **********************************************************************//

if( ! function_exists( 'woodmart_get_projects_cats_array') ) {
	function woodmart_get_projects_cats_array() {
		$return = array('All' => '');

		if( ! post_type_exists( 'portfolio' ) ) return array();

		$cats = get_terms( 'project-cat' );

		foreach ($cats as $key => $cat) {
			$return[$cat->name] = $cat->term_id;
		}

		return $return;
	}
}

// **********************************************************************//
// ! Get menus dropdown
// **********************************************************************//

if( ! function_exists( 'woodmart_get_menus_array') ) {
	function woodmart_get_menus_array( $style = 'default' ) {
		$output = array();

		$menus = wp_get_nav_menus();

		if ( 'elementor' === $style ) {
			$output[''] = esc_html__( 'Select', 'woodmart' );
		}

		foreach ( $menus as $menu ) {
			if ( 'elementor' === $style ) {
				$output[ $menu->term_id ] = $menu->name;
			} else {
				$output[ $menu->term_id ] = $menu->name;
			}
		}

		return $output;
	}
}


// **********************************************************************//
// ! Get custom header array created with header builder
// **********************************************************************//

if(!function_exists('woodmart_get_whb_headers_array')) {
	function woodmart_get_whb_headers_array( $get_from_options = false, $new = false ) {
		if ( $get_from_options ) {
			$list = get_option( 'whb_saved_headers' );
		} else {
			$headers_list = whb_get_builder()->list;
			$list = $headers_list->get_all();
		}
		$headers = array();

		if ( $new ) {
			$headers['none'] = array(
				'name'  => 'none',
				'value' => 'none',
			);
		} else {
			$headers['none'] = 'none';
		}

		if( ! empty( $list ) && is_array( $list ) ) {
            foreach ($list as $key => $header) {
				if ( $new ) {
					$headers[$key] = array(
						'name'  => $header['name'],
						'value' => $key,
					);
				} else {
					$headers[$key] = $header['name'];
				}
            }
        }

		return $headers;
	}
}

// **********************************************************************//
// ! Get registered sidebars dropdown
// **********************************************************************//

if(!function_exists('woodmart_get_sidebars_array')) {
	function woodmart_get_sidebars_array( $new = false ) {
		global $wp_registered_sidebars;
		$sidebars = array();
		if ( $new ) {
			$sidebars['none'] = array(
				'name' => 'none',
				'value' => 'none'
			);
		} else {
			$sidebars['none'] = 'none';
		}
		foreach( $wp_registered_sidebars as $id=>$sidebar ) {
			if ( $new ) {
				$sidebars[$id] = array(
					'name' => $sidebar[ 'name' ],
					'value' => $id
				);
			} else {
				$sidebars[ $id ] = $sidebar[ 'name' ];
			}
		}
		return $sidebars;
	}
}

// **********************************************************************//
// ! Get page id by template name
// **********************************************************************//

if( ! function_exists( 'woodmart_pages_ids_from_template' ) ) {
	function woodmart_pages_ids_from_template( $name ) {
		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => $name . '.php'
		));

		$return = array();

		foreach($pages as $page){
			$return[] = $page->ID;
		}

		return $return;
	}
}

// **********************************************************************//
// ! Get content of the SVG icon located in images/svg folder
// **********************************************************************//
if( ! function_exists( 'woodmart_get_svg_content' ) ) {
	function woodmart_get_svg_content($name) {
		$folder = WOODMART_THEMEROOT . '/images/svg';
		$file = $folder . '/' . $name . '.svg';

		return (file_exists( $file )) ? woodmart_get_any_svg( $file ) : false;
	}
}

if( ! function_exists( 'woodmart_get_any_svg' ) ) {
	function woodmart_get_any_svg( $file, $id = false ) {
		$content = function_exists( 'woodmart_get_svg' ) ? woodmart_get_svg( $file ) : '';
		$start_tag = '<svg';
		if( $id ) {
			$pattern = "/id=\"(\w)+\"/";
			if( preg_match($pattern, $content) ) {
				$content = preg_replace($pattern, "id=\"" . $id . "\"", $content, 1);
			} else {
				$content = preg_replace( "/<svg/", "<svg id=\"" . $id . "\"", $content);
			}
		}
		// Strip doctype
		$position = strpos($content, $start_tag);
		$content = substr($content, $position);

		return $content;
	}
}

// **********************************************************************//
//  Function return vc_row with gradient.
// **********************************************************************//
if( ! function_exists( 'woodmart_get_gradient_attr' ) ) {
	function woodmart_get_gradient_attr( $output, $obj, $attr ) {
	if ( isset( $attr['woodmart_gradient_switch'] ) && $attr['woodmart_gradient_switch'] == 'yes' ) {
			$gradient_css = woodmart_get_gradient_css( $attr['woodmart_color_gradient'] );
			$output = preg_replace_callback('/wd-row-gradient-enable.*?>/',
				function ( $matches ) use( $gradient_css ) {
				   return strtolower( $matches[0] . '<div class="woodmart-row-gradient wd-fill" style="' . $gradient_css . '"></div>' );
				}, $output );
		}
		return $output;
	}
}

add_filter( 'vc_shortcode_output', 'woodmart_get_gradient_attr', 10, 3 );

// **********************************************************************//
//  Function return gradient css.
// **********************************************************************//
if( ! function_exists( 'woodmart_get_gradient_css' ) ) {
	function woodmart_get_gradient_css( $gradient_attr ) {
		$gradient_css = explode( '|', $gradient_attr );
		$result =  'background-image:-webkit-' . $gradient_css[1] . ';';
		$result .= 'background-image:-moz-' . $gradient_css[1] . ';';
		$result .= 'background-image:-o-' . $gradient_css[1] . ';';
		$result .= 'background-image:'.$gradient_css[1] . ';';
		$result .= 'background-image:-ms-' . $gradient_css[1] . ';';
		return $result;
	}
}

// **********************************************************************//
//  Function return all images sizes
// **********************************************************************//
function woodmart_get_all_image_sizes() {
    global $_wp_additional_image_sizes;

    $default_image_sizes = array( 'thumbnail', 'medium', 'large', 'full' );

    foreach ( $default_image_sizes as $size ) {
        $image_sizes[ $size ][ 'width' ] = intval( get_option( "{$size}_size_w" ) );
        $image_sizes[ $size ][ 'height' ] = intval( get_option( "{$size}_size_h" ) );
        $image_sizes[ $size ][ 'crop' ] = get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
    }

    if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {
        $image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
    }

    return $image_sizes;
}

if( ! function_exists( 'woodmart_get_image_size' ) ) {
	function woodmart_get_image_size( $thumb_size ) {
		if ( is_string( $thumb_size ) && in_array( $thumb_size, array( 'thumbnail', 'thumb', 'medium', 'large', 'full' ) ) ) {
			$images_sizes = woodmart_get_all_image_sizes();
			$image_size = $images_sizes[$thumb_size];
			if ( $thumb_size == 'full') {
				$image_size['width'] = 999999;
				$image_size['height'] = 999999;
			}
			return array( $image_size['width'], $image_size['height'] );
		}elseif ( is_string( $thumb_size ) ) {
			preg_match_all( '/\d+/', $thumb_size, $thumb_matches );
			if ( isset( $thumb_matches[0] ) ) {
				$thumb_size = array();
				if ( count( $thumb_matches[0] ) > 1 ) {
					$thumb_size[] = $thumb_matches[0][0]; // width
					$thumb_size[] = $thumb_matches[0][1]; // height
				} elseif ( count( $thumb_matches[0] ) > 0 && count( $thumb_matches[0] ) < 2 ) {
					$thumb_size[] = $thumb_matches[0][0]; // width
					$thumb_size[] = $thumb_matches[0][0]; // height
				} else {
					$thumb_size = false;
				}
			}
		}

		return $thumb_size;
	}
}

if( ! function_exists( 'woodmart_get_image_src' ) ) {
	function woodmart_get_image_src( $thumb_id, $thumb_size ) {
		$thumb_size = woodmart_get_image_size( $thumb_size );
		$thumbnail = wpb_resize( $thumb_id, null, $thumb_size[0], $thumb_size[1], true );
		return isset( $thumbnail['url'] ) ? $thumbnail['url'] : '';
	}
}

// **********************************************************************//
// ! Append :hover to CSS selectors array
// **********************************************************************//
if( ! function_exists( 'woodmart_append_hover_state' ) ) {
	function woodmart_append_hover_state( $selectors , $focus = false ) {
		$selectors = explode(',', $selectors[0]);

		$return = array();

		foreach ($selectors as $selector) {
			$return[] = $selector . ':hover';
			( $focus ) ? $return[] .= $selector . ':focus' : false ;
		}

		return implode(',', $return);
	}
}


// **********************************************************************//
// Woodmart twitter process links
// **********************************************************************//
if( ! function_exists( 'woodmart_twitter_process_links' ) ) {
	function woodmart_twitter_process_links( $tweet ) {

		// Is the Tweet a ReTweet - then grab the full text of the original Tweet
		if( isset( $tweet->retweeted_status ) ) {
			// Split it so indices count correctly for @mentions etc.
			$rt_section = current( explode( ":", $tweet->text ) );
			$text = $rt_section.": ";
			// Get Text
			$text .= $tweet->retweeted_status->text;
		} else {
			// Not a retweet - get Tweet
			$text = $tweet->text;
		}

		// NEW Link Creation from clickable items in the text
		$text = preg_replace( '/((http)+(s)?:\/\/[^<>\s]+)/i', '<a href="$0" target="_blank" rel="nofollow noopener">$0</a>', $text );
		// Clickable Twitter names
		$text = preg_replace( '/[@]+([A-Za-z0-9-_]+)/', '<a href="https://twitter.com/$1" target="_blank" rel="nofollow noopener">@\\1</a>', $text );
		// Clickable Twitter hash tags
		$text = preg_replace( '/[#]+([A-Za-z0-9-_]+)/', '<a href="https://twitter.com/search?q=%23$1" target="_blank" rel="nofollow noopener">$0</a>', $text );
		// END TWEET CONTENT REGEX
		return $text;

	}
}

// **********************************************************************//
// Woodmart Owl Items Per Slide
// **********************************************************************//
if( ! function_exists( 'woodmart_owl_items_per_slide' ) ) {
	function woodmart_owl_items_per_slide( $slides_per_view, $hide = array(), $post_type = false, $location = false, $custom_sizes = false ) {
		$items = woodmart_get_owl_items_numbers( $slides_per_view, $post_type, $custom_sizes );
		$classes = '';

		if ( woodmart_get_opt( 'thums_position' ) == 'centered' && $location == 'main-gallery' ) {
			$items['desktop'] = $items['tablet_landscape'] = $items['tablet'] = $items['mobile'] = 2;
		}

		if ( ! in_array( 'lg', $hide ) ) $classes .= 'owl-items-lg-' . $items['desktop'];
		if ( ! in_array( 'md', $hide ) ) $classes .= ' owl-items-md-' . $items['tablet_landscape'];
 		$classes .= ' owl-items-sm-' . $items['tablet'];
 		$classes .= ' owl-items-xs-' . $items['mobile'];

		return $classes;
	}
}
// **********************************************************************//
// Woodmart Get Owl Items Numbers
// **********************************************************************//
if ( ! function_exists( 'woodmart_get_owl_items_numbers' ) ) {
	function woodmart_get_owl_items_numbers( $slides_per_view, $post_type = false, $custom_sizes = false ) {
		$items = array();

		$items['desktop'] = ( $slides_per_view > 0 ) ? $slides_per_view : 1;
		$items['tablet_landscape'] = $items['desktop'];

		if ( $items['desktop'] >= 5 ) {
			$items['tablet_landscape'] = 4;
		}

		$items['tablet'] = ( $items['tablet_landscape'] > 1 ) ? $items['tablet_landscape'] - 1 : $items['tablet_landscape'];
		$items['mobile'] = ( $items['desktop'] > 4 ) ? 2 : 1;

		if ( $post_type == 'product' ) {
			$items['mobile'] = woodmart_get_opt( 'products_columns_mobile' );
		}

		if ( $items['desktop'] == 1 ) {
			$items['mobile'] = 1;
		}

		if ( $custom_sizes ) {
			return $custom_sizes;
		}

		return $items;
	}
}



// **********************************************************************//
// Woodmart Get Settings JS
// **********************************************************************//
if ( ! function_exists('woodmart_settings_js') ) {
	function woodmart_settings_js() {

        $custom_js          = woodmart_get_opt( 'custom_js' );
        $js_ready           = woodmart_get_opt( 'js_ready' );

		ob_start();

        if( ! empty( $custom_js ) || ! empty( $js_ready ) ): ?>
            <?php if( ! empty( $custom_js ) ): ?>
                <?php echo woodmart_get_opt( 'custom_js' ); ?>
            <?php endif; ?>
            <?php if( ! empty( $js_ready ) ): ?>
                jQuery(document).ready(function() {
                    <?php echo woodmart_get_opt( 'js_ready' ); ?>
                });
            <?php endif; ?>
        <?php endif;

        return ob_get_clean();
	}
}

// **********************************************************************//
// Header classes
// **********************************************************************//
if ( ! function_exists( 'woodmart_get_header_classes' ) ) {
	function woodmart_get_header_classes(){
		$settings = whb_get_settings();
		$custom_product_header = woodmart_get_opt( 'single_product_header');

		$header_class = 'whb-header';
		$header_class .= ( $settings['overlap'] ) ? ' whb-overcontent' : '';
		$header_class .= ( $settings['overlap'] && $settings['boxed'] ) ? ' whb-boxed' : '';
		$header_class .= ( $settings['full_width'] ) ? ' whb-full-width' : '';
		$header_class .= ( $settings['sticky_shadow'] ) ? ' whb-sticky-shadow' : '';
		$header_class .= ( $settings['sticky_effect'] ) ? ' whb-scroll-' . $settings['sticky_effect'] : '';
		$header_class .= ( $settings['sticky_clone'] && $settings['sticky_effect'] == 'slide' ) ? ' whb-sticky-clone' : ' whb-sticky-real';
		$header_class .= ( $settings['hide_on_scroll'] ) ? ' whb-hide-on-scroll' : '';

		woodmart_enqueue_js_script( 'header-builder' );

		if ( ! empty( $custom_product_header ) && $custom_product_header != 'none' && woodmart_woocommerce_installed() && is_product() ) {
			$header_class .= ' whb-custom-header';
		}

		echo 'class="' . esc_attr( $header_class ) . '"';
	}
}

// **********************************************************************//
// Print script tag with content
// **********************************************************************//
if ( ! function_exists( 'woodmart_add_inline_script' ) ) {
	function woodmart_add_inline_script( $key, $content, $position = 'after' ) {
		?>
			<script>
				<?php echo apply_filters( 'woodmart_inline_script', $content ); ?>
			</script>
		<?php
	}
}

// **********************************************************************//
// Print text size css
// **********************************************************************//
if ( ! function_exists( 'woodmart_responsive_text_size_css' ) ) {
	function woodmart_responsive_text_size_css( $id, $class, $data, $action = 'echo' ) {
		if ( 'return' == $action ) {
			return '#'. $id . ' .'. $class .'{font-size:' . $data . 'px;line-height:' . intval( $data + 10 ) . 'px;}';
		} else {
			echo '#'. $id . ' .'. $class .'{font-size:' . $data . 'px;line-height:' . intval( $data + 10 ) . 'px;}';
		}
	}
}

// **********************************************************************//
// ! Function to get all pages
// **********************************************************************//

if( ! function_exists( 'woodmart_get_pages' ) ) {
	function woodmart_get_pages( $new = false ) {
		if( $new ) {
			$pages = array();
		} else {
			$pages = array( 'default' => esc_html__( 'Default', 'woodmart' ) );
		}
		foreach( get_pages() as $page ){
			if( $new ) {
				$pages[ $page->ID ] = array(
					'name'  => $page->post_title,
					'value' => $page->ID,
				);
			} else {
				$pages[$page->ID] = $page->post_title;
			}
		}

		return $pages;
	}
}

// **********************************************************************//
// ! Function to set custom 404 page
// **********************************************************************//
if( ! function_exists( 'woodmart_custom_404_page' ) ) {
	function woodmart_custom_404_page( $template ) {
		global $wp_query;
		$custom_404 = woodmart_get_opt( 'custom_404_page' );
		if ( $custom_404 == 'default' || empty( $custom_404 )  ) return $template;

		$wp_query->query( 'page_id=' . $custom_404 );
		$wp_query->the_post();
		$template = get_page_template();
		rewind_posts();

		return $template;
	}
	add_filter( '404_template', 'woodmart_custom_404_page', 999 );
}

if ( ! function_exists( 'woodmart_android_browser_bar_color' ) ) {
	/**
	 * Display cart widget side
	 *
	 * @since 1.0.0
	 */
	function woodmart_android_browser_bar_color() {
		$color = woodmart_get_opt( 'android_browser_bar_color' );

		if ( isset( $color['idle'] ) ) {
			echo '<meta name="theme-color" content="'. $color['idle'] .'">';
		}
	}
	add_filter( 'wp_head', 'woodmart_android_browser_bar_color' );
}

if ( ! function_exists( 'woodmart_settings_css' ) ) {
	function woodmart_settings_css() {
		$custom_product_background = get_post_meta( get_the_ID(),  '_woodmart_product-background', true );

		ob_start();

		echo '<style>';

		?>

		<?php if ( ! empty( $custom_product_background ) ): ?>
			.single-product .main-page-wrapper{
			background-color: <?php echo esc_html( $custom_product_background ); ?> !important;
			}
		<?php endif ?>

		<?php

		echo '</style>';

		echo ob_get_clean();
	}
	add_action( 'wp_head', 'woodmart_settings_css', 200 );
}
