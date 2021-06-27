<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 *
 */

get_header(); ?>


<?php 
	
	// Get content width and sidebar position
	$content_class = woodmart_get_content_class();

?>

<div class="site-content <?php echo esc_attr( $content_class ); ?>" role="main">

		<?php /* The loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<div class="entry-content">
						<?php echo woodmart_get_the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'woodmart' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
					</div>

					<?php woodmart_entry_meta(); ?>

				</article><!-- #post -->

				<?php 
					// If comments are open or we have at least one comment, load up the comment template.
					if ( woodmart_get_opt('page_comments') && (comments_open() || get_comments_number()) ) :
						comments_template();
					endif;
				 ?>

		<?php endwhile; ?>

</div><!-- .site-content -->


<?php get_sidebar(); ?>

<?php get_footer(); ?>
