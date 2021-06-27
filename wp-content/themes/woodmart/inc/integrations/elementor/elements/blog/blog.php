<?php
/**
 * Blog template function.
 *
 * @package xts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'woodmart_elementor_blog_template' ) ) {
	function woodmart_elementor_blog_template( $settings ) {
		$default_settings = [
			// General.
			'post_type'               => 'post',

			// Query.
			'items_per_page'          => 12,
			'include'                 => '',
			'taxonomies'              => '',
			'offset'                  => '',
			'orderby'                 => 'date',
			'order'                   => 'DESC',
			'meta_key'                => '',
			'exclude'                 => '',

			// Visibility.
			'parts_media'             => true,
			'parts_title'             => true,
			'parts_meta'              => true,
			'parts_text'              => true,
			'parts_btn'               => true,

			// Design.
			'img_size'                => 'medium',
			'blog_design'             => 'default',
			'blog_carousel_design'    => 'masonry',
			'blog_columns'            => [ 'size' => woodmart_get_opt( 'blog_columns' ) ],
			'blog_spacing'            => woodmart_get_opt( 'blog_spacing' ),
			'pagination'              => '',

			// Carousel.
			'speed'                   => '5000',
			'slides_per_view'         => [ 'size' => 3 ],
			'wrap'                    => '',
			'autoplay'                => 'no',
			'hide_pagination_control' => '',
			'hide_prev_next_buttons'  => '',
			'scroll_per_page'         => 'yes',

			// Extra.
			'lazy_loading'            => 'no',
			'scroll_carousel_init'    => 'no',
			'ajax_page'               => '',
			'custom_sizes'            => apply_filters( 'woodmart_blog_shortcode_custom_sizes', false ),
			'elementor'               => true,
		];

		$settings         = wp_parse_args( $settings, $default_settings );
		$encoded_settings = wp_json_encode( array_intersect_key( $settings, $default_settings ) );
		$is_ajax          = woodmart_is_woo_ajax();
		$paged            = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		$id               = uniqid();

		if ( $settings['ajax_page'] > 1 ) {
			$paged = $settings['ajax_page'];
		}

		$query_args = [
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'paged'          => $paged,
			'posts_per_page' => $settings['items_per_page'],
		];

		if ( $settings['post_type'] === 'ids' && $settings['include'] ) {
			$query_args['post__in'] = $settings['include'];
		}

		if ( $settings['exclude'] ) {
			$query_args['post__not_in'] = $settings['exclude'];
		}

		if ( $settings['taxonomies'] ) {
			$taxonomy_names = get_object_taxonomies( 'post' );
			$terms          = get_terms(
				$taxonomy_names,
				[
					'orderby' => 'name',
					'include' => $settings['taxonomies'],
				]
			);

			if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
				$query_args['tax_query'] = array( 'relation' => 'OR' );
				foreach ( $terms as $key => $term ) {
					$query_args['tax_query'][] = [
						'taxonomy'         => $term->taxonomy,
						'field'            => 'slug',
						'terms'            => [ $term->slug ],
						'include_children' => true,
						'operator'         => 'IN',
					];
				}
			}
		}

		if ( $settings['order'] ) {
			$query_args['order'] = $settings['order'];
		}

		if ( $settings['offset'] ) {
			$query_args['offset'] = $settings['offset'];
		}

		if ( $settings['meta_key'] ) {
			$query_args['meta_key'] = $settings['meta_key'];
		}

		if ( $settings['orderby'] ) {
			$query_args['orderby'] = $settings['orderby'];
		}

		$blog_query = new WP_Query( $query_args );

		$settings['blog_columns'] = isset( $settings['blog_columns']['size'] ) ? $settings['blog_columns']['size'] : 3;

		// Loop.
		woodmart_set_loop_prop( 'blog_type', 'shortcode' );
		woodmart_set_loop_prop( 'blog_design', $settings['blog_design'] );
		woodmart_set_loop_prop( 'img_size', $settings['img_size'] );
		woodmart_set_loop_prop( 'img_size_custom', $settings['img_size_custom'] );
		woodmart_set_loop_prop( 'blog_columns', $settings['blog_columns'] );
		woodmart_set_loop_prop( 'woodmart_loop', 0 );
		woodmart_set_loop_prop( 'parts_title', $settings['parts_title'] );
		woodmart_set_loop_prop( 'parts_meta', $settings['parts_meta'] );
		woodmart_set_loop_prop( 'parts_text', $settings['parts_text'] );
		woodmart_set_loop_prop( 'parts_media', $settings['parts_media'] );

		if ( 'carousel' === $settings['blog_design'] ) {
			woodmart_set_loop_prop( 'blog_design', $settings['blog_carousel_design'] );
		}
		if ( ! $settings['parts_btn'] ) {
			woodmart_set_loop_prop( 'parts_btn', false );
		}

		if ( $is_ajax ) {
			ob_start();
		}

		woodmart_enqueue_inline_style( 'blog-base' );

		if ( 'carousel' == $settings['blog_design'] ) {
			$settings['slides_per_view'] = $settings['slides_per_view']['size'];
			return woodmart_generate_posts_slider( $settings, $blog_query );
		} else {
			$wrapper_classes  = '';
			$wrapper_classes .= ' blog-pagination-' . $settings['pagination'];

			// Lazy loading.
			if ( 'yes' == $settings['lazy_loading'] ) {
				woodmart_lazy_loading_init( true );
				woodmart_enqueue_inline_style( 'lazy-loading' );
			}

			if ( 'masonry' === $settings['blog_design'] || 'mask' === $settings['blog_design'] ) {
				wp_enqueue_script( 'imagesloaded' );
				woodmart_enqueue_js_library( 'isotope-bundle' );
				woodmart_enqueue_js_script( 'masonry-layout' );

				$wrapper_classes .= ' masonry-container';
				$wrapper_classes .= ' wd-spacing-' . $settings['blog_spacing'];
				$wrapper_classes .= ' row';
			}

			?>
			<?php if ( ! $is_ajax ) : ?>
				<div class="wd-blog-holder blog-shortcode<?php echo esc_attr( $wrapper_classes ); ?>" id="<?php echo esc_attr( $id ); ?>" data-paged="1" data-atts="<?php echo esc_attr( $encoded_settings ); ?>" data-source="shortcode">
			<?php endif; ?>
				
				<?php while ( $blog_query->have_posts() ) : ?>
					<?php $blog_query->the_post(); ?>
					<?php get_template_part( 'content' ); ?>
				<?php endwhile; ?>

			<?php if ( ! $is_ajax ) : ?>
				</div>
			<?php endif; ?>

			<?php if ( $blog_query->max_num_pages > 1 && ! $is_ajax && $settings['pagination'] ) : ?>
				<div class="blog-footer">
					<?php if ( 'infinit' === $settings['pagination'] || 'more-btn' === $settings['pagination'] ) : ?>
						<?php wp_enqueue_script( 'imagesloaded' ); ?>
						<?php woodmart_enqueue_js_script( 'blog-load-more' ); ?>
						<?php if ( 'infinit' === $settings['pagination'] ) : ?>
							<?php woodmart_enqueue_js_library( 'waypoints' ); ?>
						<?php endif; ?>
						<a href="#" data-holder-id="<?php echo esc_attr( $id ); ?>" rel="nofollow noopener" class="btn wd-load-more wd-blog-load-more load-on-<?php echo 'more-btn' === $settings['pagination'] ? 'click' : 'scroll'; ?>"><span class="load-more-label"><?php esc_html_e( 'Load more posts', 'woodmart' ); ?></span></a>
						<div class="btn wd-load-more wd-load-more-loader"><span class="load-more-loading"><?php esc_html_e('Loading...', 'woodmart'); ?></span></div>
					<?php else : ?>
						<?php query_pagination( $blog_query->max_num_pages ); ?>
					<?php endif ?>
				</div>
			<?php endif; ?>
			<?php
		}

		wp_reset_postdata();
		woodmart_reset_loop();

		// Lazy loading.
		if ( 'yes' == $settings['lazy_loading'] ) {
			woodmart_lazy_loading_deinit();
		}

		if ( $is_ajax ) {
			return array(
				'items'  => ob_get_clean(),
				'status' => $blog_query->max_num_pages > $paged ? 'have-posts' : 'no-more-posts',
			);
		}
	}
}

