<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* ------------------------------------------------------------------------------------------------
* Blog shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_shortcode_blog' ) ) {
	function woodmart_shortcode_blog( $atts ) {
	    $parsed_atts = shortcode_atts( array(
	        'post_type'  => 'post',
	        'include'  => '',
	        'custom_query'  => '',
	        'taxonomies'  => '',
	        'pagination'  => '',
			'parts_media'  => true,
	        'parts_title'  => true,
	        'parts_meta'  => true,
	        'parts_text'  => true,
	        'parts_btn'  => true,
	        'items_per_page'  => 12,
	        'offset'  => '',
	        'orderby'  => 'date',
	        'order'  => 'DESC',
	        'meta_key'  => '',
	        'exclude'  => '',
	        'class'  => '',
	        'ajax_page' => '',
	        'img_size' => 'medium',
			'blog_design'  => 'default',
			'blog_carousel_design' => 'masonry',
			'blog_columns'  => woodmart_get_opt( 'blog_columns' ),
			'blog_spacing'  => woodmart_get_opt( 'blog_spacing' ),
			'speed' => '5000',
			'slides_per_view' => '3',
			'wrap' => '',
			'autoplay' => 'no',
			'hide_pagination_control' => '',
			'hide_prev_next_buttons' => '',
			'lazy_loading' => 'no',
			'scroll_carousel_init' => 'no',
			'scroll_per_page' => 'yes',
		    'search' => '',
	    ), $atts );

	    extract( $parsed_atts );

	    $encoded_atts = json_encode( $parsed_atts );

	    $is_ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX );

	    $output = '';

		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		$id = uniqid();

		if( $ajax_page > 1 ) $paged = $ajax_page;

	    $args = array(
	    	'post_type' => 'post',
	    	'post_status' => 'publish',
	    	'paged' => $paged,
	    	'posts_per_page' => $items_per_page
		);

		if( $post_type == 'ids' && $include != '' ) {
			$args['post__in'] = array_map('trim', explode(',', $include) );
		}

		if( ! empty( $exclude ) ) {
			$args['post__not_in'] = array_map('trim', explode(',', $exclude) );
		}

		if( ! empty( $taxonomies ) ) {
			$taxonomy_names = get_object_taxonomies( 'post' );
			$terms = get_terms( $taxonomy_names, array(
				'orderby' => 'name',
				'include' => $taxonomies
			) );

			if( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
				$args['tax_query'] = array( 'relation' => 'OR' );
				foreach ( $terms as $key => $term ) {
					$args['tax_query'][] = array(
				        'taxonomy' => $term->taxonomy,
				        'field' => 'slug',
				        'terms' => array( $term->slug ),
				        'include_children' => true,
				        'operator' => 'IN'
					);
				}
			}
		}

		if( ! empty( $order ) ) {
			$args['order'] = $order;
		}

		if( ! empty( $offset ) ) {
			$args['offset'] = $offset;
		}

		if( ! empty( $meta_key ) ) {
			$args['meta_key'] = $meta_key;
		}

		if( ! empty( $orderby ) ) {
			$args['orderby'] = $orderby;
		}
		
		if ( ! empty( $search ) ) {
			$args['s'] = sanitize_text_field( $search );
		}

	    $blog_query = new WP_Query( $args );
		
		ob_start();

		woodmart_enqueue_inline_style( 'blog-base' );

		woodmart_set_loop_prop( 'blog_type', 'shortcode' );
		woodmart_set_loop_prop( 'blog_design', $blog_design );
		woodmart_set_loop_prop( 'img_size', $img_size );
		woodmart_set_loop_prop( 'blog_columns', $blog_columns );
		woodmart_set_loop_prop( 'woodmart_loop', 0 );
		woodmart_set_loop_prop( 'parts_title', $parts_title );
		woodmart_set_loop_prop( 'parts_meta', $parts_meta );
		woodmart_set_loop_prop( 'parts_text', $parts_text );
		woodmart_set_loop_prop( 'parts_media', $parts_media );
		
		if ( $blog_design == 'carousel' ) {
			woodmart_set_loop_prop( 'blog_design', $blog_carousel_design );
		}

		$parsed_atts['custom_sizes'] = apply_filters( 'woodmart_blog_shortcode_custom_sizes', false );

	    if( ! $parts_btn ) woodmart_set_loop_prop( 'parts_btn', false );

	    if( $blog_design == 'carousel' ) {
			return woodmart_generate_posts_slider( $parsed_atts, $blog_query );
	    } else {

			if ( $lazy_loading == 'yes' ) {
				woodmart_lazy_loading_init( true );
				woodmart_enqueue_inline_style( 'lazy-loading' );
			}

		    if ( $blog_design == 'masonry' || $blog_design == 'mask' ) {
		    	$class .= ' masonry-container';
				$class .= ' wd-spacing-' . $blog_spacing;
				$class .= ' row';
			    wp_enqueue_script( 'imagesloaded' );
			    woodmart_enqueue_js_library( 'isotope-bundle' );
			    woodmart_enqueue_js_script( 'masonry-layout' );
			}

			$class .= ' blog-pagination-' . $pagination;

		    if( ! $is_ajax ) echo '<div class="wd-blog-holder blog-shortcode ' . esc_attr( $class ) . '" id="' . esc_attr( $id ) . '" data-paged="1" data-atts="' . esc_attr( $encoded_atts ) . '" data-source="shortcode">';

			while ( $blog_query->have_posts() ) {
				$blog_query->the_post();

				get_template_part( 'content' );
			}

	    	if( ! $is_ajax ) echo '</div>';

			if ( $blog_query->max_num_pages > 1 && ! $is_ajax && $pagination ) {
				?>
			    	<div class="blog-footer">
			    		<?php if ( $pagination == 'infinit' || $pagination == 'more-btn' ): ?>
						    <?php wp_enqueue_script( 'imagesloaded' ); ?>
						    <?php woodmart_enqueue_js_script( 'blog-load-more' ); ?>
						    <?php if ( 'infinit' === $pagination ) : ?>
								<?php woodmart_enqueue_js_library( 'waypoints' ); ?>
						    <?php endif; ?>
							<a href="#" data-holder-id="<?php echo esc_attr( $id ); ?>" rel="nofollow noopener" class="btn wd-load-more wd-blog-load-more load-on-<?php echo 'more-btn' === $pagination ? 'click' : 'scroll'; ?>"><span class="load-more-label"><?php esc_html_e( 'Load more posts', 'woodmart' ); ?></span></a>
							<div class="btn wd-load-more wd-load-more-loader"><span class="load-more-loading"><?php esc_html_e('Loading...', 'woodmart'); ?></span></div>
		    			<?php else: ?>
			    			<?php query_pagination( $blog_query->max_num_pages ); ?>
			    		<?php endif ?>
			    	</div>
			    <?php
			}

	    }

	    wp_reset_postdata();

		woodmart_reset_loop();

		if ( $lazy_loading == 'yes' ) {
			woodmart_lazy_loading_deinit();
		}
		
	    $output .= ob_get_clean();

	    if( $is_ajax ) {
	    	$output =  array(
	    		'items' => $output,
	    		'status' => ( $blog_query->max_num_pages > $paged ) ? 'have-posts' : 'no-more-posts'
	    	);
	    }

	    return $output;

	}
}
