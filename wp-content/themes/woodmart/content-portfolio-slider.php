<?php
/**
 * The default template for displaying content
 */

$size = woodmart_loop_prop( 'portfolio_image_size' );

if ( woodmart_is_elementor_installed() ) {
	$custom_sizes = woodmart_loop_prop( 'portfolio_image_size_custom' );

	$img = woodmart_get_image_html( // phpcs:ignore
		array(
			'image_size'             => $size,
			'image_custom_dimension' => $custom_sizes,
			'image'                  => array(
				'id' => get_post_thumbnail_id(),
			),
		),
		'image'
	);
} elseif ( function_exists( 'wpb_getImageBySize' ) ) {
	$img = wpb_getImageBySize(
		array(
			'attach_id'  => get_post_thumbnail_id(),
			'thumb_size' => $size,
		)
	);

	$img = isset( $img['thumbnail'] ) ? $img['thumbnail'] : '';
} else {
	$img = get_the_post_thumbnail( $post->ID, $size );
}

$classes[] = 'portfolio-entry';

$columns = woodmart_loop_prop( 'portfolio_column' );
$style   = woodmart_loop_prop( 'portfolio_style' );

$classes[] = 'portfolio-single';

$cats = wp_get_post_terms( get_the_ID(), 'project-cat' );

if( ! empty( $cats ) ) {
	foreach ($cats as $key => $cat) {
		$classes[] = 'proj-cat-' . $cat->slug;
	}
}

$classes[] = 'portfolio-' . $style;

woodmart_enqueue_js_library( 'photoswipe-bundle' );
woodmart_enqueue_inline_style( 'photoswipe' );
woodmart_enqueue_js_script( 'portfolio-photoswipe' );

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
	<header class="entry-header">
		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="entry-thumbnail">
				<a href="<?php echo esc_url( get_permalink() ); ?>" class="portfolio-thumbnail">
					<?php echo $img; ?>
				</a>
				<a href="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id($post->ID) ) ); ?>" class="portfolio-enlarge" data-elementor-open-lightbox="no"><?php esc_html_e('View Large', 'woodmart'); ?></a>
				<?php if ( woodmart_is_social_link_enable( 'share' ) ): ?>
					<div class="social-icons-wrapper">
					<?php if( function_exists( 'woodmart_shortcode_social' ) ) echo woodmart_shortcode_social( array( 'size' => 'small', 'style' => 'default', 'color' => 'light' ) ); ?>
					</div>
				<?php endif ?>
			</figure>
		<?php endif; ?>

		<div class="portfolio-info">
			
			<?php 

				if( ! empty( $cats ) ) {
					?>
					<div class="wrap-meta">
						<ul class="proj-cats-list">
						<?php
						foreach ($cats as $key => $cat) {
							$classes[] = 'proj-cat-' . $cat->slug;
							// get_term_link( $cat, 'project-cat' ); 
							?>
								<li><?php echo esc_html($cat->name); ?></li>
							<?php
						}
						?>
						</ul>
					</div>
					<?php
				}

			 ?>
			 
			<div class="wrap-title">
				<h3 class="wd-entities-title">
					<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php the_title(); ?></a>
				</h3>
			</div>
		 </div>
	 </header>

</article><!-- #post -->
