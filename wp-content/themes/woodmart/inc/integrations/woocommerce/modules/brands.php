<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Brand image
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_product_brand' ) ) {
	function woodmart_product_brand() {
		global $product;
		$attr = woodmart_get_opt( 'brands_attribute' );
		if( ! $attr || ! woodmart_get_opt( 'product_page_brand' ) ) return;

		$attributes = $product->get_attributes();

		if( ! isset( $attributes[ $attr ] ) || empty( $attributes[ $attr ] ) ) return;

		$brands = wc_get_product_terms( $product->get_id(), $attr, array( 'fields' => 'all' ) );
		$taxonomy = get_taxonomy( $attr );

		if( empty( $brands ) ) return;

		if ( woodmart_is_shop_on_front() ) {
			$link = home_url();
		} else {
			$link = get_post_type_archive_link( 'product' );
		}

		$classes = ( woodmart_get_opt( 'product_brand_location' ) == 'sidebar' && ! woodmart_loop_prop( 'is_quick_view' ) ) ? 'widget sidebar-widget' : '';

		$classes .= woodmart_get_old_classes( ' woodmart-product-brands' );

		echo '<div class="wd-product-brands '. esc_attr( $classes ) .'">';

		foreach ($brands as $brand) {
			$image = get_term_meta( $brand->term_id, 'image', true);
			$filter_name    = 'filter_' . sanitize_title( str_replace( 'pa_', '', $attr ) );
			$attrs = '';

			if ( get_term_meta( $brand->term_id, 'image_id', true ) ) {
				$data = wp_get_attachment_image_src( get_term_meta( $brand->term_id, 'image_id', true ) );
				$attrs = ' width="' . $data['1'] . '" height="' . $data['2'] . '"';
			}

			if ( is_object( $taxonomy ) && $taxonomy->public ) {
				$attr_link = get_term_link( $brand->term_id, $brand->taxonomy );
			} else {
				$attr_link = add_query_arg( $filter_name, $brand->slug, $link );
			}

			$content = esc_attr( $brand->name );

			if ( $image ) {
				$content = '<img src="' . esc_url( $image ) . '" title="' . esc_attr( $brand->name ) . '" alt="' . esc_attr( $brand->name ) . '" ' . $attrs . '>';
			}

			echo '<div class="woodmart-product-brand">';
				echo '<a href="' . esc_url( $attr_link ) . '">' . $content . '</a>';
			echo '</div>';
		}

		echo '</div>';
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Show product brand
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_product_brands_links' ) ) {
	function woodmart_product_brands_links() {
		global $product;
		$brand_option = woodmart_get_opt( 'brands_attribute' );
		$brands = wc_get_product_terms( $product->get_id(), $brand_option, array( 'fields' => 'all' ) );
		$taxonomy = get_taxonomy( $brand_option );
		
		if( !woodmart_get_opt( 'brands_under_title' ) || empty( $brands ) ) return;

		$link = ( woodmart_is_shop_on_front() ) ? home_url() : get_post_type_archive_link( 'product' );

		echo '<div class="wd-product-brands-links' . woodmart_get_old_classes( ' woodmart-product-brands-links' ) . '">';

		foreach ( $brands as $brand ) {
			$filter_name = 'filter_' . sanitize_title( str_replace( 'pa_', '', $brand_option ) );

			if ( is_object( $taxonomy ) && $taxonomy->public ) {
				$attr_link = get_term_link( $brand->term_id, $brand->taxonomy );
			} else {
				$attr_link = add_query_arg( $filter_name, $brand->slug, $link );
			}

			$sep = ', ';
			if ( end( $brands ) == $brand ) $sep = '';

			echo '<a href="' . esc_url( $attr_link ) . '">' . $brand->name . '</a>' . $sep;
		}

		echo '</div>';
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Add product brand tab to the single product page
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_product_brand_tab' ) ) {
	function woodmart_product_brand_tab( $tabs ) {
		global $product;
		
		$show_tab = false;
		$brand_title = esc_html__( 'About brand', 'woodmart' );
		$brand_info = wc_get_product_terms( $product->get_id(), woodmart_get_opt( 'brands_attribute' ), array( 'fields' => 'all' ) );
		if ( !isset( $brand_info[0] ) ) return $tabs;
		
		if ( $brand_info[0]->description ) $show_tab = true;
		if ( woodmart_get_opt( 'brand_tab_name' ) ) $brand_title = sprintf( esc_html__( 'About %s', 'woodmart' ), $brand_info[0]->name );
		
		if ( $show_tab ) {
			$tabs['brand_tab'] = array(
				'title' 	=> $brand_title,
				'priority' 	=> 50,
				'callback' 	=> 'woodmart_product_brand_tab_content'
			);
		}

		return $tabs;
	}
}

if( ! function_exists( 'woodmart_product_brand_tab_content' ) ) {
	function woodmart_product_brand_tab_content() {
		global $product;
		$attr = woodmart_get_opt( 'brands_attribute' );
		if ( ! $attr ) return;

		$attributes = $product->get_attributes();

		if ( ! isset( $attributes[ $attr ] ) || empty( $attributes[ $attr ] ) ) return;

		$brands = wc_get_product_terms( $product->get_id(), $attr, array( 'fields' => 'slugs' ) );

		if ( empty( $brands ) ) return;

		foreach ($brands as $id => $slug) {
			echo '<div class="woodmart-product-brand-description">';
				$brand = get_term_by('slug', $slug, $attr);
				echo do_shortcode( $brand->description );
			echo '</div>';
		}

	}
}
