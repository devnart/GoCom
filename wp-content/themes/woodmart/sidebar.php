<?php
/**
 * The sidebar containing the secondary widget area
 *
 * Displays on posts and pages.
 *
 * If no active widgets are in this sidebar, hide it completely.
 */

$sidebar_class = woodmart_get_sidebar_class();

$sidebar_name = woodmart_get_sidebar_name();

if ( strstr( $sidebar_class, 'col-lg-0' ) ) {
	return;
}

?>
<?php if ( woodmart_get_opt( 'shop_hide_sidebar' ) || woodmart_get_opt( 'shop_hide_sidebar_tablet' ) || woodmart_get_opt( 'shop_hide_sidebar_desktop' ) || woodmart_get_opt( 'hide_main_sidebar_mobile' ) ) : ?>
	<?php woodmart_enqueue_inline_style( 'off-canvas-sidebar' ); ?>
<?php endif; ?>

<aside class="sidebar-container <?php echo esc_attr( $sidebar_class ); ?> area-<?php echo esc_attr( $sidebar_name ); ?>" role="complementary">
	<?php if ( woodmart_get_opt( 'shop_hide_sidebar' ) || woodmart_get_opt( 'shop_hide_sidebar_tablet' ) || woodmart_get_opt( 'shop_hide_sidebar_desktop' ) || woodmart_get_opt( 'hide_main_sidebar_mobile' ) ) : ?>
		<div class="widget-heading">
			<div class="close-side-widget wd-action-btn wd-style-text wd-cross-icon">
				<a href="#" rel="nofollow noopener"><?php esc_html_e( 'close', 'woodmart' ); ?></a>
			</div>
		</div>
	<?php endif; ?>
	<div class="widget-area">
		<?php do_action( 'woodmart_before_sidebar_area' ); ?>
		<?php dynamic_sidebar( $sidebar_name ); ?>
		<?php do_action( 'woodmart_after_sidebar_area' ); ?>
	</div><!-- .widget-area -->
</aside><!-- .sidebar-container -->
