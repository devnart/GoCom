<?php
/**
 * The template for displaying 404 pages (Not Found)
 */

get_header(); ?>

<div class="site-content col-12" role="main">

	<header class="page-header">
		<h3 class="title"><?php esc_html_e( 'Not Found', 'woodmart' ); ?></h3>
	</header>

	<div class="page-content">
		<h1><?php esc_html_e( 'This is somewhat embarrassing, isn&rsquo;t it?', 'woodmart' ); ?></h1>
		<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'woodmart' ); ?></p>

		<?php
			woodmart_search_form();
		?>
	</div><!-- .page-content -->

</div><!-- .site-content -->

<?php get_footer(); ?>