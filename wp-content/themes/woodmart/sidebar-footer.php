<?php
/**
 * The Footer Sidebar
 */

if ( ! is_active_sidebar( 'footer-1' ) && ! is_active_sidebar( 'footer-2' ) && ! is_active_sidebar( 'footer-3' ) && ! is_active_sidebar( 'footer-4' ) && ! is_active_sidebar( 'footer-5' ) && ! is_active_sidebar( 'footer-6' ) && ! is_active_sidebar( 'footer-7' ) ) {
	return;
}

$footer_layout = woodmart_get_opt( 'footer-layout' );

$footer_config = woodmart_get_footer_config( $footer_layout );

if( count( $footer_config['cols'] ) > 0 ) {
	?>
	<div class="container main-footer">
		<aside class="footer-sidebar widget-area row" role="complementary">
			<?php
				foreach ( $footer_config['cols'] as $key => $columns ) {
					$index = $key + 1;
					?>
						<div class="footer-column footer-column-<?php echo esc_attr( $index ); ?> <?php echo esc_attr( $columns ); ?>">
							<?php dynamic_sidebar( 'footer-' . $index ); ?>
						</div>
						<?php if ( isset( $footer_config['clears'][$index] ) ): ?>
							<div class="clearfix visible-<?php echo esc_attr( $footer_config['clears'][$index] ); ?>-block"></div>
						<?php endif ?>
					<?php
				}
			?>
		</aside><!-- .footer-sidebar -->
	</div>
	<?php
}

?>

