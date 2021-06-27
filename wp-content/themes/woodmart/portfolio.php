<?php

/* Template name: Portfolio */

$filters_type = woodmart_get_opt( 'portfolio_filters_type', 'masonry' );
$filters      = woodmart_get_opt( 'portoflio_filters' );

if ( 'fragments' === woodmart_is_woo_ajax() ) {
	woodmart_get_portfolio_main_loop( true );
	die();
}

if ( ! woodmart_is_woo_ajax() ) {
	get_header();
} else {
	woodmart_page_top_part();
}
?>
	<div class="site-content page-portfolio col-12" role="main">
		<?php if ( have_posts() ) : ?>


			<?php if ( $filters && ( ( 'links' === $filters_type && is_tax() ) || ! is_tax() ) ) : ?>
				<?php woodmart_portfolio_filters( '', $filters_type ); ?>
			<?php endif ?>

			<?php woodmart_get_portfolio_main_loop(); ?>
		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>
	</div>
<?php

if ( ! woodmart_is_woo_ajax() ) {
	get_footer();
} else {
	woodmart_page_bottom_part();
}
