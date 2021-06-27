<?php
/**
 * Portfolio template function
 *
 * @package xts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'woodmart_elementor_portfolio_template' ) ) {
	function woodmart_elementor_portfolio_template( $settings ) {
		if ( woodmart_get_opt( 'disable_portfolio' ) ) {
			 return '';
		}

		$default_settings = [
			'posts_per_page' => woodmart_get_opt( 'portoflio_per_page' ),
			'filters'        => false,
			'filters_type'   => 'masonry',
			'categories'     => '',
			'style'          => woodmart_get_opt( 'portoflio_style' ),
			'columns'        => woodmart_get_opt( 'projects_columns' ),
			'spacing'        => woodmart_get_opt( 'portfolio_spacing' ),
			'pagination'     => woodmart_get_opt( 'portfolio_pagination' ),
			'ajax_page'      => '',
			'orderby'        => woodmart_get_opt( 'portoflio_orderby' ),
			'order'          => woodmart_get_opt( 'portoflio_order' ),
			'layout'         => 'grid',
			'lazy_loading'   => 'no',
			'elementor'      => true,
			'custom_sizes'   => apply_filters( 'woodmart_portfolio_shortcode_custom_sizes', false ),
			'image_size'     => 'large',
		];

		$settings            = wp_parse_args( $settings, $default_settings );
		$settings['columns'] = isset( $settings['columns']['size'] ) ? $settings['columns']['size'] : $settings['columns'];
		$encoded_settings    = wp_json_encode( array_intersect_key( $settings, $default_settings ) );
		$is_ajax             = woodmart_is_woo_ajax();
		$paged               = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

		if ( $settings['ajax_page'] > 1 ) {
			$paged = $settings['ajax_page'];
		}

		$s = false;

		if ( isset( $_REQUEST['s'] ) ) {
			$s = sanitize_text_field( $_REQUEST['s'] );
		}

		$args = array(
			'post_type'      => 'portfolio',
			'posts_per_page' => $settings['posts_per_page'],
			'orderby'        => $settings['orderby'],
			'order'          => $settings['order'],
			'paged'          => $paged,
		);

		if ( $s ) {
			$args['s'] = $s;
		}

		if ( '' != get_query_var( 'project-cat' ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'project-cat',
					'field'    => 'slug',
					'terms'    => get_query_var( 'project-cat' ),
				),
			);
		}

		if ( $settings['categories'] ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'project-cat',
					'field'    => 'term_id',
					'operator' => 'IN',
					'terms'    => $settings['categories'],
				),
			);
		}

		if ( ! $settings['style'] || ( 'inherit' === $settings['style'] ) ) {
			$settings['style'] = woodmart_get_opt( 'portoflio_style' );
		}

		woodmart_set_loop_prop( 'portfolio_style', $settings['style'] );
		woodmart_set_loop_prop( 'portfolio_column', $settings['columns'] );
		woodmart_set_loop_prop( 'portfolio_image_size', $settings['image_size'] );
		woodmart_set_loop_prop( 'portfolio_image_size_custom', $settings['image_size_custom'] );

		if ( 'parallax' === $settings['style'] ) {
			woodmart_enqueue_js_library( 'panr-parallax-bundle' );
			woodmart_enqueue_js_script( 'portfolio-effect' );
		}

		$query = new WP_Query( $args );

		wp_enqueue_script( 'imagesloaded' );
		woodmart_enqueue_js_library( 'isotope-bundle' );
		woodmart_enqueue_js_script( 'masonry-layout' );

		if ( $is_ajax ) {
			ob_start();
		}

		if ( 'yes' === $settings['lazy_loading'] ) {
			woodmart_lazy_loading_init( true );
			woodmart_enqueue_inline_style( 'lazy-loading' );
		}

		woodmart_enqueue_inline_style( 'portfolio-base' );

		?>
		<?php if ( $query->have_posts() ) : ?>
			<?php if ( ! $is_ajax ) : ?>
				<?php if ( ! is_tax() && $settings['filters'] && ! $s && 'carousel' !== $settings['layout'] ) : ?>
					<?php woodmart_portfolio_filters( $settings['categories'], $settings['filters_type'] ); ?>
				<?php endif ?>

				<div class="<?php echo $settings['layout'] !== 'carousel' ? 'masonry-container' : ''; ?> wd-portfolio-holder row wd-spacing-<?php echo esc_attr( $settings['spacing'] ); ?>" data-atts="<?php echo esc_attr( $encoded_settings ); ?>" data-source="shortcode" data-paged="1">
			<?php endif ?>

			<?php if ( 'carousel' === $settings['layout'] ) : ?>
				<?php echo woodmart_generate_posts_slider( $settings, $query ); ?>
			<?php else : ?>
				<?php while ( $query->have_posts() ) : ?>
					<?php $query->the_post(); ?>
					<?php get_template_part( 'content', 'portfolio' ); ?>
				<?php endwhile; ?>
			<?php endif; ?>
			<?php if ( ! $is_ajax ) : ?>
			</div>

				<?php if ( $query->max_num_pages > 1 && 'disable' !== $settings['pagination'] && 'carousel' !== $settings['layout'] ) : ?>
					<?php wp_enqueue_script( 'imagesloaded' ); ?>
					<?php woodmart_enqueue_js_script( 'portfolio-load-more' ); ?>
					<?php woodmart_enqueue_js_library( 'waypoints' ); ?>
				<div class="portfolio-footer">
					<?php if ( 'infinit' === $settings['pagination'] || 'load_more' === $settings['pagination'] ) : ?>
					<a href="#" rel="nofollow noopener" class="btn wd-load-more wd-portfolio-load-more load-on-<?php echo $settings['pagination'] === 'load_more' ? 'click' : 'scroll'; ?>"><span class="load-more-label"><?php esc_html_e( 'Load more projects', 'woodmart' ); ?></span></a>
					<div class="btn wd-load-more wd-load-more-loader"><span class="load-more-loading"><?php esc_html_e( 'Loading...', 'woodmart' ); ?></span></div>
					<?php else : ?>
						<?php query_pagination( $query->max_num_pages ); ?>
					<?php endif ?>
				</div>
			<?php endif ?>
		<?php endif ?>

		<?php elseif ( ! $is_ajax ) : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>
		<?php

		if ( 'yes' === $settings['lazy_loading'] ) {
			woodmart_lazy_loading_deinit();
		}

		wp_reset_postdata();

		woodmart_reset_loop();

		if ( $is_ajax ) {
			return array(
				'items'  => ob_get_clean(),
				'status' => $query->max_num_pages > $paged ? 'have-posts' : 'no-more-posts',
			);
		}
	}
}
