<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product; ?>

<li class="small-product-content">
	<div class="product-pseudo-wrap"></div>
	<div class="product-small-inner">
		<a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
			<?php echo wp_kses( $product->get_image(), array( 'img' => array( 'class' => true, 'width' => true, 'height' => true, 'src' => true,'alt' => true, 'data-wood-src' => true, 'data-srcset' => true, 'srcset' => true ) ) ); ?>
		</a>
		<div class="small-product-info">
			<span class="wd-entities-title"><?php echo esc_html( $product->get_title() ); ?></span>
			<span class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
		</div>
	</div>
</li>
