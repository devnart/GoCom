<?php
/**
 * Portfolio templates functions
 *
 * @package woodmart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'woodmart_set_projects_per_page' ) ) {
	/**
	 * Portfolio projects per page.
	 *
	 * @since 1.0.0
	 *
	 * @param object $query Query.
	 */
	function woodmart_set_projects_per_page( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( $query->is_post_type_archive( 'portfolio' ) || $query->is_tax( 'project-cat' ) ) {
			$query->set( 'posts_per_page', (int) woodmart_get_opt( 'portoflio_per_page' ) );
			$query->set( 'orderby', woodmart_get_opt( 'portoflio_orderby' ) );
			$query->set( 'order', woodmart_get_opt( 'portoflio_order' ) );
		}
	}

	add_action( 'pre_get_posts', 'woodmart_set_projects_per_page' );
}

if ( ! function_exists( 'woodmart_get_portfolio_main_loop' ) ) {
	/**
	 * Main portfolio loop
	 *
	 * @since 1.0.0
	 *
	 * @param boolean $fragments Fragments.
	 */
	function woodmart_get_portfolio_main_loop( $fragments = false ) {
		global $paged, $wp_query;

		$max_page   = $wp_query->max_num_pages;
		$style      = woodmart_get_opt( 'portoflio_style' );
		$spacing    = woodmart_get_opt( 'portfolio_spacing' );
		$pagination = woodmart_get_opt( 'portfolio_pagination' );

		wp_enqueue_script( 'imagesloaded' );
		woodmart_enqueue_js_library( 'isotope-bundle' );
		woodmart_enqueue_js_script( 'masonry-layout' );

		if ( 'parallax' === $style ) {
			woodmart_enqueue_js_library( 'panr-parallax-bundle' );
			woodmart_enqueue_js_script( 'portfolio-effect' );
		}

		if ( is_search() ) {
			$pagination = 'links';
		}

		if ( $fragments && isset( $_GET['loop'] ) ) { // phpcs:ignore
			woodmart_set_loop_prop( 'portfolio_loop', (int) sanitize_text_field( $_GET['loop'] ) ); // phpcs:ignore
		}

		if ( $fragments ) {
			ob_start();
		}

		?>

		<?php if ( woodmart_get_opt( 'ajax_portfolio' ) && ! $fragments ) : ?>
			<?php woodmart_enqueue_js_script( 'shop-loader' ); ?>
			<div class="wd-sticky-loader"><span class="wd-loader"></span></div>
		<?php endif; ?>

		<?php if ( ! $fragments ) : ?>
			<div class="masonry-container wd-portfolio-holder row wd-spacing-<?php echo esc_attr( $spacing ); ?>" data-source="main_loop" data-source="shortcode" data-paged="1">
		<?php endif ?>

		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<?php get_template_part( 'content', 'portfolio' ); ?>
		<?php endwhile; ?>

		<?php if ( ! $fragments ) : ?>
			</div>
		<?php endif ?>

		<?php if ( $fragments ) : ?>
			<?php $output = ob_get_clean(); ?>
		<?php endif; ?>

		<?php if ( $max_page > 1 && ! $fragments ) : ?>
			<?php wp_enqueue_script( 'imagesloaded' ); ?>
			<?php woodmart_enqueue_js_script( 'portfolio-load-more' ); ?>
			<?php woodmart_enqueue_js_library( 'waypoints' ); ?>
			<div class="portfolio-footer">
				<?php if ( 'infinit' === $pagination || 'load_more' === $pagination ) : ?>
					<a href="<?php echo esc_url( add_query_arg( 'woo_ajax', '1', next_posts( $max_page, false ) ) ); ?>" rel="nofollow noopener" class="btn wd-load-more wd-portfolio-load-more load-on-<?php echo 'load_more' === $pagination ? 'click' : 'scroll'; ?>">
						<?php esc_html_e( 'Load more projects', 'woodmart' ); ?>
					</a>
					<div class="btn wd-load-more wd-load-more-loader">
						<span class="load-more-loading">
							<?php esc_html_e( 'Loading...', 'woodmart' ); ?>
						</span>
					</div>
				<?php else : ?>
					<?php query_pagination( $max_page ); ?>
				<?php endif ?>
			</div>
		<?php endif; ?>

		<?php

		if ( $fragments ) {
			$output = array(
				'items'       => $output,
				'status'      => ( $max_page > $paged ) ? 'have-posts' : 'no-more-posts',
				'nextPage'    => add_query_arg( 'woo_ajax', '1', next_posts( $max_page, false ) ),
				'currentPage' => strtok( woodmart_get_current_url(), '?' ),
			);

			echo wp_json_encode( $output );
		}
	}
}

if ( ! function_exists( 'woodmart_portfolio_filters' ) ) {
	/**
	 * Generate portfolio filters
	 *
	 * @since 1.0.0
	 *
	 * @param string $category Parent category.
	 * @param string $type     Filters type.
	 */
	function woodmart_portfolio_filters( $category, $type ) {
		$categories = get_terms( 'project-cat', array( 'parent' => $category ) );

		if ( is_wp_error( $categories ) || ! $categories ) {
			return;
		}

		$wrapper_classes  = '';
		$all_link_classes = '';
		$wrapper_classes .= ' wd-type-' . $type;

		if ( 'masonry' === $type ) {
			woodmart_enqueue_js_script( 'portfolio-wd-nav-portfolios' );
			$all_link_url      = '#';
			$all_link_classes .= ' wd-active';
		} else {
			$all_link_url = get_post_type_archive_link( 'portfolio' );

			if ( is_post_type_archive( 'portfolio' ) || ! is_tax( 'project-cat' ) ) {
				$all_link_classes .= ' wd-active';
			}
		}

		?>
		<div class="portfolio-filter wd-nav-wrapper wd-mb-action-swipe text-center<?php echo esc_attr( $wrapper_classes ); ?>">
			<ul class="wd-nav-portfolio wd-nav wd-style-underline<?php echo woodmart_get_old_classes( ' masonry-filter' ); ?>">
				<li data-filter="*" class="<?php echo esc_attr( $all_link_classes ); ?>">
					<a href="<?php echo esc_url( $all_link_url ); ?>">
						<span class="nav-link-text"><?php esc_html_e( 'All', 'woodmart' ); ?></span>
					</a>
				</li>

				<?php foreach ( $categories as $category ) : ?>
					<?php
					$link_classes = '';
					$current_tax  = get_queried_object();

					if ( 'masonry' === $type ) {
						$link_url = '#';
					} else {
						$link_url = get_term_link( $category->term_id );

						if ( is_tax( 'project-cat' ) && $category->term_id === $current_tax->term_id ) {
							$link_classes .= ' wd-active';
						}
					}

					?>
					<li data-filter=".proj-cat-<?php echo esc_attr( $category->slug ); ?>" class="<?php echo esc_attr( trim( $link_classes ) ); ?>">
						<a href="<?php echo esc_url( $link_url ); ?>">
							<span class="nav-link-text"><?php echo esc_html( $category->name ); ?></span>
						</a>
					</li>
				<?php endforeach ?>
			</ul>
		</div>
		<?php
	}
}

