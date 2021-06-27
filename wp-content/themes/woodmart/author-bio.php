<?php
/**
 * The template for displaying Author bios
 */

if( ! woodmart_get_opt( 'blog_author_bio' ) ) return;
?>

<div class="author-info">
	<div class="author-avatar">
		<?php
		/**
		 * Filter the author bio avatar size.
		 *
		 * @since Twenty Thirteen 1.0
		 *
		 * @param int $size The avatar height and width size in pixels.
		 */
		$author_bio_avatar_size = apply_filters( 'twentythirteen_author_bio_avatar_size', 74 );
		echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size, '', 'author-avatar' );
		?>
	</div><!-- .author-avatar -->
	<div class="author-description">
		<h4 class="author-title"><?php printf( esc_html__( 'About %s', 'woodmart' ), get_the_author() ); ?></h4>
		<p class="author-area-info">
			<?php the_author_meta( 'description' ); ?>
			<a class="author-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
				<?php printf( wp_kses( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'woodmart' ), array( 'span' => array('class') ) ), get_the_author() ); ?>
			</a>
		</p>
	</div><!-- .author-description -->
</div><!-- .author-info -->