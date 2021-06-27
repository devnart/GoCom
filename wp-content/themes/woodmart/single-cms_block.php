<?php
/**
 * The template for displaying all html block.
 *
 * @package xts
 */

if ( ! current_user_can( apply_filters( 'woodmart_html_block_access', 'administrator' ) ) ) {
	wp_die( 'You do not have access.', '', array( 'back_link' => true ) );
}

get_header();

?>
<?php if (  woodmart_is_elementor_installed() && ( woodmart_elementor_is_edit_mode() || woodmart_elementor_is_preview_page() || woodmart_elementor_is_preview_mode() ) ) : ?>
	<div class="wd-html-block-scheme-switcher">
		<div class="wd-html-block-scheme-dark" data-color="#ffffff">
			<?php esc_html_e( 'Dark', 'woodmart' ); ?>
		</div>
	
		<div class="wd-html-block-scheme-light" data-color="#212121">
			<?php esc_html_e( 'Light', 'woodmart' ); ?>
		</div>
	</div>

	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('.wd-html-block-scheme-switcher > div').on('click', function() {
				jQuery('.website-wrapper').css('background-color', jQuery(this).data('color'));
			});
		});
	</script>
<?php endif; ?>

<div class="container">
	<div class="row">
		<div class="site-content col-lg-12 col-12 col-md-12">
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<?php the_content(); ?>
			<?php endwhile; ?>
		</div>
	</div>
</div>

<?php

get_footer();
