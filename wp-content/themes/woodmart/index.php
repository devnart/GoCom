<?php
/**
 * The main template file
 */

if( woodmart_is_woo_ajax() ) {
	do_action( 'woodmart_main_loop' );
	die();
}

get_header(); ?>

<?php 

	// Get content width and sidebar position
	$content_class = woodmart_get_content_class();

?>

<div class="site-content <?php echo esc_attr( $content_class ); ?>" role="main">

	<?php do_action( 'woodmart_main_loop' ); ?>

</div><!-- .site-content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
