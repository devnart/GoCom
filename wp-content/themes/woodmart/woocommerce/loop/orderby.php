<?php
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<form class="woocommerce-ordering<?php if ( ! empty( $list ) ): ?>-list<?php endif; ?>" method="get">
	<?php if ( ! empty( $list ) ): ?>
		<ul>
			<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
				<?php 

					$link = woodmart_shop_page_link( true );

					$link = add_query_arg( 'orderby', $id, $link );

				 ?>
				<li>
					<a href="<?php echo esc_url( $link ); ?>" data-order="<?php echo esc_attr( $id ); ?>" class="<?php if(selected( $orderby, $id, false ) ) echo 'selected-order'; ?>"><?php echo esc_html( $name ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php else: ?>
		<select name="orderby" class="orderby" aria-label="<?php esc_attr_e( 'Shop order', 'woocommerce' ); ?>">
			<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
				<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php wc_query_string_form_fields( null, array( 'orderby', 'submit', 'paged' ) ); ?>
	<?php endif ?>
</form>
