<?php
/**
 * Displayed when no products are found matching the current query.
 *
 * Override this template by copying it to yourtheme/woocommerce/loop/no-products-found.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<p class="woocommerce-info"><?php esc_html_e( 'No products were found matching your selection.', 'woocommerce' ); ?></p>

<div class="no-products-footer">
	<?php get_product_search_form(); ?>
</div>

<?php do_action( 'woodmart_after_no_product_found' ); ?>