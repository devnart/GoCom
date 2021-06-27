<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
 * ------------------------------------------------------------------------------------------------
 * Brands carousel/grid/list shortcode
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_shortcode_brands' ) ) {
	function woodmart_shortcode_brands( $atts, $content = '' ) {
		$item_class = $items_wrap_class = $owl_atts = '';
		$parsed_atts = shortcode_atts( array_merge( woodmart_get_owl_atts(), array(
			'title' => '',
			'username' => 'flickr',
			'number' => 20,
			'hover' => 'default',
			'target' => '_self',
			'link' => '',
			'ids' => '',
			'style' => 'carousel',
			'brand_style' => 'default',
			'per_row' => 3,
			'columns' => 3,
			'orderby' => '',
			'order' => 'ASC',
			'hide_empty' => 'no',
			'scroll_carousel_init' => 'no',
		) ), $atts );
		
		extract( $parsed_atts );
		
		$carousel_id = 'brands_' . rand(1000,9999);
		
		$attribute = woodmart_get_opt( 'brands_attribute' );
		
		if( empty( $attribute ) || ! taxonomy_exists( $attribute ) ) return '<div class="wd-notice wd-info">' . esc_html__( 'You must select your brand attribute in Theme Settings -> Shop -> Brands', 'woodmart' ) . '</div>';
		
		ob_start();
		
		$class = 'brands-widget slider-' . $carousel_id;
		
		if( $style != '' ) {
			$class .= ' brands-' . $style;
		}
		
		$class .= ' brands-hover-' . $hover;
		$class .= ' brands-style-' . $brand_style;
		
		
		if ( $style == 'carousel' ) {
			woodmart_enqueue_inline_style( 'owl-carousel' );
			$custom_sizes = apply_filters( 'woodmart_brands_shortcode_custom_sizes', false );

			$parsed_atts['wrap'] = $wrap;
			$parsed_atts['scroll_per_page'] = 'yes';
			$parsed_atts['carousel_id'] = $carousel_id;
			$parsed_atts['slides_per_view'] = $per_row;
			$parsed_atts['custom_sizes'] = $custom_sizes;
			$owl_atts = woodmart_get_owl_attributes( $parsed_atts );
			
			$items_wrap_class .= ' owl-carousel ' . woodmart_owl_items_per_slide( $per_row, array(), false, false, $custom_sizes );
			$class .= ' wd-carousel-container';
			$class .= ' wd-carousel-spacing-0';
			
			if ( $scroll_carousel_init == 'yes' ) {
				woodmart_enqueue_js_library( 'waypoints' );
				$class .= ' scroll-init';
			}
			
			if ( woodmart_get_opt( 'disable_owl_mobile_devices' ) ) {
				$class .= ' disable-owl-mobile';
			}
		} else {
			$items_wrap_class .= ' row';
			$items_wrap_class .= ' wd-spacing-0';
			$item_class .= woodmart_get_grid_el_class( 0, $columns );
		}
		
		echo '<div id="'. esc_attr( $carousel_id ) . '" class="brands-items-wrapper ' . esc_attr( $class ) . '" ' . $owl_atts . '>';
		
		if(!empty($title)) { echo '<h3 class="title">' . $title . '</h3>'; };
		
		$args = array(
			'taxonomy' => $attribute,
			'hide_empty' => 'yes' === $hide_empty,
			'order' => $order,
			'number' => $number
		);
		
		if ( $orderby ) $args['orderby'] = $orderby;
		
		if ( $orderby == 'random' ) {
			$args['orderby'] = 'id';
			$brand_count = wp_count_terms( $attribute, array(
				'hide_empty' => 'yes' === $hide_empty
			) );
			
			$offset = rand( 0, $brand_count - $number );
			if ( $offset <= 0 ) {
				$offset = '';
			}
			$args['offset'] = $offset;
		}
		
		
		if( ! empty( $ids ) ) {
			$args['include'] = explode(',', $ids);
		}
		
		$brands = get_terms( $args );
		$taxonomy = get_taxonomy( $attribute );
		
		if ( $orderby == 'random' ) shuffle( $brands );
		
		if ( woodmart_is_shop_on_front() ) {
			$link = home_url();
		} else {
			$link = get_post_type_archive_link( 'product' );
		}

		woodmart_enqueue_inline_style( 'brands' );
		
		echo '<div class="' . esc_attr( $items_wrap_class )  . '">';
		
		if( ! is_wp_error( $brands ) && count( $brands ) > 0 ) {
			foreach ($brands as $key => $brand) {
				$image = get_term_meta( $brand->term_id, 'image', true );

				$filter_name = 'filter_' . sanitize_title( str_replace( 'pa_', '', $attribute ) );
				
				if ( is_object( $taxonomy ) && $taxonomy->public ) {
					$attr_link = get_term_link( $brand->term_id, $brand->taxonomy );
				} else {
					$attr_link = add_query_arg( $filter_name, $brand->slug, $link );
				}
				
				echo '<div class="brand-item' . esc_attr( $item_class )  . '">';
				echo '<a href="' . esc_url( $attr_link ) . '">';
				if( $style == 'list' || empty( $image ) ) {
					echo '<span class="brand-title-wrap">' . $brand->name . '</span>';
				} elseif ( attachment_url_to_postid( $image ) ) {
					echo wp_get_attachment_image( attachment_url_to_postid( $image ), 'full', false, array( 'title' => $brand->name, 'alt' => $brand->name ) );
				} else {
					echo '<img src="' . $image . '" alt="' . $brand->name . '" title="' . $brand->name . '">';
				}
				echo '</a>';
				echo '</div>';
			}
		}
		
		echo '</div></div>';
		
		$output = ob_get_contents();
		ob_end_clean();
		
		return $output;
		
	}
}