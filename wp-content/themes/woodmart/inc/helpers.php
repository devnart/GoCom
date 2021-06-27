<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

if ( ! function_exists( 'woodmart_get_old_classes' ) ) {
	/**
	 * Get old classes.
	 *
	 * @since 1.0.0
	 *
	 * @param string $classes Classes.
	 *
	 * @return string
	 */
	function woodmart_get_old_classes( $classes ) {
		if ( ! woodmart_get_opt( 'old_elements_classes', true ) ) {
			$classes = '';
		}

		return esc_html( $classes );
	}
}

if ( ! function_exists( 'woodmart_get_headers_array' ) ) {
	/**
	 * Get custom header array created with header builder
	 *
	 * @since 1.0.0
	 *
	 * @param boolean $options Is function called in theme options.
	 *
	 * @return array
	 */
	function woodmart_get_headers_array( $options = false ) {
		if ( $options ) {
			$list = get_option( 'whb_saved_headers' );
		} else {
			$list = whb_get_builder()->list->get_all();
		}

		$output = array();

		foreach ( $list as $key => $header ) {
			$output[ $key ] = array(
				'name'  => $header['name'],
				'value' => $key,
			);
		}

		return $output;
	}
}

if ( ! function_exists( 'woodmart_get_current_url' ) ) {
	/**
	 * Get current url.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function woodmart_get_current_url() {
		global $wp;

		return home_url( $wp->request );
	}
}

if ( ! function_exists( 'woodmart_get_document_title' ) ) {
	/**
	 * Returns document title for the current page.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function woodmart_get_document_title() {
		$title = wp_get_document_title();

		$post_meta = get_post_meta( woodmart_get_the_ID(), '_yoast_wpseo_title', true );
		if ( property_exists( get_queried_object(), 'term_id' ) && function_exists( 'YoastSEO' ) ) {
			$taxonomy_helper = YoastSEO()->helpers->taxonomy;
			$meta            = $taxonomy_helper->get_term_meta( get_queried_object() );

			if ( isset( $meta['wpseo_title'] ) && $meta['wpseo_title'] ) {
				$title = wpseo_replace_vars( $meta['wpseo_title'], get_queried_object() );
			}
		} elseif ( $post_meta && function_exists( 'wpseo_replace_vars' ) ) {
			$title = wpseo_replace_vars( $post_meta, get_post( woodmart_get_the_ID() ) );
		}

		return $title;
	}
}

if ( ! function_exists( 'woodmart_get_new_size_classes' ) ) {
	function woodmart_get_new_size_classes( $element, $old_key, $selector ) {
		$array = array(
			'banner'  => array(
				'small'       => array(
					'subtitle' => 'xs',
					'title'    => 's',
				),
				'default'     => array(
					'subtitle' => 'xs',
					'title'    => 'l',
					'content'  => 'xs',
				),
				'large'       => array(
					'subtitle' => 's',
					'title'    => 'xl',
					'content'  => 'm',
				),
				'extra-large' => array(
					'subtitle' => 'm',
					'title'    => 'xxl',
				),
				'medium'      => array(
					'content' => 's',
				),
			),
			'infobox' => array(
				'small'       => array(
					'subtitle' => 'xs',
					'title'    => 's',
				),
				'default'     => array(
					'subtitle' => 'xs',
					'title'    => 'm',
				),
				'large'       => array(
					'subtitle' => 's',
					'title'    => 'xl',
				),
				'extra-large' => array(
					'subtitle' => 'm',
					'title'    => 'xxl',
				),
			),
			'title'   => array(
				'small'       => array(
					'subtitle'    => 'xs',
					'title'       => 'm',
					'after_title' => 'xs',
				),
				'default'     => array(
					'subtitle'    => 'xs',
					'title'       => 'l',
					'after_title' => 'xs',
				),
				'medium'      => array(
					'subtitle'    => 'xs',
					'title'       => 'xl',
					'after_title' => 's',
				),
				'large'       => array(
					'subtitle'    => 'xs',
					'title'       => 'xxl',
					'after_title' => 's',
				),
				'extra-large' => array(
					'subtitle'    => 'm',
					'title'       => 'xxxl',
					'after_title' => 's',
				),
			),
			'text'    => array(
				'small'       => array(
					'title' => 'm',
				),
				'default'     => array(
					'title' => 'l',
				),
				'medium'      => array(
					'title' => 'xl',
				),
				'large'       => array(
					'title' => 'xxl',
				),
				'extra-large' => array(
					'title' => 'xxxl',
				),
			),
			'list'    => array(
				'default'     => array(
					'text' => 'xs',
				),
				'medium'     => array(
					'text' => 's',
				),
				'large'      => array(
					'text' => 'm',
				),
				'extra-large' => array(
					'text' => 'l',
				),
			),
			'testimonials'    => array(
				'small'       => array(
					'text' => 'xs',
				),
				'medium'     => array(
					'text' => 's',
				),
				'large'      => array(
					'text' => 'm',
				),
			),
		);

		return isset( $array[ $element ][ $old_key ][ $selector ] ) ? 'wd-fontsize-' . $array[ $element ][ $old_key ][ $selector ] : '';
	}
}

if ( ! function_exists('array_key_first' ) ) {
	function array_key_first( array $arr ) {
		foreach( $arr as $key => $unused ) {
			return $key;
		}
		return NULL;
	}
}

if ( ! function_exists( 'woodmart_is_elementor_full_width' ) ) {
	/**
	 * Check if Elementor full width.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	function woodmart_is_elementor_full_width() {
		$page_template = get_post_meta( woodmart_get_the_ID(), '_wp_page_template', true );

		if ( woodmart_is_elementor_pro_installed() ) {
			$manager = \ElementorPro\Plugin::instance()->modules_manager->get_modules( 'theme-builder' )->get_conditions_manager();

			if ( $manager->get_documents_for_location( 'single' ) || $manager->get_documents_for_location( 'archive' ) ) {
				$page_template = 'elementor_header_footer';
			}
		}

		return 'elementor_header_footer' === $page_template && 'enabled' !== woodmart_get_opt( 'negative_gap', 'enabled' );
	}
}

if ( ! function_exists( 'woodmart_is_elementor_pro_installed' ) ) {
	/**
	 * Check if Elementor PRO is activated
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	function woodmart_is_elementor_pro_installed() {
		return defined( 'ELEMENTOR_PRO_VERSION' );
	}
}

if ( ! function_exists( 'woodmart_vc_build_link' ) ) {
	function woodmart_vc_build_link( $value ) {
		return woodmart_vc_parse_multi_attribute( $value, array(
			'url'    => '',
			'title'  => '',
			'target' => '',
			'rel'    => '',
		) );
	}
}

if ( ! function_exists( 'woodmart_vc_parse_multi_attribute' ) ) {
	function woodmart_vc_parse_multi_attribute( $value, $default = array() ) {
		$result       = $default;
		$params_pairs = explode( '|', $value );
		if ( ! empty( $params_pairs ) ) {
			foreach ( $params_pairs as $pair ) {
				$param = preg_split( '/\:/', $pair );
				if ( ! empty( $param[0] ) && isset( $param[1] ) ) {
					$result[ $param[0] ] = rawurldecode( $param[1] );
				}
			}
		}

		return $result;
	}
}
if ( ! function_exists( 'woodmart_get_size_guides_array' ) ) {
	function woodmart_get_size_guides_array( $style = 'default' ) {
		if ( 'default' === $style ) {
			$output = array(
				esc_html__( 'Select', 'woodmart' ) => '',
			);
		} elseif ( 'elementor' === $style ) {
			$output = array(
				'0' => esc_html__( 'Select', 'woodmart' ),
			);
		}

		$posts  = get_posts(
			array(
				'posts_per_page' => 200,
				'post_type'      => 'woodmart_size_guide',
			)
		);

		foreach ( $posts as $post ) {
			if ( 'default' === $style ) {
				$output[ $post->post_title ] = $post->ID;
			} elseif ( 'elementor' === $style ) {
				$output[ $post->ID ] = $post->post_title;
			}
		}

		return $output;
	}
}

if ( ! function_exists( 'woodmart_is_elementor_installed' ) ) {
	/**
	 * Check if Elementor is activated
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	function woodmart_is_elementor_installed() {
		return did_action( 'elementor/loaded' ) && 'elementor' === woodmart_get_opt( 'page_builder', 'wpb' );
	}
}
// **********************************************************************//
// Remove https
// **********************************************************************//

if( ! function_exists( 'woodmart_remove_https' ) ) {
	function woodmart_remove_https($link) {
		return preg_replace('#^https?:#', '', $link);
	}
}

// **********************************************************************//
// ! If page needs header
// **********************************************************************//

if( ! function_exists( 'woodmart_needs_header' ) ) {
	function woodmart_needs_header() {
		return ( ! woodmart_maintenance_page() && ! is_singular( 'woodmart_slide' ) && ! is_singular( 'cms_block' ) );
	}
}

// **********************************************************************//
// ! If page needs footer
// **********************************************************************//

if( ! function_exists( 'woodmart_needs_footer' ) ) {
	function woodmart_needs_footer() {
		return ( ! woodmart_maintenance_page() && ! is_singular( 'woodmart_slide' ) && ! is_singular( 'cms_block' ) );
	}
}


// **********************************************************************//
// ! Conditional tags
// **********************************************************************//

if( ! function_exists( 'woodmart_is_shop_archive' ) ) {
	function woodmart_is_shop_archive() {
		return ( woodmart_woocommerce_installed() && ( is_shop() || is_product_category() || is_product_tag() || is_singular( "product" ) || woodmart_is_product_attribute_archive() ) );
	}
}

if( ! function_exists( 'woodmart_is_blog_archive' ) ) {
	function woodmart_is_blog_archive() {
		return ( is_home() || is_search() || is_tag() || is_category() || is_date() || is_author() );
	}
}

if( ! function_exists( 'woodmart_is_portfolio_archive' ) ) {
	function woodmart_is_portfolio_archive() {
		return ( is_post_type_archive( 'portfolio' ) || is_tax( 'project-cat' ) );
	}
}

// **********************************************************************//
// ! Is maintenance page
// **********************************************************************//

if( ! function_exists( 'woodmart_maintenance_page' ) ) {
	function woodmart_maintenance_page() {

		$pages_ids = woodmart_pages_ids_from_template( 'maintenance' );

		if( ! empty( $pages_ids ) && is_page( $pages_ids ) ) {
			return true;
		}

		return false;
	}
}

// **********************************************************************//
// ! Get config file
// **********************************************************************//

if( ! function_exists( 'woodmart_get_config' ) ) {
	function woodmart_get_config( $name ) {
		$path = WOODMART_CONFIGS . '/' . $name . '.php';
		if( file_exists( $path ) ) {
			return include $path;
		} else {
			return array();
		}
	}
}


// **********************************************************************//
// ! Text to one-line string
// **********************************************************************//

if( ! function_exists( 'woodmart_text2line')) {
	function woodmart_text2line( $str ) {
		return trim(preg_replace("/('|\"|\r?\n)/", '', $str));
	}
}


// **********************************************************************//
// ! Get page ID by it's template name
// **********************************************************************//
if( ! function_exists( 'woodmart_tpl2id' ) ) {
	function woodmart_tpl2id( $tpl = '' ) {
		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => $tpl
		));
		foreach($pages as $page){
			return $page->ID;
		}
	}
}


// **********************************************************************//
// ! Function print array within a pre tags
// **********************************************************************//
if( ! function_exists( 'ar' ) ) {
	function ar($array) {

		echo '<pre>';
			print_r($array);
		echo '</pre>';

	}
}


// **********************************************************************//
// ! Get protocol (http or https)
// **********************************************************************//
if( ! function_exists( 'woodmart_http' )) {
	function woodmart_http() {
		if( ! is_ssl() ) {
			return 'http';
		} else {
			return 'https';
		}
	}
}

// **********************************************************************//
// Woodmart get theme info
// **********************************************************************//
if( ! function_exists( 'woodmart_get_theme_info' ) ) {
	function woodmart_get_theme_info( $parameter ) {
		$theme_info = wp_get_theme();
		if ( is_child_theme() && is_object( $theme_info->parent() ) ){
			$theme_info = wp_get_theme( $theme_info->parent()->template );
		}
		return $theme_info->get( $parameter );
	}
}

// **********************************************************************//
// Is share button enable
// **********************************************************************//
if ( ! function_exists( 'woodmart_is_social_link_enable' ) ) {
	function woodmart_is_social_link_enable( $type ) {
		$result = false;
		if ( $type == 'share' && ( woodmart_get_opt('share_fb') || woodmart_get_opt('share_twitter') || woodmart_get_opt('share_linkedin') || woodmart_get_opt('share_pinterest') || woodmart_get_opt('share_ok') || woodmart_get_opt('share_whatsapp') || woodmart_get_opt('share_email') || woodmart_get_opt('share_vk') || woodmart_get_opt('share_tg') || woodmart_get_opt('share_viber') ) ) {
			$result = true;
		}

		if  ( $type == 'follow' && ( woodmart_get_opt('fb_link') || woodmart_get_opt('twitter_link') || woodmart_get_opt('google_link') || woodmart_get_opt('isntagram_link') || woodmart_get_opt('pinterest_link') || woodmart_get_opt('youtube_link') || woodmart_get_opt('tumblr_link') || woodmart_get_opt('linkedin_link') || woodmart_get_opt('vimeo_link') || woodmart_get_opt('flickr_link') || woodmart_get_opt('github_link') || woodmart_get_opt('dribbble_link') || woodmart_get_opt('behance_link') || woodmart_get_opt('soundcloud_link') || woodmart_get_opt('spotify_link') || woodmart_get_opt('ok_link') || woodmart_get_opt('whatsapp_link') || woodmart_get_opt('vk_link') || woodmart_get_opt('snapchat_link') || woodmart_get_opt('tg_link') || woodmart_get_opt('tiktok_link') || woodmart_get_opt( 'social_email' ) ) ) {
			$result = true;
		}

		return $result;
	}
}

// **********************************************************************// 
// Is compare iframe
// **********************************************************************// 
if ( ! function_exists( 'woodmart_is_compare_iframe' ) ) {
	function woodmart_is_compare_iframe() {
		return wp_script_is( 'jquery-fixedheadertable', 'enqueued' );
	}
}

// **********************************************************************// 
// Is SVG image
// **********************************************************************// 
if ( ! function_exists( 'woodmart_is_svg' ) ) {
	function woodmart_is_svg( $src ) {
		return substr( $src, -3, 3 ) == 'svg';
	}
}

// **********************************************************************// 
// Get explode size
// **********************************************************************// 
if ( ! function_exists( 'woodmart_get_explode_size' ) ) {
	function woodmart_get_explode_size( $img_size, $default_size ) {
		$sizes = explode( 'x', $img_size );
		if( count( $sizes ) < 2 ) $sizes[0] = $sizes[1] = $default_size;
		return $sizes;
	}
}

// **********************************************************************// 
// Check is theme is activated with a purchase code
// **********************************************************************// 

if ( ! function_exists( 'woodmart_is_license_activated' ) ) {
	function woodmart_is_license_activated() {
	    return true;
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Is shop on front page
 * ------------------------------------------------------------------------------------------------
 */
if ( ! function_exists( 'woodmart_is_shop_on_front' ) ) {
	function woodmart_is_shop_on_front() {
		return function_exists( 'wc_get_page_id' ) && 'page' === get_option( 'show_on_front' ) && wc_get_page_id( 'shop' ) == get_option( 'page_on_front' );
	}
}

if ( ! function_exists( 'woodmart_get_allowed_html' ) ) {
	/**
	 * Return allowed html tags
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function woodmart_get_allowed_html() {
		return apply_filters(
			'woodmart_allowed_html',
			array(
				'br'     => array(),
				'i'      => array(),
				'b'      => array(),
				'u'      => array(),
				'em'     => array(),
				'del'    => array(),
				'a'      => array(
					'href'  => true,
					'class' => true,
					'title' => true,
					'rel'   => true,
				),
				'strong' => array(),
				'span'   => array(
					'style' => true,
					'class' => true,
				),
			)
		);
	}
}


if ( ! function_exists( 'woodmart_clean' ) ) {
	/**
	 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
	 * Non-scalar values are ignored.
	 *
	 * @param string|array $var Data to sanitize.
	 * @return string|array
	 */
	function woodmart_clean( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'woodmart_clean', $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}
}
