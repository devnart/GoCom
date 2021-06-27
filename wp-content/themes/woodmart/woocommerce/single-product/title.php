<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woothemes.com/document/template-structure/
 * @author     WooThemes
 * @package    WooCommerce/Templates
 * @version    4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$is_quick_view = woodmart_loop_prop( 'is_quick_view' );

?>

<h1 itemprop="name" class="product_title wd-entities-title"><?php if( $is_quick_view ): ?><a href="<?php the_permalink(); ?>"><?php endif; ?><?php echo get_the_title(); ?><?php if( $is_quick_view ): ?></a><?php endif; ?></h1>